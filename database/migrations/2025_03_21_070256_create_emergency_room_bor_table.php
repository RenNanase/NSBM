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
        Schema::create('emergency_room_bor', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->enum('shift', ['0700-1400', '1400-2100', '2100-0700']);
            $table->integer('green')->default(0);
            $table->integer('yellow')->default(0);
            $table->integer('red')->default(0);
            $table->integer('hours_24_census')->default(0);
            $table->integer('grand_total')->default(0);
            $table->integer('ambulance_call')->default(0);
            $table->integer('admission')->default(0);
            $table->integer('transfer')->default(0);
            $table->integer('death')->default(0);
            $table->foreignId('user_id')->constrained('users');
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emergency_room_bor');
    }
};
