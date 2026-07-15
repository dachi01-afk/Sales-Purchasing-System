<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('t_invoice_purchasing', function (Blueprint $table) {
            $table->id('id_invoice_purchasing');
            $table->foreignId('id_po')->constrained('t_po', 'id_po');
            $table->date('tanggal');
            $table->decimal('total', 15, 2)->default(0);
            $table->enum('status', ['draft', 'lunas'])->default('draft');
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });

        Schema::create('t_invoice_purchasing_detail', function (Blueprint $table) {
            $table->id('id_invoice_purchasing_detail');
            $table->foreignId('id_invoice_purchasing')->constrained('t_invoice_purchasing', 'id_invoice_purchasing')->cascadeOnDelete();
            $table->string('sku', 50);
            $table->foreign('sku')->references('sku')->on('m_barang');
            $table->integer('qty');
            $table->decimal('harga', 15, 2);
            $table->decimal('subtotal', 15, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('t_invoice_purchasing_detail');
        Schema::dropIfExists('t_invoice_purchasing');
    }
};