<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('vege_product', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('p_id');
            $table->string('image')->nullable();
            $table->string('p_name');
            $table->text('details');
            $table->decimal('mass', 8, 2);
            $table->decimal('price', 8, 2);
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        
        Schema::dropIfExists('vege_product');
    }
};
