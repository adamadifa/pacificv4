@extends('layouts.app')
@section('titlepage', 'Dashboard Gudang')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Home</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <a href="{{ route('dashboard') }}" class="text-slate-400 hover:text-brand-600 transition">Dashboard</a>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Gudang</span>
    </div>
@endsection

@section('content')
<div class="space-y-4">

    <!-- WELCOME SLIM BAR -->
    @include('dashboard.welcome')

    <!-- DASHBOARD SUB-NAVIGATION BAR -->
    @include('layouts.navigation_dashboard')

    <!-- MAIN CARD CONTAINER: REKAP PERSEDIAAN GUDANG -->
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-4 sm:p-5 shadow-2xs transition-colors duration-200">
        
        <!-- Header & Action Controls Bar -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 pb-3 mb-4 border-b border-slate-100 dark:border-slate-800">
            
            <!-- Left: Title & Subtitle -->
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-brand-50 dark:bg-brand-950/60 text-brand-600 dark:text-brand-400 flex items-center justify-center border border-brand-100 dark:border-brand-900 shrink-0 shadow-2xs">
                    <i data-lucide="warehouse" class="w-4 h-4"></i>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-slate-900 dark:text-white leading-tight">Matriks Persediaan Gudang (Stock Utama)</h4>
                    <span class="text-[11px] text-slate-400 leading-none">Monitoring stok fisik Gudang Pusat & seluruh Cabang dalam satuan Dus</span>
                </div>
            </div>

            <!-- Right: Search Input & Action Button (No-Label, Icon Prefix, h-[34px], rounded-lg) -->
            <div class="flex flex-wrap items-center gap-2">
                <!-- Search Cabang Live Input -->
                <div class="relative w-full sm:w-56">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="search" class="w-3.5 h-3.5"></i>
                    </div>
                    <input type="text" id="searchCabangInput" placeholder="Cari unit cabang..." class="w-full h-[34px] pl-8 pr-2.5 text-xs font-medium rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 placeholder:text-slate-400 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                </div>

                <!-- Toggle All Sub-Rows Button -->
                <button type="button" id="btnToggleAllDetails" class="inline-flex items-center gap-1.5 h-[34px] px-3 rounded-lg text-xs font-bold border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition shadow-2xs cursor-pointer select-none">
                    <i data-lucide="chevrons-up-down" class="w-3.5 h-3.5 text-slate-400"></i>
                    <span id="btnToggleText">Buka Detail</span>
                </button>
            </div>
        </div>

        <!-- Status Legend Bar -->
        <div class="flex flex-wrap items-center gap-2 sm:gap-3 py-2 px-3 mb-3.5 rounded-lg bg-slate-50 dark:bg-slate-950/60 border border-slate-200/80 dark:border-slate-800 text-[11px]">
            <span class="font-bold text-slate-500 dark:text-slate-400 mr-1 flex items-center gap-1">
                <i data-lucide="info" class="w-3 h-3"></i>
                <span>Petunjuk Warna:</span>
            </span>
            <div class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800/60 font-semibold">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                <span>Gudang Pusat</span>
            </div>
            <div class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md bg-rose-50 dark:bg-rose-950/50 text-rose-700 dark:text-rose-400 border border-rose-200/60 dark:border-rose-800/60 font-semibold">
                <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                <span>Di Bawah Buffer (Kurang)</span>
            </div>
            <div class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md bg-cyan-50 dark:bg-cyan-950/50 text-cyan-700 dark:text-cyan-400 border border-cyan-200/60 dark:border-cyan-800/60 font-semibold">
                <span class="w-2 h-2 rounded-full bg-cyan-500"></span>
                <span>Di Atas Max (Overstock)</span>
            </div>
            <div class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-800 font-semibold">
                <span class="w-2 h-2 rounded-full bg-slate-300 dark:bg-slate-600"></span>
                <span>Aman / Ideal</span>
            </div>
        </div>

        <!-- AJAX Container: Rekap Persediaan -->
        <div id="loadrekappersediaan">
            <div class="flex flex-col items-center justify-center py-16 text-slate-400">
                <div class="w-8 h-8 border-2 border-brand-600 border-t-transparent rounded-full animate-spin mb-2.5"></div>
                <span class="text-xs font-medium">Memuat data persediaan gudang...</span>
            </div>
        </div>

    </div>

</div>
@endsection

@push('myscript')
<script>
$(function() {
    // Re-initialize Lucide Icons
    if (window.lucide) {
        lucide.createIcons();
    }

    var detailsExpanded = false;

    // Function to load Rekap Persediaan Gudang (AJAX)
    function loadrekappersediaan() {
        $("#loadrekappersediaan").html(`
            <div class="flex flex-col items-center justify-center py-16 text-slate-400">
                <div class="w-8 h-8 border-2 border-brand-600 border-t-transparent rounded-full animate-spin mb-2.5"></div>
                <span class="text-xs font-medium">Memuat data persediaan gudang...</span>
            </div>
        `);

        $.ajax({
            type: "GET",
            url: "/dashboard/rekappersediaan",
            cache: false,
            success: function(respond) {
                $("#loadrekappersediaan").html(respond);
                if (window.lucide) {
                    lucide.createIcons();
                }
                applySearchFilter();
            },
            error: function(xhr, status, error) {
                console.error("Error loading rekappersediaan:", status, error);
                $("#loadrekappersediaan").html(`
                    <div class="p-6 rounded-lg bg-rose-50 dark:bg-rose-950/30 border border-rose-200 dark:border-rose-800 text-center">
                        <p class="text-xs font-semibold text-rose-600 dark:text-rose-400">Gagal memuat data rekap persediaan gudang.</p>
                        <button type="button" id="btnRetryLoad" class="mt-2 text-xs font-bold text-brand-600 hover:text-brand-700 underline cursor-pointer">Coba Lagi</button>
                    </div>
                `);
                $("#btnRetryLoad").on("click", function() {
                    loadrekappersediaan();
                });
            }
        });
    }

    // Initial Trigger
    loadrekappersediaan();

    // Live search filter across branch names
    function applySearchFilter() {
        const query = $("#searchCabangInput").val().toLowerCase().trim();
        if (!query) {
            $(".branch-v2-row").show();
            return;
        }

        $(".branch-v2-row").each(function() {
            const branchName = $(this).find("td.freeze-col").text().toLowerCase();
            const branchId = $(this).data("branch");
            if (branchName.indexOf(query) !== -1) {
                $(this).show();
            } else {
                $(this).hide();
                $(`.detail-${branchId}`).addClass('hidden');
            }
        });
    }

    $("#searchCabangInput").on("input", function() {
        applySearchFilter();
    });

    // Expand / Collapse All Details toggle
    $("#btnToggleAllDetails").on("click", function() {
        detailsExpanded = !detailsExpanded;
        if (detailsExpanded) {
            $(".detail-v2-row").removeClass("hidden");
            $(".branch-v2-row").addClass("active");
            $("#btnToggleText").text("Tutup Detail");
        } else {
            $(".detail-v2-row").addClass("hidden");
            $(".branch-v2-row").removeClass("active");
            $("#btnToggleText").text("Buka Detail");
        }
    });
});
</script>
@endpush
