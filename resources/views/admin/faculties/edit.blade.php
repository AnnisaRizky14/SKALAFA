<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edit Fakultas: {{ $faculty->name }}
            </h2>
            <div class="flex items-center space-x-2">
                <a href="{{ route('admin.faculties.show', $faculty) }}"
                   class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-lg transition-colors duration-200">
                    Lihat Detail
                </a>
                <a href="{{ route('admin.faculties.index') }}"
                   class="bg-gray-600 hover:bg-gray-700 text-white font-medium px-4 py-2 rounded-lg transition-colors duration-200">
                    Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg rounded-xl">
                <form method="POST" action="{{ route('admin.faculties.update', $faculty) }}" enctype="multipart/form-data" class="p-6">
                    @csrf
                    @method('PUT')

                    <!-- Name -->
                    <div class="mb-6">
                        <x-input-label for="name" value="Nama Fakultas" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                                      value="{{ old('name', $faculty->name) }}" required />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <!-- Short Name -->
                    <div class="mb-6">
                        <x-input-label for="short_name" value="Kode Fakultas" />
                        <x-text-input id="short_name" name="short_name" type="text" class="mt-1 block w-full"
                                      value="{{ old('short_name', $faculty->short_name) }}" placeholder="Contoh: FTI, FEB, dll." />
                        <x-input-error :messages="$errors->get('short_name')" class="mt-2" />
                        <p class="mt-1 text-sm text-gray-500">Kode singkat untuk fakultas (opsional)</p>
                    </div>

                    <!-- Description -->
                    <div class="mb-6">
                        <x-input-label for="description" value="Deskripsi" />
                        <textarea id="description" name="description" rows="4"
                                  class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                  placeholder="Deskripsi singkat tentang fakultas...">{{ old('description', $faculty->description) }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>

                    <!-- Color -->
                    <div class="mb-6">
                        <x-input-label for="color" value="Warna Tema" />
                        <div class="flex items-center mt-1">
                            <input id="color" name="color" type="color" class="h-10 w-16 border border-gray-300 rounded-l-md"
                                   value="{{ old('color', $faculty->color) }}" required />
                            <input type="text" id="color_hex" class="flex-1 border border-l-0 border-gray-300 rounded-r-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                   value="{{ old('color', $faculty->color) }}" readonly />
                        </div>
                        <x-input-error :messages="$errors->get('color')" class="mt-2" />
                        <p class="mt-1 text-sm text-gray-500">Pilih warna tema untuk fakultas</p>
                    </div>



                    <!-- Is Active -->
                    <div class="mb-6">
                        <label class="flex items-center">
                            <input type="hidden" name="is_active" value="0">
                            <input id="is_active" name="is_active" type="checkbox" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                   {{ old('is_active', $faculty->is_active) ? 'checked' : '' }}>
                            <span class="ml-2 text-sm text-gray-600">Aktifkan fakultas ini</span>
                        </label>
                        <p class="mt-1 text-sm text-gray-500">Fakultas yang aktif akan ditampilkan di survey</p>
                    </div>

                    <!-- Order -->
                    <div class="mb-6">
                        <x-input-label for="order" value="Urutan" />
                        <x-text-input id="order" name="order" type="number" class="mt-1 block w-full"
                                      value="{{ old('order', $faculty->order) }}" min="0" />
                        <x-input-error :messages="$errors->get('order')" class="mt-2" />
                        <p class="mt-1 text-sm text-gray-500">Urutan tampilan fakultas (0 = pertama)</p>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex items-center justify-end pt-6 border-t border-gray-200">
                        <a href="{{ route('admin.faculties.index') }}"
                           class="mr-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium px-4 py-2 rounded-lg transition-colors duration-200">
                            Batal
                        </a>
                        <x-primary-button>
                            Update Fakultas
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Color picker synchronization
        document.getElementById('color').addEventListener('input', function() {
            document.getElementById('color_hex').value = this.value.toUpperCase();
        });

        document.getElementById('color_hex').addEventListener('input', function() {
            document.getElementById('color').value = this.value;
        });


    </script>
</x-admin-layout>
