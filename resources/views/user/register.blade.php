@extends('templates.navbar')

@section('title', 'Promokna.id')


@section('navbar_content')

    <div class="relative flex justify-center items-center min-h-screen bg-gray-100 p-8 bg-cover bg-center"
        style="background-image: url('{{ \App\Models\Setting::get(\App\Models\Setting::KEY_EVENT_BANNER_URL) }}');">
        <div class="absolute inset-0 bg-gradient-to-b from-black/70 via-black/40 to-black/80"></div>
        <div class="bg-white mt-14 shadow-lg rounded-2xl p-8 w-full max-w-3xl z-10">
            <h2 class="text-2xl font-bold text-center text-gray-700 mb-2">Pendaftaran
                {{ \App\Models\Setting::get(\App\Models\Setting::KEY_EVENT_NAME) }}</h2>
            <h3 class="text-xl text-center text-red-800 font-semibold mb-6">Kategori: {{ $ticket->name }}</h3>

            @if ($registrationStatus == 'open')
                @if ($freeLeft <= 0)
                    <div>
                        Maaf, kuota pendaftaran sudah terpenuhi. Silahkan tunggu informasi selanjutnya!
                    </div>
                @else
                    <div class="flex gap-5 md:flex-row flex-col" x-data="{
                        step: 1,
                        withJersey: false,
                        showPassword: false,
                        form: {
                            bib_name: '{{ old('bib_name') }}',
                            full_name: '{{ old('full_name') }}',
                            email: '{{ old('email') }}',
                            phone: '{{ old('phone') }}',
                            community: '{{ old('community') }}',
                            gender: '{{ old('gender') }}',
                            {{-- nik: '{{ old('nik') }}', --}}
                            birthplace: '{{ old('birthplace') }}',
                            birthdate: '{{ old('birthdate') }}',
                            address: '{{ old('address') }}',
                            city: '{{ old('city') }}',
                            blood_type: '{{ old('blood_type') }}',
                            medical_history: '{{ old('medical_history') }}',
                            medical_note: '{{ old('medical_note') }}'
                        },
                        validateStep() {
                            const fields = [
                                [],
                                ['bib_name', 'full_name', 'email', 'phone'],
                                ['gender', 'birthplace', 'birthdate', 'address', 'city'],
                                ['blood_type']
                            ];
                            let filled = fields[this.step].every(field => this.form[field]?.trim() !== '');
                    
                            if (!filled) {
                                Swal.fire({
                                    title: 'Isi semua kolom terlebih dahulu',
                                    icon: 'warning'
                                });
                                return false;
                            }
                    
                            return true;
                        },
                        nextStep() {
                            if (this.validateStep()) this.step++;
                        },
                        prevStep() {
                            this.step--;
                        },
                        submit() {
                            if (this.validateStep()) document.getElementById('registrationForm').submit();
                        }
                    }">
                        <!-- Steps -->
                        <div
                            class="flex flex-wrap md:flex-col md:w-1/4 md:pr-4 space-x-4 space-y-4 md:space-x-0 md:space-y-4 border-b md:border-b-0 md:border-r pb-4 md:pb-0">
                            <template x-for="(label, index) in ['Data Umum', 'Biodata', 'Data Kesehatan']"
                                :key="index">
                                <button @click="step = index + 1" class="flex px-3 py-2 rounded-lg w-full"
                                    :class="step === index + 1 ? 'bg-red-500 text-white' : 'bg-gray-200 text-gray-700'">
                                    <span class="font-medium" x-text="label"></span>
                                </button>
                            </template>
                        </div>

                        <!-- Form -->
                        <form class="md:w-3/4 p-4" id="registrationForm" method="POST"
                            action="{{ route('user.attempt-register-ticket') }}">
                            @csrf

                            <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">

                            <!-- Flash Message -->
                            @if (session('success'))
                                <div class="p-4 mb-4 text-red-700 bg-red-100 rounded-lg">
                                    {{ session('success') }}
                                </div>
                            @endif

                            @if (session('error'))
                                <div class="p-4 mb-4 text-red-700 bg-red-100 rounded-lg">
                                    {{ session('error') }}
                                </div>
                            @endif

                            @if ($errors->any())
                                <script>
                                    window.addEventListener('DOMContentLoaded', function() {
                                        Swal.fire({
                                            icon: 'Error',
                                            title: 'Mohon periksa kembali',
                                            text: 'Ada kesalahan pada input yang kamu masukkan'
                                        });
                                    });
                                </script>
                            @endif

                            <div class="p-4 mb-4 bg-red-200 rounded-lg">
                                <ul class="list-disc pl-8">
                                    <li>Isi data diri anda dengan benar</li>
                                    <li>Gunakan alamat email yang aktif, informasi pendaftaran dan pembayaran akan
                                        dikirimkan
                                        melalui Email</li>
                                    <li>Kolom yang wajib diisi ditandai dengan (*)</li>
                                </ul>
                            </div>

                            <!-- Step 1: Informasi Umum -->
                            <div x-show="step === 1" class="space-y-4 mt-5">
                                <div>
                                    <label for="full_name">Nama Lengkap <span class="text-red-700">*</span></label>
                                    <input type="text" id="full_name" name="full_name" x-model="form.full_name"
                                        class="w-full p-2 border rounded @error('full_name') border-red-500 @enderror">
                                    @error('full_name')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="bib_name">Nama Dada / BIB <span class="text-red-700">*</span></label>
                                    <input type="text" id="bib_name" name="bib_name" x-model="form.bib_name"
                                        class="w-full p-2 border rounded @error('bib_name') border-red-500 @enderror">
                                    @error('bib_name')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="email">Email <span class="text-red-700">*</span></label>
                                    <input type="email" id="email" name="email" x-model="form.email"
                                        class="w-full p-2 border rounded @error('email') border-red-500 @enderror">
                                    @error('email')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="phone">No. Telepon <span class="text-red-700">*</span></label>
                                    <input type="text" id="phone" name="phone" x-model="form.phone"
                                        class="w-full p-2 border rounded @error('phone') border-red-500 @enderror">
                                    @error('phone')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="community">Asal Komunitas</label>
                                    <input type="text" id="community" name="community" x-model="form.community"
                                        class="w-full p-2 border rounded @error('community') border-red-500 @enderror">
                                    @error('community')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Step 2: Biodata -->
                            <div x-show="step === 2" class="space-y-4">
                                <div>
                                    <label for="gender">Jenis Kelamin <span class="text-red-700">*</span></label>
                                    <select id="gender" name="gender" x-model="form.gender"
                                        class="w-full p-2 border rounded @error('gender') border-red-500 @enderror">
                                        <option value="">Pilih Jenis Kelamin</option>
                                        <option value="male">Laki-laki</option>
                                        <option value="female">Perempuan</option>
                                    </select>
                                    @error('gender')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                {{-- <div>
                                    <label for="nik">NIK <span class="text-red-700">*</span></label>
                                    <input type="text" id="nik" name="nik" x-model="form.nik"
                                        class="w-full p-2 border rounded @error('nik') border-red-500 @enderror">
                                    @error('nik')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div> --}}
                                <div>
                                    <label for="birthplace">Tempat Lahir <span class="text-red-700">*</span></label>
                                    <input type="text" id="birthplace" name="birthplace" x-model="form.birthplace"
                                        class="w-full p-2 border rounded @error('birthplace') border-red-500 @enderror">
                                    @error('birthplace')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="birthdate">Tanggal Lahir <span class="text-red-700">*</span></label>
                                    <input type="date" id="birthdate" name="birthdate" x-model="form.birthdate"
                                        class="w-full p-2 border rounded @error('birthdate') border-red-500 @enderror">
                                    @error('birthdate')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="address">Alamat <span class="text-red-700">*</span></label>
                                    <input type="text" id="address" name="address" x-model="form.address"
                                        class="w-full p-2 border rounded @error('address') border-red-500 @enderror">
                                    @error('address')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="city">Kota <span class="text-red-700">*</span></label>
                                    <input type="text" id="city" name="city" x-model="form.city"
                                        class="w-full p-2 border rounded @error('city') border-red-500 @enderror">
                                    @error('city')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Step 3: Informasi Kesehatan -->
                            <div x-show="step === 3" class="space-y-4">
                                <div>
                                    <label for="blood_type">Golongan Darah <span class="text-red-700">*</span></label>
                                    <input type="text" id="blood_type" name="blood_type" x-model="form.blood_type"
                                        class="w-full p-2 border rounded @error('blood_type') border-red-500 @enderror">
                                    @error('blood_type')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="medical_history">Riwayat Penyakit Kronis</label>
                                    <input type="text" id="medical_history" name="medical_history"
                                        x-model="form.medical_history"
                                        class="w-full p-2 border rounded @error('medical_history') border-red-500 @enderror">
                                    @error('medical_history')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="medical_note">Catatan Medis</label>
                                    <textarea id="medical_note" name="medical_note" x-model="form.medical_note"
                                        class="w-full p-2 border rounded @error('medical_note') border-red-500 @enderror"></textarea>
                                    @error('medical_note')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="mb-4 flex items-center mt-2">
                                    <input type="checkbox" id="terms"
                                        class="w-5 h-5 text-red-600 border-gray-300 rounded focus:ring-red-500" required>
                                    <label for="terms" class="ml-2 text-gray-600">Saya menyetujui <a
                                            href="{{ route('home.sk') }}" target="_blank"
                                            class="text-red-500 hover:underline">syarat & ketentuan</a></label>
                                </div>
                                <!-- Responsibility Agreement -->
                                <div class="mb-4 flex items-center mt-2">
                                    <input type="checkbox" id="safety_responsibility" required
                                        class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="safety_responsibility" class="ml-2 text-gray-600">
                                        Saya bertanggungjawab penuh atas kesehatan dan keselamatan pribadi
                                    </label>
                                </div>
                                <!-- Accept Promo -->
                                <div class="mb-4 flex items-center mt-2">
                                    <input type="checkbox" id="accept_promo" name="accept_promo"
                                        class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="accept_promo" class="ml-2 text-gray-600">Saya bersedia menerima informasi
                                        promo dari Promokna.id</label>
                                </div>
                            </div>

                            <!-- Navigation Buttons -->
                            <div class="mt-4 flex justify-between">
                                <button type="button" @click="prevStep()" x-show="step > 1"
                                    class="px-4 py-2 bg-gray-300 rounded">Previous</button>
                                <button type="button" @click="nextStep()" x-show="step < 3"
                                    class="px-4 py-2 bg-red-500 text-white rounded">Next</button>
                                <button x-show="step === 3" type="button" @click="submit()" disabled
                                    class="px-4 py-2 bg-red-500 text-white rounded disabled:bg-red-300"
                                    id="btnSubmit">Submit</button>
                            </div>
                        </form>
                    </div>
                @endif
            @else
                <div>
                    Maaf, pendaftaran sekarang sudah ditutup. Silahkan tunggu informasi selanjutnya!
                </div>
            @endif
        </div>
    </div>
@endsection

@push('script')
    <script>
        function validateCheckboxes() {
            const termsChecked = document.getElementById('terms').checked;
            const safetyChecked = document.getElementById('safety_responsibility').checked;
            document.querySelector('#btnSubmit').disabled = !(termsChecked && safetyChecked);
        }

        document.getElementById('terms').addEventListener('change', validateCheckboxes);
        document.getElementById('safety_responsibility').addEventListener('change', validateCheckboxes);

        document.getElementById('registrationForm').addEventListener('submit', function(event) {
            event.preventDefault();
            alert('Pendaftaran berhasil! Anda akan menerima email konfirmasi pembayaran.');
        });
    </script>
@endpush
