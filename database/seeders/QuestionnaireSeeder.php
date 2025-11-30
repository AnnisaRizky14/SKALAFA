<?php

namespace Database\Seeders;

use App\Models\Faculty;
use App\Models\Questionnaire;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class QuestionnaireSeeder extends Seeder
{
    public function run(): void
    {
        $faculties = Faculty::all();

        foreach ($faculties as $faculty) {
            // Create 2-3 questionnaires per faculty
            for ($i = 1; $i <= 2; $i++) {
                Questionnaire::create([
                    'faculty_id' => $faculty->id,
                    'title' => "Survei Kepuasan Layanan {$faculty->name} - Periode " . ($i == 1 ? 'Ganjil' : 'Genap') . " 2024",
                    'description' => "Survei untuk mengevaluasi kualitas layanan akademik dan non-akademik di {$faculty->name}",
                    // 'instructions' => "Mohon berikan penilaian yang objektif terhadap layanan yang telah Anda terima. Penilaian Anda sangat berharga untuk peningkatan kualitas layanan fakultas.\n\nSkala Penilaian:\n1 = Sangat Tidak Puas\n2 = Tidak Puas\n3 = Cukup Puas\n4 = Puas\n5 = Sangat Puas\n\nSemua informasi yang Anda berikan akan dijaga kerahasiaannya.",
                    'estimated_duration' => rand(8, 15),
                    'is_active' => $i == 1, // Only first questionnaire is active
                    'start_date' => Carbon::now()->subDays(30),
                    'end_date' => Carbon::now()->addDays(60),
                ]);
            }
        }
    }
}