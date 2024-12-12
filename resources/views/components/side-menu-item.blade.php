@props(['link' => '#', 'label' => '', 'active' => false])
<a
    href="{{ $link }}"
    {{ $attributes->merge(['class' => $active ? 'text-gray-700 bg-gray-100' : 'block rounded-lg px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-700 hover:bg-gray-100']) }}
>
    {{ $label }}
</a>
