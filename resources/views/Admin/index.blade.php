<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin') }}
        </h2>
    </x-slot>
    <div class="bg-stockhive-grey-dark text-white shadow-sm rounded-lg mt-8 lg:w-[85%] w-full m-auto p-4">
        <form method="GET" action="{{ route('admin.sort') }}" class="m-auto text-right w-[90%]">
            <select name="sort" id="sort" class="text-white bg-stockhive-grey hover:shadow-bxs hover:border-accent transition-all hover:ring-accent p-2 rounded-lg w-[50%]">
                <option value="id" {{ request('sort') === 'id' ? 'selected' : '' }}>ID</option>
                <option value="first_name" {{ request('sort') === 'first_name' ? 'selected' : '' }}>First Name</option>
                <option value="last_name" {{ request('sort') === 'last_name' ? 'selected' : '' }}>Last Name</option>
            </select>
            <input type="hidden" name="page" value="{{ request('page', 1) }}"> <!-- Get page number, default to 1 if none set. -->
            <x-primary-button class="ml-4">Sort</x-primary-button>
        </form>
            @if($employees->isNotEmpty())
            <div class="flex justify-between items-center gap-8 my-4 border-grey bg-stockhive-grey rounded-lg p-4 border-2 m-auto w-[90%] text-right">
                <x-paginate :items="$employees"/>
            </div>
                <table class="border-separate border-2 m-auto my-4 lg:w-[90%] w-full text-center border-grey hover:border-accent transition-all hover:shadow-bxs border-spacing-2 md:border-spacing-8 bg-stockhive-grey rounded-lg">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Edit User</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($employees as $employee)
                            <form action="{{ route('admin.selectedUser') }}" method="POST">
                                @csrf
                                <tr>
                                    <input type="hidden" name="id" value="{{ $employee->id }}">
                                    <td>{{ $employee->id }}</td>
                                    <td>{{ $employee->first_name }}</td>
                                    <td>{{ $employee->last_name }}</td>
                                    <td><x-primary-button>Edit User</x-primary-button></td>
                                </tr>
                             </form>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-white">No other users in system</p>
            @endif
    </div>
</x-app-layout>
