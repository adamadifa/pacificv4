@extends('v2.layouts.app')
@section('titlepage', 'Kontrak Kerja')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">HRD</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Kontrak Kerja</span>
    </div>
@endsection

@section('content')
<div class="space-y-3.5 w-full">

    <!-- PAGE TITLE BAR -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Kontrak Kerja</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Mengelola data dan riwayat kontrak kerja karyawan.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            @can('kontrakkerja.edit')
                <button type="button" id="btnDeactivateOld"
                    class="h-[34px] px-3 rounded-lg bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold shadow-xs transition flex items-center gap-1.5 cursor-pointer">
                    <i data-lucide="ban" class="w-3.5 h-3.5"></i>
                    <span>Nonaktifkan Kontrak Lama</span>
                </button>
            @endcan
            @can('kontrakkerja.create')
                <button type="button" id="btnCreateKontrak"
                    class="h-[34px] px-3.5 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold shadow-xs transition flex items-center gap-1.5 cursor-pointer">
                    <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                    <span>Buat Kontrak</span>
                </button>
            @endcan
        </div>
    </div>

    <!-- FILTER BAR (COMPACT NO-LABEL V2, FULL WIDTH) -->
    <form action="{{ route('kontrakkerja.index') }}" method="GET"
        class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-3 shadow-2xs">
        <div class="grid grid-cols-1 sm:grid-cols-12 gap-2 items-center">
            <!-- Dari -->
            <div class="sm:col-span-3 relative">
                <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                </div>
                <input type="text" name="dari" id="dari_search" value="{{ request('dari') }}"
                    placeholder="Dari Tanggal" readonly
                    class="flatpickr-date w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition cursor-pointer">
            </div>

            <!-- Sampai -->
            <div class="sm:col-span-3 relative">
                <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                </div>
                <input type="text" name="sampai" id="sampai_search" value="{{ request('sampai') }}"
                    placeholder="Sampai Tanggal" readonly
                    class="flatpickr-date w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition cursor-pointer">
            </div>

            <!-- Nama Karyawan -->
            <div class="sm:col-span-5 relative">
                <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="user" class="w-4 h-4"></i>
                </div>
                <input type="text" name="nama_karyawan_search" id="nama_karyawan_search" value="{{ request('nama_karyawan_search') }}"
                    placeholder="Cari Nama Karyawan..."
                    class="w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
            </div>

            <!-- Cari -->
            <div class="sm:col-span-1">
                <button type="submit"
                    class="w-full h-[36px] px-3 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold shadow-xs transition flex items-center justify-center gap-1 cursor-pointer">
                    <i data-lucide="search" class="w-3.5 h-3.5"></i>
                    <span class="sm:hidden lg:inline">Cari</span>
                </button>
            </div>
        </div>
    </form>

    <!-- LIST CONTRACT CARDS (FULL WIDTH) -->
    <div class="space-y-2.5">
        @forelse ($kontrak as $d)
            @php
                $lamabulan = calculateMonthsKontrak($d->dari, $d->sampai);
                $is_active = $d->status_kontrak === '1';
                $accentBorder = $is_active ? 'border-l-4 border-l-[#284c9a]' : 'border-l-4 border-l-rose-500';
            @endphp

            <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 {{ $accentBorder }} bg-white dark:bg-slate-900 p-3.5 shadow-2xs hover:shadow-md transition-all duration-150">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-3 items-center">
                    <!-- Kolom 1: Avatar, Nama, NIK, Status -->
                    <div class="lg:col-span-3 lg:border-r lg:border-slate-100 dark:lg:border-slate-800 lg:pr-3 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full overflow-hidden shrink-0 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center justify-center">
                            @if (!empty($d->foto) && Storage::disk('public')->exists('/karyawan/' . ($d instanceof App\Models\Karyawan ? $d->getRawOriginal('foto') : $d->foto)))
                                <img src="{{ getfotoKaryawan($d->foto) }}" alt="Avatar" class="w-full h-full object-cover">
                            @else
                                <i data-lucide="user" class="w-5 h-5 text-slate-400"></i>
                            @endif
                        </div>
                        <div class="min-w-0">
                            <h6 class="text-xs font-bold text-slate-800 dark:text-white truncate">{{ textUpperCase($d->nama_karyawan) }}</h6>
                            <div class="flex items-center gap-1.5 mt-0.5">
                                <span class="text-[11px] font-mono-num text-slate-400">{{ $d->nik }}</span>
                                @if ($is_active)
                                    <span class="px-1.5 py-0.5 rounded bg-blue-50 dark:bg-blue-950/60 text-[#284c9a] dark:text-blue-300 text-[10px] font-bold">Aktif</span>
                                @else
                                    <span class="px-1.5 py-0.5 rounded bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 text-[10px] font-bold">Tidak Aktif</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Kolom 2: No. Kontrak & Tanggal -->
                    <div class="lg:col-span-3 lg:border-r lg:border-slate-100 dark:lg:border-slate-800 lg:px-3">
                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">No. Kontrak & Tanggal</p>
                        <p class="text-xs font-mono-num font-bold text-[#284c9a] dark:text-blue-400 mt-0.5">{{ $d->no_kontrak }}</p>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">{{ formatIndo($d->tanggal) }}</p>
                    </div>

                    <!-- Kolom 3: Jabatan & Penempatan -->
                    <div class="lg:col-span-3 lg:border-r lg:border-slate-100 dark:lg:border-slate-800 lg:px-3">
                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Jabatan & Penempatan</p>
                        <p class="text-xs font-bold text-slate-800 dark:text-slate-100 mt-0.5 truncate">{{ !empty($d->alias_jabatan) ? $d->alias_jabatan : $d->nama_jabatan }}</p>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">{{ $d->kode_dept }} | {{ textUpperCase($d->nama_cabang) }}</p>
                    </div>

                    <!-- Kolom 4: Masa Kontrak & Aksi -->
                    <div class="lg:col-span-3 lg:pl-3 flex items-center justify-between gap-2">
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Masa Kontrak</p>
                            <p class="text-xs font-bold text-slate-800 dark:text-slate-100 mt-0.5 flex items-center gap-1">
                                <span>{{ formatIndo($d->dari) }} -</span>
                                @if ($d->masa_kontrak == 'KT' || $d->dari == $d->sampai)
                                    <i data-lucide="infinity" class="w-4 h-4 text-rose-500 inline-block"></i>
                                @else
                                    <span>{{ formatIndo($d->sampai) }}</span>
                                @endif
                            </p>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">{{ $lamabulan }} Bulan</p>
                        </div>

                        <div class="flex items-center gap-1 shrink-0">
                            @can('kontrakkerja.edit')
                                <button type="button" no_kontrak="{{ Crypt::encrypt($d->no_kontrak) }}"
                                    class="btnEdit w-7 h-7 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-600 dark:bg-emerald-950/50 dark:hover:bg-emerald-900/60 dark:text-emerald-400 flex items-center justify-center transition cursor-pointer"
                                    title="Edit">
                                    <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
                                </button>
                            @endcan
                            @can('kontrakkerja.show')
                                <a href="{{ route('kontrakkerja.cetak', Crypt::encrypt($d->no_kontrak)) }}" target="_blank"
                                    class="w-7 h-7 rounded-lg bg-blue-50 hover:bg-blue-100 text-[#284c9a] dark:bg-blue-950/50 dark:hover:bg-blue-900/60 dark:text-blue-400 flex items-center justify-center transition"
                                    title="Cetak">
                                    <i data-lucide="printer" class="w-3.5 h-3.5"></i>
                                </a>
                            @endcan
                            @can('kontrakkerja.delete')
                                <form method="POST" name="deleteform" class="inline-flex m-0 p-0 deleteform"
                                    action="{{ route('kontrakkerja.delete', Crypt::encrypt($d->no_kontrak)) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="delete-confirm w-7 h-7 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 dark:bg-rose-950/50 dark:hover:bg-rose-900/60 dark:text-rose-400 flex items-center justify-center transition cursor-pointer"
                                        title="Hapus">
                                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                    </button>
                                </form>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-10 text-center">
                <div class="flex flex-col items-center justify-center gap-2 text-slate-400">
                    <i data-lucide="file-x" class="w-10 h-10 text-slate-300 dark:text-slate-600"></i>
                    <h5 class="text-sm font-bold text-slate-700 dark:text-slate-200">Data Tidak Ditemukan</h5>
                    <p class="text-xs text-slate-400">Data kontrak kerja belum tersedia atau tidak sesuai filter.</p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- PAGINATION -->
    @if ($kontrak->hasPages())
        <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 px-4 py-3 shadow-2xs">
            {{ $kontrak->links('v2.components.pagination') }}
        </div>
    @endif
