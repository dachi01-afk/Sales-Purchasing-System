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
        Schema::create('t_po', function (Blueprint $table) {
            $table->id('id_po');
            $table->foreignId('id_permintaan')->constrained('t_permintaan', 'id_permintaan');
            $table->foreignId('id_vendor')->constrained('m_vendor', 'id_vendor');
            $table->date('tanggal');
            $table->enum('status', ['draft', 'dikirim', 'selesai', 'dibatalkan'])->default('draft');
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });

        Schema::create('t_po_detail', function (Blueprint $table) {
            $table->id('id_po_detail');
            $table->foreignId('id_po')->constrained('t_po', 'id_po')->cascadeOnDelete();
            $table->string('sku', 50);
            $table->foreign('sku')->references('sku')->on('m_barang');
            $table->integer('qty');
            $table->decimal('harga', 15, 2);
            $table->decimal('subtotal', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_po');
        Schema::dropIfExists('t_po_detail');
    }
};
