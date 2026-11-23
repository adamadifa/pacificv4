@extends('v2.layouts.app')
@section('titlepage', 'Saldo Awal Piutang')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Home</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-slate-400">Marketing</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Saldo Awal Piutang</span>
    </div>
@endsection

@section('content')
<div class="space-y-3.5 max-w-4xl">

    <!-- PAGE TITLE BAR & ACTION BUTTONS -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Saldo Awal Piutang</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Mengelola data saldo awal piutang pelanggan per periode.</p>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            @can('sapiutang.create')
                <a href="{{ route('sapiutang.create') }}" id="btnCreate"
                    class="inline-flex items-center justify-center gap-1.5 h-[34px] px-3.5 rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs font-bold transition shadow-xs cursor-pointer shrink-0">
                    <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                    <span>Tambah Data</span>
                </a>
            @endcan
        </div>
    </div>
    <!-- FILTER SECTION (NO-LABEL, KOMPAK ~36px) -->
    <div class="w-full rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-3 sm:p-3.5 shadow-2xs">
        <form action="{{ route('sapiutang.index') }}" method="GET" id="formSearch" class="space-y-2.5">
            <div class="grid grid-cols-1 sm:grid-cols-12 gap-2 sm:gap-2.5 items-center">
                
                <!-- Bulan -->
                <div class="sm:col-span-6">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="calendar" class="w-4 h-4"></i>
                        </div>
                        <select name="bulan" id="bulan"
                            class="w-full h-[36px] pl-9 pr-3 text-xs bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-lg focus:outline-hidden focus:ring-1 focus:ring-[#284c9a] dark:focus:ring-blue-500 font-semibold text-slate-800 dark:text-slate-100">
                            <option value="">Semua Bulan</option>
                            @foreach ($list_bulan as $d)
                                <option {{ Request('bulan') == $d['kode_bulan'] ? 'selected' : '' }}
                                    value="{{ $d['kode_bulan'] }}">{{ $d['nama_bulan'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Tahun -->
                <div class="sm:col-span-4">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="calendar-days" class="w-4 h-4"></i>
                        </div>
                        <select name="tahun" id="tahun"
                            class="w-full h-[36px] pl-9 pr-3 text-xs bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-lg focus:outline-hidden focus:ring-1 focus:ring-[#284c9a] dark:focus:ring-blue-500 font-mono-num font-semibold text-slate-800 dark:text-slate-100">
                            <option value="">Tahun</option>
                            @for ($t = $start_year; $t <= date('Y'); $t++)
                                <option
                                    @if (!empty(Request('tahun'))) {{ Request('tahun') == $t ? 'selected' : '' }}
                                    @else
                                    {{ date('Y') == $t ? 'selected' : '' }} @endif
                                    value="{{ $t }}">{{ $t }}</option>
                            @endfor
                        </select>
                    </div>
                </div>

                <!-- Tombol Submit & Reset -->
                <div class="sm:col-span-2 flex items-center gap-1.5">
                    <button type="submit"
                        class="flex-1 h-[36px] px-3 rounded-lg bg-[#284c9a] hover:bg-blue-800 text-white text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-2xs cursor-pointer">
                        <i data-lucide="search" class="w-3.5 h-3.5"></i>
                        <span>Cari</span>
                    </button>
                    @if (Request('bulan') || (Request('tahun') && Request('tahun') != date('Y')))
                        <a href="{{ route('sapiutang.index') }}"
                            class="h-[36px] w-[36px] rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 flex items-center justify-center transition shadow-2xs"
                            title="Reset Filter">
                            <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                        </a>
                    @endif
                </div>

            </div>
        </form>
    </div>

    <!-- DATA TABLE SECTION (KOMPAK ~36px) -->
    <div class="w-full rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 overflow-hidden shadow-2xs">
        <div class="overflow-x-auto min-h-[240px]">
            <table class="w-full text-xs text-left border-separate border-spacing-0 whitespace-nowrap">
                <thead>
                    <!-- Baris 1: Strip Header Pacific Navy -->
                    <tr class="bg-[#284c9a] text-white">
                        <th colspan="5" class="px-3.5 py-2 bg-[#284c9a] text-white border-0">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-1.5">
                                    <i data-lucide="database-import" class="w-3.5 h-3.5 text-blue-200"></i>
                                    <span class="text-xs font-bold text-white">Data Saldo Awal Piutang</span>
                                </div>
                                <span class="text-[10.5px] text-white/70 font-mono-num">
                                    Total: {{ count($saldo_awal) }} Data
                                </span>
                            </div>
                        </th>
                    </tr>

                    <!-- Baris 2: Header Kolom -->
                    <tr class="bg-[#1e3a78] text-white text-[11px]">
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 w-32">KODE</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-blue-900">BULAN</th>
                        <th class="py-2 px-3 text-center font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 w-24">TAHUN</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 w-32">TANGGAL</th>
                        <th class="py-2 px-3 text-center font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 w-20">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80">
                    @forelse ($saldo_awal as $d)
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition">
                            
                            <!-- 1. Kode -->
                            <td class="py-1.5 px-3 font-mono-num font-bold text-[#284c9a] dark:text-blue-400">
                                {{ $d->kode_saldo_awal }}
                            </td>

                            <!-- 2. Bulan -->
                            <td class="py-1.5 px-3 font-semibold text-slate-800 dark:text-slate-100">
                                {{ $nama_bulan[$d->bulan] }}
                            </td>

                            <!-- 3. Tahun -->
                            <td class="py-1.5 px-3 text-center font-mono-num text-slate-700 dark:text-slate-200">
                                {{ $d->tahun }}
                            </td>

                            <!-- 4. Tanggal -->
                            <td class="py-1.5 px-3 font-mono-num text-slate-600 dark:text-slate-300">
                                {{ date('d-m-Y', strtotime($d->tanggal)) }}
                            </td>

                            <!-- 5. Aksi -->
                            <td class="py-1.5 px-3 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    
                                    <!-- Detail -->
                                    @can('sapiutang.show')
                                        <button type="button" kode_saldo_awal="{{ Crypt::encrypt($d->kode_saldo_awal) }}"
                                            class="showSaldoawal w-6 h-6 rounded-md bg-blue-50 hover:bg-blue-100 text-blue-600 dark:bg-blue-950/40 dark:text-blue-400 flex items-center justify-center transition cursor-pointer"
                                            title="Detail Saldo Awal">
                                            <i data-lucide="file-text" class="w-3 h-3"></i>
                                        </button>
                                    @endcan

                                    <!-- Hapus -->
                                    @can('sapiutang.delete')
                                        <form method="POST" name="deleteform" class="inline-flex m-0 p-0 deleteform"
                                            action="{{ route('sapiutang.delete', Crypt::encrypt($d->kode_saldo_awal)) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="delete-confirm w-6 h-6 rounded-md bg-rose-50 hover:bg-rose-100 text-rose-600 dark:bg-rose-950/40 dark:text-rose-400 flex items-center justify-center transition cursor-pointer"
                                                title="Hapus Saldo Awal">
                                                <i data-lucide="trash-2" class="w-3 h-3"></i>
                                            </button>
                                        </form>
                                    @endcan

                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 px-4 text-center">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <div class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-400 flex items-center justify-center">
                                        <i data-lucide="inbox" class="w-5 h-5"></i>
                                    </div>
                                    <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Tidak ada data saldo awal piutang yang ditemukan</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL DETAIL SALDO AWAL (TAILWIND V2 STATIC BACKDROP) -->
<div id="modalDetailSaldo" class="fixed inset-0 z-50 hidden transition-opacity duration-200">
    <!-- Backdrop Overlay -->
    <div id="modalDetailBackdrop" class="fixed inset-0 bg-slate-950/40 backdrop-blur-xs transition-opacity"></div>
    
    <div class="fixed inset-0 z-10 overflow-y-auto p-4 sm:p-6 flex items-center justify-center min-h-full">
        <div id="modalDetailDialog" class="relative w-full max-w-4xl bg-white dark:bg-slate-900 rounded-xl shadow-2xl border border-slate-200/80 dark:border-slate-800 transition-all transform duration-150">
            
            <!-- Modal Header (Clean Standard V2) -->
            <div class="flex items-center justify-between px-4 py-3 border-b border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-lg bg-blue-50 dark:bg-blue-950/60 text-[#284c9a] dark:text-blue-400 flex items-center justify-center">
                        <i data-lucide="database-import" class="w-4 h-4"></i>
                    </div>
                    <h3 id="modalDetailTitle" class="text-sm font-bold text-slate-800 dark:text-slate-100">Detail Saldo Awal Piutang</h3>
                </div>
                <button type="button" id="btnCloseModalDetail" class="w-7 h-7 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>

            <!-- Modal Content Loaded via AJAX -->
            <div id="modalDetailContent" class="p-4 sm:p-5 max-h-[80vh] overflow-y-auto">
                <!-- Dynamic Content Here -->
            </div>

        </div>
    </div>
</div>

@endsection

@push('myscript')
<script>
    $(function() {
        // Modal Management & Static Backdrop Handlers
        const $modal = $("#modalDetailSaldo");
        const $dialog = $("#modalDetailDialog");
        const $title = $("#modalDetailTitle");
        const $content = $("#modalDetailContent");

        function showLoading() {
            $content.html(`
                <div class="flex flex-col items-center justify-center py-12 gap-3">
                    <svg class="animate-spin h-6 w-6 text-[#284c9a] dark:text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Memuat Rincian Piutang...</span>
                </div>
            `);
        }

        function openModal(titleText) {
            $title.text(titleText);
            showLoading();
            $modal.removeClass('hidden');
            $('body').addClass('overflow-hidden');
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        }

        function closeModal() {
            $modal.addClass('hidden');
            $('body').removeClass('overflow-hidden');
            $content.html('');
        }

        function bounceModal() {
            $dialog.addClass('scale-[1.015] shadow-2xl');
            setTimeout(function() {
                $dialog.removeClass('scale-[1.015] shadow-2xl');
            }, 150);
        }

        // Static Backdrop Behavior
        $("#modalDetailBackdrop").on('click', function(e) {
            e.stopPropagation();
            bounceModal();
        });

        $(document).on('keydown', function(e) {
            if (e.key === 'Escape' && !$modal.hasClass('hidden')) {
                bounceModal();
            }
        });

        $("#btnCloseModalDetail").on('click', function() {
            closeModal();
        });

        // Open Detail Saldo Awal
        $(document).on('click', '.showSaldoawal', function(e) {
            e.preventDefault();
            const kode_saldo_awal = $(this).attr('kode_saldo_awal');
            openModal("Detail Saldo Awal Piutang");
            $.ajax({
                type: 'GET',
                url: `/sapiutang/${kode_saldo_awal}/show`,
                success: function(response) {
                    $content.html(response);
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }
                },
                error: function() {
                    $content.html(`
                        <div class="py-8 text-center text-rose-500 font-semibold text-xs">
                            Gagal memuat rincian saldo awal piutang. Silakan coba lagi.
                        </div>
                    `);
                }
            });
        });

        // SweetAlert Delete Confirm
        $(".delete-confirm").click(function(e) {
            var form = $(this).closest("form");
            e.preventDefault();
            Swal.fire({
                title: 'Apakah Anda Yakin Ingin Menghapus Data Ini ?',
                text: "Jika dihapus maka data akan hilang permanent.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#284c9a',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Hapus Saja!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });

        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
</script>
@endpush
