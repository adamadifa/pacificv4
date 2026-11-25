<form action="#" id="formAddproduk" class="space-y-3 no-auto-loading">
    <input type="hidden" name="isi_pcs_dus" id="isi_pcs_dus">
    <input type="hidden" name="isi_pcs_pack" id="isi_pcs_pack">
    <input type="hidden" name="kode_kategori_diskon" id="kode_kategori_diskon">
    <input type="hidden" name="kode_produk" id="kode_produk">

    <div class="field-group">
        <div class="relative">
            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                <i data-lucide="package" class="w-4 h-4"></i>
            </div>
            <select name="kode_harga" id="kode_harga"
                class="select2KodehargaAdd w-full h-[40px] sm:h-[36px] pl-9 pr-8 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200">
                <option value="">Pilih Produk</option>
                @foreach ($harga as $d)
                    @php
                        $harga_dus = str_contains($pelanggan->nama_pelanggan, 'KPBN') ? $d->harga_dus + 1000 : $d->harga_dus;
                    @endphp
                    <option data-isi_pcs_dus="{{ $d->isi_pcs_dus }}"
                        data-isi_pcs_pack="{{ $d->isi_pcs_pack }}"
                        data-harga_dus="{{ formatAngka($harga_dus) }}"
                        data-harga_pack="{{ formatAngka($d->harga_pack) }}"
                        data-harga_pcs="{{ formatAngka($d->harga_pcs) }}"
                        data-kode_kategori_diskon="{{ $d->kode_kategori_diskon }}"
                        value="{{ $d->kode_harga }}"
                        data-kode_produk="{{ $d->kode_produk }}">
                        {{ $d->nama_produk }} ({{ $d->kode_kategori_salesman }})
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="grid grid-cols-12 gap-2 items-start">
        <div class="col-span-5 field-group">
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="box" class="w-4 h-4"></i>
                </div>
                <input type="text" inputmode="numeric" name="jml_dus" id="jml_dus" value="" placeholder="0"
                    class="money text-right w-full h-[40px] sm:h-[36px] pl-8 pr-10 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-bold font-mono-num text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-[11px] font-bold text-slate-400">
                    Dus
                </div>
            </div>
        </div>
        <div class="col-span-7">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="banknote" class="w-4 h-4"></i>
                </div>
                <input type="text" inputmode="numeric" name="harga_dus" id="harga_dus" value="" placeholder="Harga / Dus"
                    class="money text-right w-full h-[40px] sm:h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium font-mono-num text-slate-700 dark:text-slate-200 disabled:bg-slate-100 dark:disabled:bg-slate-800 disabled:text-slate-500 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
            </div>
        </div>
    </div>

    <div class="grid grid-cols-12 gap-2 items-start">
        <div class="col-span-5">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="box" class="w-4 h-4"></i>
                </div>
                <input type="text" inputmode="numeric" name="jml_pack" id="jml_pack" value="" placeholder="0"
                    class="money text-right w-full h-[40px] sm:h-[36px] pl-8 pr-11 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-bold font-mono-num text-slate-700 dark:text-slate-200 disabled:bg-slate-100 dark:disabled:bg-slate-800 disabled:text-slate-500 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-[11px] font-bold text-slate-400">
                    Pack
                </div>
            </div>
        </div>
        <div class="col-span-7">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="banknote" class="w-4 h-4"></i>
                </div>
                <input type="text" inputmode="numeric" name="harga_pack" id="harga_pack" value="" placeholder="Harga / Pack"
                    class="money text-right w-full h-[40px] sm:h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium font-mono-num text-slate-700 dark:text-slate-200 disabled:bg-slate-100 dark:disabled:bg-slate-800 disabled:text-slate-500 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
            </div>
        </div>
    </div>

    <div class="grid grid-cols-12 gap-2 items-start">
        <div class="col-span-5">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="box" class="w-4 h-4"></i>
                </div>
                <input type="text" inputmode="numeric" name="jml_pcs" id="jml_pcs" value="" placeholder="0"
                    class="money text-right w-full h-[40px] sm:h-[36px] pl-8 pr-10 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-bold font-mono-num text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-[11px] font-bold text-slate-400">
                    Pcs
                </div>
            </div>
        </div>
        <div class="col-span-7">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="banknote" class="w-4 h-4"></i>
                </div>
                <input type="text" inputmode="numeric" name="harga_pcs" id="harga_pcs" value="" placeholder="Harga / Pcs"
                    class="money text-right w-full h-[40px] sm:h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium font-mono-num text-slate-700 dark:text-slate-200 disabled:bg-slate-100 dark:disabled:bg-slate-800 disabled:text-slate-500 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
            </div>
        </div>
    </div>

    <div class="pt-1">
        <label for="status_promosi_modal"
            class="inline-flex items-center gap-2 px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50/70 dark:bg-slate-800/60 text-xs font-bold text-slate-700 dark:text-slate-200 cursor-pointer select-none">
            <input class="status_promosi rounded border-slate-300 text-[#284c9a] focus:ring-[#284c9a]" name="status_promosi" value="1" type="checkbox" id="status_promosi_modal">
            <span>Promosi (Harga Rp 0)</span>
        </label>
    </div>

    <div class="pt-3 border-t border-slate-100 dark:border-slate-800 flex flex-col-reverse sm:flex-row sm:items-center sm:justify-end gap-2">
        <button type="button" class="btn-close-modal-produk w-full sm:w-auto h-[40px] sm:h-[36px] px-4 rounded-lg border border-slate-200 dark:border-slate-700 text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition cursor-pointer">
            Batal
        </button>
        <button type="submit" id="btnTambahproduk"
            class="w-full sm:w-auto h-[40px] sm:h-[36px] px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold shadow-xs transition inline-flex items-center justify-center gap-1.5 cursor-pointer">
            <i data-lucide="plus" class="w-3.5 h-3.5"></i>
            <span>Tambah Produk</span>
        </button>
    </div>
