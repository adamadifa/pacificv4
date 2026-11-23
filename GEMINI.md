# Aturan Keamanan & Konfirmasi Pengguna

Meskipun mode Turbo / Auto-approve aktif, asisten AI **WAJIB** mematuhi aturan persetujuan berikut sebelum mengeksekusi aksi:

## 1. File Konfigurasi & Lingkungan (.env)
- **Wajib Izin:** Dilarang mengedit, menambahkan, menimpa, atau menghapus file `.env`, `.env.*`, atau file rahasia lainnya tanpa konfirmasi dan persetujuan eksplisit dari pengguna di chat.
- **Proteksi Kredensial:** Dilarang menjalankan perintah shell/skrip yang mencetak (*echo/cat/print*) isi kredensial `.env` ke terminal atau log.
- **Alur Perubahan:** Paparkan variabel apa yang ingin diubah/ditambahkan beserta alasannya, lalu tunggu persetujuan pengguna sebelum mengubahnya.

## 2. Operasi Database
- **Boleh Langsung (Tanpa Konfirmasi):**
  - Operasi baca data / read-only (seperti query `SELECT`, pengecekan isi tabel, atau inspeksi skema data yang aman).
- **Wajib Izin Sebelum Eksekusi:**
  - Menjalankan migrasi database (misalnya: `migrate`, `db:push`, `prisma migrate`, `artisan migrate`, dll.).
  - Menghapus data atau tabel (`DELETE`, `TRUNCATE`, `DROP TABLE`, `DROP DATABASE`).
  - Mereset database atau data (misalnya: `migrate:fresh`, `migrate:reset`, atau script reset/seeder yang menimpa data).
  - Perubahan skema struktural (`ALTER TABLE`) atau pembaruan massal (`UPDATE` berskala besar).

## 3. Protokol Interaksi
Ketika sebuah tugas membutuhkan salah satu dari aksi yang **Wajib Izin** di atas:
1. **Berhenti:** Jangan memanggil tool eksekusi file atau perintah terminal terlebih dahulu.
2. **Jelaskan:** Paparkan secara ringkas perubahan atau perintah yang akan dijalankan dan dampaknya.
3. **Tunggu:** Lanjutkan eksekusi HANYA setelah pengguna memberikan konfirmasi/persetujuan di chat.

