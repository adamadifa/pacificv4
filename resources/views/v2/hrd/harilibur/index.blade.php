@extends('v2.layouts.app')
@section('titlepage', 'Hari Libur')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">HRD</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Hari Libur</span>
    </div>
@endsection

@section('content')
<div class="space-y-3.5 w-full max-w-7xl">

    <!-- PAGE TITLE BAR -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Hari Libur</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Mengelola daftar hari libur dan cuti bersama.</p>
        </div>
    </div>

    <!-- FILTER BAR (COMPACT NO-LABEL V2) -->
    <form action="{{ route('harilibur.index') }}" method="GET"
        class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-3 shadow-2xs space-y-2">
        <div class="grid grid-cols-1 sm:grid-cols-12 gap-2 items-center">
            <!-- Dari -->
            <div class="sm:col-span-3 relative">
                <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                </div>
                <input type="text" name="dari" id="dari_search" value="{{ Request('dari') }}"
                    placeholder="Dari Tanggal" readonly
                    class="flatpickr-date w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition cursor-pointer">
            </div>

            <!-- Sampai -->
            <div class="sm:col-span-3 relative">
                <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                </div>
                <input type="text" name="sampai" id="sampai_search" value="{{ Request('sampai') }}"
                    placeholder="Sampai Tanggal" readonly
                    class="flatpickr-date w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition cursor-pointer">
            </div>

            <!-- Kategori -->
            <div class="sm:col-span-3 relative">
                <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="tag" class="w-4 h-4"></i>
                </div>
                <select name="kategori" id="kategori_search"
                    class="w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                    <option value="">Semua Kategori</option>
                    @foreach ($kategorilibur as $k)
                        <option value="{{ $k->kode_kategori }}" {{ Request('kategori') == $k->kode_kategori ? 'selected' : '' }}>
                            {{ $k->nama_kategori }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Status -->
            <div class="sm:col-span-3 relative">
                <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="check-circle-2" class="w-4 h-4"></i>
                </div>
                <select name="status" id="status_search"
                    class="w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ Request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="disetujui" {{ Request('status') === 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-12 gap-2 items-center">
            @if (in_array($level_user, ['super admin', 'asst. manager hrd', 'spv presensi', 'direktur']))
                <div class="sm:col-span-5 relative">
                    <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                        <i data-lucide="building-2" class="w-4 h-4"></i>
                    </div>
                    <select name="kode_cabang" id="kode_cabang_search"
                        class="select2KodecabangSearch w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200">
                        <option value="">Semua Cabang</option>
                        @foreach ($cabang as $c)
                            <option value="{{ $c->kode_cabang }}" {{ Request('kode_cabang') == $c->kode_cabang ? 'selected' : '' }}>
                                {{ strtoupper($c->nama_cabang) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="sm:col-span-5 relative">
                    <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                        <i data-lucide="briefcase" class="w-4 h-4"></i>
                    </div>
                    <select name="kode_dept" id="kode_dept_search"
                        class="select2KodeDeptSearch w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200">
                        <option value="">Semua Departemen</option>
                        @foreach ($departemen as $dept)
                            <option value="{{ $dept->kode_dept }}" {{ Request('kode_dept') == $dept->kode_dept ? 'selected' : '' }}>
                                {{ strtoupper($dept->nama_dept) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="sm:col-span-2">
                    <button type="submit" id="btnSearch"
                        class="w-full h-[36px] px-3 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold shadow-xs transition flex items-center justify-center gap-1.5 cursor-pointer">
                        <i data-lucide="search" class="w-3.5 h-3.5"></i>
                        <span>Cari</span>
                    </button>
                </div>
            @else
                <div class="sm:col-span-12 flex justify-end">
                    <button type="submit" id="btnSearch"
                        class="h-[36px] px-5 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold shadow-xs transition flex items-center justify-center gap-1.5 cursor-pointer">
                        <i data-lucide="search" class="w-3.5 h-3.5"></i>
                        <span>Cari</span>
                    </button>
                </div>
            @endif
        </div>
    </form>

    <!-- DATA TABLE CARD (V2 COMPACT ~36PX) -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-2xs overflow-hidden">
        <!-- BARIS 1: STRIP JUDUL TABEL (bg-[#284c9a]) -->
        <div class="px-3.5 py-2 bg-[#284c9a] flex items-center justify-between text-white">
            <div class="flex items-center gap-2">
                <i data-lucide="calendar-heart" class="w-4 h-4 text-blue-200"></i>
                <h5 class="text-xs font-bold text-white uppercase tracking-wider">Data Hari Libur</h5>
            </div>
            @can('harilibur.create')
                <button type="button" id="btnCreateHariLibur"
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
                        <th class="py-2 px-3">KODE</th>
                        <th class="py-2 px-3">TANGGAL</th>
                        <th class="py-2 px-3">PENGGANTI</th>
                        <th class="py-2 px-3">CABANG</th>
                        <th class="py-2 px-3">DEPT</th>
                        <th class="py-2 px-3">KATEGORI</th>
                        <th class="py-2 px-3 min-w-[200px]">KETERANGAN</th>
                        <th class="py-2 px-3 text-center">HRD</th>
                        <th class="py-2 px-3 text-center w-28">#</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse ($harilibur as $d)
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition-colors">
                            <td class="py-1.5 px-3 font-mono-num font-bold text-[#284c9a] dark:text-blue-400">{{ $d->kode_libur }}</td>
                            <td class="py-1.5 px-3 font-mono-num text-slate-700 dark:text-slate-300">{{ formatIndo($d->tanggal) }}</td>
                            <td class="py-1.5 px-3 font-mono-num text-slate-600 dark:text-slate-400">{{ formatIndo($d->tanggal_diganti) ?: '-' }}</td>
                            <td class="py-1.5 px-3 font-mono-num text-slate-700 dark:text-slate-300">{{ $d->kode_cabang }}</td>
                            <td class="py-1.5 px-3">
                                @if ($d->kode_dept != null)
                                    <span class="font-mono-num text-slate-700 dark:text-slate-300">{{ $d->kode_dept }}</span>
                                @else
                                    <span class="px-2 py-0.5 rounded bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 text-[10.5px] font-bold">All</span>
                                @endif
                            </td>
                            <td class="py-1.5 px-3">
                                <span class="px-2 py-0.5 rounded-md bg-sky-50 dark:bg-sky-950/60 text-sky-700 dark:text-sky-300 text-[11px] font-bold">
                                    {{ $d->nama_kategori }}
                                </span>
                            </td>
                            <td class="py-1.5 px-3 text-slate-700 dark:text-slate-300">{{ textcamelCase($d->keterangan) }}</td>
                            <td class="py-1.5 px-3 text-center">
                                @if ($d->status == '1')
                                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400" title="Disetujui">
                                        <i data-lucide="check-check" class="w-3.5 h-3.5"></i>
                                    </span>
                                @else
                                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400" title="Pending">
                                        <i data-lucide="hourglass" class="w-3.5 h-3.5"></i>
                                    </span>
                                @endif
                            </td>
                            <td class="py-1.5 px-3">
                                <div class="flex items-center justify-center gap-1">
                                    @can('harilibur.edit')
                                        @if ($d->status === '0')
                                            <button type="button" kode_libur="{{ Crypt::encrypt($d->kode_libur) }}"
                                                class="btnEditHariLibur w-6 h-6 rounded-md bg-emerald-50 hover:bg-emerald-100 text-emerald-600 dark:bg-emerald-950/50 dark:hover:bg-emerald-900/60 dark:text-emerald-400 flex items-center justify-center transition cursor-pointer"
                                                title="Edit">
                                                <i data-lucide="pencil" class="w-3 h-3"></i>
                                            </button>
                                        @endif
                                    @endcan
                                    @can('harilibur.setharilibur')
                                        <a href="{{ route('harilibur.aturharilibur', Crypt::encrypt($d->kode_libur)) }}"
                                            class="w-6 h-6 rounded-md bg-blue-50 hover:bg-blue-100 text-[#284c9a] dark:bg-blue-950/50 dark:hover:bg-blue-900/60 dark:text-blue-400 flex items-center justify-center transition"
                                            title="Atur Hari Libur">
                                            <i data-lucide="settings" class="w-3 h-3"></i>
                                        </a>
                                    @endcan
                                    @can('harilibur.approve')
                                        @if ($d->status === '0')
                                            <button type="button" kode_libur="{{ Crypt::encrypt($d->kode_libur) }}"
                                                class="btnApproveHariLibur w-6 h-6 rounded-md bg-sky-50 hover:bg-sky-100 text-sky-600 dark:bg-sky-950/50 dark:hover:bg-sky-900/60 dark:text-sky-400 flex items-center justify-center transition cursor-pointer"
                                                title="Approve">
                                                <i data-lucide="check-circle" class="w-3 h-3"></i>
                                            </button>
                                        @else
                                            <form action="{{ route('harilibur.cancel', Crypt::encrypt($d->kode_libur)) }}"
                                                method="POST" class="inline-flex m-0 p-0">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="cancel-confirm w-6 h-6 rounded-md bg-amber-50 hover:bg-amber-100 text-amber-600 dark:bg-amber-950/50 dark:hover:bg-amber-900/60 dark:text-amber-400 flex items-center justify-center transition cursor-pointer"
                                                    title="Batalkan Approve">
                                                    <i data-lucide="x-circle" class="w-3 h-3"></i>
                                                </button>
                                            </form>
                                        @endif
                                    @endcan
                                    @can('harilibur.delete')
                                        @if ($d->status === '0')
                                            <form method="POST" name="deleteform" class="inline-flex m-0 p-0 deleteform"
                                                action="{{ route('harilibur.delete', Crypt::encrypt($d->kode_libur)) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="delete-confirm w-6 h-6 rounded-md bg-rose-50 hover:bg-rose-100 text-rose-600 dark:bg-rose-950/50 dark:hover:bg-rose-900/60 dark:text-rose-400 flex items-center justify-center transition cursor-pointer"
                                                    title="Hapus">
                                                    <i data-lucide="trash-2" class="w-3 h-3"></i>
                                                </button>
                                            </form>
                                        @endif
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-10 px-4 text-center">
                                <div class="flex flex-col items-center justify-center gap-1.5 text-slate-400">
                                    <i data-lucide="calendar-off" class="w-8 h-8 text-slate-300 dark:text-slate-600"></i>
                                    <span class="text-xs font-semibold">Tidak ada data hari libur ditemukan</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($harilibur->hasPages())
            <div class="px-4 py-3 border-t border-slate-100 dark:border-slate-800/80 bg-slate-50/50 dark:bg-slate-900/50">
                {{ $harilibur->links('v2.components.pagination') }}
            </div>
        @endif
    </div>
</div>

<!-- ================= MODAL HARI LIBUR (STATIC BACKDROP) ================= -->
<div id="modalHariLibur" class="fixed inset-0 z-50 hidden">
    <div id="modalHariLiburBackdrop" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4 overflow-y-auto">
        <div id="modalHariLiburDialog"
            class="relative w-full max-w-lg bg-white dark:bg-slate-900 rounded-xl shadow-2xl border border-slate-200/80 dark:border-slate-800 transform transition-all duration-150 my-auto">
            <!-- Modal Header Bersih -->
            <div class="flex items-center justify-between px-4 py-3 bg-white dark:bg-slate-900 border-b border-slate-200/80 dark:border-slate-800 rounded-t-xl">
                <div class="flex items-center gap-2">
                    <i data-lucide="calendar-heart" class="w-4 h-4 text-[#284c9a] dark:text-blue-400"></i>
                    <h5 id="modalHariLiburTitle" class="text-sm font-bold text-slate-800 dark:text-slate-100">Buat Hari Libur</h5>
                </div>
                <button type="button" id="btnCloseModalHariLibur"
                    class="w-7 h-7 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition cursor-pointer">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            <!-- Modal Body -->
            <div id="loadmodalHariLibur" class="p-4 sm:p-5"></div>
        </div>
    </div>
</div>
@endsection

@push('myscript')
<script>
    $(function() {
        if (typeof lucide !== 'undefined') lucide.createIcons();

        $('.flatpickr-date').flatpickr({
            dateFormat: 'Y-m-d',
            allowInput: false
        });

        if ($.fn.select2) {
            $('.select2KodecabangSearch').select2({
                placeholder: 'Semua Cabang',
                allowClear: true,
                width: '100%'
            });
            $('.select2KodeDeptSearch').select2({
                placeholder: 'Semua Departemen',
                allowClear: true,
                width: '100%'
            });
        }

        const $modal = $('#modalHariLibur');
        const $dialog = $('#modalHariLiburDialog');
        const $backdrop = $('#modalHariLiburBackdrop');
        const $title = $('#modalHariLiburTitle');
        const $content = $('#loadmodalHariLibur');

        function openModal(sizeClass) {
            $dialog.removeClass('max-w-lg max-w-3xl').addClass(sizeClass || 'max-w-lg');
            $modal.removeClass('hidden');
            $('body').addClass('overflow-hidden');
        }

        function closeModal() {
            $modal.addClass('hidden');
            $('body').removeClass('overflow-hidden');
            $content.html('');
        }

        window.closeModalHariLibur = closeModal;

        $('#btnCloseModalHariLibur').on('click', closeModal);

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

        $('#btnCreateHariLibur').on('click', function(e) {
            e.preventDefault();
            $title.text('Buat Hari Libur');
            openModal('max-w-lg');
            showLoading();
            $content.load('/harilibur/create', function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });

        $(document).on('click', '.btnEditHariLibur', function(e) {
            e.preventDefault();
            const kode_libur = $(this).attr('kode_libur');
            $title.text('Edit Hari Libur');
            openModal('max-w-lg');
            showLoading();
            $content.load(`/harilibur/${kode_libur}/edit`, function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });

        $(document).on('click', '.btnApproveHariLibur', function(e) {
            e.preventDefault();
            const kode_libur = $(this).attr('kode_libur');
            $title.text('Approve Hari Libur');
            openModal('max-w-3xl');
            showLoading();
            $content.load(`/harilibur/${kode_libur}/approve`, function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });
    });
</script>
@endpush
