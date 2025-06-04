<?php

namespace App\Filament\Widgets;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget;
use App\Models\Producto;
use App\Models\Despacho;


class TotalDatosBase extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total de Despachos', Despacho::count())
                ->icon('heroicon-o-truck')
                ->color('primary')
                ->description('Cantidad total registrada'),

            
            Stat::make('Total de Productos', Producto::count())
                ->icon('heroicon-o-cube')
                ->color('success')
                ->description('Cantidad total de productos disponibles'),
        ];
    }
    
    protected function getColumns(): int
    {
        return 2; // Muestra ambos stats en una fila
    }
}