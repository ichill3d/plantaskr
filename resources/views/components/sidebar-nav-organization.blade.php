<ul class="space-y-1">
    <li>
        <x-side-menu-item
            label="Overview"
            link="{{ route('organizations.overview', ['id' => $team->id, 'organization_alias' => $team->alias]) }}"
            :active="request()->routeIs('organizations.overview')"
        />
    </li>
    <li>
        <x-side-menu-item
            label="Projects"
            link="{{ route('organizations.projects', ['id' => $team->id, 'organization_alias' => $team->alias]) }}"
            :active="request()->routeIs('organizations.projects*')"
        />
    </li>
    <li>
        <x-side-menu-item
            label="Tasks"
            link="{{ route('organizations.tasks', ['id' => $team->id, 'organization_alias' => $team->alias]) }}"
            :active="request()->routeIs('organizations.tasks')"
        />
    </li>
    <li>
        <x-side-menu-item
            label="Members"
            link="{{ route('organizations.members', ['id' => $team->id, 'organization_alias' => $team->alias]) }}"
            :active="request()->routeIs('organizations.members')"
        />
    </li>
    <li>
        <x-side-menu-item
            label="Members Management"
            link="{{ route('organizations.members.management', ['id' => $team->id, 'organization_alias' => $team->alias]) }}"
            :active="request()->routeIs('organizations.members.management')"
        />
    </li>
    <li>
        <x-side-menu-item
            label="Settings"
            link="{{ route('organizations.settings', ['id' => $team->id, 'organization_alias' => $team->alias]) }}"
            :active="request()->routeIs('organizations.settings')"
        />
    </li>






{{--    <li>--}}
{{--        <details class="group [&_summary::-webkit-details-marker]:hidden">--}}
{{--            <summary--}}
{{--                class="flex cursor-pointer items-center justify-between rounded-lg px-4 py-2 text-gray-500 hover:bg-gray-100 hover:text-gray-700"--}}
{{--            >--}}
{{--                <span class="text-sm font-medium"> Projects </span>--}}
{{--                <span class="shrink-0 transition duration-300 group-open:-rotate-180">--}}
{{--                                  <x-icons.arrow-down class="size-5 text-blue-500" />--}}
{{--                                </span>--}}
{{--            </summary>--}}
{{--            <ul class="mt-2 space-y-1 px-4">--}}
{{--                <li>--}}
{{--                    <x-side-menu-item label="Dashboard" link="/about" />--}}
{{--                </li>--}}
{{--                <li>--}}
{{--                    <x-side-menu-item label="Dashboard" link="/about" />--}}
{{--                </li>--}}
{{--            </ul>--}}
{{--        </details>--}}
{{--    </li>--}}


</ul>
