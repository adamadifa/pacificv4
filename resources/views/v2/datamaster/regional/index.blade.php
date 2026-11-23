@extends('layouts.app')
@section('titlepage', 'Regional')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Home</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-slate-400">Data Master</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Regional</span>
    </div>
@endsection

@section('content')
<div class="space-y-3.5 w-full">

    <!-- PAGE TITLE BAR & ACTION -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Data Regional</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Kelola pembagian wilayah regional dan integrasi struktur operasional cabang.</p>
        </div>

        @can('regional.create')
            <button type="button" id="btncreateRegional"
                class="inline-flex items-center justify-center gap-1.5 h-[34px] px-3.5 rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs font-bold transition shadow-xs cursor-pointer shrink-0">
                <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                <span>Tambah Regional</span>
            </button>
        @endcan
    </div>

    <!-- FILTER SECTION (NO-LABEL, ICON PREFIX, H-[34PX], ROUNDED-LG) -->
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-3 shadow-2xs transition-colors duration-200">
        <form action="{{ route('regional.index') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-2">
            
            <!-- Input Cari Nama Regional -->
            <div class="relative flex-1 w-full sm:w-auto">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="search" class="w-3.5 h-3.5"></i>
                </div>
                <input type="text" name="nama_regional" id="nama_regional_search" value="{{ request('nama_regional') }}"
                    class="w-full h-[34px] pl-8 pr-2.5 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                    placeholder="Cari nama atau kode regional...">
            </div>

            <div class="flex items-center gap-2 w-full sm:w-auto">
                <!-- Submit Button -->
                <button type="submit"
                    class="flex-1 sm:flex-initial inline-flex items-center justify-center gap-1.5 h-[34px] px-4 rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs font-bold transition shadow-xs cursor-pointer">
                    <i data-lucide="search" class="w-3.5 h-3.5"></i>
                    <span>Cari</span>
                </button>

                <!-- Reset Button (if filter applied) -->
                @if (request('nama_regional'))
                    <a href="{{ route('regional.index') }}"
                        class="inline-flex items-center justify-center gap-1 h-[34px] px-3 rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 text-xs font-bold transition">
                        <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                        <span>Reset</span>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- MAIN DATA TABLE CARD (KOMPAK ~36PX, STANDAR BARU V2) -->
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-2xs overflow-hidden transition-colors duration-200">
        
        <div class="overflow-x-auto scrollbar-thin">
            <table class="w-full text-xs text-left border-collapse whitespace-nowrap">
                <thead>
                    <!-- Row 1: Integrated Title Bar (px-3.5 py-2 standar V2) -->
                    <tr class="bg-[#284c9a] text-white">
                        <th colspan="6" class="px-3.5 py-2 bg-[#284c9a] text-white border-0">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="map" class="w-4 h-4 text-blue-200"></i>
                                    <span class="text-xs font-bold text-white">Daftar Wilayah Regional</span>
                                </div>
                                <span class="text-[11px] font-semibold text-white/80 font-mono-num">
                                    Total {{ count($regional) }} Regional
                                </span>
                            </div>
                        </th>
                    </tr>
                    <!-- Row 2: Column Titles (py-2 px-3 standar V2) -->
                    <tr class="bg-[#1e3a78] text-white text-[11px] border-none">
                        <th class="py-2 px-3 text-center w-12 font-bold uppercase tracking-wider text-white/90 border-none">NO</th>
                        <th class="py-2 px-3 text-center w-32 font-bold uppercase tracking-wider text-white/90 border-none">KODE REGIONAL</th>
                        <th class="py-2 px-3.5 font-bold uppercase tracking-wider text-white/90 border-none w-56">NAMA REGIONAL</th>
                        <th class="py-2 px-3 text-center w-36 font-bold uppercase tracking-wider text-white/90 border-none">JUMLAH CABANG</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-none">DAFTAR CABANG</th>
                        <th class="py-2 px-3 text-center w-24 font-bold uppercase tracking-wider text-white/90 border-none">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 bg-white dark:bg-slate-900">
                    @forelse ($regional as $d)
                        @php
                            $cabangList = $d->cabang ?? collect();
                            $jmlCabang = $cabangList->count();
                        @endphp
                        <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/50 transition duration-100">
                            <!-- No -->
                            <td class="py-1.5 px-3 text-center font-mono-num text-slate-400 text-[11px]">
                                {{ $loop->iteration }}
                            </td>
                            <!-- Kode Regional -->
                            <td class="py-1.5 px-3 text-center">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-black font-mono-num bg-brand-50 dark:bg-brand-950/60 text-brand-700 dark:text-brand-300 border border-brand-200/60 dark:border-brand-800/60">
                                    {{ $d->kode_regional }}
                                </span>
                            </td>
                            <!-- Nama Regional -->
                            <td class="py-1.5 px-3.5 font-semibold text-slate-800 dark:text-slate-100">
                                <div class="flex items-center gap-2">
                                    <div class="w-1.5 h-1.5 rounded-full bg-emerald-500 shrink-0"></div>
                                    <span class="truncate">{{ $d->nama_regional }}</span>
                                </div>
                            </td>
                            <!-- Jumlah Cabang -->
                            <td class="py-1.5 px-3 text-center">
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold font-mono-num {{ $jmlCabang > 0 ? 'bg-blue-50 text-[#284c9a] dark:bg-blue-950/60 dark:text-blue-400 border border-blue-200/60 dark:border-blue-900/50' : 'bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400' }}">
                                    <i data-lucide="building-2" class="w-3 h-3"></i>
                                    <span>{{ $jmlCabang }} Cabang</span>
                                </span>
                            </td>
                            <!-- Daftar Cabang -->
                            <td class="py-1.5 px-3">
                                @if ($jmlCabang > 0)
                                    <div class="flex items-center gap-1 flex-wrap max-w-md">
                                        @foreach ($cabangList as $c)
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-mono-num font-bold bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60 transition"
                                                title="{{ $c->nama_cabang }}">
                                                {{ $c->kode_cabang }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-slate-400 dark:text-slate-500 text-[11px] italic">Belum ada cabang terdaftar</span>
                                @endif
                            </td>
                            <!-- Aksi (Kompak 24x24 px) -->
                            <td class="py-1.5 px-3 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    @can('regional.edit')
                                        <button type="button" 
                                            class="editRegional inline-flex items-center justify-center w-6 h-6 rounded-md bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 hover:bg-amber-500 hover:text-white transition shadow-2xs cursor-pointer"
                                            kode_regional="{{ Crypt::encrypt($d->kode_regional) }}"
                                            title="Edit Regional">
                                            <i data-lucide="edit-3" class="w-3 h-3"></i>
                                        </button>
                                    @endcan

                                    @can('regional.delete')
                                        <form method="POST" action="{{ route('regional.delete', Crypt::encrypt($d->kode_regional)) }}" class="inline-flex m-0 p-0 deleteform">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="delete-confirm inline-flex items-center justify-center w-6 h-6 rounded-md bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 hover:bg-rose-500 hover:text-white transition shadow-2xs cursor-pointer"
                                                data-name="{{ $d->nama_regional }}"
                                                title="Hapus Regional">
                                                <i data-lucide="trash-2" class="w-3 h-3"></i>
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-400">
                                <i data-lucide="inbox" class="w-8 h-8 mx-auto text-slate-300 dark:text-slate-600 mb-2"></i>
                                <span class="text-xs font-semibold">Belum ada data regional</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

</div>

<!-- MODAL REGIONAL (STANDAR V2 STATIC BACKDROP & CLEAN HEADER) -->
<div id="modalRegional" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modalTitleRegional" role="dialog" aria-modal="true">
    <!-- Backdrop Gelap -->
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity" id="modalRegionalBackdrop"></div>

    <div class="flex min-h-full items-center justify-center p-3 sm:p-4 text-center">
        <div id="modalRegionalDialog"
            class="relative w-full max-w-lg transform rounded-2xl bg-white dark:bg-slate-900 text-left shadow-2xl transition-all duration-150 border border-slate-200/80 dark:border-slate-800 overflow-hidden">
            
            <!-- Modal Header (Clean Standard) -->
            <div class="flex items-center justify-between px-4 py-3 border-b border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-lg bg-blue-50 dark:bg-blue-950/60 text-[#284c9a] dark:text-blue-400 flex items-center justify-center">
                        <i data-lucide="map" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100" id="modalTitleRegional">Form Data Regional</h3>
                        <p class="text-[11px] text-slate-400" id="modalSubtitleRegional">Kelola data pembagian wilayah regional</p>
                    </div>
                </div>
                <button type="button" class="btn-close-modal w-7 h-7 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition cursor-pointer">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>

            <!-- Modal Body Container -->
            <div class="p-4" id="modalBodyRegional">
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

        const $modal = $("#modalRegional");
        const $dialog = $("#modalRegionalDialog");
        const $body = $("#modalBodyRegional");
        const $title = $("#modalTitleRegional");
        const $subtitle = $("#modalSubtitleRegional");

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

        function bounceLockModal() {
            $dialog.addClass('scale-[1.015] shadow-2xl');
            setTimeout(function() {
                $dialog.removeClass('scale-[1.015] shadow-2xl');
            }, 150);
        }

        // Static Backdrop on outside click
        $modal.on("click", function(e) {
            if ($(e.target).closest('#modalRegionalDialog').length === 0) {
                bounceLockModal();
            }
        });

        $(document).on("keydown", function(e) {
            if (e.key === "Escape" && !$modal.hasClass('hidden')) {
                bounceLockModal();
            }
        });

        function closeModal() {
            $modal.addClass("hidden");
            $body.empty();
        }

        $(document).on("click", ".btn-close-modal", function() {
            closeModal();
        });

        // 1. TAMBAH REGIONAL
        $("#btncreateRegional").on("click", function(e) {
            e.preventDefault();
            $title.text("Tambah Regional Baru");
            $subtitle.text("Masukkan kode dan nama wilayah regional");
            $body.html(loadingSpinner());
            $modal.removeClass("hidden");

            $.get("{{ route('regional.create') }}", function(respond) {
                $body.html(respond);
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            }).fail(function() {
                $body.html(`
                    <div class="py-8 text-center text-rose-500 font-semibold text-xs">
                        Gagal memuat formulir pembuatan regional.
                    </div>
                `);
            });
        });

        // 2. EDIT REGIONAL
        $(document).on("click", ".editRegional", function(e) {
            e.preventDefault();
            const kode_regional = $(this).attr("kode_regional");
            $title.text("Edit Data Regional");
            $subtitle.text("Perbarui nama wilayah regional");
            $body.html(loadingSpinner());
            $modal.removeClass("hidden");

            $.get("/regional/" + kode_regional + "/edit", function(respond) {
                $body.html(respond);
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            }).fail(function() {
                $body.html(`
                    <div class="py-8 text-center text-rose-500 font-semibold text-xs">
                        Gagal memuat formulir edit regional.
                    </div>
                `);
            });
        });

        // 3. DELETE CONFIRMATION
        $(".deleteform").on("submit", function(e) {
            e.preventDefault();
            const form = this;
            const name = $(this).find('button').data('name') || 'Regional ini';
            
            Swal.fire({
                title: "Hapus Regional?",
                text: `Apakah Anda yakin ingin menghapus "${name}"? Pastikan tidak ada data cabang yang terhubung.`,
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
