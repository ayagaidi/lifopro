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
        Schema::create('countryc_conditions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('unifiedofficaddress');
            $table->string('statementypecoverage');
            $table->boolean('active')->default(1);
            $table->timestamp('created_at')->useCurrent();
            $table->integer('countries_id')->unsigned()->nullable();
            $table->foreign('countries_id')->references('id')->on('countries')->onDelete('cascade');
           
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('countryc_conditions');
    }
};