## 4. Standar Desain UI & Form V2 (Tailwind)
Untuk menjaga konsistensi tampilan seluruh halaman Version 2 (V2):
- **Style Form**:
  - **Filter Form Baris Pencarian**: Tanpa Label (No-Label). Gunakan placeholder atau opsi default agar layout filter tetap compact, bersih, dan presisi.
  - **Halaman Modul Laporan**:
    - **Header Modul Bersih**: Dilarang menambahkan badge format keluaran tambahan yang tidak diminta (seperti badge "Format Output: PDF & Excel") di sisi kanan header strip modul laporan.
    - **Judul Laporan Bersih (Tanpa Titik Biru)**: Dilarang menambahkan ornamen bullet / titik biru (`•` atau bulatan warna biru) di depan teks judul laporan. Cukup teks judul bersih (`font-bold text-slate-900 uppercase tracking-wider`) dengan deskripsi di bawahnya.
    - **Lebar Kontainer Proporsional**: Lebar kontainer card form laporan wajib pas dan proporsional mengikuti lebar form input di dalamnya (gunakan `max-w-4xl` atau `max-w-3xl`), tidak dibiarkan melebar kosong ke samping.
    - **Validasi Interaktif Form Laporan (Anti Swal Alert)**: Dilarang keras menggunakan dialog popup `Swal.fire` untuk validasi form/filter laporan. Wajib menerapkan sistem validasi interaktif inline standar V2 (border merah, icon prefix merah, pesan error `<p class="field-error-msg">` di bawah input, live cleanup, serta tombol cetak/export yang menampilkan spinner "Mencetak..." sementara dengan timeout 2.5s karena target membuka tab baru `_blank`).
  - **Modal Formulir Create & Edit**:
    - **Wajib Label di Atas Input**: Setiap elemen input/select pada modal formulir Create & Edit WAJIB memiliki tag `<label>` di atasnya (`block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1`).
    - **Tanda Bintang Merah (*)**: Wajib menambahkan tanda bintang merah (`<span class="text-rose-500">*</span>`) pada label untuk setiap field yang wajib diisi (*mandatory/required*).
    - **Jarak Antar Baris Rapat**: Jarak baris antar field dalam formulir wajib dibuat rapat dan rapi (gunakan `space-y-2.5` atau `space-y-3`, dan `gap-2` sampai `gap-2.5` pada grid) agar modal tetap kompak dan tidak memakan ruang vertikal berlebih.
    - **Pengecualian Input Keterangan Sejajar Tombol Aksi**: Input keterangan tambahan/opsional yang berada satu baris langsung berdampingan dengan tombol aksi (seperti input keterangan barang & tombol "Tambah Produk") tidak perlu diberi tag `<label>` di atasnya, cukup menggunakan `placeholder` yang deskriptif (misal: "Keterangan barang (opsional)") agar baris aksi tersebut rapat dan kompak terhadap baris form di atasnya tanpa memakan ruang vertikal ekstra.
  - **Wajib Icon Prefix**: Setiap input field, select box, atau dropdown filter wajib memiliki ikon Lucide di sebelah kiri (`absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10` atau `pl-3`).
  - **Border Radius Standar**: Gunakan `rounded-lg` (radius 8px) untuk semua elemen form (input, select, button, segmented pill). Jangan terlalu bulat (hindari `rounded-xl` atau `rounded-2xl` pada elemen form).
  - **Tinggi Seragam**: Standar tinggi untuk kontrol form, input, select, dan tombol aksi adalah `h-[34px]` atau `h-[36px]`.
  - **Wajib Flatpickr untuk Input Tanggal**: Setiap input tanggal WAJIB menggunakan Flatpickr (gunakan `type="text"`, class `flatpickr-date`, placeholder "Pilih Tanggal", format default `Y-m-d`, dan `readonly` agar kalender popup rapi konsisten). Dilarang keras menggunakan input tanggal native bawaan browser (`<input type="date">`).
  - **Wajib Select2 untuk Data Relasi (Karyawan, Cabang, Produk)**: Setiap dropdown pilihan data relasi banyak seperti Karyawan, Cabang, atau Produk pada formulir Create & Edit WAJIB menggunakan Select2 yang di-styling sesuai sistem desain Tailwind V2:
    - Tinggi seragam `h-[36px]` dan radius `rounded-lg` (8px).
    - Terintegrasi dengan icon prefix Lucide (padding kiri 36px sehingga teks tidak menabrak icon).
    - Panah dropdown chevron SVG custom rapi (tidak memakai tanda panah default browser/Select2 yang kaku).
    - Dropdown panel border halus, shadow modern, search input rapi, dan opsi terpilih / hover menggunakan tema Pacific Navy (`#284c9a`).
    - Di-mount secara global langsung ke `document.body` (tanpa `dropdownParent` modal) sehingga menu dropdown melayang bebas (*floating overlay*) di atas modal tanpa mengubah tinggi DOM modal, tidak menggeser elemen (*no jitter/shift*), dan tidak menimbulkan scrollbar bawah.
    - Kontainer in-page `.select2.select2-container` tetap berada dalam alur dokumen (`position: relative !important; z-index: 1 !important; width: 100% !important;`), sementara icon prefix dijamin selalu tampil di atas kotak seleksi (`z-index: 20 !important; pointer-events: none !important;`). Panel dropdown melayang diatur dengan `z-index: 99999 !important;`.
    - **Posisi Tombol Reset/Clear ('x')**: Jika fitur `allowClear` aktif, tombol hapus pilihan (`.select2-selection__clear`) WAJIB diposisikan secara absolut di sisi kanan (`position: absolute !important; right: 28px !important; top: 50% !important; transform: translateY(-50%) !important;`), tepat di sebelah kiri panah chevron dropdown, dengan padding kanan seleksi 50px (`padding-right: 50px !important;`) agar teks pilihan terpotong rapi tanpa menabrak tombol clear maupun chevron. Dilarang keras membiarkan tombol 'x' berada di sisi kiri dekat icon prefix.
    - Pada validasi error, border dan ring Select2 ikut berubah menjadi merah (`!border-rose-500 !ring-1 !ring-rose-500`) dan otomatis dibersihkan saat opsi dipilih (`select2:select`).
  - **Pencegahan Pergeseran Layout Grid Tombol Sejajar Input (Anti-Layout Shift)**:
    - Pada baris form (grid) yang memuat input field (yang memiliki `<label>`) berdampingan dengan tombol aksi (seperti tombol "Tambah Produk", "Get Saldo", "Filter", dll.):
    - **Dilarang Keras Menggunakan `items-end`**: Penggunaan `items-end` pada container grid dilarang karena ketika field input mendapatkan pesan error validasi di bawahnya (`<p class="field-error-msg">`), tinggi kolom input bertambah sehingga tombol aksi di sampingnya otomatis ikut terdorong/bergeser ke bawah (*layout shift/jumping*).
    - **Wajib Menggunakan `items-start`**: Selalu gunakan `items-start` pada container grid (`<div class="grid ... items-start">`).
    - **Wajib Spacer Label Transparan pada Kolom Tombol**: Di atas tombol aksi, wajib menambahkan tag label spacer transparan yang menyamai ukuran dan margin label input di sampingnya:
      `<label class="hidden sm:block text-xs font-bold text-transparent mb-1 select-none pointer-events-none">&nbsp;</label>` (atau `text-[11px]` jika label form menggunakan `text-[11px]`).
      Hal ini menjamin tombol aksi selalu sejajar sempurna dengan kotak input, dan posisi tombol terkunci kokoh (*zero layout shift*) baik sebelum, saat, maupun setelah pesan error validasi muncul.
    - **Judul Seksi / Sub-Header Form Natural (Dilarang Kapital Semua / Anti-Uppercase)**:
      - Judul seksi pembagian di dalam formulir atau card modal (seperti "Tambah Rincian Produk", "Tambah Produk Baru ke Daftar", "Daftar Produk Permintaan", dsb.) **DILARANG KERAS** menggunakan format kapital semua / all-caps (`uppercase tracking-wider` atau `uppercase`).
      - Wajib menggunakan huruf kapital di awal kata saja (Title Case / format normal) yang rapi, elegan, dan nyaman dibaca (misal: `text-xs font-bold text-slate-700 dark:text-slate-200` atau `text-white font-bold text-xs`).
    - **Standar Padding Card Kontainer & Card Header Formulir**:
      - **Padding Kontainer Card Seragam**: Seluruh card pembagian seksi di dalam modal formulir wajib menggunakan padding seragam `p-3 sm:p-3.5`. Dilarang menggunakan padding yang tidak konsisten (misal ada card `p-2.5 sm:p-3` dan card lain `p-3 sm:p-3.5`).
      - **Struktur Header Seksi / Sub-Header Card**:
        - Wajib dilengkapi icon prefix Lucide (`w-3.5 h-3.5 text-[#284c9a] dark:text-blue-400`).
        - Jarak margin bawah (*bottom margin*) header seksi wajib seragam `mb-2.5`.
        - Jika card berupa ringkasan dokumen referensi (Executive Summary Strip), header wajib memiliki garis pembatas halus:
          `<div class="flex items-center justify-between pb-2 mb-2.5 border-b border-slate-200/80 dark:border-slate-800">`.

