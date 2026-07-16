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
    Schema::table('sales_invoices', function (Blueprint $table) {
        $table->string('xendit_invoice_id', 100)->nullable()->after('total');
        $table->text('xendit_invoice_url')->nullable()->after('xendit_invoice_id');
        $table->timestamp('paid_at')->nullable()->after('xendit_invoice_url');
        $table->string('payment_method', 50)->nullable()->after('paid_at');
    });

    Schema::table('sales_invoices', function (Blueprint $table) {
        $table->enum('status', ['draft', 'pending_payment', 'paid', 'expired', 'cancelled'])
            ->default('draft')
            ->change();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales_invoices', function (Blueprint $table) {
        $table->dropColumn(['xendit_invoice_id', 'xendit_invoice_url', 'paid_at', 'payment_method']);
    });

    Schema::table('sales_invoices', function (Blueprint $table) {
        $table->enum('status', ['draft', 'paid'])->default('draft')->change();
    });
    }
};
