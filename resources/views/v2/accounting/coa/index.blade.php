@extends('layouts.app')
@section('titlepage', 'Chart of Account (COA)')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Home</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-slate-400">Accounting</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Chart of Accounts (COA)</span>
    </div>
@endsection

@section('content')
<div class="space-y-3.5 max-w-5xl">

    <!-- PAGE TITLE BAR & ACTION -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Chart of Accounts (COA)</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Daftar kode perkiraan akuntansi dan struktur bagan akun keuangan.</p>
        </div>

        <div class="flex items-center gap-2">
            @can('coa.create')
                <button type="button" id="btnCreateCoa"
                    class="inline-flex items-center justify-center gap-1.5 h-[34px] px-3.5 rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs font-bold transition shadow-xs cursor-pointer shrink-0">
                    <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                    <span>Tambah Akun</span>
                </button>
            @endcan
            <a href="{{ route('coa.export') }}"
                class="inline-flex items-center justify-center gap-1.5 h-[34px] px-3.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white text-xs font-bold transition shadow-xs cursor-pointer shrink-0"
                title="Export ke Excel">
                <i data-lucide="download" class="w-3.5 h-3.5"></i>
                <span>Excel</span>
            </a>
        </div>
    </div>

    <!-- FILTER SECTION (NO-LABEL, ICON PREFIX, H-[34PX], ROUNDED-LG) -->
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-3 shadow-2xs transition-colors duration-200">
        <form action="{{ route('coa.index') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-2">
            
            <!-- Input Cari Nama / Kode Akun -->
            <div class="relative flex-1 w-full sm:w-auto">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="search" class="w-3.5 h-3.5"></i>
                </div>
                <input type="text" name="nama_akun" id="nama_akun_search" value="{{ request('nama_akun') }}"
                    class="w-full h-[34px] pl-8 pr-2.5 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                    placeholder="Cari kode atau nama akun...">
            </div>

            <div class="flex items-center gap-2 w-full sm:w-auto">
                <!-- Submit Button -->
                <button type="submit"
                    class="flex-1 sm:flex-initial inline-flex items-center justify-center gap-1.5 h-[34px] px-4 rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs font-bold transition shadow-xs cursor-pointer">
                    <i data-lucide="search" class="w-3.5 h-3.5"></i>
                    <span>Cari</span>
                </button>

                <!-- Reset Button -->
                @if (request('nama_akun'))
                    <a href="{{ route('coa.index') }}"
                        class="inline-flex items-center justify-center gap-1 h-[34px] px-3 rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 text-xs font-bold transition">
                        <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                        <span>Reset</span>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- MAIN DATA TABLE CARD WITH UNIFIED PACIFIC NAVY HEADER -->
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-2xs overflow-hidden transition-colors duration-200">
        
        <div class="overflow-x-auto scrollbar-thin">
            <table class="w-full text-xs text-left border-collapse font-sans whitespace-nowrap">
                <!-- Seamless Unified Header -->
                <thead>
                    <!-- Row 1: Integrated Title Bar -->
                    <tr class="bg-[#284c9a] text-white">
                        <th colspan="5" class="px-3.5 py-2 bg-[#284c9a] text-white border-0">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="layers" class="w-4 h-4 text-blue-200"></i>
                                    <span class="text-xs font-bold uppercase tracking-wider text-white">Buku Daftar Akun (Chart of Accounts)</span>
                                </div>
                                <span class="text-[11px] font-semibold text-white/80 font-mono-num">
                                    Total {{ count($allAccounts) }} Akun
                                </span>
                            </div>
                        </th>
                    </tr>
                    <!-- Row 2: Column Titles -->
                    <tr class="bg-[#1e3a78] text-white text-[11px] border-none">
                        <th class="py-2 px-3.5 w-32 font-bold uppercase tracking-wider text-white/90 border-none">KODE AKUN</th>
                        <th class="py-2 px-3.5 font-bold uppercase tracking-wider text-white/90 border-none">NAMA AKUN</th>
                        <th class="py-2 px-3 text-center w-20 font-bold uppercase tracking-wider text-white/90 border-none">LEVEL</th>
                        <th class="py-2 px-3.5 w-36 font-bold uppercase tracking-wider text-white/90 border-none">KATEGORI</th>
                        <th class="py-2 px-3 text-center w-20 font-bold uppercase tracking-wider text-white/90 border-none">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 bg-white dark:bg-slate-900">
                    @forelse ($allAccounts as $account)
                        @php
                            $lvl = $account->level ?? 0;
                            $indentClass = 'pl-3.5';
                            if ($lvl == 1) {
                                $indentClass = 'pl-6';
                            } elseif ($lvl == 2) {
                                $indentClass = 'pl-9';
                            } elseif ($lvl == 3) {
                                $indentClass = 'pl-12';
                            } elseif ($lvl >= 4) {
                                $indentClass = 'pl-16';
                            }
                        @endphp
                        <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/50 transition duration-100">
                            <!-- Kode Akun -->
                            <td class="py-1.5 px-3.5 font-mono text-xs font-bold text-[#284c9a] dark:text-blue-400">
                                {{ $account->kode_akun }}
                            </td>

                            <!-- Nama Akun -->
                            <td class="py-1.5 px-3.5 {{ $indentClass }}">
                                @if($lvl == 0)
                                    <span class="font-extrabold text-slate-900 dark:text-white uppercase tracking-wide">{{ $account->nama_akun }}</span>
                                @elseif($lvl == 1)
                                    <span class="font-bold text-slate-800 dark:text-slate-100">{{ $account->nama_akun }}</span>
                                @elseif($lvl == 2)
                                    <span class="font-semibold text-slate-700 dark:text-slate-200">{{ $account->nama_akun }}</span>
                                @else
                                    <span class="text-slate-600 dark:text-slate-300">{{ $account->nama_akun }}</span>
                                @endif
                            </td>

                            <!-- Level -->
                            <td class="py-1.5 px-3 text-center">
                                <span class="inline-flex items-center justify-center px-1.5 py-0.5 text-[10px] font-bold rounded-md
                                    @if($lvl == 0)
                                        bg-indigo-50 text-indigo-700 border border-indigo-200 dark:bg-indigo-950/50 dark:text-indigo-300 dark:border-indigo-800
                                    @elseif($lvl == 1)
                                        bg-blue-50 text-blue-700 border border-blue-200 dark:bg-blue-950/50 dark:text-blue-300 dark:border-blue-800
                                    @elseif($lvl == 2)
                                        bg-teal-50 text-teal-700 border border-teal-200 dark:bg-teal-950/50 dark:text-teal-300 dark:border-teal-800
                                    @else
                                        bg-slate-100 text-slate-700 border border-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:border-slate-700
                                    @endif">
                                    Lvl {{ $lvl }}
                                </span>
                            </td>

                            <!-- Kategori -->
                            <td class="py-1.5 px-3.5 text-slate-500 dark:text-slate-400 text-[11px]">
                                @if(!empty($account->kode_kategori))
                                    <span class="font-medium text-slate-700 dark:text-slate-300">{{ $account->kode_kategori }}</span>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>

                            <!-- Aksi -->
                            <td class="py-1.5 px-3 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    @can('coa.edit')
                                        <button type="button" 
                                            class="btnEditCoa w-6 h-6 rounded-md bg-amber-50 dark:bg-amber-950/60 hover:bg-amber-100 text-amber-600 dark:text-amber-400 flex items-center justify-center transition shadow-2xs cursor-pointer"
                                            data-kode="{{ Crypt::encrypt($account->kode_akun) }}"
                                            title="Edit Akun">
                                            <i data-lucide="edit-3" class="w-3 h-3"></i>
                                        </button>
                                    @endcan

                                    @can('coa.delete')
                                        <form method="POST" action="{{ route('coa.delete', Crypt::encrypt($account->kode_akun)) }}" class="inline-flex m-0 p-0 deleteform">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                class="delete-confirm w-6 h-6 rounded-md bg-rose-50 dark:bg-rose-950/60 hover:bg-rose-100 text-rose-600 dark:text-rose-400 flex items-center justify-center transition shadow-2xs cursor-pointer"
                                                title="Hapus Akun">
                                                <i data-lucide="trash-2" class="w-3 h-3"></i>
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 px-4 text-center text-slate-400 dark:text-slate-500">
                                <div class="flex flex-col items-center justify-center gap-1.5">
                                    <i data-lucide="inbox" class="w-8 h-8 text-slate-300 dark:text-slate-600 stroke-[1.5]"></i>
                                    <span class="font-bold text-xs">Belum ada data Chart of Accounts</span>
                                    <span class="text-[11px] text-slate-400">Silakan tambahkan data akun baru menggunakan tombol di atas.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

