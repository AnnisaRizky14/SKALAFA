<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detail Pengaduan
            </h2>
            <a href="{{ route('admin.complaints.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <!-- Complaint Header -->
                    <div class="border-b border-gray-200 pb-6 mb-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900">{{ $complaint->title }}</h3>
                                <p class="text-sm text-gray-600 mt-1">
                                    Diterima pada {{ $complaint->created_at->format('d F Y, H:i') }}
                                </p>
                            </div>
                            <div class="text-right">
                                @if($complaint->status === 'pending')
                                    <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Tertunda
                                    </span>
                                @elseif($complaint->status === 'in_progress')
                                    <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-blue-100 text-blue-800">
                                        Dalam Proses
                                    </span>
                                @elseif($complaint->status === 'resolved')
                                    <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">
                                        Telah Selesai
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Complaint Details -->
                        <div class="lg:col-span-2">
                            <div class="bg-gray-50 rounded-lg p-6">
                                <h4 class="text-lg font-semibold text-gray-900 mb-4">Detail Pengaduan</h4>

                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Judul</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ $complaint->title }}</p>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
                                        <div class="mt-1 text-sm text-gray-900 bg-white p-3 rounded border">
                                            {{ $complaint->description }}
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Email</label>
                                            <p class="mt-1 text-sm text-gray-900">{{ $complaint->email ?: '-' }}</p>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Status</label>
                                            <p class="mt-1 text-sm text-gray-900">
                                                @if($complaint->status === 'pending')
                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                        Tertunda
                                                    </span>
                                                @elseif($complaint->status === 'in_progress')
                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                                        Dalam Proses
                                                    </span>
                                                @elseif($complaint->status === 'resolved')
                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                        Telah Selesai
                                                    </span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>

                                    @if($complaint->admin_notes)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Catatan Admin</label>
                                        <div class="mt-1 text-sm text-gray-900 bg-white p-3 rounded border">
                                            {{ $complaint->admin_notes }}
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Update Status Form -->
                        <div>
                            <div class="bg-white border border-gray-200 rounded-lg p-6">
                                <h4 class="text-lg font-semibold text-gray-900 mb-4">Perbarui Status Pengaduan</h4>

                                <form method="POST" action="{{ route('admin.complaints.update', $complaint) }}">
                                    @csrf
                                    @method('PUT')

                                    <div class="space-y-4">
                                        <div>
                                            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                            <select name="status" id="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500">
                                                <option value="pending" {{ $complaint->status === 'pending' ? 'selected' : '' }}>Tertunda</option>
                                                <option value="in_progress" {{ $complaint->status === 'in_progress' ? 'selected' : '' }}>Dalam Proses</option>
                                                <option value="resolved" {{ $complaint->status === 'resolved' ? 'selected' : '' }}>Telah Selesai</option>
                                            </select>
                                        </div>

                                        <div>
                                            <label for="admin_notes" class="block text-sm font-medium text-gray-700">Catatan Admin</label>
                                            <textarea name="admin_notes" id="admin_notes" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500" placeholder="Tambahkan catatan untuk pengaduan ini...">{{ $complaint->admin_notes }}</textarea>
                                        </div>

                                        <button type="submit" class="w-full bg-primary-600 text-white py-2 px-4 rounded-md hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                                            Perbarui Status
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <!-- Delete Complaint -->
                            <div class="bg-red-50 border border-red-200 rounded-lg p-6 mt-6">
                                <h4 class="text-lg font-semibold text-red-900 mb-4">Bahaya</h4>
                                <p class="text-sm text-red-700 mb-4">
                                    Tindakan ini tidak dapat dibatalkan. Ini akan menghapus pengaduan secara permanen.
                                </p>
                                <form method="POST" action="{{ route('admin.complaints.destroy', $complaint) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengaduan ini secara permanen?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full bg-red-600 text-white py-2 px-4 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                                        Hapus Pengaduan
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
