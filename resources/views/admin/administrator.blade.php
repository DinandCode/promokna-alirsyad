@extends ('templates.adminav')

@section('title', 'Promokna.id')

@section('navbar_admin')
    <div x-data="adminManager()" x-init="init()" class="max-w-6xl mx-auto bg-white shadow-lg rounded-lg p-6">
        <h2 class="text-xl font-semibold text-gray-700 mb-4">Kelola Administrator</h2>
        <div class="flex flex-wrap gap-2 md:gap-4 justify-between items-center mb-4">
            <input type="text" placeholder="Cari Administrator"
                class="border px-3 py-2 rounded-lg w-full sm:w-2/3 focus:outline-none focus:ring" x-model="search">
            <button @click="openModal()"
                class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 w-full sm:w-auto">Tambah
                Admin</button>
        </div>

        <div class="mb-4 bg-gray-50 p-4 rounded-lg shadow">
            <div class="flex flex-col gap-2">
                <div class="flex justify-between items-center text-purple-600 font-medium">
                    <span>👤 Semua Akses</span>
                    <span class="bg-green-500 text-white px-2 py-1 text-sm rounded-full" x-text="admins.length"></span>
                </div>
                <template x-for="(count, role) in roleCounts" :key="role">
                    <div class="flex justify-between items-center text-gray-700">
                        <span>👥 <span x-text="role"></span></span>
                        <span class="bg-green-500 text-white px-2 py-1 text-sm rounded-full" x-text="count"></span>
                    </div>
                </template>
            </div>
        </div>

        <div>
            <template x-for="admin in filteredAdmins" :key="admin.email">
                <div class="flex justify-between items-center bg-gray-50 p-3 rounded-lg mb-2 shadow">
                    <div>
                        <p class="font-medium text-gray-800" x-text="admin.email"></p>
                        <p class="text-gray-500 text-sm" x-text="'@' + admin.role"></p>
                    </div>
                    <div>
                        <button @click="openModal(admin)" class="text-blue-600">Edit</button>
                        <button @click="deleteAdmin(admin.id)" class="text-red-600">Hapus</button>
                    </div>
                </div>
            </template>
        </div>

        <!-- Empty State -->
        <template x-if="!loading && admins.length === 0">
            <div class="text-center py-10 text-gray-500">Belum ada data admin.</div>
        </template>

        <!-- Modal -->
        <div x-show="showModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white p-6 rounded w-full max-w-lg" @click.outside="closeModal()">
                <h3 class="text-lg font-semibold mb-4" x-text="editingAdmin ? 'Edit Admin' : 'Tambah Admin'"></h3>
                <form @submit.prevent="saveAdmin">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm">Nama Depan</label>
                            <input type="text" x-model="form.first_name" class="w-full border p-2 rounded" required>
                            <template x-if="errors.first_name">
                                <div class="text-sm text-red-600 mt-1" x-text="errors.first_name[0]"></div>
                            </template>
                        </div>
                        <div>
                            <label class="text-sm">Nama Belakang</label>
                            <input type="text" x-model="form.last_name" class="w-full border p-2 rounded" required>
                            <template x-if="errors.last_name">
                                <div class="text-sm text-red-600 mt-1" x-text="errors.last_name[0]"></div>
                            </template>
                        </div>
                        <div class="col-span-2">
                            <label class="text-sm">Email</label>
                            <input type="email" x-model="form.email" class="w-full border p-2 rounded" required>
                            <template x-if="errors.email">
                                <div class="text-sm text-red-600 mt-1" x-text="errors.email[0]"></div>
                            </template>
                        </div>
                        <div class="col-span-2">
                            <label class="text-sm">Telepon</label>
                            <input type="text" x-model="form.phone" class="w-full border p-2 rounded" required>
                            <template x-if="errors.phone">
                                <div class="text-sm text-red-600 mt-1" x-text="errors.phone[0]"></div>
                            </template>
                        </div>
                        <div class="col-span-2">
                            <label class="text-sm">Password</label>
                            <input type="password" x-model="form.password" class="w-full border p-2 rounded"
                                :required="!editingAdmin">
                            <template x-if="errors.password">
                                <div class="text-sm text-red-600 mt-1" x-text="errors.password[0]"></div>
                            </template>
                        </div>
                        <div class="col-span-2">
                            <label class="text-sm">Konfirmasi Password</label>
                            <input type="password" x-model="form.password_confirmation" class="w-full border p-2 rounded"
                                :required="!editingAdmin">
                            <template x-if="errors.password_confirmation">
                                <div class="text-sm text-red-600 mt-1" x-text="errors.password_confirmation[0]"></div>
                            </template>
                        </div>
                        <div class="col-span-2">
                            <label class="text-sm">Role</label>
                            <select x-model="form.role" class="w-full border p-2 rounded" required>
                                <option value="">Pilih Role</option>
                                <option value="admin">Admin</option>
                                <option value="operator">Operator</option>
                                <option value="super-admin">Super Admin</option>
                            </select>
                            <template x-if="errors.role">
                                <div class="text-sm text-red-600 mt-1" x-text="errors.role[0]"></div>
                            </template>
                        </div>
                    </div>
                    <div class="mt-4 flex justify-end gap-2">
                        <button type="button" @click="closeModal()" class="px-4 py-2 bg-gray-300 rounded">Batal</button>
                        <button class="bg-blue-500 text-white p-2 rounded-lg disabled:opacity-50" :disabled="loading"
                            x-text="loading ? (editingAdmin ? 'Menyimpan...' : 'Menambahkan...') : (editingAdmin ? 'Simpan Perubahan' : 'Simpan')"></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function adminManager() {
            return {
                admins: [],
                loading: true,
                showModal: false,
                editingAdmin: null,
                errors: {},
                search: '',
                form: {
                    first_name: '',
                    last_name: '',
                    email: '',
                    phone: '',
                    role: '',
                    password: '',
                    password_confirmation: '',
                },

                init() {
                    this.fetchAdmins();
                },

                async fetchAdmins() {
                    this.loading = true;
                    try {
                        let res = await fetch('/api/admins');
                        this.admins = await res.json();
                    } catch (err) {
                        alert('Gagal memuat data.');
                    } finally {
                        this.loading = false;
                    }
                },

                openModal(admin = null) {
                    this.editingAdmin = admin;
                    this.showModal = true;
                    this.form = admin ? {
                        first_name: admin.first_name,
                        last_name: admin.last_name,
                        email: admin.email,
                        phone: admin.phone,
                        role: admin.role,
                        password: '',
                        password_confirmation: '',
                    } : {
                        first_name: '',
                        last_name: '',
                        email: '',
                        phone: '',
                        role: '',
                        password: '',
                        password_confirmation: '',
                    };
                },

                closeModal() {
                    this.showModal = false;
                    this.editingAdmin = null;
                },

                async saveAdmin() {
                    const url = this.editingAdmin ?
                        `/api/admins/${this.editingAdmin.id}` :
                        `/api/admins`;

                    const method = this.editingAdmin ? 'PUT' : 'POST';

                    this.errors = {}; // Reset previous errors

                    try {
                        this.loading = true;
                        const res = await fetch(url, {
                            method,
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content'),
                            },
                            body: JSON.stringify(this.form)
                        });

                        if (!res.ok) {
                            const data = await res.json();
                            if (data.errors) {
                                this.errors = data.errors;
                                return;
                            }
                            throw data;
                        }

                        this.closeModal();
                        this.fetchAdmins();
                        alert(this.editingAdmin ? 'Admin diperbarui!' : 'Admin ditambahkan!');
                    } catch (err) {
                        console.error(err);
                        if (!this.errors || Object.keys(this.errors).length === 0) {
                            alert('Terjadi kesalahan saat menyimpan.');
                        }
                    } finally {
                        this.loading = false;
                    }
                },


                async deleteAdmin(id) {
                    if (!confirm('Yakin ingin menghapus admin ini?')) return;

                    try {
                        const res = await fetch(`/api/admins/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content'),
                            }
                        });

                        if (!res.ok) throw await res.json();

                        this.fetchAdmins();
                        alert('Admin berhasil dihapus.');
                    } catch (err) {
                        console.error(err);
                        alert('Gagal menghapus data.');
                    }
                },
                get filteredAdmins() {
                    return this.admins.filter(admin => admin.first_name.toLowerCase().includes(this.search
                        .toLowerCase()) || admin.last_name.toLowerCase().includes(this.search
                        .toLowerCase()));
                },
                get roleCounts() {
                    return this.admins.reduce((acc, admin) => {
                        acc[admin.role] = (acc[admin.role] || 0) + 1;
                        return acc;
                    }, {});
                }
            };
        }
    </script>
@endsection
