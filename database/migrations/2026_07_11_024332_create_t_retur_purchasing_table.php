<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('t_retur_purchasing', function (Blueprint $table) {
            $table->id('id_retur_purchasing');
            $table->foreignId('id_penerimaan')->constrained('t_penerimaan', 'id_penerimaan');
            $table->date('tanggal');
            $table->text('alasan')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });

        Schema::create('t_retur_purchasing_detail', function (Blueprint $table) {
            $table->id('id_retur_purchasing_detail');
            $table->foreignId('id_retur_purchasing')->constrained('t_retur_purchasing', 'id_retur_purchasing')->cascadeOnDelete();
            $table->string('sku', 50);
            $table->foreign('sku')->references('sku')->on('m_barang');
            $table->integer('qty_retur');
            $table->text('alasan_item')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('t_retur_purchasing_detail');
        Schema::dropIfExists('t_retur_purchasing');
    }
};