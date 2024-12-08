<x-app-layout>
    <div class="flex h-screen bg-gray-100">
        <!-- Sidebar -->
        <aside class="w-1/5 bg-white shadow-md">
            <div class="p-4">
                <nav class="">
                    <ul>
                        <li class="mb-2">
                            <a href="{{ route('organizations.overview', $team->id) }}"
                               class="block px-4 py-2 text-gray-600 rounded hover:bg-gray-200 hover:text-gray-800">
                                Overview
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
        <main class="flex-1 p-2">
            <div class="bg-white p-6 rounded shadow">
                @yield('content')
            </div>
        </main>
    </div>
</x-app-layout>
