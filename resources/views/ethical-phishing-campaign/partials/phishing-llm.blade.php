<section>
    <header>
        <p class="text-lg font-medium text-sky-900">
            @lang('ethical-phishing-campaign.partials.llm.llm')
        </p>
    </header>

    @if ($llms->isEmpty())
        <p class="text-center">{{ _('No Large Language Models available, contact the Admin to add the LLMs!') }}</p>
    @else
        <x-input-label for="llmSelect" class="text-md block font-medium text-sky-700" :value="__('ethical-phishing-campaign.partials.llm.value')" />
        <select id="llmSelect" name="llm"
            class="mt-1 block w-full sm:w-1/2 py-2 px-3 border border-sky-800 bg-white rounded-md shadow-sm focus:outline-none focus:ring-sky-800 focus:border-sky-800 sm:text-sm">
            @foreach ($llms as $llm)
                <option value="{{ $llm->id }}">{{ $llm->provider }}: {{ $llm->model }}
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('llm')" class="mt-2" />

    @endif

</section>