</form>

<script>
    $(function() {
        if (typeof lucide !== 'undefined') lucide.createIcons();
        $(".money").maskMoney();

        const form = $("#formAddproduk");

        if (form.find('.select2KodehargaAdd').length) {
            form.find('.select2KodehargaAdd').select2({
                placeholder: 'Pilih Produk',
                allowClear: true,
                dropdownCssClass: 'select2-dropdown-v2'
            });
        }

        function getharga() {
            const selectedOption = form.find("#kode_harga").find(':selected');
            const isi_pcs_dus = selectedOption.data('isi_pcs_dus');
            const isi_pcs_pack = selectedOption.data('isi_pcs_pack');
            const kode_kategori_diskon = selectedOption.data('kode_kategori_diskon');
            const kode_produk = selectedOption.data('kode_produk');
            const kode_wilayah = $("#kode_wilayah").val();

            const harga_dus = selectedOption.data('harga_dus');
            const harga_pack = selectedOption.data('harga_pack');
            const harga_pcs = selectedOption.data('harga_pcs');

            form.find("#isi_pcs_dus").val(isi_pcs_dus);
            form.find("#isi_pcs_pack").val(isi_pcs_pack);
            form.find("#kode_kategori_diskon").val(kode_kategori_diskon);
            form.find("#kode_produk").val(kode_produk);

            form.find("#harga_dus").val(harga_dus);
            form.find("#harga_pack").val(harga_pack);
            form.find("#harga_pcs").val(harga_pcs);

            if (isi_pcs_pack == "" || isi_pcs_pack == 0 || isi_pcs_pack === '0') {
                form.find("#jml_pack").prop('disabled', true);
            } else {
                form.find("#jml_pack").prop('disabled', false);
            }

            if (kode_wilayah == 'WSKB000005') {
                form.find("#harga_dus").prop('disabled', false);
                if (isi_pcs_pack == "" || isi_pcs_pack == 0 || isi_pcs_pack === '0') {
                    form.find("#harga_pack").prop('disabled', true);
                } else {
                    form.find("#harga_pack").prop('disabled', false);
                }
                form.find("#harga_pcs").prop('disabled', false);
            } else {
                form.find("#harga_dus").prop('disabled', true);
                form.find("#harga_pack").prop('disabled', true);
                form.find("#harga_pcs").prop('disabled', true);
            }
        }

        form.find("#kode_harga").on('change', function() {
            getharga();
        });

        function initHarga() {
            const nama_pelanggan = $("#nama_pelanggan").val() || '';
            const selectedOption = form.find("#kode_harga").find(':selected');
            const isi_pcs_dus = selectedOption.data('isi_pcs_dus');
            const isi_pcs_pack = selectedOption.data('isi_pcs_pack');

            if (isi_pcs_pack == "" || isi_pcs_pack === '0') {
                form.find("#jml_pack").prop('disabled', true);
            } else {
                form.find("#jml_pack").prop('disabled', false);
            }

            form.find("#isi_pcs_dus").val(isi_pcs_dus);
            form.find("#isi_pcs_pack").val(isi_pcs_pack);

            if (nama_pelanggan.includes('KPBN') || nama_pelanggan.includes('RSB')) {
                form.find("#harga_dus").prop('disabled', false);
                if (isi_pcs_pack == "" || isi_pcs_pack === 0 || isi_pcs_pack === '0') {
                    form.find("#harga_pack").prop('disabled', true);
                } else {
                    form.find("#harga_pack").prop('disabled', false);
                }
                form.find("#harga_pcs").prop('disabled', false);
            } else {
                form.find("#harga_dus").prop('disabled', true);
                form.find("#harga_pack").prop('disabled', true);
                form.find("#harga_pcs").prop('disabled', true);
            }
        }

        initHarga();
    });
</script>
