@extends('v2.layouts.app')
@section('titlepage', 'Detail Penyesuaian Upah')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">HRD</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <a href="{{ route('penyupah.index') }}" class="text-slate-400 hover:text-[#284c9a] transition">Penyesuaian Upah</a>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Detail {{ $namabulan[$penyupah->bulan] }} {{ $penyupah->tahun }}</span>
    </div>
@endsection

@section('content')
<div class="space-y-3.5 w-full max-w-7xl">

    <!-- PAGE TITLE & ACTIONS BAR -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <div class="flex items-center gap-2 flex-wrap">
                <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">
                    Periode {{ $namabulan[$penyupah->bulan] }} {{ $penyupah->tahun }}
                </h3>
                <span class="px-2 py-0.5 rounded bg-blue-50 dark:bg-blue-950/60 text-[#284c9a] dark:text-blue-300 text-[11px] font-bold font-mono-num">
                    {{ $penyupah->kode_gaji }}
                </span>
            </div>
            <p class="text-[11px] text-slate-400 mt-0.5">Kelola penambahan dan pengurangan gaji karyawan untuk periode ini.</p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('penyupah.index') }}"
                class="h-[34px] px-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 transition inline-flex items-center gap-1.5">
                <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                <span>Kembali</span>
            </a>
            <a href="{{ route('penyupah.downloadtemplate', Crypt::encrypt($penyupah->kode_gaji)) }}"
                class="h-[34px] px-3 rounded-lg border border-emerald-200 dark:border-emerald-800 bg-emerald-50 dark:bg-emerald-950/40 text-xs font-bold text-emerald-700 dark:text-emerald-300 hover:bg-emerald-100 transition inline-flex items-center gap-1.5">
                <i data-lucide="file-spreadsheet" class="w-3.5 h-3.5"></i>
                <span>Download Template</span>
            </a>
            @can('penyupah.create')
                <button type="button" id="btnOpenImportModal"
                    class="h-[34px] px-3 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-xs transition inline-flex items-center gap-1.5 cursor-pointer">
                    <i data-lucide="upload" class="w-3.5 h-3.5"></i>
                    <span>Import Excel</span>
                </button>
                <button type="button" id="btnTambahKaryawanPenyupah"
                    class="h-[34px] px-3.5 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold shadow-xs transition inline-flex items-center gap-1.5 cursor-pointer">
                    <i data-lucide="user-plus" class="w-3.5 h-3.5"></i>
                    <span>Tambah Karyawan</span>
                </button>
            @endcan
        </div>
    </div>

    <!-- STATISTIC STRIP (V2 SOLID PACIFIC NAVY) -->
    <div class="relative rounded-xl overflow-hidden bg-[#284c9a] text-white shadow-xs hover:shadow-md transition-shadow">
        <svg class="absolute right-0 top-0 h-full w-64 text-white/[0.06] pointer-events-none" viewBox="0 0 200 100" fill="none">
            <circle cx="170" cy="20" r="65" stroke="currentColor" stroke-width="18" />
            <circle cx="190" cy="85" r="45" stroke="currentColor" stroke-width="12" />
        </svg>
        <div class="relative grid grid-cols-2 lg:grid-cols-4 divide-y lg:divide-y-0 lg:divide-x divide-white/15">
            <div class="p-3.5 flex items-center justify-between gap-3">
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-wider text-white/75">Total Karyawan</p>
                    <p class="text-lg font-black font-mono-num text-white mt-0.5">{{ number_format($total_karyawan) }} <span class="text-xs font-semibold text-white/70">Orang</span></p>
                </div>
                <i data-lucide="users" class="w-7 h-7 text-white/15 shrink-0"></i>
            </div>
            <div class="p-3.5 flex items-center justify-between gap-3">
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-wider text-white/75">Total Penambah (+)</p>
                    <p class="text-lg font-black font-mono-num text-emerald-300 mt-0.5">+ Rp {{ formatAngka($total_penambah) }}</p>
                </div>
                <i data-lucide="plus-circle" class="w-7 h-7 text-white/15 shrink-0"></i>
            </div>
            <div class="p-3.5 flex items-center justify-between gap-3">
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-wider text-white/75">Total Pengurang (-)</p>
                    <p class="text-lg font-black font-mono-num text-rose-300 mt-0.5">- Rp {{ formatAngka($total_pengurang) }}</p>
                </div>
                <i data-lucide="minus-circle" class="w-7 h-7 text-white/15 shrink-0"></i>
            </div>
            <div class="p-3.5 flex items-center justify-between gap-3">
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-wider text-white/75">Net Penyesuaian</p>
                    <p class="text-lg font-black font-mono-num text-white mt-0.5">
                        {{ $total_net < 0 ? '-' : '+' }} Rp {{ formatAngka(abs($total_net)) }}
                    </p>
                </div>
                <i data-lucide="scale" class="w-7 h-7 text-white/15 shrink-0"></i>
            </div>
        </div>
    </div>

    <!-- FILTER PENCARIAN (DI LUAR CARD TABEL) -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-3 shadow-2xs">
        <div class="relative w-full">
            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="search" class="w-4 h-4"></i>
            </div>
            <input type="text" id="searchKaryawan" placeholder="Cari NIK, Nama Karyawan, Jabatan, atau Cabang..." autocomplete="off"
                class="w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
        </div>
    </div>

    <!-- DATA TABLE CARD (V2 COMPACT ~36PX) -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-2xs overflow-hidden">
        <div class="px-3.5 py-2 bg-[#284c9a] flex items-center justify-between text-white">
            <div class="flex items-center gap-2">
                <i data-lucide="list" class="w-4 h-4 text-blue-200"></i>
                <h5 class="text-xs font-bold text-white uppercase tracking-wider">Daftar Karyawan Disesuaikan</h5>
            </div>
            <span class="px-2 py-0.5 rounded bg-white/15 text-white text-[10.5px] font-bold font-mono-num">{{ count($detailpenyupah) }} Data</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left whitespace-nowrap" id="tableDetail">
                <thead class="bg-[#1e3a78] text-white/90 text-[11px] font-bold uppercase tracking-wider">
                    <tr>
                        <th class="py-2 px-3 text-center w-12">NO</th>
                        <th class="py-2 px-3">NIK</th>
                        <th class="py-2 px-3 min-w-[180px]">NAMA KARYAWAN</th>
                        <th class="py-2 px-3">JABATAN</th>
                        <th class="py-2 px-3">DEPARTEMEN</th>
                        <th class="py-2 px-3">CABANG</th>
                        <th class="py-2 px-3 text-right">PENAMBAH (+)</th>
                        <th class="py-2 px-3 text-right">PENGURANG (-)</th>
                        <th class="py-2 px-3 text-right">NET SELISIH</th>
                        <th class="py-2 px-3 text-center w-24">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse ($detailpenyupah as $index => $d)
                        @php $net_item = $d->penambah - $d->pengurang; @endphp
                        <tr class="item-row hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition-colors">
                            <td class="py-1.5 px-3 text-center font-mono-num text-slate-500">{{ $index + 1 }}</td>
                            <td class="py-1.5 px-3 font-mono-num font-semibold text-slate-700 dark:text-slate-300">{{ $d->nik }}</td>
                            <td class="py-1.5 px-3 font-bold text-slate-800 dark:text-slate-100">{{ $d->nama_karyawan }}</td>
                            <td class="py-1.5 px-3 text-slate-600 dark:text-slate-300">{{ $d->nama_jabatan ?? '-' }}</td>
                            <td class="py-1.5 px-3 text-slate-600 dark:text-slate-300">{{ $d->nama_dept ?? '-' }}</td>
                            <td class="py-1.5 px-3">
                                <span class="px-2 py-0.5 rounded bg-sky-50 dark:bg-sky-950/60 text-sky-700 dark:text-sky-300 text-[10.5px] font-bold">
                                    {{ $d->nama_cabang ?? '-' }}
                                </span>
                            </td>
                            <td class="py-1.5 px-3 text-right font-mono-num font-bold {{ $d->penambah > 0 ? 'text-emerald-600' : 'text-slate-400' }}">
                                {{ $d->penambah > 0 ? '+ Rp ' . formatAngka($d->penambah) : '-' }}
                            </td>
                            <td class="py-1.5 px-3 text-right font-mono-num font-bold {{ $d->pengurang > 0 ? 'text-rose-600' : 'text-slate-400' }}">
                                {{ $d->pengurang > 0 ? '- Rp ' . formatAngka($d->pengurang) : '-' }}
                            </td>
                            <td class="py-1.5 px-3 text-right font-mono-num font-bold {{ $net_item >= 0 ? 'text-[#284c9a] dark:text-blue-400' : 'text-rose-600' }}">
                                {{ $net_item < 0 ? '-' : ($net_item > 0 ? '+' : '') }} Rp {{ formatAngka(abs($net_item)) }}
                            </td>
                            <td class="py-1.5 px-3">
                                <div class="flex items-center justify-center gap-1">
                                    @can('penyupah.edit')
                                        <button type="button" nik="{{ Crypt::encrypt($d->nik) }}"
                                            class="btnEditKaryawanPenyupah w-6 h-6 rounded-md bg-emerald-50 hover:bg-emerald-100 text-emerald-600 dark:bg-emerald-950/50 dark:text-emerald-400 flex items-center justify-center transition cursor-pointer"
                                            title="Edit Nominal">
                                            <i data-lucide="pencil" class="w-3 h-3"></i>
                                        </button>
                                    @endcan
                                    @can('penyupah.delete')
                                        <form method="POST" name="deleteform" class="inline-flex m-0 p-0 deleteform"
                                            action="{{ route('penyupah.deletekaryawan', [Crypt::encrypt($d->kode_gaji), Crypt::encrypt($d->nik)]) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="delete-confirm w-6 h-6 rounded-md bg-rose-50 hover:bg-rose-100 text-rose-600 dark:bg-rose-950/50 dark:text-rose-400 flex items-center justify-center transition cursor-pointer"
                                                title="Hapus Karyawan">
                                                <i data-lucide="trash-2" class="w-3 h-3"></i>
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="py-10 px-4 text-center">
                                <div class="flex flex-col items-center justify-center gap-2 text-slate-400">
                                    <i data-lucide="users" class="w-8 h-8 text-slate-300 dark:text-slate-600"></i>
                                    <span class="text-xs font-semibold text-slate-600 dark:text-slate-300">Belum Ada Karyawan Disesuaikan</span>
                                    <span class="text-[11px] text-slate-400">Tambahkan karyawan secara manual atau import sekaligus dari file Excel.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if ($detailpenyupah->count() > 0)
                    <tfoot class="bg-slate-50 dark:bg-slate-800/60 border-t border-slate-200 dark:border-slate-700 font-bold text-xs">
                        <tr>
                            <td colspan="6" class="py-2 px-3 text-center uppercase tracking-wider text-slate-600 dark:text-slate-300">Total</td>
                            <td class="py-2 px-3 text-right font-mono-num text-emerald-600">+ Rp {{ formatAngka($total_penambah) }}</td>
                            <td class="py-2 px-3 text-right font-mono-num text-rose-600">- Rp {{ formatAngka($total_pengurang) }}</td>
                            <td class="py-2 px-3 text-right font-mono-num {{ $total_net >= 0 ? 'text-[#284c9a] dark:text-blue-400' : 'text-rose-600' }}">
                                {{ $total_net < 0 ? '-' : '+' }} Rp {{ formatAngka(abs($total_net)) }}
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>

<!-- ================= MODAL IMPORT EXCEL (STATIC BACKDROP) ================= -->
<div id="modalImportPenyupah" class="fixed inset-0 z-50 hidden">
    <div id="modalImportPenyupahBackdrop" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4 overflow-y-auto">
        <div id="modalImportPenyupahDialog"
            class="relative w-full max-w-lg bg-white dark:bg-slate-900 rounded-xl shadow-2xl border border-slate-200/80 dark:border-slate-800 transform transition-all duration-150 my-auto">
            <div class="flex items-center justify-between px-4 py-3 bg-white dark:bg-slate-900 border-b border-slate-200/80 dark:border-slate-800 rounded-t-xl">
                <div class="flex items-center gap-2">
                    <i data-lucide="file-spreadsheet" class="w-4 h-4 text-[#284c9a] dark:text-blue-400"></i>
                    <h5 class="text-sm font-bold text-slate-800 dark:text-slate-100">Import Penyesuaian Upah</h5>
                </div>
                <button type="button" id="btnCloseModalImportPenyupah"
                    class="w-7 h-7 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition cursor-pointer">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            <form action="{{ route('penyupah.import', Crypt::encrypt($penyupah->kode_gaji)) }}" method="POST" enctype="multipart/form-data" id="formImportExcel" class="p-4 sm:p-5 space-y-3">
                @csrf
                <div class="rounded-lg border border-blue-200/80 dark:border-blue-800/60 bg-blue-50/70 dark:bg-blue-950/30 p-3 text-xs text-slate-700 dark:text-slate-300 space-y-1">
                    <p class="font-bold text-[#284c9a] dark:text-blue-400 flex items-center gap-1.5">
                        <i data-lucide="info" class="w-3.5 h-3.5"></i>
                        <span>Panduan Import</span>
                    </p>
                    <ol class="list-decimal pl-4 space-y-0.5 text-[11px]">
                        <li>Unduh template Excel dengan tombol <strong>Download Template</strong>.</li>
                        <li>Isi nominal pada kolom <strong>Penambah</strong> dan <strong>Pengurang</strong> untuk karyawan yang bersangkutan.</li>
                        <li>Baris karyawan dengan nilai Penambah & Pengurang <code>0</code> akan otomatis dilewati.</li>
                        <li>Jika NIK sudah ada, nilai nominal akan otomatis diperbarui.</li>
                    </ol>
                </div>

                <div>
                    <label for="fileExcel" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                        Pilih File Excel (.xlsx, .xls, .csv) <span class="text-rose-500">*</span>
                    </label>
                    <input type="file" name="file" id="fileExcel" accept=".xlsx, .xls, .csv"
                        class="w-full text-xs text-slate-600 dark:text-slate-300 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-[#284c9a] hover:file:bg-blue-100 border border-slate-200 dark:border-slate-700 rounded-lg p-1 cursor-pointer">
                    <p class="mt-1 text-[11px] text-slate-400">Ukuran maksimal file: 5 MB</p>
                </div>

                <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100 dark:border-slate-800">
                    <button type="button" id="btnCancelImportPenyupah"
                        class="h-[36px] px-4 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 transition cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" id="btnSubmitImport"
                        class="h-[36px] px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold shadow-xs transition inline-flex items-center gap-1.5 cursor-pointer">
                        <i data-lucide="upload" class="w-3.5 h-3.5"></i>
                        <span>Mulai Import</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ================= MODAL KARYAWAN PENYUPAH (STATIC BACKDROP) ================= -->
<div id="modalKaryawanPenyupah" class="fixed inset-0 z-50 hidden">
    <div id="modalKaryawanPenyupahBackdrop" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4 overflow-y-auto">
        <div id="modalKaryawanPenyupahDialog"
            class="relative w-full max-w-md bg-white dark:bg-slate-900 rounded-xl shadow-2xl border border-slate-200/80 dark:border-slate-800 transform transition-all duration-150 my-auto">
            <div class="flex items-center justify-between px-4 py-3 bg-white dark:bg-slate-900 border-b border-slate-200/80 dark:border-slate-800 rounded-t-xl">
                <div class="flex items-center gap-2">
                    <i data-lucide="user-cog" class="w-4 h-4 text-[#284c9a] dark:text-blue-400"></i>
                    <h5 id="modalKaryawanPenyupahTitle" class="text-sm font-bold text-slate-800 dark:text-slate-100">Tambah Karyawan</h5>
                </div>
                <button type="button" id="btnCloseModalKaryawanPenyupah"
                    class="w-7 h-7 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition cursor-pointer">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            <div id="loadmodalKaryawanPenyupah" class="p-4 sm:p-5"></div>
        </div>
    </div>
</div>
@endsection

@push('myscript')
<script>
    $(function() {
        if (typeof lucide !== 'undefined') lucide.createIcons();

        const kode_gaji = "{{ Crypt::encrypt($penyupah->kode_gaji) }}";

        // Modal Karyawan
        const $modal = $('#modalKaryawanPenyupah');
        const $dialog = $('#modalKaryawanPenyupahDialog');
        const $backdrop = $('#modalKaryawanPenyupahBackdrop');
        const $title = $('#modalKaryawanPenyupahTitle');
        const $content = $('#loadmodalKaryawanPenyupah');

        function openModal() { $modal.removeClass('hidden'); $('body').addClass('overflow-hidden'); }
        function closeModal() { $modal.addClass('hidden'); $('body').removeClass('overflow-hidden'); $content.html(''); }
        window.closeModalKaryawanPenyupah = closeModal;

        $('#btnCloseModalKaryawanPenyupah').on('click', closeModal);
        $backdrop.on('click', function(e) {
            e.stopPropagation();
            $dialog.addClass('scale-[1.015]');
            setTimeout(() => $dialog.removeClass('scale-[1.015]'), 150);
        });

        // Modal Import
        const $modalImport = $('#modalImportPenyupah');
        const $dialogImport = $('#modalImportPenyupahDialog');
        function openImportModal() { $modalImport.removeClass('hidden'); $('body').addClass('overflow-hidden'); }
        function closeImportModal() { $modalImport.addClass('hidden'); $('body').removeClass('overflow-hidden'); }

        $('#btnOpenImportModal').on('click', openImportModal);
        $('#btnCloseModalImportPenyupah, #btnCancelImportPenyupah').on('click', closeImportModal);
        $('#modalImportPenyupahBackdrop').on('click', function(e) {
            e.stopPropagation();
            $dialogImport.addClass('scale-[1.015]');
            setTimeout(() => $dialogImport.removeClass('scale-[1.015]'), 150);
        });

        function showLoading() {
            $content.html(`<div class="py-10 flex flex-col items-center justify-center gap-2"><svg class="animate-spin h-6 w-6 text-[#284c9a]" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg><span class="text-xs font-semibold text-slate-500">Memuat formulir...</span></div>`);
        }

        $('#btnTambahKaryawanPenyupah').on('click', function(e) {
            e.preventDefault();
            $title.text('Tambah Karyawan');
            openModal(); showLoading();
            $content.load('/penyupah/' + kode_gaji + '/tambahkaryawan', function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });

        $(document).on('click', '.btnEditKaryawanPenyupah', function(e) {
            e.preventDefault();
            const nik = $(this).attr('nik');
            $title.text('Edit Penyesuaian Upah');
            openModal(); showLoading();
            $content.load('/penyupah/' + kode_gaji + '/' + nik + '/editkaryawan', function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });

        $('#searchKaryawan').on('keyup', function() {
            const value = $(this).val().toLowerCase();
            $('#tableDetail tbody tr.item-row').filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });

        $('#formImportExcel').on('submit', function(e) {
            const $file = $('#fileExcel');
            $file.siblings('.field-error-msg').remove();
            $file.removeClass('border-rose-500 ring-1 ring-rose-500');
            if (!$file.val()) {
                e.preventDefault();
                $file.addClass('border-rose-500 ring-1 ring-rose-500');
                $file.after(`<p class="field-error-msg text-[11px] font-semibold text-rose-600 mt-1">Pilih file Excel terlebih dahulu.</p>`);
                return false;
            }
            const $btn = $('#btnSubmitImport');
            $btn.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            $btn.html(`<svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg><span>Mengimport Data...</span>`);
        });
    });
</script>
@endpush
