<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Attachment;
use Illuminate\Http\UploadedFile;

class FileUpload extends Component
{
    use WithFileUploads;

    public $files = []; // Holds uploaded files
    public $taskId = null; // Task ID for attachment
    public $projectId = null; // Project ID for attachment
    public $attachments = []; // Holds current file list

    public function mount($taskId = null, $projectId = null)
    {
        $this->taskId = $taskId;
        $this->projectId = $projectId;

        $this->loadAttachments();
    }

    public function loadAttachments()
    {
        // Load attachments for tasks or projects dynamically
        $query = Attachment::query();

        if ($this->taskId) {
            $query->whereHas('tasks', fn($q) => $q->where('tasks.id', $this->taskId)
                ->whereHas('project.team', fn($teamQ) => $teamQ->where('id', auth()->user()->current_team_id)));
        } elseif ($this->projectId) {
            $query->whereHas('projects', fn($q) => $q->where('projects.id', $this->projectId)
                ->where('team_id', auth()->user()->current_team_id));
        }

        $this->attachments = $query->latest()->get();
    }
    public function updatedFiles()
    {
        logger('updatedFiles');
        // This method fires automatically when files are updated
        $this->uploadFiles();
    }
    public function uploadDroppedFiles($droppedFiles)
    {
        logger('Dropped files:', $droppedFiles);

        // Convert the raw file input into a proper UploadedFile object
        $validatedFiles = [];
        foreach ($droppedFiles as $file) {
            if ($file instanceof UploadedFile) {
                $validatedFiles[] = $file;
            }
        }

        $this->files = $validatedFiles; // Assign files to $this->files
        $this->uploadFiles();
    }
    public function uploadFiles()
    {
        if (count($this->files) > 10) {
            $this->reset('files'); // Clear the files
            session()->flash('error', 'You can only upload up to 10 files at once.');
            return;
        }

        $this->validate([
            'files.*' => 'required|file|mimes:jpg,jpeg,png,gif,pdf,doc,docx,xls,xlsx,txt|max:5120',
        ], [
            'files.*.mimes' => 'Only specific file types are allowed (jpg, png, pdf, etc.)',
            'files.*.max' => 'Each file must not exceed 2MB in size.',
        ]);

        foreach ($this->files as $file) {
            $path = $file->store('attachments', 'public');
            $attachment = Attachment::create([
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $path,
                'uploaded_by' => auth()->id(),
            ]);

            // Attach to task or project
            if ($this->taskId) {
                $attachment->tasks()->attach($this->taskId, ['is_reference' => false]);
            } elseif ($this->projectId) {
                $attachment->projects()->attach($this->projectId);
            }

        }

        $this->loadAttachments();
        $this->reset('files'); // Reset file input
    }

    public function deleteAttachment($attachmentId)
    {
        // Find the attachment
        $attachment = Attachment::findOrFail($attachmentId);

        // Delete the file from storage
        \Storage::disk('public')->delete($attachment->file_path);

        // Detach relationships (tasks or projects) and delete the record
        $attachment->tasks()->detach();
        $attachment->projects()->detach();
        $attachment->delete();

        // Refresh the list of attachments
        $this->loadAttachments();

        // Optional: Flash a success message
        session()->flash('success', 'File deleted successfully.');
    }



    public function render()
    {
        return view('livewire.file-upload');
    }
}
