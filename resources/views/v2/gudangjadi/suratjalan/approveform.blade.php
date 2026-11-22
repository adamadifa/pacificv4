@php
    $numFormat = function($val) {
        return number_format((float)($val ?? 0), 2, ',', '.');
    };
@endphp

<form action="{{ route('suratjalan.approve', Crypt::encrypt($surat_jalan->no_mutasi)) }}" method="POST" id="formApprovesuratjalan" autocomplete="off" class="space-y-4">
    @csrf
    <input type="hidden" id="cektutuplaporan">

    <!-- STRIP INFORMASI DOKUMEN -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-800/40 p-3 sm:p-4">
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 text-xs">
            <div>
                <span class="block text-[11px] font-semibold text-slate-400">No. Surat Jalan</span>
                <span class="font-bold font-mono-num text-[#284c9a] dark:text-blue-400 text-sm">
                    {{ $surat_jalan->no_mutasi }}
                </span>
            </div>
            <div>
                <span class="block text-[11px] font-semibold text-slate-400">No. Dokumen</span>
                <span class="font-bold text-slate-800 dark:text-slate-100">
                    {{ $surat_jalan->no_dok }}
                </span>
            </div>
            <div>
                <span class="block text-[11px] font-semibold text-slate-400">Tanggal Kirim</span>
                <span class="font-bold font-mono-num text-slate-800 dark:text-slate-100">
                    {{ DateToIndo($surat_jalan->tanggal) }}
                </span>
            </div>
            <div>
                <span class="block text-[11px] font-semibold text-slate-400">Cabang Tujuan</span>
                <span class="font-bold text-slate-800 dark:text-slate-100">
                    {{ textUpperCase($surat_jalan->nama_cabang) }}
                </span>
            </div>
            <div class="col-span-2">
                <span class="block text-[11px] font-semibold text-slate-400">Keterangan</span>
                <span class="font-medium text-slate-600 dark:text-slate-400 text-xs">
                    {{ !empty($surat_jalan->keterangan) ? $surat_jalan->keterangan : '-' }}
                </span>
            </div>
        </div>
    </div>

    <!-- TABEL RINCIAN BARANG -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 overflow-hidden shadow-2xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="bg-[#284c9a] text-white">
                        <th colspan="3" class="py-2 px-3.5">
                            <div class="flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider">
                                <i data-lucide="package" class="w-4 h-4 text-white/80"></i>
                                <span>Rincian Produk Dalam Surat Jalan</span>
                            </div>
                        </th>
                    </tr>
                    <tr class="bg-[#1e3a78] text-white text-[11px] font-bold uppercase tracking-wider">
                        <th class="py-1.5 px-3 w-32">Kode Produk</th>
                        <th class="py-1.5 px-3">Nama Produk</th>
                        <th class="py-1.5 px-3 text-right w-36">Jumlah</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-xs text-slate-700 dark:text-slate-300">
                    @foreach ($detail as $d)
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition-colors">
                            <td class="py-1.5 px-3 font-mono-num font-bold text-[#284c9a] dark:text-blue-400">
                                {{ $d->kode_produk }}
                            </td>
                            <td class="py-1.5 px-3 font-medium text-slate-800 dark:text-slate-100">
                                {{ $d->nama_produk }}
                            </td>
                            <td class="py-1.5 px-3 text-right font-mono-num font-bold text-slate-900 dark:text-white">
                                {{ $numFormat($d->jumlah) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- FORM PENERIMAAN / STATUS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 items-start pt-2 border-t border-slate-100 dark:border-slate-800">
        <!-- Pilihan Status Penerimaan -->
        <div class="field-group">
            <label for="status" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Pilih Status Penerimaan <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                    <i data-lucide="check-circle" class="w-3.5 h-3.5"></i>
                </div>
                <select name="status" id="status"
                    class="w-full h-[36px] pl-8 pr-8 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition appearance-none cursor-pointer">
                    <option value="">Pilih Status</option>
                    <option value="1">Diterima Cabang</option>
                    <option value="2">Transit Out</option>
                </select>
                <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                    <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                </div>
            </div>
        </div>

        <!-- Tanggal Diterima / Transit Out -->
        <div class="field-group">
            <label for="tanggal" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Tanggal Diterima / Transit Out <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                    <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                </div>
                <input type="text" name="tanggal" id="tanggal" placeholder="Pilih Tanggal" readonly
                    class="flatpickr-date w-full h-[36px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer">
            </div>
        </div>
    </div>

    <!-- TOMBOL SUBMIT -->
    <div class="pt-2">
        <button type="submit" id="btnSimpan"
            class="w-full h-[36px] inline-flex items-center justify-center gap-1.5 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] active:bg-[#152a56] text-white text-xs font-bold transition shadow-xs cursor-pointer">
            <i data-lucide="check" class="w-3.5 h-3.5"></i>
            <span>Konfirmasi Penerimaan</span>
        </button>
    </div>
</form>

<script>
$(function() {
    const $form = $("#formApprovesuratjalan");

    // Initialize Flatpickr
    $form.find(".flatpickr-date").flatpickr({
        dateFormat: "Y-m-d",
        enable: [{
            from: "{{ $start_periode }}",
            to: "{{ $end_periode }}"
        }]
    });

    // Cek Tutup Laporan
    function cektutuplaporan(tanggal, jenis_laporan) {
        $.ajax({
            type: "POST",
            url: "/tutuplaporan/cektutuplaporan",
            data: {
                _token: "{{ csrf_token() }}",
                tanggal: tanggal,
                jenis_laporan: jenis_laporan
            },
            cache: false,
            success: function(respond) {
                $("#cektutuplaporan").val(respond);
            }
        });
    }

    $form.find("#tanggal").on('change', function() {
        cektutuplaporan($(this).val(), "gudangcabang");
    });

    // Validasi Interaktif Helpers V2
    function setFieldError($field, message) {
        const $group = $field.closest('.field-group');
        $field.addClass('!border-rose-500 !ring-1 !ring-rose-500');
        $group.find('.input-prefix-icon').addClass('!text-rose-500');
        $group.find('.field-error-msg').remove();
        $group.append(`
            <p class="field-error-msg text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 flex items-center gap-1">
                <svg class="w-3.5 h-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
                <span>${message}</span>
            </p>
        `);
    }

    function clearFieldError($field) {
        const $group = $field.closest('.field-group');
        $field.removeClass('!border-rose-500 !ring-1 !ring-rose-500');
        $group.find('.input-prefix-icon').removeClass('!text-rose-500');
        $group.find('.field-error-msg').remove();
    }

    $form.find("select, input").on('input change', function() {
        clearFieldError($(this));
    });

    $form.on('submit', function(e) {
        let hasError = false;
        let $firstError = null;

        const status = $form.find("#status").val();
        const tanggal = $form.find("#tanggal").val();
        const cektutup = $form.find("#cektutuplaporan").val();

        if (!status) {
            setFieldError($form.find("#status"), "Silakan pilih status penerimaan");
            hasError = true;
            $firstError = $form.find("#status");
        }

        if (!tanggal) {
            setFieldError($form.find("#tanggal"), "Tanggal penerimaan wajib diisi");
            hasError = true;
            if (!$firstError) $firstError = $form.find("#tanggal");
        }

        if (cektutup === '1') {
            setFieldError($form.find("#tanggal"), "Laporan untuk periode tanggal ini sudah ditutup!");
            hasError = true;
            if (!$firstError) $firstError = $form.find("#tanggal");
        }

        if (hasError) {
            e.preventDefault();
            if ($firstError) $firstError.focus();
            return false;
        }

        const $btn = $form.find("#btnSimpan");
        $btn.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
        $btn.html(`
            <svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span>Memproses...</span>
        `);
    });

    if (window.lucide) lucide.createIcons();
});
</script>
