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
        Schema::create('t_permintaan', function (Blueprint $table) {
            $table->id('id_permintaan');
            $table->date('tanggal');
            $table->text('keterangan')->nullable();
            $table->enum('status', ['draft', 'disetujui', 'ditolak'])->default('draft');
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });

        Schema::create('t_permintaan_detail', function (Blueprint $table) {
            $table->id('id_permintaan_detail');
            $table->foreignId('id_permintaan')->constrained('t_permintaan', 'id_permintaan')->cascadeOnDelete();
            $table->string('sku', 50);
            $table->foreign('sku')->references('sku')->on('m_barang');
            $table->integer('qty');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_permintaan_detail');
        Schema::dropIfExists('t_permintaan');
    }
};
