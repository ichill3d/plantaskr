<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;

use Illuminate\Support\Facades\Storage;

class Attachment extends Model
{
    protected $fillable = [
        'file_name',
        'file_path',
        'uploaded_by',
    ];

    // Relationship to tasks
    public function tasks()
    {
        return $this->belongsToMany(Task::class, 'attachment_task', 'attachment_id', 'task_id')->withTimestamps();
    }

    // Relationship to projects
    public function projects()
    {
        return $this->belongsToMany(Project::class, 'attachment_project', 'attachment_id', 'project_id')->withTimestamps();
    }

    // Relationship to the user who uploaded the file
    public function uploadedBy()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Update or Create an Attachment.
     */
    public static function uploadAttachment($file, $storagePath = 'attachments')
    {
        try {
            $path = $file->storePublicly($storagePath, ['disk' => 'public']);

            return self::create([
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $path,
                'uploaded_by' => auth()->id(),
            ]);
        } catch (\Exception $e) {
            \Log::error('Attachment upload failed in Model: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Delete Attachment File.
     */
    public static function deleteAttachment($attachmentId)
    {
        $attachment = self::findOrFail($attachmentId);

        // Begin transaction for safety
        \DB::transaction(function () use ($attachment) {
            // Delete file from storage
            if ($attachment->file_path && Storage::disk('public')->exists($attachment->file_path)) {
                Storage::disk('public')->delete($attachment->file_path);
            }

            // Detach relationships
            if (method_exists($attachment, 'tasks')) {
                $attachment->tasks()->detach();
            }

            if (method_exists($attachment, 'projects')) {
                $attachment->projects()->detach();
            }

            // Delete attachment record
            $attachment->delete();
        });
    }
}
