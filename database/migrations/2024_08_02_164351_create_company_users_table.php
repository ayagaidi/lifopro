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
        Schema::create('company_users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('fullname');
            $table->string('username',50)->unique();
            $table->string('email',100)->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->boolean('active')->default(1);
            $table->timestamp('created_at')->useCurrent();
            $table->integer('companies_id')->unsigned()->nullable();
            $table->foreign('companies_id')->references('id')->on('companies')->onDelete('cascade');
            $table->integer('user_type_id')->unsigned()->nullable();
            $table->foreign('user_type_id')->references('id')->on('user_types')->onDelete('cascade');
         
            $table->rememberToken();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_users');
    }
};
