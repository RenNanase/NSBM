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
        Schema::create('ward_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('ward_id')->constrained('wards')->onDelete('cascade');
            $table->foreignId('shift_id')->constrained('shifts')->onDelete('cascade');
            $table->integer('cf_patient');
            $table->integer('total_patient')->nullable();
            $table->decimal('shift_bor', 5, 2); // Shift bed occupancy rate
            $table->integer('total_admission');
            $table->integer('total_transfer_in');
            $table->integer('total_transfer_out');
            $table->integer('total_discharge');
            $table->integer('aor');
            $table->integer('total_staff_on_duty');
            $table->integer('overtime');
            $table->integer('total_daily_patients')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ward_entries');
    }
};
