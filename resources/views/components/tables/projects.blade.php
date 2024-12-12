<tr>
    <td class="border border-gray-300 px-4 py-2">{{ $item->id }}</td>
    <td class="border border-gray-300 px-4 py-2">
        <a href="{{ route('organizations.projects.show',
                            [
                                'project_id'=> $item->id,
                                'id' => $team['id'],
                                'organization_alias' => $team['alias']
                            ]) }}">{{ $item->name }}</a>
    </td>
    <td class="border border-gray-300 px-4 py-2">{{ $item->description }}</td>
    <td class="border border-gray-300 px-4 py-2">
        <strong>{{ $item->created_at->format('d M Y') }}</strong>
    </td>
</tr>
