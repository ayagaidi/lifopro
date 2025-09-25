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
        Schema::create('company_user_roles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_users_id')->unsigned()->nullable();
            $table->foreign('company_users_id')->references('id')->on('company_users')->onDelete('cascade');
            $table->integer('company_user_permissions_id')->unsigned()->nullable();
            $table->foreign('company_user_permissions_id')->references('id')->on('company_user_permissions')->onDelete('cascade');
            $table->timestamp('created_at')->useCurrent();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_user_roles');
    }
};
