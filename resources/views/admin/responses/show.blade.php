<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detail Respon #{{ $response->id }}
            </h2>
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.responses.index') }}"
                   class="bg-gray-600 hover:bg-gray-700 text-white font-medium px-4 py-2 rounded-lg transition-colors duration-200">
                    Kembali ke Daftar
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-12">
            <!-- Response Overview -->
            <div class="bg-white overflow-hidden shadow-lg rounded-xl mb-6 p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Responden</label>
                        <p class="text-sm text-gray-900">Responden #{{ $response->id }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kuisioner</label>
                        <p class="text-sm text-gray-900">{{ $response->questionnaire->title }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Fakultas</label>
                        <p class="text-sm text-gray-900">{{ $response->questionnaire->faculty->name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai</label>
                        <p class="text-sm text-gray-900">{{ $response->completed_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>

                <!-- Participant Information -->
                @if($response->participant_info)
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <h4 class="text-sm font-semibold text-blue-800 mb-3 flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Informasi Peserta
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        @if(isset($response->participant_info['nama']) && $response->participant_info['nama'])
                        <div>
                            <label class="block text-xs font-medium text-blue-700 mb-1">Nama</label>
                            <p class="text-sm text-blue-900">{{ $response->participant_info['nama'] }}</p>
                        </div>
                        @endif
                        @if(isset($response->participant_info['email']) && $response->participant_info['email'])
                        <div>
                            <label class="block text-xs font-medium text-blue-700 mb-1">Email</label>
                            <p class="text-sm text-blue-900">{{ $response->participant_info['email'] }}</p>
                        </div>
                        @endif
                        @if(isset($response->participant_info['status']) && $response->participant_info['status'])
                        <div>
                            <label class="block text-xs font-medium text-blue-700 mb-1">Status</label>
                            <p class="text-sm text-blue-900">{{ $response->participant_info['status'] }}</p>
                        </div>
                        @endif
                        @if(isset($response->participant_info['fakultas']) && $response->participant_info['fakultas'])
                        <div>
                            <label class="block text-xs font-medium text-blue-700 mb-1">Fakultas</label>
                            <p class="text-sm text-blue-900">{{ \App\Models\Faculty::find($response->participant_info['fakultas'])->name ?? 'Tidak ditemukan' }}</p>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Rating Rata-rata</label>
                        @php $avgRating = $response->getAverageRating() @endphp
                        <div class="flex items-center">
                            <div class="flex text-yellow-400 mr-2">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= floor($avgRating))
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                    @else
                                        <svg class="w-5 h-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                    @endif
                                @endfor
                            </div>
                            <span class="text-sm text-gray-900">{{ number_format($avgRating, 1) }}/5</span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tingkat Kepuasan</label>
                        @php $satisfaction = $response->getSatisfactionLevel() @endphp
                        @if($satisfaction == 'Sangat Puas')
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                {{ $satisfaction }}
                            </span>
                        @elseif($satisfaction == 'Puas')
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ $satisfaction }}
                            </span>
                        @elseif($satisfaction == 'Cukup Puas')
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                {{ $satisfaction }}
                            </span>
                        @elseif($satisfaction == 'Tidak Puas')
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800">
                                {{ $satisfaction }}
                            </span>
                        @else
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                {{ $satisfaction }}
                            </span>
                        @endif
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Durasi</label>
                        <p class="text-sm text-gray-900">{{ $response->getDurationInMinutes() }} menit</p>
                    </div>
                </div>
            </div>

            <!-- Answers Grouped by Subcategory -->
            @foreach($subcategories as $subcategory)
                @if(isset($answersGrouped[$subcategory->id]))
                    <div class="bg-white overflow-hidden shadow-lg rounded-xl mb-6">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <h3 class="text-lg font-medium text-gray-900">{{ $subcategory->name }}</h3>
                            <p class="text-sm text-gray-500">{{ $subcategory->description ?? '' }}</p>
                        </div>
                        <div class="p-6">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pertanyaan</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jawaban</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rating</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($answersGrouped[$subcategory->id] as $answer)
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-6 py-4 whitespace-pre-wrap text-sm text-gray-900">
                                                    {{ $answer->question->question_text }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-pre-wrap text-sm text-gray-900">
                                                    {{ $answer->answer ?? 'Tidak dijawab' }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    @if($answer->rating)
                                                        <div class="flex items-center">
                                                            <div class="flex text-yellow-400 mr-2">
                                                                @for($i = 1; $i <= 5; $i++)
                                                                    @if($i <= $answer->rating)
                                                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                                        </svg>
                                                                    @else
                                                                        <svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                                        </svg>
                                                                    @endif
                                                                @endfor
                                                            </div>
                                                            <span>{{ $answer->rating }}/5</span>
                                                        </div>
                                                    @else
                                                        <span class="text-gray-500">Tidak ada rating</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach

            @if($subcategories->isEmpty())
                <div class="bg-white overflow-hidden shadow-lg rounded-xl p-6 text-center">
                    <p class="text-gray-500">Tidak ada jawaban untuk respon ini.</p>
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>
