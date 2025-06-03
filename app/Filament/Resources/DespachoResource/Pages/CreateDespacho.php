<?php

namespace App\Filament\Resources\DespachoResource\Pages;

use App\Filament\Resources\DespachoResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateDespacho extends CreateRecord
{
    protected static string $resource = DespachoResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
