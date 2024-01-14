@extends('layouts.app')

@section('content')
    <main>
        <div class="table-container" x-data="{ open: false, userId: null, name: '', email: '', role: '', updateUser(user) { this.userId = user.id; this.name = user.name; this.email = user.email; this.role = user.role; this.open = true; }}" x-init="() => { window.openModal = openModal }">
            <div class="flex justify-between">
                <!-- Search and Filter Form -->
                <div class="flex space-x-4">
                    <!-- Search bar (live search) -->
                    <input type="text" id="search-box" placeholder="Search by any field" class="border rounded-lg">

                    <!-- Role filter -->
                    <select id="type-filter" class="border rounded-lg">
                        <option value="">All Types</option>
                        <option value="CIVIL">Civil</option>
                        <option value="HOMICIDE">Homicide</option>
                        <option value="THEFT">Theft</option>
                        <option value="ASSAULT">Assault</option>
                        <option value="DRUG_POSSESSION">Drug Possession</option>
                        <option value="DUI">DUI</option>
                        <option value="FRAUD">Fraud</option>
                    </select>

                    <!-- Status filter -->
                    <select id="status-filter" class="border rounded-lg">
                        <option value="">All Status</option>
                        <option value="1">Open</option>
                        <option value="0">Closed</option>
                    </select>

                </div>
                <a href="{{ route('admin.cases.create') }}" class="btn-teal-lg">Create New Case</a>
            </div>

            <!-- Case Table -->
            <table id="case-table" class="mt-4">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Case Number</th>
                    <th>Case Title</th>
                    <th>Case Type</th>
                    <th>Lawyer</th>
                    <th>Client</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tbody id="case-table-body"></tbody>
            </table>

        </div>
    </main>

    <script>

        // Variable to store the selected user's ID
        let selectedUserId = null;

        function fetchUsers() {
            let query = $('#search-box').val();
            let type = $('#type-filter').val();
            let status = $('#status-filter').val();

            $.ajax({
                url: "{{ route('admin.cases') }}",
                type: "GET",
                data: {
                    'search_query': query,
                    'role': type,
                    'status': status
                },
                success: function(data) {
                    $('#case-table-body').html(data);
                }
            });
        }

        $(document).ready(function() {
            // Initial fetch
            fetchUsers();

            // Fetch on search or filter change
            $('#search-box, #type-filter, #status-filter').on('change keyup', fetchUsers);

            // Fetch users when table row is clicked
            $(document).on('click', '.clickable-row', function(event) {
                event.stopPropagation();
                // Store the selected case's ID
                const selectedCaseId = $(this).data('id');

                // Redirect to the edit page
                window.location.href = `/admin/cases/${selectedCaseId}/edit`;
            });

            // Close modal logic (add an 'x' button or close button to your modal)
            $("#close-modal").click(function() {
                $("#modify-modal").addClass("hidden");
            });

            // AJAX to update user
            $('#save-button').click(function() {
                // Get form data
                const userId = $('#userId').val();
                const name = $('#name').val();
                const email = $('#email').val();
                const role = $('#role').val();

                console.log(userId)

                $.ajax({
                    url: '/admin/users/' + userId, // Assuming this is the update URL
                    type: 'PUT', // Use PUT for update
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }, // CSRF token
                    data: {
                        id: userId,
                        name: name,
                        email: email,
                        role: role
                    },
                    success: function(data) {
                        // Close the modal
                        $("#modify-modal").addClass("hidden");

                        // Refresh user list or handle data
                        fetchUsers();
                    },
                    error: function(err) {
                        // Handle error
                        console.error('An error occurred:', err);
                    }
                });
            });
            // New code
            $(document).on('click', '.button-inside-row', function(event) {
                event.stopPropagation();
            });
        });
    </script>
@endsection
