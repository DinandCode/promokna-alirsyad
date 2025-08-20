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
            Pilih Kategori 
        </h2>

        <!-- Grid Card Responsif -->
        <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-2 gap-6 w-full max-w-4xl">
            @foreach ($tickets as $item)
                <a href="{{ route('peserta.login', ['ticket' => $item->id]) }}"
                    class="bg-gradient-to-br from-green-600 to-green-700 text-white rounded-2xl shadow-lg p-6 flex flex-col items-center justify-center transform hover:scale-105 hover:shadow-2xl transition duration-300">
                    
                    <!-- Ikon atau Gambar Opsional -->
                    <div class="w-16 h-16 mb-4 flex items-center justify-center bg-white/20 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>

                    <!-- Nama Kategori -->
                    <h3 class="text-lg md:text-xl font-bold">{{ $item->name }}</h3>
                    <p class="text-sm opacity-80">Kategori</p>
                </a>
            @endforeach
        </div>
  




     

            <!-- Tombol Close -->
            <button @click="openModal = false"
                class="absolute top-4 right-4 bg-gray-800 hover:bg-gray-600 text-white px-4 py-2 rounded-full shadow-lg">
                ✕
            </button>
        </div>

     <section class="relative bg-gradient-to-r from-[#0f5132] via-[#198754] to-[#145a32] text-white py-16">
    <div class="container mx-auto flex flex-col-reverse md:flex-row items-center justify-between px-6 md:px-12 gap-10">
        
        <!-- Kiri: Text -->
        <div class="w-full md:w-1/2 space-y-6 text-center md:text-left">
            
           <!-- Judul -->
<h1 data-aos="fade-left" class="text-3xl sm:text-5xl lg:text-6xl font-extrabold leading-tight text-green-800 drop-shadow-lg stroke-text">
    {{ \App\Models\Setting::get(\App\Models\Setting::KEY_EVENT_NAME) }}
</h1>

<style>
.stroke-text {
  -webkit-text-stroke: 10px white; /* stroke putih */
  paint-order: stroke fill;
}
</style>


            <!-- Deskripsi -->
            <p data-aos="fade-left" class="text-base sm:text-lg lg:text-xl text-gray-100 leading-relaxed max-w-lg mx-auto md:mx-0">
                {{ \App\Models\Setting::get(\App\Models\Setting::KEY_EVENT_DESCRIPTION) }}
            </p>

            <!-- Tanggal -->
            <div class="flex justify-center md:justify-start">
                <div class="bg-white/10 backdrop-blur-md px-6 py-4 rounded-2xl shadow-lg animate-fadeIn">
                    <span class="block text-2xl sm:text-3xl lg:text-4xl font-extrabold tracking-widest text-white">
                        {{ str_pad($day, 2, '0', STR_PAD_LEFT) }}
                        <span class="">/</span>
                        {{ str_pad($month, 2, '0', STR_PAD_LEFT) }}
                        <span class="">/</span>
                        {{ $year }}
                    </span>
                </div>
            </div>

            <!-- Tombol -->
            <div class="flex flex-col sm:flex-row justify-center md:justify-start gap-4 pt-4">
                <a  data-aos="fade-left" href="#eventDescription"
                    class="px-6 py-3 rounded-full bg-white text-green-800 font-bold shadow-md hover:bg-green-600 hover:text-white transition">
                    Info Event
                </a>
                <button @click="openModal = true"
                    class="px-6 py-3 rounded-full bg-white text-green-800 font-bold shadow-md hover:bg-green-600 hover:text-white transition" data-aos="fade-right">
                    Daftar Event
                </button>
            </div>
        </div>

        <!-- Kanan: Banner / Ilustrasi -->
        <div class="w-full md:w-1/2 flex justify-center">
            <img src="{{ \App\Models\Setting::get(\App\Models\Setting::KEY_EVENT_BANNER_URL) }}"
                alt="Banner Event"
                class="w-full max-w-xs sm:max-w-sm lg:max-w-md rounded-2xl shadow-2xl border-4 border-white/20">
        </div>
    </div>
