<div>
    <table class="table-auto border-collapse border border-gray-300 w-full">
        <thead>
        <tr>
            @foreach ($columns as $column)
                <th class="border border-gray-300 px-4 py-2">{{ $column['label'] }}</th>
            @endforeach
        </tr>
        </thead>
        <tbody>
        @foreach ($data as $item)
            @includeIf("components.tables.$dataType", ['item' => $item, 'columns' => $columns, 'team' => $team])
        @endforeach
        </tbody>
    </table>
</div>
