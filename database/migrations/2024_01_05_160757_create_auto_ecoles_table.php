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
        Schema::create('auto_ecoles', function (Blueprint $table) {
            $table->id();
            $table->string('name');

            $table->bigInteger('gerant_id')->unsigned();
            $table->foreign('gerant_id')->references('id')->on('users')->onDelete('cascade');

            $table->json("permis_list")->include("AM", "A1", "A", "B", "C", "D", "EB", "EC", "ED");

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auto_ecoles');
    }
};
