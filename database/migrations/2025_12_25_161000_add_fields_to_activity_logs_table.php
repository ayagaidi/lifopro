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
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->string('performed_by')->nullable()->after('user_name'); // من قام بالعملية (الادمن)
            $table->string('target_user')->nullable()->after('performed_by'); // المستخدم المستهدف
            $table->text('detailed_description')->nullable()->after('activity_type'); // وصف مفصل للعملية
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropColumn(['performed_by', 'target_user', 'detailed_description']);
        });
    }
};