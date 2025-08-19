@extends('templates.navbar')

@section('title', 'Promokna.id')


@section('navbar_content')

    <header id="beranda" class="pt-24 px-5 md:px-20">
        <div class="bg-white rounded-2xl shadow-lg p-10 flex flex-col items-center md:flex-row md:space-x-10">
            <!-- Logo Section -->
            <div class="flex-shrink-0">
                <img src="{{ \App\Models\Setting::get(\App\Models\Setting::KEY_WEBSITE_LOGO_URL) }}" alt="Techcomfest Logo"
                    class="w-60 h-auto"> <!-- Memperbesar gambar -->
            </div>
            <!-- Description Section -->
            <div class="text-center md:text-left">
                <h1 class="text-4xl font-bold text-gray-800 mb-4">Tentang
                    {{ \App\Models\Setting::get(\App\Models\Setting::KEY_EVENT_NAME) }}</h1>
                <h2 class="text-xl font-semibold text-gray-700 mb-6">
                    {{ \App\Models\Setting::get(\App\Models\Setting::KEY_EVENT_NAME) }} itu apa sih?</h2>
                <p class="text-gray-600 text-lg leading-relaxed">
                    {!! \App\Models\Setting::get(\App\Models\Setting::KEY_EVENT_ABOUT) !!}</p>
            </div>
        </div>
    </header>
    <section id="faq" class="pt-20 px-5 md:px-20">
        <div class="bg-white rounded-2xl shadow-lg p-10">
            <h2 class="text-3xl font-bold text-gray-800 mb-6">Frequently Asked Questions</h2>
            <p class="text-gray-600 mb-8">Yang sering ditanyakan terkait
                {{ \App\Models\Setting::get(\App\Models\Setting::KEY_EVENT_NAME) }}.</p>

            @foreach ($faqs as $item)
                <div x-data="{ open: false }" class="mb-4">
                    <button @click="open = !open"
                        class="w-full text-left flex justify-between items-center bg-gray-100 px-4 py-2 rounded-md hover:bg-gray-200">
                        <span class="text-lg font-semibold text-gray-800">{{ $item->question }}</span>
                        <svg :class="{ 'rotate-180': open }" xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5 transform transition-transform" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                        </svg>
                    </button>
                    <div x-show="open" class="mt-2 text-gray-700 text-sm px-4" x-cloak>{{ $item->answer }}</div>
                </div>
            @endforeach
        </div>
    </section>

@endsection
