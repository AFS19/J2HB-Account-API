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
        Schema::create('auto_ecole_meta', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('auto_ecole_id')->unsigned();
            $table->foreign('auto_ecole_id')->references('id')->on('auto_ecoles')->onDelete('cascade');

            $table->string('type')->default('null');

            $table->string('key')->index();
            $table->text('value')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auto_ecole_meta');
    }
};
