@extends('layouts.app')
@section('titlepage', 'OMAN (Order Management)')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Home</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-slate-400">Marketing</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-slate-400">Order Management</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">OMAN Marketing (Pusat)</span>
    </div>
@endsection

@section('content')
<div class="space-y-3.5 w-full">

    <!-- PAGE TITLE BAR & ACTION -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Order Management (OMAN Marketing / Pusat)</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Kelola konsolidasi estimasi order seluruh cabang dan buffer stok produksi.</p>
        </div>

        @can('oman.create')
            <button type="button" id="btnCreate"
                class="inline-flex items-center justify-center gap-1.5 h-[34px] px-3.5 rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs font-bold transition shadow-xs cursor-pointer shrink-0">
                <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                <span>Buat OMAN</span>
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
                    @foreach ($list_bulan as $d)
                        <option {{ Request('bulan_search') == $d['kode_bulan'] ? 'selected' : '' }}
                            value="{{ $d['kode_bulan'] }}">{{ $d['nama_bulan'] }}</option>
                    @endforeach
                </select>
                <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                    <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                </div>
            </div>

            <!-- Tahun (Col-span 5) -->
            <div class="lg:col-span-5 relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar-range" class="w-3.5 h-3.5"></i>
                </div>
                <select name="tahun_search" id="tahun_search"
                    class="w-full h-[34px] pl-8 pr-8 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition appearance-none cursor-pointer">
                    <option value="">Semua Tahun</option>
                    @for ($t = $start_year; $t <= date('Y'); $t++)
                        <option
                            @if (!empty(Request('tahun_search'))) {{ Request('tahun_search') == $t ? 'selected' : '' }}
                            @else
                                {{ date('Y') == $t ? 'selected' : '' }} 
                            @endif
                            value="{{ $t }}">{{ $t }}</option>
                    @endfor
                </select>
                <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                    <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                </div>
            </div>

            <!-- Tombol Submit / Reset (Col-span 2) -->
            <div class="lg:col-span-2 flex items-center gap-1.5">
                <button type="submit"
                    class="w-full h-[34px] px-3 rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-xs cursor-pointer">
                    <i data-lucide="search" class="w-3.5 h-3.5"></i>
                    <span>Cari</span>
                </button>
                @if (request()->hasAny(['bulan_search', 'tahun_search']))
                    <a href="{{ url()->current() }}"
                        class="h-[34px] px-2.5 rounded-lg border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 text-xs font-medium transition flex items-center justify-center cursor-pointer shrink-0"
                        title="Reset Filter">
                        <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                    </a>
                @endif
            </div>

        </form>
    </div>

    <!-- DATA TABLE SECTION (KOMPAK ~36px) -->
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 overflow-hidden shadow-2xs">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left border-collapse whitespace-nowrap">
                <thead>
                    <!-- Baris 1: Strip Header Pacific Navy -->
                    <tr class="bg-[#284c9a] text-white">
                        <th colspan="5" class="px-3.5 py-2 bg-[#284c9a] text-white border-0">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-1.5">
                                    <i data-lucide="table" class="w-3.5 h-3.5 text-blue-200"></i>
                                    <span class="text-xs font-bold text-white">Daftar OMAN Marketing (Pusat)</span>
                                </div>
                                <span class="text-[10.5px] text-white/70 font-mono-num">{{ $oman->count() }} Data Dimuat</span>
                            </div>
                        </th>
                    </tr>

                    <!-- Baris 2: Kolom Header -->
                    <tr class="bg-[#1e3a78] text-white text-[11px] border-none">
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-none w-36">KODE OMAN</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-none">BULAN</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-none w-28">TAHUN</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-none w-48 text-center">STATUS</th>
                        <th class="py-2 px-3 text-center font-bold uppercase tracking-wider text-white/90 border-none w-24">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 bg-white dark:bg-slate-900">
                    @forelse ($oman as $d)
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition duration-150">
                            <!-- Kode OMAN -->
                            <td class="py-1.5 px-3 font-mono-num font-bold text-[#284c9a] dark:text-blue-400">
                                {{ $d->kode_oman }}
                            </td>

                            <!-- Bulan -->
                            <td class="py-1.5 px-3 font-semibold text-slate-800 dark:text-slate-100">
                                {{ !empty($namabulan[$d->bulan]) ? $namabulan[$d->bulan] : $d->bulan }}
                            </td>

                            <!-- Tahun -->
                            <td class="py-1.5 px-3 font-mono-num text-slate-600 dark:text-slate-300">
                                {{ $d->tahun }}
                            </td>

                            <!-- Status -->
                            <td class="py-1.5 px-3 text-center">
                                @if ($d->status_oman === '1')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10.5px] font-bold bg-blue-50 text-blue-700 dark:bg-blue-950/60 dark:text-blue-300 border border-blue-200/80 dark:border-blue-900/60">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                        Sudah di Proses Gudang
                                    </span>
                                @elseif ($d->status_oman === '2')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10.5px] font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300 border border-emerald-200/80 dark:border-emerald-900/60">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Sudah di Proses Produksi
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10.5px] font-bold bg-amber-50 text-amber-700 dark:bg-amber-950/60 dark:text-amber-300 border border-amber-200/80 dark:border-amber-900/60">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        Belum di Proses
                                    </span>
                                @endif
                            </td>

                            <!-- Aksi (Kompak 24x24 px) -->
                            <td class="py-1.5 px-3">
                                <div class="flex items-center justify-center gap-1">
                                    <!-- Detail -->
                                    @can('oman.show')
                                        <button type="button" class="showOman w-6 h-6 rounded-md bg-blue-50 text-blue-600 hover:bg-blue-100 dark:bg-blue-950/50 dark:text-blue-400 dark:hover:bg-blue-900/50 flex items-center justify-center transition cursor-pointer"
                                            kode_oman="{{ Crypt::encrypt($d->kode_oman) }}" title="Lihat Detail OMAN">
                                            <i data-lucide="eye" class="w-3 h-3"></i>
                                        </button>
                                    @endcan

                                    <!-- Delete -->
                                    @can('oman.delete')
                                        @if ($d->status_oman === '0')
                                            <form method="POST" name="deleteform" class="inline-flex m-0 p-0 deleteform"
                                                action="{{ route('oman.delete', Crypt::encrypt($d->kode_oman)) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="delete-confirm w-6 h-6 rounded-md bg-rose-50 text-rose-600 hover:bg-rose-100 dark:bg-rose-950/50 dark:text-rose-400 dark:hover:bg-rose-900/50 flex items-center justify-center transition cursor-pointer"
                                                    title="Hapus OMAN">
                                                    <i data-lucide="trash-2" class="w-3 h-3"></i>
                                                </button>
                                            </form>
                                        @endif
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-slate-400 dark:text-slate-500">
                                <div class="flex flex-col items-center justify-center gap-1.5">
                                    <i data-lucide="inbox" class="w-7 h-7 stroke-1 text-slate-300 dark:text-slate-600"></i>
                                    <span class="text-xs font-medium">Tidak ada data OMAN Marketing ditemukan</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- PAGINATION (STANDAR V2) -->
        @if ($oman->hasPages())
            <div class="px-4 py-3 border-t border-slate-100 dark:border-slate-800/80 bg-slate-50/50 dark:bg-slate-900/50">
                {{ $oman->links('v2.components.pagination') }}
            </div>
        @endif
    </div>

