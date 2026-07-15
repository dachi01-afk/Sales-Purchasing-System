<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('t_invoice_sales', function (Blueprint $table) {
            $table->id('id_invoice_sales');
            $table->foreignId('id_do')->constrained('t_do', 'id_do');
            $table->date('tanggal');
            $table->decimal('total', 15, 2)->default(0);
            $table->enum('status', ['draft', 'lunas'])->default('draft');
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });

        Schema::create('t_invoice_sales_detail', function (Blueprint $table) {
            $table->id('id_invoice_sales_detail');
            $table->foreignId('id_invoice_sales')->constrained('t_invoice_sales', 'id_invoice_sales')->cascadeOnDelete();
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
        Schema::dropIfExists('t_invoice_sales_detail');
        Schema::dropIfExists('t_invoice_sales');
    }
};