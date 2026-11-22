@php
    $roles_show_cabang = config('global.roles_show_cabang');
    $canFilterCabang = auth()->user()->hasAnyRole($roles_show_cabang);
@endphp

<form action="{{ route('reject.update', Crypt::encrypt($reject->no_mutasi)) }}" method="POST" id="formRejectEdit" class="space-y-4" autocomplete="off">
    @csrf
    @method('PUT')

    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 p-4">
        <div class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-start">
            
            <!-- Tanggal -->
            <div class="sm:col-span-3 field-group">
                <label for="tanggal" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Tanggal <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                        <i data-lucide="calendar" class="w-4 h-4"></i>
                    </div>
                    <input type="text" name="tanggal" id="tanggal" value="{{ $reject->tanggal }}" placeholder="Pilih Tanggal" readonly
                        class="flatpickr-date w-full h-[36px] pl-9 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer">
                </div>
            </div>

            @if ($canFilterCabang)
                <!-- Cabang (Select2 V2) -->
                <div class="sm:col-span-3 field-group">
                    <label for="kode_cabang" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                        Cabang <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20 transition-colors">
                            <i data-lucide="building-2" class="w-4 h-4"></i>
                        </div>
                        <select name="kode_cabang" id="kode_cabang"
                            class="select2Kodecabang select2-has-prefix w-full h-[36px] text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100">
                            <option value="">Pilih Cabang</option>
                            @foreach ($cabang as $c)
                                <option value="{{ $c->kode_cabang }}" {{ $reject->kode_cabang == $c->kode_cabang ? 'selected' : '' }}>
                                    {{ strtoupper($c->nama_cabang) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @else
                <input type="hidden" name="kode_cabang" id="kode_cabang" value="{{ $reject->kode_cabang }}">
            @endif

            <!-- Jenis Mutasi -->
            <div class="{{ $canFilterCabang ? 'sm:col-span-3' : 'sm:col-span-4' }} field-group">
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
                            <option value="{{ $jm->kode_jenis_mutasi }}" {{ $reject->jenis_mutasi == $jm->kode_jenis_mutasi ? 'selected' : '' }}>
                                {{ strtoupper($jm->jenis_mutasi) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Keterangan -->
            <div class="{{ $canFilterCabang ? 'sm:col-span-3' : 'sm:col-span-5' }} field-group">
                <label for="keterangan" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Keterangan <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                        <i data-lucide="file-text" class="w-4 h-4"></i>
                    </div>
                    <input type="text" name="keterangan" id="keterangan" value="{{ $reject->keterangan }}" placeholder="Keterangan reject"
                        class="w-full h-[36px] pl-9 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                </div>
            </div>

        </div>
    </div>

    <!-- DETAIL ITEMS TABLE -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 overflow-hidden shadow-2xs">
        <div class="overflow-x-auto max-h-[380px]">
            <table class="w-full text-xs text-left border-collapse whitespace-nowrap">
                <thead class="sticky top-0 z-10">
                    <tr class="bg-[#284c9a] text-white">
                        <th colspan="6" class="px-4 py-2 bg-[#284c9a] text-white border-0">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="boxes" class="w-3.5 h-3.5 text-blue-200"></i>
                                    <span class="text-xs font-bold uppercase tracking-wider text-white">Perbarui Kuantitas Barang Reject</span>
                                </div>
                                <span class="text-[11px] text-white/70 font-mono-num">{{ count($produk) }} produk</span>
                            </div>
                        </th>
                    </tr>
                    <tr class="bg-[#1e3a78] text-white text-[11px] border-none">
                        <th class="py-2 px-3 text-center w-12 font-bold uppercase tracking-wider text-white/90 border-none">NO</th>
                        <th class="py-2 px-3 text-center w-28 font-bold uppercase tracking-wider text-white/90 border-none">KODE</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-none">NAMA PRODUK</th>
                        <th class="py-2 px-3 text-right font-bold uppercase tracking-wider text-white/90 border-none w-28">DUS</th>
                        <th class="py-2 px-3 text-right font-bold uppercase tracking-wider text-white/90 border-none w-28">PACK</th>
                        <th class="py-2 px-3 text-right font-bold uppercase tracking-wider text-white/90 border-none w-28">PCS</th>
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
                                    class="money jml_dus w-22 text-right px-2 py-1 text-xs font-mono-num font-bold rounded border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:border-brand-500">
                            </td>
                            <td class="py-1.5 px-3 text-right">
                                <input type="text" name="jml_pack[]" value="{{ formatAngka($jumlah_pack) }}" {{ empty($d->isi_pcs_pack) ? 'readonly' : '' }}
                                    class="money jml_pack w-22 text-right px-2 py-1 text-xs font-mono-num font-bold rounded border border-slate-200 dark:border-slate-700 {{ empty($d->isi_pcs_pack) ? 'bg-slate-100 dark:bg-slate-800/60 text-slate-400 cursor-not-allowed' : 'bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:border-brand-500' }}">
                            </td>
                            <td class="py-1.5 px-3 text-right">
                                <input type="text" name="jml_pcs[]" value="{{ formatAngka($jumlah_pcs) }}"
                                    class="money jml_pcs w-22 text-right px-2 py-1 text-xs font-mono-num font-bold rounded border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:border-brand-500">
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- ACTION BUTTONS -->
    <div class="flex items-center justify-end gap-2.5 pt-2">
        <button type="button" class="btn-close-modal h-[36px] px-4 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-slate-200 text-xs font-bold transition cursor-pointer">
            Batal
        </button>
        <button type="submit" id="btnSubmitRejectEdit"
            class="inline-flex items-center justify-center gap-2 h-[36px] px-5 rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs font-bold transition shadow-xs cursor-pointer">
            <i data-lucide="save" class="w-4 h-4"></i>
            <span>Simpan Perubahan</span>
        </button>
    </div>

</form>

<script>
    $(function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        const $form = $('#formRejectEdit');
        const $btnSubmit = $('#btnSubmitRejectEdit');

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
        const $select2Cabang = $form.find('.select2Kodecabang');
        if ($select2Cabang.length) {
            $select2Cabang.select2({ placeholder: 'Pilih Cabang', allowClear: true, width: '100%' });
        }

        const $select2Jenis = $form.find('.select2Jenismutasi');
        if ($select2Jenis.length) {
            $select2Jenis.select2({ placeholder: 'Pilih Jenis Mutasi', allowClear: true, width: '100%' });
        }

        // Form Submit
        $form.on('submit', function(e) {
            $btnSubmit.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            $btnSubmit.html(`
                <svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Menyimpan Perubahan...</span>
            `);
        });
    });
</script>
