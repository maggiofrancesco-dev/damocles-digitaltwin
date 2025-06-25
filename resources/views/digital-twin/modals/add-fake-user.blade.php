<section class="text-sky-800">
    <div class="flex flex-row w-full items-center justify-between">
        <p class="text-lg font-medium text-sky-900">
            @lang('digital-twin.modals.fakeUserTitle')
        </p>
        @if ($fakeUsers->count() > 0)
            <!-- Filter -->
            <div class="flex flex-row w-80 gap-3 justify-end items-center relative">
                <label for="filter" class="block text-sm font-bold text-sky-700">@lang('digital-twin.search')</label>
                <input type="text" id="filter" name="filter"
                    class="p-2 border border-sky-700 focus:border-sky-800 focus:ring-sky-800 rounded-md shadow-sm w-full placeholder:text-sky-700"
                    placeholder="@lang('digital-twin.modals.newFakeUser.placeholderFilter')">
                <button id="clear-filter"
                    class="hidden absolute right-2 top-1/2 transform -translate-y-1/2 focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-sky-600" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 0a10 10 0 0 1 7.071 2.929A10 10 0 0 1 20 10a10 10 0 0 1-2.929 7.071A10 10 0 0 1 10 20a10 10 0 0 1-7.071-2.929A10 10 0 0 1 0 10a10 10 0 0 1 2.929-7.071A10 10 0 0 1 10 0zm3.536 5.05a.5.5 0 0 1 .708.708L10.707 10l3.536 3.536a.5.5 0 0 1-.708.708L10 10.707l-3.536 3.536a.5.5 0 1 1-.708-.708L9.293 10 5.757 6.464a.5.5 0 0 1 .708-.708L10 9.293l3.536-3.536z" />
                    </svg>
                </button>
            </div>
        @endif
    </div>

    <div class="pt-4 w-full">


        <div class="w-full">
            <div class="flex flex-col sm:flex-row w-full items-center justify-between mb-4">
                <div class="mb-4 sm:mb-0">
                    <p class="font-semibold">@lang('digital-twin.modals.newFakeUser.available')</p>
                </div>
                <div>
                    <a href="{{ route('digital-twin.new') }}" class="cursor-pointer">
                        <x-secondary-button>
                            @lang('digital-twin.modals.newFakeUser.create')
                        </x-secondary-button>
                        <x-primary-button>
                            @lang('digital-twin.modals.newFakeUser.confirm')
                        </x-primary-button>
                    </a>
                </div>
            </div>
            <table id="fake-users-table" class="min-w-full text-center border-collapse">
                <thead class="bg-gray-100">
                    <tr class="border-b-2 border-gray-300">
                        <th class="py-2 px-4">@lang('digital-twin.modals.newFakeUser.selected')</th>
                        <th class="py-2 px-4">@lang('digital-twin.modals.newFakeUser.fullName')</th>
                        <th class="py-2 px-4">@lang('digital-twin.modals.newFakeUser.age')</th>
                        <th class="py-2 px-4">@lang('digital-twin.modals.newFakeUser.gender')</th>
                        <th class="py-2 px-4">@lang('digital-twin.modals.newFakeUser.companyRole')</th>
                        <th class="py-2 px-4">@lang('digital-twin.modals.newFakeUser.humanFactors')</th>
                        <th class="py-2 px-4"></th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @foreach ($fakeUsers as $fakeUser)
                        <tr class="table-rows hover:bg-gray-100 border-b border-gray-200 cursor-pointer">
                            <td class="w-[10%] py-2 px-4">
                                <input type="checkbox" name="selected_fake_users" value="{{ $fakeUser->id }}"
                                    class="selected_fake_users form-checkbox text-sky-700 rounded-md">
                            </td>
                            <td class="w-1/4 py-2 px-4">{{ $fakeUser->fullName() }}</td>
                            <td class="w-1/6 py-2 px-4">{{ $fakeUser->age() }}</td>
                            <td class="w-1/6 py-2 px-4">{{ $fakeUser->gender }}</td>
                            <td class="w-1/3 py-2 px-4">{{ $fakeUser->company_role }}</td>
                            <td class="w-1/3 py-2 px-4">
                                @foreach (array_keys($fakeUser->human_factors) as $humanFactor)
                                    <x-chip class="my-1">{{ $humanFactor }}</x-chip>
                                @endforeach
                            </td>
                            <td class="flex flex-row justify-end py-2 px-4">
                                <x-dropdown align="right" width="48">
                                    <x-slot name="trigger">
                                        <button class="inline-flex items-center font-semibold inner-element">
                                            <p class="classic">
                                                @lang('digital-twin.options')
                                                <span>
                                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                                        viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                </span>
                                            </p>
                                        </button>
                                    </x-slot>

                                    <x-slot name="content">
                                        <div class="flex flex-col gap-2">
                                            <button class="hover:bg-gray-100 text-red-500 inner-element"
                                                data-id="{{ $fakeUser->id }}" x-data=""
                                                @click="$dispatch('open-modal', 'delete-fake-user-modal', { id: {{ $fakeUser->id }} })">@lang('fake-user.delete')</button>
                                        </div>
                                    </x-slot>
                                </x-dropdown>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <!-- Paginator controls -->
            <div id="pagination-controls" class="flex justify-around items-center mt-4">
                <div class="flex justify-center w-1/3">
                    <x-primary-button id="prevPage">@lang('digital-twin.previous')</x-primary-button>
                </div>
                <div class="flex flex-row justify-center items-center w-1/3 gap-4">
                    <span id="pageIndicator" class="text-gray-700"></span>
                    <span id="totalCampaigns" class="text-gray-500 text-sm"></span>
                    <select id="rowsPerPage" class="border border-gray-300 rounded-md shadow-sm">
                        <option value="5" selected>5</option>
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="50">50</option>
                    </select>
                </div>
                <div class="flex justify-center w-1/3">
                    <x-primary-button id="nextPage">@lang('digital-twin.next')</x-primary-button>
                </div>
            </div>
        </div>
