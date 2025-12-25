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
        Schema::table('api_logs', function (Blueprint $table) {
            $table->string('company_name')->nullable()->after('user_name'); // اسم الشركة
            $table->string('office_name')->nullable()->after('company_name'); // اسم المكتب
            $table->string('related_link')->nullable()->after('received_data'); // رابط متعلق بالعملية
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('api_logs', function (Blueprint $table) {
            $table->dropColumn(['company_name', 'office_name', 'related_link']);
        });
    }
};