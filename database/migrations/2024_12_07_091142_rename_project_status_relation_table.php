<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameProjectStatusRelationTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::rename('project_status_relation', 'project_status_relations');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('project_status_relations', 'project_status_relation');
    }
}
