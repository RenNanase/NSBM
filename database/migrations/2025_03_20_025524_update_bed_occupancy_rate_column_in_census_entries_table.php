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
        Schema::table('census_entries', function (Blueprint $table) {
            // Change the bed_occupancy_rate column to allow values up to 9999.99
            $table->decimal('bed_occupancy_rate', 7, 2)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('census_entries', function (Blueprint $table) {
            // Revert back to original size (5,2)
            $table->decimal('bed_occupancy_rate', 5, 2)->change();
        });
    }
};
