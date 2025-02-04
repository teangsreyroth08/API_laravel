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
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();

            $table->foreignId('type_id')->references('id')
            ->on('inventory_types')
            ->onDelete('cascade')
            ->onUpdate('cascade');

            $table->string('item_code');
            $table->string('item_name');
            $table->string('quantity');
            $table->double('price');
            $table->double('low_stock_alert')->default(5);
            $table->integer('manage_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
