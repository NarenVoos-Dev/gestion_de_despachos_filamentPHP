<?php

namespace App\Filament\Resources\DespachoResource\Pages;

use App\Exports\DespachosExport;
use App\Filament\Resources\DespachoResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class ListDespachos extends ListRecords
{
    protected static string $resource = DespachoResource::class;

    protected function getHeaderActions(): array
    {
        if (Auth::user()?->hasRole('administrador')) {
            return [
                Actions\CreateAction::make(),

                Action::make('Exportar Excel')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->action(function () {
                        return Excel::download(new DespachosExport, 'despachos.xlsx');
                    }),
            ];
        }

        return [];
    }
}
