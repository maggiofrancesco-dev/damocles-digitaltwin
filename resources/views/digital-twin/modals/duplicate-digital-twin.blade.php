<section class="text-sky-800">
    <!-- Duplicate phishing campaign -->
    <p class="text-xl font-semibold text-center">
        @lang('digital-twin.modals.duplicate')
    </p>
    <div class="pt-4">
        <form id="duplicateForm" data-id="" action="{{ route('digital-twin.duplicate', ['digitalTwin' => 'id']) }}"
            method="GET">
            <p class="pb-4">@lang('digital-twin.modals.duplicateMessage')</p>
            <div class="flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')">@lang('digital-twin.modals.cancel')</x-secondary-button>
                <x-primary-button type="submit">@lang('digital-twin.modals.confirm')</x-primary-button>
            </div>
        </form>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const duplicateButton = document.querySelectorAll('[data-id]');

        duplicateButton.forEach(button => {
            button.addEventListener('click', function() {
                const id = button.getAttribute('data-id');
                const duplicateForm = document.getElementById('duplicateForm');
                const actionUrl =
                    "{{ route('digital-twin.duplicate', ['digitalTwin' => 'id']) }}";

                duplicateForm.setAttribute('data-id', id);
                duplicateForm.action = actionUrl.replace('id', id);
            });
        });

        document.getElementById('duplicateForm').addEventListener('submit', function(event) {
            document.getElementById('loadingOverlay').classList.add('flex');
            document.getElementById('loadingOverlay').classList.remove('hidden');
        });
    });
</script>
