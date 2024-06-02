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
        Schema::create('bills', function (Blueprint $table) {
            $table->increments('id'); 
            $table->integer('user_id')->constrained('users');
            $table->string('Recipient_phone');
            $table->string('Recipient_address');
            $table->decimal('total_amount', 10, 2);
            $table->dateTime('bill_date');
            $table->string('voucher');
            $table->timestamps();
        });

        DB::statement('ALTER TABLE bills AUTO_INCREMENT = 0;');

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bills');
    }
};
