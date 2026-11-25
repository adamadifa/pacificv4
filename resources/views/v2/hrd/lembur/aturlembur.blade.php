@extends('v2.layouts.app')
@section('titlepage', 'Atur Lembur')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">HRD</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <a href="{{ route('lembur.index') }}" class="text-slate-400 hover:text-[#284c9a] transition">Lembur</a>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Atur Lembur</span>
    </div>
@endsection

@section('content')
<style>
    .lembur-atur-grid {
        display: grid;
        grid-template-columns: 340px minmax(0, 1fr);
        gap: 14px;
        align-items: start;
    }

    @media (max-width: 1023px) {
        .lembur-atur-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="space-y-3.5 w-full max-w-7xl">

    <!-- PAGE TITLE BAR -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Atur Lembur</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Mengatur karyawan untuk rencana lembur.</p>
        </div>
        <a href="{{ route('lembur.index') }}"
            class="h-[34px] px-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 transition inline-flex items-center gap-1.5 self-start sm:self-auto">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
            <span>Kembali</span>
        </a>
    </div>

    <div class="lembur-atur-grid">
        <!-- Kolom Kiri: Detail Lembur -->
        <div class="min-w-0 rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-2xs overflow-hidden">
            <div class="px-3.5 py-2 bg-[#284c9a] flex items-center justify-between text-white">
                <div class="flex items-center gap-2">
                    <i data-lucide="clipboard-list" class="w-4 h-4 text-blue-200"></i>
                    <h5 class="text-xs font-bold text-white uppercase tracking-wider">Detail Lembur</h5>
                </div>
            </div>

            <div class="p-3.5 divide-y divide-dashed divide-slate-200 dark:divide-slate-800 text-xs">
                <div class="py-2 flex items-center justify-between gap-2">
                    <span class="text-slate-400">Kode Lembur</span>
                    <span class="font-mono-num font-bold text-[#284c9a] dark:text-blue-400">{{ $lembur->kode_lembur }}</span>
                </div>
                <div class="py-2 flex items-center justify-between gap-2">
                    <span class="text-slate-400">Tanggal</span>
                    <span class="font-bold text-slate-800 dark:text-slate-100">{{ DateToIndo($lembur->tanggal) }}</span>
                </div>
                <div class="py-2 flex items-center justify-between gap-2">
                    <span class="text-slate-400">Mulai</span>
                    <span class="font-mono-num font-semibold text-slate-700 dark:text-slate-200">{{ date('d-m-Y H:i', strtotime($lembur->tanggal_dari)) }}</span>
                </div>
                <div class="py-2 flex items-center justify-between gap-2">
                    <span class="text-slate-400">Selesai</span>
                    <span class="font-mono-num font-semibold text-slate-700 dark:text-slate-200">{{ date('d-m-Y H:i', strtotime($lembur->tanggal_sampai)) }}</span>
                </div>
                <div class="py-2 flex items-center justify-between gap-2">
                    <span class="text-slate-400">Istirahat</span>
                    <span>
                        @if ($lembur->istirahat == 1)
                            <span class="px-2 py-0.5 rounded bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 text-[10.5px] font-bold">Ya</span>
                        @else
                            <span class="px-2 py-0.5 rounded bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 text-[10.5px] font-bold">Tidak</span>
                        @endif
                    </span>
                </div>
                <div class="py-2 flex items-center justify-between gap-2">
                    <span class="text-slate-400">Jumlah Jam</span>
                    @php
                        $istirahat = $lembur->istirahat == 1 ? 1 : 0;
                        $jmljam = hitungjamdesimal($lembur->tanggal_dari, $lembur->tanggal_sampai) - $istirahat;
                    @endphp
                    <span class="font-mono-num font-bold text-slate-800 dark:text-slate-100">{{ $jmljam }} Jam</span>
                </div>
                <div class="py-2 flex items-center justify-between gap-2">
                    <span class="text-slate-400">Departemen</span>
                    <span class="font-semibold text-slate-700 dark:text-slate-200 text-right">{{ $lembur->nama_dept }}</span>
                </div>
                <div class="py-2 flex items-start justify-between gap-2">
                    <span class="text-slate-400 shrink-0">Keterangan</span>
                    <span class="font-semibold text-slate-700 dark:text-slate-200 text-right">{{ $lembur->keterangan ?: '-' }}</span>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Daftar Karyawan Lembur -->
        <div class="min-w-0 rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-2xs overflow-hidden">
            <div class="px-3.5 py-2 bg-[#284c9a] flex items-center justify-between text-white">
                <div class="flex items-center gap-2">
                    <i data-lucide="users" class="w-4 h-4 text-blue-200"></i>
                    <h5 class="text-xs font-bold text-white uppercase tracking-wider">Karyawan Lembur</h5>
                </div>
                @can('lembur.setlembur')
                    <button type="button" id="btnTambahKaryawanLembur"
                        class="h-[28px] px-3 rounded-lg bg-white text-[#284c9a] hover:bg-blue-50 text-xs font-bold shadow-xs transition inline-flex items-center gap-1 cursor-pointer">
                        <i data-lucide="user-plus" class="w-3.5 h-3.5"></i>
                        <span>Tambah Karyawan</span>
                    </button>
                @endcan
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left whitespace-nowrap" id="tableKaryawanLembur">
                    <thead class="bg-[#1e3a78] text-white/90 text-[11px] font-bold uppercase tracking-wider">
                        <tr>
                            <th class="py-2 px-3">NIK</th>
                            <th class="py-2 px-3">NAMA KARYAWAN</th>
                            <th class="py-2 px-3">DEPT</th>
                            <th class="py-2 px-3">GRUP</th>
                            <th class="py-2 px-3 text-center w-14">#</th>
                        </tr>
                    </thead>
                    <tbody id="loadlemburkaryawan" class="divide-y divide-slate-100 dark:divide-slate-800">
                        <tr>
                            <td colspan="5" class="py-8 px-3 text-center text-xs text-slate-400">Memuat data...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- ================= MODAL ATUR KARYAWAN LEMBUR (STATIC BACKDROP) ================= -->
<div id="modalAturKaryawanLembur" class="fixed inset-0 z-50 hidden">
    <div id="modalAturKaryawanLemburBackdrop" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4 overflow-y-auto">
        <div id="modalAturKaryawanLemburDialog"
            class="relative w-full max-w-4xl bg-white dark:bg-slate-900 rounded-xl shadow-2xl border border-slate-200/80 dark:border-slate-800 transform transition-all duration-150 my-auto">
            <!-- Modal Header Bersih -->
            <div class="flex items-center justify-between px-4 py-3 bg-white dark:bg-slate-900 border-b border-slate-200/80 dark:border-slate-800 rounded-t-xl">
                <div class="flex items-center gap-2">
                    <i data-lucide="user-plus" class="w-4 h-4 text-[#284c9a] dark:text-blue-400"></i>
                    <h5 class="text-sm font-bold text-slate-800 dark:text-slate-100">Input Karyawan Lembur</h5>
                </div>
                <button type="button" id="btnCloseModalAturKaryawanLembur"
                    class="w-7 h-7 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition cursor-pointer">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            <!-- Modal Body -->
            <div id="loadmodalAturKaryawanLembur" class="p-4 sm:p-5"></div>
        </div>
    </div>
</div>
@endsection

@push('myscript')
<script>
    $(function() {
        if (typeof lucide !== 'undefined') lucide.createIcons();

        const $modal = $('#modalAturKaryawanLembur');
        const $dialog = $('#modalAturKaryawanLemburDialog');
        const $backdrop = $('#modalAturKaryawanLemburBackdrop');
        const $content = $('#loadmodalAturKaryawanLembur');

        function loadlemburkaryawan() {
            const kode_lembur = "{{ Crypt::encrypt($lembur->kode_lembur) }}";
            $("#loadlemburkaryawan").html(`<tr><td colspan="5" class="py-8 px-3 text-center text-xs text-slate-400">Memuat data...</td></tr>`);
            $("#loadlemburkaryawan").load(`/lembur/${kode_lembur}/getkaryawanlembur`);
        }

        window.loadlemburkaryawan = loadlemburkaryawan;
        loadlemburkaryawan();

        function openModal() {
            $modal.removeClass('hidden');
            $('body').addClass('overflow-hidden');
        }

        function closeModal() {
            $modal.addClass('hidden');
            $('body').removeClass('overflow-hidden');
            $content.html('');
            loadlemburkaryawan();
        }

        $('#btnCloseModalAturKaryawanLembur').on('click', closeModal);

        $backdrop.on('click', function(e) {
            e.stopPropagation();
            $dialog.addClass('scale-[1.015]');
            setTimeout(() => $dialog.removeClass('scale-[1.015]'), 150);
        });

        $("#btnTambahKaryawanLembur").on('click', function(e) {
            e.preventDefault();
            const kode_lembur = "{{ Crypt::encrypt($lembur->kode_lembur) }}";
            openModal();
            $content.html(`
                <div class="py-10 flex flex-col items-center justify-center gap-2">
                    <svg class="animate-spin h-6 w-6 text-[#284c9a]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-xs font-semibold text-slate-500">Memuat daftar karyawan...</span>
                </div>
            `);
            $content.load(`/lembur/${kode_lembur}/aturkaryawan`, function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });

        $(document).on('click', '#tableKaryawanLembur .delete', function(e) {
            e.preventDefault();
            const kode_lembur = "{{ $lembur->kode_lembur }}";
            const nik = $(this).attr("nik");
            Swal.fire({
                title: "Hapus Karyawan?",
                text: "Karyawan akan dikeluarkan dari daftar lembur ini.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#e11d48",
                cancelButtonColor: "#64748b",
                confirmButtonText: "Ya, Hapus",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: `/lembur/deletekaryawanlembur`,
                        data: {
                            _token: "{{ csrf_token() }}",
                            kode_lembur: kode_lembur,
                            nik: nik
                        },
                        cache: false,
                        success: function(respond) {
                            if (respond.success == true) {
                                loadlemburkaryawan();
                            } else {
                                Swal.fire({
                                    title: "Oops!",
                                    text: respond.message,
                                    icon: "warning",
                                    showConfirmButton: true,
                                });
                            }
                        }
                    });
                }
            });
        });
    });
</script>
@endpush
