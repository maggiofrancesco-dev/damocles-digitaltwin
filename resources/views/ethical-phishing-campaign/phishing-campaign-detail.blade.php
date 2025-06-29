{{-- 
    Author: Davide Viccari
--}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row item-center gap-3">
            <!-- Back to phishing campaign -->
            <a href="{{ route('ethical-phishing-campaign.index') }}" class="cursor-pointer">
                <x-primary-button>
                    @lang('ethical-phishing-campaign.detailsCampaign.back')
                </x-primary-button>
            </a>
            <!-- Breadcrumb -->
            <ul class="flex flex-row gap-1 flex-wrap break-words text-sky-800">
                <li><a href="{{ route('ethical-phishing-campaign.index') }}">@lang('ethical-phishing-campaign.detailsCampaign.phishingCampaign')</a></li>
                <li>/</li>
                <li>@lang('ethical-phishing-campaign.detailsCampaign.phishingCampaignDetails')</li>
            </ul>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8 text-sky-900">

                    <p class="font-semibold text-xl">@lang('ethical-phishing-campaign.detailsCampaign.phishingCampaignDetails')</p>

                    @if ($phishingCampaign)
                        <div class="flex flex-col">
                            <div class="flex flex-row gap-2">
                                <p class="font-semibold">
                                    @lang('ethical-phishing-campaign.detailsCampaign.title'):
                                </p>
                                <p>{{ $phishingCampaign->title }}</p>
                            </div>

                            <div class="flex flex-row gap-2">
                                <p class="font-semibold">
                                    @lang('ethical-phishing-campaign.detailsCampaign.description'):
                                </p>
                                <p>{{ $phishingCampaign->description }}</p>
                            </div>

                            <div class="flex flex-col">
                                <p class="font-semibold">@lang('ethical-phishing-campaign.detailsCampaign.llm'):</p>
                                @if ($llm)
                                    <div class="flex flex-col">
                                        <p>@lang('ethical-phishing-campaign.detailsCampaign.provider'): {{ $llm->provider }}</p>
                                        <p> @lang('ethical-phishing-campaign.detailsCampaign.model'): {{ $llm->model }}</p>
                                    </div>
                                @else
                                    <p>@lang('ethical-phishing-campaign.detailsCampaign.llmNotFound')</p>
                                @endif
                            </div>

                            <div class="pt-2">
                                <p class="font-semibold">@lang('ethical-phishing-campaign.detailsCampaign.subject'):</p>
                                <input type="text" id="subject"
                                    class="w-full p-2 border border-sky-700 focus:border-sky-800 focus:ring-sky-800 rounded-md shadow-sm tab-content"
                                    readonly value="{{ $phishingCampaign->subject }}" />

                                <p class="font-semibold">@lang('ethical-phishing-campaign.detailsCampaign.content'):</p>
                                <textarea id="content"
                                    class="w-full p-2 border border-sky-700 focus:border-sky-800 focus:ring-sky-800 rounded-md shadow-sm tab-content"
                                    rows="10" readonly>{{ $phishingCampaign->content }}</textarea>
                            </div>

                        </div>
                    @else
                        <div class="text-center text-xl">
                            <p>@lang('ethical-phishing-campaign.detailsCampaign.errorRetrievingCampaign')</p>
                        </div>
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

<script></script>
