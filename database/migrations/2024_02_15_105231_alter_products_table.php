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
        Schema::table('products',function(Blueprint $table){ 
            $table->text('categories')->nullable()->after('related_products');
            $table->text('direction')->nullable()->after('shipping_returns'); 
           $table->text('ingredients')->nullable()->after('direction');
           $table->text('benefits')->nullable()->after('ingredients');
           $table->text('icons')->nullable()->after('benefits');
           
         });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products',function(Blueprint $table){ 
            $table->dropColumn('categories');
            $table->dropColumn('direction');
            $table->dropColumn('ingredients');
            $table->dropColumn('ingredients');
            $table->dropColumn('benefits');
            $table->dropColumn('icons');
           
         });
    }
};
