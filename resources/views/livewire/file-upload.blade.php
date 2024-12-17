<div class="relative"
     x-data="{
        isDragging: false,
        handleDrop(event) {
            event.preventDefault();
            this.isDragging = false;

            const input = document.getElementById('file-input');
            input.files = event.dataTransfer.files; // Assign dropped files
            input.dispatchEvent(new Event('change')); // Trigger Livewire upload
        }
     }"
     @dragover.prevent="isDragging = true"
     @dragleave.prevent="isDragging = false"
     @drop="handleDrop($event)"
>
    <!-- Overlay -->
    <div x-show="isDragging"
         class="absolute inset-0 bg-blue-50 bg-opacity-70 flex items-center justify-center z-10"
         x-transition>
        <p class="text-lg font-semibold text-blue-600">Drop files here</p>
    </div>

    <!-- File List -->
    <div class="mt-4" x-data="{ showLightbox: false, lightboxImage: '', lightboxTitle: '' }">
        @if($attachments->isNotEmpty())

            <!-- Grid Container -->
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                @foreach ($attachments as $attachment)
                    <div class="relative group">
                        <!-- Thumbnail -->
                        @if (in_array(strtolower(pathinfo($attachment->file_name, PATHINFO_EXTENSION)), ['jpg', 'png', 'jpeg', 'gif']))
                            <a href="#"
                               @click.prevent="showLightbox = true; lightboxImage = '{{ asset('storage/' . $attachment->file_path) }}'; lightboxTitle = '{{ $attachment->file_name }}'">
                                <img src="{{ asset('storage/' . $attachment->file_path) }}"
                                     alt="{{ $attachment->file_name }}"
                                     class="w-full h-32 object-cover rounded-md">
                            </a>
                        @else
                            <a href="{{ asset('storage/' . $attachment->file_path) }}"
                               target="_blank"
                               class="w-full h-32 bg-gray-200 flex items-center justify-center rounded-md text-gray-600">
                                <span class="text-sm font-semibold">{{ strtoupper(pathinfo($attachment->file_name, PATHINFO_EXTENSION)) }}</span>
                            </a>
                        @endif

                        <!-- Filename -->
                        <div class="mt-1 text-xs text-center text-gray-600 truncate">
                            {{ $attachment->file_name }}
                        </div>

                        <!-- Delete Button -->
                        <button @click="if (document.querySelector('[x-data]')) {
                            $dispatch('usermessage-show', {
                                type: 'confirm',
                                title: 'Confirm Deletion',
                                message: 'Are you sure you want to delete this?',
                                action: () => { @this.call('deleteAttachment', {{ $attachment->id }}) }
                            });
                        }"
                                class="absolute top-1 right-1 bg-red-500 text-white text-xs px-1 py-0.5 rounded hidden group-hover:block">
                            Delete
                        </button>
                    </div>
                @endforeach

                    <!-- Drop Files Here Box -->
                    <div class="w-full h-32 bg-gray-200 flex items-center justify-center rounded-md text-gray-600 border-dashed border-2 border-gray-400 relative">
                        <div class="text-sm font-semibold">Drop Files Here</div>

                        <!-- Hidden File Input -->
                        <input
                            id="file-input"
                            type="file"
                            wire:model="files"
                            multiple
                            class="absolute inset-0 opacity-0 cursor-pointer z-20"
                            accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.xls,.xlsx,.txt"
                            style="z-index: 1;"
                        >
                    </div>

            </div>

            <!-- Custom Lightbox -->
            <div x-show="showLightbox"
                 x-transition
                 class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-75 z-50"
                 @keydown.window.escape="showLightbox = false"
                 @click="showLightbox = false">
                <div class="relative p-4" @click.stop>
                    <!-- Close Button -->
                    <button @click="showLightbox = false"
                            class="absolute top-4 right-4 text-white text-3xl font-bold hover:text-gray-300">
                        &times;
                    </button>
                    <!-- Image -->
                    <img :src="lightboxImage" alt="Lightbox Image" class="max-w-full max-h-screen mx-auto rounded-md shadow-lg">
                    <!-- Title -->
                    <p class="text-center text-white mt-2 text-sm truncate" x-text="lightboxTitle"></p>
                </div>
            </div>
        @else
            <p class="text-gray-500">No files uploaded yet.</p>
        @endif
    </div>
</div>
