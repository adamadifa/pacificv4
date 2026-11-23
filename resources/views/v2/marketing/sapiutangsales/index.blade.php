@extends('v2.layouts.app')
@section('titlepage', 'S.A Piutang Sales')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Home</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-slate-400">Marketing</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">S.A Piutang Sales</span>
    </div>
@endsection

@section('content')
<div class="space-y-3.5 max-w-5xl">

    <!-- PAGE TITLE BAR & ACTION BUTTONS -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">S.A Piutang Sales</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Mengelola data saldo awal piutang salesman per cabang.</p>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            @can('sapiutangsales.create')
                <a href="{{ route('sapiutangsales.create') }}" id="btnCreate"
                    class="inline-flex items-center justify-center gap-1.5 h-[34px] px-3.5 rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs font-bold transition shadow-xs cursor-pointer shrink-0">
                    <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                    <span>Tambah Data</span>
                </a>
            @endcan
        </div>
    </div>
    <!-- FILTER SECTION (NO-LABEL, KOMPAK ~36px) -->
    <div class="w-full rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-3 sm:p-3.5 shadow-2xs">
        <form action="{{ route('sapiutangsales.index') }}" method="GET" id="formSearch" class="space-y-2.5">
            <div class="grid grid-cols-1 sm:grid-cols-12 gap-2 sm:gap-2.5 items-center">
                
                <!-- Bulan -->
                <div class="sm:col-span-6">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="calendar" class="w-4 h-4"></i>
                        </div>
                        <select name="bulan" id="bulan"
                            class="w-full h-[36px] pl-9 pr-3 text-xs bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-lg focus:outline-hidden focus:ring-1 focus:ring-[#284c9a] dark:focus:ring-blue-500 font-semibold text-slate-800 dark:text-slate-100 cursor-pointer">
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
                            class="w-full h-[36px] pl-9 pr-3 text-xs bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-lg focus:outline-hidden focus:ring-1 focus:ring-[#284c9a] dark:focus:ring-blue-500 font-mono-num font-semibold text-slate-800 dark:text-slate-100 cursor-pointer">
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

                <!-- Tombol Aksi Filter -->
                <div class="sm:col-span-2 flex items-center gap-1.5">
                    <button type="submit"
                        class="flex-1 h-[36px] px-3 bg-[#284c9a] hover:bg-[#1e3a78] text-white font-bold rounded-lg text-xs flex items-center justify-center gap-1.5 transition shadow-2xs cursor-pointer">
                        <i data-lucide="search" class="w-3.5 h-3.5"></i>
                        <span>Cari</span>
                    </button>
                    @if (Request('bulan') || Request('tahun'))
                        <a href="{{ route('sapiutangsales.index') }}"
                            class="h-[36px] px-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 font-bold rounded-lg text-xs flex items-center justify-center transition shrink-0 cursor-pointer"
                            title="Reset Filter">
                            <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i>
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <!-- DATA TABLE CARD (KOMPAK ~36px) -->
    <div class="w-full rounded-xl overflow-hidden border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-2xs">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left border-separate border-spacing-0 whitespace-nowrap">
                <thead>
                    <!-- Baris 1: Strip Header Pacific Navy -->
                    <tr class="bg-[#284c9a] text-white">
                        <th colspan="6" class="px-3.5 py-2 bg-[#284c9a] text-white border-0">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="database-import" class="w-3.5 h-3.5 text-blue-200"></i>
                                    <span class="text-xs font-bold text-white uppercase tracking-wider">Data Saldo Awal Piutang Sales</span>
                                </div>
                                <span class="text-[10.5px] text-white/70 font-mono-num">
                                    Total: {{ count($saldo_awal) }} Data
                                </span>
                            </div>
                        </th>
                    </tr>

                    <!-- Baris 2: Header Kolom -->
                    <tr class="bg-[#1e3a78] text-white text-[11px]">
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 w-36">KODE</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 min-w-[160px]">CABANG</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 w-32">BULAN</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 w-24">TAHUN</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 w-28">TANGGAL</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 text-center w-20">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80">
                    @forelse ($saldo_awal as $d)
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition">
                            <td class="py-1.5 px-3 font-mono-num font-bold text-[#284c9a] dark:text-blue-400">
                                {{ $d->kode_saldo_awal }}
                            </td>
                            <td class="py-1.5 px-3 font-medium text-slate-800 dark:text-slate-100">
                                {{ $d->nama_cabang }}
                            </td>
                            <td class="py-1.5 px-3 text-slate-700 dark:text-slate-200">
                                {{ $nama_bulan[$d->bulan] }}
                            </td>
                            <td class="py-1.5 px-3 font-mono-num text-slate-700 dark:text-slate-200">
                                {{ $d->tahun }}
                            </td>
                            <td class="py-1.5 px-3 font-mono-num text-slate-600 dark:text-slate-400">
                                {{ date('d-m-Y', strtotime($d->tanggal)) }}
                            </td>
                            <td class="py-1.5 px-3 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    @can('sapiutangsales.show')
                                        <a href="#"
                                            class="showSaldoawal w-6 h-6 rounded-md bg-blue-50 text-blue-600 hover:bg-blue-100 dark:bg-blue-950/50 dark:hover:bg-blue-900/50 dark:text-blue-400 flex items-center justify-center transition cursor-pointer"
                                            title="Lihat Detail"
                                            kode_saldo_awal="{{ Crypt::encrypt($d->kode_saldo_awal) }}">
                                            <i data-lucide="file-text" class="w-3 h-3"></i>
                                        </a>
                                    @endcan
                                    @can('sapiutangsales.delete')
                                        <form method="POST" name="deleteform" class="inline-flex m-0 p-0 deleteform"
                                            action="{{ route('sapiutangsales.delete', Crypt::encrypt($d->kode_saldo_awal)) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="delete-confirm w-6 h-6 rounded-md bg-rose-50 text-rose-600 hover:bg-rose-100 dark:bg-rose-950/50 dark:hover:bg-rose-900/50 dark:text-rose-400 flex items-center justify-center transition cursor-pointer"
                                                title="Hapus Data">
                                                <i data-lucide="trash-2" class="w-3 h-3"></i>
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <i data-lucide="database" class="w-8 h-8 text-slate-300 dark:text-slate-600"></i>
                                    <span class="text-xs">Belum ada data saldo awal piutang salesman.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- STATIC BACKDROP DETAIL MODAL V2 -->