</div>

<!-- MODAL UTAMA OMAN (STANDAR V2 STATIC BACKDROP & CLEAN HEADER) -->
<div id="modalOman" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modalOmanTitle" role="dialog" aria-modal="true">
    <!-- Backdrop Gelap -->
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity" id="modalOmanBackdrop"></div>

    <div class="flex min-h-full items-center justify-center p-3 sm:p-4 text-center">
        <div id="modalOmanDialog"
            class="relative w-full max-w-4xl transform rounded-2xl bg-white dark:bg-slate-900 text-left shadow-2xl transition-all duration-150 border border-slate-200/80 dark:border-slate-800 overflow-hidden">
            
            <!-- Modal Header (Clean Standard) -->
            <div class="flex items-center justify-between px-4 py-3 border-b border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-lg bg-blue-50 dark:bg-blue-950/60 text-[#284c9a] dark:text-blue-400 flex items-center justify-center">
                        <i data-lucide="clipboard-list" class="w-4 h-4" id="modalOmanIcon"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100" id="modalOmanTitle">Form OMAN Marketing</h3>
                        <p class="text-[11px] text-slate-400" id="modalOmanSubtitle">Kelola data OMAN Marketing</p>
                    </div>
                </div>
                <button type="button" class="btn-close-modal w-7 h-7 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition cursor-pointer">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>

            <!-- Modal Body Container -->
            <div class="p-4" id="modalOmanContent">
                <!-- Konten dinamis dimuat via AJAX -->
            </div>
        </div>
    </div>
</div>

