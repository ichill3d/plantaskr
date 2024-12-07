<x-app-layout>
    <div class="flex h-screen bg-gray-100">
        <!-- Sidebar -->
        <aside class="w-1/5 bg-white shadow-md">
            <div class="p-4">
                <h2 class="text-lg font-bold text-gray-800">Organizations</h2>
                <nav class="mt-4">
                    <ul>
                        <li class="mb-2">
                            <a href="{{ route('organizations.dashboard', $team->id) }}"
                               class="block px-4 py-2 text-gray-600 rounded hover:bg-gray-200 hover:text-gray-800">
                                Dashboard
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="{{ route('organizations.projects', $team->id) }}"
                               class="block px-4 py-2 text-gray-600 rounded hover:bg-gray-200 hover:text-gray-800">
                                Projects
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="{{ route('organizations.members', $team->id) }}"
                               class="block px-4 py-2 text-gray-600 rounded hover:bg-gray-200 hover:text-gray-800">
                                Members
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6">
            <header class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Organization: {{ $team->name }}</h1>
                <a href="#"
                   class="px-4 py-2 text-sm font-semibold text-white bg-blue-500 rounded hover:bg-blue-600">
                    Add New Project
                </a>
            </header>
            <div class="bg-white p-6 rounded shadow">
                @yield('content')
            </div>
        </main>
    </div>
</x-app-layout>
