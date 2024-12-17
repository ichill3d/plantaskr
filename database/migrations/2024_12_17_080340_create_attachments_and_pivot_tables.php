<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        // Main Attachments Table
        Schema::create('attachments', function (Blueprint $table) {
            $table->id();
            $table->string('file_name');
            $table->string('file_path');
            $table->foreignId('uploaded_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        // Pivot Table: Project-Attachment
        Schema::create('attachment_project', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->foreignId('attachment_id')->constrained('attachments')->onDelete('cascade');
            $table->timestamps();
        });

        // Pivot Table: Task-Attachment
        Schema::create('attachment_task', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained()->onDelete('cascade');
            $table->foreignId('attachment_id')->constrained('attachments')->onDelete('cascade');
            $table->boolean('is_reference')->default(false); // To track referenced files
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('attachment_task');
        Schema::dropIfExists('attachment_project');
        Schema::dropIfExists('attachments');
    }
};
