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
        Schema::table('despachos', function (Blueprint $table) {
            $table->string('guia')->nullable()->after('transportadora_id');
            $table->decimal('valor_unitario', 10, 2)->nullable()->after('guia');
            $table->decimal('valor_total', 12, 2)->nullable()->after('valor_unitario');
            $table->string('mes')->nullable()->after('valor_total');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('despachos', function (Blueprint $table) {
            //
        });
    }
};
