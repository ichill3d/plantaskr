<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('tasks', function (Blueprint $table) {
            // Foreign key indexes
            $table->index('project_id');
            $table->index('milestone_id');
            $table->index('task_status_id');
            $table->index('task_priorities_id');
            $table->index('created_by_user_id');

            // Common filter/sort columns
            $table->index('due_date');
            $table->index('board_position');
            $table->index('name');

            // Composite index for frequent query combinations (optional)
            $table->index(['project_id', 'task_status_id']);
            $table->index(['task_priorities_id', 'due_date']);
        });
    }

    public function down()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropIndex(['project_id']);
            $table->dropIndex(['milestone_id']);
            $table->dropIndex(['task_status_id']);
            $table->dropIndex(['task_priorities_id']);
            $table->dropIndex(['created_by_user_id']);
            $table->dropIndex(['due_date']);
            $table->dropIndex(['board_position']);
            $table->dropIndex(['name']);
            $table->dropIndex(['project_id', 'task_status_id']);
            $table->dropIndex(['task_priorities_id', 'due_date']);
        });
    }
};
