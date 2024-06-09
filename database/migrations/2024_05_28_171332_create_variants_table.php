<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
            $table->decimal('price_promotional', 10, 2)->nullable();
            $table->integer('quantity');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->integer('voucher_id')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('variants');
    }
};
