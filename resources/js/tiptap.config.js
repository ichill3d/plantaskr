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

window.Alpine.data('editor', (initialContent, wireModel) => {
    let editor;
    return {
        content: initialContent, // Define content in the Alpine.js state
        updatedAt: Date.now(),   // To force updates
        init() {
            const _this = this;

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
                content: this.content || '', // Initialize editor with content from Alpine.js
                onUpdate({ editor }) {
                    const newContent = editor.getHTML();
                    if (wireModel) {
                        wireModel(newContent); // Sync changes to Livewire
                    }
                    this.content = newContent; // Update Alpine.js state
                    this.updatedAt = Date.now();
                },
            });

            // Watch Livewire for updates to reset editor content
            this.$watch('content', (newContent) => {
                if (editor.getHTML() !== (newContent || '')) {
                    editor.commands.setContent(newContent || '', false);
                }
            });
        },
        isLoaded() {
            return !!editor;
        },
        isActive(type, opts = {}) {
            return editor.isActive(type, opts);
        },
        toggleHeading(opts) {
            editor.chain().toggleHeading(opts).focus().run();
        },
        toggleBold() {
            editor.chain().focus().toggleBold().run();
        },
        toggleBulletList() {
            editor.chain().focus().toggleBulletList().run();
        },
        toggleItalic() {
            editor.chain().toggleItalic().focus().run();
        },
    };
});
