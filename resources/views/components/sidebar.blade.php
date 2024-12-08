<div class="w-1/5 bg-white shadow-md">
    <!-- Title -->
    <div class="p-4 text-xl font-semibold text-gray-800">
        <h2 class="uppercase text-sm text-gray-400 bg-gray-50 py-1.5 pl-8">{{ $navTitle ?? 'Dashboard' }}</h2>
    </div>

    <!-- Navigation -->
    <div class="p-4">
        <nav>
            <ul>
                @foreach ($navItems as $item)
                    <li class="mb-2">
                        <a href="{{ $item['route'] }}"
                           class="block px-4 py-2 text-gray-600 rounded hover:bg-gray-200 hover:text-gray-800
                           {{ request()->routeIs($item['active']) ? 'bg-gray-200 text-gray-800 font-bold' : '' }}">
                            {{ $item['label'] }}
                        </a>
                    </li>
                @endforeach

            </ul>
        </nav>
    </div>
</div>