</div>

<!-- ================= MODAL KONTRAK KERJA (STATIC BACKDROP) ================= -->
<div id="modalKontrak" class="fixed inset-0 z-50 hidden">
    <div id="modalKontrakBackdrop" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4 overflow-y-auto">
        <div id="modalKontrakDialog"
            class="relative w-full max-w-xl bg-white dark:bg-slate-900 rounded-xl shadow-2xl border border-slate-200/80 dark:border-slate-800 transform transition-all duration-150 my-auto">
            <!-- Modal Header Bersih -->
            <div class="flex items-center justify-between px-4 py-3 bg-white dark:bg-slate-900 border-b border-slate-200/80 dark:border-slate-800 rounded-t-xl">
                <div class="flex items-center gap-2">
                    <i data-lucide="file-text" class="w-4 h-4 text-[#284c9a] dark:text-blue-400"></i>
                    <h5 id="modalKontrakTitle" class="text-sm font-bold text-slate-800 dark:text-slate-100">Buat Kontrak</h5>
                </div>
                <button type="button" id="btnCloseModalKontrak"
                    class="w-7 h-7 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition cursor-pointer">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            <!-- Modal Body -->
            <div id="loadmodalKontrak" class="p-4 sm:p-5 max-h-[85vh] overflow-y-auto"></div>
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

        const $modal = $('#modalKontrak');
        const $dialog = $('#modalKontrakDialog');
        const $backdrop = $('#modalKontrakBackdrop');
        const $title = $('#modalKontrakTitle');
        const $content = $('#loadmodalKontrak');

        function openModal() {
            $modal.removeClass('hidden');
            $('body').addClass('overflow-hidden');
        }

        function closeModal() {
            $modal.addClass('hidden');
            $('body').removeClass('overflow-hidden');
            $content.html('');
        }

        window.closeModalKontrak = closeModal;

        $('#btnCloseModalKontrak').on('click', closeModal);

        $backdrop.on('click', function(e) {
            e.stopPropagation();
            $dialog.addClass('scale-[1.015]');
            setTimeout(() => $dialog.removeClass('scale-[1.015]'), 150);
        });

        function showLoading() {
            $content.html(`
                <div class="py-12 flex flex-col items-center justify-center gap-2">
                    <svg class="animate-spin h-6 w-6 text-[#284c9a]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-xs font-semibold text-slate-500">Memuat formulir...</span>
                </div>
            `);
        }

        $('#btnCreateKontrak').on('click', function(e) {
            e.preventDefault();
            $title.text('Buat Kontrak');
            openModal();
            showLoading();
            $content.load('/kontrakkerja/create', function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });

        $(document).on('click', '.btnEdit', function(e) {
            e.preventDefault();
            const no_kontrak = $(this).attr('no_kontrak');
            $title.text('Edit Kontrak');
            openModal();
            showLoading();
            $content.load(`/kontrakkerja/${no_kontrak}/edit`, function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });

        $('#btnDeactivateOld').on('click', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Apakah Anda Yakin?',
                text: 'Semua kontrak lama milik karyawan yang sudah memiliki kontrak lebih baru akan dinonaktifkan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#284c9a',
                cancelButtonColor: '#e11d48',
                confirmButtonText: 'Ya, Nonaktifkan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Memproses...',
                        text: 'Mohon tunggu sebentar',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    $.ajax({
                        url: "{{ route('kontrakkerja.deactivate-old') }}",
                        type: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire('Berhasil!', response.message, 'success').then(() => {
                                    window.location.reload();
                                });
                            } else {
                                Swal.fire('Gagal!', response.message, 'error');
                            }
                        },
                        error: function() {
                            Swal.fire('Error!', 'Terjadi kesalahan pada server.', 'error');
                        }
                    });
                }
            });
        });
    });
</script>
@endpush
