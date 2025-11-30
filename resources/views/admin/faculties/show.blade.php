<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detail Fakultas: {{ $faculty->name }}
            </h2>
            <div class="flex items-center space-x-2">
                <a href="{{ route('admin.faculties.edit', $faculty) }}"
                   class="bg-yellow-600 hover:bg-yellow-700 text-white font-medium px-4 py-2 rounded-lg transition-colors duration-200">
                    Edit Fakultas
                </a>
                <a href="{{ route('admin.faculties.index') }}"
                   class="bg-gray-600 hover:bg-gray-700 text-white font-medium px-4 py-2 rounded-lg transition-colors duration-200">
                    Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Faculty Information -->
                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-lg rounded-xl">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-6">Informasi Fakultas</h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Logo -->
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Logo</label>
                                    @if($faculty->logo)
                                        <img src="{{ asset('storage/images/faculties/' . $faculty->logo) }}"
                                             alt="{{ $faculty->name }}"
                                             class="h-24 w-24 object-cover rounded-lg border border-gray-200">
                                    @else
                                        <div class="h-24 w-24 rounded-lg flex items-center justify-center text-white font-bold text-2xl border border-gray-200"
                                             style="background-color: {{ $faculty->color }}">
                                            {{ substr($faculty->name, 0, 1) }}
                                        </div>
                                    @endif
                                </div>

                                <!-- Name -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Fakultas</label>
                                    <p class="text-gray-900">{{ $faculty->name }}</p>
                                </div>

                                <!-- Short Name -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Kode Fakultas</label>
                                    <p class="text-gray-900">{{ $faculty->short_name ?? '-' }}</p>
                                </div>

                                <!-- Color -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Warna Tema</label>
                                    <div class="flex items-center space-x-2">
                                        <div class="w-6 h-6 rounded border border-gray-300" style="background-color: {{ $faculty->color }}"></div>
                                        <span class="text-gray-900">{{ $faculty->color }}</span>
                                    </div>
                                </div>

                                <!-- Status -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                                    @if($faculty->is_active)
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                            Tidak Aktif
                                        </span>
                                    @endif
                                </div>

                                <!-- Order -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Urutan</label>
                                    <p class="text-gray-900">{{ $faculty->order }}</p>
                                </div>

                                <!-- Description -->
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                                    <p class="text-gray-900">{{ $faculty->description ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistics -->
                <div>
                    <div class="bg-white overflow-hidden shadow-lg rounded-xl">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-6">Statistik</h3>

                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Total Kuisioner</span>
                                    <span class="text-lg font-semibold text-gray-900">{{ $faculty->questionnaires->count() }}</span>
                                </div>

                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Total Responden</span>
                                    <span class="text-lg font-semibold text-gray-900">{{ $stats['total_responses'] ?? 0 }}</span>
                                </div>

                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Rata-rata Kepuasan</span>
                                    <span class="text-lg font-semibold text-gray-900">{{ number_format($stats['average_satisfaction'] ?? 0, 1) }}%</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-white overflow-hidden shadow-lg rounded-xl mt-6">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Aksi Cepat</h3>

                            <div class="space-y-3">
                                <a href="{{ route('admin.faculties.edit', $faculty) }}"
                                   class="w-full bg-yellow-600 hover:bg-yellow-700 text-white font-medium px-4 py-2 rounded-lg transition-colors duration-200 text-center block">
                                    Edit Fakultas
                                </a>

                                @if(auth()->user()->isSuperAdmin())
                                <form method="POST" action="{{ route('admin.faculties.destroy', $faculty) }}"
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus fakultas ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="w-full bg-red-600 hover:bg-red-700 text-white font-medium px-4 py-2 rounded-lg transition-colors duration-200">
                                        Hapus Fakultas
                                    </button>
                                </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Questionnaires List -->
            <div class="bg-white overflow-hidden shadow-lg rounded-xl mt-6">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-6">Kuisioner Fakultas</h3>

                    @if($questionnaires->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Kuisioner
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Responden
                                        </th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Aksi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($questionnaires as $questionnaire)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $questionnaire->title }}
                                            </div>
                                            @if($questionnaire->description)
                                                <div class="text-sm text-gray-500">
                                                    {{ Str::limit($questionnaire->description, 50) }}
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($questionnaire->is_active)
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                    Aktif
                                                </span>
                                            @else
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                                    Tidak Aktif
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $questionnaire->responses->count() }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('admin.questionnaires.show', $questionnaire) }}"
                                               class="text-blue-600 hover:text-blue-900">
                                                Lihat Detail
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if($questionnaires->hasPages())
                        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6 mt-4">
                            {{ $questionnaires->links() }}
                        </div>
                        @endif
                    @else
                        <div class="text-center py-12">
                            <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada kuisioner</h3>
                            <p class="text-gray-500">Fakultas ini belum memiliki kuisioner yang terkait.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
