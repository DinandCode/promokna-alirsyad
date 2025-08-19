<!-- AOS CSS -->
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

@extends('templates.navbar')

@section('title', config('app.name'))


@section('navbar_content')

    @php
        $ocassionDate = \Carbon\Carbon::parse(\App\Models\Setting::get(\App\Models\Setting::KEY_EVENT_OCCASION_DATE));
        $day = $ocassionDate->format('d');
        $month = $ocassionDate->translatedFormat('F');
        $year = $ocassionDate->format('Y');
    @endphp

    <div x-data="{ openModal: false }">
        <div x-show="openModal" x-transition
            class="fixed top-0 inset-0 bg-black/70 flex flex-col items-center justify-center p-4 z-50">
            <!-- Judul -->
            <h2 class="text-2xl md:text-3xl font-bold text-white mb-6 text-center">
                Pilih Kategori Rute
            </h2>

            <!-- Grid Card Responsif -->
            <div class="flex gap-4 flex-col md:flex-row">
                <div class="space-y-4">
                    @foreach ($tickets as $item)
                        <a href="{{ route('peserta.login', ['ticket' => $item->id]) }}"
                            class="block text-center bg-blue-700 text-white py-2 rounded hover:bg-blue-800 transition">
                            {{ $item->name }}
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Tombol Close -->
            <button @click="openModal = false"
                class="absolute top-4 right-4 bg-gray-800 hover:bg-gray-600 text-white px-4 py-2 rounded-full shadow-lg">
                ✕
            </button>
        </div>

        <section class="relative h-[90vh] flex items-center justify-center bg-cover bg-center"
            style="background-image: url('{{ \App\Models\Setting::get(\App\Models\Setting::KEY_EVENT_BANNER_URL) }}');">

            <!-- Overlay gradasi -->
            <div class="absolute inset-0 bg-gradient-to-b from-black/70 via-black/40 to-black/80"></div>

            <div class="relative z-10 text-center text-white max-w-3xl px-6 animate-fadeIn">

                <!-- Judul -->
                <h1 class="text-4xl sm:text-6xl font-extrabold drop-shadow-lg mb-4">
                    {{ \App\Models\Setting::get(\App\Models\Setting::KEY_EVENT_NAME) }}
                </h1>

                <!-- Deskripsi -->
                <p class="text-lg sm:text-xl text-gray-200 mb-8 leading-relaxed">
                    {{ \App\Models\Setting::get(\App\Models\Setting::KEY_EVENT_DESCRIPTION) }}
                </p>

                <!-- Card Tanggal -->
                <div
                    class="inline-block bg-red-700/90 backdrop-blur-md rounded-xl px-8 py-6 shadow-xl transform hover:scale-105 transition duration-300 mb-8">
                    <div class="text-5xl font-bold">{{ $day }}</div>
                    <div class="uppercase tracking-widest">{{ $month }}</div>
                    <div class="text-xl font-medium">{{ $year }}</div>
                </div>

                <!-- Tombol -->
                <div class="flex justify-center gap-4">
                    <a href="#eventDescription"
                        class="px-6 py-3 rounded-full bg-gradient-to-r from-red-600 to-red-500 text-white font-semibold shadow-lg hover:scale-105 transition duration-300">
                        Info Event
                    </a>

                    <button @click="openModal = true"
                        class="px-6 py-3 rounded-full bg-gradient-to-r from-red-600 to-red-700 text-white font-semibold shadow-lg hover:scale-105 transition duration-300">
                        Daftar Event
                    </button>
                </div>
            </div>
        </section>

        <!-- Animasi Tailwind -->
        <style>
            @keyframes fadeIn {
                0% {
                    opacity: 0;
                    transform: translateY(20px);
                }

                100% {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .animate-fadeIn {
                animation: fadeIn 1.2s ease-out forwards;
            }
        </style>




        <section class="bg-black text-white text-center flex flex-col md:flex-row w-full">
            <div
                class="flex-1 flex items-center justify-center transition duration-300 hover:bg-red-600  hover:border-t-4 hover:border-white cursor-pointer px-4 py-6">
                <div>
                    <i class="fa-solid fa-calendar-alt text-white text-2xl"></i>
                    <h3 class="font-bold text-lg mt-2">Info Event</h3>
                    <p class="text-sm">{{ $day . ' ' . $month . ' ' . $year }}</p>
                </div>
            </div>
            <div
                class="flex-1 flex items-center justify-center transition duration-300 hover:bg-red-600  hover:border-t-4 hover:border-white cursor-pointer px-4 py-6">
                <div>
                    <i class="fa fa-ticket-alt text-white text-2xl"></i>
                    <h3 class="font-bold text-lg mt-2">Tiket</h3>
                    <p class="text-sm">Mulai dari 0 rupiah</p>
                </div>
            </div>
            <div
                class="flex-1 flex items-center justify-center transition duration-300 hover:bg-red-600  hover:border-t-4 hover:border-white cursor-pointer px-4 py-6">
                <div>
                    <i class="fas fa-map-marker-alt text-white text-2xl"></i>
                    <h3 class="font-bold text-lg mt-2">Titik Kumpul</h3>
                    <p class="text-sm">{{ \App\Models\Setting::get(\App\Models\Setting::KEY_EVENT_OCCASION_PLACE) }}</p>
                </div>
            </div>
        </section>
        <section class="p-8 grid md:grid-cols-2 gap-6 items-start overflow-x-hidden" id="eventDescription">
            <!-- Kiri: Gambar dan Info -->
            <div class="border-t-2 border-red-600 pt-4" data-aos="fade-right">
                <img src="{{ \App\Models\Setting::get(\App\Models\Setting::KEY_WEBSITE_LOGO_URL) }}" alt="Band"
                    class="rounded mb-4 w-1/2 object-fit">

                <h2 class="text-xl font-bold mb-2">Deskripsi Event</h2>
                <p class="text-sm text-gray-700">
                    {!! \App\Models\Setting::get(\App\Models\Setting::KEY_EVENT_DESCRIPTION) !!}
                </p>
            </div>

            <!-- Kanan: 2 Gambar + Deskripsi -->
            <div class="border-t border-black pt-4" data-aos="fade-left">
                <div class="flex flex-col gap-4 mb-4">
                    <h2 class="text-xl font-bold mb-2">Benefit Peserta</h2>
                    <img src="{{ \App\Models\Setting::get(\App\Models\Setting::KEY_EVENT_BENEFITS_URL) }}" alt="Img 1"
                        class="w-1/2 rounded">
                </div>
            </div>
        </section>

        <!-- Testimonial / Rute Opsional-->
        <section class="bg-cover bg-black bg-center py-16 text-white text-center" style="background-image : url('/asset/')">
            <div class=" bg-opacity-60 py-10 px-4">
                <div class="max-w-4xl mx-auto" data-aos="fade-up">
                    <img src="{{ \App\Models\Setting::get(\App\Models\Setting::KEY_EVENT_MAPS_URL) }}" alt="Gambar Rute"
                        class="mx-auto w-1/2 max-w-2xl rounded shadow-lg">
                </div>
                <!-- <div class="text-xl italic max-w-2xl mx-auto">"Amazing, these guys absolutely rocked the house down solid for 3 hours straight..."</div>
                                                          <div class="mt-4 font-bold">- Jenny Compers ★★★★★</div> -->
            </div>
        </section>

        <section class="py-12 px-4 max-w-5xl mx-auto">
            <h2 class="text-3xl font-bold text-center mb-4">Rundown Acara</h2>
            {{-- <p class="text-center text-gray-600 mb-12">
            Playing for one night only at a town near you. Come and enjoy a mind-bending experience...
        </p> --}}

            <div class="relative">
                <div class="absolute left-1/2 transform -translate-x-1/2 h-full w-1 bg-black"></div>
                @foreach ($rundowns as $item)
                    @php
                        $isEven = $loop->index % 2 == 0;
                    @endphp
                    @if (!$isEven)
                        <div class="mb-10 flex justify-end items-center relative" data-aos="fade-left">
                            <div class="w-1/2"></div>
                            <div class="absolute left-1/2 transform -translate-x-1/2 w-4 h-4 bg-red-500 rounded-full z-10">
                            </div>
                            <div class="w-1/2 pl-6">
                                <div class="bg-white shadow-md rounded-lg p-4 max-w-sm text-left">
                                    <h3 class="text-red-500 font-bold text-lg">
                                        {{ \Carbon\Carbon::parse($item->occasion_date)->translatedFormat('M d, Y (H:i)') }}
                                    </h3>
                                    <p class="font-bold">{{ $item->title }}</p>
                                    <p class="text-sm text-gray-600 mt-1">{{ $item->description }}</p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="mb-10 flex justify-start items-center relative" data-aos="fade-right">
                            <div class="w-1/2 pr-6 flex justify-end">
                                <div class="bg-white shadow-md rounded-lg p-4 max-w-sm text-right">
                                    <h3 class="text-red-500 font-bold text-lg">
                                        {{ \Carbon\Carbon::parse($item->occasion_date)->translatedFormat('M d, Y (H:i)') }}
                                    </h3>
                                    <p class="font-bold">{{ $item->title }}</p>
                                    <p class="text-sm text-gray-600 mt-1">{{ $item->description }}</p>
                                </div>
                            </div>
                            <div class="absolute left-1/2 transform -translate-x-1/2 w-4 h-4 bg-red-500 rounded-full z-10">
                            </div>
                            <div class="w-1/2"></div>
                        </div>
                    @endif
                @endforeach
            </div>

        </section>
        <!-- Section Sponsor -->
        <section class="py-6 mb-8">

            <div class="overflow-hidden relative w-full">
                <div class="flex w-max animate-marquee">
                    @php
                        $sponsors = [
                            'polygon.jpg',
                            'Langgeng jaya.png',
                            'gerai.jpeg',
                            'polygon.jpg',
                            'Langgeng jaya.png',
                            'gerai.jpeg',
                            'polygon.jpg',
                            'Langgeng jaya.png',
                            'gerai.jpeg',
                        ];
                    @endphp

                    {{-- Loop gambar dua kali untuk efek tanpa putus --}}
                    @foreach (array_merge($sponsors, $sponsors) as $sponsor)
                        <img src="{{ asset('asset/' . $sponsor) }}" alt="{{ $sponsor }}"
                            class="h-16 sm:h-20 lg:h-24 object-contain mx-6">
                    @endforeach
                </div>
            </div>
        </section>

        <style>
            @keyframes marquee {
                0% {
                    transform: translateX(0);
                }

                100% {
                    transform: translateX(-50%);
                }
            }

            .animate-marquee {
                display: flex;
                animation: marquee 25s linear infinite;
            }
        </style>
    </div>

    <!-- AOS JS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800, // durasi animasi
            once: true, // animasi hanya sekali
        });
    </script>
@endsection
