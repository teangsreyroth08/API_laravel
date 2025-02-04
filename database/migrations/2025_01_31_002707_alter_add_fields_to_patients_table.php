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
        Schema::table('patients', function (Blueprint $table) {
            $table->date('date_of_birth')->nullable();
            $table->string('email')->nullable();
            $table->string('passport')->nullable();
            $table->text('address')->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone')->nullable();
            $table->string('blood_type')->nullable();
            $table->text('chronic_conditions')->nullable();
            $table->text('current_medications')->nullable();
            $table->text('previous_surgeries')->nullable();
            $table->text('family_medical_history')->nullable();
            $table->string('preferred_doctor')->nullable();
            $table->string('insurance_provider')->nullable();
            $table->string('insurance_policy_number')->nullable();
            $table->text('billing_address')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn([
                'date_of_birth',
                'email',
                'passport',
                'address',
                'emergency_contact_name',
                'emergency_contact_phone',
                'blood_type',
                'chronic_conditions',
                'current_medications',
                'previous_surgeries',
                'family_medical_history',
                'preferred_doctor',
                'insurance_provider',
                'insurance_policy_number',
                'billing_address',
            ]);
        });
    }
};
