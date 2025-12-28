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
        Schema::table('cards', function (Blueprint $table) {
            if (!Schema::hasColumn('cards', 'cancel_by')) {
                $table->string('cancel_by')->nullable()->after('cardstautes_id');
            }
            if (!Schema::hasColumn('cards', 'res')) {
                $table->string('res')->nullable()->after('cancel_by');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cards', function (Blueprint $table) {
            $table->dropColumn('cancel_by');
            $table->dropColumn('res');
        });
    }
};
