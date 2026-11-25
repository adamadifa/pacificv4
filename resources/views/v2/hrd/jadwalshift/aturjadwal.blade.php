@extends('v2.layouts.app')
@section('titlepage', 'Atur Jadwal Shift')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">HRD</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <a href="{{ route('jadwalshift.index') }}" class="text-slate-400 hover:text-[#284c9a] transition">Jadwal Shift</a>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Atur Jadwal</span>
    </div>
@endsection

@section('content')
<style>
    .jadwalshift-3col-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 14px;
        align-items: start;
    }

    @media (max-width: 1023px) {
        .jadwalshift-3col-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="space-y-3.5 w-full max-w-7xl">

    <!-- PAGE TITLE & ACTIONS BAR -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <div class="flex items-center gap-2 flex-wrap">
                <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Atur Jadwal Shift</h3>
                <span class="px-2 py-0.5 rounded-md bg-blue-50 dark:bg-blue-950/60 text-[#284c9a] dark:text-blue-300 text-[11px] font-bold font-mono-num">
                    {{ DateToIndo($jadwalshift->dari) }} s/d {{ DateToIndo($jadwalshift->sampai) }}
                </span>
            </div>
            <p class="text-[11px] text-slate-400 mt-0.5">Penetapan karyawan untuk Shift 1, Shift 2, dan Shift 3.</p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('jadwalshift.index') }}"
                class="h-[34px] px-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 transition inline-flex items-center gap-1.5">
                <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                <span>Kembali</span>
            </a>
            <button type="button" id="gantishift" kode_jadwalshift="{{ Crypt::encrypt($jadwalshift->kode_jadwalshift) }}"
                class="h-[34px] px-3.5 rounded-lg bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold shadow-xs transition inline-flex items-center gap-1.5 cursor-pointer">
                <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i>
                <span>Ganti Shift</span>
            </button>
        </div>
    </div>

    <!-- 3 SHIFT COLUMNS -->
    <div class="jadwalshift-3col-grid">
        @foreach ([1 => 'Shift 1', 2 => 'Shift 2', 3 => 'Shift 3'] as $shiftNum => $shiftLabel)
            <div class="min-w-0 rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-2xs overflow-hidden">
                <div class="px-3.5 py-2 bg-[#284c9a] flex items-center justify-between text-white">
                    <div class="flex items-center gap-2">
                        <i data-lucide="clock" class="w-4 h-4 text-blue-200"></i>
                        <h5 class="text-xs font-bold text-white uppercase tracking-wider">{{ $shiftLabel }}</h5>
                    </div>
                    <button type="button" shift="{{ $shiftNum }}"
                        class="aturshift h-[28px] px-3 rounded-lg bg-white text-[#284c9a] hover:bg-blue-50 text-xs font-bold shadow-xs transition inline-flex items-center gap-1 cursor-pointer">
                        <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                        <span>Atur {{ $shiftLabel }}</span>
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left whitespace-nowrap" id="tabelshift{{ $shiftNum }}">
                        <thead class="bg-[#1e3a78] text-white/90 text-[11px] font-bold uppercase tracking-wider">
                            <tr>
                                <th class="py-2 px-3">NIK</th>
                                <th class="py-2 px-3">NAMA KARYAWAN</th>
                                <th class="py-2 px-3">GRUP</th>
                                <th class="py-2 px-3 text-center w-12">#</th>
                            </tr>
                        </thead>
                        <tbody id="loadshift{{ $shiftNum }}" class="divide-y divide-slate-100 dark:divide-slate-800">
                            <tr>
                                <td colspan="4" class="py-6 px-3 text-center text-xs text-slate-400">Memuat data...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- ================= MODAL ATUR / GANTI SHIFT (STATIC BACKDROP) ================= -->
