<x-guest-layout>
    <h2 class="text-2xl font-bold mb-4">Admin Login</h2>

    @if($errors->any())
        <div class="mb-4 text-red-600">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('admin.authenticate') }}">
    @csrf
    <input type="password" name="password" placeholder="Admin Password">
    <button type="submit">Login</button>
    @error('password')
        <div>{{ $message }}</div>
    @enderror
</form>

</x-guest-layout>
