<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row item-center gap-3">
            <!-- Back to digital twins -->
            <a href="{{ route('digital-twin.index') }}" class="cursor-pointer">
                <x-primary-button>
                    @lang('digital-twin.detailsPage.back')
                </x-primary-button>
            </a>
            <!-- Breadcrumb -->
            <ul class="flex flex-row gap-1 flex-wrap break-words text-sky-800">
                <li><a href="{{ route('digital-twin.index') }}">@lang('digital-twin.detailsPage.digitalTwin')</a></li>
                <li>/</li>
                <li>@lang('digital-twin.detailsPage.digitalTwinDetails')</li>
            </ul>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8 text-sky-900">
                    <p class="font-semibold text-xl">@lang('digital-twin.detailsPage.digitalTwinDetails')</p>

                    @if ($digitalTwin)
                        <div class="flex flex-col gap-4 mt-4">
                            <div class="flex flex-row gap-2">
                                <p class="font-semibold">@lang('digital-twin.detailsPage.name'):</p>
                                <p>{{ $digitalTwin->name }}</p>
                            </div>

                            <div class="flex flex-row gap-2">
                                <p class="font-semibold">@lang('digital-twin.detailsPage.surname'):</p>
                                <p>{{ $digitalTwin->surname }}</p>
                            </div>

                            <div class="flex flex-row gap-2">
                                <p class="font-semibold">@lang('digital-twin.detailsPage.dateOfBirth'):</p>
                                <p>{{ $digitalTwin->dob->format('d/m/Y') }}</p>
                            </div>

                            <div class="flex flex-row gap-2">
                                <p class="font-semibold">@lang('digital-twin.detailsPage.gender'):</p>
                                <p>{{ $digitalTwin->gender }}</p>
                            </div>

                            <div class="flex flex-row gap-2">
                                <p class="font-semibold">@lang('digital-twin.detailsPage.companyRole'):</p>
                                <p>{{ $digitalTwin->company_role }}</p>
                            </div>

                            <div>
                                <p class="font-semibold">@lang('digital-twin.detailsPage.prompt'):</p>
                                <textarea readonly rows="10"
                                    class="w-full p-2 border border-sky-700 focus:border-sky-800 focus:ring-sky-800 rounded-md shadow-sm">{{ $digitalTwin->prompt }}
                                </textarea>
                            </div>

                            @if (!empty($digitalTwin->human_factors))
                                <div>
                                    <p class="font-semibold">@lang('digital-twin.detailsPage.humanFactors'):</p>
                                    <div class="flex flex-wrap gap-2 mt-2">
                                        @foreach ($digitalTwin->human_factors as $factor => $value)
                                            <x-chip>{{ $factor }}</x-chip>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="text-center text-xl">
                            <p>@lang('digital-twin.detailsPage.errorRetrievingDigitalTwin')</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