<!-- MODAL SECONDARY: EDIT RINCIAN OMAN CABANG (Z-INDEX TINGGI, STATIC BACKDROP) -->
<div id="modalEditOmancabang" class="fixed inset-0 z-[100] hidden overflow-y-auto" aria-labelledby="modalEditOmancabangTitle" role="dialog" aria-modal="true">
    <!-- Backdrop Gelap -->
    <div class="fixed inset-0 bg-slate-950/70 backdrop-blur-xs transition-opacity" id="modalEditOmancabangBackdrop"></div>

    <div class="flex min-h-full items-center justify-center p-3 sm:p-4 text-center">
        <div id="modalEditOmancabangDialog"
            class="relative w-full max-w-lg transform rounded-2xl bg-white dark:bg-slate-900 text-left shadow-2xl transition-all duration-150 border border-slate-200/80 dark:border-slate-800 overflow-hidden">
            
            <!-- Modal Header (Clean Standard) -->
            <div class="flex items-center justify-between px-4 py-3 border-b border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-lg bg-blue-50 dark:bg-blue-950/60 text-[#284c9a] dark:text-blue-400 flex items-center justify-center">
                        <i data-lucide="edit-3" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100">Edit Kuantiti Cabang</h3>
                        <p class="text-[11px] text-slate-400">Sesuaikan kuantiti permintaan order per cabang</p>
                    </div>
                </div>
                <button type="button" class="btn-close-submodal w-7 h-7 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition cursor-pointer">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>

            <!-- Modal Body Container -->
            <div class="p-4" id="modalEditOmancabangContent">
                <!-- Konten dinamis dimuat via AJAX -->
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

        const $modal = $("#modalOman");
        const $dialog = $("#modalOmanDialog");
        const $content = $("#modalOmanContent");
        const $title = $("#modalOmanTitle");
        const $subtitle = $("#modalOmanSubtitle");
        const $icon = $("#modalOmanIcon");

        const $subModal = $("#modalEditOmancabang");
        const $subDialog = $("#modalEditOmancabangDialog");
        const $subContent = $("#modalEditOmancabangContent");

        function loadingSpinner() {
            return `
                <div class="py-12 flex flex-col items-center justify-center gap-2 text-slate-400 dark:text-slate-500">
                    <svg class="animate-spin h-7 w-7 text-brand-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-xs font-semibold">Memuat data...</span>
                </div>
            `;
        }

        function bounceLockModal($dlg) {
            $dlg.addClass('scale-[1.015] shadow-2xl');
            setTimeout(function() {
                $dlg.removeClass('scale-[1.015] shadow-2xl');
            }, 150);
        }

        // Static Backdrop on outside click / escape for Primary Modal
        $modal.on("click", function(e) {
            if ($(e.target).closest('#modalOmanDialog').length === 0) {
                bounceLockModal($dialog);
            }
        });

        // Static Backdrop for Secondary Modal
        $subModal.on("click", function(e) {
            if ($(e.target).closest('#modalEditOmancabangDialog').length === 0) {
                bounceLockModal($subDialog);
            }
        });

        $(document).on("keydown", function(e) {
            if (e.key === "Escape") {
                if (!$subModal.hasClass('hidden')) {
                    bounceLockModal($subDialog);
                } else if (!$modal.hasClass('hidden')) {
                    bounceLockModal($dialog);
                }
            }
        });

        function closeModal() {
            $modal.addClass("hidden");
            $content.empty();
        }

        function closeSubModal() {
            $subModal.addClass("hidden");
            $subContent.empty();
        }

        $(document).on("click", ".btn-close-modal", function() {
            closeModal();
        });

        $(document).on("click", ".btn-close-submodal", function() {
            closeSubModal();
        });

        // 1. OPEN CREATE MODAL
        $("#btnCreate").on("click", function(e) {
            e.preventDefault();
            $title.text("Buat OMAN Marketing");
            $subtitle.text("Konsolidasi permintaan order cabang & rencana kebutuhan produksi");
            $content.html(loadingSpinner());
            $modal.removeClass("hidden");

            $.get("{{ route('oman.create') }}", function(response) {
                $content.html(response);
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            }).fail(function() {
                $content.html(`
                    <div class="py-8 text-center text-rose-500 font-semibold text-xs">
                        Gagal memuat formulir pembuatan OMAN. Silakan muat ulang halaman.
                    </div>
                `);
            });
        });

        // 2. OPEN SHOW MODAL
        $(".showOman").on("click", function(e) {
            e.preventDefault();
            const kode_oman = $(this).attr("kode_oman");
            $title.text("Detail OMAN Marketing");
            $subtitle.text("Rincian alokasi mingguan seluruh produk OMAN Marketing");
            $content.html(loadingSpinner());
            $modal.removeClass("hidden");

            $.get("/oman/" + kode_oman + "/show", function(response) {
                $content.html(response);
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            }).fail(function() {
                $content.html(`
                    <div class="py-8 text-center text-rose-500 font-semibold text-xs">
                        Gagal memuat rincian OMAN. Silakan muat ulang halaman.
                    </div>
                `);
            });
        });

        // 3. SECONDARY MODAL TRIGGER: EDIT KUANTITI PER CABANG
        $(document).on('click', '.editOmancabang', function(e) {
            e.preventDefault();
            const kode_produk = $(this).attr('kode_produk');
            const bulan = $(this).attr('bulan');
            const tahun = $(this).attr('tahun');
            const minggu_ke = $(this).attr('minggu_ke');

            $subContent.html(loadingSpinner());
            $subModal.removeClass('hidden');

            $.ajax({
                type: "POST",
                url: "/omancabang/editprodukomancabang",
                data: {
                    _token: "{{ csrf_token() }}",
                    kode_produk: kode_produk,
                    bulan: bulan,
                    tahun: tahun,
                    minggu_ke: minggu_ke
                },
                cache: false,
                success: function(response) {
                    if (response.status === "warning") {
                        closeSubModal();
                        Swal.fire({
                            title: 'Perhatian!',
                            text: response.message,
                            icon: 'warning',
                            confirmButtonColor: '#284c9a'
                        });
                    } else {
                        $subContent.html(response);
                        if (typeof lucide !== 'undefined') {
                            lucide.createIcons();
                        }
                    }
                },
                error: function() {
                    $subContent.html(`
                        <div class="py-6 text-center text-rose-500 font-semibold text-xs">
                            Gagal memuat rincian produk cabang.
                        </div>
                    `);
                }
            });
        });

        // 4. SUBMIT UPDATE KUANTITI CABANG
        $(document).on('click', '#updateomanCabang', function(e) {
            e.preventDefault();
            const $btn = $(this);
            let kode_oman = [];
            let kode_produk = [];
            let minggu_ke = $('input[name=minggu_ke_edit_omancabang]').val();
            let jumlah = [];

            $('input[name="kode_oman_edit_omancabang[]"]').each(function() {
                kode_oman.push($(this).val());
            });

            $('input[name="kode_produk_edit_omancabang[]"]').each(function() {
                kode_produk.push($(this).val());
            });

            $('input[name="jumlah_edit_omancabang[]"]').each(function() {
                jumlah.push($(this).val());
            });

            $btn.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            $btn.html(`
                <svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Menyimpan...</span>
            `);

            $.ajax({
                type: "POST",
                url: "/omancabang/updateprodukomancabang",
                data: {
                    _token: "{{ csrf_token() }}",
                    kode_oman: kode_oman,
                    kode_produk: kode_produk,
                    minggu_ke: minggu_ke,
                    jumlah: jumlah
                },
                cache: false,
                success: function(respond) {
                    var res = respond.split("|");
                    if (res[0] === "success") {
                        Swal.fire({
                            title: res[1],
                            text: res[2],
                            icon: "success",
                            confirmButtonColor: '#284c9a',
                            timer: 1500,
                            showConfirmButton: false
                        });
                        closeSubModal();
                        if (typeof window.getomancabang === 'function') {
                            window.getomancabang();
                        }
                    } else {
                        $btn.prop('disabled', false).removeClass('opacity-75 cursor-not-allowed pointer-events-none');
                        $btn.html('<i data-lucide="check" class="w-3.5 h-3.5"></i><span>Simpan Perubahan</span>');
                        if (typeof lucide !== 'undefined') lucide.createIcons();
                        Swal.fire({
                            title: res[1] || 'Gagal',
                            text: res[2] || 'Terjadi kesalahan sistem saat memperbarui data cabang.',
                            icon: "error",
                            confirmButtonColor: '#284c9a'
                        });
                    }
                },
                error: function() {
                    $btn.prop('disabled', false).removeClass('opacity-75 cursor-not-allowed pointer-events-none');
                    $btn.html('<i data-lucide="check" class="w-3.5 h-3.5"></i><span>Simpan Perubahan</span>');
                    if (typeof lucide !== 'undefined') lucide.createIcons();
                    Swal.fire({
                        title: 'Error',
                        text: 'Terjadi kesalahan komunikasi server.',
                        icon: 'error',
                        confirmButtonColor: '#284c9a'
                    });
                }
            });
        });

        // 5. DELETE CONFIRMATION
        $(".deleteform").on("submit", function(e) {
            e.preventDefault();
            const form = this;
            Swal.fire({
                title: "Hapus Dokumen OMAN?",
                text: "Data dokumen OMAN Marketing yang dihapus tidak dapat dikembalikan!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#e11d48",
                cancelButtonColor: "#64748b",
                confirmButtonText: "Ya, Hapus!",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });

    });
</script>
@endpush
