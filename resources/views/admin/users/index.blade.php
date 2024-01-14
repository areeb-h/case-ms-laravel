@extends('layouts.app')

@section('content')
    <main>
        <div class="table-container" x-data="{ open: false, userId: null, name: '', email: '', role: '', updateUser(user) { this.userId = user.id; this.name = user.name; this.email = user.email; this.role = user.role; this.open = true; }}" x-init="() => { window.openModal = openModal }">
            <div class="flex justify-between space-x-3">
                <!-- Search and Filter Form -->
                <div class="flex space-x-4">
                    <!-- Search bar (live search) -->
                    <input type="text" id="search-box" placeholder="Search by name or email" class="border rounded-lg">

                    <!-- Role filter -->
                    <select id="role-filter" class="border rounded-lg">
                        <option value="">All Roles</option>
                        <option value="Administrator">Administrator</option>
                        <option value="Lawyer">Lawyer</option>
                        <option value="Paralegal">Paralegal</option>
                        <option value="Client">Client</option>
                        <option value="Judge">Judge</option>
                    </select>

                    <!-- Status filter -->
                    <select id="status-filter" class="border rounded-lg">
                        <option value="">All Status</option>
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>

                </div>
                <a href="{{ route('admin.users.create') }}" class="btn-teal-lg">Create New User</a>
            </div>

            <!-- User Table -->
            <table id="user-table" class="mt-4">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody id="user-table-body"></tbody>
            </table>

            <!-- Modal -->
            <div id="modify-modal" class="fixed z-10 inset-0 overflow-y-auto hidden">
                <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-left sm:p-0">
                    <!-- Modal Content -->
                    <div class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full">
                        <div class="bg-white px-6 pt-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                Edit User
                            </h3>
                        </div>

                        <div class="px-6 pb-4">
                            <form id="editUserForm" method="POST" class="space-y-4">
                                @csrf
                                @method('PUT')

                                <input id="userId" type="hidden" name="id">

                                <div>
                                    <label for="name" class="text-sm font-medium text-gray-600">Name</label>
                                    <input id="name" type="text" name="name" class="form-input rounded-lg w-full mt-1 p-2 border-gray-300">
                                </div>

                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-600">Email</label>
                                    <input id="email" type="text" name="email" class="form-input rounded-lg w-full mt-1 p-2 border-gray-300">
                                </div>

                                <div>
                                    <label for="role" class="block text-sm font-medium text-gray-600">Role</label>
                                    <select id="role" name="role" class="form-input rounded-lg w-full mt-1 p-2 border-gray-300">
                                        <option value="" disabled>Select Role</option>
                                        <option value="Administrator">Administrator</option>
                                        <option value="Lawyer">Lawyer</option>
                                        <option value="Paralegal">Paralegal</option>
                                        <option value="Client">Client</option>
                                        <option value="Judge">Judge</option>
                                    </select>
                                </div>
                            </form>
                        </div>

                        <div class="bg-gray-50 px-6 py-3 flex justify-end space-x-4">
                            <button id="close-modal" type="button" class="inline-flex justify-center py-2 px-4 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                Cancel
                            </button>
                            <button type="submit" form="editUserForm" class="inline-flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                Save
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

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

    </main>

    <script>

        // Variable to store the selected user's ID
        let selectedUserId = null;

        function fetchUsers() {
            let query = $('#search-box').val();
            let role = $('#role-filter').val();
            let status = $('#status-filter').val();

            $.ajax({
                url: "{{ route('admin.users') }}",
                type: "GET",
                data: {
                    'search_query': query,
                    'role': role,
                    'status': status
                },
                success: function(data) {
                    $('#user-table-body').html(data);
                }
            });
        }

        $(document).ready(function() {
            // Initial fetch
            fetchUsers();

            // Fetch on search or filter change
            $('#search-box, #role-filter, #status-filter').on('change keyup', fetchUsers);

            // Fetch users when table row is clicked
            $(document).on('click', '.clickable-row', function(event) {
                event.stopPropagation();
                // Store the selected user's ID
                selectedUserId = $(this).data('id');

                $.get("/admin/users/" + selectedUserId + "/edit", function(data) {
                    // Populate modal fields
                    $('#userId').val(data.id);
                    $('#name').val(data.name);
                    $('#email').val(data.email);
                    $('#role').val(data.role);

                    $('#editUserForm').attr('action', '/admin/users/' + selectedUserId);

                    // Open the modal by removing the 'hidden' class
                    $("#modify-modal").removeClass("hidden");
                });
            });

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
                    url: '/admin/users/' + userId,
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
            $(document).on('click', '.button-inside-row', function(event) {
                event.stopPropagation();
            });
        });

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
@endsection
