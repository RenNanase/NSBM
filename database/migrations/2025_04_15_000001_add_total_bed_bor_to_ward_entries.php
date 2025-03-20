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
        Schema::table('ward_entries', function (Blueprint $table) {
            // Rename current shift_bor to licensed_bed_bor to be more specific
            $table->renameColumn('shift_bor', 'licensed_bed_bor');

            // Add new column for BOR based on total beds
            $table->decimal('total_bed_bor', 5, 2)->after('licensed_bed_bor')
                  ->comment('Bed occupancy rate based on total beds');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ward_entries', function (Blueprint $table) {
            // Remove the new column
            $table->dropColumn('total_bed_bor');

            // Rename back to original name
            $table->renameColumn('licensed_bed_bor', 'shift_bor');
        });
    }
};
