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
        Schema::table('prescriptions', function (Blueprint $table) {
            $table->date('date');
            $table->integer('age');
            $table->string('blood_pressure');
            $table->float('weight');
            $table->float('height');
            $table->text('diagnosis');
            $table->text('treatment');
            $table->date('next_appointment_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prescriptions', function (Blueprint $table) {
            $table->dropColumn([
                'date',
                'age',
                'blood_pressure',
                'weight',
                'height',
                'diagnosis',
                'treatment',
                'next_appointment_date',
            ]);
        });
    }
};
