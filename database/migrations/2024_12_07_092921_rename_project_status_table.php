<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameProjectStatusTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::rename('project_status', 'project_statuses');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('project_statuses', 'project_status');
    }
}
