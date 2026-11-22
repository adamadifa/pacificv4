<form action="#" method="POST" id="formupdateMutasiDPB" class="space-y-4" autocomplete="off">
    @csrf

    <input type="hidden" name="no_mutasi" id="no_mutasi" value="{{ Crypt::encrypt($mutasi->no_mutasi) }}">

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 items-start p-3.5 rounded-lg border border-slate-200/80 dark:border-slate-800">
        
        <!-- Tanggal Mutasi -->
        <div class="field-group">
            <label for="tanggal" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Tanggal Mutasi <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                </div>
                <input type="text" name="tanggal" id="tanggal" value="{{ $mutasi->tanggal }}" placeholder="Pilih Tanggal" readonly
                    class="flatpickr-date w-full h-[36px] pl-9 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer">
            </div>
        </div>

        <!-- Jenis Mutasi -->
        <div class="field-group">
            <label for="jenis_mutasi" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Jenis Mutasi <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20 transition-colors">
                    <i data-lucide="tag" class="w-4 h-4"></i>
                </div>
                <select name="jenis_mutasi" id="jenis_mutasi"
                    class="select2Jenismutasi select2-has-prefix w-full h-[36px] text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100">
                    <option value="">Pilih Jenis Mutasi</option>
                    @foreach ($jenis_mutasi as $jm)
                        <option value="{{ $jm->kode_jenis_mutasi }}" {{ $mutasi->jenis_mutasi == $jm->kode_jenis_mutasi ? 'selected' : '' }}>
                            {{ strtoupper($jm->jenis_mutasi) }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

    </div>

    <!-- TABEL PRODUK MUTASI DPB -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 overflow-hidden shadow-2xs">
        <div class="overflow-x-auto max-h-[340px]">
            <table class="w-full text-xs text-left border-collapse whitespace-nowrap">
                <thead class="sticky top-0 z-10">
                    <tr class="bg-[#284c9a] text-white">
                        <th colspan="6" class="px-4 py-2 bg-[#284c9a] text-white border-0">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="boxes" class="w-3.5 h-3.5 text-blue-200"></i>
                                    <span class="text-xs font-bold uppercase tracking-wider text-white">Perbarui Kuantitas Barang Mutasi</span>
                                </div>
                                <span class="text-[11px] text-white/70 font-mono-num">{{ count($produk) }} produk</span>
                            </div>
                        </th>
                    </tr>
                    <tr class="bg-[#1e3a78] text-white text-[11px] border-none">
                        <th class="py-2 px-3 text-center w-12 font-bold uppercase tracking-wider text-white/90 border-none">NO</th>
                        <th class="py-2 px-3 text-center w-28 font-bold uppercase tracking-wider text-white/90 border-none">KODE</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-none">NAMA PRODUK</th>
                        <th class="py-2 px-3 text-right font-bold uppercase tracking-wider text-white/90 border-none w-24">DUS</th>
                        <th class="py-2 px-3 text-right font-bold uppercase tracking-wider text-white/90 border-none w-24">PACK</th>
                        <th class="py-2 px-3 text-right font-bold uppercase tracking-wider text-white/90 border-none w-24">PCS</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 bg-white dark:bg-slate-900">
                    @foreach ($produk as $d)
                        @php
                            $jumlah = explode('|', convertToduspackpcsv2($d->isi_pcs_dus, $d->isi_pcs_pack, $d->jumlah));
                            $jumlah_dus = $jumlah[0];
                            $jumlah_pack = $jumlah[1];
                            $jumlah_pcs = $jumlah[2];
                        @endphp
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition">
                            <td class="py-1.5 px-3 text-center text-slate-400 font-mono-num">{{ $loop->iteration }}</td>
                            <td class="py-1.5 px-3">
                                <input type="hidden" class="kode_produk" name="kode_produk[]" value="{{ $d->kode_produk }}">
                                <input type="hidden" class="isi_pcs_dus" name="isi_pcs_dus[]" value="{{ $d->isi_pcs_dus }}">
                                <input type="hidden" class="isi_pcs_pack" name="isi_pcs_pack[]" value="{{ $d->isi_pcs_pack }}">
                                <span class="font-mono-num font-bold text-[#284c9a] dark:text-blue-400">{{ $d->kode_produk }}</span>
                            </td>
                            <td class="py-1.5 px-3 font-medium text-slate-800 dark:text-slate-200">
                                {{ $d->nama_produk }}
                            </td>
                            <td class="py-1.5 px-3 text-right">
                                <input type="text" name="jml_dus[]" value="{{ formatAngka($jumlah_dus) }}"
                                    class="money jml_dus w-20 text-right px-1.5 py-0.5 text-xs font-mono-num font-bold rounded border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:border-brand-500">
                            </td>
                            <td class="py-1.5 px-3 text-right">
                                <input type="text" name="jml_pack[]" value="{{ formatAngka($jumlah_pack) }}" {{ empty($d->isi_pcs_pack) ? 'readonly' : '' }}
                                    class="money jml_pack w-20 text-right px-1.5 py-0.5 text-xs font-mono-num font-bold rounded border border-slate-200 dark:border-slate-700 {{ empty($d->isi_pcs_pack) ? 'bg-slate-100 dark:bg-slate-800/60 text-slate-400 cursor-not-allowed' : 'bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:border-brand-500' }}">
                            </td>
                            <td class="py-1.5 px-3 text-right">
                                <input type="text" name="jml_pcs[]" value="{{ formatAngka($jumlah_pcs) }}"
                                    class="money jml_pcs w-20 text-right px-1.5 py-0.5 text-xs font-mono-num font-bold rounded border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:border-brand-500">
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- ACTION BUTTONS -->
    <div class="flex items-center justify-end gap-2.5 pt-2">
        <button type="button" class="btn-close-modal-mutasi h-[36px] px-4 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-slate-200 text-xs font-bold transition cursor-pointer">
            Batal
        </button>
        <button type="submit" id="submitMutasiDpb"
            class="inline-flex items-center justify-center gap-2 h-[36px] px-5 rounded-lg bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white text-xs font-bold transition shadow-xs cursor-pointer">
            <i data-lucide="save" class="w-4 h-4"></i>
            <span>Simpan Perubahan Mutasi</span>
        </button>
    </div>

</form>

<script>
    $(function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        const $form = $('#formupdateMutasiDPB');

        // Masking
        if (typeof $.fn.maskMoney !== 'undefined') {
            $form.find(".money").maskMoney({
                thousands: '.',
                decimal: ',',
                precision: 0,
                allowZero: true
            });
        }

        // Flatpickr
        $form.find(".flatpickr-date").flatpickr({
            dateFormat: "Y-m-d",
            enable: [{
                from: "{{ $start_periode }}",
                to: "{{ $end_periode }}"
            }]
        });

        // Select2
        const $select2Jenis = $form.find('.select2Jenismutasi');
        if ($select2Jenis.length) {
            $select2Jenis.select2({
                placeholder: 'Pilih Jenis Mutasi',
                allowClear: true,
                width: '100%'
            });
        }

        $('.btn-close-modal-mutasi').on('click', function(e) {
            e.preventDefault();
            if (typeof window.closeModalMutasi === 'function') {
                window.closeModalMutasi();
            }
        });
    });
</script>