</section>

<!-- Animasi sederhana -->
<style>
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}
.animate-fadeIn {
  animation: fadeIn 1s ease-in-out;
}
</style>


       <!-- <section class="relative h-[90vh] flex items-center justify-center bg-cover bg-center"
    style="background-image: url('{{ \App\Models\Setting::get(\App\Models\Setting::KEY_EVENT_BANNER_URL) }}');">

    <div class="absolute inset-0 bg-gradient-to-b from-[#0f5132]/90 via-[#198754]/70 to-[#145a32]/90"></div>

    <div class="relative z-10 text-center text-white max-w-3xl px-6 animate-fadeIn">

        <h1 class="text-4xl sm:text-6xl font-extrabold drop-shadow-lg mb-4">
            {{ \App\Models\Setting::get(\App\Models\Setting::KEY_EVENT_NAME) }}
        </h1>

        <p class="text-lg sm:text-xl text-gray-100 mb-8 leading-relaxed">
            {{ \App\Models\Setting::get(\App\Models\Setting::KEY_EVENT_DESCRIPTION) }}
        </p>

        <div
            class="inline-block bg-[#f08a16]/95 backdrop-blur-md rounded-xl px-8 py-6 shadow-xl transform hover:scale-105 transition duration-300 mb-8">
            <div class="text-5xl font-bold">{{ $day }}</div>
            <div class="uppercase tracking-widest">{{ $month }}</div>
            <div class="text-xl font-medium">{{ $year }}</div>
        </div>

        <div class="flex justify-center gap-4">
            <a href="#eventDescription"
                class="px-6 py-3 rounded-full bg-[#f08a16] text-white font-semibold shadow-lg hover:bg-[#e67e22] hover:scale-105 transition duration-300">
                Info Event
            </a>

            <button @click="openModal = true"
                class="px-6 py-3 rounded-full bg-[#f08a16] text-white font-semibold shadow-lg hover:bg-[#e67e22] hover:scale-105 transition duration-300">
                Daftar Event
            </button>
        </div>
    </div>
</section> -->

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



<section 
    class="bg-gradient-to-r from-green-600 via-green-700 to-green-800 
           text-white border-4 border-white  md:rounded-full rounded-2xl 
           mt-4 mx-4 w-auto shadow-lg overflow-hidden">

    <div class="grid grid-cols-1 md:grid-cols-3 divide-y md:divide-y-0 md:divide-x divide-white/10">

        <!-- Info Event -->
        <div class="flex items-center justify-center px-6 py-8 
                    hover:bg-white/10 transition duration-300 cursor-pointer group">
            <div class="text-center">
                <div class="w-16 h-16 mx-auto flex items-center justify-center rounded-full 
                            bg-gradient-to-tr from-white/20 to-white/5 group-hover:from-white/30 group-hover:to-white/10 transition">
                    <i class="fa-solid fa-calendar-alt text-2xl"></i>
                </div>
                <h3 class="font-semibold text-xl mt-4">Info Event</h3>
                <p class="text-lg font-bold tracking-wide mt-1">{{ $day }} {{ $month }} {{ $year }}</p>
            </div>
        </div>

        <!-- Tiket -->
        <div class="flex items-center justify-center px-6 py-8 
                    hover:bg-white/10 transition duration-300 cursor-pointer group">
            <div class="text-center">
                <div class="w-16 h-16 mx-auto flex items-center justify-center rounded-full 
                            bg-gradient-to-tr from-white/20 to-white/5 group-hover:from-white/30 group-hover:to-white/10 transition">
                    <i class="fa fa-ticket-alt text-2xl"></i>
                </div>
                <h3 class="font-semibold text-xl mt-4">Tiket</h3>
                <p class="text-lg font-bold tracking-wide mt-1">Mulai dari 0 Rupiah</p>
            </div>
        </div>

        <!-- Titik Kumpul -->
        <div class="flex items-center justify-center px-6 py-8 
                    hover:bg-white/10 transition duration-300 cursor-pointer group">
            <div class="text-center">
                <div class="w-16 h-16 mx-auto flex items-center justify-center rounded-full 
                            bg-gradient-to-tr from-white/20 to-white/5 group-hover:from-white/30 group-hover:to-white/10 transition">
                    <i class="fas fa-map-marker-alt text-2xl"></i>
                </div>
                <h3 class="font-semibold text-xl mt-4">Titik Kumpul</h3>
                <p class="text-lg font-bold tracking-wide mt-1">
                    {{ \App\Models\Setting::get(\App\Models\Setting::KEY_EVENT_OCCASION_PLACE) }}
                </p>
            </div>
        </div>

    </div>
