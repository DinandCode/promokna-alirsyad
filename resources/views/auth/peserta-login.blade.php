@extends('templates.navbar')

@section('title', 'Login Peserta')

@section('navbar_content')
<div class="min-h-screen flex items-center justify-center bg-gray-100 px-4">
    <div class="bg-white shadow-lg rounded-lg w-full max-w-md p-8">
        <h2 class="text-2xl font-bold text-center mb-6">
            Login Peserta - Tiket {{ $ticket->name }}
        </h2>

        @if ($errors->any())
            <div class="mb-4 text-red-600 text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('peserta.login.submit', $ticket->id) }}">
            @csrf

            <div class="mb-4">
                <label for="nama" class="block font-medium">NIP/NIS</label>
                <input type="text" id="nama" name="nama" required
                    value="{{ old('nama') }}"
                    class="w-full mt-1 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-red-500">
            </div>

            <div class="mb-4">
                <label for="nis" class="block font-medium">Password</label>
                <input type="password" id="nis" name="nis" required
                    class="w-full mt-1 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-red-500">
            </div>

            <button type="submit"
                class="w-full bg-green-600 hover:bg-green-800 text-white font-semibold py-2 px-4 rounded-lg transition">
                Login
            </button>
        </form>
    </div>
</div>
@endsection