<div id="modalAturJadwal" class="fixed inset-0 z-50 hidden">
    <div id="modalAturJadwalBackdrop" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4 overflow-y-auto">
        <div id="modalAturJadwalDialog"
            class="relative w-full max-w-4xl bg-white dark:bg-slate-900 rounded-xl shadow-2xl border border-slate-200/80 dark:border-slate-800 transform transition-all duration-150 my-auto">
            <!-- Modal Header Bersih -->
            <div class="flex items-center justify-between px-4 py-3 bg-white dark:bg-slate-900 border-b border-slate-200/80 dark:border-slate-800 rounded-t-xl">
                <div class="flex items-center gap-2">
                    <i data-lucide="calendar-clock" class="w-4 h-4 text-[#284c9a] dark:text-blue-400"></i>
                    <h5 id="modalAturJadwalTitle" class="text-sm font-bold text-slate-800 dark:text-slate-100">Atur Shift</h5>
                </div>
                <button type="button" id="btnCloseModalAturJadwal"
                    class="w-7 h-7 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition cursor-pointer">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            <!-- Modal Body -->
            <div id="loadmodalAturJadwal" class="p-4 sm:p-5"></div>
        </div>
    </div>
</div>
@endsection

@push('myscript')
<script>
    $(function() {
        if (typeof lucide !== 'undefined') lucide.createIcons();

        const $modal = $('#modalAturJadwal');
        const $dialog = $('#modalAturJadwalDialog');
        const $backdrop = $('#modalAturJadwalBackdrop');
        const $title = $('#modalAturJadwalTitle');
        const $content = $('#loadmodalAturJadwal');

        function openModal() {
            $modal.removeClass('hidden');
            $('body').addClass('overflow-hidden');
        }

        function closeModal() {
            $modal.addClass('hidden');
            $('body').removeClass('overflow-hidden');
            $content.html('');
        }

        window.closeModalAturJadwal = closeModal;

        $('#btnCloseModalAturJadwal').on('click', closeModal);

        $backdrop.on('click', function(e) {
            e.stopPropagation();
            $dialog.addClass('scale-[1.015]');
            setTimeout(() => $dialog.removeClass('scale-[1.015]'), 150);
        });

        function showLoading() {
            $content.html(`
                <div class="py-10 flex flex-col items-center justify-center gap-2">
                    <svg class="animate-spin h-6 w-6 text-[#284c9a]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-xs font-semibold text-slate-500">Memuat data...</span>
                </div>
            `);
        }

        function loadshift(shift, kode_jadwal) {
            let kode_jadwalshift = "{{ $jadwalshift->kode_jadwalshift }}";
            $("#loadshift" + shift).html(`<tr><td colspan="4" class="py-6 px-3 text-center text-xs text-slate-400">Memuat data...</td></tr>`);
            $.ajax({
                type: 'POST',
                url: '/jadwalshift/getshift',
                data: {
                    _token: "{{ csrf_token() }}",
                    kode_jadwal: kode_jadwal,
                    kode_jadwalshift: kode_jadwalshift
                },
                cache: false,
                success: function(respond) {
                    $("#loadshift" + shift).html(respond);
                }
            });
        }

        loadshift(1, "JD002");
        loadshift(2, "JD003");
        loadshift(3, "JD004");

        $(".aturshift").on('click', function(e) {
            e.preventDefault();
            var shift = $(this).attr('shift');
            var kode_jadwalshift = "{{ Crypt::encrypt($jadwalshift->kode_jadwalshift) }}";
            $title.text("Atur Shift " + shift);
            openModal();
            showLoading();
            $content.load(`/jadwalshift/${shift}/${kode_jadwalshift}/aturshift`, function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });

        $(document).on('click', '.delete', function(e) {
            e.preventDefault();
            const nik = $(this).attr('nik');
            const kode_jadwalshift = "{{ $jadwalshift->kode_jadwalshift }}";
            Swal.fire({
                title: 'Hapus Shift?',
                icon: 'warning',
                text: 'Anda yakin menghapus karyawan dari shift ini?',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'POST',
                        url: `/jadwalshift/deleteshift`,
                        data: {
                            _token: "{{ csrf_token() }}",
                            nik: nik,
                            kode_jadwalshift: kode_jadwalshift
                        },
                        cache: false,
                        success: function(respond) {
                            if (respond.success == true) {
                                loadshift(1, "JD002");
                                loadshift(2, "JD003");
                                loadshift(3, "JD004");
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

        $("#gantishift").on('click', function(e) {
            e.preventDefault();
            const kode_jadwalshift = "{{ $jadwalshift->kode_jadwalshift }}";
            $title.text("Ganti Shift");
            openModal();
            showLoading();
            $content.load(`/jadwalshift/${kode_jadwalshift}/gantishift`, function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });
    });
</script>
@endpush
