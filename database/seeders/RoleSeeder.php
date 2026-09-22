<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Seed 5 Role utama beserta Permission.
     * 
     * Role:
     * - admin          : Kelola seluruh user & role, konfigurasi sistem, log
     * - staff_dewan    : Kelola operasional harian DPM (pengaduan, pengumuman, kegiatan)
     * - hmps           : Publikasi pengumuman & kegiatan prodi
     * - ukm            : Publikasi pengumuman & kegiatan UKM
     * - mahasiswa      : Kirim aspirasi, lihat pengumuman & kalender
     * 
     * Permission Khusus:
     * - penanganan_kasus_sensitif : Hanya staff dengan izin ini yang bisa membuka identitas anonim
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // =====================================================================
        // PERMISSIONS
        // =====================================================================

        $permissions = [
            // Pengaduan
            'lihat_pengaduan_umum',
            'lihat_pengaduan_sensitif',
            'kelola_status_pengaduan',
            'hapus_pengaduan',
            'penanganan_kasus_sensitif',   // Permission KRITIS — buka identitas anonim

            // Pengumuman
            'buat_pengumuman',
            'edit_pengumuman_sendiri',
            'edit_pengumuman_semua',
            'hapus_pengumuman',
            'pin_pengumuman',

            // Kegiatan
            'buat_kegiatan',
            'edit_kegiatan_sendiri',
            'edit_kegiatan_semua',
            'hapus_kegiatan',

            // User Management
            'lihat_user',
            'buat_user',
            'edit_user',
            'nonaktifkan_user',
            'hapus_user',
            'assign_role',

            // Organisasi
            'buat_organisasi',
            'edit_organisasi',
            'hapus_organisasi',

            // Sistem
            'lihat_log_aktivitas',
            'konfigurasi_sistem',
            'backup_data',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        $this->command->info('✅ ' . count($permissions) . ' permissions dibuat.');

        // =====================================================================
        // ROLES & ASSIGN PERMISSIONS
        // =====================================================================

        // 1. ADMIN — Akses penuh sistem (kecuali penanganan kasus sensitif)
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $admin->syncPermissions([
            'lihat_pengaduan_umum',
            'lihat_pengaduan_sensitif',
            'kelola_status_pengaduan',
            'hapus_pengaduan',
            'buat_pengumuman',
            'edit_pengumuman_semua',
            'hapus_pengumuman',
            'pin_pengumuman',
            'buat_kegiatan',
            'edit_kegiatan_semua',
            'hapus_kegiatan',
            'lihat_user',
            'buat_user',
            'edit_user',
            'nonaktifkan_user',
            'hapus_user',
            'assign_role',
            'buat_organisasi',
            'edit_organisasi',
            'hapus_organisasi',
            'lihat_log_aktivitas',
            'konfigurasi_sistem',
            'backup_data',
            // CATATAN: 'penanganan_kasus_sensitif' TIDAK diberikan ke admin secara default
            // Harus di-assign manual oleh Super Admin
        ]);

        // 2. STAFF DEWAN — Operasional harian DPM
        $staffDewan = Role::firstOrCreate(['name' => 'staff_dewan', 'guard_name' => 'web']);
        $staffDewan->syncPermissions([
            'lihat_pengaduan_umum',
            'kelola_status_pengaduan',
            'buat_pengumuman',
            'edit_pengumuman_semua',
            'hapus_pengumuman',
            'pin_pengumuman',
            'buat_kegiatan',
            'edit_kegiatan_semua',
            'hapus_kegiatan',
            'lihat_user',
            'edit_user',
            'nonaktifkan_user',
            'buat_organisasi',
            'edit_organisasi',
            // CATATAN: 'penanganan_kasus_sensitif' hanya untuk staff tertentu, di-assign manual
        ]);

        // 3. HMPS — Himpunan Mahasiswa Program Studi
        $hmps = Role::firstOrCreate(['name' => 'hmps', 'guard_name' => 'web']);
        $hmps->syncPermissions([
            'buat_pengumuman',
            'edit_pengumuman_sendiri',
            'hapus_pengumuman',   // hanya milik sendiri (dikontrol di Policy)
            'buat_kegiatan',
            'edit_kegiatan_sendiri',
            'hapus_kegiatan',     // hanya milik sendiri (dikontrol di Policy)
        ]);

        // 4. UKM — Unit Kegiatan Mahasiswa
        $ukm = Role::firstOrCreate(['name' => 'ukm', 'guard_name' => 'web']);
        $ukm->syncPermissions([
            'buat_pengumuman',
            'edit_pengumuman_sendiri',
            'hapus_pengumuman',   // hanya milik sendiri (dikontrol di Policy)
            'buat_kegiatan',
            'edit_kegiatan_sendiri',
            'hapus_kegiatan',     // hanya milik sendiri (dikontrol di Policy)
        ]);

        // 5. MAHASISWA — Pengguna umum kampus
        $mahasiswa = Role::firstOrCreate(['name' => 'mahasiswa', 'guard_name' => 'web']);
        $mahasiswa->syncPermissions([
            // Mahasiswa hanya bisa submit dan lihat pengaduan mereka sendiri
            // (dikontrol via Policy, bukan permission langsung)
        ]);

        // 6. DIREKTUR — Pimpinan Eksekutif
        $direktur = Role::firstOrCreate(['name' => 'direktur', 'guard_name' => 'web']);
        $direktur->syncPermissions([
            'lihat_pengaduan_umum',
            'lihat_pengaduan_sensitif',
            'lihat_user',
            'lihat_log_aktivitas',
        ]);

        // 7. WAKIL DIREKTUR — Pimpinan Eksekutif
        $wakilDirektur = Role::firstOrCreate(['name' => 'wakil_direktur', 'guard_name' => 'web']);
        $wakilDirektur->syncPermissions([
            'lihat_pengaduan_umum',
            'lihat_pengaduan_sensitif',
            'lihat_user',
            'lihat_log_aktivitas',
        ]);

        $this->command->info('✅ 7 roles dibuat: admin, staff_dewan, hmps, ukm, mahasiswa, direktur, wakil_direktur');
        $this->command->warn('⚠️  Permission "penanganan_kasus_sensitif" harus di-assign manual ke staff yang ditunjuk!');
    }
}
