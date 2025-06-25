<section class="text-sky-800">
    <div class="flex flex-row w-full items-center justify-between">
        <p class="text-lg font-medium text-sky-900">
            @lang('digital-twin.modals.evaluatePromptTitle')
        </p>
    </div>

    <div class="pt-4 w-full">
        <form class="text-sky-900 w-full space-y-4" method="POST">
            @csrf
            @if ($llms->isEmpty())
                <p class="text-center">{{ _('No Large Language Models available, contact the Admin to add the LLMs!') }}
                </p>
            @else
                <x-input-label for="llmSelect" class="text-md block font-medium text-sky-700" :value="__('phishing-campaign.partials.llm.value')" />
                <select id="llmSelect" name="llm"
                    class="mt-1 block w-full sm:w-1/2 py-2 px-3 border border-sky-800 bg-white rounded-md shadow-sm focus:outline-none focus:ring-sky-800 focus:border-sky-800 sm:text-sm">
                    @foreach ($llms as $llm)
                        <option value="{{ $llm->id }}">{{ $llm->provider }}: {{ $llm->model }}
                        </option>
                    @endforeach
                </select>
            @endif

            <div x-data="{ tab: 'prompt' }">

                <!-- Tab Buttons -->
                <div class="flex gap-2 w-full">
                    <button type="button" @click="tab = 'prompt'"
                        :class="tab === 'prompt' ? 'bg-sky-700 text-white' : 'bg-sky-100 text-sky-900'"
                        class="w-full flex-1 px-4 py-2 rounded text-sm font-medium">Prompt</button>

                    <button type="button" @click="tab = 'result'"
                        :class="tab === 'result' ? 'bg-sky-700 text-white' : 'bg-sky-100 text-sky-900'"
                        class="w-full flex-1 px-4 py-2 rounded text-sm font-medium">Result</button>
                </div>

                <!-- Tab Content -->
                <div class="p-2">

                    <div x-show="tab === 'prompt'">
                        <div>
                            <x-input-label for="title" class="text-md block font-medium text-sky-700 pb-2"
                                :value="__('digital-twin.newDigitalTwin.value.prompt')" />

                            <div class="flex flex-col gap-2">

                                <textarea name="prompt" id="prompt" placeholder="" rows="8"
                                    class="border border-sky-700 focus:border-sky-800 focus:ring-sky-800 rounded-md w-full">{{ $prompt }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div x-show="tab === 'result'" class="flex flex-col gap-2">
                        <span class="text-sm block font-medium text-sky-700 ">@lang('digital-twin.evaluation.resultDetails')</span>
                        <div x-data="{ open: false }" class="border border-sky-700 rounded p-4">
                            <!-- Header -->
                            <button type="button" @click="open = !open"
                                class="flex items-center justify-between w-full font-semibold text-sky-800">
                                @lang('digitalTwins.evaluate.internalReasoning')
                                <svg :class="{ 'rotate-180': open }" class="w-4 h-4 transform transition-transform"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <!-- Collapsible Content -->
                            <div x-show="open" x-transition class="mt-4 space-y-3">
                                <p class="text-sm text-gray-700">
                                    This is the first paragraph of reasoning or explanation.
                                </p>
                                <p class="text-sm text-gray-700">
                                    Here's a second insight that provides more detail.
                                </p>
                                <p class="text-sm text-gray-700">
                                    A third paragraph could further elaborate on the internal logic.
                                </p>
                            </div>
                        </div>

                        <div x-data="{ open: false }" class="border border-sky-700 rounded p-4">
                            <!-- Header -->
                            <button type="button" @click="open = !open"
                                class="flex items-center justify-between w-full font-semibold text-sky-800">
                                @lang('digitalTwins.evaluate.sequenceOfActions')
                                <svg :class="{ 'rotate-180': open }" class="w-4 h-4 transform transition-transform"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <!-- Collapsible Content -->
                            <div x-show="open" x-transition class="mt-4 space-y-3">
                                <p class="text-sm text-gray-700">
                                    This is the first paragraph of reasoning or explanation.
                                </p>
                                <p class="text-sm text-gray-700">
                                    Here's a second insight that provides more detail.
                                </p>
                                <p class="text-sm text-gray-700">
                                    A third paragraph could further elaborate on the internal logic.
                                </p>
                            </div>
                        </div>

                        <div x-data="{ open: false }" class="border border-sky-700 rounded p-4">
                            <!-- Header -->
                            <button type="button" @click="open = !open"
                                class="flex items-center justify-between w-full font-semibold text-sky-800">
                                @lang('digitalTwins.evaluate.postActions')
                                <svg :class="{ 'rotate-180': open }" class="w-4 h-4 transform transition-transform"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <!-- Collapsible Content -->
                            <div x-show="open" x-transition class="mt-4 space-y-3">
                                <p class="text-sm text-gray-700">
                                    This is the first paragraph of reasoning or explanation.
                                </p>
                                <p class="text-sm text-gray-700">
                                    Here's a second insight that provides more detail.
                                </p>
                                <p class="text-sm text-gray-700">
                                    A third paragraph could further elaborate on the internal logic.
                                </p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class=" flex justify-end gap-2">
                <x-secondary-button type='button'
                    x-on:click="$dispatch('close')">@lang('digital-twin.evaluate.close')</x-secondary-button>
                <x-primary-button>@lang('digital-twin.evaluate.evaluate')</x-secondary-button>
            </div>
        </form>

    </div>
</section>

<script></script>
