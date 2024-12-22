<?php

namespace App\Livewire;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Attachment;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\Request;

class FileUpload extends Component
{
    use WithFileUploads;

    public $files = []; // Holds uploaded files
    public $taskId = null; // Task ID for attachment
    public $projectId = null; // Project ID for attachment
    public $attachments = []; // Holds current file list

    protected $listeners = ['refreshAttachments' => 'loadAttachments'];

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
        // Limit file uploads
        if (count($this->files) > 10) {
            $this->reset('files');
            session()->flash('error', 'You can only upload up to 10 files at once.');
            return;
        }

        // Validate uploaded files
        $this->validate([
            'files.*' => 'required|file|mimes:jpg,jpeg,png,gif,pdf,doc,docx,xls,xlsx,txt|max:5120',
        ], [
            'files.*.mimes' => 'Only specific file types are allowed (jpg, png, pdf, etc.).',
            'files.*.max' => 'Each file must not exceed 5MB in size.',
        ]);

        // Atomic Transaction for Safety
        DB::transaction(function () {
            foreach ($this->files as $file) {
                $attachment = Attachment::uploadAttachment($file);

                // Ensure attachment exists
                if (!$attachment) {
                    throw new \Exception('Failed to upload attachment: ' . $file->getClientOriginalName());
                }

                // Attach to Task or Project
                if ($this->taskId) {
                    $attachment->tasks()->attach($this->taskId, ['is_reference' => false]);
                } elseif ($this->projectId) {
                    $attachment->projects()->attach($this->projectId);
                }
            }
        });

        // Refresh attachments and reset files
        $this->loadAttachments();
        $this->reset('files');

        session()->flash('success', 'Files uploaded successfully.');
    }

    public function deleteAttachment($attachmentId)
    {
        try {
            Attachment::deleteAttachment($attachmentId);

            // Refresh attachments if you're using Livewire or need to re-fetch
            $this->loadAttachments();

            session()->flash('success', 'Attachment deleted successfully.');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to delete attachment: ' . $e->getMessage());
        }
    }

    public function handleFileDrop(Request $request)
    {
        $file = $request->file('file'); // Retrieve the uploaded file

        if (!$file || !$file->isValid()) {
            return response()->json(['error' => 'Invalid file upload.'], 422);
        }

        // Validate the file
        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,gif,pdf,doc,docx,xls,xlsx,txt|max:5120',
        ]);

        // Store the file
        $path = $file->store('attachments', 'public');

        // Save the attachment
        $attachment = Attachment::create([
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'uploaded_by' => auth()->id(),
        ]);
        $this->taskId = $request->taskId;
        $this->projectId = $request->projectId;
        // Attach to task or project
        if ($this->taskId) {
            $attachment->tasks()->attach($this->taskId, ['is_reference' => false]);
        } elseif ($request->projectId) {
            $attachment->projects()->attach($request->projectId);
        }
        $this->loadAttachments();
        // Return the URL to the uploaded file
        return response()->json([
            'url' => \Storage::url($path),
        ]);
    }



    public function render()
    {
        return view('livewire.file-upload');
    }
}
