<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateColorAttributesTable extends Migration
{
    public function up()
    {
        Schema::create('color_attributes', function (Blueprint $table) {
            $table->unsignedBigInteger('color_id');
            $table->unsignedBigInteger('attribute_id');

            $table->primary(['color_id', 'attribute_id']);
            $table->foreign('color_id')->references('id')->on('colors')->onDelete('cascade');
            $table->foreign('attribute_id')->references('id')->on('attributes')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('color_attributes');
    }
}