- **Statistic & Metric Cards**:
  - **Satu Kesatuan Strip**: Disatukan dalam satu card container strip utuh (`rounded-xl overflow-hidden shadow-xs hover:shadow-md`), bukan card terpisah-pisah dengan jarak gap.
  - **Warna Solid Sidebar**: Menggunakan warna solid Pacific Navy (`bg-[#284c9a]`) persis seperti sidebar, teks utama putih kontras tinggi (`text-white font-black font-mono-num`), dan label/subteks putih transparan (`text-white/75` dan `text-white/55`).
  - **Inner Divider**: Antar kolom metrik dipisahkan dengan garis divider halus (`border-white/15` atau `divide-white/15`).
  - **Ornamen Latar Belakang**: Dihiasi ornamen SVG busur geometris halus (`text-white/[0.06]`), pendaran radial lembut, dan watermark icon tematik transparan (`text-white/[0.08] group-hover:text-white/[0.14]`).

## 5. Standar Validasi Form Interaktif V2
Untuk seluruh formulir Version 2 (baik halaman penuh maupun modal Create/Edit):
- **Isolasi ID & Selector Form (Anti-Bentrokan)**:
  - Dilarang keras menggunakan atribut `id` yang sama antara form filter/pencarian di halaman utama dan input di modal form (misal: gunakan `id="nama_search"`, `id="nama_create"`, dan `id="nama_edit"`).
  - Selector JavaScript/jQuery wajib selalu dibatasi di dalam lingkup form yang aktif (`const $form = $("#formModal"); const $input = $form.find("#nama_create");`) agar tidak bentrok dengan elemen lain di halaman.
- **Tampilan Visual Saat Field Tidak Valid (Error State)**:
  - **Border Input**: Border elemen wajib berubah menjadi merah tegas (`border-rose-500 ring-1 ring-rose-500 focus:border-rose-500 focus:ring-rose-500/20`).
  - **Icon Prefix**: Ikon di dalam input berubah menjadi warna merah (`text-rose-500`).
  - **Pesan Error di Bawah Input**: Teks pesan error WAJIB dimunculkan tepat di bawah field yang bermasalah dengan icon tanda seru (`<p class="field-error-msg text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 flex items-center gap-1"><svg ...></svg><span>Pesan error</span></p>`).
  - **Autofokus**: Kursor wajib otomatis diarahkan (*focus*) ke field error pertama.
- **Pencegahan Pergeseran Layout Grid (Zero Layout Shift)**:
  - Kemunculan teks `<p class="field-error-msg">` di bawah input dilarang keras menggeser atau mendorong elemen tombol atau field lain yang berada di baris grid yang sama (wajib menggunakan perataan `items-start` dan spacer label transparan pada kolom tombol, bukan `items-end`).
- **Pembersihan Otomatis Saat Mengetik (Live Cleanup)**:
  - Setiap input wajib memiliki event listener `input` dan `change` (`$input.on('input change', ...)`).
  - Begitu pengguna mulai mengetik atau mengubah isi input, tanda border merah, warna icon merah, dan teks pesan error di bawah field tersebut WAJIB langsung dibersihkan kembali ke style normal secara seketika.
