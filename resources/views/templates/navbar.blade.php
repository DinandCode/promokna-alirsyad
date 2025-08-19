@extends('templates.base')

@section('content')
    <!-- Navbar -->
    <header class="fixed top-0 left-0 w-full z-50">
        <div class="flex items-center justify-between px-6 py-4 bg-black bg-opacity-20 text-white backdrop-blur-sm">
            <div class="text-xl items-center space-x-2 flex font-bold">
                <img class="h-8 space-x-2 w-8 object-contain mr-4"
                    src="{{ \App\Models\Setting::get(\App\Models\Setting::KEY_WEBSITE_LOGO_URL) }}" alt="">
                {{ \App\Models\Setting::get(\App\Models\Setting::KEY_EVENT_NAME) }}
            </div>
            <nav class="hidden md:flex space-x-6 text-sm  tracking-wide">
                <a href="{{ route('home.index') }}" class="hover:text-orange-400 transition">Beranda</a>
                <a href="{{ route('user.peserta') }}" class="hover:text-orange-400 transition">List Peserta</a>
                <a href="{{ route('home.faq') }}" class="hover:text-orange-400 transition">FAQ</a>
                <a href="{{ route('home.sk') }}" class="hover:text-orange-400 transition">Syarat & Ketentuan</a>
                <a href="https://drive.google.com/drive/folders/1eq9-HiYjZFQ0lEuBgbmNMWy3s9eqnnf_?usp=sharing"
                    target="_blank" class="hover:text-orange-400 transition">Dokumentasi</a>
            </nav>
            <!-- Mobile menu toggle -->
            <div class="md:hidden">
                <button onclick="document.getElementById('mobileMenu').classList.toggle('hidden')">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobileMenu" class="md:hidden bg-green-800 bg-opacity-90 text-white px-6 py-4 hidden">
            <a href="{{ route('home.index') }}" class="block py-2 border-b border-gray-700">Beranda</a>
            <a href="{{ route('user.peserta') }}" class="block py-2 border-b border-gray-700">List Peserta</a>
            <a href="{{ route('home.faq') }}" class="block py-2 border-b border-gray-700">FAQ</a>
            <a href="{{ route('home.sk') }}" class="block py-2 border-b border-gray-700">Syarat & Ketentuan</a>
            <a href="https://drive.google.com/drive/folders/1eq9-HiYjZFQ0lEuBgbmNMWy3s9eqnnf_?usp=sharing" target="_blank"
                class="block py-2">Dokumentasi</a>
        </div>
    </header>
    @yield('navbar_content')
    <footer class="bg-red-800 py-12 px-4 bg-cover" style="background-image : url('/asset/Rect Light.svg')">
        <div class="max-w-6xl mx-auto grid md:grid-cols-2 gap-10 text-white">
            <!-- Bagian Kiri: Info Kontak -->
            <div>
                <h3 class="text-2xl font-semibold mb-4">Kontak Kami </h3>
                <p class="text-white-600 mb-6">Silahkan hubungi kami apabila ada pertanyaan maupun saran mengenai event ini
                </p>

                {{-- <!-- Alamat -->
                <div class="flex items-start space-x-4 mb-4">
                    <div class="bg-orange-400 text-white p-3 rounded flex items-center justify-center w-10 h-10">
                        <i class="fas fa-map-marker-alt text-white text-lg"></i>
                    </div>
                    <div>
                        <p class="font-semibold">Kantor Kami</p>
                        <p>Jl. Cempaka warni No 22<br>Jakarta – Indonesia</p>
                    </div>
                </div> --}}

                <!-- Telepon -->
                {{-- <div class="flex items-start space-x-4 mb-4">
                    <div class="bg-orange-400 text-white p-3 rounded flex items-center justify-center w-10 h-10">
                        <i class="fas fa-phone-alt text-white text-lg"></i>
                    </div>
                    <div>
                        <p class="font-semibold">Telepon Kami</p>
                        <p>Phone: 081 642 822 13</p>
                    </div>
                </div> --}}

                <!-- Email -->
                <div class="flex items-start space-x-4 mb-4">
                    <div class="bg-[#f08a16] text-white p-3 rounded flex items-center justify-center w-10 h-10">
                        <i class="fas fa-envelope text-white text-lg"></i>
                    </div>
                    <div>
                        <p class="font-semibold">Email Kami</p>
                        <p>
                            <a href="mailto:info@promokna.id" target="_blank">info@promokna.id</a>
                        </p>
                    </div>
                </div>


                <!-- Ikon Sosial Media -->
                <div class="mt-6">
                    <p class="mb-2">Follow our social media</p>
                    <div class="flex space-x-3">
                        <a href="#" class="text-white hover:text-orange-700"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-white hover:text-orange-700"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-white hover:text-orange-700"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-white hover:text-orange-700"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
            </div>

            <!-- Bagian Kanan: Formulir -->
            <div class="bg-white shadow-md rounded-lg p-8">
                <h3 class="text-2xl text-black font-semibold mb-2">Kirim Pesan Kepada Kami</h3>
                <p class="text-gray-600 mb-6 text-sm">Anda dapat mengirim pesan melalui formulir ini (semua kolom wajib
                    diisi)</p>
                <form class="space-y-4 text-black" method="POST" action="{{ route('home.send-message') }}">
                    @csrf
                    <div class="flex space-x-4">
                        <input type="text" name="name" placeholder="Name" class="w-1/2 p-2 border rounded" required>
                        <input type="text" name="company" placeholder="Company" class="w-1/2 p-2 border rounded"
                            required>
                    </div>
                    <div class="flex space-x-4">
                        <input type="text" name="phone" placeholder="Phone" class="w-1/2 p-2 border rounded" required>
                        <input type="email" name="email" placeholder="Email" class="w-1/2 p-2 border rounded" required>
                    </div>
                    <input type="text" name="subject" placeholder="Subject" class="w-full p-2 border rounded" required>
                    <textarea rows="4" name="message" placeholder="Message" class="w-full p-2 border rounded" required></textarea>
                    <button type="submit"
                        class="bg-[#f08a16] hover:bg-yellow-500 text-white py-2 px-6 rounded font-semibold">SEND
                        MESSAGE</button>
                </form>
            </div>
        </div>

        @if (session('message'))
            <script>
                window.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        title: "{{ session('message')['title'] }}",
                        icon: "{{ session('message')['type'] }}"
                    })
                });
            </script>
        @endif

        <!-- watermark -->
        <div class="border-t border-white border-opacity-20 mt-12 pt-4 text-sm text-center text-white">
            <div class="mb-1">&copy; 2025 {{ \App\Models\Setting::get(\App\Models\Setting::KEY_EVENT_NAME) }}. All
                rights reserved.</div>
            <div class="text-gray-300">
                Developed by <a href="https://promokna.id" target="_blank"
                    class="text-[#f08a16] hover:underline">promokna.id</a>
            </div>
        </div>

    </footer>
@endsection
