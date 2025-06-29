<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row item-center gap-3">
            <!-- Back to phishing campaign -->
            <a href="{{ route('ethical-phishing-campaign.index') }}" class="cursor-pointer">
                <x-primary-button>
                    @lang('ethical-phishing-campaign.newPhishingCampaign.back')
                </x-primary-button>
            </a>
            <!-- Breadcrumb -->
            <ul class="flex flex-row gap-1 flex-wrap break-words text-sky-800">
                <li><a href="{{ route('ethical-phishing-campaign.index') }}">@lang('ethical-phishing-campaign.newPhishingCampaign.phishingCampaign')</a></li>
                <li>/</li>
                <li><a href="{{ route('ethical-phishing-campaign.new') }}">@lang('ethical-phishing-campaign.newPhishingCampaign.new')</a></li>
            </ul>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white sm:overflow-hidden shadow-sm sm:rounded-lg">
                <form id="createCampaignForm" data-id="" action="{{ route('ethical-phishing-campaign.create') }}"
                    class="p-8 text-sky-900 space-y-4" method="POST">
                    @csrf
                    @method('post')
                    <p class="font-semibold text-xl">@lang('ethical-phishing-campaign.newPhishingCampaign.new')</p>
                    <p class="font-semibold text-sm">@lang('ethical-phishing-campaign.newPhishingCampaign.mandatoryField')</p>
                    <div>
                        <p class="text-lg font-medium text-sky-900">
                            *@lang('ethical-phishing-campaign.newPhishingCampaign.title'):
                        </p>
                        <x-input-label for="title" class="text-md block font-medium text-sky-700 pb-2"
                            :value="__('ethical-phishing-campaign.newPhishingCampaign.value.title')" />
                        <input type="text" id="title" name="title"
                            class="border border-sky-700 focus:border-sky-800 focus:ring-sky-800 rounded-md w-full sm:w-2/3"
                            required>
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />

                    </div>

                    <div>
                        <p class="text-lg font-medium text-sky-900">
                            *@lang('ethical-phishing-campaign.newPhishingCampaign.description'):
                        </p>
                        <x-input-label for="description" class="text-md block font-medium text-sky-700 pb-2"
                            :value="__('ethical-phishing-campaign.newPhishingCampaign.value.description')" />
                        <input type="text" id="description" name="description"
                            class="border border-sky-700 focus:border-sky-800 focus:ring-sky-800 rounded-md w-full"
                            required>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />

                    </div>

                    <div>
                        @include('ethical-phishing-campaign.partials.phishing-llm')
                    </div>

                    <div class="space-y-2">
                        <!-- Email label -->
                        <p class="text-lg font-medium text-sky-900">
                            *@lang('ethical-phishing-campaign.newPhishingCampaign.email'):
                        </p>
                        <x-input-label for="email" class="text-md block font-medium text-sky-700 pb-2"
                            :value="__('ethical-phishing-campaign.newPhishingCampaign.value.email')" />
                        <!-- Subject input and EML upload button -->
                        <div class="flex gap-2">
                            <input type="text" name="subject" id="subject"
                                placeholder="{{ __('ethical-phishing-campaign.newPhishingCampaign.value.subject') }}"
                                class="flex-1 p-2 border border-sky-700 focus:border-sky-800 focus:ring-sky-800 rounded-md shadow-sm" />
                            <x-input-error :messages="$errors->get('subject')" class="mt-2" />


                            <label x-data="" for="emlFile"
                                class="inline-flex items-center px-4 py-2 bg-sky-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-sky-700 focus:bg-sky-700 active:bg-sky-900 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2 transition ease-in-out duration-150' cursor-pointer">
                                @lang('ethical-phishing-campaign.newPhishingCampaign.uploadButton')
                            </label>
                            <input type="file" id="emlFile" name="emlFile" accept=".eml" class="hidden" />
                        </div>

                        <!-- Content textarea -->
                        <div>
                            <textarea id="content" name="content"
                                placeholder="{{ __('ethical-phishing-campaign.newPhishingCampaign.value.content') }}"
                                class="w-full p-2 border border-sky-700 focus:border-sky-800 focus:ring-sky-800 rounded-md shadow-sm"
                                rows="10"></textarea>
                            <x-input-error :messages="$errors->get('content')" class="mt-2" />

                        </div>
                    </div>



                    <div class="flex flex-row justify-around items-center pt-6">
                        <div class="flex w-1/3">
                        </div>
                        <!-- Circles which indicates the steps of the creation -->
                        <div class="flex flex-row w-1/3 justify-center items-center">
                            <span class="status active"></span>
                            <span class="status"></span>
                            <span class="status"></span>
                        </div>
                        <div class="flex w-1/3 justify-center">
                            <x-primary-button id="continue" type="submit">@lang('ethical-phishing-campaign.continue')</x-primary-button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
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
        <p class="text-2xl font-semibold text-red-700 pb-8">@lang('ethical-phishing-campaign.newPhishingCampaign.errorData')</p>
        <x-secondary-button x-on:click="$dispatch('close')">@lang('ethical-phishing-campaign.newPhishingCampaign.close')</x-secondary-button>
    </div>