- **Pencegahan Double Submit (Loading Spinner & Disable Button)**:
  - Setiap kali form di-submit dan telah dinyatakan valid, tombol submit WAJIB otomatis di-disable (`$btn.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none')`).
  - Tombol submit WAJIB menampilkan ikon loading spinner berputar (`<svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" ...>`) serta teks proses seperti "Menyimpan..." atau "Memproses..." untuk mencegah *double submit* dan pengiriman data ganda akibat multi-klik.
  - Jika validasi sisi klien (*client-side validation*) gagal / ditemukan field tidak valid (`hasError == true`), tombol submit TIDAK boleh di-disable (atau wajib dikembalikan ke kondisi normal) agar pengguna dapat langsung mengoreksi isian formulir.

## 6. Standar Tabel Data V2 (Kompak ~36px)
Untuk seluruh tabel data master dan transaksi Version 2:
- **Header Tabel (`thead`) Tetap Utuh**:
  - Menggunakan kombinasi warna tema solid Pacific Navy (`bg-[#284c9a]` untuk strip judul tabel dan `bg-[#1e3a78]` untuk header kolom).
  - Teks header putih kontras tinggi, huruf kapital, tebal (`font-bold uppercase tracking-wider text-white/90`).
- **Standar Padding & Tinggi Header Tabel (`thead`)**:
  - **Baris 1 - Strip Judul Tabel (`bg-[#284c9a]`)**:
    - Padding WAJIB `px-3.5 py-2` (atau `px-4 py-2`).
    - DILARANG menggunakan padding terlalu tipis/gepeng (`py-1` atau `py-1.5`) dan dilarang terlalu tebal (`py-3` atau `py-3.5`).
    - Memuat judul tabel `text-xs font-bold text-white`, icon tema `w-3.5 h-3.5` atau `w-4 h-4`, serta badge info / counter data di sebelah kanan.
  - **Baris 2 - Header Kolom (`bg-[#1e3a78]`)**:
    - Padding vertikal WAJIB `py-2` dan horizontal `px-3` (atau `px-3.5`), menghasilkan class `py-2 px-3`.
    - DILARANG menggunakan `py-1.5` pada header kolom agar tidak terkesan gepeng atau menjepit teks kolom header.
    - Teks: `text-[11px] font-bold uppercase tracking-wider text-white/90`.
- **Padding Sel Data (`td`) Kompak**:
  - Padding vertikal wajib menggunakan `py-1.5` (6px atas & 6px bawah).
  - Padding horizontal `px-3` atau `px-3.5` untuk teks nama/deskripsi.
- **Tombol Aksi Kompak**:
  - Tombol aksi (Edit, Hapus, Detail, Pilih) wajib berukuran `w-6 h-6 rounded-md` (24px × 24px).
  - Ikon Lucide di dalam tombol berukuran `w-3 h-3`.
  - Wadah tombol aksi wajib rapat dengan `flex items-center justify-center gap-1`.
- **Reset Tag Form Tombol Hapus**:
  - Tombol hapus yang dibungkus `<form>` wajib memakai class `inline-flex m-0 p-0 deleteform` agar tidak menambahkan margin tak terlihat dari browser.
- **No Text Wrap (Satu Baris Rapi)**:
  - Elemen `<table>` wajib menggunakan class `whitespace-nowrap` dan lebar kolom diatur proporsional (gunakan `min-w-[...]` atau `w-40`/`w-44` pada kolom teks panjang seperti Kategori/Group) agar data tidak tertekuk (wrap) menjadi 2 baris atau terpotong sempit, sehingga baris tetap seragam dan pas satu baris.
- **Target Tinggi Baris**:
  - Menghasilkan tinggi baris total **~36px** (standar emas enterprise ERP yang padat, rapi, dan mudah dibaca).
- **Colspan Strip Judul Tabel Presisi**:
  - Atribut `colspan` pada baris strip judul tabel (`bg-[#284c9a]`) WAJIB sama persis dengan total seluruh jumlah kolom data (`td`/`th` aktif) agar tidak menyisakan ruang/celah kosong putih di ujung kanan header.
- **Warna Background Card Header Pembungkus Tabel Samakan dengan Header Tabel**:
  - Jika sebuah tabel dibungkus dalam card yang memiliki baris Card Header tersendiri (misal: memuat judul tabel serta tombol aksi seperti "+ Input Pembayaran", "+ Input Giro", dsb.):
  - Warna latar belakang (*background color*) card header tersebut **WAJIB disamakan** dengan warna strip judul tabel Pacific Navy (`bg-[#284c9a] text-white`).
  - Dilarang keras menggunakan card header berwarna putih (`bg-white` atau `border-b border-slate-100`) tepat di atas header kolom tabel `bg-[#1e3a78]`, karena akan menimbulkan kontras terputus yang tidak harmonis.
  - Teks judul wajib `text-xs font-bold text-white uppercase tracking-wider`, icon Lucide `w-3.5 h-3.5 text-blue-200`, padding seragam `px-3.5 py-2`, dan tombol aksi menggunakan styling kompak (`h-[28px] px-3 rounded-lg text-xs font-bold`).
