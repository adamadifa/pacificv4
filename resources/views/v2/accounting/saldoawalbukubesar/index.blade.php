@extends('v2.layouts.app')
@section('titlepage', 'Saldo Awal Buku Besar')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Accounting</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Saldo Awal Buku Besar</span>
    </div>
@endsection

@section('content')
<div class="space-y-3.5 w-full max-w-3xl">

    <!-- PAGE TITLE BAR -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Saldo Awal Buku Besar</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Manajemen saldo awal buku besar (CoA) per periode.</p>
        </div>
        @can('saldoawalbukubesar.create')
            <a href="{{ route('saldoawalbukubesar.create') }}"
                class="h-[34px] px-3.5 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold shadow-xs transition flex items-center gap-1.5 w-fit">
                <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                <span>Buat Saldo Awal</span>
            </a>
        @endcan
    </div>

    <!-- FILTER BAR (COMPACT NO-LABEL V2) -->
    <form action="{{ route('saldoawalbukubesar.index') }}" method="GET"
        class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-3 shadow-2xs">
        <div class="grid grid-cols-1 sm:grid-cols-12 gap-2 items-center">
            <!-- Bulan -->
            <div class="sm:col-span-5 relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                </div>
                <select name="bulan" id="bulan_search"
                    class="w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                    <option value="">Semua Bulan</option>
                    @foreach ($list_bulan as $d)
                        <option value="{{ $d['kode_bulan'] }}" @selected(request('bulan') == $d['kode_bulan'])>
                            {{ $d['nama_bulan'] }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Tahun -->
            <div class="sm:col-span-5 relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar-days" class="w-4 h-4"></i>
                </div>
                <select name="tahun" id="tahun_search"
                    class="w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                    <option value="">Pilih Tahun</option>
                    @for ($t = $start_year; $t <= date('Y'); $t++)
                        <option value="{{ $t }}"
                            @if (!empty(request('tahun'))) @selected(request('tahun') == $t)
                            @else @selected(date('Y') == $t) @endif>
                            {{ $t }}
                        </option>
                    @endfor
                </select>
            </div>

            <!-- Tombol Cari -->
            <div class="sm:col-span-2">
                <button type="submit"
                    class="w-full h-[36px] px-3 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold shadow-xs transition flex items-center justify-center gap-1.5 cursor-pointer">
                    <i data-lucide="search" class="w-3.5 h-3.5"></i>
                    <span>Cari</span>
                </button>
            </div>
        </div>
    </form>

    <!-- DATA TABLE CARD (V2 COMPACT ~36PX) -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-2xs overflow-hidden">
        <!-- BARIS 1: STRIP JUDUL TABEL (bg-[#284c9a]) -->
        <div class="px-3.5 py-2 bg-[#284c9a] flex items-center justify-between text-white">
            <div class="flex items-center gap-2">
                <i data-lucide="book-open" class="w-4 h-4 text-blue-200"></i>
                <h5 class="text-xs font-bold text-white uppercase tracking-wider">Data Saldo Awal</h5>
            </div>
            <span class="px-2 py-0.5 rounded-md bg-white/15 text-[11px] font-bold font-mono-num text-white">
                {{ $saldoawalbukubesar->count() }} Periode
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left whitespace-nowrap">
                <thead class="bg-[#1e3a78] text-white/90 text-[11px] font-bold uppercase tracking-wider">
                    <tr>
                        <th class="py-2 px-3.5">KODE</th>
                        <th class="py-2 px-3">BULAN</th>
                        <th class="py-2 px-3">TAHUN</th>
                        <th class="py-2 px-3 text-center w-24">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse ($saldoawalbukubesar as $d)
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition-colors">
                            <td class="py-1.5 px-3.5 font-mono-num font-bold text-slate-800 dark:text-slate-100">
                                {{ $d->kode_saldo_awal }}
                            </td>
                            <td class="py-1.5 px-3 font-medium text-slate-700 dark:text-slate-300">
                                {{ $nama_bulan[$d->bulan] ?? $d->bulan }}
                            </td>
                            <td class="py-1.5 px-3 font-mono-num text-slate-600 dark:text-slate-400">
                                {{ $d->tahun }}
                            </td>
                            <td class="py-1.5 px-3">
                                <div class="flex items-center justify-center gap-1">
                                    @can('saldoawalbukubesar.show')
                                        <button type="button"
                                            class="btnShow w-6 h-6 rounded-md bg-sky-50 hover:bg-sky-100 text-sky-600 dark:bg-sky-950/50 dark:hover:bg-sky-900/60 dark:text-sky-400 flex items-center justify-center transition cursor-pointer"
                                            kode_saldo_awal="{{ Crypt::encrypt($d->kode_saldo_awal) }}"
                                            title="Lihat Detail">
                                            <i data-lucide="file-text" class="w-3 h-3"></i>
                                        </button>
                                    @endcan
                                    @can('saldoawalbukubesar.delete')
                                        <form method="POST" name="deleteform" class="inline-flex m-0 p-0 deleteform"
                                            action="{{ route('saldoawalbukubesar.delete', Crypt::encrypt($d->kode_saldo_awal)) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="delete-confirm w-6 h-6 rounded-md bg-rose-50 hover:bg-rose-100 text-rose-600 dark:bg-rose-950/50 dark:hover:bg-rose-900/60 dark:text-rose-400 flex items-center justify-center transition cursor-pointer"
                                                title="Hapus">
                                                <i data-lucide="trash-2" class="w-3 h-3"></i>
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-10 px-4 text-center">
                                <div class="flex flex-col items-center justify-center gap-1.5 text-slate-400">
                                    <i data-lucide="database-zap" class="w-8 h-8 text-slate-300 dark:text-slate-600"></i>
                                    <span class="text-xs font-semibold">Tidak ada data saldo awal ditemukan</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ================= MODAL DETAIL SALDO AWAL (STATIC BACKDROP) ================= -->
<div id="modalSaldoAwal" class="fixed inset-0 z-50 hidden">
    <div id="modalSaldoAwalBackdrop" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4 overflow-y-auto">
        <div id="modalSaldoAwalDialog"
            class="relative w-full max-w-2xl bg-white dark:bg-slate-900 rounded-xl shadow-2xl border border-slate-200/80 dark:border-slate-800 transform transition-all duration-150 my-auto">
            <!-- Modal Header Bersih -->
            <div class="flex items-center justify-between px-4 py-3 bg-white dark:bg-slate-900 border-b border-slate-200/80 dark:border-slate-800 rounded-t-xl">
                <div class="flex items-center gap-2">
                    <i data-lucide="book-open" class="w-4 h-4 text-[#284c9a] dark:text-blue-400"></i>
                    <h5 class="text-sm font-bold text-slate-800 dark:text-slate-100">Detail Saldo Awal Buku Besar</h5>
                </div>
                <button type="button" id="btnCloseModalSaldoAwal"
                    class="w-7 h-7 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition cursor-pointer">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            <!-- Modal Body -->
            <div id="loadmodalSaldoAwal" class="p-4 sm:p-5 max-h-[80vh] overflow-y-auto"></div>
        </div>
    </div>
</div>
@endsection

@push('myscript')
<script>
    $(function() {
        if (typeof lucide !== 'undefined') lucide.createIcons();

        const $modal = $('#modalSaldoAwal');
        const $dialog = $('#modalSaldoAwalDialog');
        const $backdrop = $('#modalSaldoAwalBackdrop');
        const $content = $('#loadmodalSaldoAwal');

        function openModal() {
            $modal.removeClass('hidden');
            $('body').addClass('overflow-hidden');
        }

        function closeModal() {
            $modal.addClass('hidden');
            $('body').removeClass('overflow-hidden');
            $content.html('');
        }

        $('#btnCloseModalSaldoAwal').on('click', closeModal);

        // Static backdrop: tidak menutup modal saat klik area luar
        $backdrop.on('click', function(e) {
            e.stopPropagation();
            $dialog.addClass('scale-[1.015]');
            setTimeout(() => $dialog.removeClass('scale-[1.015]'), 150);
        });

        $(document).on('click', '.btnShow', function(e) {
            e.preventDefault();
            const kode_saldo_awal = $(this).attr('kode_saldo_awal');
            openModal();
            $content.html(`
                <div class="py-12 flex flex-col items-center justify-center gap-2">
                    <svg class="animate-spin h-6 w-6 text-[#284c9a]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-xs font-semibold text-slate-500">Memuat detail saldo awal...</span>
                </div>
            `);

            $content.load('/saldoawalbukubesar/' + kode_saldo_awal + '/show', function(response, status) {
                if (status === 'error') {
                    $content.html(`
                        <div class="p-4 rounded-xl border border-rose-200 bg-rose-50 text-rose-700 text-xs font-semibold text-center">
                            Gagal memuat detail saldo awal. Silakan coba kembali.
                        </div>
                    `);
                }
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });
    });
</script>
@endpush
