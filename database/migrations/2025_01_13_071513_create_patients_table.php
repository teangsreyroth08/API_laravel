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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
            ->references('id')->on('users')
            ->onDelete('cascade')->onUpdate('cascade');

            $table->string('patient_id')->unique();
            $table->string('name');
            $table->integer('age');
            $table->string('gender');
            $table->string('contact');
            $table->string('id_card')->nullable();
            $table->text('allergies')->nullable();

            $table->integer('created_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
