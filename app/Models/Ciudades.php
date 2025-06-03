<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Ciudades extends Model
{
    use HasFactory;

    protected $fillable = ['nombre'];

    public function despachos()
    {
        return $this->hasMany(Despacho::class);
    }
}
