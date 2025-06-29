{{-- 
    Author: Gioele Giannico
--}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row item-center gap-3">
            <!-- Back to digital twin -->
            <a href="{{ route('digital-twin.index') }}" class="cursor-pointer">
                <x-primary-button>
                    @lang('digital-twin.newDigitalTwin.back')
                </x-primary-button>
            </a>
            <!-- Breadcrumb -->
            <ul class="flex flex-row gap-1 flex-wrap break-words text-sky-800">
                <li><a href="{{ route('digital-twin.index') }}">@lang('digital-twin.newDigitalTwin.digitalTwin')</a></li>
                <li>/</li>
                <li><a href="{{ route('digital-twin.new') }}">@lang('digital-twin.newDigitalTwin.new')</a></li>
            </ul>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white sm:overflow-hidden shadow-sm sm:rounded-lg">
                <form class="p-8 text-sky-900 w-full space-y-4" method="POST"
                    action="{{ route('digital-twin.redirect-fake-users') }}">
                    @csrf

                    <p class="font-semibold text-xl">@lang('digital-twin.newDigitalTwin.new')</p>
                    <p class="font-semibold text-sm">@lang('digital-twin.newDigitalTwin.mandatoryField')</p>
                    <div class="flex w-full">
                        <div class="w-1/2">
                            <p class="text-lg font-medium text-sky-900">
                                *@lang('digital-twin.newDigitalTwin.name'):
                            </p>
                            <x-input-label for="name" class="text-md block font-medium text-sky-700 pb-2"
                                :value="__('digital-twin.newDigitalTwin.value.name')" />
                            <input type="text" id="name" name="name"
                                class="border border-sky-700 focus:border-sky-800 focus:ring-sky-800 rounded-md w-full sm:w-2/3"
                                required>
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>
                        <div class="w-1/2">
                            <p class="text-lg font-medium text-sky-900">
                                *@lang('digital-twin.newDigitalTwin.surname'):
                            </p>
                            <x-input-label for="surname" class="text-md block font-medium text-sky-700 pb-2"
                                :value="__('digital-twin.newDigitalTwin.value.surname')" />
                            <input type="text" id="surname" name="surname"
                                class="border border-sky-700 focus:border-sky-800 focus:ring-sky-800 rounded-md w-full sm:w-2/3"
                                required>
                            <x-input-error :messages="$errors->get('surname')" class="mt-2" />
                        </div>
                    </div>
                    <div class="flex w-full">
                        <div class="w-1/2">
                            <p class="text-lg font-medium text-sky-900">
                                *@lang('digital-twin.newDigitalTwin.dateOfBirth'):
                            </p>
                            <x-input-label for="dateOfBirth" class="text-md block font-medium text-sky-700 pb-2"
                                :value="__('digital-twin.newDigitalTwin.value.dateOfBirth')" />
                            <input type="date" id="dateOfBirth" name="dateOfBirth"
                                class="border border-sky-700 focus:border-sky-800 focus:ring-sky-800 rounded-md cursor-pointer"
                                required>
                            <x-input-error :messages="$errors->get('dateOfBirth')" class="mt-2" />
                        </div>
                        <div class="w-1/2">
                            <p class="text-lg font-medium text-sky-900">
                                *@lang('digital-twin.newDigitalTwin.gender'):
                            </p>
                            <x-input-label for="gender" class="text-md block font-medium text-sky-700 pb-2"
                                :value="__('digital-twin.newDigitalTwin.value.gender')" />
                            <select id="gender" name="gender"
                                class="mt-1 block border-sky-800 focus:border-sky-900 focus:ring-sky-800 rounded-md shadow-sm w-full"
                                required autofocus autocomplete="gender">
                                <option value="Male">@lang('auth.register.male')</option>
                                <option value="Female">@lang('auth.register.female')</option>
                                <option value="Other">@lang('auth.register.other')</option>
                            </select>
                            <x-input-error :messages="$errors->get('gender')" class="mt-2" />
                        </div>
                    </div>
                    <div>
                        <p class="text-lg font-medium text-sky-900">
                            *@lang('digital-twin.newDigitalTwin.companyRole'):
                        </p>
                        <x-input-label for="companyRole" class="text-md block font-medium text-sky-700 pb-2"
                            :value="__('digital-twin.newDigitalTwin.value.companyRole')" />
                        <input type="text" id="companyRole" name="companyRole"
                            class="border border-sky-700 focus:border-sky-800 focus:ring-sky-800 rounded-md w-full sm:w-2/3"
                            required>
                        <x-input-error :messages="$errors->get('company_role')" class="mt-2" />
                    </div>
                    <div>
                        <p class="text-lg font-medium text-sky-900">
                            *@lang('digital-twin.newDigitalTwin.prompt'):
                        </p>
                        <x-input-label for="title" class="text-md block font-medium text-sky-700 pb-2"
                            :value="__('digital-twin.newDigitalTwin.value.prompt')" />

                        <div class="flex flex-col gap-2">
                            <select id="prompts" name="prompts"
                                class="border border-sky-700 focus:border-sky-800 focus:ring-sky-800 rounded-md w-full">
                                @foreach ($prompts as $prompt)
                                    <option value="{{ $prompt->value }}">{{ $prompt->title }}</option>
                                @endforeach
                            </select>

                            <textarea name="prompt" id="prompt" placeholder="" rows="15"
                                class="border border-sky-700 focus:border-sky-800 focus:ring-sky-800 rounded-md w-full"></textarea>
                            <x-input-error :messages="$errors->get('prompt')" class="mt-2" />
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
                        <x-primary-button class="ml-auto" id="continueDigitalTwin"
                            type="submit">@lang('digital-twin.continue')</x-primary-button>
                    </div>
                </form>
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
        <p class="text-2xl font-semibold text-red-700 pb-8">@lang('digital-twin.newDigitalTwin.errorData')</p>
        <x-secondary-button x-on:click="$dispatch('close')">@lang('digital-twin.newDigitalTwin.close')</x-secondary-button>
    </div>
</x-modal>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('prompts').addEventListener('change', function() {
            const selected = this.options[this.selectedIndex]
            const promptContent = selected.value || ''
            document.getElementById('prompt').value = promptContent.trimStart()
        })

        // Trigger on first load if desired:
        document.getElementById('prompts').dispatchEvent(new Event('change'));
    });
</script>
