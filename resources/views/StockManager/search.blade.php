<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Search') }}
        </h2>
    </x-slot>
    <form action='{{}}' method='POST'>
        <input type='text'></input>
    </form>
</x-app-layout>