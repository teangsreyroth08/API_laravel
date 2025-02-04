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
        Schema::create('prescriptions', function (Blueprint $table) {
            $table->id();

            // Fix the typo: consultantion_id -> consultation_id
            $table->foreignId('consultation_id')->constrained('consultations')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            // Ensure patients and doctors tables exist before creating prescriptions
            $table->foreignId('patient_id')->constrained('patients')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            $table->foreignId('doctor_id')->constrained('doctors')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            $table->text('notes')->nullable();
            $table->integer('created_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prescriptions');
    }
};
