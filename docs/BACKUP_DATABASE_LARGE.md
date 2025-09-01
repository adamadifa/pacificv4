# Backup Database Besar (1GB+) - Pacific V4

## 🚨 **Masalah Timeout Database Besar**

Database dengan ukuran 1GB+ sering mengalami timeout karena:

-   **PHP Timeout**: Default 30-60 detik
-   **Memory Limit**: Default 128M-256M
-   **Web Server Timeout**: Apache/Nginx default timeout
-   **Database Connection Timeout**: MySQL connection timeout

## 🔧 **Solusi yang Tersedia**

### **1. Solusi Web Interface (Recommended untuk User)**

#### **Backup Normal**

-   Untuk database < 100MB
-   Timeout: 30-60 detik
-   Memory: 128M-256M

#### **Backup Database Besar**

-   Untuk database 100MB - 5GB
-   Timeout: Unlimited
-   Memory: 2GB
-   Chunking: 500 rows per batch
-   Progress tracking

### **2. Solusi Command Line (Recommended untuk Admin)**

#### **Command: `php artisan backup:large-database`**

```bash
# Backup dengan default settings
php artisan backup:large-database

# Backup dengan nama file custom
php artisan backup:large-database --filename=backup_custom.sql

# Backup dengan chunk size custom
php artisan backup:large-database --chunk-size=200

# Backup menggunakan queue system
php artisan backup:large-database --use-queue
```

#### **Command: `php artisan backup:database`**

```bash
# Backup normal
php artisan backup:database

# Backup dengan nama file custom
php artisan backup:database --filename=backup_normal.sql
```

#### **Command: `php artisan backup:stream-download`**

```bash
# Streaming download dengan progress bar
php artisan backup:stream-download backup_large_2024-01-01_12-00-00.sql

# Download dengan nama output custom
php artisan backup:stream-download backup_large_2024-01-01_12-00-00.sql --output=my_backup.sql

# Download dengan chunk size custom (dalam KB)
php artisan backup:stream-download backup_large_2024-01-01_12-00-00.sql --chunk-size=2048
```

#### **Command: `php artisan backup:stream-direct`**

```bash
# Streaming download langsung dari database (tanpa simpan file di server)
php artisan backup:stream-direct

# Download dengan nama output custom
php artisan backup:stream-direct --output=my_direct_backup.sql

# Download dengan chunk size custom
php artisan backup:stream-direct --chunk-size=500

# Download dengan progress tracking
php artisan backup:stream-direct --progress

# Kombinasi semua opsi
php artisan backup:stream-direct --output=backup.sql --chunk-size=1000 --progress
```

### **3. Solusi Streaming Download (Recommended untuk File Besar)**

#### **Keuntungan Streaming Download:**

-   ✅ Tidak ada timeout
-   ✅ Progress tracking real-time
-   ✅ Resume capability (jika terputus)
-   ✅ Memory efficient
-   ✅ Support file sangat besar (10GB+)
-   ✅ Pause/Resume functionality

#### **Fitur Streaming Download:**

-   **Chunked Transfer**: File dikirim dalam chunks 1MB
-   **Progress Bar**: Real-time progress dengan percentage
-   **Speed Monitoring**: Monitor download speed
-   **Resume Download**: Lanjutkan download yang terputus
-   **Pause/Resume**: Pause dan resume download
-   **Range Requests**: Support HTTP Range headers

### **4. Solusi Streaming Download Langsung dari Database (Recommended untuk User)**

#### **Keuntungan Streaming Download Langsung:**

-   ✅ **Tidak ada file tersimpan di server** - Hemat disk space
-   ✅ **Download langsung ke komputer user** - Tidak ada file temporary
-   ✅ **Memory efficient** - Hanya load data yang sedang diproses
-   ✅ **Real-time streaming** - Data langsung dikirim ke browser
-   ✅ **Support database sangat besar** - Tidak ada batasan ukuran file
-   ✅ **Progress tracking** - Monitor progress real-time

#### **Fitur Streaming Download Langsung:**

-   **Direct Database Access**: Akses langsung ke database tanpa simpan file
-   **Chunked Processing**: Process data dalam chunks untuk memory efficiency
-   **Real-time Output**: Data langsung dikirim ke browser user
-   **Progress Comments**: Progress tracking dalam file SQL
-   **Memory Management**: Garbage collection dan buffer flush otomatis
-   **No Server Storage**: Tidak ada file tersimpan di server Laravel

### **5. Solusi Queue System (Recommended untuk Production)**

#### **Keuntungan Queue:**

-   ✅ Tidak ada timeout
-   ✅ Bisa dijalankan di background
-   ✅ Progress tracking via log
-   ✅ Error handling yang baik
-   ✅ Bisa di-monitor

#### **Setup Queue:**

```bash
# Install queue driver (Redis recommended)
composer require predis/predis

# Setup queue worker
php artisan queue:work --timeout=3600

# Monitor queue
php artisan queue:monitor
```

## 📋 **Cara Penggunaan**

### **Melalui Web Interface:**

1. **Login** sebagai user dengan permission `backup.database`
2. **Navigasi** ke menu **Utilities > Backup Database**
3. **Pilih tipe backup:**
    - **Backup Normal**: Untuk database kecil
    - **Backup Database Besar**: Untuk database besar (1GB+)