- **Proteksi Dropdown Menu pada Kolom Freeze / Sticky (Anti-Tertutup Baris Lain)**:
  - Pada kolom tabel yang memiliki posisi freeze / sticky (seperti kolom Aksi dengan `sticky right-0 z-10`), elemen dropdown menu (seperti tombol dropdown Cetak Faktur & Surat Jalan) yang dibuka WAJIB menaikkan stacking context parent cell (`<td>`) dan baris (`<tr>`) dengan class `!z-40` dan `!z-30 relative` saat menu terbuka.
  - Alasan: Semua cell sticky memiliki z-index yang sama (`z-10`). Berdasarkan aturan CSS stacking context, baris di bawahnya di-render lebih akhir di DOM sehingga otomatis menimpa/menutupi menu dropdown yang berada di baris atasnya.
  - Wajib selalu membersihkan class elevasi z-index ini (`$('td.sticky').removeClass('!z-40'); $('tr').removeClass('!z-30 relative');`) saat menu dropdown ditutup atau saat klik di luar menu.
  - Container tabel scroll (`overflow-x-auto`) wajib diberikan ruang vertikal yang memadai (misal `min-h-[280px]`) dan handler dropdown dilengkapi deteksi cerdas dropup vs dropdown jika posisi tombol berada dekat dengan bagian bawah tabel/layar.

## 7. Standar Komponen Pagination V2
Untuk seluruh halaman Version 2 yang menggunakan penomoran halaman (Pagination):
- **Wajib Menggunakan Komponen Standar**:
  - Panggil menggunakan `{{ $data->links('v2.components.pagination') }}` di dalam container pembungkus:
    ```html
    @if ($data->hasPages())
        <div class="px-4 py-3 border-t border-slate-100 dark:border-slate-800/80 bg-slate-50/50 dark:bg-slate-900/50">
            {{ $data->links('v2.components.pagination') }}
        </div>
    @endif
    ```
- **Proteksi Pemanggilan Total Data (Anti-Crash `Collection::total does not exist`)**:
  - Dilarang keras memanggil method `->total()` secara langsung/telanjang (misal: `{{ $data->total() }}` atau `{{ $data->total() ?? ... }}`) pada template Blade.
  - Alasan: Pada controller yang menggunakan `simplePaginate()` (`Illuminate\Pagination\Paginator`) atau `get()` (`Illuminate\Database\Eloquent\Collection`), method `total()` tidak tersedia. Operator `??` bawaan PHP tidak menangkap error method yang tidak ada, sehingga PHP langsung melempar fatal exception `Method Illuminate\Database\Eloquent\Collection::total does not exist`.
  - Wajib selalu menggunakan conditional method check:
    `{{ method_exists($data, 'total') ? $data->total() : $data->count() }}`
    atau simpan ke variabel di blok `@php`:
    `@php $totalData = method_exists($data, 'total') ? $data->total() : $data->count(); @endphp`
- **Dilarang Menulis Teks Menampilkan Ganda**:
  - Komponen `v2.components.pagination` sudah otomatis menyajikan teks info "Menampilkan X - Y dari Z data" di sebelah kiri dan navigasi halaman di sebelah kanan. Dilarang keras menambahkan tag `<span>Menampilkan...</span>` manual di luar pemanggilan pagination agar tidak terjadi teks ganda / duplicate showing.
- **Tampilan Tombol Halaman Pacific Navy**:
  - Halaman aktif berwarna solid Pacific Navy (`bg-[#284c9a] text-white font-bold font-mono-num shadow-xs`).
  - Halaman tidak aktif memiliki border halus (`border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-300 hover:bg-slate-100`).
  - Ukuran tombol kompak `h-7 min-w-[28px] px-2 rounded-lg text-xs` dengan navigasi panah SVG rapi.

## 8. Standar Navigasi & Posisi Scroll Sidebar V2
Untuk menjaga kenyamanan navigasi sidebar Version 2:
- **Preservasi Posisi & Auto-Focus Menu Aktif**:
  - Saat berpindah menu/halaman, sidebar dilarang keras mereset scroll ke posisi paling atas (`scrollTop: 0`).
  - Container `.sidebar-scroll-container` secara otomatis menyelaraskan posisi scroll agar menu yang sedang aktif (`.menu-item.active`) berada tepat di tengah (*vertical center*) area pandang sidebar.
  - Jika halaman tidak memiliki item menu aktif tertentu, posisi scroll terakhir dipulihkan secara otomatis dari `sessionStorage`.

