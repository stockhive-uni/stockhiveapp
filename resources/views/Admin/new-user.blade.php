<x-app-layout>
    <x-slot name="header">
        <h1 class="font-semibold text-3xl text-white text-center leading-tight">
            {{ __('New User') }}
        </h1>
    </x-slot>
    <div class="bg-stockhive-grey-dark text-white shadow-sm rounded-lg mt-8 lg:w-[85%] w-full m-auto p-4">
    @if (isset($error))
        <p class="text-error">{{ $error }}</p>
    @endif
    <h1 class="text-2xl font-bold text-center pb-4">Info:</h1>
    <form action="{{ route('admin.addNewUser') }}" method="POST">
        @csrf
        <div class="lg:flex lg:items-center lg:space-x-2">
            <div>
                <h2>First Name:</h2>
                <input type="text" class="bg-stockhive-grey rounded-lg text-white border-2 hover:shadow-bxs transition-all hover:border-accent" name="first_name">
            </div>
            <div>
                <h2>Last Name:</h2>
                <input type="text" class="bg-stockhive-grey rounded-lg text-white border-2 hover:shadow-bxs transition-all hover:border-accent" name="last_name">
            </div>
            <div>
                <h2>Email:</h2>
                <input type="text" class="bg-stockhive-grey rounded-lg text-white border-2 hover:shadow-bxs transition-all hover:border-accent" name="email">
            </div>
            <div>
                <h2>Password:</h2>
                <input type="password" class="bg-stockhive-grey rounded-lg text-white border-2 hover:shadow-bxs transition-all hover:border-accent"name="password">
            </div>
        </div>
        <h1 class="text-2xl font-bold text-center py-8">Roles & Permissions:</h1>
            @csrf
            <table class="border-separate border-2 m-auto my-4 lg:w-[90%] w-full text-center border-grey hover:border-accent transition-all hover:shadow-bxs border-spacing-2 md:border-spacing-8 bg-stockhive-grey rounded-lg">
                <thead>
                    <tr class="text-left border-b-2 border-grey">
                        <th class="py-2 px-4 text-center">Role</th>
                        <th class="py-2 px-4 text-center">Permissions</th>
                        <th class="py-2 px-4 text-center">Active</th>
                    </tr>
                </thead>
                @php
                    $roles = DB::select("SELECT * FROM role")
                @endphp
                <tbody>
                    @foreach ($roles as $role)
                    <tr class="hover:bg-stockhive-grey-light transition-all">
                        @php
                        $permissions = DB::select("SELECT permission.name FROM role_permission, permission WHERE permission.id = role_permission.permission_id AND role_id = " . $role->id)
                        @endphp
                        <td class="py-2 px-4">{{ $role->name }}</td>
                        <td class="py-2 px-4">
                            @foreach ($permissions as $permission)
                                <p class="text-sm text-gray-600 bg-stockhive-grey-dark my-4 p-2 rounded-lg">{{ $permission->name }}</p>
                            @endforeach
                        </td>
                        <td>
                            <input type="checkbox" name="roles[]"  class="form-checkbox h-5 w-5 bg-stockhive-grey-dark text-accent rounded border-2" value="{{ $role->id }}">
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        <x-primary-button>Create User</x-primary-button>
    </form>
</div>
</x-app-layout>