<div id="modalDetailSaldo" class="fixed inset-0 z-50 hidden">
    <!-- Backdrop Gelap -->
    <div id="modalDetailBackdrop" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>

    <!-- Wrapper Dialog -->
    <div class="fixed inset-0 flex items-center justify-center p-4 overflow-y-auto">
        <div id="modalDetailDialog"
            class="relative w-full max-w-2xl bg-white dark:bg-slate-900 rounded-xl shadow-2xl border border-slate-200/80 dark:border-slate-800 transform transition-all duration-150">
            
            <!-- Modal Header Bersih (Sesuai GEMINI.md: Tanpa background solid, border halus) -->
            <div class="flex items-center justify-between px-4 py-3 border-b border-slate-200/80 dark:border-slate-800">
                <div class="flex items-center gap-2">
                    <i data-lucide="database-import" class="w-4 h-4 text-[#284c9a] dark:text-blue-400"></i>
                    <h5 id="modalDetailTitle" class="text-sm font-bold text-slate-800 dark:text-slate-100">Detail Saldo Awal Piutang Sales</h5>
                </div>
                <button type="button" id="btnCloseModalDetail"
                    class="w-7 h-7 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition cursor-pointer">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>

            <!-- Modal Body Container -->
            <div id="modalDetailContent" class="p-4">
                <!-- Rincian di-load via AJAX -->
            </div>
        </div>
    </div>
</div>

@endsection

@push('myscript')
<script>
    $(function() {
        const $modal = $("#modalDetailSaldo");
        const $dialog = $("#modalDetailDialog");
        const $content = $("#modalDetailContent");

        function openModal(title) {
            $("#modalDetailTitle").text(title || "Detail Saldo Awal Piutang Sales");
            $content.html(`
                <div class="py-12 flex flex-col items-center justify-center gap-2">
                    <div class="w-6 h-6 border-2 border-[#284c9a] border-t-transparent rounded-full animate-spin"></div>
                    <span class="text-xs text-slate-500 font-medium">Memuat data saldo awal...</span>
                </div>
            `);
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

        // Static Backdrop Behavior (Anti-Tutup Sembarangan)
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
            openModal("Detail Saldo Awal Piutang Sales");
            $.ajax({
                type: 'GET',
                url: `/sapiutangsales/${kode_saldo_awal}/show`,
                success: function(response) {
                    $content.html(response);
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }
                },
                error: function() {
                    $content.html(`
                        <div class="py-8 text-center text-rose-500 font-semibold text-xs">
                            Gagal memuat rincian saldo awal piutang sales. Silakan coba lagi.
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
                text: "Jika dihapus maka data akan hilang permanen.",
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
