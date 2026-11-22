@extends('layouts.app')
@section('titlepage', 'Detail Aturan Diskon')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Home</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-slate-400">Data Master</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <a href="{{ route('kategoridiskon.index') }}" class="text-slate-500 hover:text-brand-600 transition">Kategori Diskon</a>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Detail Aturan</span>
    </div>
@endsection

@section('content')
<div class="space-y-4 max-w-5xl">

    <!-- BACK BUTTON & TITLE -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div class="flex items-center gap-2.5">
            <a href="{{ route('kategoridiskon.index') }}"
                class="inline-flex items-center justify-center w-8 h-8 rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 shadow-2xs transition">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
            </a>
            <div>
                <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Detail Aturan Diskon</h3>
                <p class="text-[11px] text-slate-400 mt-0.5">Atur batasan tier kuantitas dan potongan harga tunai maupun kredit.</p>
            </div>
        </div>

        <button type="button" id="btncreateDetail"
            kode_kategori_diskon="{{ Crypt::encrypt($kategoridiskon->kode_kategori_diskon) }}"
            class="inline-flex items-center justify-center gap-1.5 h-[34px] px-3.5 rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs font-bold transition shadow-xs cursor-pointer shrink-0">
            <i data-lucide="plus" class="w-3.5 h-3.5"></i>
            <span>Tambah Tier Aturan</span>
        </button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-3.5">
        
        <!-- INFO KATEGORI CARD -->
        <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-2xs overflow-hidden h-fit transition-colors duration-200">
            <!-- Header Navy -->
            <div class="bg-[#284c9a] text-white px-4 py-3 flex items-center gap-2">
                <i data-lucide="info" class="w-4 h-4 text-blue-200"></i>
                <span class="text-xs font-bold uppercase tracking-wider text-white">Informasi Kategori</span>
            </div>
            
            <div class="p-4 space-y-3">
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Kode Kategori</span>
                    <div class="mt-0.5">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-black font-mono-num bg-brand-50 dark:bg-brand-950/60 text-brand-700 dark:text-brand-300 border border-brand-200/60 dark:border-brand-800/60">
                            {{ $kategoridiskon->kode_kategori_diskon }}
                        </span>
                    </div>
                </div>

                <div class="border-t border-slate-100 dark:border-slate-800/80 pt-2.5">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Nama Kategori</span>
                    <p class="text-xs font-bold text-slate-800 dark:text-slate-100 mt-0.5">
                        {{ $kategoridiskon->nama_kategori }}
                    </p>
                </div>

                <div class="border-t border-slate-100 dark:border-slate-800/80 pt-2.5">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Jumlah Aturan Tier</span>
                    <p class="text-xs font-bold text-slate-700 dark:text-slate-300 mt-0.5 font-mono-num">
                        {{ count($diskons) }} Tier Aturan Diskon
                    </p>
                </div>
            </div>
        </div>

        <!-- MAIN TABLE ATURAN KUANTITAS & POTONGAN -->
        <div class="lg:col-span-2 rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-2xs overflow-hidden transition-colors duration-200">
            
            <div class="overflow-x-auto scrollbar-thin">
                <table class="w-full text-xs text-left border-collapse font-sans">
                    <!-- Unified Navy Header -->
                    <thead>
                        <tr class="bg-[#284c9a] text-white">
                            <th colspan="6" class="px-4 py-3 bg-[#284c9a] text-white border-0">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <i data-lucide="layers" class="w-4 h-4 text-blue-200"></i>
                                        <span class="text-xs font-bold uppercase tracking-wider text-white">Aturan Kuantitas & Potongan</span>
                                    </div>
                                    <span class="text-[11px] font-semibold text-white/80 font-mono-num">
                                        {{ count($diskons) }} Level
                                    </span>
                                </div>
                            </th>
                        </tr>
                        <tr class="bg-[#1e3a78] text-white text-[11px] border-none">
                            <th class="py-2.5 px-3 text-center w-12 font-bold uppercase tracking-wider text-white/90 border-none">NO</th>
                            <th class="py-2.5 px-3 text-center font-bold uppercase tracking-wider text-white/90 border-none">MIN QTY</th>
                            <th class="py-2.5 px-3 text-center font-bold uppercase tracking-wider text-white/90 border-none">MAX QTY</th>
                            <th class="py-2.5 px-3 text-right font-bold uppercase tracking-wider text-white/90 border-none">DISKON KREDIT</th>
                            <th class="py-2.5 px-3 text-right font-bold uppercase tracking-wider text-white/90 border-none">DISKON TUNAI</th>
                            <th class="py-2.5 px-3 text-center w-20 font-bold uppercase tracking-wider text-white/90 border-none">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 bg-white dark:bg-slate-900">
                        @forelse ($diskons as $d)
                            <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/50 transition duration-100">
                                <td class="py-1.5 px-3 text-center font-mono-num text-slate-400 border-r border-slate-100 dark:border-slate-800/60 text-[11px]">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="py-1.5 px-3 text-center font-mono-num font-bold text-slate-700 dark:text-slate-300 border-r border-slate-100 dark:border-slate-800/60">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-slate-200">
                                        {{ number_format($d->min_qty) }}
                                    </span>
                                </td>
                                <td class="py-1.5 px-3 text-center font-mono-num font-bold text-slate-700 dark:text-slate-300 border-r border-slate-100 dark:border-slate-800/60">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-slate-200">
                                        {{ number_format($d->max_qty) }}
                                    </span>
                                </td>
                                <td class="py-1.5 px-3 text-right font-mono-num font-bold text-brand-600 dark:text-brand-400 border-r border-slate-100 dark:border-slate-800/60">
                                    Rp {{ number_format($d->diskon) }}
                                </td>
                                <td class="py-1.5 px-3 text-right font-mono-num font-bold text-emerald-600 dark:text-emerald-400 border-r border-slate-100 dark:border-slate-800/60">
                                    Rp {{ number_format($d->diskon_tunai) }}
                                </td>
                                <td class="py-1.5 px-3 text-center">
                                    <div class="flex items-center justify-center gap-1">
                                        <button type="button" 
                                            class="editDetail inline-flex items-center justify-center w-6 h-6 rounded-md bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 hover:bg-amber-500 hover:text-white transition shadow-2xs cursor-pointer"
                                            diskon_id="{{ Crypt::encrypt($d->id) }}"
                                            title="Edit Tier">
                                            <i data-lucide="edit-3" class="w-3 h-3"></i>
                                        </button>
                                        <form method="POST" action="{{ route('kategoridiskon.destroydetail', Crypt::encrypt($d->id)) }}" class="inline-flex m-0 p-0 deleteform">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="delete-confirm inline-flex items-center justify-center w-6 h-6 rounded-md bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 hover:bg-rose-500 hover:text-white transition shadow-2xs cursor-pointer"
                                                data-name="Aturan Qty {{ $d->min_qty }} - {{ $d->max_qty }}"
                                                title="Hapus Tier">
                                                <i data-lucide="trash-2" class="w-3 h-3"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center text-slate-400">
                                    <i data-lucide="inbox" class="w-8 h-8 mx-auto text-slate-300 dark:text-slate-600 mb-2"></i>
                                    <span class="text-xs font-semibold">Belum ada aturan tier diskon untuk kategori ini</span>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>

    </div>

