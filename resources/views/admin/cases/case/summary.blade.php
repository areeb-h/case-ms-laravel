<!-- Inside summary.blade.php -->
<div class="container-2 shadow-sm space-y-4">

    <div class="flex justify-between items-center">
        <p class="font-bold text-lg pl-2">{{ $case->case_title }}</p>
        <div class="flex space-x-4 items-center">
            <p class="px-2.5 py-1 rounded-lg w-[75px] text-center {{ $case->status == 'Closed' ? 'bg-orange-100' : 'bg-green-100' }}">
                {{ $case->status }}
            </p>
            <p class="px-2.5 py-1 bg-slate-100 rounded-lg">Case Number: {{ $case->case_number }}</p>
        </div>
    </div>

    <div class="flex justify-between items-center border-t border-slate-100/90 pt-4">
        <div class="flex space-x-4">
            <div class="flex items-center /space-x-2 pl-6 p-2 w-fit bg-gray-100/50 rounded-lg">
                <p class="font-semibold">Lawyer: </p> <p class="hidden">{{$case->lawyer->name}}</p>
                <form class="flex space-x-2" id="reassign-form" action="{{ route('admin.cases.reassignLawyer', $case->id) }}" method="POST">
                    @csrf
                    <select class="text-input" name="new_lawyer_id">
                        @foreach($lawyers as $lawyer)
                            <option value="{{ $lawyer->id }}" {{ $lawyer->id == $case->lawyer->id ? 'selected' : '' }}>
                                {{ $lawyer->name }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn-orange">Reassign</button>
                </form>
            </div>
            <div class="flex items-center space-x-2 px-6 p-2 w-fit bg-gray-100/50 rounded-lg">
                <p class="font-semibold">Client: </p>
                <p class="">{{$case->client->name}}</p>
            </div>
        </div>
        <div class="flex items-center px-6 p-3 w-fit h-full bg-gray-100/50 rounded-lg">
            Time taken: {{ $diffInDays }} {{ $diffInDays == 1 ? 'day' : 'days' }} {{ $diffInHours }} {{ $diffInHours == 1 ? 'hour' : 'hours' }}
        </div>
    </div>

    <div class="p-5 border border-slate-200/50 shadow-sm rounded-lg h-fit">
        <div class="flex justify-between">
            <h1 class="font-semibold">Description</h1>
            <button class="btn-white" onclick="toggleEdit()">Edit</button>
        </div>

        <!-- Text view -->
        <div id="descriptionText" class="pt-2.5 text-justify">
            {!! nl2br(e($case->description)) !!}
        </div>

        <!-- Edit form (hidden by default) -->
        <div id="descriptionForm" class="hidden mt-4">
            <form action="{{ route('admin.cases.updateDescription', $case->id) }}" method="POST">
                @csrf
                @method('PUT')
                <textarea name="description" rows="4" class="w-full border-slate-200 rounded-lg mb-4 h-[372px]">{{ $case->description }}</textarea>
                <div class="flex space-x-4 justify-end">
                    <button type="submit" class="btn-red">Cancel</button>
                    <button type="submit" class="btn-teal">Save</button>
                </div>
            </form>
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

<!-- Include this script in your page -->
<script>
    let isEditing = false;

    function toggleEdit() {
        isEditing = !isEditing;
        document.getElementById('descriptionText').classList.toggle('hidden');
        document.getElementById('descriptionForm').classList.toggle('hidden');
    }
</script>






