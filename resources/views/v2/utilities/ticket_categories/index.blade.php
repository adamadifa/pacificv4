@extends('layouts.app')
@section('titlepage', 'Master Kategori Tiket')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <a href="{{ route('dashboard') }}" class="text-slate-400 hover:text-[#284c9a] transition-colors">Home</a>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <a href="{{ route('ticket.index') }}" class="text-slate-400 hover:text-[#284c9a] transition-colors">Ticket</a>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-[#284c9a] dark:text-blue-400 font-bold">Master Kategori Tiket</span>
    </div>
@endsection

@section('content')
<div class="space-y-4">
    @include('layouts.navigation_ticket')

    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-2xs overflow-hidden">
        <!-- Card Header Pacific Navy -->
        <div class="px-3.5 py-2 bg-[#284c9a] text-white flex items-center justify-between gap-2 flex-wrap">
            <div class="flex items-center gap-2">
                <i data-lucide="tags" class="w-3.5 h-3.5 text-blue-200"></i>
                <h2 class="text-xs font-bold text-white uppercase tracking-wider">Daftar Master Kategori Tiket</h2>
            </div>
            <button type="button" id="btnCreateCategory" class="inline-flex items-center gap-1.5 h-[28px] px-3 rounded-lg bg-white/15 hover:bg-white/25 border border-white/20 text-white font-bold text-xs transition cursor-pointer">
                <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                <span>Tambah Kategori Baru</span>
            </button>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto scrollbar-thin">
            <table class="w-full text-left border-collapse text-xs font-sans whitespace-nowrap">
                <thead>
                    <tr class="bg-[#1e3a78] text-white/90">
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-[11px] w-10 text-center">No</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-[11px]">Kode Kategori</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-[11px]">Nama Kategori</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-[11px]">Keterangan</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-[11px] text-center">Manager Dept</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-[11px] text-center">SMM</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-[11px] text-center">RSM</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-[11px] text-center">GM</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-[11px] text-center">Lampiran</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-[11px] text-center">Template</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-[11px] text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80">
                    @forelse ($categories as $index => $c)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                            <td class="py-1.5 px-3 text-center font-mono-num text-slate-500">{{ $index + 1 }}</td>
                            <td class="py-1.5 px-3">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-blue-50 dark:bg-blue-950/50 text-[#284c9a] dark:text-blue-400 font-mono-num font-bold text-[11px] border border-blue-100 dark:border-blue-900">
                                    {{ $c->kode_kategori }}
                                </span>
                            </td>
                            <td class="py-1.5 px-3 font-bold text-slate-800 dark:text-slate-100">{{ $c->nama_kategori }}</td>
                            <td class="py-1.5 px-3 text-slate-500 dark:text-slate-400 max-w-[220px] truncate" title="{{ $c->keterangan }}">
                                {{ $c->keterangan ?? '-' }}
                            </td>
                            <td class="py-1.5 px-3 text-center">
                                @if ($c->perlu_manager_dept)
                                    <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-500 inline-block"></i>
                                @else
                                    <i data-lucide="minus" class="w-3.5 h-3.5 text-slate-300 dark:text-slate-600 inline-block"></i>
                                @endif
                            </td>
                            <td class="py-1.5 px-3 text-center">
                                @if ($c->perlu_smm)
                                    <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-500 inline-block"></i>
                                @else
                                    <i data-lucide="minus" class="w-3.5 h-3.5 text-slate-300 dark:text-slate-600 inline-block"></i>
                                @endif
                            </td>
                            <td class="py-1.5 px-3 text-center">
                                @if ($c->perlu_rsm)
                                    <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-500 inline-block"></i>
                                @else
                                    <i data-lucide="minus" class="w-3.5 h-3.5 text-slate-300 dark:text-slate-600 inline-block"></i>
                                @endif
                            </td>
                            <td class="py-1.5 px-3 text-center">
                                @if ($c->perlu_gm)
                                    <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-500 inline-block"></i>
                                @else
                                    <i data-lucide="minus" class="w-3.5 h-3.5 text-slate-300 dark:text-slate-600 inline-block"></i>
                                @endif
                            </td>
                            <td class="py-1.5 px-3 text-center">
                                @if ($c->wajib_lampiran)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-rose-50 dark:bg-rose-950/50 text-rose-600 dark:text-rose-400 font-bold text-[10px] border border-rose-100 dark:border-rose-900">Wajib</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 font-semibold text-[10px]">Opsional</span>
                                @endif
                            </td>
                            <td class="py-1.5 px-3 text-center">
                                @if ($c->template_file)
                                    <a href="{{ asset($c->template_file) }}" target="_blank" class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-sky-50 dark:bg-sky-950/50 text-sky-600 dark:text-sky-400 hover:bg-sky-100 font-bold text-[11px] border border-sky-100 dark:border-sky-900 transition">
                                        <i data-lucide="download" class="w-3 h-3"></i>
                                        <span>Template</span>
                                    </a>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="py-1.5 px-3 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    <button type="button" class="btnEditCategory w-6 h-6 rounded-md bg-amber-50 dark:bg-amber-950/50 text-amber-600 dark:text-amber-400 hover:bg-amber-100 border border-amber-200/80 dark:border-amber-900 flex items-center justify-center transition cursor-pointer" data-id="{{ $c->id }}" title="Edit">
                                        <i data-lucide="edit-3" class="w-3 h-3"></i>
                                    </button>
                                    <form action="{{ route('ticketcategory.delete', $c->id) }}" method="POST" class="inline-flex m-0 p-0 deleteform">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="delete-confirm w-6 h-6 rounded-md bg-rose-50 dark:bg-rose-950/50 text-rose-600 dark:text-rose-400 hover:bg-rose-100 border border-rose-200/80 dark:border-rose-900 flex items-center justify-center transition cursor-pointer" title="Hapus">
                                            <i data-lucide="trash-2" class="w-3 h-3"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="py-8 text-center text-slate-400">
                                <div class="flex flex-col items-center gap-1.5">
                                    <i data-lucide="folder-open" class="w-8 h-8 text-slate-300 dark:text-slate-700"></i>
                                    <span>Belum ada master kategori tiket. Klik "Tambah Kategori Baru" untuk menambahkan.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<x-modal-form id="modalCategory" size="modal-lg" show="loadmodalform" title="Master Kategori Tiket" />
@endsection

@push('myscript')
<script>
    $(function() {
        $("#btnCreateCategory").click(function(e) {
            e.preventDefault();
            $("#modalCategory").modal("show");
            $("#modalCategory").find(".modal-title").text("Tambah Master Kategori Tiket");
            $("#modalCategory").find(".loadmodalform").load("{{ route('ticketcategory.create') }}", function() {
                if (window.lucide) lucide.createIcons();
            });
        });

        $(".btnEditCategory").click(function(e) {
            e.preventDefault();
            let id = $(this).data("id");
            $("#modalCategory").modal("show");
            $("#modalCategory").find(".modal-title").text("Edit Master Kategori Tiket");
            $("#modalCategory").find(".loadmodalform").load(`/ticketcategory/${id}/edit`, function() {
                if (window.lucide) lucide.createIcons();
            });
        });
    });
</script>
@endpush
