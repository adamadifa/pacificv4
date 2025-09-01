# Backup Database - Pacific V4

## Deskripsi

Fitur backup database memungkinkan administrator untuk membuat, mengunduh, dan mengelola file backup database MySQL secara manual melalui interface web atau command line.

## Fitur Utama

-   ✅ Buat backup database secara manual
-   ✅ Lihat daftar file backup yang tersedia
-   ✅ Download file backup
-   ✅ Hapus file backup yang tidak diperlukan
-   ✅ Log aktivitas backup untuk audit trail
-   ✅ Command line interface untuk automation

## Persyaratan Sistem

-   MySQL/MariaDB server
-   `mysqldump` command tersedia di sistem
-   Permission write ke direktori `storage/app/backups`
-   Role user dengan permission `backup.database`

## Cara Penggunaan

### 1. Melalui Web Interface

1. Login sebagai user dengan permission `backup.database`
2. Navigasi ke menu **Utilities > Backup Database**
3. Klik tombol **"Buat Backup"** untuk membuat backup baru
4. Gunakan tombol **Download** untuk mengunduh file backup
5. Gunakan tombol **Hapus** untuk menghapus file backup

### 2. Melalui Command Line

```bash
# Backup dengan nama file default
php artisan backup:database

# Backup dengan nama file custom
php artisan backup:database --filename=nama_file_custom.sql
```

## Struktur File

```
storage/
└── app/
    └── backups/
        ├── backup_2024-01-15_10-30-00.sql
        ├── backup_2024-01-15_14-45-30.sql
        └── ...
```

## Permission

-   **Permission Name**: `backup.database`
-   **Role Default**: `super admin`, `gm administrasi`
-   **Access Control**: Hanya user dengan permission yang dapat mengakses

## Keamanan

-   Semua aktivitas backup di-log menggunakan Spatie Activity Log
-   File backup disimpan di direktori yang aman (`storage/app/backups`)
-   Validasi permission sebelum akses
-   Konfirmasi sebelum penghapusan file

## Troubleshooting

### Error: "mysqldump command not found"

**Solusi**: Install MySQL client tools atau pastikan `mysqldump` tersedia di PATH sistem.

### Error: "Access denied for user"

**Solusi**: Periksa konfigurasi database di file `.env` dan pastikan user memiliki privilege untuk backup.

### Error: "Permission denied" saat membuat direktori

**Solusi**: Pastikan web server memiliki permission write ke direktori `storage/app/backups`.

## Maintenance

-   File backup disimpan secara permanen sampai dihapus manual
-   Direkomendasikan untuk setup backup otomatis menggunakan cron job
-   Monitor penggunaan disk space untuk direktori backup

## Cron Job Setup (Opsional)

Untuk backup otomatis, tambahkan ke crontab:

```bash
# Backup setiap hari jam 2 pagi
0 2 * * * cd /path/to/project && php artisan backup:database

# Backup setiap minggu hari Minggu jam 3 pagi
0 3 * * 0 cd /path/to/project && php artisan backup:database --filename=weekly_backup_$(date +\%Y\%m\%d).sql
```

## Dependencies

-   Laravel 10+
-   Spatie Laravel Permission
-   Spatie Laravel Activity Log
-   Carbon (untuk format tanggal)

## Support

Untuk bantuan teknis, hubungi tim development atau buat ticket melalui sistem.
