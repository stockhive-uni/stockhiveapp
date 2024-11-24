<x-app-layout>
    <form action="{{ route('admin.updateSettings') }}" method="POST">
        @csrf
        <h2>ID: {{ ($user['id']) }}</h2>
        <h2>First Name:</h2>
        <input type="text" name="first_name" value="{{ ($user['first_name']) }}">
        <h2>Last Name:</h2>
        <input type="text" name="last_name" value="{{ ($user['last_name']) }}">
        <input type="hidden" name="id" value ="{{ $user['id'] }}">
        <x-primary-button>Save Settings</x-primary-button>
    </form>
    <div>
        <h2>Active Permissions:</h2>
        @php global $permissions; @endphp
        @include('components.get-permissions', ['id' => $user['id']])

        @foreach($permissions->groupBy('categoryName') as $category => $categoryPermissions)
            <div>
                <h3>{{ $category }}</h3>
                <ul>
                    @foreach($categoryPermissions as $permission)
                        <li>{{ $permission->id }} - {{ $permission->permissionName }}</li>
                    @endforeach
                </ul>
            </div>
        @endforeach
    </div>
    <div>
        <h2>Roles</h2>
        <form action="{{ route('admin.updatePermissions') }}" method="POST">
            @csrf
            <input type="hidden" name="id" value="{{ $user['id'] }}">
            <table>
                <tr>
                    <th>Role</th>
                    <th>Permissions</th>
                    <th>Active</th>
                </tr>
                @php
                    $roles = DB::select("SELECT *, CASE WHEN name IN (SELECT name FROM role, user_role WHERE role.id = user_role.role_id AND user_role.user_id = ?) THEN TRUE ELSE FALSE END AS isActive FROM role", [$user['id']])
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
                    @php
                        $checked = "";
                        if ($role->isActive == 1) {
                            $checked = "checked";
                        }
                    @endphp
                    <td>
                        <input type="checkbox" name="roles[]" value="{{ $role->id }}" {{$checked}}>
                    </td>
                </tr>
                @endforeach
            </table>
            <x-primary-button>Update Permissions</x-primary-button>
        </form>
    </div>
</x-app-layout>
