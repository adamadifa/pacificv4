@extends('layouts.app')
@section('titlepage', 'OMAN Cabang')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Home</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-slate-400">Marketing</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-slate-400">Order Management</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">OMAN Cabang</span>
    </div>
@endsection

@section('content')
<div class="space-y-3.5 w-full">

    <!-- PAGE TITLE BAR & ACTION -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Order Management (OMAN Cabang)</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Kelola perencanaan dan estimasi kuantiti permintaan order barang cabang per minggu.</p>
        </div>

        @can('omancabang.create')
            <button type="button" id="btnCreate"
                class="inline-flex items-center justify-center gap-1.5 h-[34px] px-3.5 rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs font-bold transition shadow-xs cursor-pointer shrink-0">
                <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                <span>Buat OMAN Cabang</span>
            </button>
        @endcan
    </div>

    <!-- NAVIGATION TABS ORDER MANAGEMENT -->
    @include('v2.layouts.navigation_oman')

    <!-- FILTER SECTION (No-Label, Icon Prefix, h-[34px], rounded-lg) -->
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-3 shadow-2xs transition-colors duration-200">
        <form action="{{ url()->current() }}" method="GET" id="formSearch" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-2 items-center">
            
            <!-- Bulan (Col-span 5) -->
            <div class="lg:col-span-5 relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                </div>
                <select name="bulan_search" id="bulan_search"
                    class="w-full h-[34px] pl-8 pr-8 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition appearance-none cursor-pointer">
                    <option value="">Semua Bulan</option>
                    @foreach ($list_bulan as $b)
                        <option value="{{ $b['kode_bulan'] }}" {{ Request('bulan_search') == $b['kode_bulan'] ? 'selected' : '' }}>
                            {{ $b['nama_bulan'] }}
                        </option>
                    @endforeach
                </select>
                <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                    <i data-lucide="chevron-down" class="w-3 h-3"></i>
                </div>
            </div>

            <!-- Tahun (Col-span 5) -->
            <div class="lg:col-span-5 relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar-days" class="w-3.5 h-3.5"></i>
                </div>
                <select name="tahun_search" id="tahun_search"
                    class="w-full h-[34px] pl-8 pr-8 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition appearance-none cursor-pointer">
                    <option value="">Semua Tahun</option>
                    @for ($t = $start_year; $t <= date('Y') + 1; $t++)
                        <option value="{{ $t }}"
                            {{ !empty(Request('tahun_search')) ? (Request('tahun_search') == $t ? 'selected' : '') : (date('Y') == $t ? 'selected' : '') }}>
                            {{ $t }}
                        </option>
                    @endfor
                </select>
                <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                    <i data-lucide="chevron-down" class="w-3 h-3"></i>
                </div>
            </div>

            <!-- Tombol Cari (Col-span 2) -->
            <div class="lg:col-span-2 flex items-center">
                <button type="submit"
                    class="w-full h-[34px] inline-flex items-center justify-center gap-1.5 px-3 rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs font-bold transition shadow-xs cursor-pointer">
                    <i data-lucide="search" class="w-3.5 h-3.5"></i>
                    <span>Cari</span>
                </button>
            </div>

        </form>
    </div>

    <!-- DATA TABLE SECTION -->
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-2xs overflow-hidden transition-colors duration-200">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <!-- Strip Judul Tabel (Colspan Penuh = 6) -->
                    <tr class="bg-[#284c9a] border-b border-[#1e3a78] text-white">
                        <th colspan="6" class="px-3.5 py-2">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="archive" class="w-4 h-4 text-white/80"></i>
                                    <span class="text-xs font-bold text-white">Data OMAN Cabang</span>
                                </div>
                                <span class="text-[11px] text-white/80 font-mono-num font-semibold">
                                    Total: {{ method_exists($oman_cabang, 'total') ? number_format($oman_cabang->total(), 0, ',', '.') : number_format($oman_cabang->count(), 0, ',', '.') }}
                                </span>
                            </div>
                        </th>
                    </tr>
                    <!-- Header Kolom Pacific Navy Pekat (#1e3a78) -->
                    <tr class="bg-[#1e3a78] text-white text-[11px] font-bold uppercase tracking-wider">
                        <th class="py-2 px-3">Kode OMAN</th>
                        <th class="py-2 px-3">Cabang</th>
                        <th class="py-2 px-3">Bulan</th>
                        <th class="py-2 px-3">Tahun</th>
                        <th class="py-2 px-3">Status</th>
                        <th class="py-2 px-3 text-center w-24">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 text-xs text-slate-700 dark:text-slate-300">
                    @forelse ($oman_cabang as $d)
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition-colors">
                            
                            <!-- Kode OMAN -->
                            <td class="py-1.5 px-3">
                                <span class="font-bold font-mono-num text-[#284c9a] dark:text-blue-400">
                                    {{ $d->kode_oman }}
                                </span>
                            </td>

                            <!-- Cabang -->
                            <td class="py-1.5 px-3">
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-blue-50 dark:bg-blue-950/60 text-[#284c9a] dark:text-blue-400 font-bold text-[11px] border border-blue-200/60 dark:border-blue-900/50">
                                    <i data-lucide="building-2" class="w-3 h-3"></i>
                                    <span>{{ textUpperCase($d->nama_cabang ?? $d->kode_cabang) }}</span>
                                </span>
                            </td>

                            <!-- Bulan -->
                            <td class="py-1.5 px-3 font-semibold text-slate-800 dark:text-slate-100">
                                {{ $nama_bulan[$d->bulan] ?? $d->bulan }}
                            </td>

                            <!-- Tahun -->
                            <td class="py-1.5 px-3 font-mono-num font-medium">
                                {{ $d->tahun }}
                            </td>

                            <!-- Status -->
                            <td class="py-1.5 px-3">
                                @if ($d->status_oman_cabang === '1')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10.5px] font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/50">
                                        <i data-lucide="check" class="w-3 h-3"></i>
                                        <span>Sudah di Proses</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10.5px] font-bold bg-amber-50 text-amber-700 dark:bg-amber-950/60 dark:text-amber-400 border border-amber-200 dark:border-amber-800/50">
                                        <i data-lucide="clock" class="w-3 h-3"></i>
                                        <span>Belum di Proses</span>
                                    </span>
                                @endif
                            </td>

                            <!-- Aksi Kompak (24x24 px) -->
                            <td class="py-1.5 px-3 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    
                                    {{-- Edit --}}
                                    @can('omancabang.edit')
                                        @if ($d->status_oman_cabang === '0')
                                            <button type="button" kode_oman="{{ Crypt::encrypt($d->kode_oman) }}" title="Edit OMAN Cabang"
                                                class="btnEdit w-6 h-6 rounded-md flex items-center justify-center text-amber-600 hover:text-amber-700 hover:bg-amber-50 dark:hover:bg-amber-950/30 transition cursor-pointer">
                                                <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                                            </button>
                                        @endif
                                    @endcan

                                    {{-- Detail --}}
                                    @can('omancabang.show')
                                        <button type="button" kode_oman="{{ Crypt::encrypt($d->kode_oman) }}" title="Lihat Rincian OMAN"
                                            class="btnShow w-6 h-6 rounded-md flex items-center justify-center text-sky-600 hover:text-sky-700 hover:bg-sky-50 dark:hover:bg-sky-950/30 transition cursor-pointer">
                                            <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                        </button>
                                    @endcan

                                    {{-- Hapus --}}
                                    @can('omancabang.delete')
                                        @if ($d->status_oman_cabang === '0')
                                            <form method="POST" action="{{ route('omancabang.delete', Crypt::encrypt($d->kode_oman)) }}" class="inline-flex m-0 p-0 deleteform">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" title="Hapus OMAN Cabang"
                                                    class="w-6 h-6 rounded-md flex items-center justify-center text-rose-600 hover:text-rose-700 hover:bg-rose-50 dark:hover:bg-rose-950/30 transition cursor-pointer">
                                                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                                </button>
                                            </form>
                                        @endif
                                    @endcan

                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center gap-1.5">
                                    <i data-lucide="inbox" class="w-6 h-6 text-slate-300"></i>
                                    <span class="text-xs font-medium">Belum ada data OMAN cabang</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- PAGINATION V2 STANDAR -->
        @if ($oman_cabang->hasPages())
            <div class="px-4 py-3 border-t border-slate-100 dark:border-slate-800/80 bg-slate-50/50 dark:bg-slate-900/50">
                {{ $oman_cabang->links('v2.components.pagination') }}
            </div>
        @endif
    </div>

