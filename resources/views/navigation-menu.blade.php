<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        plantaskr
{{--                        <x-application-mark class="block h-9 w-auto" />--}}
                    </a>
                </div>

                <!-- Organization Name -->
                @if (auth()->user()->currentTeam)
                    <div class="relative ms-4 flex items-center text-gray-600 text-sm font-medium">

                        <!-- Organization Name Link -->
                        <a href="{{ route('organizations.overview', [auth()->user()->currentTeam->id, auth()->user()->currentTeam->alias]) }}" class="ms-4 flex items-center text-gray-600 text-sm font-medium">
                            {{ auth()->user()->currentTeam->name }}
                        </a>

                        <!-- Dropdown Trigger -->
                        <button id="org-dropdown-trigger" class="ml-2 flex items-center text-gray-500 hover:text-gray-700 focus:outline-none">
                            <svg class="h-4 w-4 transition" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div id="org-dropdown-menu" class="absolute top-9 left-0 mt-2 w-64 bg-white border border-gray-200 rounded-lg shadow-lg opacity-0 invisible transition-opacity duration-200 z-50">
                            <!-- Owned Teams -->
                            <div class="px-4 py-2 text-xs text-gray-500 uppercase tracking-wider">
                                Owned Teams
                            </div>
                            @php
                                $ownTeams = \App\Models\Team::where('user_id', auth()->id())->with('owner')->get();
                            @endphp
                            @foreach ($ownTeams as $team)
                                <a href="{{ route('organizations.overview', ['id' => $team->id, 'organization_alias' => $team->alias]) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    {{ $team->name }}
                                </a>
                            @endforeach

                            <div class="border-t border-gray-200 my-1"></div>

                            <!-- Teams You Are a Member Of -->
                            <div class="px-4 py-2 text-xs text-gray-500 uppercase tracking-wider">
                                Teams You Are a Member Of
                            </div>
                            @php
                                $memberTeams = \App\Models\Team::whereHas('users', function ($query) {
                                    $query->where('user_id', auth()->id());
                                })->where('user_id', '!=', auth()->id())->with('owner')->get();
                            @endphp
                            @foreach ($memberTeams as $team)
                                <a href="{{ route('organizations.overview', ['id' => $team->id, 'organization_alias' => $team->alias]) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    {{ $team->name }}
                                </a>
                            @endforeach

                            <!-- Personal Space -->
                            <a href="{{ route('personal.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Personal Dashboard
                            </a>
                        </div>
                    </div>
                    @push('scripts')
                        <script>
                            document.addEventListener('DOMContentLoaded', function () {
                                const trigger = $('#org-dropdown-trigger');
                                const menu = $('#org-dropdown-menu');

                                // Toggle dropdown on click
                                trigger.click(function (e) {
                                    e.stopPropagation(); // Prevent the click from bubbling up
                                    menu.toggleClass('opacity-0 invisible opacity-100 visible');
                                });

                                // Close dropdown when clicking outside
                                $(document).click(function () {
                                    if (!menu.hasClass('invisible')) {
                                        menu.addClass('opacity-0 invisible').removeClass('opacity-100 visible');
                                    }
                                });

                                // Prevent dropdown from closing when interacting with it
                                menu.click(function (e) {
                                    e.stopPropagation();
                                });
                            });
                        </script>
                    @endpush

                @endif


                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link href="{{ route('tasks.index'/*, ['user_id' => auth()->id()]*/) }}" :active="request()->routeIs('tasks.*')">
                        {{ __('Tasks') }}
                    </x-nav-link>
                    <x-nav-link href="{{ route('organizations.projects', [
                                                    auth()->user()->currentTeam->id,
                                                    auth()->user()->currentTeam->alias
                                                    ]) }}" :active="request()->routeIs('organizations.projects*')">

                        {{ __('Projects') }}
                    </x-nav-link>
                    <x-nav-link href="{{ route('organizations.members', [
                                                    auth()->user()->currentTeam->id,
                                                    auth()->user()->currentTeam->alias
                                                    ]) }}" :active="request()->routeIs('organizations.members*')">
                        {{ __('Members') }}
                    </x-nav-link>
                    <x-nav-link href="#">
                        {{ __('Reports') }}
                    </x-nav-link>
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <!-- Settings Dropdown -->
                <div class="ms-3 relative">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                <button class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                    <img class="size-8 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                                </button>
                            @else
                                <span class="inline-flex rounded-md">
                                    <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150">
                                        {{ Auth::user()->name }}

                                        <svg class="ms-2 -me-0.5 size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                        </svg>
                                    </button>
                                </span>
                            @endif
                        </x-slot>

                        <x-slot name="content">
                            <!-- Account Management -->
                            <div class="block px-4 py-2 text-xs text-gray-400">
                                {{ __('Manage Account') }}
                            </div>

                            <x-dropdown-link href="{{ route('profile.show') }}">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <x-dropdown-link href="{{ route('organizations.index') }}">
                                {{ __('Organizations') }}
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}" x-data>
                                @csrf

                                <x-dropdown-link href="{{ route('logout') }}"
                                                 @click.prevent="$root.submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="size-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link href="#">
                {{ __('Tasks') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link href="{{ route('projects.index') }}" :active="request()->routeIs('projects.*')">
                {{ __('Projects') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link href="#">
                {{ __('Members') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link href="#">
                {{ __('Reports') }}
            </x-responsive-nav-link>
        </div>
    </div>
</nav>
