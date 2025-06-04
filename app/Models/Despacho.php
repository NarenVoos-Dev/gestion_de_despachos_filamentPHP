<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Cliente;
use App\Models\Producto;
use App\Models\Transportadora;
use App\Models\User;

class Despacho extends Model
{
    use HasFactory;

    protected $fillable = [
        'fecha',
        'fecha_entrega',
        'orden_compra',
        'orden_pedido',
        'factura',
        'cliente_id',
        'ciudad',
        'cantidad_pedido',
        'producto_id',
        'empresa',
        'transportadora_id',
        'guia',
        'valor_unitario',
        'valor_total',
        'mes',
        'adjunto',
        'estado',
        'novedad_factura',
        'creado_por',
        'actualizado_por',
    ];

    // Relaciones
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    public function transportadora()
    {
        return $this->belongsTo(Transportadora::class);
    }

    public function creador()
    {
        return $this->belongsTo(User::class, 'creado_por');
    }

    public function editor()
    {
        return $this->belongsTo(User::class, 'actualizado_por');
    }
    public function ciudad()
    {
        return $this->belongsTo(Ciudades::class);
    }

    // Registrar quién crea o edita
    protected static function booted()
    {
        static::creating(function ($model) {
            $model->creado_por = auth()->id();
        });

        static::updating(function ($model) {
            $model->actualizado_por = auth()->id();
        });
    }
}

