<div
    x-data="{
        isDragging: false,
        files: [],
        previews: [],
        handleDrop(event) {
            event.preventDefault();
            this.isDragging = false;
            const newFiles = [...event.dataTransfer.files];
            this.files = [...this.files, ...newFiles];
            this.generatePreviews(newFiles);
        },
        handleFileInput(event) {
            const newFiles = [...event.target.files];
            this.files = [...this.files, ...newFiles];
            this.generatePreviews(newFiles);
        },
        generatePreviews(newFiles) {
            newFiles.forEach(file => {
                const reader = new FileReader();
                if (file.type.startsWith('image/')) {
                    reader.onload = (e) => {
                        this.previews.push({
                            type: 'image',
                            src: e.target.result,
                            name: file.name,
                        });
                    };
                    reader.readAsDataURL(file);
                } else {
                    this.previews.push({
                        type: 'file',
                        fileType: file.type || 'File',
                        name: file.name,
                    });
                }
            });
        }
    }"
    @dragover.prevent="isDragging = true"
    @dragleave.prevent="isDragging = false"
    @drop.prevent="handleDrop($event)"
    class="relative border-4 border-dashed rounded-lg p-6 text-center cursor-pointer transition-all duration-200 ease-in-out"
    :class="{'border-blue-500 bg-blue-50': isDragging, 'border-gray-300 bg-gray-50': !isDragging}"
>
    <!-- Hidden File Input -->
    <input
        type="file"
        multiple
        class="absolute inset-0 opacity-0 cursor-pointer"
        @change="handleFileInput($event)"
    >

    <!-- Content -->
    <div>
        <div x-show="!isDragging">
            <p class="text-gray-600 text-lg font-semibold mb-2">Drag & drop files here</p>
            <p class="text-gray-500 text-sm">or click to browse</p>
        </div>
        <div x-show="isDragging" class="text-blue-600 text-lg font-semibold">
            Drop your files here...
        </div>
    </div>

    <!-- File Previews -->
    <div class="mt-6 grid grid-cols-3 gap-4">
        <template x-for="preview in previews" :key="preview.name">
            <div class="flex flex-col items-center">
                <!-- Image Preview -->
                <template x-if="preview.type === 'image'">
                    <img :src="preview.src" class="w-24 h-24 object-cover border rounded shadow-md" alt="Image Preview">
                </template>
                <!-- Default Preview for Non-Image Files -->
                <template x-if="preview.type === 'file'">
                    <div class="w-24 h-24 flex items-center justify-center border rounded bg-gray-100 text-gray-600 shadow-md">
                        <span class="text-sm text-center" x-text="preview.fileType"></span>
                    </div>
                </template>
                <!-- File Name -->
                <span class="text-xs mt-2 truncate w-24 text-gray-700" x-text="preview.name"></span>
            </div>
        </template>
    </div>
</div>
