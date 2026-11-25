@extends('v2.layouts.app')
@section('titlepage', 'Log Error Presensi')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Human Resources</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Log Error Presensi</span>
    </div>
@endsection

@section('content')
<div class="space-y-3.5 w-full max-w-7xl">

    <!-- PAGE TITLE BAR -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Log Error Presensi</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Daftar error saat proses presensi via Mobile Apps.</p>
        </div>
    </div>

    <!-- FILTER BAR (COMPACT NO-LABEL V2) -->
    <form action="{{ route('presensi.logerror') }}" method="GET"
        class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-3 shadow-2xs">
        <div class="grid grid-cols-1 sm:grid-cols-12 gap-2 items-center">
            <div class="sm:col-span-3 relative">
                <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                </div>
                <input type="text" name="dari" id="dari_log_search" value="{{ request('dari') }}"
                    placeholder="Dari Tanggal" readonly
                    class="flatpickr-date w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition cursor-pointer">
            </div>

            <div class="sm:col-span-3 relative">
                <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                </div>
                <input type="text" name="sampai" id="sampai_log_search" value="{{ request('sampai') }}"
                    placeholder="Sampai Tanggal" readonly
                    class="flatpickr-date w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition cursor-pointer">
            </div>

            <div class="sm:col-span-5 relative">
                <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="user" class="w-4 h-4"></i>
                </div>
                <input type="text" name="nama_karyawan" id="nama_karyawan_log_search" value="{{ request('nama_karyawan') }}"
                    placeholder="Cari Nama Karyawan..."
                    class="w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
            </div>

            <div class="sm:col-span-1">
                <button type="submit"
                    class="w-full h-[36px] px-3 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold shadow-xs transition flex items-center justify-center gap-1 cursor-pointer">
                    <i data-lucide="search" class="w-3.5 h-3.5"></i>
                    <span class="sm:hidden lg:inline">Cari</span>
                </button>
            </div>
        </div>
    </form>

    <!-- DATA TABLE CARD (V2 COMPACT ~36PX) -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-2xs overflow-hidden">
        <div class="px-3.5 py-2 bg-[#284c9a] flex items-center justify-between text-white">
            <div class="flex items-center gap-2">
                <i data-lucide="alert-circle" class="w-4 h-4 text-blue-200"></i>
                <h5 class="text-xs font-bold text-white uppercase tracking-wider">Data Log Error</h5>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left whitespace-nowrap">
                <thead class="bg-[#1e3a78] text-white/90 text-[11px] font-bold uppercase tracking-wider">
                    <tr>
                        <th class="py-2 px-3 w-12 text-center">NO</th>
                        <th class="py-2 px-3">WAKTU</th>
                        <th class="py-2 px-3">NIK</th>
                        <th class="py-2 px-3">NAMA KARYAWAN</th>
                        <th class="py-2 px-3 text-center">STATUS</th>
                        <th class="py-2 px-3">PESAN ERROR</th>
                        <th class="py-2 px-3 text-center w-16">#</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse ($log_error as $d)
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition-colors">
                            <td class="py-1.5 px-3 text-center font-mono-num text-slate-500">{{ $loop->iteration + $log_error->firstItem() - 1 }}</td>
                            <td class="py-1.5 px-3 font-mono-num text-slate-600 dark:text-slate-300">{{ date('d-m-Y H:i:s', strtotime($d->jam)) }}</td>
                            <td class="py-1.5 px-3 font-mono-num text-slate-600 dark:text-slate-300">{{ $d->nik }}</td>
                            <td class="py-1.5 px-3 font-bold text-slate-800 dark:text-slate-100">{{ $d->nama_karyawan }}</td>
                            <td class="py-1.5 px-3 text-center">
                                @if ($d->status_presensi == 'masuk')
                                    <span class="px-2 py-0.5 rounded-md bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 text-[11px] font-bold">Masuk</span>
                                @else
                                    <span class="px-2 py-0.5 rounded-md bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 text-[11px] font-bold">Pulang</span>
                                @endif
                            </td>
                            <td class="py-1.5 px-3 text-slate-700 dark:text-slate-300 max-w-[380px] truncate" title="{{ $d->error_message }}">
                                {{ $d->error_message }}
                            </td>
                            <td class="py-1.5 px-3 text-center">
                                <button type="button" payload="{{ $d->payload }}"
                                    class="btnShowPayload w-6 h-6 rounded-md bg-sky-50 hover:bg-sky-100 text-sky-600 dark:bg-sky-950/50 dark:text-sky-400 inline-flex items-center justify-center transition cursor-pointer"
                                    title="Lihat Payload">
                                    <i data-lucide="eye" class="w-3 h-3"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-10 px-4 text-center">
                                <div class="flex flex-col items-center justify-center gap-1.5 text-slate-400">
                                    <i data-lucide="database-zap" class="w-8 h-8 text-slate-300 dark:text-slate-600"></i>
                                    <span class="text-xs font-semibold">Tidak ada log error presensi</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($log_error->hasPages())
            <div class="px-4 py-3 border-t border-slate-100 dark:border-slate-800/80 bg-slate-50/50 dark:bg-slate-900/50">
                {{ $log_error->links('v2.components.pagination') }}
            </div>
        @endif
    </div>
