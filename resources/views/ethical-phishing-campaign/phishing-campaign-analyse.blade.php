<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row items-center gap-3">
            <!-- Back to phishing campaign details -->
            <a href="{{ route('ethical-phishing-campaign.index') }}" class="cursor-pointer">
                <x-primary-button>
                    @lang('ethical-phishing-campaign.analyseCampaign.back')
                </x-primary-button>
            </a>
            <!-- Breadcrumb -->
            <ul class="flex flex-row gap-1 flex-wrap break-words text-sky-800">
                <li><a href="{{ route('ethical-phishing-campaign.index') }}">@lang('ethical-phishing-campaign.analyseCampaign.phishingCampaign')</a></li>
                <li>/</li>
                <li>@lang('ethical-phishing-campaign.analyseCampaign.analyse')</li>
            </ul>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8 text-sky-900">

                    <div class="flex flex-col w-full">

                        <div class="flex justify-start">
                            <p class="font-semibold text-xl">@lang('ethical-phishing-campaign.analyseCampaign.analyses')</p>
                        </div>

                        <!-- Charts -->
                        <div class="my-4">
                            <div
                                class="flex flex-col py-2 space-y-4 md:space-y-0 md:flex-row md:justify-around w-full items-center">
                                <div class="flex flex-col items-center w-60 md:w-64">
                                    <canvas id="openedEmails"></canvas>
                                </div>
                                <div class="flex flex-col items-center w-60 md:w-64">
                                    <canvas id="clickedEmails"></canvas>
                                </div>
                            </div>
                            <div
                                class="flex flex-col py-2 space-y-4 md:space-y-0 md:flex-row md:justify-around w-full items-center">
                                <div class="flex flex-col items-center w-60 md:w-64">
                                    <canvas id="genderOpened"></canvas>
                                </div>
                                <div class="flex flex-col items-center w-60 md:w-64">
                                    <canvas id="genderClicked"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- User -->
                    <div id="no-results" class="font-bold py-4 text-center hidden">
                        @lang('ethical-phishing-campaign.analyseCampaign.noResult')
                    </div>

                    @foreach ($users as $user)
                        <div class="py-4 user-entry" id="user-{{ $user->id }}">
                            <div class="flex flex-col">
                                <div class="flex flex-row gap-1">
                                    <p class="font-bold">@lang('ethical-phishing-campaign.analyseCampaign.user'):</p>
                                    <p class="user-name">{{ $user->name }} {{ $user->surname }}</p>
                                </div>
                                <div class="flex flex-row gap-1">
                                    <p class="font-bold">@lang('ethical-phishing-campaign.analyseCampaign.gender'):</p>
                                    <p class="user-gender">{{ $user->gender }}</p>
                                </div>
                                <div class="flex flex-row gap-1">
                                    <p class="font-bold">@lang('ethical-phishing-campaign.analyseCampaign.age'):</p>
                                    <p class="user-age">{{ $user->age }}</p>
                                </div>
                                @if (isset($user->response))
                                    <div class="flex flex-row gap-1 text-red-500">
                                        <p class="font-bold">@lang('ethical-phishing-campaign.analyseCampaign.opened'):</p>
                                        <p class="user-opened">{{ $user->response['opened'] ? 'Yes' : 'No' }}</p>
                                    </div>
                                    <div class="flex flex-row gap-1 text-red-500">
                                        <p class="font-bold">@lang('ethical-phishing-campaign.analyseCampaign.clicked'):</p>
                                        <p class="user-clicked">{{ $user->response['clicked'] ? 'Yes' : 'No' }}</p>
                                    </div>

                                    <div class="space-y-3 my-4">
                                        <div x-data="{ open: false }" class="border border-sky-700 rounded p-4">
                                            <!-- Header -->
                                            <button type="button" @click="open = !open"
                                                class="flex items-center justify-between w-full font-semibold text-sky-800">
                                                @lang('ethical-phishing-campaign.analyseCampaign.internalReasoning')
                                                <svg :class="{ 'rotate-180': open }"
                                                    class="w-4 h-4 transform transition-transform" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M19 9l-7 7-7-7" />
                                                </svg>
                                            </button>

                                            <!-- Collapsible Content -->
                                            <div x-show="open" x-transition class="mt-3">
                                                <ul
                                                    class="list-disc list-inside space-y-1 text-sm text-gray-700 font-medium">
                                                    @foreach ($user->response['internal_reasoning'] as $reason)
                                                        <li>{{ $reason }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>

                                        <div x-data="{ open: false }" class="border border-sky-700 rounded p-4">
                                            <!-- Header -->
                                            <button type="button" @click="open = !open"
                                                class="flex items-center justify-between w-full font-semibold text-sky-800">
                                                @lang('ethical-phishing-campaign.analyseCampaign.sequenceOfActions')
                                                <svg :class="{ 'rotate-180': open }"
                                                    class="w-4 h-4 transform transition-transform" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M19 9l-7 7-7-7" />
                                                </svg>
                                            </button>

                                            <!-- Collapsible Content -->
                                            <div x-show="open" x-transition class="mt-3">
                                                <ul
                                                    class="list-disc list-inside space-y-1 text-sm text-gray-700 font-medium">
                                                    @foreach ($user->response['sequence_of_actions'] as $action)
                                                        <li>{{ $action }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>

                                        <div x-data="{ open: false }" class="border border-sky-700 rounded p-4">
                                            <!-- Header -->
                                            <button type="button" @click="open = !open"
                                                class="flex items-center justify-between w-full font-semibold text-sky-800">
                                                @lang('ethical-phishing-campaign.analyseCampaign.outcome')
                                                <svg :class="{ 'rotate-180': open }"
                                                    class="w-4 h-4 transform transition-transform" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M19 9l-7 7-7-7" />
                                                </svg>
                                            </button>

                                            <!-- Collapsible Content -->
                                            <div x-show="open" x-transition class="mt-3">
                                                <ul
                                                    class="list-disc list-inside space-y-1 text-sm text-gray-700 font-medium">
                                                    @foreach ($user->response['outcome'] as $action)
                                                        <li>{{ $action }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>

                                        <div x-data="{ open: false }" class="border border-sky-700 rounded p-4">
                                            <!-- Header -->
                                            <button type="button" @click="open = !open"
                                                class="flex items-center justify-between w-full font-semibold text-sky-800">
                                                @lang('ethical-phishing-campaign.analyseCampaign.postActionsEmotions')
                                                <svg :class="{ 'rotate-180': open }"
                                                    class="w-4 h-4 transform transition-transform" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M19 9l-7 7-7-7" />
                                                </svg>
                                            </button>

                                            <!-- Collapsible Content -->
                                            <div x-show="open" x-transition class="mt-3">
                                                <ul
                                                    class="list-disc list-inside space-y-1 text-sm text-gray-700 font-medium">
                                                    @foreach ($user->response['post_actions_emotions'] as $emotion)
                                                        <li>{{ $emotion }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach

                    <!-- Paginator controls -->
                    <div id="pagination-controls" class="flex justify-around items-center">
                        <div class="flex justify-center w-1/3">
                            <x-primary-button id="prevUser">@lang('ethical-phishing-campaign.analyseCampaign.previous')</x-primary-button>
                        </div>
                        <div class="flex justify-center w-1/3">
                            <span id="userIndicator" class="text-gray-700"></span>
                        </div>
                        <div class="flex justify-center w-1/3">
                            <x-primary-button id="nextUser">@lang('ethical-phishing-campaign.analyseCampaign.next')</x-primary-button>
                        </div>
                    </div>

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

<script>
    // Charts initialization
    const totalEmailsCount = {{ $attackedDigitalTwins->count() }};
    const sentEmailsCount = {{ $emailSent->count() }};
    const emailOpenedCount = {{ $emailOpened->count() }};
    const emailNotOpenedCount = {{ $emailNotOpened->count() }};
    const emailClickedCount = {{ $emailClicked->count() }};
    const emailNotClickedCount = {{ $emailNotClicked->count() }};

    const opened = sentEmailsCount - emailNotOpenedCount
    const notOpened = sentEmailsCount - emailOpenedCount
    const dataOpenedEmails = [{
            status: 'Not opened',
            count: notOpened
        },
        {
            status: 'Opened',
            count: opened
        }
    ];

    new Chart(document.getElementById('openedEmails'), {
        type: 'doughnut',
        data: {
            labels: dataOpenedEmails.map(row => row.status),
            datasets: [{
                data: dataOpenedEmails.map(row => row.count),
                backgroundColor: ['#F44336', '#4CAF50']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'left',
                },
                title: {
                    display: true,
                    text: 'Emails opened (' + opened + ') and not opened (' + notOpened + ')'
                }
            }
        }
    });

    const clicked = sentEmailsCount - emailNotClickedCount
    const notClicked = sentEmailsCount - emailClickedCount
    const dataClickedEmails = [{
            status: 'Not clicked',
            count: notClicked
        },
        {
            status: 'Clicked',
            count: clicked
        }
    ];

    new Chart(document.getElementById('clickedEmails'), {
        type: 'doughnut',
        data: {
            labels: dataClickedEmails.map(row => row.status),
            datasets: [{
                data: dataClickedEmails.map(row => row.count),
                backgroundColor: ['#F44336', '#4CAF50']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'left',
                },
                title: {
                    display: true,
                    text: 'Emails clicked (' + clicked + ') and not clicked (' + notClicked + ')'
                }
            }
        }
    });

    const dataGenderOpened = [{
            status: 'Male',
            count: emailOpenedCount - {{ $openedFemaleCount }} - {{ $openedOtherCount }}
        },
        {
            status: 'Female',
            count: emailOpenedCount - {{ $openedMaleCount }} - {{ $openedOtherCount }}
        },
        {
            status: 'Other',
            count: emailOpenedCount - {{ $openedMaleCount }} - {{ $openedFemaleCount }}
        }
    ];

    new Chart(document.getElementById('genderOpened'), {
        type: 'doughnut',
        data: {
            labels: dataGenderOpened.map(row => row.status),
            datasets: [{
                data: dataGenderOpened.map(row => row.count),
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'left',
                },
                title: {
                    display: true,
                    text: 'Gender has opened on the phishing email'
                }
            }
        }
    });

    const dataGenderClicked = [{
            status: 'Male',
            count: emailClickedCount - {{ $clickedFemaleCount }} - {{ $clickedOtherCount }}
        },
        {
            status: 'Female',
            count: emailClickedCount - {{ $clickedMaleCount }} - {{ $clickedOtherCount }}
        },
        {
            status: 'Other',
            count: emailClickedCount - {{ $clickedMaleCount }} - {{ $clickedFemaleCount }}
        }
    ];

    new Chart(document.getElementById('genderClicked'), {
        type: 'doughnut',
        data: {
            labels: dataGenderClicked.map(row => row.status),
            datasets: [{
                data: dataGenderClicked.map(row => row.count),
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'left',
                },
                title: {
                    display: true,
                    text: 'Gender has clicked on the phishing link'
                }
            }
        }
    });

    // Tabulation and Filter
    document.addEventListener('DOMContentLoaded', function() {
        const users = @json($users);
        let currentUserIndex = 0;
        const userContainers = document.querySelectorAll(".user-entry");
        const prevUserButton = document.getElementById('prevUser');
        const nextUserButton = document.getElementById('nextUser');
        const userIndicator = document.getElementById('userIndicator');
        const paginationControls = document.getElementById('pagination-controls');

        function toggleNavigationButtons() {
            if (users.length <= 1) {
                prevUserButton.style.display = 'none';
                nextUserButton.style.display = 'none';
            } else {
                prevUserButton.style.display = currentUserIndex > 0 ? 'block' : 'none';
                nextUserButton.style.display = currentUserIndex < users.length - 1 ? 'block' : 'none';
            }
        }

        function showUser(index) {
            userContainers.forEach(function(container, idx) {
                container.style.display = idx === index ? 'block' : 'none';
            });
            userIndicator.textContent = `User: ${index + 1} / ${users.length}`;
            toggleNavigationButtons();
        }

        function resetSearch() {
            filterInput.value = "";
            clearFilterButton.style.display = "none";
            userContainers.forEach(function(container, index) {
                container.style.display = index === 0 ? 'block' : 'none';
            });
            currentUserIndex = 0;
            showUser(currentUserIndex);
            paginationControls.style.display = 'flex';
            noResultsMessage.style.display = 'none';
        }

        prevUserButton.addEventListener('click', function() {
            if (currentUserIndex > 0) {
                currentUserIndex--;
                showUser(currentUserIndex);
            }
        });

        nextUserButton.addEventListener('click', function() {
            if (currentUserIndex < users.length - 1) {
                currentUserIndex++;
                showUser(currentUserIndex);
            }
        });

        // Initialize: show the first user and manage navigation buttons
        showUser(currentUserIndex);

        // Filter handling
        const filterInput = document.getElementById("filter");
        const clearFilterButton = document.getElementById("clear-filter");
        const noResultsMessage = document.getElementById('no-results');

        if (filterInput && clearFilterButton) {
            filterInput.addEventListener("input", function() {
                const filterValue = this.value.toLowerCase().trim();

                clearFilterButton.style.display = this.value.trim() !== "" ? "block" : "none";

                let resultsFound = false;

                userContainers.forEach(function(container, index) {
                    const name = container.querySelector('.user-name').textContent
                        .toLowerCase();
                    const email = container.querySelector('.user-email').textContent
                        .toLowerCase();
                    const gender = container.querySelector('.user-gender').textContent
                        .toLowerCase();

                    if (name.includes(filterValue) || email.includes(filterValue) || gender
                        .includes(filterValue)) {
                        container.style.display = 'block';
                        if (index === 0) {
                            showUser(index);
                        }
                        resultsFound = true;
                    } else {
                        container.style.display = 'none';
                    }
                });

                // Show no results
                noResultsMessage.style.display = resultsFound ? 'none' : 'block';

                // Show pagination controls only if there's no active filter
                paginationControls.style.display = filterValue !== "" ? 'none' : 'flex';

                // Reset pagination and show first user if filter is cleared manually
                if (filterValue === "") {
                    resetSearch();
                }
            });

            clearFilterButton.addEventListener("click", function() {
                resetSearch();
            });
        }
    });
</script>
