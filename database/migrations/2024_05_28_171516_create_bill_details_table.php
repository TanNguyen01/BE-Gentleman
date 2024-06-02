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
        Schema::create('bill_details', function (Blueprint $table) {
            $table->increments('id'); 
            $table->string('product_name');
            $table->string('attribute_name');
            $table->decimal('price', 10, 2);
            $table->integer('quantity');
            $table->integer('bill_id');
            $table->timestamps();
        });

        DB::statement('ALTER TABLE bill_details AUTO_INCREMENT = 0;');

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bill_details');
    }
};
