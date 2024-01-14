<!-- hearings.blade.php -->
<div class="container-2 overflow-hidden space-y-4">
    <div class="flex justify-between items-center">
        <p class="font-bold text-lg pl-2">{{ $case->case_title }}</p>
        <div class="flex space-x-4 items-center">
            <p class="px-2.5 py-1 rounded-lg w-[75px] text-center {{ $case->status == 'Closed' ? 'bg-orange-100' : 'bg-green-100' }}">
                {{ $case->status }}
            </p>
            <p class="px-2.5 py-1 bg-slate-100 rounded-lg">Case Number: {{ $case->case_number }}</p>
        </div>
    </div>

    <!-- Form to Create New Hearing -->
    <form class="p-4 flex flex-col rounded-lg border" action="{{ route('admin.cases.createHearing', $case->id) }}" method="post">
        @csrf

        <div class="flex space-x-4">
            <div class="w-full space-y-2">
                <label for="scheduled_time">Hearing Date and Time:</label>
                <input class="text-input" type="datetime-local" id="scheduled_time" name="scheduled_time">
            </div>
            <div class="w-full space-y-2">
                <!-- Dropdown to select a Judge -->
                <label for="judge_id">Select Judge:</label>
                <select class="text-input" id="judge_id" name="judge_id">
                    <option value="" disabled>Select Judge</option>
                    @foreach ($judges as $judge)
                        <option value="{{ $judge->id }}">{{ $judge->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="flex space-x-4 mt-4">
            <!-- Text area for notes -->
            <textarea name="notes" id="notes" rows="4" class="text-input p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:border-teal-500/20" placeholder="Write your notes here..."></textarea>

            <button class="btn-white" type="submit">Schedule Hearing</button>
        </div>
    </form>

    <div class="bg-slate-50 pt-0.5 rounded-lg">

        <!-- Header -->
        <div class="grid grid-cols-12 gap-4 my-2 items-center font-semibold">
            <div class="col-span-1 p-2 pl-4">#</div>
            <div class="col-span-2 p-2">Hearing Date</div>
            <div class="col-span-2 p-2">Judge</div>
            <div class="col-span-3 p-2">Notes</div>
            <div class="col-span-2 p-2">Status</div>
            <div class="col-span-1 p-2 pr-4">Actions</div>
        </div>

        <!-- Total Hearings Count -->
        @php
            $totalHearings = count($hearings);
        @endphp

        <div class="divide-y divide-black/2 rounded-lg overflow-hidden">
            <!-- Rows -->
            @foreach($hearings as $hearing)
                @php
                    $reverseCount = $totalHearings - $loop->index;
                    $status = \Carbon\Carbon::now()->lessThan(\Carbon\Carbon::parse($hearing->scheduled_time)) ? 'Upcoming' : 'Finished';
                @endphp
                <div class="grid grid-cols-12 gap-3 items-center {{ $status === 'Upcoming' ? 'bg-green-100/20' : 'bg-orange-100/20' }}">
                    <div class="col-span-1 p-2 pl-4 rounded-r-md bg-black/5">{{$reverseCount}}</div>
                    <div class="col-span-2 p-2">
                        {{ \Carbon\Carbon::parse($hearing->scheduled_time)->format('d M Y, h:i a') }}
                    </div>
                    <div class="col-span-2 p-2">{{ $hearing->judge->name }}</div>
                    <div class="col-span-3 p-2 text-justify">{!! nl2br(e($hearing->notes)) !!}</div>
                    <div class="col-span-2 p-2">{{ $status }}</div>
                    <div class="col-span-2 p-2 flex space-x-2">
                            <!-- Edit Button -->
                            <button class="btn-orange items-center">
                                Edit
                            </button>
                            <!-- Delete Button -->
                            <form action="{{ route('admin.hearings.delete', $hearing->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button onclick="return confirm('Are you sure you want to delete this hearing?')" type="submit" class="p-2 bg-red-100 border border-red-200 hover:bg-red-100/50 rounded-md">
                                    <svg width="18" height="18" viewBox="0 0 24 24">
                                        <path d="M5 4V2h14v2h1v2h-16V4h1zM6 20v-14h12v14h-12zM17 6h-10v12h10v-12z"></path>
                                        <line x1="8" y1="11" x2="8" y2="16"></line>
                                        <line x1="16" y1="11" x2="16" y2="16"></line>
                                    </svg>
                                </button>
                            </form>
                    </div>
                </div>
            @endforeach
        </div>

    </div>
</div>

@if (session('error'))
    <div id="toast" class="fixed bottom-4 right-4 bg-red-500/80 text-white py-2 px-4 rounded-lg">
        {{ session('error') }}
        <button class="close-toast text-white ml-2">x</button>
    </div>
@endif

@if (session('success'))
    <div id="toast" class="fixed bottom-4 right-4 bg-green-500/80 text-white py-2 px-4 rounded-lg">
        {{ session('success') }}
        <button class="close-toast text-white ml-2">x</button>
    </div>
@endif

<script>
    setTimeout(() => {
        const toastElements = document.querySelectorAll('#toast');
        toastElements.forEach(toast => {
            toast.classList.add('fade-out');
            setTimeout(() => {
                toast.remove();
            }, 1000); // 1 second to allow the fade-out effect to complete
        });
    }, 6000); // 6000ms = 6 seconds

    // Close button click handler
    document.addEventListener('click', function(event) {
        if (event.target.matches('.close-toast')) {
            const toast = event.target.closest('#toast');
            toast.classList.add('fade-out');
            setTimeout(() => {
                toast.remove();
            }, 1000); // 1 second to allow the fade-out effect to complete
        }
    });
</script>

