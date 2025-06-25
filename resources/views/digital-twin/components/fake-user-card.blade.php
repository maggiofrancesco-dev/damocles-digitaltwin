<?php
$class = $class ?? '';
$create = $create ?? false;
$selected = ($selected ?? false) && !$create;
$fullName = $fullName ?? '';
?>

@if ($create)
    <div x-data="" @click="$dispatch('open-modal', 'add-fake-user')"
        class='cursor-pointer flex flex-col bg-sky-100/75 rounded-md w-32 h-36 border-0 hover:border-2 hover:border-sky-800 transition-border duration-75 ease-in {{ $class }}'>
        <div class="h-full flex flex-col items-center justify-center">
            <span class="material-symbols-outlined text-5xl align-middle">add</span>
            <span class="font-semibold mt-2">@lang('digital-twin.fakeUserCard.add')</span>
        </div>
    </div>
@else
    <div
        class='flex
        flex-col rounded-md w-32 h-36 bg-red-500 border-2 {{ $class ?? '' }}
        {{ $selected ? 'border-sky-800' : 'border-none' }}'>
        <div>
            <span>{{ $fullName }}</span>
        </div>
    </div>
@endif
