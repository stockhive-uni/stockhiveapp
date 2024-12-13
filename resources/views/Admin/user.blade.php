<x-app-layout>
    @php global $permissions; @endphp
    @include('components.get-permissions', ['id' => Auth::User()->id])
    <div class="bg-stockhive-grey-dark text-white shadow-sm md:rounded-lg mt-8 lg:w-[85%] w-full m-auto p-4">
        <form action="{{ route('admin.updateSettings') }}" method="POST">
            @csrf
            <h1 class="text-xl">ID: {{ ($user['id']) }}</h1>
            <div class="flex flex-col md:flex-row md:items-center md:space-x-4 space-y-4 md:space-y-0">
                <div class="flex flex-col">
                    <h2 class="mb-2">First Name:</h2>
                    <input type="text" class="w-full bg-stockhive-grey rounded-lg text-white border-2 hover:shadow-bxs transition-all hover:border-accent" name="first_name" value="{{ ($user['first_name']) }}">
                </div>
                <div class="flex flex-col">
                    <h2 class="mb-2">Last Name:</h2>
                    <input type="text" class="w-full bg-stockhive-grey rounded-lg text-white border-2 hover:shadow-bxs transition-all hover:border-accent" name="last_name" value="{{ ($user['last_name']) }}">
                </div>
                <div class="flex flex-col">
                    <h2 class="mb-2">Email:</h2>
                    <input type="text" class="w-full bg-stockhive-grey rounded-lg text-white border-2 hover:shadow-bxs transition-all hover:border-accent" name="email" value="{{ ($user['email']) }}">
                </div>
            </div>
            <div class="mt-4 flex flex-col md:flex-row md:items-center">
            <input type="hidden" name="id" value="{{ $user['id'] }}">
                <x-primary-button class="mt-4 md:mt-0">Save Settings</x-primary-button>
            </div>
        </form>
        @if (in_array("16", $permissions))
            <form action="{{ route('admin.toggleAccountActivation') }}" method="POST">
                @csrf
                <input type="hidden" name="id" value="{{ $user['id'] }}">
                <div class="flex flex-col md:flex-row md:items-center md:space-x-4 space-y-4 md:space-y-0">
                <x-primary-button>
                @php
                if ($user['password'] == null) {
                    echo "Activate Account";
                } else {
                    echo "Deactivate Account";
                }
                @endphp
                </x-primary-button>
                </div>
                @if ($user['password'] == null)
                <h2>Password</h2>
                <input type="password" name="password" class="bg-stockhive-grey rounded-lg text-white border-2 hover:shadow-bxs transition-all hover:border-accent">
                @endif
            </form>
        @endif
    </div>
    <div class="bg-stockhive-grey-dark text-white shadow-sm md:rounded-lg mt-8 lg:w-[85%] w-full m-auto p-4">
        <h2 class="text-2xl text-white text-center">Active Permissions:</h2>
        @php
            $userPermissions = DB::select("SELECT DISTINCT permission.id, permission.name AS permissionName, category.name AS categoryName FROM permission, role_permission, user_role, category WHERE permission.id = role_permission.permission_id AND role_permission.role_id = user_role.role_id AND permission.category_id = category.id AND user_role.user_id = " . $user['id']);
        @endphp

        @foreach(collect($userPermissions)->groupBy('categoryName') as $category => $categoryPermissions)
            <div class="mt-4">
                <h1 class="font-bold text-xl">{{ $category }}</h1>
                <ul class="flex items-center space-x-4">
                    @foreach($categoryPermissions as $permission)
                        <li class="text-sm text-gray-600 bg-stockhive-grey my-4 p-2 rounded-lg">{{ $permission->id }} - {{ $permission->permissionName }}</li>
                    @endforeach
                </ul>
            </div>
        @endforeach
    </div>
    <div class="bg-stockhive-grey-dark text-white shadow-sm md:rounded-lg mt-8 lg:w-[85%] w-full m-auto p-4">
        <h2 class="text-white text-center text-2xl">Edit permissions:</h2>
        @if ($user['id'] == Auth::user()->id)
        <h2 class="text-error">You cannot edit your own permissions</h2>
        @else
        <form action="{{ route('admin.updatePermissions') }}" method="POST">
            @csrf
            <input type="hidden" name="id" value="{{ $user['id'] }}">
            <table class="border-separate border-2 m-auto my-4 lg:w-[90%] w-full text-center border-grey hover:border-accent transition-all hover:shadow-bxs border-spacing-2 md:border-spacing-8 bg-stockhive-grey rounded-lg">
    <thead>
        <tr class="text-left border-b-2 border-grey">
            <th class="py-2 px-4 text-center">Role</th>
            <th class="py-2 px-4 text-center">Permissions</th>
            <th class="py-2 px-4 text-center">Active</th>
        </tr>
    </thead>
    <tbody>
        @php
            $roles = DB::select("SELECT *, CASE WHEN name IN (SELECT name FROM role, user_role WHERE role.id = user_role.role_id AND user_role.user_id = ?) THEN TRUE ELSE FALSE END AS isActive FROM role", [$user['id']])
        @endphp
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
            @php
                $checked = $role->isActive == 1 ? "checked" : "";
            @endphp
            <td class="py-2 px-4">
                <input type="checkbox" name="roles[]" value="{{ $role->id }}" {{$checked}} class="form-checkbox h-5 w-5 bg-stockhive-grey-dark text-accent rounded border-2">
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
            <div class="lg:w-[90%] m-auto">
                <x-primary-button>Update Permissions</x-primary-button>
            </div>
        </form>
        @endif
    </div>
    <br />
</x-app-layout>
