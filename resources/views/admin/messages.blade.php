@extends('templates.adminav')

@section('title', 'Pesan Masuk - Promokna.id')

@push('head')
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
    <script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
@endpush

@section('navbar_admin')

 <div class="bg-gray-100 min-h-screen">
            <div class="max-w-6xl mx-auto bg-white shadow-lg rounded-lg p-6">
            <h1 class="text-xl font-bold mb-4">Pesan Masuk dari Pengguna</h1>

            {{-- Form pencarian --}}
            <form method="GET" action="{{ route('admin.pesan') }}"
                class="mt-2 mb-4 flex flex-wrap gap-2 md:gap-4 items-center">
                <input type="text" name="search" placeholder="Cari berdasarkan nama atau email"
                    value="{{ request('search') }}" class="border p-2 rounded-lg w-full sm:w-1/2">

                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Cari</button>
                @if (!empty(request('search')))
                    <a href="{{ route('admin.pesan') }}" class="bg-gray-600 text-white px-4 py-2 rounded">Bersihkan</a>
                @endif
            </form>

            <div class="overflow-x-auto mt-4">
                <table class="w-full border-collapse border border-gray-300">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border p-2">No</th>
                            <th class="border p-2">Nama</th>
                            <th class="border p-2">Perusahaan</th>
                            <th class="border p-2">Telepon</th>
                            <th class="border p-2">Email</th>
                            <th class="border p-2">Subjek</th>
                            <th class="border p-2">Pesan</th>
                            <th class="border p-2">Waktu</th>
                            <th class="border p-2">Aksi</th>
                        </tr>
                    </thead>
                    @forelse($messages as $index => $message)
                        <tbody x-data="{ open: false, isLoading: false }" id="row-{{ $message->id }}">
                            <tr class="bg-white border hover:bg-gray-50">
                                <td class="border p-2 text-center">
                                    {{ ($messages->currentPage() - 1) * $messages->perPage() + $index + 1 }}
                                </td>
                                <td class="border p-2">{{ $message->name }}</td>
                                <td class="border p-2">{{ $message->company }}</td>
                                <td class="border p-2">{{ $message->phone }}</td>
                                <td class="border p-2">{{ $message->email }}</td>
                                <td class="border p-2">{{ $message->subject }}</td>
                                <td class="border p-2">{{ $message->message }}</td>
                                <td class="border p-2 text-sm text-gray-600">
                                    {{ $message->created_at->format('d M Y H:i') }}
                                </td>
                                <td class="border p-2 action-cell">
                                    @if ($message->replyLog && $message->replyLog->status == 'sent')
                                        <div class="flex flex-col gap-1">
                                            <span class="text-green-600 font-semibold">Sudah Dibalas</span>
                                            <button type="button" class="bg-gray-500 text-white px-3 py-1 rounded"
                                                onclick="lihatBalasan(this)"
                                                data-subject="{{ $message->replyLog->subject }}"
                                                data-content="{{ e($message->replyLog->content) }}">
                                                Lihat Balasan
                                            </button>
                                        </div>
                                    @else
                                        <button type="button" class="bg-blue-500 text-white px-3 py-1 rounded"
                                            @click="open = !open">
                                            Balas
                                        </button>
                                    @endif
                                </td>
                            </tr>

                            {{-- baris balasan --}}
                            <tr x-bind:style="open ? '' : 'display: none;'">
                                <td colspan="9" class="border p-4 bg-gray-50">
                                    <form @submit="isLoading = true; sendReply(event, {{ $message->id }}, () => { isLoading = false; open = false })">
                                        @csrf
                                        <input type="text" name="subject" placeholder="Subjek Balasan" {!! $message->replyLog ? 'value="' . $message->replyLog->subject . '"' : 'value="' . config('app.name') . " | BALAS: " . $message->subject . '"' !!}
                                            class="border p-2 rounded w-full mb-2">

                                        {{-- Editor Trix SELALU ada di DOM --}}
                                        <input id="x-content-{{ $message->id }}" type="hidden" name="content" {!! $message->replyLog ? 'value="' . $message->replyLog->content . '"' : '' !!}>
                                        <trix-editor input="x-content-{{ $message->id }}"
                                            class="border rounded w-full"></trix-editor>

                                        <button :disabled="isLoading"
                                            class="mt-2 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 disabled:opacity-50 flex items-center gap-2">
                                            <template x-if="!isLoading">
                                                <span>Kirim</span>
                                            </template>
                                            <template x-if="isLoading">
                                                <span class="flex items-center gap-2">
                                                    <svg class="animate-spin h-4 w-4 text-white"
                                                        xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                                            stroke="currentColor" stroke-width="4"></circle>
                                                        <path class="opacity-75" fill="currentColor"
                                                            d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                                    </svg>
                                                    Mengirim...
                                                </span>
                                            </template>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        </tbody>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-4">Tidak ada pesan yang ditemukan.</td>
                        </tr>
                    @endforelse
                </table>

                <div class="mt-4">
                    {{ $messages->appends(request()->query())->links() }}
                </div>
            </div>
        </div>

        <!-- Modal Preview -->
        <div x-data="{ showPreview: false, content: '', subject: '' }" x-show="showPreview"
            class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50" x-cloak x-transition>
            <div class="bg-white rounded-lg shadow-lg w-1/2 max-h-[80vh] overflow-y-auto p-6 relative">
                <button @click="showPreview = false"
                    class="absolute top-2 right-2 text-gray-600 hover:text-gray-800">✖</button>

                <h2 class="text-xl font-bold mb-4" x-text="subject"></h2>
                <div class="prose max-w-none" x-html="content"></div>
            </div>
        </div>

 </div>

@endsection

@push('script')
    <script>
        function sendReply(event, messageId, completed) {
            event.preventDefault();

            let form = event.target;
            let formData = new FormData(form);

            fetch(`/admin/pesan/${messageId}/reply`, {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": form.querySelector('input[name="_token"]').value,
                        "Accept": "application/json"
                    },
                    body: formData
                })
                .then(response => {
                    if (!response.ok) throw new Error("Gagal mengirim balasan");
                    return response.json();
                })
                .then(result => {
                    // update UI kolom aksi
                    let row = document.querySelector(`#row-${messageId} .action-cell`);
                    row.innerHTML = `
                <div class="flex flex-col gap-1">
                    <span class="text-green-600 font-semibold">Sudah Dibalas</span>
                    <button type="button" class="bg-gray-500 text-white px-3 py-1 rounded"
                        onclick="lihatBalasan(this)"
                        data-subject="${result.subject}"
                        data-content="${result.content}">
                        Lihat Balasan
                    </button>
                </div>
            `;
                    Swal.fire({
                        title: "Balasan berhasil dikirim!",
                        icon: "success"
                    });
                })
                .catch(err => {
                    console.error(err);
                    Swal.fire({
                        title: "Terjadi kesalahan saat mengirimkan balasan",
                        icon: "error"
                    });
                }).finally(() => {
                    if (completed) completed();
                });
        }

        function lihatBalasan(button) {
            let subject = button.getAttribute("data-subject");
            let content = button.getAttribute("data-content");

            // decode entity -> biar jadi HTML bener
            let parser = new DOMParser();
            let decoded = parser.parseFromString(content, "text/html").body.innerText;

            let modal = document.querySelector('[x-data*="showPreview"]');
            let alpine = Alpine.$data(modal);
            alpine.subject = subject;
            alpine.content = decoded;
            alpine.showPreview = true;
        }
    </script>
@endpush
