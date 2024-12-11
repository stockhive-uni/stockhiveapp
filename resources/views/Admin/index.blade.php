<x-app-layout>
    @php global $permissions; @endphp
    @include('components.get-permissions', ['id' => Auth::User()->id])
    <x-slot name="header">
        <h1 class="font-semibold text-3xl text-center py-4 text-gray-800 leading-tight">
            {{ __('Admin') }}
        </h1>
    </x-slot>
    @if (in_array("14", $permissions))
        <div class="lg:p-8 md:p-4 p-2 my-4 bg-stockhive-grey-dark lg:rounded-lg w-full lg:w-[85%] m-auto text-white">
            <h2 class="text-2xl text-center text-white">Actions:</h2>
            <div class="flex justify-between items-center gap-8 my-4 border-grey bg-stockhive-grey rounded-lg p-4 border-2 m-auto w-[90%] text-right">
                <form method="GET" action="{{ route('admin.createNewUser') }}">
                    <x-primary-button>Create New User</x-primary-button>
                </form>
            </div>
        </div>
    @endif
    @if($employees->isNotEmpty())
    <div class="lg:p-8 md:p-4 p-2 my-4 bg-stockhive-grey-dark lg:rounded-lg w-full lg:w-[85%] m-auto text-white">
        <table class="border-separate border-2 m-auto my-4 lg:w-[90%] w-full text-center border-grey hover:border-accent transition-all hover:shadow-bxs border-spacing-1 md:border-spacing-8 bg-stockhive-grey rounded-lg">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    @if (in_array("15", $permissions))
                        <th>Edit User</th>
                    @endif
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
                        @if (in_array("15", $permissions))
                            <td><x-primary-button>Edit User</x-primary-button></td>
                        @endif
                    </tr>
                </form>
                @endforeach
            </tbody>
        </table>
    </div>
        @else
            <p class="text-white">No other users in system</p>
        @endif
    <div class="p-8 my-4 bg-stockhive-grey-dark lg:rounded-lg w-full lg:w-[85%] m-auto">
        <x-paginate :items="$employees"/>
    </div>
    <br />
</x-app-layout>