</x-modal>

<!-- Error date modal -->
<x-modal name="error-date-modal" id="error-date-modal" title="Error date" :show="false">
    <div class="p-6 rounded-lg relative text-center">
        <p class="text-2xl font-semibold text-red-700 pb-8">@lang('ethical-phishing-campaign.newPhishingCampaign.errorDate')</p>
        <x-secondary-button x-on:click="$dispatch('close')">@lang('ethical-phishing-campaign.newPhishingCampaign.close')</x-secondary-button>
    </div>
</x-modal>

<!-- Error llm modal -->
<x-modal name="error-llm-modal" id="error-llm-modal" title="Error llm" :show="false">
    <div class="p-6 rounded-lg relative text-center">
        <p class="text-2xl font-semibold text-red-700 pb-8">@lang('ethical-phishing-campaign.newPhishingCampaign.noLLMs')</p>
        <x-secondary-button x-on:click="$dispatch('close')">@lang('ethical-phishing-campaign.newPhishingCampaign.close')</x-secondary-button>
    </div>
</x-modal>



<x-modal name="eml-error-modal">
    <section class="text-sky-800 p-6">
        <!-- Modal Title -->
        <p class="text-xl font-semibold text-center">
            @lang('ethical-phishing-campaign.modals.elmErrorTitle')
        </p>

        <!-- Modal Message -->
        <p class="pb-4 pt-6 text-center">
            @lang('ethical-phishing-campaign.modals.elmErrorMessage')
        </p>

        <!-- Buttons -->
        <div class="flex justify-end gap-3 mt-6">
            <x-secondary-button x-on:click="$dispatch('close-modal', 'eml-error-modal')">
                @lang('ethical-phishing-campaign.modals.close')
            </x-secondary-button>
        </div>
    </section>
</x-modal>



<script>
    document.addEventListener('DOMContentLoaded', function() {
        const emlInput = document.getElementById('emlFile');

        if (!emlInput) return;

        emlInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (!file) return;

            const reader = new FileReader();

            reader.onload = function(e) {
                const rawContent = e.target.result;
                const content = rawContent.replace(/\r\n/g, '\n'); // normalize line endings

                // Extract Subject
                const subjectMatch = content.match(/^Subject:\s*(.+)$/m);
                const subject = subjectMatch ? subjectMatch[1].trim() : '';

                // Extract MIME parts
                const parts = content.split(/--[^\n]+/);
                let plainBody = '';
                let htmlBody = '';

                for (const part of parts) {
                    const typeMatch = part.match(/Content-Type:\s*(text\/plain|text\/html)/i);
                    if (!typeMatch) continue;

                    const [, type] = typeMatch;
                    const bodyMatch = part.split('\n\n')[1]; // split headers from body
                    if (!bodyMatch) continue;

                    if (type === 'text/plain' && !plainBody) {
                        plainBody = bodyMatch.trim();
                    } else if (type === 'text/html' && !htmlBody) {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(bodyMatch, "text/html");
                        htmlBody = doc.body.innerText.trim();
                    }
                }

                const finalBody = plainBody || htmlBody || '';

                // Populate form fields
                const subjectInput = document.getElementById('subject');
                const contentTextarea = document.getElementById('content');

                if (subjectInput) subjectInput.value = subject;
                if (contentTextarea) contentTextarea.value = finalBody;

                console.log(finalBody.trim() === '')

                // Fallback if no body found
                if (finalBody.trim() === '') {
                    // Dispatch Alpine event to open modal
                    window.dispatchEvent(new CustomEvent('open-modal', {
                        detail: 'eml-error-modal'
                    }));
                }
            };

            reader.readAsText(file);
        });
    });
</script>
