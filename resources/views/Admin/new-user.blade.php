<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('New User') }}
        </h2>
    </x-slot>
    <form action="{{ route('admin.addNewUser') }}" method="POST">
        @csrf
        <h2>First Name:</h2>
        <input type="text" name="first_name">
        <h2>Last Name:</h2>
        <input type="text" name="last_name">
        <h2>Email:</h2>
        <input type="text" name="email">
        <h2>Password:</h2>
        <input type="password" name="password">
        <h2>Roles</h2>
            @csrf
            <table>
                <tr>
                    <th>Role</th>
                    <th>Permissions</th>
                    <th>Active</th>
                </tr>
                @php
                    $roles = DB::select("SELECT * FROM role")
                @endphp
                @foreach ($roles as $role)
                <tr>
                    @php
                    $permissions = DB::select("SELECT permission.name FROM role_permission, permission WHERE permission.id = role_permission.permission_id AND role_id = " . $role->id)
                    @endphp
                    <td>{{ $role->name }}</td>
                    <td>
                        @foreach ($permissions as $permission)
                            <p>{{ $permission->name }}</p>
                        @endforeach
                    </td>
                    <td>
                        <input type="checkbox" name="roles[]" value="{{ $role->id }}">
                    </td>
                </tr>
                @endforeach
            </table>
        <x-primary-button>Create User</x-primary-button>
    </form>
</x-app-layout>