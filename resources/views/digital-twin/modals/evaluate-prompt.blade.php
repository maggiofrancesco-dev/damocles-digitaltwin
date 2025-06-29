<section class="text-sky-800">
    <div class="flex flex-row w-full items-center justify-between">
        <p class="text-lg font-medium text-sky-900">
            @lang('digital-twin.modals.evaluatePromptTitle')
        </p>
    </div>

    <div class="pt-4 w-full">
        <div class="text-sky-900 w-full space-y-4">

            <div>
                <x-input-label for="title" class="text-md block font-medium text-sky-700 pb-2" :value="__('digital-twin.newDigitalTwin.value.prompt')" />

                <div class="flex flex-col gap-2">

                    <textarea name="promptModal" id="promptModal" placeholder="" rows="8"
                        class="border border-sky-700 focus:border-sky-800 focus:ring-sky-800 rounded-md w-full">{{ $prompt }}</textarea>
                </div>
            </div>


            <div class=" flex justify-end gap-2">
                <x-primary-button type='button' x-on:click="$dispatch('close')">@lang('digital-twin.evaluateClose')</x-primary-button>
            </div>
            </form>

        </div>
</section>

<script>
    function fillTemplate(template, data) {
        for (const key in data) {
            const placeholder = `\{\{${key}\}\}`;
            template = template.split(placeholder).join(data[key]);
        }
        return template;
    }

    const evaluateButtons = document.querySelectorAll('[data-user]');
    const promptTextarea = document.querySelector('#promptModal');

    // Store the original template when the page loads
    const originalTemplate = promptTextarea.textContent.trim();

    evaluateButtons.forEach(button => {
        button.addEventListener('click', function() {
            const user = JSON.parse(button.getAttribute('data-user'));

            // Reset the prompt to original template before inserting new data
            let newPrompt = fillTemplate(originalTemplate, user);

            const hfs = Object.entries(user.human_factors).map(
                ([key, value]) => `\n${key}: Risk level ${value} out of 5`
            ).join('');

            newPrompt += `\n\nOther vulnerable human factors of this user are: ${hfs}`;

            promptTextarea.textContent = newPrompt;
        });
    });

    document.getElementById('deleteForm')?.addEventListener('submit', function(event) {
        const overlay = document.getElementById('loadingOverlay');
        if (overlay) {
            overlay.classList.add('flex');
            overlay.classList.remove('hidden');
        }
    });
</script>
