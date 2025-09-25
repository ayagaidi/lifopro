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
        Schema::create('distributions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('numerofcard');
            $table->timestamp('created_at')->useCurrent();
            $table->integer('offices_id')->unsigned()->nullable();
            $table->foreign('offices_id')->references('id')->on('offices')->onDelete('cascade');
            $table->integer('company_users_id')->unsigned()->nullable();
            $table->foreign('company_users_id')->references('id')->on('company_users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('distributions');
    }
};
