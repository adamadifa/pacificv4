@extends('layouts.app')
@section('titlepage', 'Backup Database')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Home</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-slate-400">Utilities</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Backup Database</span>
    </div>
@endsection

@section('content')
<div class="space-y-4 max-w-4xl">

    <!-- PAGE TITLE BAR -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Database Backup (Streaming Download)</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Unduh cadangan database langsung dari server ke perangkat Anda tanpa menyimpan file sementara di server.</p>
        </div>
        <button type="button" data-bs-toggle="modal" data-bs-target="#helpModal"
            class="inline-flex items-center justify-center gap-1.5 h-[34px] px-3.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700/60 text-slate-700 dark:text-slate-200 text-xs font-bold transition shadow-2xs cursor-pointer shrink-0">
            <i data-lucide="help-circle" class="w-3.5 h-3.5 text-[#284c9a] dark:text-blue-400"></i>
            <span>Panduan Backup</span>
        </button>
    </div>

    <!-- MAIN CARD -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-4 sm:p-6 shadow-2xs space-y-5">
        
        <!-- Action Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
            
            <!-- Download Langsung -->
            <a href="{{ route('backup.database.stream.download') }}"
                onclick="return confirm('Streaming download langsung dari database. File akan langsung download ke komputer Anda tanpa disimpan di server. Lanjutkan?')"
                class="group rounded-xl border border-blue-200/80 dark:border-blue-900/60 bg-[#284c9a] hover:bg-[#1e3a78] text-white p-4 flex items-center gap-3.5 transition shadow-xs">
                <div class="w-11 h-11 rounded-lg bg-white/15 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                    <i data-lucide="download" class="w-5 h-5"></i>
                </div>
                <div class="min-w-0">
                    <span class="block text-sm font-bold leading-tight">Download Langsung</span>
                    <span class="block text-[11px] text-white/75 mt-0.5">Streaming download cepat tanpa progress bar</span>
                </div>
            </a>

            <!-- Download dengan Progress -->
            <a href="{{ route('backup.database.stream.download.progress') }}"
                onclick="return confirm('Streaming download dengan progress tracking. File akan langsung download ke komputer Anda tanpa disimpan di server. Lanjutkan?')"
                class="group rounded-xl border border-emerald-200/80 dark:border-emerald-900/60 bg-emerald-600 hover:bg-emerald-700 text-white p-4 flex items-center gap-3.5 transition shadow-xs">
                <div class="w-11 h-11 rounded-lg bg-white/15 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                    <i data-lucide="activity" class="w-5 h-5"></i>
                </div>
                <div class="min-w-0">
                    <span class="block text-sm font-bold leading-tight">Download dengan Progress</span>
                    <span class="block text-[11px] text-white/80 mt-0.5">Streaming download dengan tracking progress</span>
                </div>
            </a>

        </div>

        <!-- Features Info Strip -->
        <div class="rounded-xl bg-slate-50/80 dark:bg-slate-800/50 border border-slate-200/80 dark:border-slate-800 p-4">
            <div class="flex items-center gap-1.5 mb-3">
                <i data-lucide="shield-check" class="w-4 h-4 text-[#284c9a] dark:text-blue-400"></i>
                <h4 class="text-xs font-bold text-slate-800 dark:text-slate-100">Keunggulan Streaming Backup</h4>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 text-xs text-slate-600 dark:text-slate-300">
                <div class="flex items-center gap-2">
                    <i data-lucide="check-circle-2" class="w-3.5 h-3.5 text-emerald-600 shrink-0"></i>
                    <span>Tidak ada file tersimpan menumpuk di server</span>
                </div>
                <div class="flex items-center gap-2">
                    <i data-lucide="check-circle-2" class="w-3.5 h-3.5 text-emerald-600 shrink-0"></i>
                    <span>File SQL langsung terunduh ke perangkat Anda</span>
                </div>
                <div class="flex items-center gap-2">
                    <i data-lucide="check-circle-2" class="w-3.5 h-3.5 text-emerald-600 shrink-0"></i>
                    <span>Mendukung ukuran database berskala besar</span>
                </div>
                <div class="flex items-center gap-2">
                    <i data-lucide="check-circle-2" class="w-3.5 h-3.5 text-emerald-600 shrink-0"></i>
                    <span>Bebas kendala PHP execution timeout</span>
                </div>
            </div>
        </div>

    </div>

</div>

<!-- HELP MODAL (STATIC BACKDROP, CLEAN HEADER) -->
<div class="modal fade" id="helpModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-2xl overflow-hidden transition-all duration-150">
            <div class="flex items-center justify-between px-4 py-3 bg-white dark:bg-slate-900 border-b border-slate-200/80 dark:border-slate-800">
                <div class="flex items-center gap-2">
                    <i data-lucide="help-circle" class="w-4 h-4 text-[#284c9a] dark:text-blue-400"></i>
                    <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100">Panduan Streaming Backup</h3>
                </div>
                <button type="button" data-bs-dismiss="modal"
                    class="w-7 h-7 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition cursor-pointer">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            <div class="p-4 sm:p-5 text-xs text-slate-600 dark:text-slate-300 space-y-2.5 leading-relaxed">
                <p>Fitur <strong>Streaming Download</strong> memungkinkan Anda mengunduh salinan database (dump SQL) secara langsung dari server tanpa memakan ruang penyimpanan disk server.</p>
                <ul class="list-disc pl-4 space-y-1">
                    <li><strong>Download Langsung:</strong> Mulai unduhan seketika melalui browser.</li>
                    <li><strong>Download dengan Progress:</strong> Menampilkan indikator kemajuan unduhan untuk database berukuran besar.</li>
                </ul>
            </div>
            <div class="px-4 py-3 border-t border-slate-200/80 dark:border-slate-800 flex justify-end">
                <button type="button" data-bs-dismiss="modal"
                    class="h-[34px] px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold transition cursor-pointer">
                    Mengerti
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('myscript')
<script>
    $(function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
</script>
@endpush
