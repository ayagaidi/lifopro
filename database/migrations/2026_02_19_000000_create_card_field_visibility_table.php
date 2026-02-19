<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('card_field_visibility', function (Blueprint $table) {
            $table->id();
            $table->string('field_name');
            $table->string('field_label');
            $table->boolean('visible')->default(true);
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        // Insert default fields
        $defaultFields = [
            ['field_name' => 'vehicle_type', 'field_label' => 'نوع المركبة', 'visible' => true, 'order' => 1],
            ['field_name' => 'vehicle_nationality', 'field_label' => 'جنسية المركبة', 'visible' => true, 'order' => 2],
            ['field_name' => 'manufacturing_year', 'field_label' => 'سنة الصنع', 'visible' => true, 'order' => 3],
            ['field_name' => 'chassis_number', 'field_label' => 'رقم الهيكل (الشاسيه)', 'visible' => true, 'order' => 4],
            ['field_name' => 'plate_number', 'field_label' => 'رقم اللوحة', 'visible' => true, 'order' => 5],
            ['field_name' => 'engine_number', 'field_label' => 'رقم المحرك (الموتور)', 'visible' => true, 'order' => 6],
            ['field_name' => 'usage_purpose', 'field_label' => 'الغرض من الإستعمال', 'visible' => true, 'order' => 7],
        ];

        foreach ($defaultFields as $field) {
            DB::table('card_field_visibility')->insert($field);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('card_field_visibility');
    }
};
