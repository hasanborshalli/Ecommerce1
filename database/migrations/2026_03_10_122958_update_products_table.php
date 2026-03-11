<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Allows showing a product as "Out of Stock" rather than hiding it completely
            $table->boolean('show_when_out_of_stock')->default(true)->after('is_on_sale');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('show_when_out_of_stock');
        });
    }
};