<?php

namespace Database\Seeders;

use App\Models\Organisasi;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Roles & Permissions
        $this->call(RoleSeeder::class);

        // 2. Kategori Pengaduan
        $this->call(KategoriPengaduanSeeder::class);

        // 3. Buat data Organisasi (HMPS & UKM)
        $hmpsTE = Organisasi::firstOrCreate(
            ['nama' => 'Himpunan Mahasiswa Teknik Elektro'],
            [
                'nama'        => 'Himpunan Mahasiswa Teknik Elektro',
                'singkatan'   => 'HMTE',
                'tipe'        => 'HMPS',
                'prodi_terkait' => 'Teknik Elektro',
                'deskripsi'   => 'Himpunan Mahasiswa Program Studi Teknik Elektro Politeknik Negeri Medan.',
                'is_active'   => true,
            ]
        );

        $hmpsTI = Organisasi::firstOrCreate(
            ['nama' => 'Himpunan Mahasiswa Teknik Informatika'],
            [
                'nama'        => 'Himpunan Mahasiswa Teknik Informatika',
                'singkatan'   => 'HMTI',
                'tipe'        => 'HMPS',
                'prodi_terkait' => 'Teknik Informatika',
                'deskripsi'   => 'Himpunan Mahasiswa Program Studi Teknik Informatika Politeknik Negeri Medan.',
                'is_active'   => true,
            ]
        );

        $ukmPramuka = Organisasi::firstOrCreate(
            ['nama' => 'UKM Pramuka Polmed'],
            [
                'nama'        => 'UKM Pramuka Polmed',
                'singkatan'   => 'Pramuka',
                'tipe'        => 'UKM',
                'prodi_terkait' => null,
                'deskripsi'   => 'Unit Kegiatan Mahasiswa Pramuka Politeknik Negeri Medan.',
                'is_active'   => true,
            ]
        );

        $ukmOlahraga = Organisasi::firstOrCreate(
            ['nama' => 'UKM Olahraga Polmed'],
            [
                'nama'        => 'UKM Olahraga Polmed',
                'singkatan'   => 'UKM-OR',
                'tipe'        => 'UKM',
                'prodi_terkait' => null,
                'deskripsi'   => 'Unit Kegiatan Mahasiswa Olahraga Politeknik Negeri Medan.',
                'is_active'   => true,
            ]
        );

        $this->command->info('✅ 4 Organisasi dibuat: 2 HMPS + 2 UKM');

        // 4. Buat akun Admin default
        $admin = User::firstOrCreate(
            ['username' => 'admin'],
            [
                'nama'        => 'Administrator',
                'name'        => 'Administrator',
                'email'       => 'admin@dpm-polmed.ac.id',
                'password'    => Hash::make('password123'),
                'is_active'   => true,
            ],
        );
        $admin->assignRole('admin');

        // 5. Buat akun Staff Dewan
        $staff = User::firstOrCreate(
            ['username' => 'staffdewan'],
            [
                'nama'        => 'Staff Dewan Perwakilan',
                'name'        => 'Staff Dewan Perwakilan',
                'email'       => 'staff@dpm-polmed.ac.id',
                'password'    => Hash::make('password123'),
                'is_active'   => true,
            ],
        );
        $staff->assignRole('staff_dewan');

        // 5.1 Buat akun Direktur
        $direktur = User::firstOrCreate(
            ['username' => 'direktur'],
            [
                'nama'        => 'Direktur Utama',
                'name'        => 'Direktur Utama',
                'email'       => 'direktur@polmed.ac.id',
                'password'    => Hash::make('password123'),
                'is_active'   => true,
            ],
        );
        $direktur->assignRole('direktur');

        // 5.2 Buat akun Wakil Direktur
        $wakilDirektur = User::firstOrCreate(
            ['username' => 'wakildirektur'],
            [
                'nama'        => 'Wakil Direktur',
                'name'        => 'Wakil Direktur',
                'email'       => 'wadir@polmed.ac.id',
                'password'    => Hash::make('password123'),
                'is_active'   => true,
            ],
        );
        $wakilDirektur->assignRole('wakil_direktur');

        // 6. Buat akun HMPS — Teknik Elektro
        $hmpsTeAkun = User::firstOrCreate(
            ['username' => 'hmte'],
            [
                'nama'          => 'HMPS Teknik Elektro',
                'name'          => 'HMPS Teknik Elektro',
                'email'         => 'hmte@dpm-polmed.ac.id',
                'password'      => Hash::make('password123'),
                'organisasi_id' => $hmpsTE->id,
                'is_active'     => true,
            ],
        );
        $hmpsTeAkun->assignRole('hmps');

        // 7. Buat akun HMPS — Teknik Informatika
        $hmpsTiAkun = User::firstOrCreate(
            ['username' => 'hmti'],
            [
                'nama'          => 'HMPS Teknik Informatika',
                'name'          => 'HMPS Teknik Informatika',
                'email'         => 'hmti@dpm-polmed.ac.id',
                'password'      => Hash::make('password123'),
                'organisasi_id' => $hmpsTI->id,
                'is_active'     => true,
            ],
        );
        $hmpsTiAkun->assignRole('hmps');

        // 8. Buat akun UKM — Pramuka
        $ukmPramukaAkun = User::firstOrCreate(
            ['username' => 'ukmpramuka'],
            [
                'nama'          => 'UKM Pramuka Polmed',
                'name'          => 'UKM Pramuka Polmed',
                'email'         => 'pramuka@dpm-polmed.ac.id',
                'password'      => Hash::make('password123'),
                'organisasi_id' => $ukmPramuka->id,
                'is_active'     => true,
            ],
        );
        $ukmPramukaAkun->assignRole('ukm');

        // 9. Buat akun UKM — Olahraga
        $ukmOlahragaAkun = User::firstOrCreate(
            ['username' => 'ukmolahraga'],
            [
                'nama'          => 'UKM Olahraga Polmed',
                'name'          => 'UKM Olahraga Polmed',
                'email'         => 'olahraga@dpm-polmed.ac.id',
                'password'      => Hash::make('password123'),
                'organisasi_id' => $ukmOlahraga->id,
                'is_active'     => true,
            ],
        );
        $ukmOlahragaAkun->assignRole('ukm');

        // 10. Buat akun Mahasiswa contoh (3 Akun)
        $mhs1 = User::firstOrCreate(
            ['email' => 'mhs1@students.polmed.ac.id'],
            [
                'nim'         => '2205011001',
                'prodi'       => 'Teknik Komputer',
                'nama'        => 'Budi Santoso',
                'name'        => 'Budi Santoso',
                'password'    => Hash::make('password'),
                'is_active'   => true,
            ]
        );
        $mhs1->assignRole('mahasiswa');

        $mhs2 = User::firstOrCreate(
            ['email' => 'mhs2@students.polmed.ac.id'],
            [
                'nim'         => '2205011002',
                'prodi'       => 'Teknik Komputer',
                'nama'        => 'Siti Aminah',
                'name'        => 'Siti Aminah',
                'password'    => Hash::make('password'),
                'is_active'   => true,
            ]
        );
        $mhs2->assignRole('mahasiswa');

        $mhs3 = User::firstOrCreate(
            ['email' => 'mhs3@students.polmed.ac.id'],
            [
                'nim'         => '2205011003',
                'prodi'       => 'Teknik Sipil',
                'nama'        => 'Agus Pratama',
                'name'        => 'Agus Pratama',
                'password'    => Hash::make('password'),
                'is_active'   => true,
            ]
        );
        $mhs3->assignRole('mahasiswa');

        // =====================================================================
        // RINGKASAN
        // =====================================================================
        $this->command->info('');
        $this->command->info('🎉 Database berhasil di-seed!');
        $this->command->info('');
        $this->command->info('Gunakan kredensial ini untuk LOGIN');
        $this->command->info('');
        $this->command->info('  [ADMIN]');
        $this->command->info('  Username : admin             | Password: password123');
        $this->command->info('');
        $this->command->info('  [STAFF DEWAN]');
        $this->command->info('  Username : staffdewan        | Password: password123');
        $this->command->info('');
        $this->command->info('  [PIMPINAN EKSEKUTIF]');
        $this->command->info('  Username : direktur          | Password: password123');
        $this->command->info('  Username : wakildirektur     | Password: password123');
        $this->command->info('');
        $this->command->info('  [HMPS]');
        $this->command->info('  Username : hmte              | Password: password123');
        $this->command->info('  Username : hmti              | Password: password123');
        $this->command->info('');
        $this->command->info('  [UKM]');
        $this->command->info('  Username : ukmpramuka        | Password: password123');
        $this->command->info('  Username : ukmolahraga       | Password: password123');
        $this->command->info('');
        $this->command->info('  [MAHASISWA (Password: password)]');
        $this->command->info('  Email    : mhs1@students.polmed.ac.id | NIM: 2205011001');
        $this->command->info('  Email    : mhs2@students.polmed.ac.id | NIM: 2205011002');
        $this->command->info('  Email    : mhs3@students.polmed.ac.id | NIM: 2205011003');
        $this->command->warn('');
        $this->command->warn('⚠️  Segera ganti password sebelum production!');
    }
}
