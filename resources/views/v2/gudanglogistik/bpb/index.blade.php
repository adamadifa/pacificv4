@extends('layouts.app')
@section('titlepage', 'Bukti Permintaan Barang')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Home</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-slate-400">Gudang Logistik</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Bukti Permintaan Barang</span>
    </div>
@endsection

@section('content')
<div class="space-y-4 max-w-7xl">

    <!-- PAGE HEADER TITLE BAR -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Bukti Permintaan Barang (BPB)</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Kelola dan pantau bukti permintaan barang serta alur serah terima dari gudang logistik.</p>
        </div>
    </div>

    <!-- FILTER SECTION (NO LABEL STANDAR FORM FILTER V2) -->
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-3.5 shadow-2xs">
        <form action="{{ route('bpb.index') }}" method="GET" id="formSearch" class="w-full space-y-2.5">
            <!-- Row 1: Search, Dari, Sampai -->
            <div class="grid grid-cols-1 sm:grid-cols-12 gap-2.5 items-center">
                <div class="sm:col-span-6">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                            <i data-lucide="barcode" class="w-4 h-4"></i>
                        </div>
                        <input type="text" name="no_bpb_search" id="no_bpb_search" value="{{ Request('no_bpb_search') }}"
                            placeholder="Cari No. Bukti BPB..."
                            class="w-full h-[36px] pl-9 pr-3 text-xs rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-hidden focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a] transition">
                    </div>
                </div>

                <div class="sm:col-span-3">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                            <i data-lucide="calendar" class="w-4 h-4"></i>
                        </div>
                        <input type="text" name="dari" id="dari" value="{{ Request('dari') }}" placeholder="Dari Tanggal" readonly
                            class="flatpickr-date w-full h-[36px] pl-9 pr-3 text-xs rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-hidden focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a] transition cursor-pointer">
                    </div>
                </div>

                <div class="sm:col-span-3">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                            <i data-lucide="calendar" class="w-4 h-4"></i>
                        </div>
                        <input type="text" name="sampai" id="sampai" value="{{ Request('sampai') }}" placeholder="Sampai Tanggal" readonly
                            class="flatpickr-date w-full h-[36px] pl-9 pr-3 text-xs rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-hidden focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a] transition cursor-pointer">
                    </div>
                </div>
            </div>

            <!-- Row 2: Status, Cabang, Departemen, Cari Button -->
            <div class="grid grid-cols-1 sm:grid-cols-12 gap-2.5 items-center">
                <div class="sm:col-span-3">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                            <i data-lucide="check-circle" class="w-4 h-4"></i>
                        </div>
                        <select name="status" id="status"
                            class="w-full h-[36px] pl-9 pr-7 text-xs font-medium rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a] transition appearance-none cursor-pointer">
                            <option value="">Semua Status</option>
                            <option value="selesai" {{ Request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="proses" {{ Request('status') == 'proses' ? 'selected' : '' }}>Proses</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="chevron-down" class="w-4 h-4"></i>
                        </div>
                    </div>
                </div>

                <div class="sm:col-span-3">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                            <i data-lucide="building" class="w-4 h-4"></i>
                        </div>
                        <select name="kode_cabang" id="kode_cabang"
                            class="w-full h-[36px] pl-9 pr-7 text-xs font-medium rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a] transition appearance-none cursor-pointer">
                            <option value="">Semua Cabang</option>
                            @foreach ($cabangList as $c)
                                <option value="{{ $c->kode_cabang }}" {{ Request('kode_cabang') == $c->kode_cabang ? 'selected' : '' }}>
                                    {{ $c->nama_cabang }}
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="chevron-down" class="w-4 h-4"></i>
                        </div>
                    </div>
                </div>

                <div class="sm:col-span-4">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                            <i data-lucide="briefcase" class="w-4 h-4"></i>
                        </div>
                        <select name="kode_dept" id="kode_dept"
                            class="w-full h-[36px] pl-9 pr-7 text-xs font-medium rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a] transition appearance-none cursor-pointer">
                            <option value="">Semua Departemen</option>
                            @foreach ($deptList as $d)
                                <option value="{{ $d->kode_dept }}" {{ Request('kode_dept') == $d->kode_dept ? 'selected' : '' }}>
                                    {{ $d->nama_dept }}
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="chevron-down" class="w-4 h-4"></i>
                        </div>
                    </div>
                </div>

                <div class="sm:col-span-2">
                    <button type="submit"
                        class="w-full h-[36px] inline-flex items-center justify-center gap-1.5 px-4 rounded-lg bg-[#284c9a] hover:bg-blue-800 active:bg-blue-900 text-white text-xs font-bold transition shadow-xs cursor-pointer">
                        <i data-lucide="search" class="w-3.5 h-3.5"></i>
                        <span>Cari</span>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- DATA TABLE CONTAINER (STANDAR V2 THEAD DUA BARIS & KOMPAK ~36px) -->
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-2xs overflow-hidden transition-colors duration-200">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left border-collapse whitespace-nowrap">
                <thead>
                    <!-- Row 1: Title Strip (colspan=8) -->
                    <tr class="bg-[#284c9a] text-white">
                        <th colspan="8" class="px-4 py-2.5 bg-[#284c9a] text-white border-0">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="receipt" class="w-4 h-4 text-blue-200"></i>
                                    <span class="text-xs font-bold uppercase tracking-wider text-white">DATA BUKTI PERMINTAAN BARANG (BPB)</span>
                                </div>
                                <button type="button" id="btnCreate"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md bg-white hover:bg-blue-50 text-[#284c9a] text-xs font-bold transition shadow-xs cursor-pointer">
                                    <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                                    <span>Tambah Data</span>
                                </button>
                            </div>
                        </th>
                    </tr>
                    <!-- Row 2: Column Headers -->
                    <tr class="bg-[#1e3a78] text-white text-[11px] border-none">
                        <th class="py-2.5 px-3 text-center w-28 font-bold uppercase tracking-wider text-white/90 border-none">NO. BUKTI</th>
                        <th class="py-2.5 px-3 text-center font-bold uppercase tracking-wider text-white/90 border-none w-28">TANGGAL</th>
                        <th class="py-2.5 px-3 font-bold uppercase tracking-wider text-white/90 border-none">DEPARTEMEN</th>
                        <th class="py-2.5 px-3 text-center font-bold uppercase tracking-wider text-white/90 border-none w-20">CABANG</th>
                        <th class="py-2.5 px-3 font-bold uppercase tracking-wider text-white/90 border-none">YANG MENGAJUKAN</th>
                        <th class="py-2.5 px-3 text-center font-bold uppercase tracking-wider text-white/90 border-none w-20">GUDANG</th>
                        <th class="py-2.5 px-3 text-center font-bold uppercase tracking-wider text-white/90 border-none w-24">STATUS</th>
                        <th class="py-2.5 px-3 text-center font-bold uppercase tracking-wider text-white/90 border-none w-28">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 bg-white dark:bg-slate-900">
                    @forelse ($bpb as $d)
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition">
                            <!-- No Bukti -->
                            <td class="py-1.5 px-3 text-center font-bold font-mono-num text-[#284c9a] dark:text-blue-400">
                                {{ $d->no_bpb }}
                            </td>
                            <!-- Tanggal -->
                            <td class="py-1.5 px-3 text-center text-slate-600 dark:text-slate-400 font-mono-num">
                                {{ !empty($d->tanggal) ? DateToIndo($d->tanggal) : '-' }}
                            </td>
                            <!-- Dept -->
                            <td class="py-1.5 px-3 font-medium text-slate-800 dark:text-slate-200">
                                {{ $d->nama_dept }}
                            </td>
                            <!-- Cabang -->
                            <td class="py-1.5 px-3 text-center font-mono-num text-slate-700 dark:text-slate-300">
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10.5px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                                    {{ $d->kode_cabang }}
                                </span>
                            </td>
                            <!-- User -->
                            <td class="py-1.5 px-3 text-slate-700 dark:text-slate-300">
                                {{ $d->nama_user }}
                            </td>
                            <!-- Approve Gudang -->
                            <td class="py-1.5 px-3 text-center">
                                @if ($d->approve_gudang == '1')
                                    <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-emerald-100 text-emerald-600 dark:bg-emerald-950/60 dark:text-emerald-400 shadow-2xs" title="Disetujui Gudang">
                                        <i data-lucide="check" class="w-3 h-3"></i>
                                    </span>
                                @else
                                    <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-amber-100 text-amber-600 dark:bg-amber-950/60 dark:text-amber-400 shadow-2xs" title="Menunggu Gudang">
                                        <i data-lucide="clock" class="w-3 h-3"></i>
                                    </span>
                                @endif
                            </td>
                            <!-- Status -->
                            <td class="py-1.5 px-3 text-center">
                                @if ($d->total_serah_terima == $d->total_bpb)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                                        Selesai
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 dark:bg-rose-950/50 dark:text-rose-300 border border-rose-200 dark:border-rose-800">
                                        Proses
                                    </span>
                                @endif
                            </td>
                            <!-- Aksi (Kompak w-6 h-6, icon w-3 h-3) -->
                            <td class="py-1.5 px-3 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    @if (Auth::user()->id == '67' && $d->approve_gudang == '0')
                                        <a href="#" class="btnApprove w-6 h-6 rounded-md bg-emerald-50 hover:bg-emerald-100 text-emerald-600 dark:bg-emerald-950/40 dark:hover:bg-emerald-900/60 dark:text-emerald-400 flex items-center justify-center transition shadow-2xs"
                                            data-approve="1"
                                            no_bpb="{{ Crypt::encrypt($d->no_bpb) }}"
                                            title="Approve Gudang">
                                            <i data-lucide="check-circle" class="w-3 h-3"></i>
                                        </a>
                                    @endif

                                    @if (empty($d->tanggal_pembelian))
                                        <a href="#" class="btnEdit w-6 h-6 rounded-md bg-amber-50 hover:bg-amber-100 text-amber-600 dark:bg-amber-950/40 dark:hover:bg-amber-900/60 dark:text-amber-400 flex items-center justify-center transition shadow-2xs"
                                            no_bpb="{{ Crypt::encrypt($d->no_bpb) }}"
                                            title="Edit BPB">
                                            <i data-lucide="edit-3" class="w-3 h-3"></i>
                                        </a>
                                    @endif

                                    <a href="#" class="btnShow w-6 h-6 rounded-md bg-blue-50 hover:bg-blue-100 text-blue-600 dark:bg-blue-950/40 dark:hover:bg-blue-900/60 dark:text-blue-400 flex items-center justify-center transition shadow-2xs"
                                        no_bpb="{{ Crypt::encrypt($d->no_bpb) }}"
                                        title="Lihat Detail & Serah Terima">
                                        <i data-lucide="file-text" class="w-3 h-3"></i>
                                    </a>

                                    @if ($d->approve_gudang == '0')
                                        <form method="POST" class="inline-flex m-0 p-0 deleteform"
                                            action="{{ route('bpb.delete', Crypt::encrypt($d->no_bpb)) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="delete-confirm w-6 h-6 rounded-md bg-rose-50 hover:bg-rose-100 text-rose-600 dark:bg-rose-950/40 dark:hover:bg-rose-900/60 dark:text-rose-400 flex items-center justify-center transition shadow-2xs cursor-pointer border-0 p-0"
                                                title="Hapus Data">
                                                <i data-lucide="trash-2" class="w-3 h-3"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-10 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center gap-1.5">
                                    <i data-lucide="receipt-off" class="w-8 h-8 text-slate-300 dark:text-slate-600"></i>
                                    <span class="text-xs font-semibold">Data Bukti Permintaan Barang tidak ditemukan</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- PAGINATION (KOMPONEN RESMI V2) -->
        @if ($bpb->hasPages())
            <div class="px-4 py-3 border-t border-slate-100 dark:border-slate-800/80 bg-slate-50/50 dark:bg-slate-900/50">
                {{ $bpb->links('v2.components.pagination') }}
            </div>
        @endif
    </div>
</div>

<!-- MODAL FORM & DETAIL (STANDAR V2 STATIC BACKDROP) -->
<div id="modal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs hidden transition-opacity">
    <div id="modalDialog" class="relative w-full max-w-5xl max-h-[90vh] bg-white dark:bg-slate-900 rounded-xl shadow-2xl border border-slate-200 dark:border-slate-800 flex flex-col overflow-hidden transition-all duration-150 transform scale-100">
        <!-- Header -->
        <div class="px-5 py-3.5 border-b border-slate-200/80 dark:border-slate-800 flex items-center justify-between shrink-0 bg-white dark:bg-slate-900">
            <div class="flex items-center gap-2">
                <i data-lucide="receipt" class="w-4 h-4 text-[#284c9a] dark:text-blue-400"></i>
                <h4 class="modal-title text-sm font-bold text-slate-800 dark:text-slate-100">Form Bukti Permintaan Barang</h4>
            </div>
            <button type="button" id="btnModalClose" class="w-7 h-7 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition cursor-pointer">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>
        <!-- Body -->
        <div class="p-5 overflow-y-auto" id="loadmodal">
            <!-- Dynamic Content -->
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

        $(".flatpickr-date").flatpickr({
            dateFormat: "Y-m-d"
        });

        const $modal = $("#modal");
        const $modalDialog = $("#modalDialog");

        function openModal(title) {
            $(".modal-title").text(title);
            $modal.removeClass("hidden");
            $("body").addClass("overflow-hidden");
        }

        function closeModal() {
            $modal.addClass("hidden");
            $("body").removeClass("overflow-hidden");
            $("#loadmodal").html("");
        }

        $("#btnModalClose").on("click", function() {
            closeModal();
        });

        // Static Backdrop: bounce animation on outer click or Escape
        $modal.on("click", function(e) {
            if ($(e.target).is($modal)) {
                $modalDialog.addClass("scale-[1.015] shadow-2xl");
                setTimeout(() => {
                    $modalDialog.removeClass("scale-[1.015] shadow-2xl");
                }, 150);
            }
        });

        $(document).on("keydown", function(e) {
            if (e.key === "Escape" && !$modal.hasClass("hidden")) {
                $modalDialog.addClass("scale-[1.015] shadow-2xl");
                setTimeout(() => {
                    $modalDialog.removeClass("scale-[1.015] shadow-2xl");
                }, 150);
            }
        });

        function loadingSpinner(text = "Memuat data...") {
            return `
                <div class="py-12 flex flex-col items-center justify-center gap-2 text-slate-400">
                    <svg class="animate-spin h-6 w-6 text-[#284c9a]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-xs font-semibold">${text}</span>
                </div>
            `;
        }

        $("#btnCreate").on("click", function(e) {
            e.preventDefault();
            openModal("Tambah Data Bukti Permintaan Barang");
            $("#loadmodal").html(loadingSpinner("Memuat formulir tambah BPB..."));
            $("#loadmodal").load(`/bpb/create`);
        });

        $(".btnEdit").on("click", function(e) {
            e.preventDefault();
            const no_bpb = $(this).attr("no_bpb");
            openModal("Edit Bukti Permintaan Barang");
            $("#loadmodal").html(loadingSpinner("Memuat data BPB..."));
            $("#loadmodal").load(`/bpb/${no_bpb}/edit`);
        });

        $(".btnShow").on("click", function(e) {
            e.preventDefault();
            const no_bpb = $(this).attr("no_bpb");
            openModal("Detail BPB & Serah Terima");
            $("#loadmodal").html(loadingSpinner("Memuat rincian BPB..."));
            $("#loadmodal").load(`/bpb/${no_bpb}/show`);
        });

        $(".btnApprove").on("click", function(e) {
            e.preventDefault();
            let no_bpb = $(this).attr("no_bpb");
            let approve = $(this).attr("data-approve");

            Swal.fire({
                title: 'Approve BPB?',
                text: 'Pastikan data BPB sudah benar sebelum menyetujui',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Approve',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#16a34a',
                cancelButtonColor: '#e11d48',
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Memproses...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                            $.ajax({
                                url: `/bpb/${no_bpb}/storeapprove`,
                                type: 'POST',
                                data: {
                                    approve: approve,
                                    _token: $('meta[name="csrf-token"]').attr('content')
                                },
                                success: function(res) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil!',
                                        text: res.message,
                                        timer: 1500,
                                        showConfirmButton: false
                                    }).then(() => location.reload());
                                },
                                error: function(xhr) {
                                    Swal.fire(
                                        'Gagal!',
                                        xhr.responseJSON?.message ?? 'Terjadi kesalahan sistem',
                                        'error'
                                    );
                                }
                            });
                        }
                    });
                }
            });
        });
    });
</script>
@endpush
