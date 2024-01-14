@foreach ($cases as $case)
    <tr class="divide-y divide-gray-200 clickable-row hover:cursor-pointer hover:bg-slate-50" data-id="{{ $case->id }}">
        <td>{{ $case->id }}</td>
        <td>{{ $case->case_number }}</td>
        <td>{{ $case->case_title }}</td>
        <td>{{ $case->case_type }}</td>
        <td>{{ $case->lawyer->name }}</td>
        <td>{{ $case->client->name }}</td>
        <td>{{ $case->status }}</td>
    </tr>
@endforeach
