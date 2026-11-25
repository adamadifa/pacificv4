@extends('layouts.app')
@section('titlepage', 'Log Aktivitas')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Home</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-slate-400">Utilities</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Log Aktivitas</span>
    </div>
@endsection

@section('content')
@php
    $totalLog = method_exists($activity, 'total') ? $activity->total() : $activity->count();
@endphp

<div class="space-y-3.5 w-full">

    <!-- PAGE TITLE BAR & ACTION BUTTONS -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Log Aktivitas Sistem</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Rekam jejak audit transaksi, perubahan data, dan aktivitas pengguna sistem.</p>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            <form action="{{ route('activitylog.prune') }}" method="POST" class="inline-flex m-0 p-0">
                @csrf
                <button type="submit"
                    class="delete-confirm inline-flex items-center justify-center gap-1.5 h-[34px] px-3.5 rounded-lg bg-rose-600 hover:bg-rose-700 active:bg-rose-800 text-white text-xs font-bold transition shadow-xs cursor-pointer shrink-0">
                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                    <span>Prune Log (&gt; 3 Bulan)</span>
                </button>
            </form>
        </div>
    </div>

    <!-- FILTER SECTION (NO-LABEL, COMPACT H-[34PX], LUCIDE PREFIX, ROUNDED-LG) -->
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-3 shadow-2xs transition-colors duration-200">
        <form action="{{ route('activitylog.index') }}" method="GET" id="formSearch" class="space-y-2">
            
            <!-- Baris 1: Dari, Sampai, Kategori, Aktivitas -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-2 items-center">
                
                <!-- Dari Tanggal (Col-span 3) -->
                <div class="lg:col-span-3 relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                    </div>
                    <input type="text" name="dari" id="dari_log" value="{{ Request('dari') }}" placeholder="Dari Tanggal" readonly
                        class="flatpickr-date w-full h-[34px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer">
                </div>

                <!-- Sampai Tanggal (Col-span 3) -->
                <div class="lg:col-span-3 relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                    </div>
                    <input type="text" name="sampai" id="sampai_log" value="{{ Request('sampai') }}" placeholder="Sampai Tanggal" readonly
                        class="flatpickr-date w-full h-[34px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer">
                </div>

                <!-- Kategori (Col-span 3) -->
                <div class="lg:col-span-3 relative select2-container-v2">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                        <i data-lucide="folder" class="w-3.5 h-3.5"></i>
                    </div>
                    <select name="log_name" id="log_name" class="select2LogName w-full">
                        <option value="">Semua Kategori</option>
                        @foreach ($kategori as $d)
                            <option value="{{ $d->log_name }}" {{ Request('log_name') == $d->log_name ? 'selected' : '' }}>
                                {{ textCamelCase($d->log_name) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Aktivitas / Event (Col-span 3) -->
                <div class="lg:col-span-3 relative select2-container-v2">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                        <i data-lucide="zap" class="w-3.5 h-3.5"></i>
                    </div>
                    <select name="event" id="event" class="select2Event w-full">
                        <option value="">Semua Aktivitas</option>
                        <option value="create" {{ Request('event') == 'create' ? 'selected' : '' }}>Created</option>
                        <option value="update" {{ Request('event') == 'update' ? 'selected' : '' }}>Updated</option>
                        <option value="cancel" {{ Request('event') == 'cancel' ? 'selected' : '' }}>Cancel</option>
                        <option value="delete" {{ Request('event') == 'delete' ? 'selected' : '' }}>Delete</option>
                    </select>
                </div>

            </div>

            <!-- Baris 2: User, No. Bukti, Tombol Cari -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-2 items-center">
                
                <!-- User (Col-span 5) -->
                <div class="lg:col-span-5 relative select2-container-v2">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                        <i data-lucide="user" class="w-3.5 h-3.5"></i>
                    </div>
                    <select name="id_user" id="id_user" class="select2User w-full">
                        <option value="">Semua Pengguna</option>
                        @foreach ($users as $d)
                            <option value="{{ $d->id }}" {{ Request('id_user') == $d->id ? 'selected' : '' }}>
                                {{ $d->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- No. Bukti (Col-span 5) -->
                <div class="lg:col-span-5 relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="barcode" class="w-3.5 h-3.5"></i>
                    </div>
                    <input type="text" name="no_bukti" id="no_bukti_log" value="{{ Request('no_bukti') }}"
                        placeholder="Cari No. Bukti / Referensi"
                        class="w-full h-[34px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                </div>

                <!-- Tombol Aksi Filter (Col-span 2) -->
                <div class="lg:col-span-2 flex items-center gap-1.5">
                    <button type="submit" id="btnSearch"
                        class="flex-1 h-[34px] px-3 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] active:bg-[#152a57] text-white text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-xs cursor-pointer">
                        <i data-lucide="search" class="w-3.5 h-3.5"></i>
                        <span>Cari</span>
                    </button>
                    <a href="{{ route('activitylog.index') }}" title="Reset Filter"
                        class="h-[34px] w-[34px] rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700/60 text-slate-600 dark:text-slate-300 flex items-center justify-center transition shadow-2xs shrink-0 cursor-pointer">
                        <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                    </a>
                </div>

            </div>

        </form>
    </div>

    <!-- DATA TABLE CARD (V2 STANDARD ~36PX ROW HEIGHT) -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 overflow-hidden shadow-2xs">
        <div class="overflow-x-auto min-h-[260px]">
            <table class="w-full text-xs text-left border-separate border-spacing-0 whitespace-nowrap">
                <thead>
                    <tr class="bg-[#284c9a] text-white">
                        <th colspan="6" class="px-3.5 py-2 bg-[#284c9a] text-white border-0">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-1.5">
                                    <i data-lucide="activity" class="w-3.5 h-3.5 text-blue-200"></i>
                                    <span class="text-xs font-bold text-white uppercase tracking-wider">Data Log Aktivitas</span>
                                </div>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-white/15 text-white">
                                    Total: {{ number_format($totalLog, 0, ',', '.') }} Log
                                </span>
                            </div>
                        </th>
                    </tr>
                    <tr class="bg-[#1e3a78] text-white text-[11px]">
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 w-36">WAKTU</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 w-36">KATEGORI</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 w-40">USER</th>
                        <th class="py-2 px-3 text-center font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 w-24">AKSI</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 min-w-[260px]">AKTIVITAS</th>
                        <th class="py-2 px-3 text-center font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 w-16">DETAIL</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80">
                    @forelse ($activity as $d)
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition-colors">
                            <td class="py-1.5 px-3 font-mono-num text-slate-600 dark:text-slate-400">
                                {{ date('d-m-Y H:i:s', strtotime($d->created_at)) }}
                            </td>
                            <td class="py-1.5 px-3 font-semibold text-slate-800 dark:text-slate-200">
                                {{ textCamelCase($d->log_name) }}
                            </td>
                            <td class="py-1.5 px-3 font-bold text-slate-900 dark:text-slate-100">
                                {{ $d->name }}
                            </td>
                            <td class="py-1.5 px-3 text-center">
                                @if ($d->event == 'create')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800/60">
                                        {{ textUpperCase($d->event) }}
                                    </span>
                                @elseif ($d->event == 'update')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-sky-50 text-sky-700 dark:bg-sky-950/60 dark:text-sky-300 border border-sky-200 dark:border-sky-800/60">
                                        {{ textUpperCase($d->event) }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 dark:bg-rose-950/60 dark:text-rose-300 border border-rose-200 dark:border-rose-800/60">
                                        {{ textUpperCase($d->event) }}
                                    </span>
                                @endif
                            </td>
                            <td class="py-1.5 px-3 text-slate-700 dark:text-slate-300">
                                {{ $d->description }}
                            </td>
                            <td class="py-1.5 px-3 text-center">
                                <button type="button" properties="{{ json_encode($d->properties, JSON_PRETTY_PRINT) }}"
                                    class="showDetail w-6 h-6 rounded-md bg-blue-50 text-blue-600 hover:bg-blue-100 dark:bg-blue-950/50 dark:text-blue-400 inline-flex items-center justify-center transition shadow-2xs cursor-pointer"
                                    title="Lihat Properti Perubahan">
                                    <i data-lucide="file-json" class="w-3 h-3"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400 dark:text-slate-500">
                                <div class="flex flex-col items-center justify-center gap-1.5">
                                    <i data-lucide="activity" class="w-8 h-8 stroke-[1.2] text-slate-300 dark:text-slate-600"></i>
                                    <span class="text-xs font-semibold">Tidak ada data Log Aktivitas</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- PAGINATION STRIP -->
        @if ($activity->hasPages())
            <div class="px-4 py-3 border-t border-slate-100 dark:border-slate-800/80 bg-slate-50/50 dark:bg-slate-900/50">
                {{ $activity->links('v2.components.pagination') }}
            </div>
        @endif
    </div>

</div>

<!-- MODAL DETAIL LOG AKTIVITAS (STATIC BACKDROP, EXPLICIT CLOSE) -->
<div class="modal fade" id="modalDetailLog" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-2xl overflow-hidden transition-all duration-150">
            <div class="flex items-center justify-between px-4 py-3 bg-white dark:bg-slate-900 border-b border-slate-200/80 dark:border-slate-800">
                <div class="flex items-center gap-2">
                    <i data-lucide="file-json" class="w-4 h-4 text-[#284c9a] dark:text-blue-400"></i>
                    <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100 modal-title">Detail Aktivitas</h3>
                </div>
                <button type="button" data-bs-dismiss="modal"
                    class="w-7 h-7 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition cursor-pointer">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            <div class="p-4 sm:p-5 max-h-[80vh] overflow-y-auto" id="loadmodal"></div>
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

        $('.select2LogName').select2({ placeholder: 'Semua Kategori', allowClear: true, width: '100%' });
        $('.select2Event').select2({ placeholder: 'Semua Aktivitas', allowClear: true, width: '100%' });
        $('.select2User').select2({ placeholder: 'Semua Pengguna', allowClear: true, width: '100%' });

        $('#modalDetailLog').on('click', function(e) {
            if ($(e.target).is('#modalDetailLog')) {
                const $dialog = $(this).find('.modal-content');
                $dialog.addClass('scale-[1.015]');
                setTimeout(() => $dialog.removeClass('scale-[1.015]'), 150);
            }
        });

        $(document).on('click', '.showDetail', function() {
            const properties = $(this).attr('properties');
            $('#modalDetailLog').modal('show');
            $('#modalDetailLog').find('#loadmodal').html(`
                <pre class="p-3.5 rounded-lg bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-xs font-mono-num text-slate-800 dark:text-slate-200 overflow-x-auto leading-relaxed">${properties}</pre>
            `);
        });
    });
</script>
@endpush
