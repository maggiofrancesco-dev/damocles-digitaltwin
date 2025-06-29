<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row item-center gap-3">
            <!-- Back to digital twin -->
            <a href="{{ route('digital-twin.index') }}" class="cursor-pointer">
                <x-primary-button>
                    @lang('digital-twin.newDigitalTwin.back')
                </x-primary-button>
            </a>
            <!-- Breadcrumb -->
            <ul class="flex flex-row gap-1 flex-wrap break-words text-sky-800">
                <li><a href="{{ route('digital-twin.index') }}">@lang('digital-twin.newDigitalTwin.digitalTwin')</a></li>
                <li>/</li>
                <li><a href="{{ route('digital-twin.new') }}">@lang('digital-twin.newDigitalTwin.new')</a></li>
            </ul>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white sm:overflow-hidden shadow-sm sm:rounded-lg">
                <form class="p-8 text-sky-900 w-full space-y-4" method="POST"
                    action="{{ route('digital-twin.fake-users') }}">
                    @csrf

                    <p class="font-semibold text-xl">@lang('digital-twin.newDigitalTwin.simulatePrompt')</p>
                    <div>
                        <p class="text-lg font-medium text-sky-900">
                            *@lang('digital-twin.newDigitalTwin.prompt'):
                        </p>
                        <x-input-label for="title" class="text-md block font-medium text-sky-700 pb-2"
                            :value="__('digital-twin.newDigitalTwin.value.prompt')" />

                        <div class="flex flex-col gap-2">

                            <textarea name="prompt" id="prompt" placeholder="" rows="8"
                                class="border border-sky-700 focus:border-sky-800 focus:ring-sky-800 rounded-md w-full">{{ $prompt }}</textarea>
                        </div>
                    </div>

                    <div class="w-full">
                        <div class="flex flex-col sm:flex-row w-full items-center justify-between mb-4">
                            <div class="mb-4 sm:mb-0">
                                <p class="font-semibold">@lang('digital-twin.fakeUsers.available')</p>
                            </div>
                            <div class="flex flex-row gap-2 items-center">
                                @if ($fakeUsers->count() > 0)
                                    <!-- Filter -->
                                    <div class="flex flex-row w-80 gap-3 justify-end items-center relative">
                                        <label for="filter"
                                            class="block text-sm font-bold text-sky-700">@lang('digital-twin.search')</label>
                                        <input type="text" id="filter" name="filter"
                                            class="h-[34px] p-2 border border-sky-700 focus:border-sky-800 focus:ring-sky-800 rounded-md shadow-sm w-full placeholder:text-sky-700"
                                            placeholder="@lang('digital-twin.fakeUsers.placeholderFilter')">
                                        <button id="clear-filter"
                                            class="hidden absolute right-2 top-1/2 transform -translate-y-1/2 focus:outline-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-sky-600"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M10 0a10 10 0 0 1 7.071 2.929A10 10 0 0 1 20 10a10 10 0 0 1-2.929 7.071A10 10 0 0 1 10 20a10 10 0 0 1-7.071-2.929A10 10 0 0 1 0 10a10 10 0 0 1 2.929-7.071A10 10 0 0 1 10 0zm3.536 5.05a.5.5 0 0 1 .708.708L10.707 10l3.536 3.536a.5.5 0 0 1-.708.708L10 10.707l-3.536 3.536a.5.5 0 1 1-.708-.708L9.293 10 5.757 6.464a.5.5 0 0 1 .708-.708L10 9.293l3.536-3.536z" />
                                            </svg>
                                        </button>
                                    </div>
                                @endif
                                <x-primary-button x-data=''
                                    @click="$dispatch('open-modal', 'create-fake-user-modal')" type='button'>
                                    @lang('digital-twin.fakeUsers.create')
                                </x-primary-button>
                            </div>
                        </div>
                        <table id="fake-users-table" class="min-w-full text-center border-collapse">
                            <thead class="bg-gray-100">
                                <tr class="border-b-2 border-gray-300">
                                    <th class="py-2 px-4">@lang('digital-twin.fakeUsers.fullName')</th>
                                    <th class="py-2 px-4">@lang('digital-twin.fakeUsers.age')</th>
                                    <th class="py-2 px-4">@lang('digital-twin.fakeUsers.gender')</th>
                                    <th class="py-2 px-4">@lang('digital-twin.fakeUsers.companyRole')</th>
                                    <th class="py-2 px-4">@lang('digital-twin.fakeUsers.humanFactors')</th>
                                    <th class="py-2 px-4"></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white">
                                @foreach ($fakeUsers as $fakeUser)
                                    <tr class="table-rows hover:bg-gray-100 border-b border-gray-200 cursor-pointer">
                                        <td class="w-1/4 py-2 px-4">{{ $fakeUser->fullName() }}</td>
                                        <td class="w-1/6 py-2 px-4">{{ $fakeUser->age() }}</td>
                                        <td class="w-1/6 py-2 px-4">{{ $fakeUser->gender }}</td>
                                        <td class="w-1/3 py-2 px-4">{{ $fakeUser->company_role }}</td>
                                        <td class="w-1/3 py-2 px-4">
                                            <div class="flex gap-1 items-center justify-center">
                                                @foreach (array_keys(array_slice($fakeUser->human_factors, 0, 1)) as $factor)
                                                    <x-chip>{{ $factor }}</x-chip>
                                                @endforeach

                                                @if (count($fakeUser->human_factors) > 2)
                                                    <div x-data="{ open: false }" class="relative">
                                                        <button type="button"
                                                            onclick="event.preventDefault(); event.stopPropagation();"
                                                            @click="open = !open"
                                                            class="bg-sky-300 text-white text-xs px-2 py-1 rounded-full">+{{ count($fakeUser->human_factors) - 2 }}</button>

                                                        <div x-show="open" @click.outside="open = false"
                                                            class="absolute z-10 mt-1 bg-white border shadow-lg rounded p-2 text-sm">
                                                            @foreach (array_keys(array_slice($fakeUser->human_factors, 2)) as $factor)
                                                                <x-chip class="my-1 bg-sky-300">
                                                                    {{ strtoupper($factor) }}</x-chip>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td onclick="event.stopPropagation();"
                                            class="flex flex-row justify-end items-center py-2 px-4">
                                            <x-dropdown align="right" width="48">
                                                <x-slot name="trigger">
                                                    <button type="button"
                                                        class="inline-flex items-center font-semibold inner-element">
                                                        <p class="classic">
                                                            @lang('digital-twin.options')
                                                            <span>
                                                                <svg class="fill-current h-4 w-4"
                                                                    xmlns="http://www.w3.org/2000/svg"
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
                                                        <button type="button" class="hover:bg-gray-100 inner-element"
                                                            data-user="{{ $fakeUser->toJson() }}"
                                                            x-data=""
                                                            @click="$dispatch('open-modal', 'evaluate-modal')">@lang('digital-twin.evaluate')</button>
                                                        <button type="button"
                                                            class="hover:bg-gray-100 text-red-500 inner-element"
                                                            data-id="{{ $fakeUser->id }}" x-data=""
                                                            @click="$dispatch('open-modal', 'delete-fake-user-modal', { id: {{ $fakeUser->id }} })">@lang('digital-twin.delete')</button>
                                                    </div>
                                                </x-slot>
                                            </x-dropdown>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <x-input-error :messages="$errors->get('selected_users')" class="mt-2" />

                        <!-- Paginator controls -->
                        <div id="pagination-controls" class="flex justify-around items-center mt-4">
                            <div class="flex justify-center w-1/3">
                                <x-primary-button type='button' id="prevPage">@lang('digital-twin.previous')</x-primary-button>
                            </div>
                            <div class="flex flex-row justify-center items-center w-1/3 gap-4">
                                <span id="pageIndicator" class="text-gray-700"></span>
                                <span id="totalUsers" class="text-gray-500 text-sm"></span>
                                <select id="rowsPerPage" class="border border-gray-300 rounded-md shadow-sm">
                                    <option value="5" selected>5</option>
                                    <option value="10">10</option>
                                    <option value="20">20</option>
                                    <option value="50">50</option>
                                </select>
                            </div>
                            <div class="flex justify-center w-1/3">
                                <x-primary-button type='button' id="nextPage">@lang('digital-twin.next')</x-primary-button>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-row justify-around items-center pt-6">
                        <div class="flex w-1/3">
                        </div>
                        <!-- Circles which indicates the steps of the creation -->
                        <div class="flex flex-row w-1/3 justify-center items-center">
                            <span class="status"></span>
                            <span class="status active"></span>
                            <span class="status"></span>
                        </div>
                        <a href="{{ route('digital-twin.select-users') }}">
                            <x-primary-button class="ml-auto" id="continueDigitalTwin"
                                type="button">@lang('digital-twin.continue')</x-primary-button>
                        </a>
                    </div>
                </form>

            </div>

            <!-- Loading screen -->
            <div id="loadingOverlay"
                class="fixed top-0 left-0 w-full h-full bg-gray-900 bg-opacity-50 items-center justify-center z-50 hidden">
                <div class="loader ease-linear rounded-full border-8 border-t-8 h-32 w-32"></div>
            </div>
        </div>
