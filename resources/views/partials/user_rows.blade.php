@foreach ($users as $user)
    <tr class="divide-y divide-gray-200 clickable-row hover:cursor-pointer hover:bg-slate-50" data-id="{{ $user->id }}">
        <td>{{ $user->id }}</td>
        <td>{{ $user->name }}</td>
        <td>{{ $user->email }}</td>
        <td>
            @foreach($user->getRoleNames() as $role)
                {{ $role }}
            @endforeach
        </td>
        <td class="flex space-x-2 button-inside-row justify-between">
            <!-- Activation/Deactivation Buttons -->
            @if ($user->is_active)
                <form action="{{ route('admin.users.deactivate', $user->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="{{ Auth::user()->id == $user->id ? 'btn-orange-disabled ' : 'btn-orange' }}" {{ Auth::user()->id == $user->id ? 'disabled' : '' }}>Deactivate</button>
                </form>
            @else
                <form action="{{ route('admin.users.activate', $user->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="{{ Auth::user()->id == $user->id ? 'btn-orange-disabled ' : 'btn-green' }}" {{ Auth::user()->id == $user->id ? 'disabled' : '' }}>Activate</button>
                </form>
            @endif

            <!-- Delete User Button -->
            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete the user?')">
                @csrf
                @method('DELETE')
                <button
                    id="delete"
                    type="submit"
                    class="{{ Auth::user()->id == $user->id ? 'p-1.5 bg-gray-300/50 border rounded-md' : 'p-1.5 bg-red-100 border border-red-200 hover:bg-red-100/50 rounded-md' }}"
                    {{ Auth::user()->id == $user->id ? 'disabled' : '' }}
                >
                    <svg width="18" height="18" viewBox="0 0 24 24">
                        <path d="M5 4V2h14v2h1v2h-16V4h1zM6 20v-14h12v14h-12zM17 6h-10v12h10v-12z"></path>
                        <line x1="8" y1="11" x2="8" y2="16"></line>
                        <line x1="16" y1="11" x2="16" y2="16"></line>
                    </svg>
                </button>
            </form>
        </td>
    </tr>
@endforeach
