<?php

namespace App\Filament\Widgets;

use App\Models\Despacho;
use App\Models\Producto;
use Filament\Widgets\TableWidget;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class TotalVentas extends TableWidget
{
    protected static ?string $heading = 'Total de ventas por Producto';
    //protected static ?int $columnSpan = 'full';
     protected int | string | array $columnSpan = 1;

    public $fecha_inicio;
    public $fecha_fin;
    public array $estados_seleccionados = [];

    protected function getTableQuery(): Builder
    {
        return Despacho::query()
            ->select([
                'producto_id',
                DB::raw('productos.nombre as producto_nombre'),
                DB::raw('SUM(cantidad_pedido) as total_cantidad'),
                DB::raw('SUM(valor_total) as total_valor')
            ])
            ->join('productos', 'productos.id', '=', 'despachos.producto_id')
            ->when($this->fecha_inicio, fn($q) => $q->whereDate('fecha', '>=', Carbon::parse($this->fecha_inicio)))
            ->when($this->fecha_fin, fn($q) => $q->whereDate('fecha', '<=', Carbon::parse($this->fecha_fin)))
            ->when($this->estados_seleccionados, fn($q) => $q->whereIn('estado', $this->estados_seleccionados))
            ->groupBy('producto_id', 'productos.nombre')
            ->orderBy('productos.nombre');
    }

    public function getTableRecordKey(\Illuminate\Database\Eloquent\Model $record): string
    {
        return (string) ($record->producto_id ?? '0');
    }

    protected function getTableHeaderActions(): array
    {
        return [
            Tables\Actions\Action::make('Filtrar')
                ->form([
                    \Filament\Forms\Components\DatePicker::make('fecha_inicio')
                        ->label('Fecha Inicio')
                        ->default(now()->subMonth()),
                        
                    \Filament\Forms\Components\DatePicker::make('fecha_fin')
                        ->label('Fecha Fin')
                        ->default(now()),
                        
                    \Filament\Forms\Components\CheckboxList::make('estados_seleccionados')
                        ->label('Estados a Incluir')
                        ->options([
                            'pendiente' => 'Pendiente',
                            'enviado' => 'Enviado',
                            'entregado' => 'Entregado',
                            'cancelado' => 'Cancelado',
                        ])
                        ->default(['pendiente', 'enviado', 'entregado'])
                        ->columns(4)
                ])
                ->action(function(array $data) {
                    $this->fecha_inicio = $data['fecha_inicio'];
                    $this->fecha_fin = $data['fecha_fin'];
                    $this->estados_seleccionados = $data['estados_seleccionados'];
                    $this->dispatch('refresh'); // Cambiado de emitSelf a dispatch
                }),

                Tables\Actions\Action::make('Exportar PDF')
                ->color('danger')
                ->icon('heroicon-o-printer')
                ->action(function () {
                    $data = $this->getTableQuery()->get();
                    $filtros = [
                        'fecha_inicio' => $this->fecha_inicio,
                        'fecha_fin' => $this->fecha_fin,
                        'estados' => $this->estados_seleccionados
                    ];
                    
                    $pdf = Pdf::loadView('pdf.despachos', [
                        'data' => $data,
                        'filtros' => $filtros,
                        'title' => 'Reporte de Ventas por Producto'
                    ]);
                    
                    return response()->streamDownload(
                        fn () => print($pdf->output()),
                        'ventas_por_producto_'.now()->format('Y-m-d').'.pdf'
                    );
                })
        ];
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('producto_nombre')
                ->label('Producto')
                ->sortable(),
                
            Tables\Columns\TextColumn::make('total_cantidad')
                ->label('Cantidad Total')
                ->numeric()
                ->sortable()
                ->summarize([
                    Tables\Columns\Summarizers\Sum::make()
                        ->label('Total')
                        ->numeric(),
                ]),
                
            Tables\Columns\TextColumn::make('total_valor')
                ->label('Valor Total (COP)')
                ->money('COP')
                ->sortable()
                ->summarize([
                    Tables\Columns\Summarizers\Sum::make()
                        ->label('Total')
                        ->money('COP'),
                ]),
        ];
    }

    protected function getTableRecordsPerPageSelectOptions(): array
    {
        return [10, 25, 50, 100];
    }

}