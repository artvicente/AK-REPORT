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
        Schema::create('project_stats', function (Blueprint $table) {
        $table->id();
        $table->string('category');
        $table->integer('infosheets_received')->default(0);
        $table->integer('images_captured')->default(0);
        $table->integer('encoded')->default(0);
        $table->integer('for_review')->default(0);
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_stats');
    }
};
