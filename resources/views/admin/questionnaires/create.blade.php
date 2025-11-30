<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Buat Kuisioner Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg rounded-xl">
                <div class="p-6">
                                            <form method="POST" action="{{ route('admin.questionnaires.prepare') }}">
                                                @csrf

                                                <!-- Faculty Selection -->
                                                <div class="mb-6">
                                                    <x-input-label for="faculty_id" :value="__('Fakultas')" />
                                                    <select id="faculty_id" name="faculty_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-primary focus:ring-primary" required>
                                                        <option value="">Pilih Fakultas</option>
                                                        @foreach($faculties as $faculty)
                                                            <option value="{{ $faculty->id }}">
                                                                {{ $faculty->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <x-input-error :messages="$errors->get('faculty_id')" class="mt-2" />
                                                </div>

                                                <!-- Sub Category Selection -->
                                                <div class="mb-6">
                                                    <x-input-label for="sub_category_id" :value="__('Kategori Kuisioner')" />
                                                    <select id="sub_category_id" name="sub_category_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-primary focus:ring-primary">
                                                        <option value="">Pilih Kategori (Opsional)</option>
                                                        @foreach($subCategories as $subCategory)
                                                            <option value="{{ $subCategory->id }}">
                                                                {{ $subCategory->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <x-input-error :messages="$errors->get('sub_category_id')" class="mt-2" />
                                                </div>

                                                <!-- Title -->
                                                <div class="mb-6">
                                                    <x-input-label for="title" :value="__('Judul Kuisioner')" />
                                                    <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title')" required autofocus />
                                                    <x-input-error :messages="$errors->get('title')" class="mt-2" />
                                                </div>

                                                <!-- Description -->
                                                <div class="mb-6">
                                                    <x-input-label for="description" :value="__('Deskripsi')" />
                                                    <textarea id="description" name="description" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-primary focus:ring-primary">{{ old('description') }}</textarea>
                                                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                                                </div>

                                                <!-- Estimated Duration -->
                                                <div class="mb-6">
                                                    <x-input-label for="estimated_duration" :value="__('Estimasi Durasi (menit)')" />
                                                    <x-text-input id="estimated_duration" class="block mt-1 w-full" type="number" name="estimated_duration" :value="old('estimated_duration', 10)" min="1" max="60" required />
                                                    <x-input-error :messages="$errors->get('estimated_duration')" class="mt-2" />
                                                </div>

                                                <!-- Active Status -->
                                                <div class="mb-6">
                                                    <x-input-label for="is_active" :value="__('Status Aktif')" />
                                                    <div class="mt-2">
                                                        <label class="flex items-center">
                                                            <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active') ? 'checked' : '' }} class="rounded border-gray-300 text-primary shadow-sm focus:border-primary focus:ring-primary">
                                                            <span class="ml-2 text-sm text-gray-600">Kuisioner aktif dan tersedia untuk diisi</span>
                                                        </label>
                                                    </div>
                                                    <x-input-error :messages="$errors->get('is_active')" class="mt-2" />
                                                </div>

                                                <!-- Start Date -->
                                                <div class="mb-6">
                                                    <x-input-label for="start_date" :value="__('Tanggal Mulai')" />
                                                    <x-text-input id="start_date" class="block mt-1 w-full" type="date" name="start_date" :value="old('start_date')" />
                                                    <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
                                                </div>

                                                <!-- End Date -->
                                                <div class="mb-6">
                                                    <x-input-label for="end_date" :value="__('Tanggal Berakhir')" />
                                                    <x-text-input id="end_date" class="block mt-1 w-full" type="date" name="end_date" :value="old('end_date')" />
                                                    <x-input-error :messages="$errors->get('end_date')" class="mt-2" />
                                                </div>

                                                <div class="flex items-center justify-end gap-4">
                                                    <a href="{{ route('admin.questionnaires.index') }}" class="text-gray-500 hover:text-gray-700">
                                                        {{ __('Batal') }}
                                                    </a>
                                                    <x-primary-button type="submit">
                                                        {{ __('Berikutnya') }}
                                                    </x-primary-button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </x-admin-layout>
