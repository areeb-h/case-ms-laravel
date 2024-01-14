<!-- resources/views/admin/cases/create.blade.php -->

@extends('layouts.app')

@section('content')
    <main>
        <div class="container">
            <div class="bg-white mx-auto p-5 rounded-xl space-y-5 w-fit">

                <!-- Success Toast -->
                @if (session('success'))
                    <div id="toast" class="fixed bottom-4 right-4 bg-green-500/80 text-white py-2 px-4 rounded-lg">
                        {{ session('success') }}
                        <button class="close-toast text-white ml-2">x</button>
                    </div>
                @endif

                <!-- Error Toast -->
                @if (session('error'))
                    <div id="toast" class="fixed bottom-4 right-4 bg-red-500/80 text-white py-2 px-4 rounded-lg">
                        {{ session('error') }}
                        <button class="close-toast text-white ml-2">x</button>
                    </div>
                @endif

                <h1 class="bg-green-200/30 font-bold text-slate-800 text-center px-3 py-2 rounded-xl /mx-auto /w-fit">Create New Case</h1>

                <form class="space-y-5" action="{{ route('admin.cases.store') }}" method="POST">
                    @csrf

                    <div class="flex space-x-5 h-fit">

                        <div class="space-y-5">
                            <div class="w-full space-y-2">
                                <label for="case_number">Case Number</label>
                                <input class="text-input" type="text" name="case_number" value="{{ old('case_number') }}" required>
                            </div>

                            <div class="w-full space-y-2">
                                <label for="case_title">Case Title</label>
                                <input class="text-input" type="text" name="case_title" value="{{ old('case_title') }}" required>
                            </div>

                            <div class="flex rounded-lg bg-slate-100/50 border space-x-4 p-4">
                                <div class="w-full space-y-2">
                                    <label for="case_type">Case Type</label>
                                    <select class="text-input" name="case_type" required>
                                        <option value="CIVIL">Civil</option>
                                        <option value="HOMICIDE">Homicide</option>
                                        <option value="THEFT">Theft</option>
                                        <option value="ASSAULT">Assault</option>
                                        <option value="DRUG_POSSESSION">Drug Possession</option>
                                        <option value="DUI">Driving Under Influence</option>
                                        <option value="FRAUD">Fraud</option>
                                    </select>
                                </div>

                                <div class="w-full space-y-2">
                                    <label for="Case Role">Case Role</label>
                                    <select class="text-input" name="case_role" required>
                                        <option value="Defendant">Defendant</option>
                                        <option value="Appellant">Appellant</option>
                                    </select>
                                </div>
                            </div>

                            <div class="flex rounded-lg bg-slate-100/50 border space-x-4 p-4">
                                <div class="w-full space-y-2">
                                    <label for="client_id">Client ID</label>
                                    <!-- Clients dropdown -->
                                    <select class="text-input" name="client_id" type="number" required>
                                        @foreach($clients as $client)
                                            <option value="{{ $client->id }}">{{ $client->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="w-full space-y-2">
                                    <label for="lawyer_id">Lawyer ID</label>
                                    <!-- Lawyers dropdown -->
                                    <select  class="text-input" name="lawyer_id" type="number" required>
                                        @foreach($lawyers as $lawyer)
                                            <option value="{{ $lawyer->id }}">{{ $lawyer->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="w-full space-y-2">
                            <label for="description">Description</label>
                            <textarea class="w-full border-teal-300 rounded-lg mb-4 h-[calc(100%-31.2px)]" name="description">{{ old('description') }}</textarea>
                        </div>

                    </div>

                    <div class="flex justify-between mt-5 pt-5 border-t space-x-4">
                        <div class="flex rounded-lg bg-red-300/50 hover:bg-red-300 items-center">
                            <a class="px-4 py-1.5 rounded-lg hover:bg-red-300 w-full" href="{{ url()->previous() }}">Cancel</a>
                        </div>
                        <div class="w-full rounded-lg flex bg-teal-300/50 hover:bg-teal-300 items-center">
                            <button class="w-full" type="submit">Create Case</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main>
@endsection

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
