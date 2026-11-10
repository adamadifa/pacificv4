#!/bin/bash
# Script Rollback Otomatis jika terjadi kendala saat perbaikan limit penjualan
echo "Memulai proses rollback..."
cp -v backup_limit_fix/app/Models/Penjualan.php app/Models/Penjualan.php
cp -v backup_limit_fix/app/Http/Controllers/PenjualanController.php app/Http/Controllers/PenjualanController.php
cp -v backup_limit_fix/resources/views/marketing/penjualan/edit.blade.php resources/views/marketing/penjualan/edit.blade.php
cp -v backup_limit_fix/resources/views/marketing/penjualan/create.blade.php resources/views/marketing/penjualan/create.blade.php
cp -v backup_limit_fix/resources/views/sfa/penjualan_create.blade.php resources/views/sfa/penjualan_create.blade.php
cp -v backup_limit_fix/resources/views/sfa/penjualan_edit.blade.php resources/views/sfa/penjualan_edit.blade.php
echo "Rollback selesai! Seluruh file telah dikembalikan ke kondisi awal persis sebelum perbaikan."

