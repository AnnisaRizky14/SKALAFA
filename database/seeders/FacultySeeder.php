<?php
// database/seeders/FacultySeeder.php

namespace Database\Seeders;

use App\Models\Faculty;
use Illuminate\Database\Seeder;

class FacultySeeder extends Seeder
{
    public function run(): void
    {
        $faculties = [
            [
                'name' => 'Fakultas Teknik',
                'short_name' => 'FT',
                'description' => 'Fakultas Teknik Universitas Bengkulu memiliki berbagai program studi teknik yang berkualitas.',
                'color' => '#ff6b35',
                'order' => 1,
            ],
            [
                'name' => 'Fakultas Hukum',
                'short_name' => 'FH',
                'description' => 'Fakultas Hukum Universitas Bengkulu menghasilkan sarjana hukum yang kompeten.',
                'color' => '#db0000ff',
                'order' => 2,
            ],
            [
                'name' => 'Fakultas Ekonomi dan Bisnis',
                'short_name' => 'FEB',
                'description' => 'Fakultas Ekonomi dan Bisnis mencetak ekonom dan pengusaha handal.',
                'color' => '#ffd900ff',
                'order' => 3,
            ],
            [
                'name' => 'Fakultas Pertanian',
                'short_name' => 'FP',
                'description' => 'Fakultas Pertanian berfokus pada pengembangan sektor pertanian modern.',
                'color' => '#117911ff',
                'order' => 4,
            ],
            [
                'name' => 'Fakultas Keguruan dan Ilmu Pendidikan',
                'short_name' => 'FKIP',
                'description' => 'FKIP mencetak tenaga pendidik profesional dan berkualitas.',
                'color' => '#979caaff',
                'order' => 5,
            ],
            [
                'name' => 'Fakultas Matematika dan Ilmu Pengetahuan Alam',
                'short_name' => 'FMIPA',
                'description' => 'FMIPA mengembangkan ilmu pengetahuan dasar dan terapan.',
                'color' => '#2ab8f0ff',
                'order' => 6,
            ],
            [
                'name' => 'Fakultas Ilmu Sosial dan Ilmu Politik',
                'short_name' => 'FISIP',
                'description' => 'FISIP mempelajari fenomena sosial dan politik dalam masyarakat.',
                'color' => '#522c08ff',
                'order' => 7,
            ],
            [
                'name' => 'Fakultas Kedokteran',
                'short_name' => 'FK',
                'description' => 'Fakultas Kedokteran mencetak dokter profesional untuk Indonesia.',
                'color' => '#a015b3ff',
                'order' => 8,
            ],
        ];

        foreach ($faculties as $faculty) {
            Faculty::create($faculty);
        }
    }
}