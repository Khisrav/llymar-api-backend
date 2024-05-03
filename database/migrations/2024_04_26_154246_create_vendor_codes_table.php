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
        Schema::create('vendor_codes', function (Blueprint $table) {
            $table->id();
            $table->integer('vendor_code');
            $table->string('img');
            $table->string('name');
            $table->string('type')->nullable();
            $table->string('unit');
            $table->integer('price');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_codes');
    }
};
