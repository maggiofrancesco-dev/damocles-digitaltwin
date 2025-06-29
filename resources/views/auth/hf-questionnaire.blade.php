<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
<x-guest-layout>

    <p class="text-xl font-extrabold text-center">Register - Human factors survey</p>

    <div x-data="{ page: 1 }">
        <form id="registrationForm" method="POST" action="{{ route('hf.questionnaire') }}">
            @csrf

            {{-- Page 1 --}}
            <div x-show="page === 1">
                <div class="flex flex-col">
                @foreach (array_slice($data['questions'], 0, 7) as $question)
                    <div class="mt-4">
                        <label class="block font-bold text-base text-sky-700">
                            {{ $question['id'] }}. {{ $question['text'] }}
                        </label>
                        <div class="grid grid-cols-3 gap-4">
                            <x-radio-input name="{{ 'question' . $question['id'] }}" value="0" label="{{ $question['answers'][0]['text'] }}" />
                            <x-radio-input name="{{ 'question' . $question['id'] }}" value="1" label="{{ $question['answers'][1]['text'] }}" />
                            <x-radio-input name="{{ 'question' . $question['id'] }}" value="2" label="{{ $question['answers'][2]['text'] }}" />
                        </div>
                    </div>
                @endforeach
                </div>

                <div class="flex items-center justify-end mt-4">
                    <x-secondary-button class="ms-4" @click="page = 2">
                        Next
                    </x-secondary-button>
                </div>
            </div>


            {{-- Page 2 --}}
            <div x-show="page === 2">
                <div class="flex flex-col">
                @foreach (array_slice($data['questions'], 7, 8) as $question)
                    <div class="mt-4">
                        <label class="block font-bold text-base text-sky-700">
                            {{ $question['id'] }}. {{ $question['text'] }}
                        </label>
                        <div class="grid grid-cols-3 gap-4">
                            <x-radio-input name="{{ 'question' . $question['id'] }}" value="0" label="{{ $question['answers'][0]['text'] }}" />
                            <x-radio-input name="{{ 'question' . $question['id'] }}" value="1" label="{{ $question['answers'][1]['text'] }}" />
                            <x-radio-input name="{{ 'question' . $question['id'] }}" value="2" label="{{ $question['answers'][2]['text'] }}" />
                        </div>
                    </div>
                @endforeach
                </div>

                <div class="flex items-center justify-end mt-4">
                    <x-secondary-button class="ms-4" @click="page = 1">
                        Back
                    </x-secondary-button>
                    <x-primary-button class="ms-4">
                        Complete
                    </x-primary-button>
                </div>
            </div>

        </form>
    </div>

    <!-- Loading screen -->
    <div id="loadingOverlay"
        class="fixed top-0 left-0 w-full h-full bg-gray-900 bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="loader ease-linear rounded-full border-8 border-t-8 h-32 w-32"></div>
    </div>

</x-guest-layout>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<script>
    document.addEventListener('DOMContentLoaded', (event) => {
        const togglePassword = document.querySelector('#togglePassword');
        const togglePasswordConfirmation = document.querySelector('#togglePasswordConfirmation');
        const password = document.querySelector('#password');
        const passwordConfirmation = document.querySelector('#password_confirmation');

    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('registrationForm').addEventListener('submit', function(event) {
            document.getElementById('loadingOverlay').classList.add('flex');
            document.getElementById('loadingOverlay').classList.remove('hidden');
        });
    });
});
</script>


