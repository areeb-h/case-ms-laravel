<div x-data="{ open: false }" @click.away="open = false">
    <button @click="open = !open" class="block px-4 py-2 text-gray-800 border rounded-xl hover:text-blue-600">
        {{ Auth::user()->name }}
    </button>
    <div x-show="open" class="z-10 text-center origin-top-right absolute right-0 mt-2 w-fit rounded-lg shadow-sm bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-100">
        <!-- Dropdown content here -->
        <div class="px-4 py-3 text-sm text-gray-900 dark:text-white">
            <div class="font-medium"> {{ Auth::user()->email }}</div>
        </div>
        <a href="{{ route('admin.profile.edit') }}" class="block px-4 py-2 hover:bg-gray-100">Settings</a>

        <form class="w-full" onsubmit="return confirm('Are you sure you want to sign out?')" id="logout-form" action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="w-full py-2 text-red-600 hover:bg-gray-100 hover:rounded-b-lg">
                {{ __('Logout') }}
            </button>
        </form>
    </div>
</div>

