<!-- actions.blade.php -->
<div class="container-2 overflow-hidden space-y-4">

    <div class="flex justify-between items-center">
        <p class="font-bold text-lg pl-2">{{ $case->case_title }}</p>
        <div class="flex space-x-4 items-center">
            <div x-data="{ isClosed: {{ $case->status == 'Closed' ? 'true' : 'false' }} }">
                <form x-ref="form" action="{{ route('admin.cases.statusUpdate', $case->id) }}" method="post">
                    @csrf
                    <input type="hidden" name="case_status" x-ref="hiddenInput" x-bind:value="isClosed ? 'Open' : 'Closed'">
                    <button type="submit" @click="isClosed = !isClosed;" class="rounded-md border hover:bg-slate-100 py-[3.21px] w-[122px] text-center">
                        <span x-text="isClosed ? 'Reopen Case' : 'Close Case'"></span>
                    </button>
                </form>
            </div>
            <p class="px-2.5 py-1 rounded-lg w-[75px] text-center {{ $case->status == 'Closed' ? 'bg-orange-100' : 'bg-green-100' }}">
                {{ $case->status }}
            </p>
            <p class="px-2.5 py-1 bg-slate-100 rounded-lg">Case Number: {{ $case->case_number }}</p>
        </div>
    </div>


    <div class="border-t">
        <!-- Header -->
        <div class="grid grid-cols-10 gap-4 my-4 items-center font-semibold">
            <div class="col-span-1 px-4">#</div>
            <div class="col-span-2 px-4">Action</div>
            <div class="col-span-3 px-4">Description</div>
            <div class="col-span-2 px-4">User</div>
            <div class="col-span-2 px-4">Date</div>
        </div>

        <!-- Total Actions Count -->
        @php
            $totalActions = count($actions);
        @endphp

        <div class="divide-y divide-black/2 rounded-lg overflow-hidden">
            <!-- Rows -->
            @foreach($actions as $action)
                @php
                    $reverseCount = $totalActions - $loop->index;
                @endphp
                <div class="grid grid-cols-10 gap-3 /rounded-lg items-center {{ $action->action_type === 'Document Deleted' ? 'bg-red-100' : ($action->action_type === 'Document Uploaded' ? 'bg-green-100' : 'bg-slate-100') }}">
                    <div class="col-span-1 p-2 pl-4 rounded-r-md bg-black/5">{{$reverseCount}}</div>
                    <div class="col-span-2 p-2">{{ $action->action_type }}</div>
                    <div class="col-span-3 p-2">{{ $action->description }}</div>
                    <div class="col-span-2 p-2">{{ $action->user->name }} [{{ $action->user->roles->first()->name }}]</div>
                    <div class="col-span-2 p-2 pr-4">
                        <div>{{ \Carbon\Carbon::parse($action->created_at)->format('d M Y') }}</div>
                        <div>{{ \Carbon\Carbon::parse($action->created_at)->format('h:i a') }}</div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Add this to your stylesheet or within a <style> tag -->
<style>
    .toggle-checkbox:checked {
        @apply: right-0 border-green-400;
        right: 0;
        border-color: #68D391;
    }
    .toggle-checkbox:checked + .toggle-label {
        @apply: bg-green-400;
        background-color: #68D391;
    }
    .toggle-label {
        @apply: block w-5 h-5 mt-px bg-white rounded-full shadow-inner transition-transform duration-300 ease-in;
        position: absolute;
        top: 0.25rem;
        left: 0.25rem;
        width: 1rem;
        height: 1rem;
        cursor: pointer;
        transition: background 0.3s ease-in-out;
    }
</style>
