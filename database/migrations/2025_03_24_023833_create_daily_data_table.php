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
        Schema::create('daily_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ward_id')->constrained('wards')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users');
            $table->date('date');
            $table->integer('death')->default(0);
            $table->integer('neonatal_jaundice')->default(0);
            $table->integer('bedridden_case')->default(0);
            $table->integer('incident_report')->default(0);
            $table->text('remarks')->nullable();
            $table->timestamps();

            // Add unique constraint to prevent duplicate entries for the same ward and date
            $table->unique(['ward_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_data');
    }
};
