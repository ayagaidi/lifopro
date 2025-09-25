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
        Schema::create('companies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('phonenumber');
            $table->string('code')->unique();
            $table->string('fullname_manger')->nullable();
            $table->string('phonenumber_manger')->nullable();
            $table->string('address');
            $table->string('email');
            $table->string('website');
            $table->string('logo');
            $table->integer('cities_id')->unsigned()->nullable();
            $table->foreign('cities_id')->references('id')->on('cities')->onDelete('cascade');
            $table->integer('regions_id')->unsigned()->nullable();
            $table->foreign('regions_id')->references('id')->on('regions')->onDelete('cascade');
            $table->timestamp('created_at')->useCurrent();
            $table->boolean('active')->default(1);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
