<tr>
    <td class="border border-gray-300 px-4 py-2">{{ $item->id }}</td>
    <td class="border border-gray-300 px-4 py-2">
        <a href="{{ route('organizations.tasks.show',
                            [
                                'id' => $team['id'],
                                'organization_alias' => $team['alias'],
                                'task_id' => $item->id
                            ]) }}">{{ $item->name }}</a>
    </td>
    <td class="border border-gray-300 px-4 py-2">{{ $item->description }}</td>
    <td class="border border-gray-300 px-4 py-2">
        <strong>{{ $item->created_at->format('d M Y') }}</strong>
    </td>
</tr>
