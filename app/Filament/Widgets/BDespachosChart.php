<?php

namespace App\Filament\Widgets;

use App\Models\Despacho;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class DespachosChart extends ChartWidget
{
    protected static ?string $heading = 'Despachos por Fecha';

    protected static bool $isLazy = false;
    protected static bool $hasForm = true;

    public ?array $data = [];
     protected int | string | array $columnSpan = 2;
    protected function getForm(): Form
    {
        return Forms\Form::make()
            ->schema([
                Forms\Components\DatePicker::make('fecha_inicio')
                    ->label('Desde')
                    ->default(Carbon::now()->startOfMonth()),

                Forms\Components\DatePicker::make('fecha_fin')
                    ->label('Hasta')
                    ->default(Carbon::now()->endOfMonth()),
            ])
            ->statePath('data');
    }

    protected function getData(): array
    {
        $inicio = $this->data['fecha_inicio'] ?? Carbon::now()->startOfMonth()->toDateString();
        $fin = $this->data['fecha_fin'] ?? Carbon::now()->endOfMonth()->toDateString();

        $data = Trend::model(Despacho::class)
            ->between(Carbon::parse($inicio), Carbon::parse($fin))
            ->perDay()
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Despachos',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate)->all(),
                    'backgroundColor' => '#4f46e5',
                    'borderColor' => '#4f46e5',
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => Carbon::parse($value->date)->format('d M'))->all(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
    protected function getColumns(): int
    {
        return 2; // Muestra ambos stats en una fila
    }
}
