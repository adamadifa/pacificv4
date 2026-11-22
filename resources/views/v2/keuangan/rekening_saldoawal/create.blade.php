@extends('layouts.app')
@section('titlepage', 'Buat Saldo Awal Rekening')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Home</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-slate-400">Keuangan</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <a href="{{ route('sarekening.index') }}" class="text-slate-400 hover:text-brand-600 transition">Saldo Awal Rekening</a>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Buat</span>
    </div>
@endsection

@section('content')
<div class="space-y-3.5">

    <!-- PAGE TITLE BAR -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Buat Saldo Awal Rekening</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Input saldo awal harian per rekening bank secara manual atau impor dari Excel.</p>
        </div>

        <a href="{{ route('sarekening.index') }}"
            class="inline-flex items-center justify-center gap-1.5 h-[34px] px-3.5 rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 text-xs font-bold transition shadow-2xs shrink-0">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Kembali</span>
        </a>
    </div>

    <!-- MAIN FORM -->
    <form action="{{ route('sarekening.store') }}" method="POST" id="formSaldoAwal" class="space-y-3.5">
        @csrf

        <!-- TANGGAL INPUT CARD (NO-LABEL, ICON PREFIX, H-[34PX], ROUNDED-LG, FLATPICKR) -->
        <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-3 shadow-2xs transition-colors duration-200">
            <div class="flex flex-col sm:flex-row sm:items-center gap-2.5">
                <div class="w-full sm:w-40 shrink-0">
                    <span class="text-xs font-bold text-slate-700 dark:text-slate-300 block">Tanggal Saldo Awal</span>
                    <span class="text-[11px] text-slate-400 block">Pilih tanggal efektif</span>
                </div>
                <div class="relative flex-1 w-full">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                    </div>
                    <input type="text" name="tanggal" id="tanggal" value="{{ date('Y-m-d') }}" required
                        class="flatpickr-date w-full h-[34px] pl-8 pr-2.5 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer"
                        placeholder="Pilih Tanggal Saldo Awal" readonly>
                </div>
            </div>
        </div>

        <!-- BANK TABLE CARD WITH SEAMLESS UNIFIED HEADER -->
        <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-2xs overflow-hidden transition-colors duration-200">
            
            <!-- Table of Bank Accounts with Seamless Integrated Header -->
            <div class="overflow-x-auto scrollbar-thin">
                <table class="w-full text-xs text-left border-collapse font-sans">
                    <thead>
                        <!-- Row 1: Integrated Title & Actions -->
                        <tr class="bg-[#284c9a] text-white">
                            <th colspan="3" class="px-4 py-3.5 bg-[#284c9a] text-white border-0">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                                    <div class="flex items-center gap-2">
                                        <i data-lucide="building-2" class="w-4 h-4 text-blue-200"></i>
                                        <span class="text-xs font-bold uppercase tracking-wider text-white">Input Rincian Saldo Bank</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <!-- Template Download -->
                                        <a href="{{ route('sarekening.downloadtemplate') }}"
                                            class="inline-flex items-center gap-1.5 h-[28px] px-2.5 rounded-md bg-emerald-600 hover:bg-emerald-700 text-white text-[11px] font-bold transition shadow-xs">
                                            <i data-lucide="download" class="w-3 h-3"></i>
                                            <span>Template Excel</span>
                                        </a>

                                        <!-- Import Modal Trigger -->
                                        <button type="button" id="btnOpenImportModal"
                                            class="inline-flex items-center gap-1.5 h-[28px] px-2.5 rounded-md bg-sky-600 hover:bg-sky-700 text-white text-[11px] font-bold transition shadow-xs cursor-pointer">
                                            <i data-lucide="file-up" class="w-3 h-3"></i>
                                            <span>Import Excel</span>
                                        </button>
                                    </div>
                                </div>
                            </th>
                        </tr>
                        <!-- Row 2: Column Titles Seamlessly Flowing Below -->
                        <tr class="bg-[#284c9a] text-white text-[11px] border-none">
                            <th class="py-3 px-3 text-center w-12 font-bold uppercase tracking-wider text-white/90 border-none">NO</th>
                            <th class="py-3 px-4 font-bold uppercase tracking-wider text-white/90 border-none">NAMA BANK & NOMOR REKENING</th>
                            <th class="py-3 px-4 font-bold uppercase tracking-wider text-right w-60 text-white/90 border-none">SALDO AWAL (RP)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 bg-white dark:bg-slate-900">
                        @foreach ($bank as $d)
                            <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/50 transition duration-100">
                                <!-- No -->
                                <td class="py-2 px-3 text-center font-mono-num text-slate-400 border-r border-slate-100 dark:border-slate-800/60 text-[11px]">
                                    {{ $loop->iteration }}
                                </td>

                                <!-- Nama Bank & Rekening -->
                                <td class="py-2 px-3.5 border-r border-slate-100 dark:border-slate-800/60">
                                    <input type="hidden" name="kode_bank[]" value="{{ $d->kode_bank }}">
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-md bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 flex items-center justify-center font-bold text-[11px] shrink-0 border border-slate-200/60 dark:border-slate-700">
                                            {{ substr($d->nama_bank, 0, 3) }}
                                        </div>
                                        <div>
                                            <span class="font-bold text-slate-900 dark:text-white block leading-tight">{{ $d->nama_bank }}</span>
                                            <span class="text-[10px] font-mono-num text-slate-400 block leading-tight">{{ $d->no_rekening }}</span>
                                        </div>
                                    </div>
                                </td>

                                <!-- Saldo Awal Input (Money Mask, h-[32px], rounded-lg) -->
                                <td class="py-1.5 px-3.5 text-right">
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 text-xs font-bold z-10 font-mono-num">
                                            Rp
                                        </div>
                                        <input type="text" name="jumlah[]" placeholder="0"
                                            class="money w-full h-[32px] pl-9 pr-2.5 text-right text-xs font-mono-num font-bold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-900 dark:text-white focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Form Action Footer -->
            <div class="flex items-center justify-end gap-2 px-3.5 py-2.5 border-t border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/40">
                <a href="{{ route('sarekening.index') }}"
                    class="inline-flex items-center justify-center h-[34px] px-4 rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 text-xs font-bold transition shadow-2xs cursor-pointer">
                    Batal
                </a>
                <button type="submit"
                    class="inline-flex items-center justify-center gap-1.5 h-[34px] px-5 rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs font-bold transition shadow-xs cursor-pointer">
                    <i data-lucide="save" class="w-3.5 h-3.5"></i>
                    <span>Simpan Saldo Awal</span>
                </button>
            </div>

        </div>
    </form>

