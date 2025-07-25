<section class="text-sky-800">
    <!-- Delete questionnaire campaign -->
    <p class="text-xl font-semibold text-center">
        @lang('questionnaire-campaign.modals.delete')
    </p>
    <div class="pt-4">
        <form id="deleteForm" data-id=""
            action="{{ route('questionnaire-campaign.destroy', ['questionnaireCampaign' => 'id']) }}" method="POST">
            @csrf
            @method('DELETE')
            <p class="pb-4">@lang('questionnaire-campaign.modals.deleteMessage')</p>
            <div class="flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')">@lang('questionnaire-campaign.modals.cancel')</x-secondary-button>
                <x-danger-button type="submit">@lang('questionnaire-campaign.modals.confirm')</x-danger-button>
            </div>
        </form>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteButton = document.querySelectorAll('[data-id]');

        deleteButton.forEach(button => {
            button.addEventListener('click', function() {
                const id = button.getAttribute('data-id');
                const deleteForm = document.getElementById('deleteForm');
                const actionUrl =
                    "{{ route('questionnaire-campaign.destroy', ['questionnaireCampaign' => 'id']) }}";

                deleteForm.setAttribute('data-id', id);
                deleteForm.action = actionUrl.replace('id', id);
            });
        });

        document.getElementById('deleteForm').addEventListener('submit', function(event) {
            document.getElementById('loadingOverlay').classList.add('flex');
            document.getElementById('loadingOverlay').classList.remove('hidden');
        });
    });
</script>
