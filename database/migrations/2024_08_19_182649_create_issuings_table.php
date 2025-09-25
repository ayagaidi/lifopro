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
        Schema::create('issuings', function (Blueprint $table) {
            $table->increments('id');
            $table->dateTime('issuing_date');
            $table->string('insurance_name');
            $table->string('insurance_location');
            $table->string('insurance_phone');
            $table->string('motor_number');
            $table->string('plate_number');
            $table->string('chassis_number');
            $table->year('car_made_date');

            $table->integer('cars_id')->unsigned()->nullable();
            $table->foreign('cars_id')->references('id')->on('cars')->onDelete('cascade');
           

            $table->integer('cards_id')->unsigned()->nullable();
            $table->foreign('cards_id')->references('id')->on('cards')->onDelete('cascade');
           
            

            $table->integer('vehicle_nationalities_id')->unsigned()->nullable();
            $table->foreign('vehicle_nationalities_id')->references('id')->on('vehicle_nationalities')->onDelete('cascade');
            
            $table->dateTime('insurance_day_from');
            $table->integer('insurance_days_number');
            $table->dateTime('nsurance_day_to');
            $table->integer('insurance_country_number');

            
            $table->decimal('insurance_installment_daily', 8, 3);
            $table->decimal('insurance_installment', 8, 3);

            
            $table->decimal('insurance_supervision', 8, 3);

            $table->decimal('insurance_tax', 8, 3);
            $table->decimal('insurance_version', 8, 3);
            $table->decimal('insurance_stamp', 8, 3);

            $table->decimal('insurance_total', 8, 3);
            $table->string('insurance_clauses_id');

            $table->integer('countries_id')->unsigned()->nullable();
            $table->foreign('countries_id')->references('id')->on('countries')->onDelete('cascade');
         
           
            
            $table->timestamp('created_at')->useCurrent();
            $table->integer('companies_id')->unsigned()->nullable();
            $table->foreign('companies_id')->references('id')->on('companies')->onDelete('cascade');
            $table->integer('company_users_id')->unsigned()->nullable();
            $table->foreign('company_users_id')->references('id')->on('company_users')->onDelete('cascade');
            $table->integer('offices_id')->unsigned()->nullable();
            $table->foreign('offices_id')->references('id')->on('offices')->onDelete('cascade');
            $table->integer('office_users_id')->unsigned()->nullable();
            $table->foreign('office_users_id')->references('id')->on('office_users')->onDelete('cascade');

            $table->integer('users_id')->unsigned()->nullable();
            $table->foreign('users_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('issuings');
    }
};
