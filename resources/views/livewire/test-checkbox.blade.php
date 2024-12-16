<div>
    @foreach (range(1, 5) as $i)
        <label>
            <input
                type="checkbox"
                wire:model="selected"
                value="{{ $i }}"
            >
            Option {{ $i }}
        </label>
    @endforeach
</div>
