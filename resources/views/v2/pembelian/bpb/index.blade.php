@extends('v2.layouts.app')
@section('titlepage', 'BPPB Pembelian')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Home</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-slate-400">Pembelian</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">BPPB</span>
    </div>
@endsection

@section('content')
<div class="space-y-3.5 w-full">

    <!-- PAGE TITLE BAR & ACTION BUTTONS -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">BPPB (Bukti Permintaan &amp; Penerimaan Barang)</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Kelola data bukti permintaan dan penerimaan barang (BPPB) Pembelian.</p>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            <a href="{{ route('bpbpembelian.create') }}" id="btnCreate"
                class="inline-flex items-center justify-center gap-1.5 h-[34px] px-3.5 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] active:bg-[#152852] text-white text-xs font-bold transition shadow-xs cursor-pointer shrink-0">
                <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                <span>Tambah Data</span>
            </a>
        </div>
    </div>

    <!-- FILTER SECTION (NO-LABEL, KOMPAK ~36px) -->
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-3 shadow-2xs">
        <form action="{{ route('bpbpembelian.index') }}" method="GET" id="formSearch" class="space-y-2">
            
            <!-- Baris 1: No. Bukti, Dari Tanggal, Sampai Tanggal -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-2 items-center">
                <!-- No Bukti -->
                <div class="lg:col-span-6 relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="barcode" class="w-3.5 h-3.5"></i>
                    </div>
                    <input type="text" name="no_bpb_search" id="no_bpb_search" value="{{ Request('no_bpb_search') }}"
                        placeholder="No. Bukti BPPB"
                        class="w-full h-[36px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                </div>

                <!-- Dari Tanggal -->
                <div class="lg:col-span-3 relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                    </div>
                    <input type="text" name="dari" id="dari" value="{{ Request('dari') }}"
                        placeholder="Dari Tanggal" readonly
                        class="flatpickr-date w-full h-[36px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer">
                </div>

                <!-- Sampai Tanggal -->
                <div class="lg:col-span-3 relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                    </div>
                    <input type="text" name="sampai" id="sampai" value="{{ Request('sampai') }}"
                        placeholder="Sampai Tanggal" readonly
                        class="flatpickr-date w-full h-[36px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer">
                </div>
            </div>

            <!-- Baris 2: Status, Cabang, Departemen, Tombol Cari -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-2 items-center">
                <!-- Status -->
                <div class="lg:col-span-3 relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="check-circle-2" class="w-3.5 h-3.5"></i>
                    </div>
                    <select name="status" id="status"
                        class="w-full h-[36px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer">
                        <option value="">Status</option>
                        <option value="selesai" {{ Request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="proses" {{ Request('status') == 'proses' ? 'selected' : '' }}>Proses</option>
                    </select>
                </div>

                <!-- Cabang -->
                <div class="lg:col-span-3 relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="building-2" class="w-3.5 h-3.5"></i>
                    </div>
                    <select name="kode_cabang" id="kode_cabang"
                        class="w-full h-[36px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer">
                        <option value="">Semua Cabang</option>
                        @foreach ($cabangList as $c)
                            <option value="{{ $c->kode_cabang }}" {{ Request('kode_cabang') == $c->kode_cabang ? 'selected' : '' }}>
                                {{ $c->nama_cabang }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Departemen -->
                <div class="lg:col-span-3 relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="layers" class="w-3.5 h-3.5"></i>
                    </div>
                    <select name="kode_dept" id="kode_dept"
                        class="w-full h-[36px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer">
                        <option value="">Semua Departemen</option>
                        @foreach ($deptList as $d)
                            <option value="{{ $d->kode_dept }}" {{ Request('kode_dept') == $d->kode_dept ? 'selected' : '' }}>
                                {{ $d->nama_dept }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Tombol Cari & Reset -->
                <div class="lg:col-span-3 flex items-center gap-1.5">
                    <button type="submit"
                        class="flex-1 h-[36px] px-3 bg-[#284c9a] hover:bg-[#1e3a78] active:bg-[#152852] text-white font-bold rounded-lg text-xs flex items-center justify-center gap-1.5 transition shadow-2xs cursor-pointer">
                        <i data-lucide="search" class="w-3.5 h-3.5"></i>
                        <span>Cari Data</span>
                    </button>
                    @if (Request('no_bpb_search') || Request('dari') || Request('sampai') || Request('status') || Request('kode_cabang') || Request('kode_dept'))
                        <a href="{{ route('bpbpembelian.index') }}"
                            class="h-[36px] px-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 font-bold rounded-lg text-xs flex items-center justify-center transition shrink-0 cursor-pointer"
                            title="Reset Filter">
                            <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <!-- DATA TABLE CARD (KOMPAK ~36px) -->
    <div class="rounded-xl overflow-hidden border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-2xs">
        <div class="overflow-x-auto min-h-[280px]">
            <table class="w-full text-xs text-left border-separate border-spacing-0 whitespace-nowrap">
                <thead>
                    <!-- Baris 1: Strip Header Pacific Navy -->
                    <tr class="bg-[#284c9a] text-white">
                        <th colspan="9" class="px-3.5 py-2 bg-[#284c9a] text-white border-0">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-1.5">
                                    <i data-lucide="receipt" class="w-3.5 h-3.5 text-blue-200"></i>
                                    <span class="text-xs font-bold text-white uppercase tracking-wider">Data BPPB Pembelian</span>
                                </div>
                                <span class="text-[10.5px] text-white/70 font-mono-num">
                                    Total: {{ method_exists($bpb, 'total') ? number_format($bpb->total(), 0, ',', '.') : number_format($bpb->count(), 0, ',', '.') }} Data
                                </span>
                            </div>
                        </th>
                    </tr>

                    <!-- Baris 2: Header Kolom -->
                    <tr class="bg-[#1e3a78] text-white text-[11px]">
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 w-36">NO. BUKTI</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 w-28">TGL PENGAJUAN</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 min-w-[140px]">DEPARTEMEN</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 w-20">CABANG</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 min-w-[150px]">YANG MENGAJUKAN</th>
                        <th class="py-2 px-3 text-center font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 w-24">HEAD DEPT</th>
                        <th class="py-2 px-3 text-center font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 w-24">PEMBELIAN</th>
                        <th class="py-2 px-3 text-center font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 w-24">STATUS</th>
                        <th class="py-2 px-3 text-center font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 w-24">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80">
                    @php
                        $user_auth_id = Auth::user()->id;
                        $cabang = Auth::user()->kode_cabang;
                        $dept = Auth::user()->kode_dept;
                        if ($cabang == 'PST' && $dept == 'AKT') {
                            $approver_head_dept = '194';
                        } elseif ($dept == 'KEU') {
                            $approver_head_dept = '57';
                        } elseif ($dept == 'PMB') {
                            $approver_head_dept = '9';
                        } elseif ($dept == 'HRD') {
                            $approver_head_dept = '109';
                        } elseif ($dept == 'ADT') {
                            $approver_head_dept = '196';
                        } elseif ($dept == 'GAF') {
                            $approver_head_dept = '64';
                        } elseif ($dept == 'GDG') {
                            $approver_head_dept = '28';
                        } elseif ($dept == 'PRD') {
                            $approver_head_dept = '71';
                        } elseif ($dept == 'PDQ') {
                            $approver_head_dept = '62';
                        } elseif ($dept == 'MTC') {
                            $approver_head_dept = '61';
                        } else {
                            $approver_head_dept = '';
                        }
                    @endphp

                    @forelse ($bpb as $d)
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition">
                            <td class="py-1.5 px-3 font-mono-num font-bold text-[#284c9a] dark:text-blue-400">
                                {{ $d->no_bpb }}
                            </td>
                            <td class="py-1.5 px-3 font-mono-num text-slate-700 dark:text-slate-300">
                                {{ !empty($d->tanggal) ? DateToIndo($d->tanggal) : '-' }}
                            </td>
                            <td class="py-1.5 px-3 font-medium text-slate-800 dark:text-slate-200">
                                {{ $d->nama_dept }}
                            </td>
                            <td class="py-1.5 px-3 font-mono-num font-semibold text-slate-700 dark:text-slate-300">
                                <span class="px-1.5 py-0.5 rounded bg-slate-100 dark:bg-slate-800 text-[11px] font-mono-num font-bold">
                                    {{ $d->kode_cabang }}
                                </span>
                            </td>
                            <td class="py-1.5 px-3 text-slate-700 dark:text-slate-300">
                                {{ $d->nama_user }}
                            </td>
                            <!-- Approval Head Dept -->
                            <td class="py-1.5 px-3 text-center">
                                @if ($d->approve_head_dept == '1')
                                    <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-emerald-100 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300 shadow-2xs"
                                        title="Disetujui Head Dept">
                                        <i data-lucide="check" class="w-3 h-3"></i>
                                    </span>
                                @else
                                    <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-amber-100 text-amber-700 dark:bg-amber-950/60 dark:text-amber-300 shadow-2xs"
                                        title="Menunggu Persetujuan Head Dept">
                                        <i data-lucide="clock" class="w-3 h-3"></i>
                                    </span>
                                @endif
                            </td>
                            <!-- Approval Pembelian -->
                            <td class="py-1.5 px-3 text-center">
                                @if ($d->approve_gudang == '1')
                                    <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-emerald-100 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300 shadow-2xs"
                                        title="Disetujui Pembelian">
                                        <i data-lucide="check" class="w-3 h-3"></i>
                                    </span>
                                @else
                                    <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-amber-100 text-amber-700 dark:bg-amber-950/60 dark:text-amber-300 shadow-2xs"
                                        title="Menunggu Persetujuan Pembelian">
                                        <i data-lucide="clock" class="w-3 h-3"></i>
                                    </span>
                                @endif
                            </td>
                            <!-- Status Selesai / Proses -->
                            <td class="py-1.5 px-3 text-center">
                                @if ($d->total_serah_terima == $d->total_bpb && $d->total_bpb > 0)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10.5px] font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800 shadow-2xs">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        <span>Selesai</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10.5px] font-bold bg-rose-50 text-rose-700 dark:bg-rose-950/60 dark:text-rose-300 border border-rose-200 dark:border-rose-800 shadow-2xs">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                        <span>Proses</span>
                                    </span>
                                @endif
                            </td>
                            <!-- Aksi -->
                            <td class="py-1.5 px-3 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    <!-- Approve Head Dept -->
                                    @if ($user_auth_id == $approver_head_dept && $d->approve_head_dept == '0')
                                        <a href="#" class="btnApprove w-6 h-6 rounded-md bg-emerald-50 text-emerald-600 hover:bg-emerald-100 dark:bg-emerald-950/50 dark:hover:bg-emerald-900/50 dark:text-emerald-400 flex items-center justify-center transition cursor-pointer"
                                            no_bpb="{{ Crypt::encrypt($d->no_bpb) }}" data-approve="0"
                                            title="Approve Head Dept">
                                            <i data-lucide="check-circle" class="w-3 h-3"></i>
                                        </a>
                                    @endif

                                    <!-- Approve Pembelian -->
                                    @if ($user_auth_id == '54' && $d->approve_head_dept == '1' && $d->approve_gudang == '0')
                                        <a href="#" class="btnApprove w-6 h-6 rounded-md bg-emerald-50 text-emerald-600 hover:bg-emerald-100 dark:bg-emerald-950/50 dark:hover:bg-emerald-900/50 dark:text-emerald-400 flex items-center justify-center transition cursor-pointer"
                                            no_bpb="{{ Crypt::encrypt($d->no_bpb) }}" data-approve="1"
                                            title="Approve Pembelian">
                                            <i data-lucide="check-circle" class="w-3 h-3"></i>
                                        </a>
                                    @endif

                                    <!-- Edit BPB (Modal) -->
                                    @if ($d->approve_head_dept == '1' && empty($d->tanggal_pembelian) && $user_auth_id == '54')
                                        <a href="#" class="btnEdit w-6 h-6 rounded-md bg-amber-50 text-amber-600 hover:bg-amber-100 dark:bg-amber-950/50 dark:hover:bg-amber-900/50 dark:text-amber-400 flex items-center justify-center transition cursor-pointer"
                                            no_bpb="{{ Crypt::encrypt($d->no_bpb) }}"
                                            title="Edit BPB">
                                            <i data-lucide="edit-3" class="w-3 h-3"></i>
                                        </a>
                                    @endif

                                    <!-- Detail / Show BPB -->
                                    <a href="{{ route('bpbpembelian.show', Crypt::encrypt($d->no_bpb)) }}"
                                        class="w-6 h-6 rounded-md bg-blue-50 text-blue-600 hover:bg-blue-100 dark:bg-blue-950/50 dark:hover:bg-blue-900/50 dark:text-blue-400 flex items-center justify-center transition cursor-pointer"
                                        title="Detail BPPB">
                                        <i data-lucide="file-text" class="w-3 h-3"></i>
                                    </a>

                                    <!-- Delete BPB -->
                                    @if ($d->approve_head_dept == '0' && empty($d->no_bukti))
                                        <form method="POST" class="inline-flex m-0 p-0 deleteform"
                                            action="{{ route('bpbpembelian.delete', Crypt::encrypt($d->no_bpb)) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="delete-confirm w-6 h-6 rounded-md bg-rose-50 text-rose-600 hover:bg-rose-100 dark:bg-rose-950/50 dark:hover:bg-rose-900/50 dark:text-rose-400 flex items-center justify-center transition cursor-pointer"
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
                            <td colspan="9" class="py-8 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <i data-lucide="receipt" class="w-8 h-8 text-slate-300 dark:text-slate-600"></i>
                                    <span class="text-xs">Belum ada data BPPB Pembelian.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- PAGINATION SECTION V2 -->
        @if ($bpb->hasPages())
            <div class="px-4 py-3 border-t border-slate-100 dark:border-slate-800/80 bg-slate-50/50 dark:bg-slate-900/50">
                {{ $bpb->links('v2.components.pagination') }}
            </div>
        @endif
    </div>

</div>

<!-- STATIC BACKDROP MODAL EDIT BPB V2 -->
<div id="modalEditBPB" class="fixed inset-0 z-50 hidden">
    <!-- Backdrop Gelap -->
    <div id="modalEditBackdrop" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>

    <!-- Wrapper Dialog -->
    <div class="fixed inset-0 flex items-center justify-center p-4 overflow-y-auto">
        <div id="modalEditDialog"
            class="relative w-full max-w-4xl bg-white dark:bg-slate-900 rounded-xl shadow-2xl border border-slate-200/80 dark:border-slate-800 transform transition-all duration-150">
            
            <!-- Modal Header Bersih (Sesuai GEMINI.md: Tanpa background solid, border halus) -->
            <div class="flex items-center justify-between px-4 py-3 border-b border-slate-200/80 dark:border-slate-800">
                <div class="flex items-center gap-2">
                    <i data-lucide="edit-3" class="w-4 h-4 text-[#284c9a] dark:text-blue-400"></i>
                    <h5 id="modalEditTitle" class="text-sm font-bold text-slate-800 dark:text-slate-100">Edit BPPB Pembelian</h5>
                </div>
                <button type="button" id="btnCloseModalEdit"
                    class="w-7 h-7 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition cursor-pointer">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>

            <!-- Modal Body Container -->
            <div id="modalEditContent">
                <!-- Konten Edit di-load via AJAX -->
            </div>
        </div>
    </div>
</div>

@endsection

@push('myscript')
<script>
    $(function() {
        const $modal = $("#modalEditBPB");
        const $dialog = $("#modalEditDialog");
        const $content = $("#modalEditContent");

        function openModal() {
            $content.html(`
                <div class="py-12 flex flex-col items-center justify-center gap-2">
                    <div class="w-6 h-6 border-2 border-[#284c9a] border-t-transparent rounded-full animate-spin"></div>
                    <span class="text-xs text-slate-500 font-medium">Memuat form edit BPB...</span>
                </div>
            `);
            $modal.removeClass('hidden');
            $('body').addClass('overflow-hidden');
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        }

        function closeModal() {
            $modal.addClass('hidden');
            $('body').removeClass('overflow-hidden');
            $content.html('');
        }

        function bounceModal() {
            $dialog.addClass('scale-[1.015] shadow-2xl');
            setTimeout(function() {
                $dialog.removeClass('scale-[1.015] shadow-2xl');
            }, 150);
        }

        // Static Backdrop Behavior (Anti-Tutup Sembarangan)
        $("#modalEditBackdrop").on('click', function(e) {
            e.stopPropagation();
            bounceModal();
        });

        $(document).on('keydown', function(e) {
            if (e.key === 'Escape' && !$modal.hasClass('hidden')) {
                bounceModal();
            }
        });

        $("#btnCloseModalEdit").on('click', function() {
            closeModal();
        });

        // Edit BPB modal trigger
        $(document).on('click', '.btnEdit', function(e) {
            e.preventDefault();
            const no_bpb = $(this).attr("no_bpb");
            openModal();
            $.ajax({
                type: 'GET',
                url: `/bpbpembelian/${no_bpb}/edit`,
                success: function(response) {
                    $content.html(response);
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }
                },
                error: function() {
                    $content.html(`
                        <div class="py-8 text-center text-rose-500 font-semibold text-xs">
                            Gagal memuat form edit BPB. Silakan coba lagi.
                        </div>
                    `);
                }
            });
        });

        // Approval Action with SweetAlert
        $(document).on('click', '.btnApprove', function(e) {
            e.preventDefault();
            const no_bpb = $(this).attr("no_bpb");
            const approve = $(this).attr("data-approve") || '0';

            Swal.fire({
                title: 'Konfirmasi Approval BPB',
                text: 'Pastikan data BPB sudah diperiksa dengan teliti sebelum menyetujui.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Setujui',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#ef4444'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Memproses Approval...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                            $.ajax({
                                url: `/bpbpembelian/${no_bpb}/storeapprove`,
                                type: 'POST',
                                data: {
                                    approve: approve,
                                    _token: $('meta[name="csrf-token"]').attr('content')
                                },
                                success: function(res) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil Disetujui!',
                                        text: res.message || 'Data BPPB berhasil diapprove.',
                                        timer: 1500,
                                        showConfirmButton: false
                                    }).then(() => location.reload());
                                },
                                error: function(xhr) {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Gagal!',
                                        text: xhr.responseJSON?.message ?? 'Terjadi kesalahan saat approval.',
                                        confirmButtonColor: '#284c9a'
                                    });
                                }
                            });
                        }
                    });
                }
            });
        });

        // Delete Confirm
        $(".delete-confirm").click(function(e) {
            const form = $(this).closest("form");
            e.preventDefault();
            Swal.fire({
                title: 'Apakah Anda Yakin?',
                text: "Data BPPB ini akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#284c9a',
                cancelButtonColor: '#ef4444',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });

        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
</script>
@endpush