4. **Streaming Download Langsung:**
    - **Download Langsung**: Streaming langsung dari database tanpa simpan file
    - **Download dengan Progress**: Streaming dengan progress tracking

### **Melalui Command Line:**

#### **Untuk Database Kecil (< 100MB):**

```bash
php artisan backup:database
```

#### **Untuk Database Besar (100MB - 5GB):**

```bash
php artisan backup:large-database
```

#### **Untuk Database Sangat Besar (5GB+):**

```bash
php artisan backup:large-database --use-queue
```

## ⚙️ **Konfigurasi Optimasi**

### **PHP Configuration:**

```ini
; php.ini atau .htaccess
max_execution_time = 0
memory_limit = 2G
max_input_time = 0
post_max_size = 2G
upload_max_filesize = 2G
```

### **MySQL Configuration:**

```ini
; my.cnf atau my.ini
max_allowed_packet = 1G
net_buffer_length = 16384
wait_timeout = 28800
interactive_timeout = 28800
```

### **Web Server Configuration:**

#### **Apache (.htaccess):**

```apache
php_value max_execution_time 0
php_value memory_limit 2G
php_value max_input_time 0
```

#### **Nginx (nginx.conf):**

```nginx
fastcgi_read_timeout 3600;
proxy_read_timeout 3600;
```

## 🚀 **Best Practices untuk Database Besar**

### **1. Waktu Backup:**

-   **Off-peak hours**: 2:00 AM - 6:00 AM
-   **Weekend**: Sabtu/Minggu pagi
-   **Maintenance window**: Saat maintenance

### **2. Monitoring:**

```bash
# Monitor disk space
df -h

# Monitor memory usage
free -h

# Monitor MySQL processes
SHOW PROCESSLIST;

# Monitor backup progress
tail -f storage/logs/laravel.log
```

### **3. Backup Strategy:**

-   **Daily**: Backup incremental (hanya data baru)
-   **Weekly**: Backup full database
-   **Monthly**: Backup + compression
-   **Yearly**: Backup + archive

## 🔍 **Troubleshooting**

### **Error: "Maximum execution time exceeded"**

**Solusi:**

```bash
# Set unlimited timeout
set_time_limit(0);

# Atau via command line
php -d max_execution_time=0 artisan backup:large-database
```

### **Error: "Allowed memory size exhausted"**

**Solusi:**

```bash
# Increase memory limit
php -d memory_limit=2G artisan backup:large-database

# Atau set di php.ini
memory_limit = 2G
```

### **Error: "MySQL server has gone away"**

**Solusi:**

```bash
# Increase MySQL timeout
SET SESSION wait_timeout = 28800;
SET SESSION interactive_timeout = 28800;

# Atau gunakan queue system
php artisan backup:large-database --use-queue
```

### **Error: "Connection timed out"**

**Solusi:**

```bash
# Check network connectivity
ping database_host

# Check firewall settings
# Use queue system for long operations
```

## 📊 **Performance Benchmark**

### **Database Size vs Time:**

-   **100MB**: 2-5 menit
-   **500MB**: 10-20 menit
-   **1GB**: 20-40 menit
-   **5GB**: 1-3 jam
-   **10GB+**: 3-8 jam

### **Optimization Impact:**

-   **Chunking**: 40-60% faster
-   **Memory optimization**: 20-30% faster
-   **Queue system**: No timeout issues
-   **mysqldump**: 2-3x faster than Laravel

## 🛡️ **Security Considerations**

### **File Permissions:**

```bash
# Set proper permissions
chmod 750 storage/app/backups
chown www-data:www-data storage/app/backups
```

### **Access Control:**

-   Hanya user dengan permission `backup.database`
-   Log semua aktivitas backup
-   Encrypt backup files jika diperlukan

### **Network Security:**

-   Backup via internal network
-   Use VPN untuk remote backup
-   Encrypt backup transfer

## 📈 **Monitoring & Alerting**

### **Log Monitoring:**

```bash
# Monitor backup logs
tail -f storage/logs/laravel.log | grep "backup"

# Monitor queue logs
tail -f storage/logs/laravel.log | grep "queue"
```

### **Health Checks:**

```bash
# Check backup directory
ls -la storage/app/backups/

# Check backup file integrity
file storage/app/backups/*.sql

# Check backup file size
du -sh storage/app/backups/
```

## 🔄 **Automation & Cron Jobs**

### **Daily Backup (2:00 AM):**

```bash
0 2 * * * cd /path/to/project && php artisan backup:database
```

### **Weekly Large Backup (Sunday 3:00 AM):**

```bash
0 3 * * 0 cd /path/to/project && php artisan backup:large-database
```

### **Monthly Queue Backup (1st day 4:00 AM):**

```bash
0 4 1 * * cd /path/to/project && php artisan backup:large-database --use-queue
```

## 📞 **Support & Maintenance**

### **Regular Maintenance:**

-   Monitor disk space usage
-   Clean old backup files
-   Test backup restoration
-   Update backup scripts

### **Emergency Procedures:**

-   Stop backup process: `pkill -f "backup:large-database"`
-   Clear queue: `php artisan queue:clear`
-   Restart queue worker: `php artisan queue:restart`

### **Contact Information:**

-   **Technical Support**: development@pacific.com
-   **Emergency**: +62-xxx-xxx-xxxx
-   **Documentation**: docs.pacific.com/backup
