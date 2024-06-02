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
        Schema::create('addresses', function (Blueprint $table) {
            $table->increments('id'); 
            $table->string('address');
            $table->integer('user_id');
            $table->boolean('status');
            $table->timestamps();
        });
        
        DB::statement('ALTER TABLE addresses AUTO_INCREMENT = 0;');

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