## 9. Standar Modal Dialog V2 (Static Backdrop & Explicit Close)
Untuk seluruh modal dialog formulir Create, Edit, maupun konfirmasi di Version 2:
- **Header Modal Bersih (Tanpa Background Color Solid)**:
  - Header modal dialog WAJIB menggunakan latar belakang bersih tanpa warna blok solid (`bg-white dark:bg-slate-900 border-b border-slate-200/80 dark:border-slate-800`), DILARANG menggunakan background solid seperti `#284c9a` pada header modal.
  - Teks judul modal menggunakan warna standar tegas (`text-sm font-bold text-slate-800 dark:text-slate-100`) dengan icon tema di sisi kiri (`text-[#284c9a] dark:text-blue-400`).
  - Tombol close (`x`) di pojok kanan atas berpenampilan halus dan bersih (`w-7 h-7 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition`).
- **Static Backdrop (Anti-Tutup Sembarangan)**:
  - Dilarang keras menutup modal saat pengguna mengklik area luar / latar belakang gelap (*backdrop click*). Hal ini untuk mencegah hilangnya isian data form yang sedang diketik akibat salah klik atau interaksi dengan dropdown/kalender.
  - Dilarang menutup modal saat pengguna menekan tombol keyboard `Escape`.
- **Hanya Tutup Lewat Aksi Sadar / Eksplisit**:
  - Modal HANYA boleh ditutup jika pengguna secara eksplisit mengklik tombol icon close (`x`) di pojok kanan atas header modal atau tombol Batal.
- **Feedback Visual (Bounce / Pulse Animation)**:
  - Saat pengguna mengklik backdrop luar atau menekan `Escape`, kotak dialog modal wajib memberikan feedback visual berupa pantulan halus (`scale-[1.015] shadow-2xl` selama ~150ms) sebagai indikator jelas bahwa modal terkunci.

## 10. Standar Modal Detail & Tampilan Rincian Data V2
Untuk seluruh modal detail data (seperti Detail Transaksi, Detail BPB, Barang Masuk, Barang Keluar, Opname, Saldo Awal, dll.):
- **Header Informasi Terpadu**:
  - Strip ringkasan dokumen (Nomor Bukti / BPB, Tanggal, Pengaju / Supplier, Departemen / Cabang, Status badge) disatukan dalam satu card ringkas dan harmonis, tidak terpecah menjadi kotak-kotak terpisah yang timpang.
- **Metric Strip Pacific Navy**:
  - Jika modal menampilkan rekapitulasi angka/progres (misal: Total Order, Diserahkan, Sisa), gunakan strip metrik terpadu bertema Pacific Navy (`bg-[#284c9a]`) dengan inner divider halus, teks angka putih kontras (`font-mono-num font-black`), dan persentase pencapaian yang jelas.
- **Tabel Rincian Kompak (~36px)**:
  - Header tabel menggunakan standar 2 baris V2: Baris 1 strip `#284c9a` dengan icon dan jumlah item barang (`colspan` presisi penuh), Baris 2 header kolom `#1e3a78`.
  - Padding baris data kompak `py-1.5 px-3` dengan zebra/hover halus.
- **Jarak Proporsional Antar Seksi (Anti-Nempel)**:
  - Antara card formulir/tabel rincian dan card riwayat serah terima/log riwayat di bawahnya WAJIB diberi jarak pemisah yang proporsional (bungkus seluruh seksi modal dalam container `space-y-5` atau berikan jarak `mt-5`) agar elemen tidak saling menempel rapat.
- **Proteksi Format Angka 0 (Anti-Blank / Zero Fallback)**:
  - Dilarang keras membiarkan nilai kuantiti atau harga yang bernilai `0` tercetak kosong/blank. (Fungsi helper `formatAngkaDesimal()` bawaan mengevaluasi `!empty(0)` sebagai `false` sehingga mengembalikan string kosong `""`).
  - Wajib selalu menggunakan fungsi pemformatan aman yang menjamin angka 0 tetap tercetak `0,00`:
    `@php $numFormat = function($val) { return number_format((float)($val ?? 0), 2, ',', '.'); }; @endphp`
    atau `number_format((float)($val ?? 0), 2, ',', '.')`.
- **Empty State Riwayat / Rincian Rapi**:
  - Tampilan ketika rincian atau riwayat belum memiliki data wajib memiliki icon Lucide transparan dan teks petunjuk yang rapi di tengah.

## 11. Proteksi Helper Role & Navigasi Tab V2
- **Dilarang Memanggil Role Sebagai Fungsi Global (`Call to undefined function hasanyrole`)**:
  - `hasanyrole` dan `hasrole` adalah Blade directive bawaan Spatie Permission (`@hasanyrole(...)`, `@endhasanyrole`), **BUKAN** fungsi PHP global `hasanyrole()`.
  - Jika digunakan di dalam ekspresi ternary / PHP (misal di dalam atribut `class="{{ ... }}"`), WAJIB selalu memanggil method milik objek user:
    `auth()->user()->hasAnyRole($roles)` atau `auth()->user()->hasRole($role)`.