</div>

<!-- UNIFIED MODAL DIALOG V2 (STATIC BACKDROP & BOUNCE LOCK) -->
<div id="modalOmanCabang" class="fixed inset-0 z-50 hidden transition-opacity duration-200">
    <!-- Backdrop Blur with dark overlay -->
    <div class="fixed inset-0 bg-slate-900/60 dark:bg-slate-950/80 backdrop-blur-xs modal-backdrop-v2"></div>
    
    <div class="modal-wrapper fixed inset-0 z-20 overflow-y-auto p-3 sm:p-4 flex items-center justify-center">
        <div id="modalOmanCabangDialog" class="modal-dialog relative w-full !max-w-4xl bg-white dark:bg-slate-900 rounded-xl shadow-2xl border border-slate-200/80 dark:border-slate-800 transform transition-all duration-200 flex flex-col max-h-[92vh]">
            
            <!-- Modal Header (Clean without solid bg) -->
            <div class="flex items-center justify-between px-4 py-2.5 border-b border-slate-200/80 dark:border-slate-800 shrink-0">
                <div class="flex items-center gap-2.5">
                    <div id="modalHeaderIconContainer" class="w-7 h-7 rounded-lg bg-blue-50 dark:bg-blue-950/50 text-[#284c9a] dark:text-blue-400 flex items-center justify-center">
                        <i id="modalHeaderIcon" data-lucide="building-2" class="w-3.5 h-3.5"></i>
                    </div>
                    <div>
                        <h4 id="modalOmanCabangTitle" class="text-xs font-bold text-slate-800 dark:text-slate-100">OMAN Cabang</h4>
                        <p id="modalOmanCabangSubtitle" class="text-[10px] text-slate-400">Pengelolaan data order management cabang</p>
                    </div>
                </div>
                <button type="button" class="btn-close-modal w-7 h-7 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition cursor-pointer">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>

            <!-- Modal Body (Loaded via AJAX) -->
            <div id="modalOmanCabangContent" class="p-3 sm:p-3.5 overflow-y-auto">
                <div class="flex flex-col items-center justify-center py-10 gap-2 text-slate-400">
                    <div class="w-7 h-7 border-2 border-brand-500 border-t-transparent rounded-full animate-spin"></div>
                    <span class="text-xs font-medium">Memuat data...</span>
                </div>
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

        // Modal Elements
        const $modal = $('#modalOmanCabang');
        const $modalDialog = $('#modalOmanCabangDialog');
        const $modalContent = $('#modalOmanCabangContent');
        const $modalTitle = $('#modalOmanCabangTitle');
        const $modalSubtitle = $('#modalOmanCabangSubtitle');

        function openModal(title, subtitle, sizeClass, iconName) {
            $modalTitle.text(title);
            $modalSubtitle.text(subtitle);
            $modalDialog.removeClass(function(index, className) {
                return (className.match(/(^|\s)\S*max-w-\S+/g) || []).join(' ');
            });
            $modalDialog.addClass(sizeClass || '!max-w-4xl');
            
            if (iconName) {
                $('#modalHeaderIcon').attr('data-lucide', iconName);
            }
            
            $modal.removeClass('hidden');
            $('body').addClass('overflow-hidden');
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        }

        function closeModal() {
            $modal.addClass('hidden');
            $('body').removeClass('overflow-hidden');
            $modalContent.html(`
                <div class="flex flex-col items-center justify-center py-10 gap-2 text-slate-400">
                    <div class="w-7 h-7 border-2 border-brand-500 border-t-transparent rounded-full animate-spin"></div>
                    <span class="text-xs font-medium">Memuat data...</span>
                </div>
            `);
        }

        function triggerLockAnimation() {
            $modalDialog.addClass('scale-[1.015] shadow-2xl');
            setTimeout(function() {
                $modalDialog.removeClass('scale-[1.015] shadow-2xl');
            }, 150);
        }

        // Backdrop Click Lock (Static Backdrop)
        $modal.on('click', function(e) {
            if ($(e.target).is($modal) || $(e.target).hasClass('modal-wrapper') || $(e.target).hasClass('modal-backdrop-v2')) {
                triggerLockAnimation();
            }
        });

        // Escape Key Lock
        $(document).on('keydown', function(e) {
            if (!$modal.hasClass('hidden') && e.key === 'Escape') {
                triggerLockAnimation();
            }
        });

        // Close explicit
        $modal.on('click', '.btn-close-modal', function(e) {
            e.preventDefault();
            closeModal();
        });

        // Click Buat OMAN Cabang (Create)
        $('#btnCreate').on('click', function(e) {
            e.preventDefault();
            openModal('Buat OMAN Cabang', 'Input rencana kuantiti permintaan order cabang per minggu', '!max-w-4xl', 'plus-circle');
            $.ajax({
                url: "{{ route('omancabang.create') }}",
                type: 'GET',
                success: function(response) {
                    $modalContent.html(response);
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }
                },
                error: function() {
                    $modalContent.html(`
                        <div class="py-10 text-center text-rose-500">
                            <i data-lucide="alert-triangle" class="w-8 h-8 mx-auto mb-2 text-rose-500"></i>
                            <p class="text-xs font-bold">Gagal memuat form tambah OMAN cabang.</p>
                        </div>
                    `);
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }
                }
            });
        });

        // Click Edit OMAN Cabang
        $('.btnEdit').on('click', function(e) {
            e.preventDefault();
            const kodeOman = $(this).attr('kode_oman');
            openModal('Edit OMAN Cabang', 'Perbarui estimasi kuantiti permintaan order cabang', '!max-w-4xl', 'edit-3');
            $.ajax({
                url: `/omancabang/${kodeOman}/edit`,
                type: 'GET',
                success: function(response) {
                    $modalContent.html(response);
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }
                },
                error: function() {
                    $modalContent.html(`
                        <div class="py-10 text-center text-rose-500">
                            <i data-lucide="alert-triangle" class="w-8 h-8 mx-auto mb-2 text-rose-500"></i>
                            <p class="text-xs font-bold">Gagal memuat form edit OMAN cabang.</p>
                        </div>
                    `);
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }
                }
            });
        });

        // Click Detail OMAN Cabang (Show)
        $('.btnShow').on('click', function(e) {
            e.preventDefault();
            const kodeOman = $(this).attr('kode_oman');
            openModal('Detail OMAN Cabang', 'Rincian data permintaan order cabang per minggu', '!max-w-4xl', 'eye');
            $.ajax({
                url: `/omancabang/${kodeOman}/show`,
                type: 'GET',
                success: function(response) {
                    $modalContent.html(response);
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }
                },
                error: function() {
                    $modalContent.html(`
                        <div class="py-10 text-center text-rose-500">
                            <i data-lucide="alert-triangle" class="w-8 h-8 mx-auto mb-2 text-rose-500"></i>
                            <p class="text-xs font-bold">Gagal memuat detail OMAN cabang.</p>
                        </div>
                    `);
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }
                }
            });
        });

        // Delete confirmation OMAN Cabang
        $(document).on('submit', '.deleteform', function(e) {
            e.preventDefault();
            const form = this;
            Swal.fire({
                title: 'Apakah Anda Yakin?',
                text: 'Data OMAN cabang ini akan dihapus secara permanen!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Hapus Data!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });

    });
</script>
@endpush
