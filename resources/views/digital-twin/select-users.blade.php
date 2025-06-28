<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row item-center gap-3">
            <!-- Back to phishing campaign -->
            <a href="{{ route('digital-twin.index') }}" class="cursor-pointer">
                <x-primary-button>
                    @lang('digital-twin.users.back')
                </x-primary-button>
            </a>
            <!-- Breadcrumb -->
            <ul class="flex flex-row gap-1 flex-wrap break-words text-sky-800">
                <li><a href="{{ route('digital-twin.index') }}">@lang('digital-twin.users.digitalTwin')</a></li>
                <li>/</li>
                <li><a href="{{ route('digital-twin.new') }}">@lang('digital-twin.users.newDigitalTwin')</a></li>
                <li>/</li>
                <li>@lang('digital-twin.users.selectUsers')</li>
            </ul>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8 text-sky-900">

                    <p class="flex text-lg font-medium text-sky-900 pb-2">
                        @lang('digital-twin.users.selectUsersToInvite'):
                    </p>
                    <p class="flex text-xs font-normal text-sky-700 pb-2">
                        *@lang('digital-twin.users.explaination')
                    </p>
                    @if (!$users->isEmpty())

                        <!-- Filters -->
                        <p class="font-medium">@lang('digital-twin.users.filters'):</p>
                        <div class="flex flex-row flex-wrap gap-2 pb-4">
                            <div class="flex flex-row w-80 gap-3 items-center relative">
                                @if (!$users->isEmpty())
                                    <!-- Filter -->
                                    <label for="userFilter"
                                        class="block text-sm font-bold text-sky-700">@lang('digital-twin.users.search')</label>
                                    <input type="text" id="userFilter" name="userFilter"
                                        class="p-2 border border-sky-700 focus:border-sky-800 focus:ring-sky-800 rounded-md shadow-sm w-full placeholder:text-sky-700"
                                        placeholder="@lang('digital-twin.users.enterSearch')">
                                    <button id="clear-user-filter"
                                        class="hidden absolute right-2 top-1/2 transform -translate-y-1/2 focus:outline-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-sky-600"
                                            viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M10 0a10 10 0 0 1 7.071 2.929A10 10 0 0 1 20 10a10 10 0 0 1-2.929 7.071A10 10 0 0 1 10 20a10 10 0 0 1-7.071-2.929A10 10 0 0 1 0 10a10 10 0 0 1 2.929-7.071A10 10 0 0 1 10 0zm3.536 5.05a.5.5 0 0 1 .708.708L10.707 10l3.536 3.536a.5.5 0 0 1-.708.708L10 10.707l-3.536 3.536a.5.5 0 1 1-.708-.708L9.293 10 5.757 6.464a.5.5 0 0 1 .708-.708L10 9.293l3.536-3.536z" />
                                        </svg>
                                    </button>
                                @endif
                            </div>

                            <div class="flex flex-wrap gap-2 items-center">
                                <button
                                    class="all-button py-2 px-4 border border-sky-700 bg-white rounded-md shadow-sm focus:outline-none focus:ring-sky-800 focus:border-sky-800 text-sm"
                                    id="selectAllButton">@lang('digital-twin.users.all')</button>
                                <!-- Gender -->
                                <button type="button"
                                    class="all-male-button py-2 px-4 border border-sky-700 bg-white rounded-md shadow-sm focus:outline-none focus:ring-sky-800 focus:border-sky-800 text-sm"
                                    id="selectAllMaleButton">@lang('digital-twin.users.allMale')</button>
                                <button type="button"
                                    class="all-female-button py-2 px-4 border border-sky-700 bg-white rounded-md shadow-sm focus:outline-none focus:ring-sky-800 focus:border-sky-800 text-sm"
                                    id="selectAllFemaleButton">@lang('digital-twin.users.allFemale')</button>
                                <button type="button"
                                    class="all-other-button py-2 px-4 border border-sky-700 bg-white rounded-md shadow-sm focus:outline-none focus:ring-sky-800 focus:border-sky-800 text-sm"
                                    id="selectAllOtherButton">@lang('digital-twin.users.allOther')</button>
                            </div>

                            <!-- Age -->
                            <div class="flex flex-row items-center gap-2">
                                <label for="ageFrom"
                                    class="block text-sm font-bold text-sky-700">@lang('digital-twin.users.ageFrom'):</label>
                                <input type="number" id="ageFrom" name="ageFrom" min="18" max="80"
                                    class="p-2 border border-sky-700 focus:border-sky-800 focus:ring-sky-800 rounded-md shadow-sm w-16">
                                <label for="ageTo"
                                    class="block text-sm font-bold text-sky-700">@lang('digital-twin.users.ageTo')</label>
                                <input type="number" id="ageTo" name="ageTo" min="18" max="80"
                                    class="p-2 border border-sky-700 focus:border-sky-800 focus:ring-sky-800 rounded-md shadow-sm w-16">
                                <x-primary-button id="selectAgeRangeButton">@lang('digital-twin.users.ageSelect')</x-primary-button>
                            </div>
                        </div>

                        <form id="saveDigitalTwinForm" action="{{ route('digital-twin.create') }}" method="POST"
                            class="w-full">
                            @csrf
                            @method('post')

                            <table id="users-table" class="min-w-full text-center border-collapse">
                                <thead class="bg-gray-100">
                                    <tr class="border-b-2 border-gray-300">
                                        <th class="py-2 px-4">@lang('digital-twin.users.selected')</th>
                                        <th class="py-2 px-4">@lang('digital-twin.users.fullName')</th>
                                        <th class="py-2 px-4">@lang('digital-twin.users.age')</th>
                                        <th class="py-2 px-4">@lang('digital-twin.users.gender')</th>
                                        <th class="py-2 px-4">@lang('digital-twin.users.companyRole')</th>
                                        <th class="py-2 px-4">@lang('digital-twin.users.humanFactors')</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white">
                                    @foreach ($users as $user)
                                        <tr
                                            class="table-rows hover:bg-gray-100 border-b border-gray-200 cursor-pointer">
                                            <td class="w-[10%] py-2 px-4">
                                                <input type="checkbox" name="selected_users[]"
                                                    data-gender="{{ $user->gender }}" value="{{ $user->id }}"
                                                    class="selected_user_checkbox form-checkbox text-sky-700 rounded-md">
                                            </td>
                                            <td class="w-1/4 py-2 px-4">{{ $user->fullName() }}</td>
                                            <td class="w-1/6 py-2 px-4">{{ $user->age() }}</td>
                                            <td class="w-1/6 py-2 px-4">{{ $user->gender }}</td>
                                            <td class="w-1/3 py-2 px-4">{{ $user->company_role }}</td>
                                            <td class="w-1/3 py-2 px-4">
                                                <div class="flex gap-1 items-center justify-center">
                                                    @foreach (array_keys(array_slice($user->human_factors, 0, 1)) as $factor)
                                                        <x-chip>{{ $factor }}</x-chip>
                                                    @endforeach

                                                    @if (count($user->human_factors) > 2)
                                                        <div x-data="{ open: false }" class="relative">
                                                            <button type="button"
                                                                onclick="event.preventDefault(); event.stopPropagation();"
                                                                @click="open = !open"
                                                                class="bg-sky-300 text-white text-xs px-2 py-1 rounded-full">+{{ count($user->human_factors) - 2 }}</button>

                                                            <div x-show="open" @click.outside="open = false"
                                                                class="absolute z-10 mt-1 bg-white border shadow-lg rounded p-2 text-sm">
                                                                @foreach (array_keys(array_slice($user->human_factors, 2)) as $factor)
                                                                    <x-chip class="my-1 bg-sky-300">
                                                                        {{ strtoupper($factor) }}</x-chip>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <!-- Paginator controls -->
                            <div id="pagination-controls" class="flex justify-around items-center mt-4">
                                <div class="flex justify-center w-1/3">
                                    <x-primary-button type='button'
                                        id="prevPage">@lang('digital-twin.previous')</x-primary-button>
                                </div>
                                <div class="flex flex-row justify-center items-center w-1/3 gap-4">
                                    <span id="pageIndicator" class="text-gray-700"></span>
                                    <span id="totalUsers" class="text-gray-500 text-sm"></span>
                                    <select id="rowsPerPage" class="border border-gray-300 rounded-md shadow-sm">
                                        <option value="5">5</option>
                                        <option value="10" selected>10</option>
                                        <option value="20">20</option>
                                        <option value="50">50</option>
                                    </select>
                                </div>
                                <div class="flex justify-center w-1/3">
                                    <x-primary-button type='button'
                                        id="nextPage">@lang('digital-twin.next')</x-primary-button>
                                </div>
                            </div>

                            <input type="hidden" name="selected_users_ordered" id="selected_users_ordered">

                            <div class="flex flex-row justify-around items-center pt-6">
                                <div class="flex w-1/3">
                                </div>
                                <!-- Circles which indicates the steps of the creation -->
                                <div class="flex flex-row w-1/3 justify-center items-center">
                                    <span class="status"></span>
                                    <span class="status"></span>
                                    <span class="status active"></span>
                                </div>
                                <div class="flex w-1/3 justify-center">
                                    <x-primary-button type="submit">@lang('digital-twin.users.create')</x-primary-button>
                                </div>
                            </div>
                        </form>
                    @else
                        <p class="text-center">@lang('digital-twin.users.noUsers')</p>
                    @endif

                </div>
            </div>
        </div>
    </div>

    <!-- Loading screen -->
    <div id="loadingOverlay"
        class="fixed top-0 left-0 w-full h-full bg-gray-900 bg-opacity-50 items-center justify-center z-50 hidden">
        <div class="loader ease-linear rounded-full border-8 border-t-8 h-32 w-32"></div>
    </div>
