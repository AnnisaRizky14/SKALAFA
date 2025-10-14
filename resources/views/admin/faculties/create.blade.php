<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Tambah Fakultas
            </h2>
            <a href="{{ route('admin.faculties.index') }}"
               class="bg-gray-600 hover:bg-gray-700 text-white font-medium px-4 py-2 rounded-lg transition-colors duration-200">
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg rounded-xl">
                <form method="POST" action="{{ route('admin.faculties.store') }}" enctype="multipart/form-data" class="p-6">
                    @csrf

                    <!-- Name -->
                    <div class="mb-6">
                        <x-input-label for="name" value="Nama Fakultas" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                                      value="{{ old('name') }}" required />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <!-- Short Name -->
                    <div class="mb-6">
                        <x-input-label for="short_name" value="Kode Fakultas" />
                        <x-text-input id="short_name" name="short_name" type="text" class="mt-1 block w-full"
                                      value="{{ old('short_name') }}" placeholder="Contoh: FTI, FEB, dll." />
                        <x-input-error :messages="$errors->get('short_name')" class="mt-2" />
                        <p class="mt-1 text-sm text-gray-500">Kode singkat untuk fakultas (opsional)</p>
                    </div>

                    <!-- Description -->
                    <div class="mb-6">
                        <x-input-label for="description" value="Deskripsi" />
                        <textarea id="description" name="description" rows="4"
                                  class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                  placeholder="Deskripsi singkat tentang fakultas...">{{ old('description') }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>

                    <!-- Color -->
                    <div class="mb-6">
                        <x-input-label for="color" value="Warna Tema" />
                        <div class="flex items-center mt-1">
                            <input id="color" name="color" type="color" class="h-10 w-16 border border-gray-300 rounded-l-md"
                                   value="{{ old('color', '#3B82F6') }}" required />
                            <input type="text" id="color_hex" class="flex-1 border border-l-0 border-gray-300 rounded-r-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                   value="{{ old('color', '#3B82F6') }}" readonly />
                        </div>
                        <x-input-error :messages="$errors->get('color')" class="mt-2" />
                        <p class="mt-1 text-sm text-gray-500">Pilih warna tema untuk fakultas</p>
                    </div>

                    <!-- Logo -->
                    <div class="mb-6">
                        <x-input-label for="logo" value="Logo Fakultas" />
                        <input id="logo" name="logo" type="file" accept="image/*"
                               class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
                        <x-input-error :messages="$errors->get('logo')" class="mt-2" />
                        <p class="mt-1 text-sm text-gray-500">Upload logo fakultas (JPEG, PNG, JPG - maksimal 2MB)</p>
                        <div id="logo_preview" class="mt-3 hidden">
                            <img id="preview_img" src="" alt="Preview" class="h-20 w-20 object-cover rounded-lg border">
                        </div>
                    </div>

                    <!-- Is Active -->
                    <div class="mb-6">
                        <label class="flex items-center">
                            <input id="is_active" name="is_active" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                   {{ old('is_active', true) ? 'checked' : '' }}>
                            <span class="ml-2 text-sm text-gray-600">Aktifkan fakultas ini</span>
                        </label>
                        <p class="mt-1 text-sm text-gray-500">Fakultas yang aktif akan ditampilkan di survey</p>
                    </div>

                    <!-- Order -->
                    <div class="mb-6">
                        <x-input-label for="order" value="Urutan" />
                        <x-text-input id="order" name="order" type="number" class="mt-1 block w-full"
                                      value="{{ old('order', 0) }}" min="0" />
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
                            Simpan Fakultas
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

        // Logo preview
        document.getElementById('logo').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('logo_preview');
            const previewImg = document.getElementById('preview_img');

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    preview.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            } else {
                preview.classList.add('hidden');
            }
        });
    </script>
</x-admin-layout>
