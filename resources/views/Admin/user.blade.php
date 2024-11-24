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
    </div>
</x-app-layout>
