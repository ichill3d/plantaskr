// Define setupEditor before Alpine
import { Editor } from '@tiptap/core';
import StarterKit from '@tiptap/starter-kit';
import FileHandler from '@tiptap-pro/extension-file-handler'
import Image from '@tiptap/extension-image'

async function handleFiles(currentEditor, files, pos = null) {
    for (const file of files) {
        if (file.type.startsWith('image/')) {
            const formData = new FormData();
            formData.append('file', file);

            // Optional: Add taskId or projectId if needed
            const taskId = document.querySelector('[data-task-id]')?.dataset.taskId;
            const projectId = document.querySelector('[data-project-id]')?.dataset.projectId;

            if (taskId) formData.append('taskId', taskId);
            if (projectId) formData.append('projectId', projectId);

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            try {
                const response = await fetch('/upload-file-endpoint', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken },
                    body: formData,
                });

                if (response.ok) {
                    const { url } = await response.json();

                    // Insert the uploaded image into the editor
                    currentEditor.chain().insertContentAt(pos || currentEditor.state.selection.anchor, {
                        type: 'image',
                        attrs: { src: url },
                    }).focus().run();
                    Livewire.dispatch('refreshAttachments');
                } else {
                    console.error('File upload failed:', await response.text());
                }
            } catch (error) {
                console.error('File upload error:', error);
            }
        }
    }
}

window.Alpine.data('editor', (content, wireModel) => {
    let editor
    return {
        updatedAt: Date.now(), // force Alpine to rerender on selection change
        init() {
            const _this = this

            // Get taskId and projectId from the DOM
            const taskId = this.$el.getAttribute('data-task-id');
            const projectId = this.$el.getAttribute('data-project-id');

            const plainContent = typeof content === 'object' && content.initialValue
                ? content.initialValue
                : content;

            editor = new Editor({
                element: this.$refs.element,
                extensions: [
                    StarterKit,
                    Image.configure({
                        inline: true,
                    }),
                    FileHandler.configure({
                        allowedMimeTypes: ['image/png', 'image/jpeg', 'image/gif', 'image/webp'],
                        onDrop: (currentEditor, files, pos) => {
                            handleFiles(currentEditor, files, pos);
                        },
                        onPaste: (currentEditor, files) => {
                            handleFiles(currentEditor, files);
                        },
                    }),
                ],
                content: plainContent || '',
                onCreate({ editor }) {

                    _this.updatedAt = Date.now()
                },
                onUpdate({ editor }) {
                    content = editor.getHTML();
                    if (wireModel) {
                        wireModel(content); // Sync changes to Livewire
                    }
                    _this.updatedAt = Date.now()
                },
                onSelectionUpdate({ editor }) {
                    _this.updatedAt = Date.now()
                },
            })
        },
        isLoaded() {
            return editor
        },
        isActive(type, opts = {}) {
            return editor.isActive(type, opts)
        },
        toggleHeading(opts) {
            editor.chain().toggleHeading(opts).focus().run()
        },
        toggleBold() {
            editor.chain().focus().toggleBold().run()
        },
        toggleBulletList() {
            editor.chain().focus().toggleBulletList().run()
        },
        toggleItalic() {
            editor.chain().toggleItalic().focus().run()
        },
    };
});
