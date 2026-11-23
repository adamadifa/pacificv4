<form action="#" id="formEditproduk" class="space-y-3" autocomplete="off">
    <input type="hidden" name="isi_pcs_dus" id="isi_pcs_dus">
    <input type="hidden" name="isi_pcs_pack" id="isi_pcs_pack">
    <input type="hidden" name="kode_kategori_diskon" id="kode_kategori_diskon">
    <input type="hidden" name="kode_produk" id="kode_produk">
    <input type="hidden" name="index_old" id="index_old" value="{{ $dataproduk['index_old'] }}">
    
    <input type="hidden" name="kode_harga" id="kode_harga" value="{{ $dataproduk['kode_harga'] }}"
        data-isi_pcs_dus="{{ $dataproduk['isi_pcs_dus'] }}" data-isi_pcs_pack="{{ $dataproduk['isi_pcs_pack'] }}"
        data-kode_kategori_diskon="{{ $dataproduk['kode_kategori_diskon'] }}" data-kode_produk="{{ $dataproduk['kode_produk'] }}"
        data-harga_dus="{{ $dataproduk['harga_dus'] }}" data-harga_pack="{{ $dataproduk['harga_pack'] }}"
        data-harga_pcs="{{ $dataproduk['harga_pcs'] }}">

    <!-- NAMA PRODUK -->
    <div>
        <div class="relative">
            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="package" class="w-3.5 h-3.5"></i>
            </div>
            <input type="text" name="nama_produk_label" id="nama_produk_label" value="{{ $dataproduk['nama_produk'] }}" placeholder="Nama Produk" readonly
                class="w-full h-[36px] pl-8 pr-3 text-xs font-bold rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-slate-100 cursor-not-allowed">
        </div>
    </div>

    <!-- DUS & HARGA DUS -->
    <div class="grid grid-cols-12 gap-2">
        <div class="col-span-5">
            <div class="relative flex rounded-lg shadow-2xs">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="box" class="w-3.5 h-3.5"></i>
                </div>
                <input type="text" name="jml_dus" id="jml_dus" value="{{ $dataproduk['jml_dus'] }}" placeholder="0"
                    class="money w-full h-[36px] pl-8 pr-2.5 text-right text-xs font-bold font-mono-num rounded-l-lg border border-r-0 border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 focus:z-10 transition">
                <span class="inline-flex items-center justify-center h-[36px] px-2.5 rounded-r-lg border border-l-0 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-bold text-[11px] uppercase tracking-wider select-none shrink-0 min-w-[48px]">
                    DUS
                </span>
            </div>
        </div>
        <div class="col-span-7">
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="banknote" class="w-3.5 h-3.5"></i>
                </div>
                <input type="text" name="harga_dus" id="harga_dus" value="{{ $dataproduk['harga_dus'] }}" placeholder="Harga / Dus"
                    class="money w-full h-[36px] pl-8 pr-3 text-right text-xs font-bold font-mono-num rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
            </div>
        </div>
    </div>

    <!-- PACK & HARGA PACK -->
    <div class="grid grid-cols-12 gap-2">
        <div class="col-span-5">
            <div class="relative flex rounded-lg shadow-2xs">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="layers" class="w-3.5 h-3.5"></i>
                </div>
                <input type="text" name="jml_pack" id="jml_pack" value="{{ $dataproduk['jml_pack'] }}" placeholder="0"
                    class="money w-full h-[36px] pl-8 pr-2.5 text-right text-xs font-bold font-mono-num rounded-l-lg border border-r-0 border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 focus:z-10 transition">
                <span class="inline-flex items-center justify-center h-[36px] px-2.5 rounded-r-lg border border-l-0 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-bold text-[11px] uppercase tracking-wider select-none shrink-0 min-w-[48px]">
                    PACK
                </span>
            </div>
        </div>
        <div class="col-span-7">
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="banknote" class="w-3.5 h-3.5"></i>
                </div>
                <input type="text" name="harga_pack" id="harga_pack" value="{{ $dataproduk['harga_pack'] }}" placeholder="Harga / Pack"
                    class="money w-full h-[36px] pl-8 pr-3 text-right text-xs font-bold font-mono-num rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
            </div>
        </div>
    </div>

    <!-- PCS & HARGA PCS -->
    <div class="grid grid-cols-12 gap-2">
        <div class="col-span-5">
            <div class="relative flex rounded-lg shadow-2xs">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="circle-dot" class="w-3.5 h-3.5"></i>
                </div>
                <input type="text" name="jml_pcs" id="jml_pcs" value="{{ $dataproduk['jml_pcs'] }}" placeholder="0"
                    class="money w-full h-[36px] pl-8 pr-2.5 text-right text-xs font-bold font-mono-num rounded-l-lg border border-r-0 border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 focus:z-10 transition">
                <span class="inline-flex items-center justify-center h-[36px] px-2.5 rounded-r-lg border border-l-0 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-bold text-[11px] uppercase tracking-wider select-none shrink-0 min-w-[48px]">
                    PCS
                </span>
            </div>
        </div>
        <div class="col-span-7">
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="banknote" class="w-3.5 h-3.5"></i>
                </div>
                <input type="text" name="harga_pcs" id="harga_pcs" value="{{ $dataproduk['harga_pcs'] }}" placeholder="Harga / Pcs"
                    class="money w-full h-[36px] pl-8 pr-3 text-right text-xs font-bold font-mono-num rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
            </div>
        </div>
    </div>

    <!-- STATUS PROMOSI -->
    <div class="pt-0.5">
        <label class="inline-flex items-center gap-2 cursor-pointer select-none">
            <input type="checkbox" name="status_promosi_edit" id="status_promosi_edit" value="1"
                class="status_promosi_edit w-4 h-4 rounded border-slate-300 dark:border-slate-700 text-[#284c9a] focus:ring-[#284c9a]/20 cursor-pointer"
                {{ $dataproduk['status_promosi'] == '1' ? 'checked' : '' }}>
            <span class="text-xs font-bold text-slate-700 dark:text-slate-300">Barang Promosi (Harga Rp 0)</span>
        </label>
    </div>

    <!-- ACTION BUTTONS -->
    <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100 dark:border-slate-800">
        <button type="submit" id="btnUpdateproduk"
            class="w-full h-[36px] rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] active:bg-[#193066] text-white text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-xs cursor-pointer">
            <i data-lucide="check" class="w-3.5 h-3.5"></i>
            <span>Update Produk</span>
        </button>
    </div>
