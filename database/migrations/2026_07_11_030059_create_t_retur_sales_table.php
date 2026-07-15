<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('t_retur_sales', function (Blueprint $table) {
            $table->id('id_retur_sales');
            $table->foreignId('id_do')->constrained('t_do', 'id_do');
            $table->date('tanggal');
            $table->text('alasan')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });

        Schema::create('t_retur_sales_detail', function (Blueprint $table) {
            $table->id('id_retur_sales_detail');
            $table->foreignId('id_retur_sales')->constrained('t_retur_sales', 'id_retur_sales')->cascadeOnDelete();
            $table->string('sku', 50);
            $table->foreign('sku')->references('sku')->on('m_barang');
            $table->integer('qty_retur');
            $table->text('alasan_item')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('t_retur_sales_detail');
        Schema::dropIfExists('t_retur_sales');
    }
};