<?php

namespace App\Exports;

use App\Models\Despacho;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DespachosExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Despacho::with(['cliente', 'transportadora']) // si usas relaciones
            ->get()
            ->map(function ($despacho) {
                return [
                    'ID' => $despacho->id,
                    'Cliente' => $despacho->cliente->nombre ?? '',
                    'Fecha' => $despacho->fecha,
                    'Estado' => $despacho->estado,
                    'Transportadora' => $despacho->transportadora->nombre ?? '',
                    // Agrega más campos si lo deseas
                ];
            });
    }

    public function headings(): array
    {
        return [
            'ID',
            'Cliente',
            'Fecha',
            'Estado',
            'Transportadora',
        ];
    }
}
