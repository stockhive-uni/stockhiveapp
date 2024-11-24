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
</x-app-layout>
