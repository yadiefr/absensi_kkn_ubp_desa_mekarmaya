<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Default Settings
        $settings = [
            ['key' => 'morning_start_time', 'value' => '06:00'],
            ['key' => 'morning_end_time', 'value' => '10:00'],
            ['key' => 'night_start_time', 'value' => '18:00'],
            ['key' => 'night_end_time', 'value' => '22:00'],
            ['key' => 'posko_latitude', 'value' => '-6.200000'],
            ['key' => 'posko_longitude', 'value' => '106.816666'],
            ['key' => 'radius_meters', 'value' => '50'],
        ];

        foreach ($settings as $setting) {
            \App\Models\Setting::create($setting);
        }

        // Admin
        $this->call([
            AdminSeeder::class,
        ]);

        $students = [
            ['prodi' => 'Teknik Mesin', 'nim' => '23416221201110', 'name' => 'MOCH AUGIE KAROM APRILIANSYAH'],
            ['prodi' => 'Teknik Industri', 'nim' => '21416226201046', 'name' => 'SEPTIAN RABBANI'],
            ['prodi' => 'Teknik Industri', 'nim' => '23416226201149', 'name' => 'ALDI ROHALDI'],
            ['prodi' => 'Teknik Industri', 'nim' => '25416226201423', 'name' => 'SONI'],
            ['prodi' => 'Farmasi', 'nim' => '23416248201105', 'name' => 'RESTRI PUTRI NIMARLIANA'],
            ['prodi' => 'Farmasi', 'nim' => '23416248201131', 'name' => 'ICHA HANITA'],
            ['prodi' => 'Teknik Informatika', 'nim' => '23416255201053', 'name' => 'MUHAMMAD DIRA'],
            ['prodi' => 'Teknik Informatika', 'nim' => '23416255201236', 'name' => 'PITRIYADI'],
            ['prodi' => 'Sistem Informasi', 'nim' => '23416257201058', 'name' => 'TAHARA ABIZAR RAMADHAN GURNING'],
            ['prodi' => 'Manajemen', 'nim' => '23416261201061', 'name' => 'NISA AULIA RAMADHANI'],
            ['prodi' => 'Manajemen', 'nim' => '23416261201157', 'name' => 'DELIA CANTIKA MAHENDRA'],
            ['prodi' => 'Manajemen', 'nim' => '23416261201241', 'name' => 'FALERINA SOCA'],
            ['prodi' => 'Manajemen', 'nim' => '23416261201340', 'name' => 'JIYAN TASYA RIZQIYAH'],
            ['prodi' => 'Akuntansi', 'nim' => '23416262201138', 'name' => 'NUR AZIZAH'],
            ['prodi' => 'Psikologi', 'nim' => '23416273201224', 'name' => 'SYIFAA HIRTA UTAMI'],
            ['prodi' => 'Psikologi', 'nim' => '23416273201242', 'name' => 'DITA FADILAH'],
            ['prodi' => 'Psikologi', 'nim' => '23416273201306', 'name' => 'DINDA KHAIRUNISA'],
            ['prodi' => 'Ilmu Hukum', 'nim' => '23416274201134', 'name' => 'RISMA AMELIA'],
            ['prodi' => 'Pendidikan Guru Sekolah Dasar', 'nim' => '23416286206113', 'name' => 'RATU INDAH HALIMATUSSADIAH'],
        ];

        foreach ($students as $student) {
            User::create([
                'name' => ucwords(strtolower($student['name'])),
                'password' => Hash::make('password'),
                'role' => 'mahasiswa',
                'nim' => $student['nim'],
                'prodi' => $student['prodi'],
            ]);
        }
    }
}
