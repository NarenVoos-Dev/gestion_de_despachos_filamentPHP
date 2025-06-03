<?php

namespace App\Filament\Resources\TransportadoraResource\Pages;

use App\Filament\Resources\TransportadoraResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTransportadora extends CreateRecord
{
    protected static string $resource = TransportadoraResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
