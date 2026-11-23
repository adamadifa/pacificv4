@extends('v2.layouts.app')
@section('titlepage', 'Retur')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Home</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-slate-400">Marketing</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Retur</span>
    </div>
@endsection

@section('content')
<div class="space-y-3.5 w-full">

    <!-- PAGE TITLE BAR & ACTION BUTTONS -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Data Retur</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Kelola data transaksi retur barang dan penggantian barang.</p>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            @can('retur.create')
                <a href="{{ route('retur.create') }}" id="btnCreate"
                    class="inline-flex items-center justify-center gap-1.5 h-[34px] px-3.5 rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs font-bold transition shadow-xs cursor-pointer shrink-0">
                    <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                    <span>Input Retur</span>
                </a>
            @endcan
        </div>
    </div>
    <!-- FILTER SECTION (NO-LABEL, KOMPAK ~36px) -->
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-3 sm:p-3.5 shadow-2xs">
        <form action="{{ route('retur.index') }}" method="GET" id="formSearch" class="space-y-2.5">
            
            <!-- Baris 1: Filter Tanggal & Relasi Cabang/Salesman -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-2 sm:gap-2.5">
                <!-- Dari Tanggal -->
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400">
                        <i data-lucide="calendar" class="w-4 h-4"></i>
                    </div>
                    <input type="text" name="dari" id="dari" value="{{ Request('dari') }}"
                        class="flatpickr-date w-full h-[36px] pl-9 pr-3 text-xs bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-lg focus:outline-hidden focus:ring-1 focus:ring-[#284c9a] dark:focus:ring-blue-500 placeholder:text-slate-400"
                        placeholder="Dari Tanggal" readonly>
                </div>

                <!-- Sampai Tanggal -->
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400">
                        <i data-lucide="calendar" class="w-4 h-4"></i>
                    </div>
                    <input type="text" name="sampai" id="sampai" value="{{ Request('sampai') }}"
                        class="flatpickr-date w-full h-[36px] pl-9 pr-3 text-xs bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-lg focus:outline-hidden focus:ring-1 focus:ring-[#284c9a] dark:focus:ring-blue-500 placeholder:text-slate-400"
                        placeholder="Sampai Tanggal" readonly>
                </div>

                <!-- Cabang (Role Check) -->
                @hasanyrole($roles_show_cabang)
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                            <i data-lucide="building-2" class="w-4 h-4"></i>
                        </div>
                        <select name="kode_cabang_search" id="kode_cabang_search" class="select2Kodecabangsearch w-full h-[36px] text-xs">
                            <option value="">Semua Cabang</option>
                            @foreach ($cabang as $c)
                                <option value="{{ $c->kode_cabang }}" {{ Request('kode_cabang_search') == $c->kode_cabang ? 'selected' : '' }}>
                                    {{ strtoupper($c->nama_cabang) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endrole

                <!-- Salesman -->
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                        <i data-lucide="user" class="w-4 h-4"></i>
                    </div>
                    <select name="kode_salesman_search" id="kode_salesman_search" class="select2Kodesalesmansearch w-full h-[36px] text-xs">
                        <option value="">Semua Salesman</option>
                    </select>
                </div>
            </div>

            <!-- Baris 2: Pencarian Spesifik & Tombol Aksi -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-2 sm:gap-2.5 items-center">
                <!-- No. Faktur -->
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400">
                        <i data-lucide="barcode" class="w-4 h-4"></i>
                    </div>
                    <input type="text" name="no_faktur_search" id="no_faktur_search" value="{{ Request('no_faktur_search') }}"
                        class="w-full h-[36px] pl-9 pr-3 text-xs bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-lg focus:outline-hidden focus:ring-1 focus:ring-[#284c9a] dark:focus:ring-blue-500 placeholder:text-slate-400"
                        placeholder="No. Faktur">
                </div>

                <!-- Kode Pelanggan -->
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400">
                        <i data-lucide="qr-code" class="w-4 h-4"></i>
                    </div>
                    <input type="text" name="kode_pelanggan_search" id="kode_pelanggan_search" value="{{ Request('kode_pelanggan_search') }}"
                        class="w-full h-[36px] pl-9 pr-3 text-xs bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-lg focus:outline-hidden focus:ring-1 focus:ring-[#284c9a] dark:focus:ring-blue-500 placeholder:text-slate-400"
                        placeholder="Kode Pelanggan">
                </div>

                <!-- Nama Pelanggan -->
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400">
                        <i data-lucide="users" class="w-4 h-4"></i>
                    </div>
                    <input type="text" name="nama_pelanggan_search" id="nama_pelanggan_search" value="{{ Request('nama_pelanggan_search') }}"
                        class="w-full h-[36px] pl-9 pr-3 text-xs bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-lg focus:outline-hidden focus:ring-1 focus:ring-[#284c9a] dark:focus:ring-blue-500 placeholder:text-slate-400"
                        placeholder="Nama Pelanggan">
                </div>

                <!-- Tombol Submit & Reset -->
                <div class="flex items-center gap-1.5">
                    <button type="submit"
                        class="flex-1 h-[36px] px-3 rounded-lg bg-[#284c9a] hover:bg-blue-800 text-white text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-2xs cursor-pointer">
                        <i data-lucide="search" class="w-3.5 h-3.5"></i>
                        <span>Cari Data</span>
                    </button>
                    @if (Request('dari') || Request('sampai') || Request('kode_cabang_search') || Request('kode_salesman_search') || Request('no_faktur_search') || Request('kode_pelanggan_search') || Request('nama_pelanggan_search'))
                        <a href="{{ route('retur.index') }}"
                            class="h-[36px] w-[36px] rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 flex items-center justify-center transition shadow-2xs"
                            title="Reset Filter">
                            <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                        </a>
                    @endif
                </div>
            </div>

        </form>
    </div>

    <!-- DATA TABLE SECTION (KOMPAK ~36px, FROZEN RETUR & AKSI) -->
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 overflow-hidden shadow-2xs">
        <div class="overflow-x-auto min-h-[280px]">
            <table class="w-full text-xs text-left border-separate border-spacing-0 whitespace-nowrap">
                <thead>
                    <!-- Baris 1: Strip Header Pacific Navy -->
                    <tr class="bg-[#284c9a] text-white">
                        <th colspan="9" class="px-3.5 py-2 bg-[#284c9a] text-white border-0">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-1.5">
                                    <i data-lucide="receipt-refund" class="w-3.5 h-3.5 text-blue-200"></i>
                                    <span class="text-xs font-bold text-white">Data Transaksi Retur</span>
                                </div>
                                <span class="text-[10.5px] text-white/70 font-mono-num">
                                    Total: {{ method_exists($retur, 'total') ? number_format($retur->total(), 0, ',', '.') : number_format($retur->count(), 0, ',', '.') }} Data
                                </span>
                            </div>
                        </th>
                    </tr>

                    <!-- Baris 2: Header Kolom -->
                    <tr class="bg-[#1e3a78] text-white text-[11px]">
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 sticky left-0 z-20 bg-[#1e3a78] shadow-[2px_0_5px_-2px_rgba(0,0,0,0.3)]">NO. RETUR</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-blue-900">TANGGAL</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-blue-900">NO. FAKTUR</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-blue-900">NAMA PELANGGAN</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-blue-900">CABANG</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-blue-900">SALESMAN</th>
                        <th class="py-2 px-3 text-center font-bold uppercase tracking-wider text-white/90 border-b border-blue-900">JENIS RETUR</th>
                        <th class="py-2 px-3 text-right font-bold uppercase tracking-wider text-white/90 border-b border-blue-900">TOTAL</th>
                        <th class="py-2 px-3 text-center font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 sticky right-0 z-20 bg-[#1e3a78] shadow-[-2px_0_5px_-2px_rgba(0,0,0,0.3)]">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80">
                    @forelse ($retur as $d)
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition">
                            
                            <!-- 1. No. Retur (Frozen Left) -->
                            <td class="py-1.5 px-3 font-bold font-mono-num text-slate-800 dark:text-slate-100 sticky left-0 z-10 bg-white dark:bg-slate-900 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.08)] dark:shadow-[2px_0_5px_-2px_rgba(0,0,0,0.4)]">
                                <span class="text-[#284c9a] dark:text-blue-400">{{ $d->no_retur }}</span>
                            </td>

                            <!-- 2. Tanggal -->
                            <td class="py-1.5 px-3 font-mono-num text-slate-600 dark:text-slate-300">
                                {{ date('d-m-Y', strtotime($d->tanggal)) }}
                            </td>

                            <!-- 3. No. Faktur -->
                            <td class="py-1.5 px-3 font-mono-num font-semibold text-slate-700 dark:text-slate-200">
                                {{ $d->no_faktur }}
                            </td>

                            <!-- 4. Nama Pelanggan -->
                            <td class="py-1.5 px-3 font-semibold text-slate-800 dark:text-slate-100">
                                {{ textUpperCase($d->nama_pelanggan) }}
                            </td>

                            <!-- 5. Nama Cabang -->
                            <td class="py-1.5 px-3 text-slate-600 dark:text-slate-300">
                                {{ textUpperCase($d->nama_cabang) }}
                            </td>

                            <!-- 6. Salesman -->
                            <td class="py-1.5 px-3 text-slate-600 dark:text-slate-300">
                                {{ textUpperCase($d->nama_salesman) }}
                            </td>

                            <!-- 7. Jenis Retur -->
                            <td class="py-1.5 px-3 text-center">
                                @if ($d->jenis_retur == 'GB')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10.5px] font-bold bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800/60">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                                        <span>Ganti Barang</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10.5px] font-bold bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-300 border border-rose-200/60 dark:border-rose-800/60">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-600"></span>
                                        <span>Potong Faktur</span>
                                    </span>
                                @endif
                            </td>

                            <!-- 8. Total -->
                            <td class="py-1.5 px-3 text-right font-mono-num font-bold text-slate-800 dark:text-slate-100">
                                {{ formatRupiah($d->total_retur) }}
                            </td>

                            <!-- 9. Aksi (Frozen Right) -->
                            <td class="py-1.5 px-3 text-center sticky right-0 z-10 bg-white dark:bg-slate-900 shadow-[-2px_0_5px_-2px_rgba(0,0,0,0.08)] dark:shadow-[-2px_0_5px_-2px_rgba(0,0,0,0.4)]">
                                <div class="flex items-center justify-center gap-1">
                                    
                                    <!-- Detail Retur -->
                                    @can('retur.show')
                                        <button type="button" no_retur="{{ Crypt::encrypt($d->no_retur) }}"
                                            class="btnShow w-6 h-6 rounded-md bg-blue-50 hover:bg-blue-100 text-blue-600 dark:bg-blue-950/40 dark:text-blue-400 flex items-center justify-center transition cursor-pointer"
                                            title="Detail Retur">
                                            <i data-lucide="file-text" class="w-3 h-3"></i>
                                        </button>
                                    @endcan

                                    <!-- Hapus Retur -->
                                    @can('retur.delete')
                                        <form method="POST" name="deleteform" class="inline-flex m-0 p-0 deleteform"
                                            action="{{ route('retur.delete', Crypt::encrypt($d->no_retur)) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="delete-confirm w-6 h-6 rounded-md bg-rose-50 hover:bg-rose-100 text-rose-600 dark:bg-rose-950/40 dark:text-rose-400 flex items-center justify-center transition cursor-pointer"
                                                title="Hapus Retur">
                                                <i data-lucide="trash-2" class="w-3 h-3"></i>
                                            </button>
                                        </form>
                                    @endcan

                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-8 px-4 text-center">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <div class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-400 flex items-center justify-center">
                                        <i data-lucide="inbox" class="w-5 h-5"></i>
                                    </div>
                                    <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Tidak ada data transaksi retur yang ditemukan</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- PAGINATION CONTAINER -->
        <div class="px-4 py-3 border-t border-slate-100 dark:border-slate-800/80 bg-slate-50/50 dark:bg-slate-900/50">
            {{ $retur->links('v2.components.pagination') }}
        </div>
    </div>
</div>

<!-- MODAL DETAIL RETUR (TAILWIND V2 STATIC BACKDROP) -->
<div id="modalDetailRetur" class="fixed inset-0 z-50 hidden transition-opacity duration-200">
    <!-- Backdrop Overlay -->
    <div id="modalDetailBackdrop" class="fixed inset-0 bg-slate-950/40 backdrop-blur-xs transition-opacity"></div>
    
    <div class="fixed inset-0 z-10 overflow-y-auto p-4 sm:p-6 flex items-center justify-center min-h-full">
        <div id="modalDetailDialog" class="relative w-full max-w-4xl bg-white dark:bg-slate-900 rounded-xl shadow-2xl border border-slate-200/80 dark:border-slate-800 transition-all transform duration-150">
            
            <!-- Modal Header (Clean Standard V2) -->
            <div class="flex items-center justify-between px-4 py-3 border-b border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-lg bg-blue-50 dark:bg-blue-950/60 text-[#284c9a] dark:text-blue-400 flex items-center justify-center">
                        <i data-lucide="receipt-refund" class="w-4 h-4"></i>
                    </div>
                    <h3 id="modalDetailTitle" class="text-sm font-bold text-slate-800 dark:text-slate-100">Detail Retur</h3>
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
        // Flatpickr Setup
        if (typeof flatpickr !== 'undefined') {
            $(".flatpickr-date").flatpickr({
                dateFormat: "Y-m-d",
                allowInput: false
            });
        }

        // Floating Select2 Setup
        const select2Kodecabangsearch = $('.select2Kodecabangsearch');
        if (select2Kodecabangsearch.length) {
            select2Kodecabangsearch.select2({
                placeholder: 'Semua Cabang',
                allowClear: true,
                width: '100%'
            });
        }

        const select2Kodesalesmansearch = $('.select2Kodesalesmansearch');
        if (select2Kodesalesmansearch.length) {
            select2Kodesalesmansearch.select2({
                placeholder: 'Semua Salesman',
                allowClear: true,
                width: '100%'
            });
        }

        // Get Salesman by Cabang
        function getsalesmanbyCabang() {
            var kode_cabang = $("#kode_cabang_search").val();
            var kode_salesman = "{{ Request('kode_salesman_search') }}";
            $.ajax({
                type: 'POST',
                url: '/salesman/getsalesmanbycabang',
                data: {
                    _token: "{{ csrf_token() }}",
                    kode_cabang: kode_cabang,
                    kode_salesman: kode_salesman
                },
                cache: false,
                success: function(respond) {
                    $("#kode_salesman_search").html(respond);
                    if (select2Kodesalesmansearch.length) {
                        select2Kodesalesmansearch.trigger('change.select2');
                    }
                }
            });
        }

        getsalesmanbyCabang();
        $("#kode_cabang_search").on('change', function() {
            getsalesmanbyCabang();
        });

        // Modal Management & Static Backdrop Handlers
        const $modal = $("#modalDetailRetur");
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
                    <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Memuat Data...</span>
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

        // Open Detail Retur
        $(document).on('click', '.btnShow', function(e) {
            e.preventDefault();
            const no_retur = $(this).attr('no_retur');
            openModal("Detail Retur");
            $.ajax({
                type: 'GET',
                url: `/retur/${no_retur}/show`,
                success: function(response) {
                    $content.html(response);
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }
                },
                error: function() {
                    $content.html(`
                        <div class="py-8 text-center text-rose-500 font-semibold text-xs">
                            Gagal memuat rincian data retur. Silakan coba lagi.
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