- **Wajib Menyediakan Navigasi Tab V2 (`v2.layouts.navigation_*`)**:
  - Setiap kali sub-modul meng-include baris navigasi tab pill (seperti `@include('layouts.navigation_mutasigudangjadi')` atau `navigation_mutasigudangcabang`), WAJIB dipastikan file padanan V2-nya telah dibuat di `resources/views/v2/layouts/`.
  - Styling navigasi tab V2 wajib horizontal pill yang rapi:
    - Container: `<div class="flex items-center gap-1.5 overflow-x-auto pb-1 scrollbar-none">`.
    - Tab Aktif: `bg-[#284c9a] text-white shadow-xs`.
    - Tab Tidak Aktif: `bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 text-slate-600 dark:text-slate-300 hover:text-brand-600 dark:hover:text-brand-400 hover:border-brand-200 dark:hover:border-brand-900 hover:bg-brand-50/50 dark:hover:bg-brand-950/30`.
    - Icon: Lucide icon dengan ukuran `w-4 h-4`.
  - Dilarang membiarkan halaman V2 me-load navigasi Bootstrap lama (`<ul class="nav nav-pills nav-pills-custom">`) karena tag `<li>` tanpa Tailwind flex akan runtuh menjadi daftar vertikal bertumpuk.

## 12. Prinsip Paritas Lengkap & Preservasi Istilah Asli (Zero Omission & 100% Label Parity)
- **Wajib Audit Komprehensif Seluruh Elemen V1 Sebelum & Saat Migrasi**:
  - Sebelum dan saat membuat halaman atau formulir Version 2 (baik halaman `index`, `create`, `edit`, `show`, modal dialog, maupun komponen terkait), asisten AI **WAJIB** membaca dan mengaudit seluruh file V1 padanannya terlebih dahulu secara menyeluruh baris demi baris.
- **Dilarang Keras Menghilangkan / Melewatkan Field & Komponen Apapun (Anti-Omission)**:
  - Setiap elemen input pada formulir V1 (input teks, select box, tanggal, radio/checkbox/switch, textarea, file upload, hidden input, peta/koordinat, dsb.) **WAJIB** dicantumkan lengkap pada formulir V2.
  - Jangan sampai ada data, bagian, atau kontrol input yang hilang atau terlewat saat restyling ke V2 (seperti contoh kasus: field titik koordinat GPS / picker peta lokasi pelanggan, NIK, KK, limit kredit, status jaminan, opsi dropdown, keterangan tambahan, dsb.).
  - Jika V1 memiliki fitur visual atau integrasi khusus (misal: Leaflet map, kalkulator dinamis, modal pencarian referensi, status baris, dsb.), seluruh fitur tersebut **WAJIB** diikutsertakan dan berfungsi 100% pada V2.
- **Dilarang Keras Mengubah Istilah / Label Asli Bawaan V1 (Preservasi Label Verbatim)**:
  - Teks label form, header kolom, judul ringkasan, dan baris footer tabel (`tfoot`) **DILARANG KERAS** diubah, diganti, disingkat, atau ditambahi kata-kata baru yang tidak ada pada V1.
  - Wajib mempertahankan istilah dan penamaan asli dari V1 secara persis (*verbatim*).
  - Contoh larangan:
    - Dilarang mengubah `TOTAL` menjadi `TOTAL DPP` atau `TOTAL RETUR`. Jika di V1 tertulis `TOTAL`, maka di V2 wajib tetap tertulis `TOTAL`.
    - Dilarang mengubah `GRAND TOTAL` menjadi `GRAND TOTAL NETTO`.
    - Dilarang mengubah `JUMLAH BAYAR` menjadi `JUMLAH TERBAYAR`.
    - Dilarang mengubah `SISA BAYAR` menjadi `SISA PIUTANG / TAGIHAN`.
    - Dilarang mengubah `SUBTOTAL` menjadi `SUBTOTAL BRUTO`.
    - Dilarang mengubah `POTONG FAKTUR` menjadi `POTONG FAKTUR (PF)`.
    - Dilarang mengubah `GANTI BARANG` menjadi `GANTI BARANG (GB)`.
  - Dilarang mengimprovisasi penamaan label tampilan UI berdasarkan nama variabel internal backend/PHP (contoh: variabel `$total_dpp` di controller/view tidak boleh dijadikan label tampilan `TOTAL DPP` apabila label UI aslinya adalah `TOTAL`).
- **Audit Checklist Verifikasi Form V1 vs V2**:
  - Setelah menyusun form Create atau Edit V2, lakukan verifikasi silang (*cross-check*) setiap atribut `name="..."` dan `id="..."` terhadap form V1 untuk menjamin tidak ada satu pun field yang tertinggal (*100% field parity*).
  - Pastikan seluruh parameter dan data yang dikirimkan ke backend controller tidak berkurang sedikit pun dari versi V1.
