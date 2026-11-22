<div class="space-y-3">
    <!-- Kode Barang -->
    <div class="field-group">
        <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Kode Barang
        </label>
        <div class="relative">
            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="barcode" class="w-4 h-4"></i>
            </div>
            <input type="text" name="no_bukti_edit" id="no_bukti_edit" value="{{ $detail->kode_barang_produksi }}"
                class="w-full h-[36px] pl-9 pr-3 text-xs font-bold font-mono-num rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-500"
                readonly disabled>
        </div>
    </div>

    <!-- Nama Barang -->
    <div class="field-group">
        <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Nama Barang
        </label>
        <div class="relative">
            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="package" class="w-4 h-4"></i>
            </div>
            <input type="text" name="nama_barang_edit" id="nama_barang_edit" value="{{ $detail->nama_barang }}"
                class="w-full h-[36px] pl-9 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-700 dark:text-slate-300"
                readonly disabled>
        </div>
    </div>

    <!-- Keterangan -->
    <div class="field-group">
        <label for="keterangan_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Keterangan
        </label>
        <div class="relative">
            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="file-text" class="w-4 h-4"></i>
            </div>
            <input type="text" name="keterangan_edit" id="keterangan_edit" value="{{ $detail->keterangan }}"
                class="w-full h-[36px] pl-9 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                placeholder="Keterangan (Opsional)">
        </div>
    </div>

    <!-- Jumlah -->
    <div class="field-group">
        <label for="jumlah_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Jumlah <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="layers" class="w-4 h-4"></i>
            </div>
            <input type="text" name="jumlah_edit" id="jumlah_edit" value="{{ formatAngkaDesimal($detail->jumlah) }}"
                class="number-separator-edit w-full h-[36px] pl-9 pr-3 text-xs font-bold font-mono-num text-right rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                placeholder="0">
        </div>
    </div>

    <!-- Tombol Aksi -->
    <div class="pt-2">
        <button type="button" id="updatebarang" id_edit="{{ $detail->id }}"
            class="w-full h-[36px] inline-flex items-center justify-center gap-1.5 rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs font-bold transition shadow-xs cursor-pointer">
            <i data-lucide="send" class="w-3.5 h-3.5"></i>
            <span>Update Barang</span>
        </button>
    </div>
</div>

<script>
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
    if (typeof easyNumberSeparator !== 'undefined') {
        easyNumberSeparator({
            selector: '.number-separator-edit',
            separator: '.',
            decimalSeparator: ','
        });
    }
</script>