</form>

<script>
    $(function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        if ($.fn.maskMoney) {
            $(".money").maskMoney();
        }

        const form = $("#formEditproduk");

        function getharga() {
            let selectedOption = form.find("#kode_harga");
            let isi_pcs_dus = selectedOption.data('isi_pcs_dus');
            let isi_pcs_pack = selectedOption.data('isi_pcs_pack');
            let kode_kategori_diskon = selectedOption.data('kode_kategori_diskon');

            let harga_dus;
            let harga_pack;
            let harga_pcs;
            if (form.find('#status_promosi_edit').is(':checked')) {
                harga_dus = 0;
                harga_pack = 0;
                harga_pcs = 0;
            } else {
                harga_dus = selectedOption.data('harga_dus');
                harga_pack = selectedOption.data('harga_pack');
                harga_pcs = selectedOption.data('harga_pcs');
            }

            form.find("#isi_pcs_dus").val(isi_pcs_dus);
            form.find("#isi_pcs_pack").val(isi_pcs_pack);
            form.find("#kode_kategori_diskon").val(kode_kategori_diskon);

            form.find("#harga_dus").val(harga_dus);
            form.find("#harga_pack").val(harga_pack);
            form.find("#harga_pcs").val(harga_pcs);

            if (isi_pcs_pack == "" || isi_pcs_pack === '0') {
                form.find("#jml_pack").prop('disabled', true).addClass('bg-slate-50 dark:bg-slate-800/60 cursor-not-allowed');
                form.find("#harga_pack").prop('disabled', true).addClass('bg-slate-50 dark:bg-slate-800/60 cursor-not-allowed');
            } else {
                form.find("#jml_pack").prop('disabled', false).removeClass('bg-slate-50 dark:bg-slate-800/60 cursor-not-allowed');
                form.find("#harga_pack").prop('disabled', false).removeClass('bg-slate-50 dark:bg-slate-800/60 cursor-not-allowed');
            }
        }

        form.find('.status_promosi_edit').change(function() {
            getharga();
        });

        function initHarga() {
            const nama_pelanggan = $("#nama_pelanggan").val() || "";
            let selectedOption = form.find("#kode_harga");
            let isi_pcs_dus = selectedOption.data('isi_pcs_dus');
            let isi_pcs_pack = selectedOption.data('isi_pcs_pack');
            let kode_kategori_diskon = selectedOption.data('kode_kategori_diskon');
            let kode_produk = selectedOption.data('kode_produk');

            if (isi_pcs_pack == "" || isi_pcs_pack === '0') {
                form.find("#jml_pack").prop('disabled', true).addClass('bg-slate-50 dark:bg-slate-800/60 cursor-not-allowed');
            } else {
                form.find("#jml_pack").prop('disabled', false).removeClass('bg-slate-50 dark:bg-slate-800/60 cursor-not-allowed');
            }

            form.find("#isi_pcs_dus").val(isi_pcs_dus);
            form.find("#isi_pcs_pack").val(isi_pcs_pack);
            form.find("#kode_kategori_diskon").val(kode_kategori_diskon);
            form.find("#kode_produk").val(kode_produk);

            if (nama_pelanggan.includes('KPBN') || nama_pelanggan.includes('RSB')) {
                form.find("#harga_dus").prop('disabled', false).removeClass('bg-slate-50 dark:bg-slate-800/60 cursor-not-allowed');
                if (isi_pcs_pack == "" || isi_pcs_pack === 0) {
                    form.find("#harga_pack").prop('disabled', true).addClass('bg-slate-50 dark:bg-slate-800/60 cursor-not-allowed');
                } else {
                    form.find("#harga_pack").prop('disabled', false).removeClass('bg-slate-50 dark:bg-slate-800/60 cursor-not-allowed');
                }
                form.find("#harga_pcs").prop('disabled', false).removeClass('bg-slate-50 dark:bg-slate-800/60 cursor-not-allowed');
            } else {
                if (isi_pcs_pack == "" || isi_pcs_pack === 0) {
                    form.find("#harga_pack").prop('disabled', true).addClass('bg-slate-50 dark:bg-slate-800/60 cursor-not-allowed');
                } else {
                    form.find("#harga_pack").prop('disabled', false).removeClass('bg-slate-50 dark:bg-slate-800/60 cursor-not-allowed');
                }
            }
        }

        initHarga();
    });
</script>
