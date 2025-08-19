@extends('templates.adminav')
@section('title', 'Tambah Tiket')
@section('navbar_admin')
    <div class="w-full">
        <div class="sm:flex sm:items-center sm:justify-between mb-4">
            <h1 class="text-xl font-bold text-gray-800">Create New Ticket</h1>
            <a href="{{ route('admin.tickets.index') }}"
                class="hidden sm:inline-block px-4 py-2 bg-gray-500 text-white rounded-md shadow-sm text-sm hover:bg-gray-600">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to List
            </a>
        </div>

        <div class="bg-white shadow rounded-lg mb-4">
            <div class="p-6">

                {{-- Error Alert --}}
                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                        <strong class="font-bold">Oops!</strong>
                        <ul class="mt-2 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.tickets.store') }}" method="POST">
                    @csrf

                    {{-- Name --}}
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700">Ticket Name</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}"
                            class="mt-1 block w-full p-2 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            required>
                    </div>

                    {{-- Price --}}
                    {{-- <div class="mb-4">
                        <label for="price" class="block text-sm font-medium text-gray-700">Price (Rp)</label>
                        <input type="number" id="price" name="price" value="{{ old('price') }}"
                            class="mt-1 block w-full p-2 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            placeholder="Kosongkan untuk tiket gratis"
                            min="1">
                    </div> --}}

                    {{-- Quota --}}
                    <div class="mb-4">
                        <label for="quota" class="block text-sm font-medium text-gray-700">Quota (optional)</label>
                        <input type="number" id="quota" name="quota" value="{{ old('quota') }}"
                            class="mt-1 block w-full p-2 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            min="1" placeholder="Kosongkan untuk tiket tak terbatas">
                        <p class="text-sm text-gray-500 mt-1">Jika kosong maka kuota akan tak terbatas, jika isi pastikan lebih dari 0</p>
                    </div>

                    {{-- Actions --}}
                    <div class="flex justify-end">
                        <a href="{{ route('admin.tickets.index') }}"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md shadow-sm text-sm hover:bg-gray-300">
                            Batal
                        </a>
                        <button type="submit"
                            class="ml-3 px-4 py-2 bg-blue-500 text-white rounded-md shadow-sm text-sm hover:bg-blue-600">
                            Simpan Tiket
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection
