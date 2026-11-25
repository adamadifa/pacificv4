@extends('v2.layouts.app')
@section('titlepage', 'Jadwal Shift')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">HRD</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Jadwal Shift</span>
    </div>
@endsection

@section('content')
<div class="space-y-3.5 w-full max-w-4xl">

    <!-- PAGE TITLE BAR -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Jadwal Shift</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Mengelola data jadwal shift kerja karyawan.</p>
        </div>
    </div>

    <!-- DATA TABLE CARD (V2 COMPACT ~36PX) -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-2xs overflow-hidden">
        <!-- BARIS 1: STRIP JUDUL TABEL (bg-[#284c9a]) -->
        <div class="px-3.5 py-2 bg-[#284c9a] flex items-center justify-between text-white">
            <div class="flex items-center gap-2">
                <i data-lucide="calendar-clock" class="w-4 h-4 text-blue-200"></i>
                <h5 class="text-xs font-bold text-white uppercase tracking-wider">Data Jadwal Shift</h5>
            </div>
            @can('jadwalshift.create')
                <button type="button" id="btnCreateJadwalShift"
                    class="h-[28px] px-3 rounded-lg bg-white text-[#284c9a] hover:bg-blue-50 text-xs font-bold shadow-xs transition flex items-center gap-1 cursor-pointer">
                    <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                    <span>Tambah</span>
                </button>
            @endcan
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left whitespace-nowrap">
                <thead class="bg-[#1e3a78] text-white/90 text-[11px] font-bold uppercase tracking-wider">
                    <tr>
                        <th rowspan="2" class="py-2 px-3 align-middle">KODE</th>
                        <th colspan="2" class="py-1.5 px-3 text-center border-b border-l border-white/15">PERIODE</th>
                        <th rowspan="2" class="py-2 px-3 align-middle text-center border-l border-white/15 w-28">#</th>
                    </tr>
                    <tr>
                        <th class="py-1.5 px-3 text-center border-l border-white/15">DARI</th>
                        <th class="py-1.5 px-3 text-center border-l border-white/15">SAMPAI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse ($jadwalshift as $d)
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition-colors">
                            <td class="py-1.5 px-3 font-mono-num font-bold text-[#284c9a] dark:text-blue-400">{{ $d->kode_jadwalshift }}</td>
                            <td class="py-1.5 px-3 text-center font-mono-num text-slate-700 dark:text-slate-300">{{ DateToIndo($d->dari) }}</td>
                            <td class="py-1.5 px-3 text-center font-mono-num text-slate-700 dark:text-slate-300">{{ DateToIndo($d->sampai) }}</td>
                            <td class="py-1.5 px-3">
                                <div class="flex items-center justify-center gap-1">
                                    @can('jadwalshift.edit')
                                        <button type="button" kode_jadwalshift="{{ Crypt::encrypt($d->kode_jadwalshift) }}"
                                            class="btnEditJadwalShift w-6 h-6 rounded-md bg-emerald-50 hover:bg-emerald-100 text-emerald-600 dark:bg-emerald-950/50 dark:hover:bg-emerald-900/60 dark:text-emerald-400 flex items-center justify-center transition cursor-pointer"
                                            title="Edit">
                                            <i data-lucide="pencil" class="w-3 h-3"></i>
                                        </button>
                                    @endcan
                                    @can('jadwalshift.setjadwal')
                                        <a href="{{ route('jadwalshift.aturjadwal', Crypt::encrypt($d->kode_jadwalshift)) }}"
                                            class="w-6 h-6 rounded-md bg-blue-50 hover:bg-blue-100 text-[#284c9a] dark:bg-blue-950/50 dark:hover:bg-blue-900/60 dark:text-blue-400 flex items-center justify-center transition"
                                            title="Atur Jadwal">
                                            <i data-lucide="settings" class="w-3 h-3"></i>
                                        </a>
                                    @endcan
                                    @can('jadwalshift.delete')
                                        <form method="POST" name="deleteform" class="inline-flex m-0 p-0 deleteform"
                                            action="{{ route('jadwalshift.delete', Crypt::encrypt($d->kode_jadwalshift)) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="delete-confirm w-6 h-6 rounded-md bg-rose-50 hover:bg-rose-100 text-rose-600 dark:bg-rose-950/50 dark:hover:bg-rose-900/60 dark:text-rose-400 flex items-center justify-center transition cursor-pointer"
                                                title="Hapus">
                                                <i data-lucide="trash-2" class="w-3 h-3"></i>
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-10 px-4 text-center">
                                <div class="flex flex-col items-center justify-center gap-1.5 text-slate-400">
                                    <i data-lucide="calendar-off" class="w-8 h-8 text-slate-300 dark:text-slate-600"></i>
                                    <span class="text-xs font-semibold">Tidak ada data jadwal shift</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($jadwalshift->hasPages())
            <div class="px-4 py-3 border-t border-slate-100 dark:border-slate-800/80 bg-slate-50/50 dark:bg-slate-900/50">
                {{ $jadwalshift->links('v2.components.pagination') }}
            </div>
        @endif
    </div>
</div>

<!-- ================= MODAL JADWAL SHIFT (STATIC BACKDROP) ================= -->
<div id="modalJadwalShift" class="fixed inset-0 z-50 hidden">
    <div id="modalJadwalShiftBackdrop" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4 overflow-y-auto">
        <div id="modalJadwalShiftDialog"
            class="relative w-full max-w-md bg-white dark:bg-slate-900 rounded-xl shadow-2xl border border-slate-200/80 dark:border-slate-800 transform transition-all duration-150 my-auto">
            <!-- Modal Header Bersih -->
            <div class="flex items-center justify-between px-4 py-3 bg-white dark:bg-slate-900 border-b border-slate-200/80 dark:border-slate-800 rounded-t-xl">
                <div class="flex items-center gap-2">
                    <i data-lucide="calendar-clock" class="w-4 h-4 text-[#284c9a] dark:text-blue-400"></i>
                    <h5 id="modalJadwalShiftTitle" class="text-sm font-bold text-slate-800 dark:text-slate-100">Buat Jadwal Shift</h5>
                </div>
                <button type="button" id="btnCloseModalJadwalShift"
                    class="w-7 h-7 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition cursor-pointer">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            <!-- Modal Body -->
            <div id="loadmodalJadwalShift" class="p-4 sm:p-5"></div>
        </div>
    </div>
</div>
@endsection

@push('myscript')
<script>
    $(function() {
        if (typeof lucide !== 'undefined') lucide.createIcons();

        const $modal = $('#modalJadwalShift');
        const $dialog = $('#modalJadwalShiftDialog');
        const $backdrop = $('#modalJadwalShiftBackdrop');
        const $title = $('#modalJadwalShiftTitle');
        const $content = $('#loadmodalJadwalShift');

        function openModal() {
            $modal.removeClass('hidden');
            $('body').addClass('overflow-hidden');
        }

        function closeModal() {
            $modal.addClass('hidden');
            $('body').removeClass('overflow-hidden');
            $content.html('');
        }

        window.closeModalJadwalShift = closeModal;

        $('#btnCloseModalJadwalShift').on('click', closeModal);

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
                    <span class="text-xs font-semibold text-slate-500">Memuat formulir...</span>
                </div>
            `);
        }

        $('#btnCreateJadwalShift').on('click', function(e) {
            e.preventDefault();
            $title.text('Buat Jadwal Shift');
            openModal();
            showLoading();
            $content.load('/jadwalshift/create', function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });

        $(document).on('click', '.btnEditJadwalShift', function(e) {
            e.preventDefault();
            const kode_jadwalshift = $(this).attr('kode_jadwalshift');
            $title.text('Edit Jadwal Shift');
            openModal();
            showLoading();
            $content.load(`/jadwalshift/${kode_jadwalshift}/edit`, function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });
    });
</script>
@endpush
