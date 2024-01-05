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
        Schema::create('work_process', function (Blueprint $table) {
            $table->id();
            $table->string('name');

            $table->bigInteger('auto_ecole_id')->unsigned();
            $table->foreign('auto_ecole_id')->references('id')->on('auto_ecoles')->onDelete('cascade');

            $table->json('steps');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_process');
    }
};
