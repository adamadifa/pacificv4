<form action="#" id="formEditproduk" class="space-y-3">
    <input type="hidden" name="isi_pcs_dus" id="isi_pcs_dus">
    <input type="hidden" name="isi_pcs_pack" id="isi_pcs_pack">
    <input type="hidden" name="index_old" id="index_old" value="{{ $dataproduk['index_old'] }}">
    
    <!-- Pilih Produk (Disabled) -->
    <div class="field-group">
        <div class="relative">
            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="package" class="w-3.5 h-3.5"></i>
            </div>
            <select name="kode_harga" id="kode_harga"
                class="w-full h-[36px] pl-8 pr-3 text-xs bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-slate-500 font-semibold cursor-not-allowed"
                disabled>
                <option value="">Pilih Produk</option>
                @foreach ($harga as $d)
                    <option data-isi_pcs_dus="{{ $d->isi_pcs_dus }}" data-isi_pcs_pack="{{ $d->isi_pcs_pack }}"
                        data-harga_dus="{{ formatAngka($d->harga_retur_dus) }}" data-harga_pack="{{ formatAngka($d->harga_retur_pack) }}"
                        data-harga_pcs="{{ formatAngka($d->harga_retur_pcs) }}" {{ $dataproduk['kode_harga'] == $d->kode_harga ? 'selected' : '' }}
                        value="{{ $d->kode_harga }}">
                        {{ $d->nama_produk }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Grid 3 Kolom: DUS, PACK, PCS -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5">
        
        <!-- DUS -->
        <div class="space-y-1.5">
            <div class="relative flex rounded-lg shadow-2xs">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="box" class="w-3.5 h-3.5"></i>
                </div>
                <input type="text" name="jml_dus" id="jml_dus" value="{{ $dataproduk['jml_dus'] }}" placeholder="0"
                    class="money w-full h-[36px] pl-8 pr-2.5 text-right font-bold font-mono-num text-xs rounded-l-lg border border-r-0 border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 focus:z-10 transition">
                <span class="inline-flex items-center justify-center h-[36px] px-2.5 rounded-r-lg border border-l-0 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-bold text-[11px] uppercase tracking-wider select-none shrink-0 min-w-[50px]">
                    DUS
                </span>
            </div>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="banknote" class="w-3.5 h-3.5"></i>
                </div>
                <input type="text" name="harga_dus" id="harga_dus" value="{{ $dataproduk['harga_dus'] }}" placeholder="Harga / Dus"
                    class="money w-full h-[36px] pl-8 pr-3 text-right font-bold font-mono-num text-xs rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
            </div>
        </div>

        <!-- PACK -->
        <div class="space-y-1.5">
            <div class="relative flex rounded-lg shadow-2xs">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="box" class="w-3.5 h-3.5"></i>
                </div>
                <input type="text" name="jml_pack" id="jml_pack" value="{{ $dataproduk['jml_pack'] }}" placeholder="0"
                    class="money w-full h-[36px] pl-8 pr-2.5 text-right font-bold font-mono-num text-xs rounded-l-lg border border-r-0 border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 focus:z-10 transition">
                <span class="inline-flex items-center justify-center h-[36px] px-2.5 rounded-r-lg border border-l-0 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-bold text-[11px] uppercase tracking-wider select-none shrink-0 min-w-[50px]">
                    PACK
                </span>
            </div>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="banknote" class="w-3.5 h-3.5"></i>
                </div>
                <input type="text" name="harga_pack" id="harga_pack" value="{{ $dataproduk['harga_pack'] }}" placeholder="Harga / Pack"
                    class="money w-full h-[36px] pl-8 pr-3 text-right font-bold font-mono-num text-xs rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
            </div>
        </div>

        <!-- PCS -->
        <div class="space-y-1.5">
            <div class="relative flex rounded-lg shadow-2xs">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="box" class="w-3.5 h-3.5"></i>
                </div>
                <input type="text" name="jml_pcs" id="jml_pcs" value="{{ $dataproduk['jml_pcs'] }}" placeholder="0"
                    class="money w-full h-[36px] pl-8 pr-2.5 text-right font-bold font-mono-num text-xs rounded-l-lg border border-r-0 border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 focus:z-10 transition">
                <span class="inline-flex items-center justify-center h-[36px] px-2.5 rounded-r-lg border border-l-0 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-bold text-[11px] uppercase tracking-wider select-none shrink-0 min-w-[50px]">
                    PCS
                </span>
            </div>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="banknote" class="w-3.5 h-3.5"></i>
                </div>
                <input type="text" name="harga_pcs" id="harga_pcs" value="{{ $dataproduk['harga_pcs'] }}" placeholder="Harga / Pcs"
                    class="money w-full h-[36px] pl-8 pr-3 text-right font-bold font-mono-num text-xs rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
            </div>
        </div>

    </div>

    <!-- Tombol Submit -->
    <div class="pt-2">
        <button type="submit" id="btnUpdateproduk"
            class="w-full h-[36px] rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] active:bg-[#152852] text-white text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-2xs cursor-pointer">
            <i data-lucide="save" class="w-4 h-4"></i>
            <span>Update Produk</span>
        </button>
    </div>
</form>

<script>
    $(function() {
        if (typeof $.fn.maskMoney !== 'undefined') {
            $(".money").maskMoney({
                thousands: '.',
                decimal: ',',
                precision: 0,
                allowZero: true
            });
        }

        const $form = $("#formEditproduk");

        function initHarga() {
            const nama_pelanggan = $("#nama_pelanggan").val() || '';
            let selectedOption = $form.find("#kode_harga").find(':selected');
            let isi_pcs_dus = selectedOption.data('isi_pcs_dus');
            let isi_pcs_pack = selectedOption.data('isi_pcs_pack');

            if (isi_pcs_pack == "" || isi_pcs_pack === '0' || isi_pcs_pack === 0) {
                $form.find("#jml_pack").prop('disabled', true).addClass('bg-slate-100 dark:bg-slate-800 opacity-60 cursor-not-allowed');
                $form.find("#harga_pack").prop('disabled', true).addClass('bg-slate-100 dark:bg-slate-800 opacity-60 cursor-not-allowed');
            } else {
                $form.find("#jml_pack").prop('disabled', false).removeClass('bg-slate-100 dark:bg-slate-800 opacity-60 cursor-not-allowed');
                $form.find("#harga_pack").prop('disabled', false).removeClass('bg-slate-100 dark:bg-slate-800 opacity-60 cursor-not-allowed');
            }

            $form.find("#isi_pcs_dus").val(isi_pcs_dus);
            $form.find("#isi_pcs_pack").val(isi_pcs_pack);

            $form.find("#harga_dus").prop('disabled', false);
            $form.find("#harga_pcs").prop('disabled', false);
        }

        initHarga();

        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
</script>
