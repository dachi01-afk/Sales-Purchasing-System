<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('t_do', function (Blueprint $table) {
            $table->id('id_do');
            $table->foreignId('id_so')->constrained('t_so', 'id_so');
            $table->date('tanggal');
            $table->text('alamat_pengiriman')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });

        Schema::create('t_do_detail', function (Blueprint $table) {
            $table->id('id_do_detail');
            $table->foreignId('id_do')->constrained('t_do', 'id_do')->cascadeOnDelete();
            $table->string('sku', 50);
            $table->foreign('sku')->references('sku')->on('m_barang');
            $table->integer('qty_dikirim');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('t_do_detail');
        Schema::dropIfExists('t_do');
    }
};