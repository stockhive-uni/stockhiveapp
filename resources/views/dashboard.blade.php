<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
         <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-stockhive-grey-dark text-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <p class="text-xl"><span class="font-bold">Name:</span> {{Auth::user()->first_name }} {{Auth::user()->last_name}}</p>
                    <p class="text-xl"><span class="font-bold">Email:</span> {{ Auth::user()->email }}</p>
                    <p class="text-xl"><span class="font-bold">Employee ID:</span> {{ Auth::user()->id}}</p>
                </div> 
                
            </div>
        </div>
    </div>
</x-app-layout>
