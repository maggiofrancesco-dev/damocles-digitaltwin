<section class="text-sky-800">
    <!-- Delete phishing campaign -->
    <p class="text-xl font-semibold text-center">
        @lang('fake-user.modals.delete')
    </p>
    <div class="pt-4">
        <form id="deleteForm" data-id="" action="{{ route('fake-user.destroy', ['fakeUserId' => 'id']) }}"
            method="POST">
            @csrf
            @method('DELETE')
            <p class="pb-4">@lang('fake-user.modals.deleteMessage')</p>
            <div class="flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')">@lang('fake-user.modals.cancel')</x-secondary-button>
                <x-danger-button type="submit">@lang('fake-user.modals.confirm')</x-danger-button>
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
                    "{{ route('fake-user.destroy', ['fakeUserId' => 'id']) }}";

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