</div>

<!-- Modal Container -->
<div id="modalDetailDiskon" class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="relative w-full max-w-lg bg-white dark:bg-slate-900 rounded-xl shadow-2xl border border-slate-200 dark:border-slate-800 overflow-hidden transform transition-all duration-200 my-8">
        <!-- Modal Header -->
        <div class="flex items-center justify-between px-4 py-3 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50">
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 rounded-lg bg-brand-50 dark:bg-brand-950/60 text-brand-600 dark:text-brand-400 flex items-center justify-center">
                    <i data-lucide="percent" class="w-4 h-4"></i>
                </div>
                <h4 id="modalTitleDetailDiskon" class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">
                    Form Aturan Diskon
                </h4>
            </div>
            <button type="button" class="btn-close-modal text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 p-1 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>
        <!-- Modal Body Content -->
        <div id="modalBodyDetailDiskon" class="p-4 max-h-[80vh] overflow-y-auto scrollbar-thin">
            <!-- Loaded via AJAX -->
        </div>
    </div>
</div>

@endsection

@push('myscript')
<script>
    $(function() {
        const $modal = $("#modalDetailDiskon");
        const $modalTitle = $("#modalTitleDetailDiskon");
        const $modalBody = $("#modalBodyDetailDiskon");

        function openModal(title) {
            $modalTitle.text(title);
            $modalBody.html(`
                <div class="py-12 flex flex-col items-center justify-center gap-2">
                    <svg class="animate-spin h-6 w-6 text-brand-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-xs text-slate-400">Memuat formulir aturan...</span>
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

        // Close on button click or outside click
        $modal.on("click", ".btn-close-modal", closeModal);
        // Static Backdrop: Explicit close only

        // Modal Tambah Detail
        $("#btncreateDetail").click(function(e) {
            e.preventDefault();
            var kode_kategori_diskon = $(this).attr("kode_kategori_diskon");
            openModal("Tambah Aturan Diskon");
            $modalBody.load('/kategoridiskon/' + kode_kategori_diskon + '/createdetail', function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });

        // Modal Edit Detail
        $(document).on('click', '.editDetail', function(e) {
            e.preventDefault();
            var diskon_id = $(this).attr("diskon_id");
            openModal("Edit Aturan Diskon");
            $modalBody.load('/kategoridiskon/' + diskon_id + '/editdetail', function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });
    });
</script>
@endpush
