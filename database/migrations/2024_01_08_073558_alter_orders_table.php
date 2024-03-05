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
        Schema::table('orders',function(Blueprint $table){
            $table->foreignId('discount_coupon_id')->nullable()->constrained()->onDelete('cascade')->after('country_id');
            $table->enum('payment_status',['Paid','Unpaid'])->after('grand_total')->default('Unpaid');
            $table->enum('status',['Pending','Shipped','Delivered','Cancelled'])->after('payment_status')->default('Pending');
         });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders',function(Blueprint $table){
            $table->dropColumn('discount_coupon_id');
            $table->dropColumn('payment_status');
            $table->dropColumn('status');
         });
    }
};
