@extends('v2.layouts.app')
@section('titlepage', 'Atur Hari Libur')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">HRD</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <a href="{{ route('harilibur.index') }}" class="text-slate-400 hover:text-[#284c9a] transition">Hari Libur</a>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Atur Karyawan</span>
    </div>
@endsection

@section('content')
<style>
    .harilibur-atur-grid {
        display: grid;
        grid-template-columns: 340px minmax(0, 1fr);
        gap: 14px;
        align-items: start;
    }

    @media (max-width: 1023px) {
        .harilibur-atur-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="space-y-3.5 w-full max-w-7xl">

    <!-- PAGE TITLE BAR -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Atur Hari Libur</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Kelola penetapan dan daftar karyawan yang diliburkan.</p>
        </div>
        <a href="{{ route('harilibur.index') }}"
            class="h-[34px] px-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 transition inline-flex items-center gap-1.5 self-start sm:self-auto">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
            <span>Kembali</span>
        </a>
    </div>

    <div class="harilibur-atur-grid">
        <!-- Kolom Kiri: Informasi Hari Libur -->
        <div class="min-w-0 rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-2xs overflow-hidden">
            <div class="px-3.5 py-2 bg-[#284c9a] flex items-center justify-between text-white">
                <div class="flex items-center gap-2">
                    <i data-lucide="calendar-heart" class="w-4 h-4 text-blue-200"></i>
                    <h5 class="text-xs font-bold text-white uppercase tracking-wider">Informasi Hari Libur</h5>
                </div>
                @if ($harilibur->status === '1')
                    <span class="px-2 py-0.5 rounded bg-emerald-500 text-white text-[10.5px] font-bold">Disetujui</span>
                @else
                    <span class="px-2 py-0.5 rounded bg-amber-400 text-slate-900 text-[10.5px] font-bold">Pending</span>
                @endif
            </div>

            <div class="p-3.5 divide-y divide-dashed divide-slate-200 dark:divide-slate-800 text-xs">
                <div class="py-2 flex items-center justify-between gap-2">
                    <span class="text-slate-400">Kode Libur</span>
                    <span class="font-mono-num font-bold text-[#284c9a] dark:text-blue-400">{{ $harilibur->kode_libur }}</span>
                </div>
                <div class="py-2 flex items-center justify-between gap-2">
                    <span class="text-slate-400">Tanggal</span>
                    <span class="font-bold text-slate-800 dark:text-slate-100">{{ DateToIndo($harilibur->tanggal) }}</span>
                </div>
                <div class="py-2 flex items-center justify-between gap-2">
                    <span class="text-slate-400">Kategori</span>
                    <span class="px-2 py-0.5 rounded bg-sky-50 dark:bg-sky-950/60 text-sky-700 dark:text-sky-300 text-[11px] font-bold">
                        {{ $harilibur->nama_kategori }}
                    </span>
                </div>
                @if (!empty($harilibur->tanggal_diganti))
                    <div class="py-2 flex items-center justify-between gap-2">
                        <span class="text-slate-400">Pengganti</span>
                        <span class="font-semibold text-[#284c9a] dark:text-blue-400">{{ DateToIndo($harilibur->tanggal_diganti) }}</span>
                    </div>
                @endif
                @if (!empty($harilibur->tanggal_limajam))
                    <div class="py-2 flex items-center justify-between gap-2">
                        <span class="text-slate-400">Tanggal 5 Jam</span>
                        <span class="font-semibold text-amber-600 dark:text-amber-400">{{ DateToIndo($harilibur->tanggal_limajam) }}</span>
                    </div>
                @endif
                <div class="py-2 flex items-center justify-between gap-2">
                    <span class="text-slate-400">Cabang</span>
                    <span class="font-semibold text-slate-700 dark:text-slate-200 text-right">{{ $harilibur->nama_cabang }}</span>
                </div>
                @if ($harilibur->kode_cabang == 'PST')
                    <div class="py-2 flex items-center justify-between gap-2">
                        <span class="text-slate-400">Departemen</span>
                        <span class="font-semibold text-slate-700 dark:text-slate-200 text-right">{{ $harilibur->nama_dept ?: 'Semua Dept' }}</span>
                    </div>
                @endif
                <div class="py-2 flex items-start justify-between gap-2">
                    <span class="text-slate-400 shrink-0">Keterangan</span>
                    <span class="font-semibold text-slate-700 dark:text-slate-200 text-right">{{ $harilibur->keterangan ?: '-' }}</span>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Filter + Daftar Karyawan yang Diliburkan -->
        <div class="min-w-0 space-y-3">
            <!-- Filter Pencarian (Di Luar Card Tabel, Lebar Sejajar) -->
            <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-3 shadow-2xs">
                <div class="flex items-center gap-2 w-full">
                    <div class="relative flex-1 min-w-0">
                        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                            <i data-lucide="search" class="w-4 h-4"></i>
                        </div>
                        <input type="text" id="searchKaryawan" placeholder="Cari NIK, nama, atau grup..." autocomplete="off"
                            class="w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                    </div>
                    <button type="button" id="btnResetSearch" title="Reset Pencarian"
                        class="h-[36px] w-[36px] shrink-0 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-500 hover:bg-slate-50 dark:hover:bg-slate-700 flex items-center justify-center transition cursor-pointer">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
            </div>

            <!-- Card Tabel Karyawan (Card Header & Table Header Menyatu) -->
            <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-2xs overflow-hidden">
                <div class="px-3.5 py-2 bg-[#284c9a] flex flex-wrap items-center justify-between gap-2 text-white">
                    <div class="flex items-center gap-2">
                        <i data-lucide="users" class="w-4 h-4 text-blue-200"></i>
                        <h5 class="text-xs font-bold text-white uppercase tracking-wider">Karyawan Diliburkan</h5>
                        <span class="px-2 py-0.5 rounded bg-white/15 text-white text-[10.5px] font-bold" id="countKaryawan">0 Karyawan</span>
                    </div>
                    @can('harilibur.setharilibur')
                        @if ($harilibur->status === '0')
                            <button type="button" id="btnTambahKaryawanLibur"
                                class="h-[28px] px-3 rounded-lg bg-white text-[#284c9a] hover:bg-blue-50 text-xs font-bold shadow-xs transition inline-flex items-center gap-1 cursor-pointer">
                                <i data-lucide="user-plus" class="w-3.5 h-3.5"></i>
                                <span>Tambah Karyawan</span>
                            </button>
                        @endif
                    @endcan
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left whitespace-nowrap" id="tableKaryawanLibur">
                        <thead class="bg-[#1e3a78] text-white/90 text-[11px] font-bold uppercase tracking-wider">
                            <tr>
                                <th class="py-2 px-3 text-center w-12">NO</th>
                                <th class="py-2 px-3">NIK</th>
                                <th class="py-2 px-3">NAMA KARYAWAN</th>
                                <th class="py-2 px-3">DEPT</th>
                                <th class="py-2 px-3">GRUP</th>
                                <th class="py-2 px-3 text-center w-14">#</th>
                            </tr>
                        </thead>
                        <tbody id="loadliburkaryawan" class="divide-y divide-slate-100 dark:divide-slate-800">
                            <tr>
                                <td colspan="6" class="py-8 px-3 text-center text-xs text-slate-400">Memuat data...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ================= MODAL ATUR KARYAWAN HARI LIBUR (STATIC BACKDROP) ================= -->
<div id="modalAturKaryawan" class="fixed inset-0 z-50 hidden">
    <div id="modalAturKaryawanBackdrop" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4 overflow-y-auto">
        <div id="modalAturKaryawanDialog"
            class="relative w-full max-w-4xl bg-white dark:bg-slate-900 rounded-xl shadow-2xl border border-slate-200/80 dark:border-slate-800 transform transition-all duration-150 my-auto">
            <!-- Modal Header Bersih -->
            <div class="flex items-center justify-between px-4 py-3 bg-white dark:bg-slate-900 border-b border-slate-200/80 dark:border-slate-800 rounded-t-xl">
                <div class="flex items-center gap-2">
                    <i data-lucide="user-plus" class="w-4 h-4 text-[#284c9a] dark:text-blue-400"></i>
                    <h5 class="text-sm font-bold text-slate-800 dark:text-slate-100">Input Karyawan Hari Libur</h5>
                </div>
                <button type="button" id="btnCloseModalAturKaryawan"
                    class="w-7 h-7 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition cursor-pointer">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            <!-- Modal Body -->
            <div id="loadmodalAturKaryawan" class="p-4 sm:p-5"></div>
        </div>
    </div>
</div>
@endsection

@push('myscript')
<script>
    $(function() {
        if (typeof lucide !== 'undefined') lucide.createIcons();

        const $modal = $('#modalAturKaryawan');
        const $dialog = $('#modalAturKaryawanDialog');
        const $backdrop = $('#modalAturKaryawanBackdrop');
        const $content = $('#loadmodalAturKaryawan');

        function updateKaryawanCount() {
            const rows = $("#loadliburkaryawan tr.row-karyawan");
            const visibleRows = $("#loadliburkaryawan tr.row-karyawan:visible");
            const total = rows.length;
            const visible = visibleRows.length;

            if ($("#searchKaryawan").val().trim() !== "") {
                $("#countKaryawan").text(`${visible} dari ${total} Karyawan`);
            } else {
                $("#countKaryawan").text(`${total} Karyawan`);
            }
        }

        function filterKaryawan(query) {
            let matched = 0;
            $("#loadliburkaryawan tr.row-karyawan").each(function() {
                const text = $(this).text().toLowerCase();
                if (text.indexOf(query) !== -1) {
                    $(this).show();
                    matched++;
                } else {
                    $(this).hide();
                }
            });

            if (matched === 0 && $("#loadliburkaryawan tr.row-karyawan").length > 0) {
                if ($("#noMatchRow").length === 0) {
                    $("#loadliburkaryawan").append(`
                        <tr id="noMatchRow">
                            <td colspan="6" class="py-6 px-3 text-center text-xs text-slate-400">
                                Tidak ditemukan karyawan yang cocok dengan pencarian.
                            </td>
                        </tr>
                    `);
                }
            } else {
                $("#noMatchRow").remove();
            }
            updateKaryawanCount();
        }

        function loadliburkaryawan() {
            const kode_libur = "{{ Crypt::encrypt($harilibur->kode_libur) }}";
            $("#loadliburkaryawan").html(`<tr><td colspan="6" class="py-8 px-3 text-center text-xs text-slate-400">Memuat data karyawan libur...</td></tr>`);
            $.ajax({
                type: 'GET',
                url: `/harilibur/${kode_libur}/getkaryawanlibur`,
                cache: false,
                success: function(html) {
                    $("#loadliburkaryawan").html(html);
                    updateKaryawanCount();
                    const searchVal = $("#searchKaryawan").val().toLowerCase();
                    if (searchVal) {
                        filterKaryawan(searchVal);
                    }
                },
                error: function() {
                    $("#loadliburkaryawan").html(`<tr><td colspan="6" class="py-8 px-3 text-center text-xs text-rose-500">Gagal memuat data karyawan libur.</td></tr>`);
                }
            });
        }

        window.loadliburkaryawan = loadliburkaryawan;
        loadliburkaryawan();

        $("#searchKaryawan").on('input keyup', function() {
            filterKaryawan($(this).val().toLowerCase().trim());
        });

        $("#btnResetSearch").on('click', function() {
            $("#searchKaryawan").val('');
            filterKaryawan('');
        });

        function openModal() {
            $modal.removeClass('hidden');
            $('body').addClass('overflow-hidden');
        }

        function closeModal() {
            $modal.addClass('hidden');
            $('body').removeClass('overflow-hidden');
            $content.html('');
            loadliburkaryawan();
        }

        $('#btnCloseModalAturKaryawan').on('click', closeModal);

        $backdrop.on('click', function(e) {
            e.stopPropagation();
            $dialog.addClass('scale-[1.015]');
            setTimeout(() => $dialog.removeClass('scale-[1.015]'), 150);
        });

        $("#btnTambahKaryawanLibur").on('click', function(e) {
            e.preventDefault();
            const kode_libur = "{{ Crypt::encrypt($harilibur->kode_libur) }}";
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
            $content.load(`/harilibur/${kode_libur}/aturkaryawan`, function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });

        $(document).on('click', '#tableKaryawanLibur .delete', function(e) {
            e.preventDefault();
            const kode_libur = "{{ $harilibur->kode_libur }}";
            const nik = $(this).attr("nik");
            const nama = $(this).attr("nama") || nik;

            Swal.fire({
                title: "Hapus Karyawan?",
                html: `Karyawan <b>${nama}</b> akan dikeluarkan dari daftar hari libur ini.`,
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
                        url: `/harilibur/deletekaryawanlibur`,
                        data: {
                            _token: "{{ csrf_token() }}",
                            kode_libur: kode_libur,
                            nik: nik
                        },
                        cache: false,
                        success: function(respond) {
                            if (respond.success == true) {
                                Swal.fire({
                                    title: "Berhasil!",
                                    text: "Karyawan berhasil dikeluarkan.",
                                    icon: "success",
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                                loadliburkaryawan();
                            } else {
                                Swal.fire({
                                    title: "Oops!",
                                    text: respond.message,
                                    icon: "warning",
                                    showConfirmButton: true,
                                });
                            }
                        },
                        error: function() {
                            Swal.fire({
                                title: "Error!",
                                text: "Terjadi kesalahan pada server saat menghapus data.",
                                icon: "error"
                            });
                        }
                    });
                }
            });
        });
    });
</script>
@endpush
