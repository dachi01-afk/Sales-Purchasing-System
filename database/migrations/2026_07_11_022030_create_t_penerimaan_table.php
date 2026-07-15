<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('t_penerimaan', function (Blueprint $table) {
            $table->id('id_penerimaan');
            $table->foreignId('id_po')->constrained('t_po', 'id_po');
            $table->date('tanggal');
            $table->text('keterangan')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });

        Schema::create('t_penerimaan_detail', function (Blueprint $table) {
            $table->id('id_penerimaan_detail');
            $table->foreignId('id_penerimaan')->constrained('t_penerimaan', 'id_penerimaan')->cascadeOnDelete();
            $table->string('sku', 50);
            $table->foreign('sku')->references('sku')->on('m_barang');
            $table->integer('qty_diterima');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('t_penerimaan_detail');
        Schema::dropIfExists('t_penerimaan');
    }
};