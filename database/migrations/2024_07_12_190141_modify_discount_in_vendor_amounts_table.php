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
        Schema::table('vendor_amounts', function (Blueprint $table) {
            //discount = 1 coz 1 == 100% i.e. initial price
            $table->float('discount')->default(1)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vendor_amounts', function (Blueprint $table) {
            //
        });
    }
};
