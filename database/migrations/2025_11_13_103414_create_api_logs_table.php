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
        Schema::create('api_logs', function (Blueprint $table) {
            $table->id();
            $table->string('user_name'); // اسم المستخدم
            $table->string('operation_type'); // نوع العملية مثل طلب البطاقات، الموافقات، التحقق من الحالات، إصدار ومنح البطاقات للشركات، وغيرها
            $table->timestamp('execution_date'); // تاريخ ووقت التنفيذ
            $table->enum('status', ['success', 'failure']); // حالة العملية ناجحة / فاشلة
            $table->json('sent_data')->nullable(); // البيانات المرسلة
            $table->json('received_data')->nullable(); // البيانات التي تم استقبالها
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_logs');
    }
};
