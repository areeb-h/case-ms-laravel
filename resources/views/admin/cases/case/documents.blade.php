<!-- documents.blade.php -->
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

    <form class="flex rounded-lg p-4 justify-between border items-center" action="{{ route('admin.cases.uploadDocument', $case->id) }}" method="POST" enctype="multipart/form-data" onsubmit="return confirm('Are you sure you want to upload this document?')">
        @csrf
        <input id="fileInput" type="file" name="document" class="bg-slate-100 pr-2 rounded-lg" accept=".pdf,.doc,.docx,.jpg,.png" required>
        <div class="flex items-center space-x-4 w-[300px]">
            <label for="document_type">Type</label>
            <select class="text-input" name="document_type" required>
                <option value="">Select Type</option>
                <option value="Case Report">Case Report</option>
                <option value="Others">Others</option>
            </select>
            <button id="uploadButton" class="btn-lg" type="submit">Upload</button>
        </div>
    </form>


@foreach(['Case Report', 'Others'] as $type)
        <div class="container-2 border">
            <h3 class="font-semibold mb-2">{{ $type }}</h3>
            @if(isset($documents[$type]))
                <ul class="grid grid-cols-2 gap-4">
                    @foreach($documents[$type] as $document)
                        <li class="flex items-center justify-between bg-slate-50 w-full border rounded-lg p-2 pr-3">
                            <div class="truncate">
                                <p class="truncate pl-2"> {{ $document->document_name }} </p>
                                <div class="flex ml-2 text-xs text-gray-400">
                                    <div>{{ \Carbon\Carbon::parse($document->created_at)->format('d M Y, ') }}</div>
                                    <div class="ml-1">{{ \Carbon\Carbon::parse($document->created_at)->format('h:i a') }}</div>
                                </div>
                            </div>
                            <div class="flex space-x-2 ml-2">
                                <a href="{{ asset('storage/' . $document->document_path) }}" download>
                                    <button id="download" class="p-2 bg-slate-100 hover:bg-slate-50/50 border rounded-md">
                                        <svg width="18" height="18" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z"/>
                                        </svg>
                                    </button>
                                </a>
                                <form action="{{ route('admin.cases.deleteDocument', $document->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button id="delete" onclick="return confirm('Are you sure you want to delete this document?')"  type="submit" class="p-2 bg-red-100 border border-red-200 hover:bg-red-100/50 rounded-md">
                                        <svg width="18" height="18" viewBox="0 0 24 24">
                                            <path d="M5 4V2h14v2h1v2h-16V4h1zM6 20v-14h12v14h-12zM17 6h-10v12h10v-12z"></path>
                                            <line x1="8" y1="11" x2="8" y2="16"></line>
                                            <line x1="16" y1="11" x2="16" y2="16"></line>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <p>No documents of this type.</p>
            @endif
        </div>
    @endforeach
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






