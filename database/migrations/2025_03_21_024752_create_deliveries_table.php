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
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ward_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained();
            $table->date('report_date');
            $table->integer('svd')->default(0);
            $table->integer('lscs')->default(0);
            $table->integer('vacuum')->default(0);
            $table->integer('forceps')->default(0);
            $table->integer('breech')->default(0);
            $table->integer('eclampsia')->default(0);
            $table->integer('twin')->default(0);
            $table->integer('mrp')->default(0);
            $table->integer('fsb_mbs')->default(0);
            $table->integer('bba')->default(0);
            $table->integer('total')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();

            // Ensure only one entry per ward per date
            $table->unique(['ward_id', 'report_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deliveries');
    }
};
