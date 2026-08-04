# Rencana Implementasi - Fitur Broadcast Tagihan

Fitur ini ditambahkan pada modul **Marketing** untuk mengirimkan pengingat tagihan (piutang yang sudah jatuh tempo atau belum) kepada pelanggan melalui WhatsApp (asynchronous job).

---

## 1. Database & Hak Akses (Permissions)

### Seeder: `database/seeders/Broadcasttagihanpermissionseeder.php`
- Membuat permission group: `Broadcast Tagihan`
- Menambahkan permission: `broadcasttagihan.index` dan `broadcasttagihan.send`
- Memberikan permission tersebut ke role **super admin** dan role terkait (seperti **marketing** / **gm marketing** jika ada).

---

## 2. Routing (`routes/web.php`)

Menambahkan route controller baru dalam middleware group `auth`:
```php
Route::controller(BroadcasttagihanController::class)->group(function () {
    Route::get('/broadcasttagihan', 'index')->name('broadcasttagihan.index')->can('broadcasttagihan.index');
    Route::post('/broadcasttagihan/send', 'send')->name('broadcasttagihan.send')->can('broadcasttagihan.send');
});
```

---

## 3. Backend (Controller & Job)

### Controller: `app/Http/Controllers/BroadcasttagihanController.php`
- **index()**: 
  - Mengambil daftar faktur kredit (`jenis_transaksi = 'K'`) yang belum lunas (`sisa_piutang > 0`).
  - Menghitung `sisa_piutang` per faktur dengan query subtotal bruto, potongan, retur, dan historibayar.
  - Membandingkan `jatuh_tempo` dengan tanggal hari ini untuk menentukan status: **"Sudah Jatuh Tempo"** (jika `jatuh_tempo < hari_ini`) atau **"Belum Jatuh Tempo"**.
  - Menerapkan filter: Cabang, Salesman, Nama Pelanggan, dan Status Jatuh Tempo.
  - Membatasi data berdasarkan hak akses Cabang/Salesman user yang login (regional sales manager / salesman / dll).
- **send()**:
  - Menerima request `no_faktur` untuk dikirimkan pengingat tagihan.
  - Mengambil data detail faktur, sisa piutang, nama pelanggan, dan nomor HP.
  - Melakukan dispatch `SendBroadcastTagihanJob` untuk mengirimkan WhatsApp secara background.

### Background Job: `app/Jobs/SendBroadcastTagihanJob.php`
- Job asynchronous (`ShouldQueue`) untuk memproses pengiriman pesan ke API WhatsApp (`https://wa.portalmp.com/send-message`).
- Format pesan WhatsApp:
  ```text
  *INFO TAGIHAN PACIFIC*

  Kepada Yth.
  *Nama Pelanggan* (Kode Pelanggan)

  Berikut rincian tagihan Anda yang belum lunas:
  - No. Faktur: *No Faktur*
  - Tanggal Faktur: Tanggal
  - Jatuh Tempo: *Jatuh Tempo*
  - Status: *Sudah Jatuh Tempo / Belum Jatuh Tempo*
  - Sisa Piutang: *Rp Sisa Piutang*

  Mohon segera melakukan pembayaran. Jika Anda telah melakukan pembayaran, silakan abaikan pesan ini atau kirimkan bukti transfer Anda.

  Terma kasih.
  ```

---

## 4. User Interface (UI / Views)

### Menu Sidebar: `resources/views/layouts/sidebar/marketing.blade.php`
Menambahkan item menu **Broadcast Tagihan** di bawah submenu Marketing:
```html
@can('broadcasttagihan.index')
    <li class="menu-item {{ request()->is(['broadcasttagihan', 'broadcasttagihan/*']) ? 'active' : '' }}">
        <a href="{{ route('broadcasttagihan.index') }}" class="menu-link">
            <div>Broadcast Tagihan</div>
        </a>
    </li>
@endcan
```

### View Utama: `resources/views/marketing/broadcasttagihan/index.blade.php`
Halaman dashboard untuk mengelola broadcast tagihan:
- **Filter**: Dropdown Cabang, Dropdown Salesman, Input Nama Pelanggan, Dropdown Status Jatuh Tempo.
- **Tabel**:
  - No. Faktur
  - Tanggal Faktur
  - Nama Pelanggan (Kode)
  - No. WA Pelanggan (`no_hp_pelanggan`)
  - Salesman & Cabang
  - Sisa Piutang
  - Jatuh Tempo
  - Status (Badge merah "Lewat Jatuh Tempo" atau hijau "Belum Jatuh Tempo")
  - Tombol **Kirim WA** (mengirimkan request AJAX ke controller untuk trigger WhatsApp broadcast)

---

## 5. Rencana Pengujian
1. Menjalankan seeder permission.
2. Membuka halaman "Broadcast Tagihan" di modul Marketing.
3. Memastikan filter Cabang, Salesman, dan Status Jatuh Tempo berfungsi dengan benar.
4. Mengklik tombol "Kirim WA" pada salah satu tagihan dan memastikan Job masuk ke queue serta mengirimkan pesan dengan format yang benar.
