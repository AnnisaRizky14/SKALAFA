<?php

namespace Database\Seeders;

use App\Models\Questionnaire;
use App\Models\Question;
use App\Models\SubCategory;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    public function run(): void
    {
        $questionnaires = Questionnaire::all();
        $subcategories = SubCategory::all();

        $questionTemplates = [
            1 => [ // Infrastruktur
                'Bagaimana penilaian Anda terhadap kondisi gedung dan ruang kuliah di fakultas ini?',
                'Bagaimana penilaian Anda terhadap fasilitas laboratorium yang tersedia?',
                'Bagaimana penilaian Anda terhadap kondisi toilet dan fasilitas sanitasi?',
                'Bagaimana penilaian Anda terhadap ketersediaan dan kondisi tempat parkir?',
                'Bagaimana penilaian Anda terhadap fasilitas internet dan WiFi di fakultas?',
                'Bagaimana penilaian Anda terhadap pencahayaan dan ventilasi di ruang kuliah?',
                'Bagaimana penilaian Anda terhadap kebersihan lingkungan fakultas secara keseluruhan?',
            ],
            2 => [ // Layanan
                'Bagaimana penilaian Anda terhadap pelayanan administrasi akademik (KRS, KHS, dll)?',
                'Bagaimana penilaian Anda terhadap kecepatan pelayanan di bagian administrasi?',
                'Bagaimana penilaian Anda terhadap keramahan staff administrasi fakultas?',
                'Bagaimana penilaian Anda terhadap kemudahan akses informasi akademik?',
                'Bagaimana penilaian Anda terhadap pelayanan perpustakaan fakultas?',
                'Bagaimana penilaian Anda terhadap pelayanan bimbingan konseling mahasiswa?',
                'Bagaimana penilaian Anda terhadap responsivitas faculty dalam menangani keluhan?',
            ],
            3 => [ // Akademik
                'Bagaimana penilaian Anda terhadap kualitas pengajaran dosen di fakultas ini?',
                'Bagaimana penilaian Anda terhadap relevansi kurikulum dengan kebutuhan industri?',
                'Bagaimana penilaian Anda terhadap variasi metode pembelajaran yang digunakan?',
                'Bagaimana penilaian Anda terhadap ketersediaan bahan ajar dan referensi?',
                'Bagaimana penilaian Anda terhadap sistem evaluasi dan penilaian yang diterapkan?',
                'Bagaimana penilaian Anda terhadap bimbingan akademik yang diberikan dosen?',
                'Bagaimana penilaian Anda terhadap kesempatan untuk penelitian dan publikasi?',
            ],
            4 => [ // Fasilitas
                'Bagaimana penilaian Anda terhadap fasilitas komputer dan akses internet?',
                'Bagaimana penilaian Anda terhadap koleksi buku dan jurnal di perpustakaan?',
                'Bagaimana penilaian Anda terhadap fasilitas olahraga yang tersedia?',
                'Bagaimana penilaian Anda terhadap kantin dan fasilitas konsumsi?',
                'Bagaimana penilaian Anda terhadap ruang diskusi dan belajar mandiri?',
                'Bagaimana penilaian Anda terhadap fasilitas untuk kegiatan organisasi mahasiswa?',
                'Bagaimana penilaian Anda terhadap aksesibilitas fasilitas bagi difabel?',
            ],
            5 => [ // Sumber Daya Manusia
                'Bagaimana penilaian Anda terhadap kompetensi dan keahlian dosen?',
                'Bagaimana penilaian Anda terhadap kedisiplinan dosen dalam mengajar?',
                'Bagaimana penilaian Anda terhadap kemampuan komunikasi dosen?',
                'Bagaimana penilaian Anda terhadap profesionalisme staff administrasi?',
                'Bagaimana penilaian Anda terhadap dukungan dosen dalam pengembangan soft skills?',
                'Bagaimana penilaian Anda terhadap ketersediaan dosen untuk konsultasi?',
                'Bagaimana penilaian Anda terhadap kepemimpinan dan manajemen fakultas?',
            ],
        ];

        foreach ($questionnaires as $questionnaire) {
            $questionOrder = 1;

            foreach ($subcategories as $subcategory) {
                $questions = $questionTemplates[$subcategory->id] ?? [];
                
                foreach ($questions as $questionText) {
                    Question::create([
                        'questionnaire_id' => $questionnaire->id,
                        'sub_category_id' => $subcategory->id,
                        'question_text' => $questionText,
                        'order' => $questionOrder++,
                        'is_required' => true,
                    ]);
                }
            }
        }
    }
}