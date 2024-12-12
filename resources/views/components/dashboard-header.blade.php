<div class="flex justify-between items-center mb-6">
    <h2>{{$title}}</h2>
    @if(isset($action))
        <a href="{{ $action['url'] }}"
           class="px-4 py-2 text-sm font-semibold text-white bg-blue-500 rounded hover:bg-blue-600">
            {{ $action['label'] }}
        </a>
    @endif
</div>