</section>




        <section class="p-8 grid md:grid-cols-2 gap-6 items-start overflow-x-hidden" id="eventDescription">
            <!-- Kiri: Gambar dan Info -->
            <div class="border-t-2 border-green-600 pt-4" data-aos="fade-right">
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
        <section class="bg-cover bg-black bg-center py-10 text-white text-center" style="background-image : url('/asset/Rect Light.svg')">
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
    <h2 class="text-3xl font-bold text-center mb-10 text-gray-800">Rundown Acara</h2>

    <div class="relative">
        <!-- Garis tengah -->
        <div class="absolute left-1/2 transform -translate-x-1/2 h-full w-1 
                    bg-gradient-to-b from-green-500 via-greem-700 to-green-600">
        </div>

        @foreach ($rundowns as $item)
            @php
                $isEven = $loop->index % 2 == 0;
            @endphp

            @if (!$isEven)
                <!-- Kanan -->
                <div class="mb-12 flex justify-end items-center relative" data-aos="fade-left">
                    <div class="w-1/2"></div>
                    <!-- Titik -->
                    <div class="absolute left-1/2 transform -translate-x-1/2 w-5 h-5 
                                bg-gradient-to-tr from-green-500 to-green-800 rounded-full z-10 border-2 border-white shadow-md">
                    </div>
                    <div class="w-1/2 pl-6">
                        <div class="bg-white shadow-lg rounded-xl p-5 max-w-sm text-left border border-gray-100">
                            <h3 class="text-green-600 font-bold text-lg">
                                {{ \Carbon\Carbon::parse($item->occasion_date)->translatedFormat('d M Y (H:i)') }}
                            </h3>
                            <p class="font-bold text-gray-900">{{ $item->title }}</p>
                            <p class="text-sm text-gray-600 mt-1">{{ $item->description }}</p>
                        </div>
                    </div>
                </div>
            @else
                <!-- Kiri -->
                <div class="mb-12 flex justify-start items-center relative" data-aos="fade-right">
                    <div class="w-1/2 pr-6 flex justify-end">
                        <div class="bg-white shadow-lg rounded-xl p-5 max-w-sm text-right border border-gray-100">
                            <h3 class="text-green-600 font-bold text-lg">
                                {{ \Carbon\Carbon::parse($item->occasion_date)->translatedFormat('d M Y (H:i)') }}
                            </h3>
                            <p class="font-bold text-gray-900">{{ $item->title }}</p>
                            <p class="text-sm text-gray-600 mt-1">{{ $item->description }}</p>
                        </div>
                    </div>
                    <!-- Titik -->
                    <div class="absolute left-1/2 transform -translate-x-1/2 w-5 h-5 
                                bg-green-400 rounded-full z-10 border-2 border-white shadow-md">
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
                            'Milad Ke-111.png',
                            'Edu Expo .png',
                             'Milad Ke-111.png',
                            'Edu Expo .png',
                             'Milad Ke-111.png',
                            'Edu Expo .png',

                            
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
