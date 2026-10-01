<?php

namespace Database\Seeders;

use App\Models\Anak;
use App\Models\Desa;
use App\Models\Faskes;
use App\Models\Sekolah;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seeder ini membuat:
     * - 1 akun Admin Kecamatan
     * - 5 Desa
     * - 2 Faskes per desa (Puskesmas + Posyandu)
     * - 2-3 Sekolah per desa (jenjang acak SD/SMP/SMA)
     * - 1 akun User Sekolah per sekolah
     * - 10-20 siswa per sekolah (tanggal lahir sesuai rentang umur jenjangnya)
     * - 8-15 data balita per desa (usia 0-5 th) untuk data stunting,
     *   ~25% di antaranya berstatus "Stunting"
     */
    public function run(): void
    {
        // 1. Akun Admin Kecamatan
        User::create([
            'name' => 'Admin Kecamatan',
            'email' => 'admin@kecamatan.go.id',
            'password' => Hash::make('password'),
            'role' => 'admin_kecamatan',
        ]);

        // 2. Data Desa
        $namaDesa = [
            'Desa Sukamaju',
            'Desa Mekarsari',
            'Desa Tanjung Harapan',
            'Desa Sumberrejo',
            'Desa Cempaka Putih',
        ];

        $desas = collect($namaDesa)->map(fn ($nama) => Desa::create(['nama_desa' => $nama]));

        $jenjangList = ['SD', 'SMP', 'SMA'];

        foreach ($desas as $desa) {
            // 3. Fasilitas Kesehatan per desa
            Faskes::create([
                'desa_id' => $desa->id,
                'nama_faskes' => 'Puskesmas '.$desa->nama_desa,
                'jenis' => 'Puskesmas',
                'jumlah_tenaga_medis' => rand(5, 15),
            ]);

            Faskes::create([
                'desa_id' => $desa->id,
                'nama_faskes' => 'Posyandu '.$desa->nama_desa,
                'jenis' => 'Posyandu',
                'jumlah_tenaga_medis' => rand(2, 6),
            ]);

            // 4. Sekolah per desa (2-3 sekolah, jenjang acak)
            $jumlahSekolah = rand(2, 3);

            for ($i = 1; $i <= $jumlahSekolah; $i++) {
                $jenjang = $jenjangList[array_rand($jenjangList)];

                $sekolah = Sekolah::create([
                    'desa_id' => $desa->id,
                    'nama_sekolah' => $jenjang.' Negeri '.$i.' '.$desa->nama_desa,
                    'jenjang' => $jenjang,
                    'jumlah_guru' => rand(6, 20),
                    // Total Siswa = jumlah_siswa_l + jumlah_siswa_p, diinput langsung (bukan hitung baris Anak)
                    'jumlah_siswa_l' => rand(40, 120),
                    'jumlah_siswa_p' => rand(40, 120),
                ]);

                // 5. Akun User Sekolah untuk sekolah ini
                $emailSlug = str()->slug($sekolah->nama_sekolah);

                User::create([
                    'name' => 'Operator '.$sekolah->nama_sekolah,
                    'email' => $emailSlug.'@sekolah.go.id',
                    'password' => Hash::make('password'),
                    'role' => 'user_sekolah',
                    'sekolah_id' => $sekolah->id,
                ]);
            }

            // 6. Data Balita (0-5 tahun, tanpa sekolah) — untuk data stunting
            $jumlahBalita = rand(8, 15);

            for ($b = 1; $b <= $jumlahBalita; $b++) {
                $umurBulan = rand(1, 60);
                $tanggalLahir = now()->subMonths($umurBulan)->subDays(rand(0, 27));

                Anak::create([
                    'sekolah_id' => null,
                    'desa_id' => $desa->id,
                    'nama_anak' => fake('id_ID')->name(),
                    'tanggal_lahir' => $tanggalLahir,
                    'jenis_kelamin' => fake()->randomElement(['L', 'P']),
                    'status_stunting' => fake()->boolean(25) ? 'Stunting' : 'Normal',
                ]);
            }
        }
    }
}
