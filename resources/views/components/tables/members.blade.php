<tr>
    <td class="border border-gray-300 px-4 py-2">{{ $item->name }}</td>
    <td class="border border-gray-300 px-4 py-2">{{ $item->email }}</td>
    <td class="border border-gray-300 px-4 py-2">
        <span class="{{ $item->status === 'active' ? 'text-green-500' : 'text-red-500' }}">
            {{ ucfirst($item->status) }}
        </span>
    </td>
</tr>
