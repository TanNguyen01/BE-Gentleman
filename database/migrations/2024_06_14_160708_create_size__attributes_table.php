<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSizeAttributesTable extends Migration
{
    public function up()
    {
        Schema::create('size_attributes', function (Blueprint $table) {
            $table->unsignedBigInteger('size_id');
            $table->unsignedBigInteger('attribute_id');
            $table->primary(['size_id', 'attribute_id']);
            $table->foreign('size_id')->references('id')->on('sizes')->onDelete('cascade');
            $table->foreign('attribute_id')->references('id')->on('attributes')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('size_attributes');
    }
}
