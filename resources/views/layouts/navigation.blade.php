<nav x-data="{ open: false }" class="bg-white border-b border-sky-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 md:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">

                    @if (Auth::user() && Auth::user()->role == 'User')
                        <a href="{{ route('questionnaires-campaign.index') }}">
                            <p class="text-xl font-extrabold text-sky-900">DAMOCLES</p>
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}">
                            <p class="text-xl font-extrabold text-sky-900">DAMOCLES</p>
                        </a>
                    @endif

                </div>

                <!-- Navigation Links -->
                @if (Auth::user() && Auth::user()->role != 'User')
                    <div class="hidden space-x-8 md:-my-px md:ms-10 md:flex">
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                            @lang('dashboard.dashboard')
                        </x-nav-link>
                    </div>
                @endif

                @if (Auth::user() && Auth::user()->role == 'Admin')
                    <div class="hidden space-x-8 md:-my-px md:ms-10 md:flex">
                        <x-nav-link :href="route('users', ['role' => 'user'])" :active="request()->routeIs('users')">
                            @lang('dashboard.users')
                        </x-nav-link>
                    </div>
                    <div class="hidden space-x-8 md:-my-px md:ms-10 md:flex">
                        <x-nav-link :href="route('llms.index')" :active="request()->routeIs('llms.index')">
                            @lang('dashboard.llm')
                        </x-nav-link>
                    </div>
                @endif

                @if (Auth::check() && Auth::user()->role == 'Evaluator')
                    <!-- Evaluation Campaign -->
                    <div class="hidden md:flex md:items-center md:ms-6">
                        <x-dropdown align="left" class="hover:text-red-500">
                            <x-slot name="trigger">
                                <button
                                    class="inline-flex items-center border border-transparent text-sm leading-4 font-medium rounded-md text-sky-800 bg-white hover:text-sky-700 focus:outline-none transition ease-in-out duration-150">

                                    <div>@lang('dashboard.evaluationCampaign')</div>

                                    <div>
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>

                            <x-slot name="content">

                                <x-dropdown-link :href="route('phishing-campaign.index')">
                                    @lang('dashboard.phishingCampaign')
                                </x-dropdown-link>

                                <x-dropdown-link :href="route('ethical-phishing-campaign.index')">
                                    @lang('dashboard.ethicalPhishingCampaign')
                                </x-dropdown-link>

                                <x-dropdown-link :href="route('questionnaires-campaign.index')">
                                    @lang('dashboard.surveyCampaign')
                                </x-dropdown-link>

                            </x-slot>
                        </x-dropdown>
                    </div>

                    <!-- Phishing Campaign -->
                    <div class="hidden md:flex md:items-center md:ms-6">
                        <x-dropdown align="left" class="hover:text-red-500">
                            <x-slot name="trigger">
                                <button
                                    class="inline-flex items-center border border-transparent text-sm leading-4 font-medium rounded-md text-sky-800 bg-white hover:text-sky-700 focus:outline-none transition ease-in-out duration-150">
                                    <div>@lang('dashboard.advanced')</div>
                                    <div>
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>

                            <x-slot name="content">

                                <x-dropdown-link :href="route('phishing-campaign.option')">
                                    @lang('dashboard.phishingCampaignOption')
                                </x-dropdown-link>

                                <x-dropdown-link :href="route('questionnaires')">
                                    @lang('dashboard.availableQuestionnaires')
                                </x-dropdown-link>

                            </x-slot>
                        </x-dropdown>
                    </div>

                    <div class="hidden md:flex md:items-center md:ms-6">
                        <x-nav-link :href="route('digital-twin.index')" :active="request()->routeIs('digital-twin')">
                            @lang('dashboard.digitalTwins')
                        </x-nav-link>
                    </div>
                @endif

                @if (Auth::check() && Auth::user()->role == 'User')
                    <div class="hidden space-x-8 md:-my-px md:ms-10 md:flex">
                        <x-nav-link :href="route('questionnaires-campaign.index')" :active="request()->routeIs('questionnaires-campaign.index')">
                            @lang('dashboard.questionnaireCampaign')
                        </x-nav-link>
                    </div>
                @endif

            </div>

            <!-- Settings Dropdown -->
            <div class="hidden md:flex md:items-center md:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-sky-800 bg-white hover:text-sky-600 focus:outline-none transition ease-in-out duration-150">
                            <div class="pr-1 font-semibold">{{ Auth::user()->name }}</div>
                            <div class="font-semibold">{{ Auth::user()->surname }}</div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            @lang('dashboard.profile')
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                @lang('dashboard.logout')
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center md:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-sky-800 hover:text-sky-600 hover:bg-sky-100 focus:outline-none focus:bg-sky-100 focus:text-sky-700 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden md:hidden">
        <div class="pt-2 pb-1 space-y-1">
            @if (Auth::user() && Auth::user()->role != 'User')
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    @lang('dashboard.dashboard')
                </x-responsive-nav-link>
            @endif

            @if (Auth::user() && Auth::user()->role == 'Admin')
                <x-responsive-nav-link :href="route('users', ['role' => 'user'])" :active="request()->routeIs('users')">
                    @lang('dashboard.users')
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('llms.index')" :active="request()->routeIs('llms.index')">
                    @lang('dashboard.llm')
                </x-responsive-nav-link>
            @endif

            @if (Auth::check() && Auth::user()->role == 'Evaluator')
                <!-- Evaluation Campaign -->
                <div class="pt-4 pb-1 border-t border-sky-200">
                    <div class="px-4">
                        <div class="font-semibold text-base text-sky-800">@lang('dashboard.evaluationCampaign')</div>
                    </div>
                    <div class="mt-3 space-y-1">
                        <x-responsive-nav-link :href="route('phishing-campaign.index')">
                            @lang('dashboard.phishingCampaign')
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('questionnaires-campaign.index')">
                            @lang('dashboard.surveyCampaign')
                        </x-responsive-nav-link>
                    </div>
                </div>

                <!-- Phising Campaign -->
                <div class="pt-4 pb-1 border-t border-sky-200">
                    <div class="px-4">
                        <div class="font-semibold text-base text-sky-800">@lang('dashboard.advanced')</div>
                    </div>
                    <div class="mt-3 space-y-1">
                        <x-responsive-nav-link :href="route('phishing-campaign.option')">
                            @lang('dashboard.phishingCampaignOption')
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('questionnaires')">
                            @lang('dashboard.availableQuestionnaires')
                        </x-responsive-nav-link>
                    </div>
                </div>
            @endif

            @if (Auth::check() && Auth::user()->role == 'User')
                <x-responsive-nav-link :href="route('questionnaires-campaign.index')" :active="request()->routeIs('questionnaires-campaign.index')">
                    @lang('dashboard.questionnaireCampaign')
                </x-responsive-nav-link>
            @endif

        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-sky-200">
            <div class="px-4">
                <div class="font-semibold text-base text-sky-800">{{ Auth::user()->name }} {{ Auth::user()->surname }}
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    @lang('dashboard.profile')
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        @lang('dashboard.logout')
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
