<div
    x-data="editor(
        @entangle($attributes->wire('model')->value()).live,
        value => $wire.set('{{ $attributes->wire('model')->value() }}', value)
    )"
    data-task-id="{{ $attributes->get('taskId', null) }}"
    data-project-id="{{ $attributes->get('projectId', null) }}"
    wire:ignore
    {{ $attributes->except(['wire:model', 'taskId', 'projectId']) }}
>
    <template x-if="isLoaded()">
        <div class="menu flex space-x-2 mb-2 border-b pb-2">
            <button
                type="button"
                class="px-3 py-1 border rounded-md hover:bg-gray-100"
                @click="toggleHeading({ level: 2 })"
                :class="{ 'bg-gray-200 font-bold': isActive('heading', { level: 2 }) }"
            >
                H1
            </button>
            <button
                type="button"
                class="px-3 py-1 border rounded-md hover:bg-gray-100 flex items-center justify-center"
                @click="toggleBold()"
                :class="{ 'bg-gray-200 font-bold': isActive('bold') }"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 24 24"
                    fill="currentColor"
                    class="w-5 h-5"
                >
                    <path d="M8 11H12.5C13.8807 11 15 9.88071 15 8.5C15 7.11929 13.8807 6 12.5 6H8V11ZM18 15.5C18 17.9853 15.9853 20 13.5 20H6V4H12.5C14.9853 4 17 6.01472 17 8.5C17 9.70431 16.5269 10.7981 15.7564 11.6058C17.0979 12.3847 18 13.837 18 15.5ZM8 13V18H13.5C14.8807 18 16 16.8807 16 15.5C16 14.1193 14.8807 13 13.5 13H8Z"></path>
                </svg>
            </button>

            <button
                type="button"
                class="px-3 py-1 border rounded-md hover:bg-gray-100"
                @click="toggleItalic()"
                :class="{ 'bg-gray-200 font-bold': isActive('italic') }"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 24 24"
                    fill="currentColor"
                    class="w-5 h-5"
                >
                    <path d="M15 20H7V18H9.92661L12.0425 6H9V4H17V6H14.0734L11.9575 18H15V20Z"></path>                </svg>
            </button>

            <button
                type="button"
                class="px-3 py-1 border rounded-md hover:bg-gray-100"
                @click="toggleBulletList()"
                :class="{ 'bg-gray-200 font-bold': isActive('bullet_list') }"
            >
                List
            </button>


        </div>
    </template>
    <div
        x-ref="element"
        class="p-4 border rounded-md min-h-[200px] bg-white focus:outline-none prose prose-sm  xl:prose-lg"
    ></div>
</div>
