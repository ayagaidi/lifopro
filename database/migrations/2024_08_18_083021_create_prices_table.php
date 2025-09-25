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
        Schema::create('prices', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('installment_daily_1', 8, 3);
            $table->decimal('installment_daily_2', 8, 3);
            $table->decimal('supervision', 8, 3);
            $table->decimal('tax', 8, 3);
            $table->decimal('version', 8, 3);

            $table->decimal('stamp', 8, 3);
            $table->decimal('increase', 8, 3);

        
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prices');
    }
};
