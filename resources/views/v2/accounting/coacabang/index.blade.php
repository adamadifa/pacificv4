@extends('v2.layouts.app')
@section('titlepage', 'COA Cabang')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Accounting</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">COA Cabang</span>
    </div>
@endsection

@section('content')
<div class="space-y-3.5 w-full max-w-4xl">

    <!-- PAGE TITLE BAR -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">COA Cabang</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Pengaturan Chart of Account untuk masing-masing cabang.</p>
        </div>
    </div>

    <!-- FILTER SECTION (COMPACT NO-LABEL V2) -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-3 shadow-2xs">
        <div class="relative select2-container-v2">
            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                <i data-lucide="building" class="w-4 h-4"></i>
            </div>
            <select name="filter_cabang" id="filter_cabang" class="select2Cabang w-full">
                <option value="">Pilih Cabang</option>
                @foreach ($cabang as $d)
                    <option value="{{ $d->kode_cabang }}" @selected(request('kode_cabang') == $d->kode_cabang)>
                        {{ $d->kode_cabang }} - {{ textUpperCase($d->nama_cabang) }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- DATA TABLE CARD (V2 COMPACT ~36PX) -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-2xs overflow-hidden">
        <!-- BARIS 1: STRIP JUDUL TABEL (bg-[#284c9a]) -->
        <div class="px-3.5 py-2 bg-[#284c9a] flex items-center justify-between text-white">
            <div class="flex items-center gap-2">
                <i data-lucide="list" class="w-4 h-4 text-blue-200"></i>
                <h5 class="text-xs font-bold text-white uppercase tracking-wider">Daftar COA Cabang</h5>
            </div>
            <div class="flex items-center gap-2">
                @can('coacabang.create')
                    <button type="button" id="btnCreateCoaCabang" style="display: none;"
                        class="h-[28px] px-3 rounded-lg bg-white/15 hover:bg-white/25 text-white text-xs font-bold transition flex items-center gap-1 cursor-pointer">
                        <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                        <span>Tambah</span>
                    </button>
                @endcan
            </div>
        </div>

        <!-- LOADING INDICATOR -->
        <div id="loadingCoaCabang" style="display: none;" class="py-12 flex flex-col items-center justify-center gap-2">
            <svg class="animate-spin h-6 w-6 text-[#284c9a]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="text-xs text-slate-500 font-semibold">Memuat data...</span>
        </div>

        <!-- DATA CONTAINER -->
        <div id="data-container">
            <div class="p-4">
                <div class="p-3.5 rounded-xl border border-blue-200/80 dark:border-blue-900/60 bg-blue-50/70 dark:bg-blue-950/30 text-[#284c9a] dark:text-blue-300 flex items-center justify-center gap-2 text-xs font-semibold">
                    <i data-lucide="info" class="w-4 h-4 shrink-0"></i>
                    <span>Silakan pilih cabang terlebih dahulu untuk menampilkan data</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ================= MODAL COA CABANG (STATIC BACKDROP) ================= -->
<div id="modalCoaCabang" class="fixed inset-0 z-50 hidden">
    <div id="modalCoaCabangBackdrop" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4 overflow-y-auto">
        <div id="modalCoaCabangDialog" class="relative w-full max-w-lg bg-white dark:bg-slate-900 rounded-xl shadow-2xl border border-slate-200/80 dark:border-slate-800 transform transition-all duration-150">
            <!-- Modal Header -->
            <div class="flex items-center justify-between px-4 py-3 border-b border-slate-200/80 dark:border-slate-800">
                <div class="flex items-center gap-2">
                    <i data-lucide="settings" class="w-4 h-4 text-[#284c9a] dark:text-blue-400"></i>
                    <h5 id="modalCoaCabangTitle" class="text-sm font-bold text-slate-800 dark:text-slate-100">Tambah COA Cabang</h5>
                </div>
                <button type="button" id="btnCloseModalCoaCabang"
                    class="w-7 h-7 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition cursor-pointer">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            <!-- Modal Body -->
            <div id="modalCoaCabangContent" class="p-4 sm:p-5"></div>
        </div>
    </div>
</div>
@endsection

@push('myscript')
<script>
    $(function() {
        if (typeof lucide !== 'undefined') lucide.createIcons();

        const $select2Cabang = $('.select2Cabang');
        if ($select2Cabang.length) {
            $select2Cabang.select2({
                placeholder: 'Pilih Cabang',
                allowClear: true,
                dropdownCssClass: 'select2-dropdown-v2'
            });
        }

        function initModalHelper(modalId, dialogId, backdropId, closeBtnId) {
            const $modal = $(modalId);
            const $dialog = $(dialogId);
            const $backdrop = $(backdropId);
            const $closeBtn = $(closeBtnId);

            function open() {
                $modal.removeClass('hidden');
                $('body').addClass('overflow-hidden');
            }

            function close() {
                $modal.addClass('hidden');
                $('body').removeClass('overflow-hidden');
                $modal.find(`${modalId}Content`).html('');
            }

            $closeBtn.on('click', close);

            $backdrop.on('click', function(e) {
                e.stopPropagation();
                $dialog.addClass('scale-[1.015] shadow-2xl');
                setTimeout(() => $dialog.removeClass('scale-[1.015] shadow-2xl'), 150);
            });

            $(document).on('keydown', function(e) {
                if (e.key === 'Escape' && !$modal.hasClass('hidden')) {
                    $dialog.addClass('scale-[1.015] shadow-2xl');
                    setTimeout(() => $dialog.removeClass('scale-[1.015] shadow-2xl'), 150);
                }
            });

            return { open, close };
        }

        window.modalCoaCabangHelper = initModalHelper('#modalCoaCabang', '#modalCoaCabangDialog', '#modalCoaCabangBackdrop', '#btnCloseModalCoaCabang');

        const loadingSpinner = `
            <div class="py-12 flex flex-col items-center justify-center gap-2">
                <svg class="animate-spin h-6 w-6 text-[#284c9a]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="text-xs text-slate-500 font-semibold">Memuat formulir...</span>
            </div>
        `;

        @if (request('kode_cabang'))
            loadData('{{ request('kode_cabang') }}');
        @endif

        $("#filter_cabang").on('change', function() {
            const kode_cabang = $(this).val();
            const url = new URL(window.location);

            if (kode_cabang) {
                loadData(kode_cabang);
                url.searchParams.set('kode_cabang', kode_cabang);
                window.history.pushState({}, '', url);
            } else {
                $("#data-container").html(`
                    <div class="p-4">
                        <div class="p-3.5 rounded-xl border border-blue-200/80 dark:border-blue-900/60 bg-blue-50/70 dark:bg-blue-950/30 text-[#284c9a] dark:text-blue-300 flex items-center justify-center gap-2 text-xs font-semibold">
                            <i data-lucide="info" class="w-4 h-4 shrink-0"></i>
                            <span>Silakan pilih cabang terlebih dahulu untuk menampilkan data</span>
                        </div>
                    </div>
                `);
                if (typeof lucide !== 'undefined') lucide.createIcons();
                $("#btnCreateCoaCabang").hide();
                url.searchParams.delete('kode_cabang');
                window.history.pushState({}, '', url);
            }
        });

        function loadData(kode_cabang) {
            $("#loadingCoaCabang").show();
            $("#data-container").hide();
            $("#btnCreateCoaCabang").hide();

            $.ajax({
                url: "{{ route('coacabang.getdata') }}",
                type: 'GET',
                data: { kode_cabang: kode_cabang },
                success: function(response) {
                    $("#data-container").html(response);
                    $("#loadingCoaCabang").hide();
                    $("#data-container").show();
                    if (kode_cabang) {
                        $("#btnCreateCoaCabang").show();
                    }
                    if (typeof lucide !== 'undefined') lucide.createIcons();
                },
                error: function() {
                    $("#loadingCoaCabang").hide();
                    $("#data-container").html(`
                        <div class="p-4">
                            <div class="p-3.5 rounded-xl border border-rose-200 dark:border-rose-900/60 bg-rose-50/80 dark:bg-rose-950/30 text-rose-700 dark:text-rose-300 flex items-center justify-center gap-2 text-xs font-semibold">
                                <i data-lucide="alert-circle" class="w-4 h-4 shrink-0"></i>
                                <span>Terjadi kesalahan saat memuat data</span>
                            </div>
                        </div>
                    `);
                    $("#data-container").show();
                    if (typeof lucide !== 'undefined') lucide.createIcons();
                }
            });
        }

        $("#btnCreateCoaCabang").on('click', function(e) {
            e.preventDefault();
            const kode_cabang = $("#filter_cabang").val();
            $("#modalCoaCabangTitle").text("Tambah COA Cabang");
            $("#modalCoaCabangContent").html(loadingSpinner);
            modalCoaCabangHelper.open();
            $("#modalCoaCabangContent").load(`/coacabang/create?kode_cabang=${kode_cabang}`, function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });

        $(document).on('click', '#data-container .btnDeleteCoaCabang', function(e) {
            e.preventDefault();
            const form = $(this).closest('form');
            Swal.fire({
                title: 'Hapus COA Cabang?',
                text: 'Data yang dihapus tidak dapat dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#284c9a',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endpush
