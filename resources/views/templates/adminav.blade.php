@extends('templates.base')

@section('content')
    @push('head')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <meta name="csrf-token" content="{{ csrf_token() }}">
    @endpush

    <div class="flex min-h-screen font-sans">
        <aside
            class="relative w-64 bg-gradient-to-br from-red-700 min-h-screen shadow-md to-black p-6 flex flex-col overflow-hidden backdrop-blur-md">

            <div class="absolute inset-0 z-0 opacity-20 pointer-events-none"></div>

            <div class="relative z-10">
                <h3 class="text-3x2 font-bold mb-10 tracking-widget text-white items-center gap-2 flex">
                    <img src="{{ \App\Models\Setting::get(\App\Models\Setting::KEY_WEBSITE_LOGO_URL) }}" alt=""
                        class="w-6 h-6">{{ \App\Models\Setting::get(\App\Models\Setting::KEY_EVENT_NAME) }}
                </h3>

                <nav class="flex flex-col gap-4 flex-grow text-white">
                    <a href="{{ route('admin.index') }}"
                        class="flex items-center gap-3 p-3 rounded-lg hover:bg-black hover:translate-x-1 transition-all">
                        <img width="30" height="30" src="https://img.icons8.com/3d-fluency/94/bar-chart.png" alt="bar-chart"/> <span class="font-medium">Dashboard</span>
                    </a>

                    @canany(['is-admin', 'is-superadmin'])
                        <a href="{{ route('admin.tickets.index') }}"
                            class="flex items-center gap-3 p-3 rounded-lg hover:bg-black hover:translate-x-1 transition-all">
                            <img width="30" height="30" src="https://img.icons8.com/isometric/50/add-ticket.png" alt="add-ticket"/> <span class="font-medium">Tiket</span>
                        </a>
                    @endcanany

                    <a href="{{ route('admin.peserta') }}"
                        class="flex items-center gap-3 p-3 rounded-lg hover:bg-black hover:translate-x-1 transition-all">
                        <img width="30" height="30" src="https://img.icons8.com/3d-fluency/94/person-male--v3.png" alt="person-male--v3"/><span class="font-medium">Peserta</span>
                    </a>

                    <a href="{{ route('admin.fotobib.show') }}"
                        class="flex items-center gap-3 p-3 rounded-lg hover:bg-black hover:translate-x-1 transition-all">
                        <img width="30" height="30" src="https://img.icons8.com/3d-fluency/30/camera.png" alt="camera"/><span class="font-medium">Foto BIB</span>
                    </a>

                    @can('is-superadmin')
                        <a href="{{ route('admin.administrator') }}"
                            class="flex items-center gap-3 p-3 rounded-lg hover:bg-black hover:translate-x-1 transition-all">
                            <img width="30" height="30" src="https://img.icons8.com/3d-fluency/30/user-male-circle.png" alt="user-male-circle"/><span class="font-medium">Administrator</span>
                        </a>
                    @endcan

                    @canany(['is-admin', 'is-superadmin'])
                        <a href="{{ route('admin.laporan') }}"
                            class="flex items-center gap-3 p-3 rounded-lg hover:bg-black hover:translate-x-1 transition-all">
                            <img width="30" height="30" src="https://img.icons8.com/3d-fluency/30/graph-report.png" alt="graph-report"/> <span class="font-medium">Laporan</span>
                        </a>
                    @endcanany

                    @canany(['is-admin', 'is-superadmin'])
                        <a href="{{ route('admin.pesan') }}"
                            class="flex items-center gap-3 p-3 rounded-lg hover:bg-black hover:translate-x-1 transition-all">
                            <img width="30" height="30" src="https://img.icons8.com/3d-fluency/30/chat-message.png" alt="chat-message"/><span class="font-medium">Pesan</span>
                        </a>
                    @endcanany

                    @canany(['is-admin', 'is-superadmin'])
                        <a href="{{ route('admin.mails') }}"
                            class="flex items-center gap-3 p-3 rounded-lg hover:bg-black hover:translate-x-1 transition-all">
                            <img width="30" height="30" src="https://img.icons8.com/isometric/30/time-machine.png" alt="time-machine"/><span class="font-medium">Histori Email</span>
                        </a>
                    @endcanany

                    @canany(['is-admin', 'is-superadmin'])
                        <a href="{{ route('admin.pengaturan') }}"
                            class="flex items-center gap-3 p-3 rounded-lg hover:bg-black hover:translate-x-1 transition-all">
                            <img width="30" height="30" src="https://img.icons8.com/3d-fluency/30/automatic.png" alt="automatic"/><span class="font-medium">Pengaturan</span>
                        </a>
                    @endcanany
                </nav>
            </div>

            <div class="mt-auto"></div>
        </aside>

        <div class="flex-1 flex flex-col">
            <header class="flex justify-end items-center bg-black text-white p-4 shadow-md">
                @auth
                    <div class="flex items-center gap-3 relative" x-data="{ open: false }">
                        <p class="font-semibold">{{ Auth::user()->first_name }}</p>

                        <button @click="open = !open" class=" hover:bg-red-500 p-2 rounded-full transition">
                            👤
                        </button>

                        <div x-show="open" @click.away="open = false"
                            class="absolute right-0 mt-2 w-48 bg-white text-blue-700 rounded-lg shadow-lg overflow-hidden animate-fade-in-down z-50">
                            <a href="#" class="block px-4 py-2 hover:bg-blue-100">✏️ Edit Profil</a>
                            <a href="{{ route('logout') }}" class="block px-4 py-2 hover:bg-blue-100">🚪 Logout</a>
                        </div>

                        <a href="mailto:webmail@example.com" class=" hover:bg-red-500 p-2 rounded-full  transition">
                            ✉️
                        </a>
                    </div>
                @endauth
            </header>

            <main class="p-8 overflow-auto shadow-lg min-h-screen">
                @yield('navbar_admin')
            </main>
        </div>
    </div>

    <style>
        .animate-fade-in-down {
            animation: fadeInDown 0.3s ease-out forwards;
        }

        @keyframes fadeInDown {
            0% {
                opacity: 0;
                transform: translateY(-10px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
@endsection