</div>

<!-- ================= MODAL PAYLOAD (STATIC BACKDROP) ================= -->
<div id="modalPayload" class="fixed inset-0 z-50 hidden">
    <div id="modalPayloadBackdrop" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4 overflow-y-auto">
        <div id="modalPayloadDialog"
            class="relative w-full max-w-2xl bg-white dark:bg-slate-900 rounded-xl shadow-2xl border border-slate-200/80 dark:border-slate-800 transform transition-all duration-150 my-auto">
            <div class="flex items-center justify-between px-4 py-3 bg-white dark:bg-slate-900 border-b border-slate-200/80 dark:border-slate-800 rounded-t-xl">
                <div class="flex items-center gap-2">
                    <i data-lucide="code-2" class="w-4 h-4 text-[#284c9a] dark:text-blue-400"></i>
                    <h5 class="text-sm font-bold text-slate-800 dark:text-slate-100">Detail Payload</h5>
                </div>
                <button type="button" id="btnCloseModalPayload"
                    class="w-7 h-7 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition cursor-pointer">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            <div id="loadpayload" class="p-4 sm:p-5 max-h-[80vh] overflow-y-auto"></div>
        </div>
    </div>
</div>
@endsection

@push('myscript')
<script>
    $(function() {
        if (typeof lucide !== 'undefined') lucide.createIcons();

        $('.flatpickr-date').flatpickr({
            dateFormat: 'Y-m-d',
            allowInput: false
        });

        const $modal = $('#modalPayload');
        const $dialog = $('#modalPayloadDialog');
        const $backdrop = $('#modalPayloadBackdrop');
        const $content = $('#loadpayload');

        $('#btnCloseModalPayload').on('click', function() {
            $modal.addClass('hidden');
            $('body').removeClass('overflow-hidden');
            $content.html('');
        });

        $backdrop.on('click', function(e) {
            e.stopPropagation();
            $dialog.addClass('scale-[1.015]');
            setTimeout(() => $dialog.removeClass('scale-[1.015]'), 150);
        });

        $(document).on('click', '.btnShowPayload', function(e) {
            e.preventDefault();
            const payload = $(this).attr('payload');
            let formatted = payload;
            try {
                formatted = JSON.stringify(JSON.parse(payload), null, 4);
            } catch (err) {}

            $content.html(`<pre class="p-3 rounded-lg bg-slate-50 dark:bg-slate-800/70 border border-slate-200 dark:border-slate-700 text-xs font-mono-num text-slate-700 dark:text-slate-200 whitespace-pre-wrap break-words">${formatted}</pre>`);
            $modal.removeClass('hidden');
            $('body').addClass('overflow-hidden');
        });
    });
</script>
@endpush
