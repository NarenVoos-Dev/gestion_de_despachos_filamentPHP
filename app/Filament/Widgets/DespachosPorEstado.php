<?php

namespace App\Filament\Widgets;

use App\Models\Despacho;
use Filament\Widgets\ChartWidget;

class DespachosPorEstado extends ChartWidget
{
    protected static ?string $heading = 'Despachos por Estado';

    protected static bool $isLazy = false;
    protected int | string | array $columnSpan = 1;

    protected function getData(): array
    {
        // Contar los despachos agrupados por estado
        $estados = Despacho::selectRaw('estado, COUNT(*) as total')
            ->groupBy('estado')
            ->pluck('total', 'estado');

        return [
            'datasets' => [
                [
                    'label' => 'Despachos',
                    'data' => $estados->values()->all(),
                    'backgroundColor' => [
                        '#facc15', // Amarillo (Pendiente)
                        '#3b82f6', // Azul (Enviado)
                        '#10b981', // Verde (Entregado)
                        '#f87171', // Rojo (Cancelado)
                        '#a78bfa', // Morado
                    ],
                ],
            ],
            'labels' => $estados->keys()->map(fn ($estado) => ucfirst($estado))->all(),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut'; // Cambia a 'bar' si prefieres barras
    }
}
