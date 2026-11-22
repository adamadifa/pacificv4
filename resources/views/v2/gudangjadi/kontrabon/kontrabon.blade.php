<!-- PAGE TITLE BAR & ACTION -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
    <div>
        <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Kontrabon Angkutan</h3>
        <p class="text-[11px] text-slate-400 mt-0.5">Pengelolaan tagihan dan pemrosesan kontrabon armada angkutan gudang jadi.</p>
    </div>

    @if (request()->is('kontrabonangkutan'))
        @can('kontrabonangkutan.create')
            <a href="{{ route('kontrabonangkutan.create') }}" id="btnCreate"
                class="inline-flex items-center justify-center gap-1.5 h-[34px] px-3.5 rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs font-bold transition shadow-xs cursor-pointer shrink-0">
                <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                <span>Buat Kontra Bon</span>
            </a>
        @endcan
    @endif
</div>

<!-- FILTER SECTION (NO-LABEL, ICON PREFIX, H-[34PX], ROUNDED-LG) -->
<div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-3 shadow-2xs transition-colors duration-200">
    <form action="{{ request()->url() }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-2 items-center">
        
        <!-- Dari Tanggal -->
        <div class="lg:col-span-3 relative">
            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
            </div>
            <input type="text" name="dari" id="dari" value="{{ Request('dari') }}" placeholder="Dari Tanggal" readonly
                class="flatpickr-date w-full h-[34px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer">
        </div>

        <!-- Sampai Tanggal -->
        <div class="lg:col-span-3 relative">
            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
            </div>
            <input type="text" name="sampai" id="sampai" value="{{ Request('sampai') }}" placeholder="Sampai Tanggal" readonly
                class="flatpickr-date w-full h-[34px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer">
        </div>

        <!-- Semua Angkutan -->
        <div class="lg:col-span-3 relative">
            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                <i data-lucide="truck" class="w-3.5 h-3.5"></i>
            </div>
            <select name="kode_angkutan_search" id="kode_angkutan_search"
                class="select2Kodeangkutansearch select2-has-prefix w-full h-[34px] text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                <option value="">Semua Angkutan</option>
                @foreach ($angkutan as $a)
                    <option value="{{ $a->kode_angkutan }}" {{ Request('kode_angkutan_search') == $a->kode_angkutan ? 'selected' : '' }}>
                        {{ textUpperCase($a->nama_angkutan) }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Status Pembayaran -->
        <div class="lg:col-span-2 relative">
            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="check-circle" class="w-3.5 h-3.5"></i>
            </div>
            <select name="status_search" id="status_search"
                class="w-full h-[34px] pl-8 pr-8 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition appearance-none cursor-pointer">
                <option value="">Semua Status</option>
                <option value="SP" {{ Request('status_search') == 'SP' ? 'selected' : '' }}>Sudah Diproses</option>
                <option value="BP" {{ Request('status_search') === 'BP' ? 'selected' : '' }}>Belum Diproses</option>
            </select>
            <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                <i data-lucide="chevron-down" class="w-3 h-3"></i>
            </div>
        </div>

        <!-- Tombol Cari -->
        <div class="lg:col-span-1 flex items-center">
            <button type="submit"
                class="w-full h-[34px] inline-flex items-center justify-center gap-1.5 px-3 rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs font-bold transition shadow-xs cursor-pointer">
                <i data-lucide="search" class="w-3.5 h-3.5"></i>
                <span class="lg:hidden">Cari</span>
            </button>
        </div>
    </form>
</div>

<!-- DATA TABLE SECTION -->
<div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-2xs overflow-hidden transition-colors duration-200">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse whitespace-nowrap">
            <thead>
                <!-- Strip Judul Tabel (Colspan Penuh = 5) -->
                <tr class="bg-[#284c9a] border-b border-[#1e3a78] text-white">
                    <th colspan="5" class="px-3.5 py-2">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <i data-lucide="file-text" class="w-4 h-4 text-white/80"></i>
                                <span class="text-xs font-bold uppercase tracking-wider text-white">Data Kontrabon Angkutan</span>
                            </div>
                        </div>
                    </th>
                </tr>
                <!-- Header Kolom Pacific Navy Pekat (#1e3a78) -->
                <tr class="bg-[#1e3a78] text-white text-[11px] font-bold uppercase tracking-wider">
                    <th class="py-2 px-3">No. Kontrabon</th>
                    <th class="py-2 px-3">Tanggal</th>
                    <th class="py-2 px-3">Angkutan</th>
                    <th class="py-2 px-3 text-center">Status Pembayaran</th>
                    <th class="py-2 px-3 text-center w-28">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 text-xs text-slate-700 dark:text-slate-300">
                @forelse ($kontrabon as $d)
                    <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition-colors">
                        <!-- No Kontrabon -->
                        <td class="py-1.5 px-3">
                            <span class="font-bold font-mono-num text-[#284c9a] dark:text-blue-400">
                                {{ $d->no_kontrabon }}
                            </span>
                        </td>

                        <!-- Tanggal -->
                        <td class="py-1.5 px-3 font-mono-num">
                            {{ formatIndo($d->tanggal) }}
                        </td>

                        <!-- Angkutan -->
                        <td class="py-1.5 px-3 font-semibold text-slate-800 dark:text-slate-200">
                            {{ $d->nama_angkutan }}
                        </td>

                        <!-- Status Pembayaran -->
                        <td class="py-1.5 px-3 text-center">
                            @if (!empty($d->tanggal_bayar) || !empty($d->tanggal_bayar_hutang))
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/50 font-mono-num">
                                    {{ formatIndo($d->tanggal_bayar ?? $d->tanggal_bayar_hutang) }}
                                </span>
                            @else
                                <span class="text-amber-500 inline-flex items-center gap-1 text-[11px] font-medium" title="Belum Diproses">
                                    <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                                    <span>Belum Diproses</span>
                                </span>
                            @endif
                        </td>

                        <!-- Aksi Kompak (24x24 px) -->
                        <td class="py-1.5 px-3 text-center">
                            <div class="flex items-center justify-center gap-1">
                                {{-- Detail --}}
                                @can('kontrabonangkutan.show')
                                    <button type="button" no_kontrabon="{{ Crypt::encrypt($d->no_kontrabon) }}" title="Lihat Detail"
                                        class="btnShow w-6 h-6 rounded-md flex items-center justify-center text-sky-600 hover:text-sky-700 hover:bg-sky-50 dark:hover:bg-sky-950/30 transition cursor-pointer">
                                        <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                    </button>
                                @endcan

                                {{-- Proses / Batalkan Pembayaran --}}
                                @can('kontrabonangkutan.proses')
                                    @if (empty($d->tanggal_bayar) && empty($d->tanggal_bayar_hutang))
                                        <button type="button" no_kontrabon="{{ Crypt::encrypt($d->no_kontrabon) }}" title="Proses Kontrabon"
                                            class="btnProses w-6 h-6 rounded-md flex items-center justify-center text-emerald-600 hover:text-emerald-700 hover:bg-emerald-50 dark:hover:bg-emerald-950/30 transition cursor-pointer">
                                            <i data-lucide="check-square" class="w-3.5 h-3.5"></i>
                                        </button>
                                    @else
                                        <form method="POST" action="{{ route('kontrabonangkutan.cancelproses', Crypt::encrypt($d->no_kontrabon)) }}" class="inline-flex m-0 p-0 deleteform">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" title="Batalkan Pembayaran"
                                                class="cancel-confirm w-6 h-6 rounded-md flex items-center justify-center text-amber-600 hover:text-amber-700 hover:bg-amber-50 dark:hover:bg-amber-950/30 transition cursor-pointer">
                                                <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                                            </button>
                                        </form>
                                    @endif
                                @endcan

                                {{-- Hapus --}}
                                @can('kontrabonangkutan.delete')
                                    @if (empty($d->tanggal_bayar) && empty($d->tanggal_bayar_hutang))
                                        <form method="POST" action="{{ route('kontrabonangkutan.delete', Crypt::encrypt($d->no_kontrabon)) }}" class="inline-flex m-0 p-0 deleteform">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" title="Hapus Kontrabon"
                                                class="delete-confirm w-6 h-6 rounded-md flex items-center justify-center text-rose-600 hover:text-rose-700 hover:bg-rose-50 dark:hover:bg-rose-950/30 transition cursor-pointer">
                                                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                            </button>
                                        </form>
                                    @endif
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-slate-400">
                            <div class="flex flex-col items-center justify-center gap-1.5">
                                <i data-lucide="inbox" class="w-6 h-6 text-slate-300"></i>
                                <span class="text-xs font-medium">Belum ada data kontrabon angkutan</span>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- PAGINATION V2 STANDAR -->
    @if ($kontrabon->hasPages())
        <div class="px-4 py-3 border-t border-slate-100 dark:border-slate-800/80 bg-slate-50/50 dark:bg-slate-900/50">
            {{ $kontrabon->links('v2.components.pagination') }}
        </div>
    @endif
</div>

<!-- MODAL FORM V2 -->
<x-modal-form id="modal" size="modal-xl" show="loadmodal" title="" />
<x-modal-form id="modalDetailpembelian" size="modal-xl" show="loadmodalDetailpembelian" title="" />

@push('myscript')
<script>
$(function() {
    const $select2Angkutan = $('.select2Kodeangkutansearch');
    if ($select2Angkutan.length) {
        $select2Angkutan.select2({
            placeholder: 'Semua Angkutan',
            allowClear: true,
            width: '100%'
        });
    }

    function loadingModal() {
        return `
            <div class="p-6 text-center text-slate-400 flex flex-col items-center justify-center gap-2">
                <svg class="animate-spin h-6 w-6 text-[#284c9a]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="text-xs font-semibold">Memuat data...</span>
            </div>
        `;
    }

    $(".btnShow").on('click', function(e) {
        e.preventDefault();
        const no_kontrabon = $(this).attr("no_kontrabon");
        $("#modal").modal("show");
        $("#modal").find(".modal-title").text("Detail Kontrabon Angkutan");
        $("#modal").find("#loadmodal").html(loadingModal());
        $("#modal").find("#loadmodal").load(`/kontrabonangkutan/${no_kontrabon}/show`);
    });

    $(".btnProses").on('click', function(e) {
        e.preventDefault();
        const no_kontrabon = $(this).attr("no_kontrabon");
        $("#modal").modal("show");
        $("#modal").find(".modal-title").text("Proses Pembayaran Kontrabon");
        $("#modal").find("#loadmodal").html(loadingModal());
        $("#modal").find("#loadmodal").load(`/kontrabonangkutan/${no_kontrabon}/proses`);
    });

    $(document).on('click', '.btnShowpembelian', function(e) {
        e.preventDefault();
        const no_bukti = $(this).attr("no_bukti");
        $("#modalDetailpembelian").modal("show");
        $("#modalDetailpembelian").find(".modal-title").text("Detail Pembelian");
        $("#modalDetailpembelian").find("#loadmodalDetailpembelian").html(loadingModal());
        $("#modalDetailpembelian").find("#loadmodalDetailpembelian").load(`/pembelian/${no_bukti}/show`);
    });
});
</script>
@endpush
