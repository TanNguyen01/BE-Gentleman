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
        Schema::create('attributes', function (Blueprint $table) {
            $table->increments('id'); 
            $table->string('name');
            $table->string('value');
            $table->timestamps();
        });

        DB::statement('ALTER TABLE attributes AUTO_INCREMENT = 0;');

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attributes');
    }
};