- **Paritas Fitur, Kolom Data, & Tombol Aksi**:
  - Seluruh kolom data penting pada tabel V1 dan seluruh tombol aksi (Cetak, Export, Detail, Status, Logika Khusus, dsb.) harus tetap tersedia dan berfungsi dengan baik di V2.

## 13. Standar Page Title Bar & Navigasi Breadcrumb V2 (Konsistensi Layout /penjualan)
Untuk menjaga konsistensi arsitektur header dan navigasi di seluruh halaman Version 2:
- **Navigasi Navbar (`@section('navigasi')`) HANYA untuk Breadcrumb Ringkas**:
  - Blok `@section('navigasi')` di-render langsung di dalam top navbar global (`v2.layouts.navbar`).
  - **DILARANG KERAS** meletakkan judul halaman (`<h3>`/`<h4>`), deskripsi/subjudul modul (`<p>`), tombol aksi, atau layout dua kolom di dalam `@section('navigasi')`.
  - `@section('navigasi')` WAJIB HANYA berisi navigasi breadcrumb inline yang ringkas dengan teks abu-abu dan pemisah chevron (`chevron-right`):
    ```html
    @section('navigasi')
        <div class="flex items-center gap-1.5 text-xs">
            <span class="text-slate-400">Home</span>
            <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
            <span class="text-slate-400">Marketing</span>
            <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
            <span class="text-brand-600 dark:text-brand-400 font-bold">Penjualan</span>
        </div>
    @endsection
    ```
- **Page Title Bar & Action Buttons Wajib di Dalam `@section('content')`**:
  - Judul halaman (`<h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">`), deskripsi halaman (`<p class="text-[11px] text-slate-400 mt-0.5">`), dan tombol aksi utama halaman (seperti `Input Penjualan`, `Input Retur`, `+ Tambah Data`, dsb.) **WAJIB diletakkan di baris paling atas di dalam `@section('content')`**:
    ```html
    <!-- PAGE TITLE BAR & ACTION BUTTONS -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Data Penjualan</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Kelola data transaksi penjualan barang, faktur, surat jalan, dan penerimaan pesanan.</p>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            @can('penjualan.create')
                <a href="{{ route('penjualan.create') }}" id="btnCreate"
                    class="inline-flex items-center justify-center gap-1.5 h-[34px] px-3.5 rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs font-bold transition shadow-xs cursor-pointer shrink-0">
                    <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                    <span>Input Penjualan</span>
                </a>
            @endcan
        </div>
    </div>
    ```
- **Struktur Halaman Utama yang Rapi & Seragam**:
  1. Top Navbar: Hanya breadcrumb ringkas (`@section('navigasi')`).
  2. Main Content Area (`@section('content')`):
     - Baris 1: Page Title Bar & Action Buttons (Judul `h3 font-black text-base`, deskripsi `p text-[11px] text-slate-400`, tombol aksi di kanan).
     - Baris 2: Filter Card (No-Label, compact `h-[36px]`).
     - Baris 3: Data Table Card (~36px compact rows, header solid Pacific Navy `#284c9a` & `#1e3a78`).

- **Keselarasan Lebar Section Content (Page Title Bar, Filter, & Tabel Wajib Sejajar)**:
  - Seluruh elemen utama pada area konten (Baris Page Title Bar beserta tombol aksi, Card Filter Pencarian, dan Card Tabel Data) **WAJIB dibungkus dalam satu kontainer dengan lebar yang seragam dan konsisten** (misal: `<div class="w-full space-y-3.5">` untuk tabel lebar seperti Penjualan/Retur, atau `<div class="max-w-4xl space-y-3.5">` / `<div class="max-w-5xl space-y-3.5">` untuk tabel ringkas seperti Saldo Awal).
  - **DILARANG KERAS** membiarkan Page Title Bar melebar penuh ke kanan (`w-full`) sementara Card Filter menggunakan `max-w-3xl` dan Card Tabel menggunakan `max-w-4xl`/`max-w-5xl`. Hal tersebut menyebabkan tombol aksi utama terlempar jauh ke tepi kanan layar dan filter menjadi lebih pendek dari tabel (*misaligned / tidak sejajar*).
  - Setiap elemen anak (Page Title Bar, Filter Card, dan Tabel Card) wajib menggunakan lebar penuh terhadap container pembungkusnya (`w-full` di dalam wrapper yang ditentukan), sehingga sisi kiri dan sisi kanan dari Page Title Bar, tombol aksi, filter form, dan tabel data selalu **sejajar sempurna (*perfectly aligned/flush*)**.
  - **Tombol Aksi Tunggal (Anti-Duplikasi)**: Tombol aksi utama (seperti `Input Penjualan`, `Input Retur`, `+ Tambah Data`) cukup diletakkan pada Page Title Bar di kanan atas. Dilarang menduplikasi tombol yang sama di dalam header strip tabel (`thead`), kecuali jika halaman tidak menggunakan Page Title Bar terpisah. Header strip tabel cukup memuat Judul Tabel, Ikon, dan Counter Total Data (`Total: X Data`).






