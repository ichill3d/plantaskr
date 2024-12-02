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
        Schema::create('project_status_relation', function (Blueprint $table) {
            $table->id();
            $table->foreignId('projects_id')->constrained('projects')->onDelete('cascade');
            $table->foreignId('project_status_id')->constrained('project_status')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_status_relation');
    }
};
