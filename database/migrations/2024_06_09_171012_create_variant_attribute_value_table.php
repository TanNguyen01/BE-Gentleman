<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVariantAttributeValueTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('variant_attribute_value')) {
            Schema::create('variant_attribute_value', function (Blueprint $table) {
                $table->id();
                $table->foreignId('variant_id')->constrained()->onDelete('cascade');
                $table->foreignId('attribute_value_id')->constrained()->onDelete('cascade');
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('variant_attribute_value');
    }
}