</section>

<script>
    // Tabulation
    document.addEventListener('DOMContentLoaded', function() {
        if (document.getElementById('pagination-controls')) {
            const rowsPerPageSelect = document.getElementById('rowsPerPage');
            const pageIndicator = document.getElementById('pageIndicator');
            const totalCampaigns = document.getElementById('totalCampaigns');

            let rowsPerPage = parseInt(rowsPerPageSelect.value);
            let currentPage = 1;
            const table = document.getElementById('fake-users-table').getElementsByTagName('tbody')[0];
            const totalRows = table.getElementsByTagName('tr').length;
            let totalPages = Math.ceil(totalRows / rowsPerPage);

            if (totalRows <= 10) {
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
                totalCampaigns.innerText = `Total campaigns: ${totalRows}`;
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


    // Filter
    document.addEventListener('DOMContentLoaded', function() {
        const filterInput = document.getElementById("filter");
        const clearFilterButton = document.getElementById("clear-filter");
        const rows = document.querySelectorAll("#fake-users-table tbody tr");

        if (filterInput && clearFilterButton) {
            filterInput.addEventListener("input", function() {
                const filterValue = this.value.toLowerCase().trim();

                clearFilterButton.style.display = this.value.trim() !== "" ? "block" : "none";

                rows.forEach(function(row) {
                    const name = row.cells[1].textContent.toLowerCase();
                    const age = row.cells[2].textContent.toLowerCase();
                    const gender = row.cells[3].textContent.toLowerCase();
                    const role = row.cells[4].textContent.toLowerCase();
                    const humanFactors = row.cells[5].textContent.toLowerCase();

                    if (name.includes(filterValue) || age.includes(filterValue) ||
                        gender.includes(filterValue) || role.includes(filterValue) ||
                        humanFactors.includes(filterValue)) {
                        row.style.display = "";
                    } else {
                        row.style.display = "none";
                    }
                });
            });

            clearFilterButton.addEventListener("click", function() {
                // Clear the search input
                filterInput.value = "";

                // Hide the clear button
                clearFilterButton.style.display = "none";

                rows.forEach(function(row) {
                    row.style.display = "";
                });
            });
        }
    });

    //Checkboxes
    document.addEventListener('DOMContentLoaded', function() {
        const rows = document.querySelectorAll(".table-rows");
        const selectedFakeUsers = document.querySelectorAll('.selected_fake_users');

        selectedFakeUsers.forEach(elem => elem.addEventListener('click', (e) => {
            e.stopPropagation()
        }))


        rows.forEach((row, i) => {
            row.addEventListener('click', (e) => {
                selectedFakeUsers[i].checked = !selectedFakeUsers[i].checked;
            })
        })
    })
</script>