</x-app-layout>

<!-- Error modal -->
<!-- Error data modal -->
<x-modal name="error-data-modal" id="error-data-modal" title="Error data" :show="false">
    <div class="p-6 rounded-lg relative text-center">
        <p class="text-2xl font-semibold text-red-700 pb-8">@lang('digital-twin.newDigitalTwin.errorData')</p>
        <x-secondary-button x-on:click="$dispatch('close')">@lang('digital-twin.newDigitalTwin.close')</x-secondary-button>
    </div>
</x-modal>


<!-- Delete modal -->
<x-modal name="delete-fake-user-modal" id="delete-fake-user-modal" title="Delete Fake User" :show="false">
    <div class="p-4 rounded-lg relative">
        @include('digital-twin.modals.delete-fake-user')
    </div>
</x-modal>

<!-- Evaluate modal -->
<x-modal name="evaluate-modal" id="evaluate-modal" title="Evaluate prompt" :show="false">
    <div class="p-4 rounded-lg relative">
        @include('digital-twin.modals.evaluate-prompt')
    </div>
</x-modal>

<x-modal name="create-fake-user-modal" id="create-fake-user-modal" title="{{ __('Create new fake user') }}"
    :show="false">
    <div class="p-4 rounded-lg relative">
        @include('digital-twin.modals.create-fake-user')
    </div>
