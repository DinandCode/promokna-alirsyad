@extends ('templates.adminav')

@section('title', 'Promokna.id')

@push('head')
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
    <script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
@endpush


@section('navbar_admin')

    <div class="max-w-6xl mx-auto bg-white shadow-lg rounded-lg p-6">
        <!-- Konten Halaman dengan Transisi Slide -->
        <div class="relative mt-6 h-auto overflow-hidden">

            <!-- Halaman Home -->
            <form x-transition:enter="transform transition ease-out duration-500"
                x-transition:enter-start="translate-x-full opacity-0" x-transition:enter-end="translate-x-0 opacity-100"
                x-transition:leave="transform transition ease-in duration-500"
                x-transition:leave-start="translate-x-0 opacity-100" x-transition:leave-end="-translate-x-full opacity-0"
                class="relative w-full p-4 bg-white shadow-lg rounded-lg" action="{{ route('admin.settings.update') }}"
                method="POST" enctype="multipart/form-data" x-data="{ logo: '', banner: '', registrasiDibuka: {{ \App\Models\Setting::get(\App\Models\Setting::KEY_REGISTRATION_STATUS) === 'open' ? 'true' : 'false' }} }">
                @csrf

                @if (session('success'))
                    <div class="p-4 mb-4 text-green-700 bg-green-100 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="p-4 mb-4 text-red-700 bg-red-100 rounded-lg">
                        {{ session('error') }}
                    </div>
                @endif

                <h2 class="text-2xl font-bold text-center">Pengaturan Beranda</h2>
                <p class="text-gray-600 text-center mb-6">Atur informasi utama website event lari.</p>
                <div class="mb-4">
                    <label class="block text-gray-700">Nama Event</label>
                    <input name="{{ \App\Models\Setting::KEY_EVENT_NAME }}" class="mt-2 w-full border p-2 rounded"
                        value="{{ \App\Models\Setting::get(\App\Models\Setting::KEY_EVENT_NAME) }}">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700">Hari Pelaksanaan Event</label>
                    <input name="{{ \App\Models\Setting::KEY_EVENT_OCCASION_DATE }}" class="mt-2 w-full border p-2 rounded"
                        type="date" value="{{ \App\Models\Setting::get(\App\Models\Setting::KEY_EVENT_OCCASION_DATE) }}">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700">Tempat Pelaksanaan Event</label>
                    <input name="{{ \App\Models\Setting::KEY_EVENT_OCCASION_PLACE }}" class="mt-2 w-full border p-2 rounded"
                        type="text"
                        value="{{ \App\Models\Setting::get(\App\Models\Setting::KEY_EVENT_OCCASION_PLACE) }}">
                </div>
                {{-- <div class="mb-4">
                    <label class="block text-gray-700">Biaya Pendaftaran (Jersey)</label>
                    <input name="{{ \App\Models\Setting::KEY_PAYMENT_AMOUNT }}" class="mt-2 w-full border p-2 rounded"
                        value="{{ \App\Models\Setting::get(\App\Models\Setting::KEY_PAYMENT_AMOUNT) }}">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700">Biaya Admin Pembayaran (%)</label>
                    <input name="{{ \App\Models\Setting::KEY_PAYMENT_RATE_PERCENT }}"
                        class="mt-2 w-full border p-2 rounded"
                        value="{{ \App\Models\Setting::get(\App\Models\Setting::KEY_PAYMENT_RATE_PERCENT) }}">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700">Limit Pendaftar Tanpa Jersey (Gratis)</label>
                    <input name="{{ \App\Models\Setting::KEY_EVENT_FREE_MEMBER_LIMIT }}" type="number"
                        class="mt-2 w-full border p-2 rounded"
                        value="{{ \App\Models\Setting::get(\App\Models\Setting::KEY_EVENT_FREE_MEMBER_LIMIT) }}">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700">Limit Pendaftar Dengan Jersey (Bayar)</label>
                    <input name="{{ \App\Models\Setting::KEY_EVENT_PAID_MEMBER_LIMIT }}" type="number"
                        class="mt-2 w-full border p-2 rounded"
                        value="{{ \App\Models\Setting::get(\App\Models\Setting::KEY_EVENT_PAID_MEMBER_LIMIT) }}">
                </div> --}}
                <!-- Input Banner -->
                <div class="mb-4">
                    <label class="block text-gray-700">Banner Website</label>
                    @if (\App\Models\Setting::get(\App\Models\Setting::KEY_EVENT_BANNER_URL))
                        <img src="{{ \App\Models\Setting::get(\App\Models\Setting::KEY_EVENT_BANNER_URL) }}"
                            class="w-64 object-cover rounded shadow">
                    @endif
                    <input type="file" name="{{ \App\Models\Setting::KEY_EVENT_BANNER_URL }}"
                        class="mt-2 w-full border p-2 rounded" accept="image/*"
                        @change="banner = URL.createObjectURL($event.target.files[0])">
                    <div class="mt-2" x-show="banner">
                        <img :src="banner" class="w-64 object-cover rounded shadow">
                    </div>
                </div>
                <!-- Input Logo -->
                <div class="mb-4">
                    <label class="block text-gray-700">Logo Website</label>
                    @if (\App\Models\Setting::get(\App\Models\Setting::KEY_WEBSITE_LOGO_URL))
                        <img src="{{ \App\Models\Setting::get(\App\Models\Setting::KEY_WEBSITE_LOGO_URL) }}"
                            class="w-32 h-32 object-cover rounded shadow">
                    @endif
                    <input type="file" name="{{ \App\Models\Setting::KEY_WEBSITE_LOGO_URL }}"
                        class="mt-2 w-full border p-2 rounded" accept="image/*"
                        @change="logo = URL.createObjectURL($event.target.files[0])">
                    <div class="mt-2" x-show="logo">
                        <img :src="logo" class="w-32 h-32 object-cover rounded shadow">
                    </div>
                </div>
                <!-- Upload Gambar Baju & Medali -->
                <div x-data="{ benefit: '', maps: '' }">
                    <div>
                        <label class="block text-gray-700">Benefit Peserta</label>
                        @if (\App\Models\Setting::get(\App\Models\Setting::KEY_EVENT_BENEFITS_URL))
                            <img src="{{ \App\Models\Setting::get(\App\Models\Setting::KEY_EVENT_BENEFITS_URL) }}"
                                class="w-64 object-cover rounded shadow">
                        @endif
                        <input type="file" class="mt-2 w-full border p-2 rounded" accept="image/*"
                            name="{{ \App\Models\Setting::KEY_EVENT_BENEFITS_URL }}"
                            @change="benefit = URL.createObjectURL($event.target.files[0])">
                        <div class="mt-2" x-show="benefit">
                            <img :src="benefit" class="w-64 object-cover rounded shadow">
                        </div>
                    </div>
                    <div>
                        <label class="block text-gray-700">Peta Rute</label>
                        @if (\App\Models\Setting::get(\App\Models\Setting::KEY_EVENT_MAPS_URL))
                            <img src="{{ \App\Models\Setting::get(\App\Models\Setting::KEY_EVENT_MAPS_URL) }}"
                                class="w-64 object-cover rounded shadow">
                        @endif
                        <input type="file" class="mt-2 w-full border p-2 rounded" accept="image/*"
                            name="{{ \App\Models\Setting::KEY_EVENT_MAPS_URL }}"
                            @change="maps = URL.createObjectURL($event.target.files[0])">
                        <div class="mt-2" x-show="maps">
                            <img :src="maps" class="w-64 object-cover rounded shadow">
                        </div>
                    </div>
                </div>
                <!-- Deskripsi Event -->
                <div class="mb-4">
                    <label class="block text-gray-700">Deskripsi Event</label>
                    <textarea name="{{ \App\Models\Setting::KEY_EVENT_DESCRIPTION }}" class="mt-2 w-full border p-2 rounded"
                        rows="4">{{ \App\Models\Setting::get(\App\Models\Setting::KEY_EVENT_DESCRIPTION) }}</textarea>
                </div>
                <!-- Edit Teks Rundown -->
                <div class="mb-4" x-data='{ rundown: @json($existingRundowns->isNotEmpty() ? $existingRundowns : [['judul' => '', 'tanggal' => '', 'deskripsi' => '']]) }'>
                    <label class="block text-gray-700">Teks Rundown</label>
                    <input type="hidden" name="rundowns" :value="JSON.stringify(rundown)">
                    <template x-for="(item, index) in rundown" :key="index">
                        <div class="mb-4 p-4 border rounded-lg bg-gray-50">
                            <input type="text" placeholder="Judul Acara" class="w-full border p-2 rounded mb-2"
                                x-model="item.judul">
                            <input type="datetime-local" placeholder="Tanggal Acara"
                                class="w-full border p-2 rounded mb-2" x-model="item.tanggal">
                            <textarea placeholder="Deskripsi" class="w-full border p-2 rounded" x-model="item.deskripsi"></textarea>
                            <button class="mt-2 text-red-500" @click="rundown.splice(index, 1)">Hapus</button>
                        </div>
                    </template>
                    <button class="mt-2 bg-blue-500 text-white px-4 py-2 rounded-full" type="button"
                        @click="rundown.push({judul: '', tanggal: '', deskripsi: ''})">Tambah Rundown</button>
                </div>

                <!-- Status Registrasi -->
                <div class="flex items-center bg-gray-200 p-4 rounded-lg gap-4">
                    <span class="text-gray-700">Status Registrasi</span>
                    <input type="hidden" name="{{ \App\Models\Setting::KEY_REGISTRATION_STATUS }}"
                        :value="registrasiDibuka ? 'open' : 'closed'">
                    <button type="button" @click="registrasiDibuka = !registrasiDibuka"
                        :class="registrasiDibuka ? 'bg-green-500' : 'bg-red-500'"
                        class="px-4 py-2 text-white rounded shadow-md transition-all  ml-auto block">
                        <span x-text="registrasiDibuka ? 'Buka' : 'Tutup'"></span>&nbsp;
                    </button>
                </div>

                <div class="mt-6 text-right">
                    <button
                        class="bg-blue-600 text-white px-4 py-2 rounded-full shadow-lg hover:bg-blue-700 transition-all">Simpan
                        Perubahan</button>
                </div>
            </form>

            <!-- Pengaturan Halaman FAQ -->
            <form x-transition:enter="transform transition ease-out duration-500"
                x-transition:enter-start="translate-x-full opacity-0" x-transition:enter-end="translate-x-0 opacity-100"
                x-transition:leave="transform transition ease-in duration-500"
                x-transition:leave-start="translate-x-0 opacity-100" x-transition:leave-end="-translate-x-full opacity-0"
                class="w-full p-4 bg-white shadow-lg rounded-lg" method="POST"
                action="{{ route('admin.faqs.update') }}">
                @csrf
                <button @click="page = 'home'" class="mt-4 bg-red-500 text-white px-4 py-2 rounded-lg">⬅️
                    Kembali</button>
                <!-- Faq -->
                <div class="my-12">
                    @if (session('success'))
                        <div class="p-4 mb-4 text-green-700 bg-green-100 rounded-lg">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="p-4 mb-4 text-red-700 bg-red-100 rounded-lg">
                            {{ session('error') }}
                        </div>
                    @endif
                </div>
                <h2 class="text-2xl text-center font-bold mt-6 ">Pengaturan FAQ</h2>
                <p class="text-gray-600 text-center mb-6">Atur informasi seputar pertanyaan event lari.</p>

                <div class="mb-4" x-data='{ faqs: @json($existingFaqs->isNotEmpty() ? $existingFaqs : [['pertanyaan' => '', 'jawaban' => '']]) }'>
                    <input type="hidden" name="faqs" :value="JSON.stringify(faqs)">
                    <template x-for="(faq, index) in faqs" :key="index">
                        <div class="mb-4 p-4 border rounded-lg bg-gray-50">
                            <input type="text" placeholder="Pertanyaan" class="w-full border p-2 rounded mb-2"
                                x-model="faq.pertanyaan">
                            <textarea placeholder="Jawaban" class="w-full border p-2 rounded" x-model="faq.jawaban"></textarea>
                            <button class="mt-2 text-red-500" @click="faqs.splice(index, 1)">Hapus</button>
                        </div>
                    </template>
                    <button class="mt-2 bg-blue-500 text-white px-4 py-2 rounded-full" type="button"
                        @click="faqs.push({pertanyaan: '', jawaban: ''})">Tambah FAQ</button>
                </div>
                <!-- Tentang -->
                <h2 class="text-2xl text-center font-bold mt-6">Pengaturan Tentang</h2>
                <p class="text-gray-600 text-center mb-6">Atur informasi tentang event lari.</p>

                <div class="mb-4">
                    <input id="{{ \App\Models\Setting::KEY_EVENT_ABOUT }}" type="hidden"
                        name="{{ \App\Models\Setting::KEY_EVENT_ABOUT }}"
                        value="{{ \App\Models\Setting::get(\App\Models\Setting::KEY_EVENT_ABOUT) }}">
                    <trix-editor input="{{ \App\Models\Setting::KEY_EVENT_ABOUT }}"
                        class="trix-content bg-white rounded border p-2"></trix-editor>
                </div>
                <div class="mt-6 text-right">
                    <button
                        class="bg-blue-600 text-white px-4 py-2 rounded-full shadow-lg hover:bg-blue-700 transition-all">Simpan
                        Perubahan</button>
                </div>

                <!-- Pengaturan S&K -->
                <h2 class="text-2xl text-center font-bold mt-6">Pengaturan S&K</h2>
                <p class="text-gray-600 text-center mb-6">Atur informasi tentang syarat dan ketentuan event.</p>

                <div class="mb-4">
                    <input id="{{ \App\Models\Setting::KEY_EVENT_SK }}" type="hidden"
                        name="{{ \App\Models\Setting::KEY_EVENT_SK }}"
                        value="{{ \App\Models\Setting::get(\App\Models\Setting::KEY_EVENT_SK) }}">
                    <trix-editor input="{{ \App\Models\Setting::KEY_EVENT_SK }}"
                        class="trix-content bg-white rounded border p-2"></trix-editor>
                </div>
                <div class="mt-6 text-right">
                    <button
                        class="bg-blue-600 text-white px-4 py-2 rounded-full shadow-lg hover:bg-blue-700 transition-all">Simpan
                        Perubahan</button>
                </div>
            </form>

            <form action="{{ route('admin.settings.update') }}" method="POST">
                @csrf
                <h2 class="text-2xl text-center font-bold mt-6 ">Pengaturan Email</h2>
                <p class="text-gray-600 text-center mb-6">Atur informasi tambahan seputar konfirmasi email.</p>

                <div class="mb-4">
                    <label class="block text-gray-700">Catatan Tambahan saat Pengiriman email sukses</label>
                    <input id="{{ \App\Models\Setting::KEY_SUCCESS_EMAIL_NOTE }}" type="hidden"
                        name="{{ \App\Models\Setting::KEY_SUCCESS_EMAIL_NOTE }}"
                        value="{{ \App\Models\Setting::get(\App\Models\Setting::KEY_SUCCESS_EMAIL_NOTE) }}">
                    <trix-editor input="{{ \App\Models\Setting::KEY_SUCCESS_EMAIL_NOTE }}"
                        class="trix-content bg-white rounded border p-2"></trix-editor>
                </div>

                <div class="mt-6 text-right">
                    <button
                        class="bg-blue-600 text-white px-4 py-2 rounded-full shadow-lg hover:bg-blue-700 transition-all">Simpan
                        Perubahan</button>
                </div>
            </form>
        </div>
    </div>
@endsection
