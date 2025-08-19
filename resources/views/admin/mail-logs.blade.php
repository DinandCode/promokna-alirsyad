@extends('templates.adminav')

@section('title', 'Pesan Masuk - Promokna.id')

@section('navbar_admin')
    <div class="max-w-7xl mx-auto p-6">
        <h2 class="text-2xl font-bold mb-4">📬 Mail Logs</h2>

        <form method="GET" action="{{ route('admin.mails') }}" class="mb-6">
            <div class="flex items-center gap-2">
                <input type="text" name="search" value="{{ $search }}" placeholder="Search email, subject, or status"
                    class="px-4 py-2 border rounded w-full max-w-md" />
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Search
                </button>
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full table-auto border border-gray-200">
                <thead class="bg-gray-100">
                    <tr class="text-left text-sm font-semibold text-gray-700">
                        <th class="px-4 py-2 border">ID</th>
                        <th class="px-4 py-2 border">To</th>
                        <th class="px-4 py-2 border">Subject</th>
                        <th class="px-4 py-2 border">Status</th>
                        <th class="px-4 py-2 border">Sent At</th>
                        <th class="px-4 py-2 border">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($logs as $log)
                        <tr class="text-sm text-gray-800 hover:bg-gray-50">
                            <td class="px-4 py-2 border">{{ $log->id }}</td>
                            <td class="px-4 py-2 border">{{ $log->to }}</td>
                            <td class="px-4 py-2 border">{{ $log->subject }}</td>
                            <td class="px-4 py-2 border">
                                <span
                                    class="inline-block px-2 py-1 text-xs rounded
                                @if ($log->status === 'sent') bg-green-200 text-green-800
                                @elseif($log->status === 'pending') bg-gray-200 text-gray-800
                                @elseif($log->status === 'failed') bg-red-200 text-red-800
                                @elseif($log->status === 'canceled') bg-yellow-200 text-yellow-800 @endif">
                                    {{ ucfirst($log->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-2 border">{{ $log->created_at->format('Y-m-d H:i') }}</td>
                            <td class="px-4 py-2 border">
                                <button class="text-blue-600 hover:underline text-sm"
                                    onclick="toggleContent({{ $log->id }})">
                                    View
                                </button>
                                @if ($log->status === 'failed')
                                    <form action="{{ route('admin.mails.resend', $log->id) }}" method="POST"
                                        class="inline-block ml-2">
                                        @csrf
                                        <button type="submit" class="text-green-600 hover:underline text-sm">
                                            Resend
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                        <tr id="content-{{ $log->id }}" style="display: none;">
                            <td colspan="6" class="border px-4 py-2 bg-gray-50">
                                <div class="max-h-64 overflow-y-auto text-sm whitespace-pre-wrap">
                                    {!! $log->content !!}
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center px-4 py-4 text-gray-500">No logs found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $logs->withQueryString()->links() }}
        </div>
    </div>

    <script>
        function toggleContent(id) {
            const row = document.getElementById('content-' + id);
            row.style.display = row.style.display === 'none' ? 'table-row' : 'none';
        }
    </script>
@endsection
