@extends('templates.base')

@section('title', 'Promokna ID')

<body class="h-screen flex flex-col items-center justify-center bg-white relative text-white px-4">
    <div class="max-w-md w-full bg-blue-100  bg-opacity-10 rounded-md shadow-lg text-black p-8 ">
        <h2 class="text-3xl font-bold text-center mb-6 relative z-10">Admin</h2>
        {{-- FORM LOGIN --}}
        <form id="loginForm" class="w-full max-w-sm relative z-10" action="{{ route('handle-login') }}" method="POST">
            @csrf
            {{-- Email --}}
            <div class="mb-4">
                <label class="block text-black font-semibold">Email</label>
                <input type="email" name="email" placeholder="youremail@gmail.com" value="{{ old('email') }}"
                    class="w-full p-2 border border-gray-300 rounded mt-1 focus:ring focus:ring-indigo-300 text-black">
            </div>

            {{-- Password --}}
            <div class="mb-4" x-data="{ show: false }">
                <label class="block text-black font-semibold">Password</label>
                <div class="relative">
                    <input :type="show ? 'text' : 'password'" name="password" placeholder="••••••••"
                        class="w-full p-2 border border-gray-300 rounded mt-1 focus:ring focus:ring-indigo-300 text-black pr-10">

                    {{-- Tombol icon mata --}}
                    <button type="button" @click="show = !show" class="absolute right-3 top-4 text-gray-500">
                        <template x-if="!show">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </template>
                        <template x-if="show">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.973 9.973 0 012.293-3.95m2.634-2.416A9.956 9.956 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.973 9.973 0 01-4.338 5.272M15 12a3 3 0 00-3-3" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />
                            </svg>
                        </template>
                    </button>
                </div>
            </div>

            {{-- Error Global --}}
            @if ($errors->has('email') && !$errors->has('password'))
                <p class="text-red-500 text-center mb-4">{{ $errors->first('email') }}</p>
            @endif
            <div class="flex items-center justify-between mb-4">
                <label class="flex items-center">
                    <input type="checkbox" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                    <span class="ml-2 text-sm text-gray-600">Remember me</span>
                </label>
                <a href="#" class="text-sm text-indigo-600 hover:text-indigo-500">Forgot password?</a>
            </div>
            <button class="w-full bg-indigo-600 text-white p-2 rounded hover:bg-indigo-700">Login</button>
        </form>
    </div>
</body>
