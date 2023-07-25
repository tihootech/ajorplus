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
        Schema::create('mounths', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('num');
            $table->string('symbol');
            $table->string('name');
            $table->string('quantity');
            $table->unsignedSmallInteger('state');
            $table->string('brick');
            $table->boolean('mark')->default(0);
            $table->boolean('fire')->default(0);
            $table->text('discription')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mounths');
    }
};
