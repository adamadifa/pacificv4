@extends('layouts.app')
@section('titlepage', 'Ajuan Faktur')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <a href="{{ route('sfa.pelanggan') }}" class="text-slate-400 hover:text-[#284c9a] transition">SFA</a>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <a href="{{ route('sfa.showpelanggan', Crypt::encrypt($pelanggan->kode_pelanggan)) }}"
            class="text-slate-400 hover:text-[#284c9a] transition truncate max-w-[120px] sm:max-w-none">{{ $pelanggan->nama_pelanggan }}</a>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Ajuan Faktur</span>
    </div>
@endsection

@section('content')
<div class="max-w-2xl mx-auto space-y-3">
    <!-- PAGE HEADER -->
    <div class="flex items-center justify-between gap-2">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Ajuan Faktur Kredit</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Pengajuan penambahan jumlah faktur kredit pelanggan.</p>
        </div>
        <a href="{{ route('sfa.showpelanggan', Crypt::encrypt($pelanggan->kode_pelanggan)) }}"
            class="h-[34px] px-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 text-xs font-bold inline-flex items-center gap-1.5 shrink-0 transition">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
            <span>Kembali</span>
        </a>
    </div>

    <!-- INFO PELANGGAN STRIP -->
    <div class="rounded-xl bg-[#284c9a] text-white p-3 sm:p-3.5 shadow-xs space-y-1.5">
        <div class="flex items-center justify-between gap-2 pb-1.5 border-b border-white/15">
            <span class="text-[11px] font-bold font-mono-num text-blue-200">{{ $pelanggan->kode_pelanggan }}</span>
            <span class="px-2 py-0.5 rounded bg-white/15 text-[10px] font-bold">Data Pelanggan</span>
        </div>
        <p class="text-sm font-black leading-snug">{{ strtoupper($pelanggan->nama_pelanggan) }}</p>
        <p class="text-[11px] text-white/75 flex items-start gap-1.5">
            <i data-lucide="map-pin" class="w-3.5 h-3.5 shrink-0 mt-0.5 text-blue-200"></i>
            <span>{{ $pelanggan->alamat_pelanggan ?: '-' }}</span>
        </p>
    </div>

    <!-- FORM AJUAN FAKTUR -->
    <form action="{{ route('sfa.storeajuanfaktur', Crypt::encrypt($pelanggan->kode_pelanggan)) }}"
        autocomplete="off" id="formAjuanfaktur" method="POST" enctype="multipart/form-data"
        class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200/80 dark:border-slate-800 shadow-2xs p-3 sm:p-3.5 space-y-2.5">
        @csrf

        <div class="flex items-center gap-2 pb-2 mb-1 border-b border-slate-100 dark:border-slate-800">
            <i data-lucide="file-plus-2" class="w-3.5 h-3.5 text-[#284c9a] dark:text-blue-400"></i>
            <h4 class="text-xs font-bold text-slate-700 dark:text-slate-200">Detail Pengajuan Faktur</h4>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
            <div>
                <label for="tanggal" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Tanggal Pengajuan <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                    </div>
                    <input type="text" name="tanggal" id="tanggal" value="{{ date('Y-m-d') }}"
                        placeholder="Pilih Tanggal" readonly
                        class="flatpickr-date w-full h-[36px] pl-8 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition cursor-pointer">
                </div>
            </div>

            <div>
                <label for="jumlah_faktur" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Jumlah Faktur <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="files" class="w-3.5 h-3.5"></i>
                    </div>
                    <input type="text" name="jumlah_faktur" id="jumlah_faktur" placeholder="0"
                        class="money w-full h-[36px] pl-8 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs font-mono-num font-bold text-right text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                </div>
            </div>
        </div>

        <div>
            <label for="keterangan" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Keterangan <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="absolute top-2.5 left-2.5 pointer-events-none text-slate-400 z-10">
                    <i data-lucide="file-text" class="w-3.5 h-3.5"></i>
                </div>
                <textarea name="keterangan" id="keterangan" rows="3" placeholder="Tuliskan alasan/keterangan pengajuan faktur..."
                    class="w-full pl-8 pr-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition"></textarea>
            </div>
        </div>

        <label class="inline-flex items-center gap-2 pt-1 cursor-pointer select-none">
            <input class="cod rounded border-slate-300 text-[#284c9a] focus:ring-[#284c9a]" name="cod" value="1" type="checkbox" id="cod">
            <span class="text-xs font-semibold text-slate-700 dark:text-slate-200">Pembayaran Saat Turun Barang Selanjutnya (COD)</span>
        </label>

        <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100 dark:border-slate-800">
            <a href="{{ route('sfa.showpelanggan', Crypt::encrypt($pelanggan->kode_pelanggan)) }}"
                class="h-[36px] px-4 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 text-xs font-bold inline-flex items-center justify-center transition">
                Batal
            </a>
            <button type="submit" id="btnSimpan"
                class="h-[36px] px-5 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold shadow-xs transition inline-flex items-center justify-center gap-1.5">
                <i data-lucide="send" class="w-3.5 h-3.5"></i>
                <span>Kirim Ajuan Faktur</span>
            </button>
        </div>
    </form>
</div>
@endsection

@push('myscript')
<script src="{{ asset('assets/js/pages/ajuanfaktur.js') }}"></script>
<script>
    $(function() {
        if (typeof lucide !== 'undefined') lucide.createIcons();

        const $form = $("#formAjuanfaktur");

        if (typeof flatpickr !== 'undefined') {
            flatpickr(".flatpickr-date", {
                dateFormat: "Y-m-d",
                allowInput: false
            });
        }

        function setFieldError($el, msg) {
            const $wrap = $el.closest('.relative');
            $el.addClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500 focus:ring-rose-500/20');
            $wrap.find('> div:first-child').removeClass('text-slate-400').addClass('text-rose-500');
            $wrap.parent().find('.field-error-msg').remove();
            $wrap.after(`<p class="field-error-msg text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 flex items-center gap-1"><svg class="w-3 h-3 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg><span>${msg}</span></p>`);
        }

        function clearFieldError($el) {
            const $wrap = $el.closest('.relative');
            $el.removeClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500 focus:ring-rose-500/20');
            $wrap.find('> div:first-child').removeClass('text-rose-500').addClass('text-slate-400');
            $wrap.parent().find('.field-error-msg').remove();
        }

        $form.find('input, textarea').on('input change', function() {
            clearFieldError($(this));
        });

        $form.on('submit', function(e) {
            let firstError = null;
            const requiredFields = [
                { id: '#tanggal', msg: 'Tanggal pengajuan wajib diisi.' },
                { id: '#jumlah_faktur', msg: 'Jumlah faktur wajib diisi.' },
                { id: '#keterangan', msg: 'Keterangan wajib diisi.' }
            ];

            requiredFields.forEach(function(field) {
                const $el = $form.find(field.id);
                const val = ($el.val() || '').toString().trim();
                const isZero = field.id === '#jumlah_faktur' && (val === '' || val === '0');
                if (!val || isZero) {
                    setFieldError($el, field.msg);
                    if (!firstError) firstError = $el;
                }
            });

            if (firstError) {
                e.preventDefault();
                firstError.focus();
                return false;
            }

            const $btn = $("#btnSimpan");
            $btn.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            $btn.html(`<svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg><span>Menyimpan...</span>`);
        });
    });
</script>
@endpush
