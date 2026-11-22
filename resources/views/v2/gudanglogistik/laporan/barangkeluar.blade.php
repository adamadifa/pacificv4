<form method="POST" action="{{ route('laporangudanglogistik.cetakbarangkeluar') }}" id="frmLaporanbarangkeluar" target="_blank" class="space-y-3.5" autocomplete="off">
    @csrf

    <!-- Jenis Pengeluaran -->
    <div class="field-group">
        <label for="kode_jenis_pengeluaran" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Jenis Pengeluaran
        </label>
        <div class="relative">
            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                <i data-lucide="tag" class="w-4 h-4"></i>
            </div>
            <select name="kode_jenis_pengeluaran" id="kode_jenis_pengeluaran"
                class="w-full h-[36px] pl-9 pr-7 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition appearance-none cursor-pointer">
                <option value="">Semua Jenis Pengeluaran</option>
                @foreach ($list_jenis_pengeluaran as $d)
                    <option value="{{ $d['kode_jenis_pengeluaran'] }}">{{ $d['jenis_pengeluaran'] }}</option>
                @endforeach
            </select>
            <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                <i data-lucide="chevron-down" class="w-4 h-4"></i>
            </div>
        </div>
    </div>

    <!-- Cabang -->
    <div class="field-group">
        <label for="kode_cabang_keluar" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Cabang Tujuan
        </label>
        <div class="relative">
            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                <i data-lucide="building" class="w-4 h-4"></i>
            </div>
            <select name="kode_cabang" id="kode_cabang_keluar" class="Select2Kodecabang w-full">
                <option value="">Semua Cabang</option>
                @foreach ($cabang as $c)
                    <option value="{{ $c->kode_cabang }}">{{ $c->nama_cabang }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Kategori -->
    <div class="field-group">
        <label for="kode_kategori_keluar" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Kategori Barang
        </label>
        <div class="relative">
            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                <i data-lucide="layers" class="w-4 h-4"></i>
            </div>
            <select name="kode_kategori" id="kode_kategori_keluar"
                class="w-full h-[36px] pl-9 pr-7 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition appearance-none cursor-pointer">
                <option value="">Semua Kategori</option>
                @foreach ($kategori as $d)
                    <option value="{{ $d->kode_kategori }}">{{ $d->nama_kategori }}</option>
                @endforeach
            </select>
            <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                <i data-lucide="chevron-down" class="w-4 h-4"></i>
            </div>
        </div>
    </div>

    <!-- Barang -->
    <div class="field-group">
        <label for="kode_barang_keluar" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Barang
        </label>
        <div class="relative">
            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                <i data-lucide="box" class="w-4 h-4"></i>
            </div>
            <select name="kode_barang" id="kode_barang_keluar" class="select2Kodebarangkeluar w-full">
                <option value="">Semua Barang</option>
            </select>
        </div>
    </div>

    <!-- Rentang Periode -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 items-start">
        <div class="field-group">
            <label for="dari_keluar" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Dari Tanggal <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                </div>
                <input type="text" name="dari" id="dari_keluar" placeholder="Pilih Tanggal Mulai" readonly
                    class="flatpickr-date w-full h-[36px] pl-9 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer">
            </div>
        </div>

        <div class="field-group">
            <label for="sampai_keluar" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Sampai Tanggal <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                </div>
                <input type="text" name="sampai" id="sampai_keluar" placeholder="Pilih Tanggal Selesai" readonly
                    class="flatpickr-date w-full h-[36px] pl-9 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer">
            </div>
        </div>
    </div>

    <!-- Tombol Cetak & Export -->
    <div class="pt-3 border-t border-slate-100 dark:border-slate-800 flex items-center gap-2">
        <button type="submit" name="submitButton"
            class="flex-1 h-[36px] inline-flex items-center justify-center gap-2 px-4 rounded-lg bg-[#284c9a] hover:bg-blue-800 active:bg-blue-900 text-white text-xs font-bold transition shadow-xs cursor-pointer">
            <i data-lucide="printer" class="w-4 h-4"></i>
            <span>Cetak Dokumen</span>
        </button>

        <button type="submit" name="exportButton"
            class="h-[36px] inline-flex items-center justify-center gap-2 px-4 rounded-lg bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white text-xs font-bold transition shadow-xs cursor-pointer"
            title="Download Excel">
            <i data-lucide="download" class="w-4 h-4"></i>
            <span class="hidden sm:inline">Export Excel</span>
        </button>
    </div>
</form>

<script>
    $(function() {
        const form = $("#frmLaporanbarangkeluar");

        function getbarangbykategori() {
            var kode_kategori = form.find("#kode_kategori_keluar").val();
            $.ajax({
                type: 'POST',
                url: '/barangpembelian/getbarangbykategori',
                data: {
                    _token: "{{ csrf_token() }}",
                    kode_kategori: kode_kategori
                },
                cache: false,
                success: function(respond) {
                    form.find("#kode_barang_keluar").html(respond);
                    form.find("#kode_barang_keluar").trigger('change');
                }
            });
        }

        form.find("#kode_kategori_keluar").on('change', function() {
            getbarangbykategori();
        });

        form.find('.select2Kodebarangkeluar').select2({
            placeholder: 'Semua Barang',
            allowClear: true,
            width: '100%'
        });

        form.find('.Select2Kodecabang').select2({
            placeholder: 'Semua Cabang',
            allowClear: true,
            width: '100%'
        });
    });
</script>
