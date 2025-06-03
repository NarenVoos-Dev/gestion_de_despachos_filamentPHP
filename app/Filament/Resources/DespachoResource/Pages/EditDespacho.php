<?php

namespace App\Filament\Resources\DespachoResource\Pages;

use App\Filament\Resources\DespachoResource;
use Illuminate\Support\Facades\Auth;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDespacho extends EditRecord
{
    protected static string $resource = DespachoResource::class;

    protected function getHeaderActions(): array
    {
        if (Auth::user()?->hasRole('administrador')) {
            return [
                Actions\DeleteAction::make(),
            ];
        }

        return [];
    
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
