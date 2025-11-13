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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->string('activity_type'); // نوع العملية مثل تغيير كلمة المرور
            $table->string('user_name'); // اسم المستخدم
            $table->timestamp('activity_date'); // تاريخ ووقت العملية
            $table->enum('status', ['success', 'failure']); // حالة التنفيذ نجاح أو فشل
            $table->text('reason')->nullable(); // السبب إن وجد
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
