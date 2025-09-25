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
        Schema::create('office_user_roles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('office_users_id')->unsigned()->nullable();
            $table->foreign('office_users_id')->references('id')->on('office_users')->onDelete('cascade');
            $table->integer('office_user_permissions_id')->unsigned()->nullable();
            $table->foreign('office_user_permissions_id')->references('id')->on('office_user_permissions')->onDelete('cascade');
            $table->timestamp('created_at')->useCurrent();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('office_user_roles');
    }
};
