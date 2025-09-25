<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cards', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('card_serial')->unique();
            $table->string('card_number');
            $table->string('book_id');
            $table->dateTime('card_insert_date');
            $table->boolean('card_on_hold')->default(0);
            $table->timestamp('created_at')->useCurrent();
            $table->integer('companies_id')->unsigned()->nullable();
            $table->foreign('companies_id')->references('id')->on('companies')->onDelete('cascade');
            $table->integer('cardstautes_id')->unsigned()->nullable();
            $table->foreign('cardstautes_id')->references('id')->on('cardstautes')->onDelete('cascade');
            $table->integer('requests_id')->unsigned()->nullable();
            $table->foreign('requests_id')->references('id')->on('requests')->onDelete('cascade');
            $table->integer('users_id')->unsigned()->nullable();
            $table->foreign('users_id')->references('id')->on('users')->onDelete('cascade');
      
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cards');
    }
};
