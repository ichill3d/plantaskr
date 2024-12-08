<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTaskUsersRoleIdForeign extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('task_users', function (Blueprint $table) {
            // Drop the incorrect foreign key
            $table->dropForeign(['role_id']);

            // Add the correct foreign key
            $table->foreign('role_id')
                ->references('id')
                ->on('task_roles') // Reference task_roles table
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('task_users', function (Blueprint $table) {
            // Drop the fixed foreign key
            $table->dropForeign(['role_id']);

            // Restore the original (incorrect) foreign key
            $table->foreign('role_id')
                ->references('id')
                ->on('users') // Revert to referencing users
                ->onDelete('cascade');
        });
    }
}
