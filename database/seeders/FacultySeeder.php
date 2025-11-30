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
                'description' => 'Mengembangkan inovasi rekayasa untuk kebutuhan teknis dan Teknologi Industri modern.',
                'color' => '#ff6b35',
                'order' => 1,
            ],
            [
                'name' => 'Fakultas Hukum',
                'short_name' => 'FH',
                'description' => 'Mengembangkan pemahaman hukum berintegritas untuk kajian regulasi dan etika.',
                'color' => '#db0000ff',
                'order' => 2,
            ],
            [
                'name' => 'Fakultas Ekonomi dan Bisnis',
                'short_name' => 'FEB',
                'description' => 'Mengembangkan strategi ekonomi yang adaptif dan mendukung kegiatan dibidang usaha.',
                'color' => '#ffd900ff',
                'order' => 3,
            ],
            [
                'name' => 'Fakultas Pertanian',
                'short_name' => 'FP',
                'description' => 'Mengembangkan ilmu agraria terapan untuk peningkatan produksi dan keberlanjutan.',
                'color' => '#117911ff',
                'order' => 4,
            ],
            [
                'name' => 'Fakultas Keguruan dan Ilmu Pendidikan',
                'short_name' => 'FKIP',
                'description' => 'Mengembangkan kompetensi pedagogik untuk mendukung proses pembelajaran.',
                'color' => '#979caaff',
                'order' => 5,
            ],
            [
                'name' => 'Fakultas Matematika dan Ilmu Pengetahuan Alam',
                'short_name' => 'FMIPA',
                'description' => 'Mengembangkan riset sains terapan untuk memperkuat dasar teoretis dan aplikatif.',
                'color' => '#2ab8f0ff',
                'order' => 6,
            ],
            [
                'name' => 'Fakultas Ilmu Sosial dan Ilmu Politik',
                'short_name' => 'FISIP',
                'description' => 'Mengembangkan analisis sosial kritis untuk memahami dinamika masyarakat.',
                'color' => '#522c08ff',
                'order' => 7,
            ],
            [
                'name' => 'Fakultas Kedokteran dan Ilmu Kesehatan',
                'short_name' => 'FKIK',
                'description' => 'Mengembangkan ilmu kesehatan, meningkatkan kualitas layanan medis.',
                'color' => '#a015b3ff',
                'order' => 8,
            ],
        ];

        foreach ($faculties as $faculty) {
            Faculty::create($faculty);
        }
    }
}