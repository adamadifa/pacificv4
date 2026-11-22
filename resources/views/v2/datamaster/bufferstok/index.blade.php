@extends('layouts.app')
@section('titlepage', 'Buffer & Max Stok')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Home</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-slate-400">Data Master</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Buffer Stok</span>
    </div>
@endsection

@section('content')
<div class="space-y-3.5 max-w-4xl">

    <!-- PAGE TITLE BAR -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Buffer & Max Stok Produk</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Kelola batas stok minimum (buffer) dan maksimum produk per cabang operasional.</p>
        </div>
    </div>

    <!-- FILTER CABANG (JIKA USER MEMILIKI AKSES) -->
    @hasanyrole($roles_show_cabang)
        <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-3 shadow-2xs transition-colors duration-200 max-w-xs">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="building-2" class="w-3.5 h-3.5"></i>
                </div>
                <select name="kode_cabang" id="kode_cabang_select"
                    class="w-full h-[34px] pl-8 pr-7 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer appearance-none">
                    @foreach ($cabang as $c)
                        <option value="{{ $c->kode_cabang }}" {{ (request('kode_cabang') ?? 'PST') == $c->kode_cabang ? 'selected' : '' }}>
                            {{ $c->nama_cabang }}
                        </option>
                    @endforeach
                </select>
                <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                    <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                </div>
            </div>
        </div>
    @endhasanyrole

    <!-- MAIN DATA TABLE FORM CARD WITH PACIFIC NAVY HEADER -->
    <form action="{{ route('bufferstok.update') }}" method="POST" id="formBufferstok">
        @csrf
        @method('PUT')

        <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-2xs overflow-hidden transition-colors duration-200">
            
            <div class="overflow-x-auto scrollbar-thin">
                <table class="w-full text-xs text-left border-collapse font-sans whitespace-nowrap">
                    <!-- Unified Header -->
                    <thead>
                        <!-- Row 1: Title Strip -->
                        <tr class="bg-[#284c9a] text-white">
                            <th colspan="4" class="px-4 py-3 bg-[#284c9a] text-white border-0">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <i data-lucide="layers" class="w-4 h-4 text-blue-200"></i>
                                        <span class="text-xs font-bold uppercase tracking-wider text-white">Data Buffer & Max Stok Produk</span>
                                    </div>
                                    <span class="text-[11px] font-semibold text-white/80">
                                        Cabang: <span id="labelCabangAktif" class="font-bold text-white">PST</span>
                                    </span>
                                </div>
                            </th>
                        </tr>
                        <!-- Row 2: Column Titles -->
                        <tr class="bg-[#1e3a78] text-white text-[11px] border-none">
                            <th class="py-2.5 px-3.5 w-36 font-bold uppercase tracking-wider text-white/90 border-none">KODE PRODUK</th>
                            <th class="py-2.5 px-4 font-bold uppercase tracking-wider text-white/90 border-none">NAMA PRODUK</th>
                            <th class="py-2.5 px-4 text-center w-40 font-bold uppercase tracking-wider text-white/90 border-none">BUFFER STOK</th>
                            <th class="py-2.5 px-4 text-center w-40 font-bold uppercase tracking-wider text-white/90 border-none">MAX. STOK</th>
                        </tr>
                    </thead>
                    <tbody id="loadbufferstok" class="divide-y divide-slate-100 dark:divide-slate-800/80 bg-white dark:bg-slate-900">
                        <tr>
                            <td colspan="4" class="py-12 text-center text-slate-400">
                                <div class="inline-flex items-center justify-center gap-2">
                                    <svg class="animate-spin h-5 w-5 text-brand-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span class="text-xs">Memuat data buffer stok...</span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Footer Action Button -->
            <div class="px-4 py-3 border-t border-slate-100 dark:border-slate-800/80 bg-slate-50/50 dark:bg-slate-900/50 flex justify-end">
                <button type="submit" id="btnSubmitBufferstok" data-loading-text="Memperbarui Buffer Stok..."
                    class="inline-flex items-center justify-center gap-1.5 h-[36px] px-5 rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs font-bold transition shadow-xs cursor-pointer">
                    <i data-lucide="save" class="w-3.5 h-3.5"></i>
                    <span>Simpan Perubahan Buffer Stok</span>
                </button>
            </div>
        </div>
    </form>

</div>
@endsection

@push('myscript')
<script>
    $(function() {
        const $loadContainer = $("#loadbufferstok");
        const $selectCabang = $("#kode_cabang_select");
        const $labelCabang = $("#labelCabangAktif");

        function loadBufferStok(kodeCabang) {
            kodeCabang = kodeCabang || ($selectCabang.length ? $selectCabang.val() : "PST") || "PST";
            $labelCabang.text(kodeCabang);
            $loadContainer.html(`
                <tr>
                    <td colspan="4" class="py-12 text-center text-slate-400">
                        <div class="inline-flex items-center justify-center gap-2">
                            <svg class="animate-spin h-5 w-5 text-brand-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span class="text-xs">Memuat buffer stok cabang ${kodeCabang}...</span>
                        </div>
                    </td>
                </tr>
            `);

            $loadContainer.load('/bufferstok/' + kodeCabang + '/getbufferstok', function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        }

        // Initial Load
        loadBufferStok();

        // Change Cabang
        $selectCabang.on("change", function() {
            loadBufferStok($(this).val());
        });
    });
</script>
@endpush
