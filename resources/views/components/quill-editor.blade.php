<div>
    <!-- Quill Editor Container -->
    <div id="quill-editor-{{ $name }}" class="quill-editor h-64 border rounded-md" style="max-height:1000px; "></div>
    <!-- Hidden Input for Sync -->
    <input type="hidden" id="quill-input-{{ $name }}" name="{{ $name }}" wire:model.defer="{{ $name }}">

    @script
    <script>
        setTimeout(function () {
            initializeQuillEditor('{{ $name }}');
        }, 100);

        Livewire.on('refreshQuillContent', function (event) {
            setTimeout(function () {
                initializeQuillEditor('{{ $name }}');
            }, 100);
        });

        function initializeQuillEditor(name) {
            const editorSelector = `#quill-editor-${name}`;
            const inputSelector = `#quill-input-${name}`;
            const editorElement = document.querySelector(editorSelector);
            const inputElement = document.querySelector(inputSelector);

            if (editorElement && inputElement && !editorElement.__quillInitialized) {
                const quill = new Quill(editorSelector, {
                    theme: 'snow',
                    modules: {
                        toolbar: [
                            ['bold', 'italic', 'underline', 'strike'], // Basic formatting
                            [{ 'list': 'ordered' }, { 'list': 'bullet' }], // Lists
                            [{ 'header': [1, 2, 3, false] }], // Headers
                            ['link', 'image'], // Links and images
                        ]
                    }
                });

                editorElement.__quillInitialized = true;

                // Set initial content from Livewire-bound property
                quill.root.innerHTML = inputElement.value;

                // Automatically resize editor on content change
                quill.on('text-change', function () {
                    inputElement.value = quill.root.innerHTML;
                    inputElement.dispatchEvent(new Event('input')); // Trigger Livewire binding
                    adjustQuillHeight(editorElement, quill);
                });

                // Adjust height on initialization
                adjustQuillHeight(editorElement, quill);

                console.log('Quill editor initialized:', editorSelector);
            }
        }
    </script>
    @endscript
</div>
