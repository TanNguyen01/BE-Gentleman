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
        Schema::create('vouchers', function (Blueprint $table) {
            $table->increments('id'); 
            $table->string('voucher_code');
            $table->decimal('discount_amount', 10, 2);
            $table->date('expiration_date');
            $table->decimal('minimum_purchase', 10, 2);
            $table->integer('usage_limit');
            $table->boolean('status');
            $table->string('description');
            $table->timestamps();
        });

        DB::statement('ALTER TABLE vouchers AUTO_INCREMENT = 0;');

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
