<section class="text-sky-800">
    <div class="flex flex-row w-full items-center justify-between">
        <p class="text-lg font-medium text-sky-900">
            @lang('digital-twin.modals.evaluatePromptTitle')
        </p>
    </div>

    <form method="POST" action="{{ route('fake-user.create') }}"
        @submit.prevent="
            fetch($el.action, {
                method: 'POST',
                body: new FormData($el),
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            }).then(response => {
                if (response.ok) {
                    $dispatch('close');
                    window.location.reload();
                }
            })"
        class="space-y-4">
        @csrf

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <!-- Name -->
            <div>
                <x-input-label for="name" :value="__('digital-twin.modals.newFakeUser.name')" />
                <x-text-input id="name" name="name" type="text" class="w-full"
                    placeholder="{{ __('digital-twin.modals.newFakeUser.placeholderName') }}" required />
                <x-input-error :messages="$errors->get('name')" />
            </div>

            <!-- Surname -->
            <div>
                <x-input-label for="surname" :value="__('digital-twin.modals.newFakeUser.surname')" />
                <x-text-input id="surname" name="surname" type="text" class="w-full"
                    placeholder="{{ __('digital-twin.modals.newFakeUser.placeholderSurname') }}" required />
                <x-input-error :messages="$errors->get('surname')" />
            </div>

            <!-- Gender -->
            <div>
                <x-input-label for="gender" :value="__('digital-twin.modals.newFakeUser.gender')" />
                <select id="gender" name="gender"
                    class="mt-1 block border-sky-800 focus:border-sky-900 focus:ring-sky-800 rounded-md shadow-sm w-full"
                    required autofocus autocomplete="gender">
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Other">Other</option>
                </select>
                <x-input-error :messages="$errors->get('gender')" />
            </div>

            <!-- Date of Birth -->
            <div>
                <x-input-label for="dob" :value="__('digital-twin.modals.newFakeUser.dob')" />
                <input type="date" id="dob" name="dob"
                    class="mt-1 w-full border border-sky-800 focus:border-sky-800 focus:ring-sky-800 rounded-md cursor-pointer"
                    required>
                <x-input-error :messages="$errors->get('dob')" class="mt-2" />
            </div>

            <!-- Company Role -->
            <div class="col-span-1 sm:col-span-2">
                <x-input-label for="company_role" :value="__('digital-twin.modals.newFakeUser.companyRole')" />
                <x-text-input id="company_role" name="company_role" type="text" class="w-full"
                    placeholder="{{ __('digital-twin.modals.newFakeUser.placeholderCompanyRole') }}" required />
                <x-input-error :messages="$errors->get('company_role')" />
            </div>
        </div>

        <!-- Human Factors -->
        <div class="mt-4">
            <x-input-label for="human_factors" :value="__('digital-twin.modals.newFakeUser.humanFactors')" />
            <div class="border border-sky-700 rounded-md p-3 mt-2 space-y-3 max-h-64 overflow-y-auto">
                @foreach ($allHumanFactors as $factor)
                    <div class="flex items-center justify-between">
                        <label class="text-sm font-medium text-sky-800">
                            <input type="checkbox" name="human_factors[{{ $factor }}][enabled]" value="1"
                                class="selected_user_checkbox form-checkbox text-sky-700 rounded-md mr-2">
                            {{ $factor }}
                        </label>
                        <input type="range" name="human_factors[{{ $factor }}][value]" min="1"
                            max="5" step="1" value="3" class="w-1/4 bg-sky-800" />
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Submit -->
        <div class="pt-4 flex justify-end">
            <x-primary-button type="submit">
                {{ __('digital-twin.modals.newFakeUser.create') }}
            </x-primary-button>
        </div>
    </form>
</section>
