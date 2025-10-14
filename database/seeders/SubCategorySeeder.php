<?php
// database/seeders/SubCategorySeeder.php

namespace Database\Seeders;

use App\Models\SubCategory;
use Illuminate\Database\Seeder;

class SubCategorySeeder extends Seeder
{
    public function run(): void
    {
        $subcategories = [
            [
                'name' => 'Infrastruktur',
                'description' => 'Pertanyaan terkait fasilitas fisik dan infrastruktur fakultas',
                'order' => 1,
            ],
            [
                'name' => 'Layanan',
                'description' => 'Pertanyaan terkait kualitas pelayanan akademik dan administrasi',
                'order' => 2,
            ],
            [
                'name' => 'Akademik',
                'description' => 'Pertanyaan terkait proses pembelajaran dan kurikulum',
                'order' => 3,
            ],
            [
                'name' => 'Fasilitas',
                'description' => 'Pertanyaan terkait fasilitas penunjang pembelajaran',
                'order' => 4,
            ],
            [
                'name' => 'Sumber Daya Manusia',
                'description' => 'Pertanyaan terkait kualitas dosen dan tenaga kependidikan',
                'order' => 5,
            ],
        ];

        foreach ($subcategories as $subcategory) {
            SubCategory::create($subcategory);
        }
    }
}