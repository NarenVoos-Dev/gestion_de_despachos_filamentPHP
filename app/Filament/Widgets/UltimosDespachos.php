<?php

namespace App\Filament\Widgets;

use App\Models\Despacho;
use Filament\Tables;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class UltimosDespachos extends BaseWidget
{
    protected static ?string $heading = 'Últimos Despachos Creados';
    protected static bool $isLazy = false;
    protected int | string | array $columnSpan = 2;
    protected int $limite = 5; // Puedes cambiar 10 por 5 o 20 si lo deseas

    protected function getTableQuery(): Builder
    {
        return Despacho::latest()->limit($this->limite);
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('fecha')
                ->label('Fecha de creación')
                ->date()
                ->alignCenter()
                ->sortable(),
            Tables\Columns\TextColumn::make('fecha_entrega')
                ->date()
                ->alignCenter()
                ->sortable(),
            Tables\Columns\TextColumn::make('orden_compra')
                ->alignCenter()
                ->searchable(),
            Tables\Columns\TextColumn::make('orden_pedido')
                ->alignCenter()
                ->searchable(),
            Tables\Columns\TextColumn::make('factura')
                ->label('Factura')
                ->alignCenter()
                ->searchable(),
            Tables\Columns\TextColumn::make('cliente.nombre')
                ->label('Cliente')
                ->alignCenter()
                ->searchable()
                ->numeric(),
            Tables\Columns\TextColumn::make('cliente.nombre')
                ->label('Cliente')
                ->searchable(),

            Tables\Columns\TextColumn::make('fecha')
                ->label('Fecha')
                ->date(),

            Tables\Columns\BadgeColumn::make('estado')
                ->label('Estado')
                ->colors([
                    'primary' => 'pendiente',
                    'info' => 'enviado',
                    'success' => 'entregado',
                    'danger' => 'cancelado',
                ])
                ->sortable(),

            Tables\Columns\TextColumn::make('transportadora.nombre')
                ->label('Transportadora')
                ->default('-'),
        ];
    }

    protected function getColumns(): int
    {
        return 2; // Muestra ambos stats en una fila
    }

}