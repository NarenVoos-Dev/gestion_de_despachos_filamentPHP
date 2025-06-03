<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('despachos', function (Blueprint $table) {
            $table->id();
            $table->date('fecha')->nullable();
            $table->date('fecha_entrega')->nullable();
            $table->string('orden_compra')->nullable();
            $table->string('orden_pedido')->nullable();
            $table->string('factura')->nullable();

            $table->foreignId('cliente_id')->constrained()->cascadeOnDelete();
            $table->string('ciudad')->nullable();
            $table->float('cantidad_pedido')->nullable();
            $table->foreignId('producto_id')->constrained()->cascadeOnDelete();
            $table->string('empresa')->nullable();
            $table->foreignId('transportadora_id')->constrained()->cascadeOnDelete();
            $table->string('estado')->default('pendiente');
            $table->text('novedad_factura')->nullable();

            $table->foreignId('creado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('actualizado_por')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('despachos');
    }
};
