@extends('v2.layouts.app')
@section('titlepage', $kategori == 'EK' ? 'Piutang Eks Karyawan' : 'Piutang Karyawan')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Keuangan</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">
            {{ $kategori == 'EK' ? 'Piutang Eks Karyawan' : 'Piutang Karyawan' }}
        </span>
    </div>
@endsection

@section('content')
<div class="space-y-3.5 w-full">

    <!-- PAGE TITLE BAR & ACTION BUTTON -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">
                {{ $kategori == 'EK' ? 'Piutang Eks Karyawan' : 'Piutang Karyawan' }}
            </h3>
            <p class="text-[11px] text-slate-400 mt-0.5">
                Manajemen data piutang dan pinjaman {{ $kategori == 'EK' ? 'eks karyawan' : 'karyawan' }}.
            </p>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            @can('piutangkaryawan.create')
                <button type="button" id="btnCreatePiutang"
                    class="inline-flex items-center justify-center gap-1.5 h-[34px] px-3.5 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold transition shadow-xs cursor-pointer shrink-0">
                    <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                    <span>Input Piutang</span>
                </button>
            @endcan
        </div>
    </div>

    <!-- FILTER SECTION (COMPACT NO-LABEL V2) -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-3 shadow-2xs">
        <form action="{{ $kategori == 'EK' ? route('piutangkaryawan.indexekskaryawan') : route('piutangkaryawan.index') }}" method="GET" id="formFilterPiutang" class="w-full">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-2.5 items-center">
                <!-- Dari Tanggal -->
                <div class="relative lg:col-span-2">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="calendar" class="w-4 h-4"></i>
                    </div>
                    <input type="text" name="dari" id="dari_filter" value="{{ Request('dari') }}" placeholder="Dari Tanggal" readonly
                        class="flatpickr-date w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/60 text-xs font-semibold text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
                </div>

                <!-- Sampai Tanggal -->
                <div class="relative lg:col-span-2">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="calendar" class="w-4 h-4"></i>
                    </div>
                    <input type="text" name="sampai" id="sampai_filter" value="{{ Request('sampai') }}" placeholder="Sampai Tanggal" readonly
                        class="flatpickr-date w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/60 text-xs font-semibold text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
                </div>

                <!-- Filter Cabang (Jika Berwenang) -->
                @hasanyrole($roles_show_cabang)
                    <div class="relative select2-container-v2 lg:col-span-3">
                        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                            <i data-lucide="building" class="w-4 h-4"></i>
                        </div>
                        <select name="kode_cabang_search" id="kode_cabang_search" class="select2Kodecabangsearch w-full">
                            <option value="">Semua Cabang</option>
                            @foreach ($cabang as $d)
                                <option value="{{ $d->kode_cabang }}" {{ Request('kode_cabang_search') == $d->kode_cabang ? 'selected' : '' }}>
                                    {{ textUpperCase($d->nama_cabang) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endhasanyrole

                <!-- Nama Karyawan Search -->
                <div class="relative {{ auth()->user()->hasAnyRole($roles_show_cabang) ? 'lg:col-span-4' : 'lg:col-span-7' }}">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="user" class="w-4 h-4"></i>
                    </div>
                    <input type="text" name="nama_karyawan_search" id="nama_karyawan_search" value="{{ Request('nama_karyawan_search') }}" placeholder="Cari Nama Karyawan..."
                        class="w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/60 text-xs font-semibold text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
                </div>

                <!-- Tombol Aksi Filter -->
                <div class="flex items-center gap-1.5 lg:col-span-1">
                    <button type="submit"
                        class="flex-1 h-[36px] px-3 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold transition flex items-center justify-center gap-1 shadow-xs cursor-pointer shrink-0" title="Cari Data">
                        <i data-lucide="search" class="w-3.5 h-3.5"></i>
                    </button>

                    @if (request()->hasAny(['dari', 'sampai', 'kode_cabang_search', 'nama_karyawan_search']))
                        <a href="{{ $kategori == 'EK' ? route('piutangkaryawan.indexekskaryawan') : route('piutangkaryawan.index') }}"
                            class="h-[36px] px-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-100 text-slate-500 text-xs font-bold transition flex items-center justify-center cursor-pointer shrink-0" title="Reset Filter">
                            <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i>
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <!-- DATA TABLE CARD (V2 COMPACT ~36PX) -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-2xs overflow-hidden">
        <!-- BARIS 1: STRIP JUDUL TABEL (bg-[#284c9a]) -->
        <div class="px-3.5 py-2 bg-[#284c9a] flex items-center justify-between text-white">
            <div class="flex items-center gap-2">
                <i data-lucide="users" class="w-4 h-4 text-blue-200"></i>
                <h5 class="text-xs font-bold text-white uppercase tracking-wider">
                    Data {{ $kategori == 'EK' ? 'Piutang Eks Karyawan' : 'Piutang Karyawan' }}
                </h5>
            </div>
            <div class="flex items-center gap-2">
                @php
                    $totalData = method_exists($piutangkaryawan, 'total') ? $piutangkaryawan->total() : $piutangkaryawan->count();
                @endphp
                <span class="text-[11px] font-semibold px-2.5 py-0.5 rounded-full bg-white/10 text-white border border-white/20 font-mono-num">
                    Total: {{ $totalData }} Data
                </span>
            </div>
        </div>

        <!-- TABLE CONTENT (KOLOM TERPISAH MANDIRI, TANPA DATA BERTUMPUK) -->
        <div class="overflow-x-auto min-h-[300px]">
            <table class="w-full text-left text-xs whitespace-nowrap">
                <!-- BARIS 2: HEADER KOLOM (bg-[#1e3a78]) -->
                <thead class="bg-[#1e3a78] text-white/90">
                    <tr>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-center">NO. PINJAMAN</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-center">TANGGAL</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-center">NIK</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider min-w-[180px]">NAMA KARYAWAN</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-center">KANTOR</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider min-w-[120px]">JABATAN</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-right">JUMLAH</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-right">BAYAR</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-right">SISA TAGIHAN</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-center">KAT</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-center">KET</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-center w-16 sticky right-0 z-10 bg-[#1e3a78]">#</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300 font-medium">
                    @forelse ($piutangkaryawan as $d)
                        @php
                            $sisatagihan = $d->jumlah - $d->totalpembayaran;
                        @endphp
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition">
                            <!-- NO. PINJAMAN -->
                            <td class="py-1.5 px-3 text-center font-mono-num font-bold text-slate-800 dark:text-slate-100">
                                <span class="inline-flex items-center gap-1">
                                    <span>{{ $d->no_pinjaman }}</span>
                                    @if ($d->status == 1)
                                        <i data-lucide="shield-check" class="w-3.5 h-3.5 text-sky-500" title="Hanya Bisa Dilihat Keuangan"></i>
                                    @endif
                                </span>
                            </td>

                            <!-- TANGGAL -->
                            <td class="py-1.5 px-3 text-center font-mono-num text-slate-600 dark:text-slate-400">
                                {{ date('d-m-Y', strtotime($d->tanggal)) }}
                            </td>

                            <!-- NIK -->
                            <td class="py-1.5 px-3 text-center font-mono-num text-slate-600 dark:text-slate-400">
                                {{ $d->nik }}
                            </td>

                            <!-- NAMA KARYAWAN -->
                            <td class="py-1.5 px-3 font-semibold text-slate-800 dark:text-slate-100">
                                {{ textUpperCase($d->nama_karyawan) }}
                            </td>

                            <!-- KANTOR -->
                            <td class="py-1.5 px-3 text-center">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300">
                                    {{ textUpperCase($d->kode_cabang) }}
                                </span>
                            </td>

                            <!-- JABATAN -->
                            <td class="py-1.5 px-3 text-slate-600 dark:text-slate-300">
                                {{ singkatString($d->nama_jabatan) }}
                            </td>

                            <!-- JUMLAH -->
                            <td class="py-1.5 px-3 text-right font-mono-num font-bold text-slate-800 dark:text-slate-100">
                                {{ formatAngka($d->jumlah) }}
                            </td>

                            <!-- BAYAR -->
                            <td class="py-1.5 px-3 text-right font-mono-num font-bold text-emerald-600 dark:text-emerald-400">
                                {{ formatAngka($d->totalpembayaran) }}
                            </td>

                            <!-- SISA TAGIHAN -->
                            <td class="py-1.5 px-3 text-right font-mono-num font-bold {{ $sisatagihan > 0 ? 'text-rose-600 dark:text-rose-400' : 'text-slate-400' }}">
                                {{ formatAngka($sisatagihan) }}
                            </td>

                            <!-- KATEGORI -->
                            <td class="py-1.5 px-3 text-center">
                                @if ($d->kategori == 'KA')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300">
                                        Karyawan
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-rose-100 text-rose-800 dark:bg-rose-950/60 dark:text-rose-300">
                                        Eks Karyawan
                                    </span>
                                @endif
                            </td>

                            <!-- KETERANGAN LUNAS / BELUM + KEUANGAN -->
                            <td class="py-1.5 px-3 text-center">
                                <div class="inline-flex items-center justify-center gap-1">
                                    @if ($sisatagihan == 0)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300">
                                            Lunas
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-rose-100 text-rose-800 dark:bg-rose-950/60 dark:text-rose-300">
                                            Belum Lunas
                                        </span>
                                    @endif
                                    @if ($d->status == 1)
                                        <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded text-[10px] font-bold bg-sky-100 text-sky-800 dark:bg-sky-950/60 dark:text-sky-300" title="Hanya Bisa Dilihat Keuangan">
                                            <i data-lucide="lock" class="w-2.5 h-2.5"></i>
                                            <span>Keuangan</span>
                                        </span>
                                    @endif
                                </div>
                            </td>

                            <!-- AKSI STICKY RIGHT -->
                            <td class="py-1.5 px-3 text-center sticky right-0 z-10 bg-white dark:bg-slate-900 group-hover:bg-slate-50 dark:group-hover:bg-slate-800/40">
                                <div class="flex items-center justify-center gap-1">
                                    <!-- Detail Show -->
                                    @can('piutangkaryawan.show')
                                        <button type="button" data-nopinjam="{{ Crypt::encrypt($d->no_pinjaman) }}"
                                            class="btnShowPiutang w-6 h-6 rounded-md bg-blue-50 text-blue-600 hover:bg-blue-100 dark:bg-blue-950/60 dark:text-blue-300 transition flex items-center justify-center cursor-pointer" title="Detail Piutang">
                                            <i data-lucide="file-text" class="w-3 h-3"></i>
                                        </button>
                                    @endcan

                                    <!-- Delete Piutang -->
                                    @can('piutangkaryawan.delete')
                                        @if ($d->status === '0')
                                            <form action="{{ route('piutangkaryawan.delete', Crypt::encrypt($d->no_pinjaman)) }}" method="POST" class="inline-flex m-0 p-0 deleteform">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btnDeletePiutang w-6 h-6 rounded-md bg-rose-50 text-rose-600 hover:bg-rose-100 dark:bg-rose-950/60 dark:text-rose-300 transition flex items-center justify-center cursor-pointer" title="Hapus Piutang">
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
                            <td colspan="12" class="py-8 text-center text-slate-400 dark:text-slate-500">
                                <div class="flex flex-col items-center justify-center gap-1.5">
                                    <i data-lucide="inbox" class="w-8 h-8 text-slate-300 dark:text-slate-600"></i>
                                    <span class="text-xs font-semibold">Tidak ada data piutang yang ditemukan</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- PAGINATION CONTAINER -->
        @if ($piutangkaryawan->hasPages())
            <div class="px-4 py-3 border-t border-slate-100 dark:border-slate-800/80 bg-slate-50/50 dark:bg-slate-900/50">
                {{ $piutangkaryawan->links('v2.components.pagination') }}
            </div>
        @endif
    </div>
</div>

<!-- ================= MODAL CREATE / DETAIL PIUTANG (STATIC BACKDROP) ================= -->
<div id="modalPiutang" class="fixed inset-0 z-50 hidden">
    <div id="modalPiutangBackdrop" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4 overflow-y-auto">
        <div id="modalPiutangDialog" class="relative w-full max-w-2xl bg-white dark:bg-slate-900 rounded-xl shadow-2xl border border-slate-200/80 dark:border-slate-800 transform transition-all duration-150">
            <!-- Modal Header -->
            <div class="flex items-center justify-between px-4 py-3 border-b border-slate-200/80 dark:border-slate-800">
                <div class="flex items-center gap-2">
                    <i data-lucide="file-text" class="w-4 h-4 text-[#284c9a] dark:text-blue-400"></i>
                    <h5 id="modalPiutangTitle" class="text-sm font-bold text-slate-800 dark:text-slate-100">
                        {{ $kategori == 'EK' ? 'Input Piutang Eks Karyawan' : 'Input Piutang Karyawan' }}
                    </h5>
                </div>
                <button type="button" id="btnCloseModalPiutang"
                    class="w-7 h-7 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition cursor-pointer">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            <!-- Modal Body -->
            <div id="modalPiutangContent" class="p-4 sm:p-5 max-h-[85vh] overflow-y-auto"></div>
        </div>
    </div>
</div>

<!-- ================= MODAL PEMBAYARAN PIUTANG (STATIC BACKDROP) ================= -->
<div id="modalBayarPiutang" class="fixed inset-0 z-50 hidden">
    <div id="modalBayarPiutangBackdrop" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4 overflow-y-auto">
        <div id="modalBayarPiutangDialog" class="relative w-full max-w-md bg-white dark:bg-slate-900 rounded-xl shadow-2xl border border-slate-200/80 dark:border-slate-800 transform transition-all duration-150">
            <!-- Modal Header -->
            <div class="flex items-center justify-between px-4 py-3 border-b border-slate-200/80 dark:border-slate-800">
                <div class="flex items-center gap-2">
                    <i data-lucide="receipt" class="w-4 h-4 text-[#284c9a] dark:text-blue-400"></i>
                    <h5 id="modalBayarPiutangTitle" class="text-sm font-bold text-slate-800 dark:text-slate-100">Input Pembayaran Piutang Karyawan</h5>
                </div>
                <button type="button" id="btnCloseModalBayarPiutang"
                    class="w-7 h-7 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition cursor-pointer">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            <!-- Modal Body -->
            <div id="modalBayarPiutangContent" class="p-4 sm:p-5"></div>
        </div>
    </div>
</div>

<!-- ================= MODAL PILIH KARYAWAN (STATIC BACKDROP) ================= -->
<div id="modalKaryawanPiutang" class="fixed inset-0 z-50 hidden">
    <div id="modalKaryawanPiutangBackdrop" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4 overflow-y-auto">
        <div id="modalKaryawanPiutangDialog" class="relative w-full max-w-5xl bg-white dark:bg-slate-900 rounded-xl shadow-2xl border border-slate-200/80 dark:border-slate-800 transform transition-all duration-150">
            <!-- Modal Header -->
            <div class="flex items-center justify-between px-4 py-3 border-b border-slate-200/80 dark:border-slate-800">
                <div class="flex items-center gap-2">
                    <i data-lucide="users" class="w-4 h-4 text-[#284c9a] dark:text-blue-400"></i>
                    <h5 class="text-sm font-bold text-slate-800 dark:text-slate-100">Pilih Data Karyawan</h5>
                </div>
                <button type="button" id="btnCloseModalKaryawanPiutang"
                    class="w-7 h-7 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition cursor-pointer">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            <!-- Modal Body with DataTable -->
            <div class="p-4 sm:p-5 max-h-[80vh] overflow-y-auto">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs whitespace-nowrap" id="tabelKaryawanPiutang" style="width: 100%;">
                        <thead class="bg-[#1e3a78] text-white">
                            <tr>
                                <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-center text-white" style="color: #ffffff !important;">NIK</th>
                                <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider min-w-[180px] text-white" style="color: #ffffff !important;">NAMA KARYAWAN</th>
                                <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-white" style="color: #ffffff !important;">JABATAN</th>
                                <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-white" style="color: #ffffff !important;">DEPT</th>
                                <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-white" style="color: #ffffff !important;">KANTOR</th>
                                <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-center text-white" style="color: #ffffff !important;">STATUS</th>
                                <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-center w-12 text-white" style="color: #ffffff !important;">#</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800 font-medium"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('myscript')
<script>
    $(function() {
        if (typeof lucide !== 'undefined') lucide.createIcons();

        $(".flatpickr-date").flatpickr({
            dateFormat: "Y-m-d",
            allowInput: false
        });

        const $select2Cabang = $('.select2Kodecabangsearch');
        if ($select2Cabang.length) {
            $select2Cabang.select2({
                placeholder: 'Semua Cabang',
                allowClear: true,
                dropdownCssClass: 'select2-dropdown-v2'
            });
        }

        function initModalHelper(modalId, dialogId, backdropId, closeBtnId, onCloseCallback) {
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
                if (typeof onCloseCallback === 'function') {
                    onCloseCallback($modal);
                }
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

        window.modalPiutangHelper = initModalHelper('#modalPiutang', '#modalPiutangDialog', '#modalPiutangBackdrop', '#btnCloseModalPiutang', function($modal) {
            if ($modal.data('kategori-changed')) {
                location.reload();
            }
        });
        window.modalBayarPiutangHelper = initModalHelper('#modalBayarPiutang', '#modalBayarPiutangDialog', '#modalBayarPiutangBackdrop', '#btnCloseModalBayarPiutang');
        window.modalKaryawanPiutangHelper = initModalHelper('#modalKaryawanPiutang', '#modalKaryawanPiutangDialog', '#modalKaryawanPiutangBackdrop', '#btnCloseModalKaryawanPiutang');

        const loadingSpinner = `
            <div class="py-12 flex flex-col items-center justify-center gap-2">
                <svg class="animate-spin h-6 w-6 text-[#284c9a]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="text-xs text-slate-500 font-semibold">Memuat formulir...</span>
            </div>
        `;

        // Open Create Modal
        $("#btnCreatePiutang").on('click', function(e) {
            e.preventDefault();
            $("#modalPiutangDialog").removeClass('max-w-5xl').addClass('max-w-2xl');
            $("#modalPiutangTitle").text("{{ $kategori == 'EK' ? 'Input Piutang Eks Karyawan' : 'Input Piutang Karyawan' }}");
            $("#modalPiutangContent").html(loadingSpinner);
            modalPiutangHelper.open();
            $("#modalPiutangContent").load(`/piutangkaryawan/create?kategori={{ $kategori }}`, function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });

        // Open Detail Show Modal
        $(document).on('click', '.btnShowPiutang', function(e) {
            e.preventDefault();
            const nopinjam = $(this).data("nopinjam");
            $("#modalPiutangDialog").removeClass('max-w-2xl').addClass('max-w-5xl');
            $("#modalPiutangTitle").text("Detail Piutang Karyawan");
            $("#modalPiutangContent").html(loadingSpinner);
            modalPiutangHelper.open();
            $("#modalPiutangContent").load(`/piutangkaryawan/${nopinjam}/show`, function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });

        // Delete Piutang Confirmation
        $(document).on('click', '.btnDeletePiutang', function(e) {
            e.preventDefault();
            const form = $(this).closest('form');
            Swal.fire({
                title: 'Hapus Data Piutang?',
                text: 'Data piutang yang dihapus tidak dapat dipulihkan!',
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

        // Trigger Modal Cari Karyawan from Create Piutang
        $(document).on('click', '#nik_search_piutang', function(e) {
            e.preventDefault();
            modalKaryawanPiutangHelper.open();
            if (!$.fn.DataTable.isDataTable('#tabelKaryawanPiutang')) {
                $('#tabelKaryawanPiutang').DataTable({
                    processing: true,
                    serverSide: true,
                    order: [[1, 'asc']],
                    ajax: "{{ route('karyawan.getkaryawanpiutangkaryawanjson') }}?kategori={{ $kategori }}",
                    bAutoWidth: false,
                    columns: [
                        { data: 'nik', name: 'nik', orderable: true, searchable: true, width: '12%', className: 'text-center font-mono-num font-bold py-1.5 px-3', createdCell: (td) => $(td).addClass('text-slate-700 dark:text-slate-300') },
                        { data: 'nama_karyawan', name: 'nama_karyawan', orderable: true, searchable: true, width: '28%', className: 'font-semibold py-1.5 px-3', createdCell: (td) => $(td).addClass('text-slate-800 dark:text-slate-100') },
                        { data: 'nama_jabatan', name: 'nama_jabatan', orderable: true, searchable: false, width: '18%', className: 'py-1.5 px-3', createdCell: (td) => $(td).addClass('text-slate-600 dark:text-slate-400') },
                        { data: 'nama_dept', name: 'nama_dept', orderable: true, searchable: false, width: '18%', className: 'py-1.5 px-3', createdCell: (td) => $(td).addClass('text-slate-600 dark:text-slate-400') },
                        { data: 'nama_cabang', name: 'nama_cabang', orderable: true, searchable: false, width: '14%', className: 'text-center py-1.5 px-3' },
                        { data: 'statuskaryawan', name: 'statuskaryawan', orderable: true, searchable: false, width: '10%', className: 'text-center py-1.5 px-3' },
                        { data: 'action', name: 'action', orderable: false, searchable: false, width: '6%', className: 'text-center py-1.5 px-3' }
                    ]
                });
            }
        });

        // Open Modal Input Pembayaran Piutang from Detail Show
        $(document).on('click', '#btnBayarPiutangModal', function(e) {
            e.preventDefault();
            const nopinjam = $(this).data("nopinjam");
            $("#modalBayarPiutangTitle").text("Input Pembayaran Piutang Karyawan");
            $("#modalBayarPiutangContent").html(loadingSpinner);
            modalBayarPiutangHelper.open();
            $("#modalBayarPiutangContent").load(`/pembayaranpiutangkaryawan/${nopinjam}/create`, function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });
    });
</script>
@endpush