</x-app-layout>

<!-- Error user modal -->
<x-modal name="error-user-modal" id="error-user-modal" title="Error user!" :show="false">
    <div class="p-4 rounded-lg relative text-center">
        <p class="text-2xl font-semibold text-red-700 pb-8">@lang('digital-twin.users.chooseUser')</p>
        <x-primary-button x-on:click="$dispatch('close')">@lang('digital-twin.users.close')</x-primary-button>
    </div>
</x-modal>

<!-- Error age modal -->
<x-modal name="error-age-modal" id="error-age-modal" title="Error age!" :show="false">
    <div class="p-4 rounded-lg relative text-center">
        <p class="text-2xl font-semibold text-red-700 pb-8">@lang('digital-twin.users.errorAge')</p>
        <x-primary-button x-on:click="$dispatch('close')">@lang('digital-twin.users.close')</x-primary-button>
    </div>
</x-modal>

<script>
    // Tabulation
    document.addEventListener('DOMContentLoaded', function() {
        if (document.getElementById('pagination-controls')) {
            const rowsPerPageSelect = document.getElementById('rowsPerPage');
            const pageIndicator = document.getElementById('pageIndicator');
            const totalUsers = document.getElementById('totalUsers');

            let rowsPerPage = parseInt(rowsPerPageSelect.value);
            let currentPage = 1;
            const table = document.getElementById('users-table').getElementsByTagName('tbody')[0];
            const totalRows = table.getElementsByTagName('tr').length;
            let totalPages = Math.ceil(totalRows / rowsPerPage);

            if (totalRows <= 5) {
                pageIndicator.style.display = 'none';
                rowsPerPageSelect.style.display = 'none';
            }

            function updateTable() {
                for (let i = 0; i < totalRows; i++) {
                    table.rows[i].style.display = (i >= (currentPage - 1) * rowsPerPage && i < currentPage *
                        rowsPerPage) ? '' : 'none';
                }
                totalPages = Math.ceil(totalRows / rowsPerPage);
                pageIndicator.innerText = `${currentPage} / ${totalPages}`;
                totalUsers.innerText = `Total users: ${totalRows}`;
                document.getElementById('prevPage').classList.toggle('hidden', currentPage === 1);
                document.getElementById('nextPage').classList.toggle('hidden', currentPage === totalPages);
            }

            document.getElementById('prevPage').addEventListener('click', function() {
                if (currentPage > 1) {
                    currentPage--;
                    updateTable();
                }
            });

            document.getElementById('nextPage').addEventListener('click', function() {
                if (currentPage < totalPages) {
                    currentPage++;
                    updateTable();
                }
            });

            rowsPerPageSelect.addEventListener('change', function() {
                rowsPerPage = parseInt(this.value);
                currentPage = 1; // Reset to first page
                updateTable();
            });

            // Initialize table display
            updateTable();
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        const userCheckboxes = document.querySelectorAll('.selected_user_checkbox');
        const selectAllButton = document.getElementById('selectAllButton');
        const selectAllMaleButton = document.getElementById('selectAllMaleButton');
        const selectAllFemaleButton = document.getElementById('selectAllFemaleButton');
        const selectAllOtherButton = document.getElementById('selectAllOtherButton');
        const selectAgeRangeButton = document.getElementById('selectAgeRangeButton');
        const ageFromInput = document.getElementById('ageFrom');
        const ageToInput = document.getElementById('ageTo');

        let allUsersSelected = false;
        let allMalesSelected = false;
        let allFemalesSelected = false;
        let allOthersSelected = false;

        function updateSelectedUsersCount() {
            const selectedCount = document.querySelectorAll('.selected_user_checkbox:checked').length;
            document.getElementById('totalSelectedUsers').textContent = selectedCount;
        }

        // Utility function to calculate age
        function calculateAge(dateString) {
            if (!dateString) return NaN; // Return NaN if dateString is empty

            // Convert dd/mm/yyyy to yyyy-mm-dd
            const parts = dateString.split('/');
            const formattedDateString = `${parts[2]}-${parts[1]}-${parts[0]}`;

            const birthDate = new Date(formattedDateString);
            const today = new Date();

            let age = today.getFullYear() - birthDate.getFullYear();
            const monthDifference = today.getMonth() - birthDate.getMonth();
            if (monthDifference < 0 || (monthDifference === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }
            return age;
        }

        // Utility function to toggle button styles
        function toggleButtonStyles(button, isActive) {
            if (isActive) {
                button.classList.remove('bg-white', 'text-sky-700');
                button.classList.add('bg-sky-800', 'text-white');
            } else {
                button.classList.remove('bg-sky-800', 'text-white');
                button.classList.add('bg-white', 'text-sky-700');
            }
        }

        function updateSelectAllButton() {
            const userCheckboxes = document.querySelectorAll('.selected_user_checkbox');
            const allChecked = Array.from(userCheckboxes).every(checkbox => checkbox.checked);
            selectAllButton.textContent = allChecked ? "Deselect all" : "Select all";
            toggleButtonStyles(selectAllButton, allChecked);
        }

        if (selectAllButton) {
            selectAllButton.addEventListener('click', function() {
                allUsersSelected = !allUsersSelected;
                allMalesSelected = allUsersSelected;
                allFemalesSelected = allUsersSelected;
                allOthersSelected = allUsersSelected;

                userCheckboxes.forEach(function(checkbox) {
                    checkbox.checked = allUsersSelected;
                });

                selectAllButton.textContent = allUsersSelected ? "Deselect all" : "Select all";
                toggleButtonStyles(selectAllButton, allUsersSelected);

                toggleButtonStyles(selectAllMaleButton, allMalesSelected);
                toggleButtonStyles(selectAllFemaleButton, allFemalesSelected);
                toggleButtonStyles(selectAllOtherButton, allOthersSelected);

                updateSelectedUsersCount();
            });
        }

        if (selectAllMaleButton) {
            selectAllMaleButton.addEventListener('click', function() {
                allMalesSelected = !allMalesSelected;
                userCheckboxes.forEach(function(checkbox) {
                    if (checkbox.dataset.gender === 'Male') {
                        checkbox.checked = allMalesSelected;
                    }
                });
                toggleButtonStyles(selectAllMaleButton, allMalesSelected);
                updateSelectAllButton();
                updateSelectedUsersCount();
            });
        }

        if (selectAllFemaleButton) {
            selectAllFemaleButton.addEventListener('click', function() {
                allFemalesSelected = !allFemalesSelected;
                userCheckboxes.forEach(function(checkbox) {
                    if (checkbox.dataset.gender === 'Female') {
                        checkbox.checked = allFemalesSelected;
                    }
                });
                toggleButtonStyles(selectAllFemaleButton, allFemalesSelected);
                updateSelectAllButton();
                updateSelectedUsersCount();
            });
        }

        if (selectAllOtherButton) {
            selectAllOtherButton.addEventListener('click', function() {
                allOthersSelected = !allOthersSelected;
                userCheckboxes.forEach(function(checkbox) {
                    if (checkbox.dataset.gender === 'Other') {
                        checkbox.checked = allOthersSelected;
                    }
                });
                toggleButtonStyles(selectAllOtherButton, allOthersSelected);
                updateSelectAllButton();
                updateSelectedUsersCount();
            });
        }

        if (selectAgeRangeButton) {
            selectAgeRangeButton.addEventListener('click', function() {
                const ageFrom = parseInt(ageFromInput.value, 10);
                const ageTo = parseInt(ageToInput.value, 10);

                userCheckboxes.forEach(function(checkbox) {
                    const age = checkbox.closest('tr').querySelector('td:nth-child(3)')
                        .textContent.trim();
                    checkbox.checked = (!isNaN(ageFrom) && age >= ageFrom) && (isNaN(ageTo) ||
                        age <= ageTo);
                });
                updateSelectAllButton();
                updateSelectedUsersCount();
            });
        }

        userCheckboxes.forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                updateSelectAllButton();
                updateSelectedUsersCount();
            });
        });
    });

    // Filter
    function setupTableFilter(userFilterInput, clearFilterButton, rows) {
        userFilterInput.addEventListener("input", function() {
            const userFilterValue = this.value.toLowerCase().trim();

            clearFilterButton.style.display = this.value.trim() !== "" ? "block" : "none";

            rows.forEach(function(row) {
                const rowData = Array.from(row.cells).map(cell => cell.textContent.toLowerCase());
                const matchesFilter = rowData.some(data => data.includes(userFilterValue));
                row.style.display = matchesFilter ? "" : "none";
            });
        });

        clearFilterButton.addEventListener("click", function() {
            userFilterInput.value = "";
            clearFilterButton.style.display = "none";

            rows.forEach(function(row) {
                row.style.display = "";
            });
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        const userFilterInput = document.getElementById("userFilter");
        const clearFilterButton = document.getElementById("clear-user-filter");
        const rows = document.querySelectorAll("#users-table tbody tr");

        setupTableFilter(userFilterInput, clearFilterButton, rows);
    });

    document.addEventListener('DOMContentLoaded', function() {
        const selectedOrder = [];
        const checkboxes = document.querySelectorAll('.selected_user_checkbox');
        const hiddenField = document.getElementById('selected_users_ordered');

        checkboxes.forEach(cb => {
            cb.addEventListener('change', () => {
                const userId = cb.value;

                if (cb.checked) {
                    // Add if not already in array
                    if (!selectedOrder.includes(userId)) {
                        selectedOrder.push(userId);
                    }
                } else {
                    // Remove if unchecked
                    const index = selectedOrder.indexOf(userId);
                    if (index > -1) selectedOrder.splice(index, 1);
                }

                // Update hidden field with CSV
                hiddenField.value = selectedOrder.join(',');
            });
        });

        // Optionally handle form reset
        document.getElementById('saveDigitalTwinForm').addEventListener('reset', () => {
            selectedOrder.length = 0;
            hiddenField.value = '';
        });
    })
</script>