</div>

<!-- TAILWIND MODAL IMPORT EXCEL -->
<div id="modalImport" class="fixed inset-0 z-50 hidden overflow-y-auto" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity" id="modalImportBackdrop"></div>

    <div class="flex min-h-full items-center justify-center p-3 sm:p-4 text-center">
        <div class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-slate-900 text-left shadow-2xl transition-all w-full max-w-md border border-slate-200 dark:border-slate-800">
            
            <!-- Modal Header -->
            <div class="flex items-center justify-between px-4 py-3 border-b border-slate-100 dark:border-slate-800">
                <div class="flex items-center gap-2">
                    <i data-lucide="file-up" class="w-4 h-4 text-brand-600 dark:text-brand-400"></i>
                    <h3 class="text-xs font-bold text-slate-800 dark:text-slate-100 uppercase tracking-wider">Import Saldo Awal Rekening</h3>
                </div>
                <button type="button" id="btnModalImportClose" class="w-7 h-7 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 flex items-center justify-center transition cursor-pointer">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>

            <!-- Modal Form -->
            <form action="{{ route('sarekening.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="p-4 space-y-3.5">
                    
                    <!-- Info Alert -->
                    <div class="p-2.5 rounded-lg bg-sky-50 dark:bg-sky-950/40 border border-sky-200 dark:border-sky-800 text-sky-800 dark:text-sky-300 text-xs flex items-start gap-2">
                        <i data-lucide="info" class="w-3.5 h-3.5 text-sky-600 dark:text-sky-400 shrink-0 mt-0.5"></i>
                        <div class="leading-relaxed text-[11px]">
                            Pastikan format file sesuai dengan <strong>Template Excel</strong> yang telah disediakan. Kolom bank dan jumlah saldo wajib terisi dengan benar.
                        </div>
                    </div>

                    <!-- Tanggal Saldo Awal -->
                    <div>
                        <span class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1">Tanggal Saldo Awal</span>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                            </div>
                            <input type="text" name="tanggal" value="{{ date('Y-m-d') }}" required
                                class="flatpickr-date w-full h-[34px] pl-8 pr-2.5 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer" readonly>
                        </div>
                    </div>

                    <!-- File Input -->
                    <div>
                        <span class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1">File Excel (.xlsx, .xls, .csv)</span>
                        <input type="file" name="file" accept=".xlsx, .xls, .csv" required
                            class="w-full text-xs text-slate-500 file:mr-2.5 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100 dark:file:bg-slate-800 dark:file:text-slate-200 cursor-pointer border border-slate-200 dark:border-slate-800 rounded-lg p-1 bg-white dark:bg-slate-900">
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="px-4 py-2.5 border-t border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 flex justify-end gap-2">
                    <button type="button" id="btnModalImportCancel" class="h-[32px] px-3.5 rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 text-xs font-bold transition cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="inline-flex items-center gap-1.5 h-[32px] px-3.5 rounded-lg bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold transition shadow-xs cursor-pointer">
                        <i data-lucide="upload" class="w-3.5 h-3.5"></i>
                        <span>Import</span>
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection

@push('myscript')
<script src="{{ asset('assets/vendor/js/jquery.maskMoney.js') }}"></script>
<script>
$(function() {
    // Re-initialize Lucide Icons
    if (window.lucide) {
        lucide.createIcons();
    }

    // Flatpickr
    if ($('.flatpickr-date').length && typeof flatpickr !== 'undefined') {
        $('.flatpickr-date').flatpickr({
            dateFormat: 'Y-m-d',
            allowInput: false
        });
    }

    // MaskMoney
    if ($.fn.maskMoney) {
        $(".money").maskMoney({
            prefix: '',
            thousands: '.',
            decimal: ',',
            precision: 0
        });
    }

    // Modal Import controls
    function openImportModal() {
        $("#modalImport").removeClass("hidden");
        $("body").addClass("overflow-hidden");
        if (window.lucide) { lucide.createIcons(); }
    }

    function closeImportModal() {
        $("#modalImport").addClass("hidden");
        $("body").removeClass("overflow-hidden");
    }

    $("#btnOpenImportModal").on("click", function() {
        openImportModal();
    });

    $("#btnModalImportClose, #btnModalImportCancel, #modalImportBackdrop").on("click", function() {
        closeImportModal();
    });

    // Form Validation
    $("#formSaldoAwal").on("submit", function() {
        const tanggal = $("#tanggal").val();
        if (!tanggal) {
            Swal.fire({
                title: "Perhatian",
                text: "Tanggal saldo awal wajib dipilih!",
                icon: "warning",
                confirmButtonColor: "#284c9a"
            });
            return false;
        }
    });
});
</script>
@endpush
