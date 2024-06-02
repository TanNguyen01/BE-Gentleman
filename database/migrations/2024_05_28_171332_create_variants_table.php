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
        Schema::create('variants', function (Blueprint $table) {
            $table->increments('id'); 
            $table->integer('product_id');
            $table->integer('attribute_id');
            $table->decimal('price', 10, 2);
            $table->integer('quantity');
            $table->boolean('status');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
        });

        DB::statement('ALTER TABLE variants AUTO_INCREMENT = 0;');

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('variants');
    }
};
