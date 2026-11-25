@extends('layouts.app')
@section('titlepage', 'Mutasi Kendaraan')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Home</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-slate-400">General Affair</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Mutasi Kendaraan</span>
    </div>
@endsection

@section('content')
@php
    $totalMutasi = method_exists($mutasikendaraan, 'total') ? $mutasikendaraan->total() : $mutasikendaraan->count();
@endphp

<div class="space-y-3.5 w-full">

    <!-- PAGE TITLE BAR & ACTION BUTTONS -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Mutasi Kendaraan</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Mengelola data perpindahan dan mutasi kendaraan operasional antar cabang.</p>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            @can('mutasikendaraan.create')
                <button type="button" id="btnCreate"
                    class="inline-flex items-center justify-center gap-1.5 h-[34px] px-3.5 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] active:bg-[#152a57] text-white text-xs font-bold transition shadow-xs cursor-pointer shrink-0">
                    <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                    <span>Input Mutasi</span>
                </button>
            @endcan
        </div>
    </div>

    <!-- FILTER SECTION (NO-LABEL, COMPACT H-[34PX], LUCIDE PREFIX, ROUNDED-LG) -->
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-3 shadow-2xs transition-colors duration-200">
        <form action="{{ route('mutasikendaraan.index') }}" method="GET" id="formSearch">
            <div class="grid grid-cols-1 sm:grid-cols-12 gap-2 items-center">
                
                <!-- Cari No. Polisi (Col-span 10) -->
                <div class="sm:col-span-10 relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="search" class="w-3.5 h-3.5"></i>
                    </div>
                    <input type="text" name="no_polisi" id="no_polisi_search" value="{{ Request('no_polisi') }}"
                        placeholder="Cari No. Polisi Kendaraan..."
                        class="w-full h-[34px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition uppercase">
                </div>

                <!-- Tombol Aksi Filter (Col-span 2) -->
                <div class="sm:col-span-2 flex items-center gap-1.5">
                    <button type="submit"
                        class="flex-1 h-[34px] px-3 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] active:bg-[#152a57] text-white text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-xs cursor-pointer">
                        <i data-lucide="search" class="w-3.5 h-3.5"></i>
                        <span>Cari</span>
                    </button>
                    <a href="{{ route('mutasikendaraan.index') }}" title="Reset Filter"
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
                    <!-- Baris 1: Strip Judul Tabel (bg-[#284c9a]) -->
                    <tr class="bg-[#284c9a] text-white">
                        <th colspan="7" class="px-3.5 py-2 bg-[#284c9a] text-white border-0">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-1.5">
                                    <i data-lucide="truck" class="w-3.5 h-3.5 text-blue-200"></i>
                                    <span class="text-xs font-bold text-white uppercase tracking-wider">Data Mutasi Kendaraan</span>
                                </div>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-white/15 text-white">
                                    Total: {{ number_format($totalMutasi, 0, ',', '.') }} Data
                                </span>
                            </div>
                        </th>
                    </tr>
                    <!-- Baris 2: Header Kolom (bg-[#1e3a78]) -->
                    <tr class="bg-[#1e3a78] text-white text-[11px]">
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 w-36">NO. MUTASI</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 w-32">NO. POLISI</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 w-28">TGL MUTASI</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 w-40">ASAL</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 w-40">TUJUAN</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 min-w-[200px]">KETERANGAN</th>
                        <th class="py-2 px-3 text-center font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 w-16">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80">
                    @forelse ($mutasikendaraan as $k)
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition-colors">
                            
                            <!-- No. Mutasi -->
                            <td class="py-1.5 px-3 font-mono-num font-bold text-[#284c9a] dark:text-blue-400">
                                {{ $k->no_mutasi }}
                            </td>

                            <!-- No. Polisi -->
                            <td class="py-1.5 px-3 font-mono-num font-bold text-slate-900 dark:text-slate-100 uppercase">
                                <span class="inline-flex items-center px-2 py-0.5 rounded bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-slate-200 border border-slate-200/80 dark:border-slate-700 text-[11px]">
                                    {{ $k->no_polisi }}
                                </span>
                            </td>

                            <!-- Tanggal Mutasi -->
                            <td class="py-1.5 px-3 font-medium text-slate-600 dark:text-slate-300">
                                {{ formatIndo($k->tanggal) }}
                            </td>

                            <!-- Asal -->
                            <td class="py-1.5 px-3 font-semibold text-slate-700 dark:text-slate-300 uppercase">
                                {{ $k->cabang_asal }}
                            </td>

                            <!-- Tujuan -->
                            <td class="py-1.5 px-3 font-semibold text-emerald-700 dark:text-emerald-400 uppercase">
                                {{ $k->cabang_tujuan }}
                            </td>

                            <!-- Keterangan -->
                            <td class="py-1.5 px-3 text-slate-600 dark:text-slate-400">
                                {{ $k->keterangan ?: '-' }}
                            </td>

                            <!-- Aksi Kompak (~24px) -->
                            <td class="py-1.5 px-3 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    @can('mutasikendaraan.delete')
                                        <form method="POST" action="{{ route('mutasikendaraan.delete', Crypt::encrypt($k->no_mutasi)) }}"
                                            class="inline-flex m-0 p-0 deleteform">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="delete-confirm w-6 h-6 rounded-md bg-rose-50 text-rose-600 hover:bg-rose-100 dark:bg-rose-950/50 dark:text-rose-400 inline-flex items-center justify-center transition shadow-2xs cursor-pointer"
                                                title="Hapus Mutasi">
                                                <i data-lucide="trash-2" class="w-3 h-3"></i>
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400 dark:text-slate-500">
                                <div class="flex flex-col items-center justify-center gap-1.5">
                                    <i data-lucide="truck" class="w-8 h-8 stroke-[1.2] text-slate-300 dark:text-slate-600"></i>
                                    <span class="text-xs font-semibold">Belum ada data Mutasi Kendaraan</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- PAGINATION STRIP -->
        @if ($mutasikendaraan->hasPages())
            <div class="px-4 py-3 border-t border-slate-100 dark:border-slate-800/80 bg-slate-50/50 dark:bg-slate-900/50">
                {{ $mutasikendaraan->links('v2.components.pagination') }}
            </div>
        @endif
    </div>

</div>

<!-- MODAL FORM MUTASI KENDARAAN (STATIC BACKDROP, EXPLICIT CLOSE) -->
<div class="modal fade" id="modalMutasi" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-2xl overflow-hidden transition-all duration-150">
            
            <!-- Modal Header (Clean Standard V2) -->
            <div class="flex items-center justify-between px-4 py-3 bg-white dark:bg-slate-900 border-b border-slate-200/80 dark:border-slate-800">
                <div class="flex items-center gap-2">
                    <i data-lucide="truck" class="w-4 h-4 text-[#284c9a] dark:text-blue-400"></i>
                    <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100 modal-title">Input Mutasi Kendaraan</h3>
                </div>
                <button type="button" data-bs-dismiss="modal"
                    class="w-7 h-7 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition cursor-pointer">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-4 sm:p-5 max-h-[85vh] overflow-y-auto" id="loadmodal">
                <!-- Dynamic Content via AJAX -->
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

        // Static modal bounce feedback on backdrop click
        $('#modalMutasi').on('click', function(e) {
            if ($(e.target).is('#modalMutasi')) {
                const $dialog = $(this).find('.modal-content');
                $dialog.addClass('scale-[1.015]');
                setTimeout(() => $dialog.removeClass('scale-[1.015]'), 150);
            }
        });

        function loading() {
            $("#loadmodal").html(`
                <div class="flex flex-col items-center justify-center py-10 gap-3">
                    <svg class="animate-spin h-7 w-7 text-[#284c9a] dark:text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                    <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Memuat formulir...</span>
                </div>
            `);
        }

        $("#btnCreate").click(function(e) {
            e.preventDefault();
            loading();
            $("#modalMutasi").modal("show");
            $("#modalMutasi").find(".modal-title").text("Input Mutasi Kendaraan");
            $("#modalMutasi").find("#loadmodal").load(`/mutasikendaraan/create`, function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });
    });
</script>
@endpush
