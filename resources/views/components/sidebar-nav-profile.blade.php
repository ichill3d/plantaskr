<ul class="space-y-1">
    <li>
        <x-side-menu-item
            label="Dashboard"
            link="{{ route('personal.dashboard') }}" />
    </li>
    <li>
        <x-side-menu-item
            label="Profile Settings"
            link="{{ route('personal.profile') }}" />
    </li>

    <li>
        <x-side-menu-item
            label="Organizations"
            link="{{ route('personal.organizations') }}" />
    </li>

</ul>
