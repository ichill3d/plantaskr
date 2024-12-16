<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    @livewireStyles
</head>
<body class="font-sans antialiased bg-gray-200">

<div class="grid grid-cols-[15rem_minmax(0,1fr)] h-[calc(100vh-0.5rem)] overflow-hidden px-2 pt-2 space-x-2 max-w-9xl mx-auto sm:px-6 lg:px-8">
    <!-- Left column (fixed) -->
    <div class="w-60 h-full flex flex-col overflow-hidden">
        <!-- Sidebar Content -->
        <div class="bg-white h-full rounded-xl flex flex-col overflow-hidden">
            <!-- Top Section -->
            <div class="text-xl text-center border-b p-2 mb-2 font-bold">
                @yield('sidebarTitle')
            </div>
            <!-- Middle Content -->
            <div class="px-4 py-6 flex-grow overflow-auto">
                @yield('sidebar')
            </div>
            <!-- Footer Section -->
            <div class="mt-auto border-t border-gray-100">
                @yield('sidebarFooter')
            </div>
        </div>
    </div>

    <!-- Right column (scrollable) -->
    <div class="flex flex-col h-full overflow-hidden">
        <!-- Sticky Header -->
        <div class="flex gap-4 mb-2">
            <!-- First column (fixed width w-20) -->
            <div class="w-80">
                @yield('headerLeft')
            </div>

            <!-- Second column (flexible width) -->
            <div class="flex-1">
                @yield('headerCenter')
            </div>

            <!-- Third column (fixed width w-10) -->
            <div class="w-32 flex items-center">
                @yield('headerRight')
            </div>
        </div>
        <!-- Scrollable Content -->
        <div class="flex-1 overflow-auto bg-white shadow rounded-xl p-4">
            @yield('content')
        </div>
    </div>
</div>

@stack('modals')

@livewireScripts

<!-- Include pushed scripts -->
@stack('scripts')
</body>
</html>