</div>

<!-- Modal Container (Standard V2 Static Backdrop) -->
<div id="modalCoa" class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="relative w-full max-w-lg bg-white dark:bg-slate-900 rounded-xl shadow-2xl border border-slate-200 dark:border-slate-800 overflow-hidden transform transition-all duration-200 my-8">
        <!-- Modal Header (Clean V2 Standard) -->
        <div class="flex items-center justify-between px-4 py-3 border-b border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900">
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 rounded-lg bg-blue-50 dark:bg-blue-950/60 text-[#284c9a] dark:text-blue-400 flex items-center justify-center">
                    <i data-lucide="layers" class="w-4 h-4"></i>
                </div>
                <h4 id="modalTitleCoa" class="text-sm font-bold text-slate-800 dark:text-slate-100">
                    Tambah Akun COA
                </h4>
            </div>
            <button type="button" class="btn-close-modal w-7 h-7 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition cursor-pointer">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>
        <!-- Modal Body Content -->
        <div id="modalBodyCoa" class="p-4 max-h-[80vh] overflow-y-auto scrollbar-thin">
            <!-- Loaded via AJAX -->
        </div>
    </div>
</div>

@endsection

@push('myscript')
<script>
    $(function() {
        const $modal = $("#modalCoa");
        const $modalTitle = $("#modalTitleCoa");
        const $modalBody = $("#modalBodyCoa");

        function openModal(title) {
            $modalTitle.text(title);
            $modalBody.html(`
                <div class="py-12 flex flex-col items-center justify-center gap-2">
                    <svg class="animate-spin h-6 w-6 text-brand-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-xs text-slate-400">Memuat formulir...</span>
                </div>
            `);
            $modal.removeClass("hidden").addClass("flex");
            if (typeof lucide !== 'undefined') lucide.createIcons();
        }

        function closeModal() {
            if ($.fn.select2) {
                $('.select2-hidden-accessible').select2('close');
            }
            $modal.addClass("hidden").removeClass("flex");
            $modalBody.empty();
        }

        // Close on button click
        $modal.on("click", ".btn-close-modal", closeModal);

        // Modal Tambah Akun
        $("#btnCreateCoa").click(function(e) {
            e.preventDefault();
            openModal("Tambah Akun COA Baru");
            $modalBody.load("{{ route('coa.create') }}", function(response, status, xhr) {
                if (status === "error") {
                    $modalBody.html(`
                        <div class="p-4 text-center text-rose-500 font-semibold text-xs">
                            Gagal memuat formulir: ${xhr.status} ${xhr.statusText}
                        </div>
                    `);
                }
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });

        // Modal Edit Akun
        $(document).on('click', '.btnEditCoa', function(e) {
            e.preventDefault();
            var kode_akun = $(this).attr("data-kode");
            openModal("Edit Akun COA");
            $modalBody.load("/coa/" + encodeURIComponent(kode_akun) + "/edit", function(response, status, xhr) {
                if (status === "error") {
                    $modalBody.html(`
                        <div class="p-4 text-center text-rose-500 font-semibold text-xs">
                            Gagal memuat formulir: ${xhr.status} ${xhr.statusText}
                        </div>
                    `);
                }
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });
    });
</script>
@endpush
