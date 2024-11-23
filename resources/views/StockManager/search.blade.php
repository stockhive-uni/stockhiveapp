<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Search') }}
        </h2>
    </x-slot>
    <form action='{{}}' method='GET'>
        <input type='text' name='search' :value="old('search')"></input>
    </form>
</x-app-layout>