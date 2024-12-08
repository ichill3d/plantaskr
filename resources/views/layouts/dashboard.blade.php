<x-app-layout>
    <div class="flex h-screen bg-white">
        <!-- Sidebar -->
        <x-sidebar :nav-items="$navItems">
            <x-slot:navTitle>
                @yield('navTitle')
            </x-slot:navTitle>
        </x-sidebar>

        <!-- Main Content -->
        <main class="flex-1">
            <x-dashboadHeaderTitle>@yield('dashboadHeaderTitle')</x-dashboadHeaderTitle>
            <div class="bg-white p-6">
                @yield('content')
            </div>
        </main>
    </div>
</x-app-layout>
