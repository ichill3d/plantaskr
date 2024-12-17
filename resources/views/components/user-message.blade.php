<div x-data="{
        open: false,
        type: '',
        title: '',
        message: '',
        action: null,

        show(config) {
            this.type = config.type;
            this.title = config.title || '';
            this.message = config.message || '';
            this.action = config.action || null;
            this.open = true;
        },

        confirm() {
            if (this.action) this.action();
            this.open = false;
        }
    }" x-init="
        window.addEventListener('usermessage-show', (event) => {
            show(event.detail);
        });
    " x-cloak>
    <!-- Overlay -->
    <div x-show="open" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" x-transition>
        <div class="bg-white rounded-lg p-6 w-80">
            <!-- Title -->
            <h2 class="text-lg font-semibold mb-4" x-text="title"></h2>

            <!-- Message -->
            <p class="text-gray-700 mb-6" x-text="message"></p>

            <!-- Buttons -->
            <div class="flex justify-end space-x-2">
                <button @click="open = false" class="px-3 py-1 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
                <button @click="confirm()" class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">OK</button>
            </div>
        </div>
    </div>
</div>