</x-modal>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const table = document.getElementById('fake-users-table').getElementsByTagName('tbody')[0];
        const rows = Array.from(table.getElementsByTagName('tr'));
        const pageIndicator = document.getElementById('pageIndicator');
        const totalUsers = document.getElementById('totalUsers');
        const rowsPerPageSelect = document.getElementById('rowsPerPage');
        const prevPageBtn = document.getElementById('prevPage');
        const nextPageBtn = document.getElementById('nextPage');
        const filterInput = document.getElementById("filter");
        const clearFilterButton = document.getElementById("clear-filter");

        let currentPage = 1;
        let rowsPerPage = parseInt(rowsPerPageSelect.value);
        let filteredRows = [...rows]; // initialize with all

        function paginate() {
            const totalRows = filteredRows.length;
            const totalPages = Math.ceil(totalRows / rowsPerPage);

            pageIndicator.innerText = `${currentPage} / ${totalPages}`;
            totalUsers.innerText = `Total users: ${totalRows}`;

            prevPageBtn.classList.toggle('hidden', currentPage === 1);
            nextPageBtn.classList.toggle('hidden', currentPage === totalPages || totalPages === 0);

            // Hide all rows first
            rows.forEach(row => row.style.display = 'none');

            // Show only filtered + paginated rows
            const start = (currentPage - 1) * rowsPerPage;
            const end = start + rowsPerPage;
            filteredRows.slice(start, end).forEach(row => {
                row.style.display = '';
            });
        }

        function applyFilter() {
            const query = filterInput.value.toLowerCase().trim();

            clearFilterButton.style.display = query ? 'block' : 'none';

            filteredRows = rows.filter(row => {
                return Array.from(row.cells).some(cell =>
                    cell.textContent.toLowerCase().includes(query)
                );
            });

            currentPage = 1; // Reset to first page
            paginate();
        }

        filterInput.addEventListener("input", applyFilter);
        clearFilterButton.addEventListener("click", () => {
            filterInput.value = '';
            clearFilterButton.style.display = 'none';
            applyFilter();
        });

        prevPageBtn.addEventListener("click", () => {
            if (currentPage > 1) {
                currentPage--;
                paginate();
            }
        });

        nextPageBtn.addEventListener("click", () => {
            const totalPages = Math.ceil(filteredRows.length / rowsPerPage);
            if (currentPage < totalPages) {
                currentPage++;
                paginate();
            }
        });

        rowsPerPageSelect.addEventListener("change", () => {
            rowsPerPage = parseInt(rowsPerPageSelect.value);
            currentPage = 1;
            paginate();
        });

        // Initial load
        applyFilter();
    });
</script>
