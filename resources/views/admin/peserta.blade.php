@extends ('templates.adminav')

@section('title', 'Promokna.id')

@section('navbar_admin')

    <body class="bg-gray-100 " x-data="participantsComponent()" x-init="fetchParticipants">
        <div class="max-w-6xl mx-auto bg-white shadow-lg rounded-lg p-6">
            <div class="flex justify-between items-center border-b pt-4 pb-4">
                <h1 class="text-xl font-bold">Data Peserta</h1>
                <button @click="openForm()" class="bg-blue-500 text-white p-2 rounded-lg hover:bg-blue-600">➕ Tambah
                    Peserta</button>
            </div>

            <!-- Search -->
            <div class="mt-6 mb-4 flex flex-wrap gap-2 items-center w-full">
                <div class="flex gap-2 mb-4 flex-shrink-0">
                    <input type="text" placeholder="Cari nama peserta..." x-model="search"
                        @input.debounce.500ms="searchParticipants" class="border p-2 rounded w-full" />

                    <select x-model="paymentStatusFilter" @change="searchParticipants" class="border p-2 rounded">
                        <option value="all">Semua</option>
                        <option value="paid">Dibayar</option>
                        <option value="pending">Pending</option>
                        <option value="expired">Kedaluwarsa</option>
                        <option value="no_payment">Tanpa Jersey</option>
                    </select>
                </div>
                <template x-if="searchLoading">
                    <svg class="animate-spin h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                </template>
            </div>

            <div class="my-4">
                <p>Menampilkan total: <strong x-text="pagination.total"></strong></p>
            </div>

            <!-- List Peserta -->
            <template x-for="item in peserta" :key="item.id">
                <div class="bg-white shadow rounded-lg p-4 mb-4 border border-gray-300">
                    <div class="bg-blue-500 text-white py-2 px-4 rounded-t-lg font-bold flex justify-between items-center">
                        <span>
                            No Peserta / BIB: <span x-text="item.bib"></span>
                        </span>
                        <template x-if="item.payment_status == 'paid'">
                            <span class="inline-block py-1 px-2 rounded-lg bg-green-500">Lunas</span>
                        </template>
                        <template x-if="item.payment_status == 'failed'">
                            <span class="inline-block py-1 px-2 rounded-lg bg-red-500">Gagal</span>
                        </template>
                        <template x-if="item.payment_status == 'expired'">
                            <span class="inline-block py-1 px-2 rounded-lg bg-red-500">Kedaluwarsa</span>
                        </template>
                        <template x-if="item.payment_status == 'pending'">
                            <span class="inline-block py-1 px-2 rounded-lg bg-orange-500">Belum Dibayar</span>
                        </template>
                        <template x-if="!item.payment_status">
                            <span class="inline-block py-1 px-2 rounded-lg bg-green-500">Tanpa Jersey</span>
                        </template>
                    </div>
                    <div class="p-4 bg-gray-100 rounded-b-lg">
                        <p><strong>Nama BIB:</strong> <span x-text="item.bib_name"></span></p>
                        <p><strong>Nama Lengkap:</strong> <span x-text="item.full_name"></span></p>
                        <p><strong>Ukuran Jersey:</strong> <span x-text="item.jersey_size.toUpperCase()"></span></p>
                        <p><strong>Komunitas:</strong> <span x-text="item.community"></span></p>
                        <p><strong>Jenis Kelamin:</strong> <span x-text="item.gender"></span></p>
                        <p><strong>Email:</strong> <span x-text="item.email"></span></p>
                        <p><strong>Nomor WA:</strong> <span x-text="item.phone"></span></p>
                        <p><strong>Alamat:</strong> <span x-text="item.address"></span></p>
                        <template x-if="item.taken_by && item.taken_phone && item.taken_relationship">
                            <div class="mt-4 bg-white border border-green-400 rounded p-3">
                                <p class="font-semibold text-green-700 mb-2">📦 Info Pengambilan Racepack:</p>
                                <p><strong>Diambil oleh:</strong> <span x-text="item.taken_by"></span></p>
                                <p><strong>No HP Pengambil:</strong> <span x-text="item.taken_phone"></span></p>
                                <p><strong>Hubungan dengan Peserta:</strong> <span x-text="item.taken_relationship"></span>
                                </p>
                            </div>
                        </template>
                        <template x-if="!(item.taken_by && item.taken_phone && item.taken_relationship)">
                            <div class="mt-4 text-yellow-600 font-semibold">
                                🚫 Racepack belum diambil
                            </div>
                        </template>
                    </div>
                    <div class="flex justify-end gap-2 p-2">
                        @canany(['is-admin', 'is-superadmin'])
                            <button @click="openRacepackRetrievalForm(item)"
                                x-show='!item.taken_by && !item.taken_phone && !item.taken_relationship'
                                class="bg-yellow-500 text-white p-2 rounded-lg hover:bg-yellow-600">✏️ Ambil
                                Racepack</button>
                            <button @click="openForm(item)"
                                class="bg-yellow-500 text-white p-2 rounded-lg hover:bg-yellow-600">✏️ Edit</button>
                            <button @click="deleteParticipant(item.id)"
                                class="bg-red-500 text-white p-2 rounded-lg hover:bg-red-600">Hapus</button>
                        @endcanany
                    </div>
                </div>
            </template>
            <div class="flex justify-center mt-4 space-x-2 flex-wrap">
                <button class="px-3 py-1 border rounded" :class="{ 'bg-gray-300': currentPage === 1 }"
                    @click="changePage(currentPage - 1)" :disabled="currentPage === 1">Prev</button>

                <template x-for="page in pagination.last_page" :key="page">
                    <button class="px-3 py-1 border rounded" :class="{ 'bg-blue-500 text-white': currentPage === page }"
                        @click="changePage(page)" x-text="page"></button>
                </template>

                <button class="px-3 py-1 border rounded" :class="{ 'bg-gray-300': currentPage === pagination.last_page }"
                    @click="changePage(currentPage + 1)" :disabled="currentPage === pagination.last_page">Next</button>
            </div>
        </div>

        <!-- Modal Form -->
        <div x-show="showForm" class="fixed inset-0 bg-gray-800 bg-opacity-50 p-12 overflow-scroll" x-transition>
            <form @submit.prevent="submitForm" class="bg-white p-6 rounded-lg shadow-lg w-full max-w-xl mx-auto">
                <h2 class="text-xl font-bold mb-4" x-text="form.id ? 'Edit Peserta' : 'Tambah Peserta'"></h2>

                <template x-if="errorMessage">
                    <p class="text-red-600 mb-2" x-text="errorMessage"></p>
                </template>
                <div>
                    <label for="bib_name" class="mb-2 inline-block">Nama BIB / Dada <span
                            class="text-red-600">*</span></label>
                    <input type="text" id="bib_name" x-model="form.bib_name" class="border p-2 rounded-lg w-full mb-2">
                </div>
                <div>
                    <label for="full_name" class="mb-2 inline-block">Full Belakang <span
                            class="text-red-600">*</span></label>
                    <input type="text" x-model="form.full_name" id="full_name"
                        class="border p-2 rounded-lg w-full mb-2">
                </div>
                <div>
                    <label for="email" class="mb-2 inline-block">Email <span class="text-red-600">*</span></label>
                    <input type="email" id="email" x-model="form.email" class="border p-2 rounded-lg w-full mb-2">
                </div>
                <div>
                    <label for="phone" class="mb-2 inline-block">No Telepon <span
                            class="text-red-600">*</span></label>
                    <input type="text" id="phone" x-model="form.phone" class="border p-2 rounded-lg w-full mb-2">
                </div>
                <div>
                    <label for="community" class="mb-2 inline-block">Komunitas</label>
                    <input type="text" id="community" x-model="form.community"
                        class="border p-2 rounded-lg w-full mb-2">
                </div>
                <div>
                    <label for="gender" class="mb-2 block">Jenis Kelamin</label>
                    <input type="radio" id="gender-male" value="male" x-model="form.gender">
                    <label for="gender-male">Laki-laki</label>
                    <input type="radio" id="gender-female" value="female" x-model="form.gender">
                    <label for="gender-female">Perempuan</label>
                </div>
                <div>
                    <label for="nik" class="mb-2 inline-block">NIK</label>
                    <input type="text" id="nik" x-model="form.nik" class="border p-2 rounded-lg w-full mb-2">
                </div>
                <div>
                    <label for="birthplace" class="mb-2 inline-block">Tempat Lahir</label>
                    <input type="text" id="birthplace" x-model="form.birthplace"
                        class="border p-2 rounded-lg w-full mb-2">
                </div>
                <div>
                    <label for="birthdate" class="mb-2 inline-block">Tanggal Lahir</label>
                    <input type="date" id="birthdate" x-model="form.birthdate"
                        class="border p-2 rounded-lg w-full mb-2">
                </div>
                <div>
                    <label for="address" class="mb-2 inline-block">Alamat</label>
                    <input type="text" id="address" x-model="form.address"
                        class="border p-2 rounded-lg w-full mb-2">
                </div>
                <div>
                    <label for="city" class="mb-2 inline-block">Kota</label>
                    <input type="texy" id="city" x-model="form.city" class="border p-2 rounded-lg w-full mb-2">
                </div>
                <div>
                    <label for="jersey_size" class="mb-2 inline-block">Jersey Size</label>
                    <select id="jersey_size" name="jersey_size" x-model="form.jersey_size"
                        class="w-full p-2 border rounded">
                        <option value="">Select Jersey Size</option>
                        <optgroup label="Dewasa">
                            <option value="dewasa_xs">Dewasa XS (L 48 x P 66 cm)</option>
                            <option value="dewasa_s">Dewasa S (L 50 x P 68 cm)</option>
                            <option value="dewasa_m">Dewasa M (L 52 x P 70 cm)</option>
                            <option value="dewasa_l">Dewasa L (L 54 x P 72 cm)</option>
                            <option value="dewasa_xl">Dewasa XL (L 57 x P 74 cm)</option>
                            <option value="dewasa_2xl">Dewasa 2XL (L 60 x P 76 cm)</option>
                            <option value="dewasa_3xl">Dewasa 3XL (L 63 x P 78 cm)</option>
                        </optgroup>

                        <optgroup label="Anak">
                            <option value="anak_2th">Anak 2 th (L 26 x P 38 cm)</option>
                            <option value="anak_4th">Anak 4 th (L 30 x P 42 cm)</option>
                            <option value="anak_6th">Anak 6 th (L 33 x P 44 cm)</option>
                            <option value="anak_8th">Anak 8 th (L 36 x P 50 cm)</option>
                            <option value="anak_10th">Anak 10 th (L 40 x P 56 cm)</option>
                            <option value="anak_12th">Anak 12 th (L 44 x P 62 cm)</option>
                        </optgroup>
                    </select>
                </div>
                <div>
                    <label for="blood_type" class="mb-2 inline-block">Blood Type</label>
                    <input type="text" id="blood_type" name="blood_type" x-model="form.blood_type"
                        class="w-full p-2 border rounded">
                </div>
                <div>
                    <label for="medical_history" class="mb-2 inline-block">Medical History</label>
                    <input type="text" id="medical_history" name="medical_history" x-model="form.medical_history"
                        class="w-full p-2 border rounded">
                </div>
                <div>
                    <label for="medical_note" class="mb-2 inline-block">Medical Note</label>
                    <textarea id="medical_note" name="medical_note" x-model="form.medical_note" class="w-full p-2 border rounded"></textarea>
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" @click="closeForm"
                        class="bg-gray-500 text-white p-2 rounded-lg">Batal</button>
                    <button type="submit" class="bg-blue-500 text-white p-2 rounded-lg"
                        x-text="loading ? 'Menyimpan...' : 'Simpan'" :disabled="loading"></button>
                </div>
            </form>
        </div>

        <div x-show="showRacePackForm" class="fixed inset-0 bg-gray-800 bg-opacity-50 p-12 overflow-scroll" x-transition>
            <form @submit.prevent="submitRacepackForm" class="bg-white p-6 rounded-lg shadow-lg w-full max-w-xl mx-auto">
                <h3 class="font-bold text-blue-700 mb-4">Form Pengambilan Racepack</h3>
                <div class="mb-2">
                    <input type="checkbox" x-model="racepackForm.sendiri" name="sendiri" id="sendiri">
                    <label for="sendiri">Diambil sendiri</label>
                </div>
                <div x-show="!racepackForm.sendiri">
                    <div class="mb-2">
                        <label class="block text-sm text-gray-700">Diambil oleh</label>
                        <input type="text" x-model="racepackForm.taken_by" class="w-full border p-1 rounded" />
                    </div>
                    <div class="mb-2">
                        <label class="block text-sm text-gray-700">Nomor HP Pengambil</label>
                        <input type="text" x-model="racepackForm.taken_phone" class="w-full border p-1 rounded" />
                    </div>
                    <div class="mb-2">
                        <label class="block text-sm text-gray-700">Hubungan dengan Peserta</label>
                        <input type="text" x-model="racepackForm.taken_relationship"
                            class="w-full border p-1 rounded" />
                    </div>
                </div>
                <div class="flex justify-end gap-2 mt-2">
                    <div class="flex justify-end gap-2">
                        <button type="button" @click="closeRacepackForm"
                            class="bg-gray-500 text-white p-2 rounded-lg">Batal</button>
                        <button type="submit" class="bg-blue-500 text-white p-2 rounded-lg"
                            x-text="loading ? 'Menyimpan...' : 'Simpan'" :disabled="loading"></button>
                    </div>
                </div>
            </form>
        </div>

        <script>
            function participantsComponent() {
                return {
                    peserta: [],
                    form: {},
                    racepackForm: {},
                    showForm: false,
                    showRacePackForm: false,
                    searchLoading: false,
                    loading: false,
                    errorMessage: '',
                    paymentStatusFilter: 'all',
                    search: '',
                    pagination: {
                        current_page: 1,
                        last_page: 1,
                        total: 0
                    },
                    currentPage: 1,
                    searchParticipants() {
                        this.fetchParticipants(1, this.search);
                    },
                    fetchParticipants(page = 1, search = '') {
                        this.searchLoading = true;
                        const params = new URLSearchParams({
                            page,
                            search,
                            payment_status: this.paymentStatusFilter
                        });

                        fetch(`/api/participants?${params.toString()}`)
                            .then(res => res.json())
                            .then(data => {
                                this.peserta = data.data;
                                this.pagination = {
                                    current_page: data.current_page,
                                    last_page: data.last_page,
                                    total: data.total
                                };
                                this.currentPage = data.current_page;
                            })
                            .finally(() => {
                                this.searchLoading = false;
                            });
                    },
                    changePage(page) {
                        if (page < 1 || page > this.pagination.last_page) return;
                        this.fetchParticipants(page);
                    },
                    openRacepackRetrievalForm(p) {
                        this.errorMessage = '';
                        this.racepackForm = {
                            id: p.id,
                            sendiri: !!p.sendiri,
                            taken_by: p.taken_by,
                            taken_phone: p.taken_phone,
                            taken_relationship: p.taken_relationship,
                        };

                        this.showRacePackForm = true;
                    },
                    openForm(p = null) {
                        this.errorMessage = '';
                        this.form = p ? {
                            ...p
                        } : {
                            bib_name: '',
                            full_name: '',
                            email: '',
                            phone: '',
                            community: '',
                            gender: '',
                            nik: '',
                            birthplace: '',
                            birthdate: '',
                            address: '',
                            city: '',
                            jersey_size: '',
                            blood_type: '',
                            medical_history: '',
                            medical_note: ''
                        };
                        this.showForm = true;
                    },
                    closeForm() {
                        this.showForm = false;
                    },
                    deleteParticipant(id) {
                        Swal.fire({
                            title: 'Yakin ingin menghapus?',
                            text: 'Data peserta yang dihapus tidak dapat dikembalikan!',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#3085d6',
                            confirmButtonText: 'Ya, hapus!',
                            cancelButtonText: 'Batal'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                fetch(`/api/participants/${id}`, {
                                        method: 'DELETE',
                                        headers: {
                                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                                .getAttribute('content')
                                        }
                                    })
                                    .then(res => {
                                        if (!res.ok) throw new Error('Gagal menghapus data');
                                        Swal.fire('Terhapus!', 'Peserta berhasil dihapus.', 'success');
                                        this.fetchParticipants(this.search, this.page); // Refresh daftar
                                    })
                                    .catch(err => {
                                        Swal.fire('Gagal!', err.message, 'error');
                                    });
                            }
                        });
                    },
                    submitRacepackForm() {
                        this.errorMessage = '';

                        let requiredFields = [
                            'taken_by', 'taken_phone', 'taken_relationship',
                        ];

                        if (this.racepackForm.sendiri) {
                            requiredFields = [];
                        }

                        const missingFields = requiredFields.filter(field => !this.racepackForm[field]);

                        if (missingFields.length > 0) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Data Tidak Lengkap',
                                text: 'Mohon lengkapi semua kolom yang wajib diisi!',
                                footer: 'Kolom wajib: ' + missingFields.join(', ')
                            });
                            return;
                        }

                        const url = '/api/participants/' + this.racepackForm.id + '/racepack?user_id={{ auth()->user()->id }}';

                        this.loading = true;

                        fetch(url, {
                                method: 'PUT',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                        'content')
                                },
                                body: JSON.stringify({
                                    sendiri: !!this.racepackForm.sendiri,
                                    taken_by: this.racepackForm.taken_by,
                                    taken_phone: this.racepackForm.taken_phone,
                                    taken_relationship: this.racepackForm.taken_relationship
                                })
                            })
                            .then(async res => {
                                this.loading = false;
                                if (!res.ok) {
                                    const err = await res.json();
                                    Swal.fire({
                                        title: 'Gagal menyimpan data',
                                        icon: 'error'
                                    });
                                }
                                this.fetchParticipants();
                                this.closeRacepackForm();
                            })
                            .catch(err => {
                                this.errorMessage = err.message;
                                this.loading = false;
                            });
                    },
                    closeRacepackForm() {
                        this.showRacePackForm = false;
                        this.racepackForm = {};
                    },
                    submitForm() {
                        this.errorMessage = '';

                        const requiredFields = [
                            'bib_name', 'full_name', 'email', 'phone',
                        ];

                        const missingFields = requiredFields.filter(field => !this.form[field]);

                        if (missingFields.length > 0) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Data Tidak Lengkap',
                                text: 'Mohon lengkapi semua kolom yang wajib diisi!',
                                footer: 'Kolom wajib: ' + missingFields.join(', ')
                            });
                            return;
                        }

                        this.loading = true;

                        const method = this.form.id ? 'PUT' : 'POST';
                        const url = this.form.id ? `/api/participants/${this.form.id}` : '/api/participants';

                        fetch(url, {
                                method: method,
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                        'content')
                                },
                                body: JSON.stringify(this.form)
                            })
                            .then(async res => {
                                this.loading = false;
                                if (!res.ok) {
                                    const err = await res.json();
                                    throw new Error(err.message || 'Gagal menyimpan data');
                                }
                                this.fetchParticipants();
                                this.closeForm();
                            })
                            .catch(err => {
                                this.errorMessage = err.message;
                                this.loading = false;
                            });
                    },
                    deleteParticipant(id) {
                        Swal.fire({
                            title: 'Yakin ingin menghapus?',
                            text: 'Data peserta yang dihapus tidak dapat dikembalikan!',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#3085d6',
                            confirmButtonText: 'Ya, hapus!',
                            cancelButtonText: 'Batal'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                fetch(`/api/participants/${id}`, {
                                        method: 'DELETE',
                                        headers: {
                                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                                .getAttribute('content')
                                        }
                                    })
                                    .then(deleted => {
                                        Swal.fire('Terhapus!', 'Peserta berhasil dihapus.', 'success');
                                        this.fetchParticipants();
                                    })
                                    .catch(err => {
                                        Swal.fire(
                                            'Gagal!',
                                            err.message,
                                            'error'
                                        );
                                    });
                            }
                        });
                    }

                };
            }
        </script>
    </body>
@endsection
