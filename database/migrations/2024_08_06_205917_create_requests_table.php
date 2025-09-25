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
        Schema::create('requests', function (Blueprint $table) {
            $table->increments('id');
            $table->string('request_number')->unique();
            $table->string('cards_number');
            $table->boolean('active')->default(1);
            $table->timestamp('created_at')->useCurrent();
            $table->integer('companies_id')->unsigned()->nullable();
            $table->foreign('companies_id')->references('id')->on('companies')->onDelete('cascade');
            $table->integer('company_users_id')->unsigned()->nullable();
            $table->foreign('company_users_id')->references('id')->on('company_users')->onDelete('cascade');
            $table->integer('users_id')->unsigned()->nullable();
            $table->foreign('users_id')->references('id')->on('users')->onDelete('cascade');
         
            $table->integer('request_statuses_id')->unsigned()->nullable();
            $table->foreign('request_statuses_id')->references('id')->on('request_statuses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requests');
    }
};
