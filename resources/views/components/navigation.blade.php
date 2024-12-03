<!-- resources/views/components/navigation.blade.php -->
<nav class="bg-white border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center h-16">
        <!-- Logo -->
        <div>
            <a href="/" class="text-lg font-bold text-gray-800">Plantaskr</a>
        </div>

        <!-- Links -->
        <div class="hidden md:flex space-x-4">
            <a href="/features" class="text-gray-600 hover:text-gray-800">Features</a>
            <a href="/pricing" class="text-gray-600 hover:text-gray-800">Pricing</a>
            <a href="/contact" class="text-gray-600 hover:text-gray-800">Contact</a>
        </div>

        <!-- Authentication Links -->
        @if(auth()->check())
            <div>
                <a href="/dashboard" class="text-gray-600 hover:text-gray-800">Dashboard</a>
                <form method="POST" action="{{ route('logout') }}" x-data>
                    @csrf

                    <x-dropdown-link href="{{ route('logout') }}"
                                     @click.prevent="$root.submit();">
                        {{ __('Log Out') }}
                    </x-dropdown-link>
                </form>
            </div>
        @else
            <div class="space-x-4">
                <a href="/login" class="text-gray-600 hover:text-gray-800">Login</a>
                <a href="/register" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Sign Up</a>
            </div>
        @endif
    </div>
</nav>
