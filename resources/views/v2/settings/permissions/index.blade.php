@extends('layouts.app')
@section('titlepage', 'Permissions')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Home</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-slate-400">Settings</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Permissions</span>
    </div>
@endsection

@section('content')
<div class="space-y-3.5 w-full">

    <!-- PAGE TITLE BAR & ACTION BUTTONS -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Manajemen Permission</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Kelola hak akses sistem, grup permission, serta keterkaitannya dengan role & user.</p>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            <button type="button" id="btncreatePermission"
                class="inline-flex items-center justify-center gap-1.5 h-[34px] px-3.5 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] active:bg-[#152a57] text-white text-xs font-bold transition shadow-xs cursor-pointer shrink-0">
                <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                <span>Tambah Permission</span>
            </button>
        </div>
    </div>

    <!-- FILTER SECTION (NO-LABEL, COMPACT H-[34PX], LUCIDE PREFIX, ROUNDED-LG) -->
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-3 shadow-2xs transition-colors duration-200">
        <form action="{{ route('permissions.index') }}" method="GET" id="formSearch" class="space-y-2">
            <div class="grid grid-cols-1 sm:grid-cols-12 gap-2 items-center">
                
                <!-- Filter Group (Col-span 5) -->
                <div class="sm:col-span-5 relative select2-container-v2">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                        <i data-lucide="folder" class="w-3.5 h-3.5"></i>
                    </div>
                    <select name="id_permission_group" id="id_permission_group_search" class="select2GroupSearch w-full">
                        <option value="">Semua Group Permission</option>
                        @foreach ($permission_groups as $g)
                            <option value="{{ $g->id }}" {{ Request('id_permission_group') == $g->id ? 'selected' : '' }}>
                                {{ $g->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Live Table Search (Col-span 5) -->
                <div class="sm:col-span-5 relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="search" class="w-3.5 h-3.5"></i>
                    </div>
                    <input type="text" id="tableSearch"
                        placeholder="Filter cepat nama permission di tabel ini..."
                        class="w-full h-[34px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                </div>

                <!-- Tombol Aksi Filter (Col-span 2) -->
                <div class="sm:col-span-2 flex items-center gap-1.5">
                    <button type="submit"
                        class="flex-1 h-[34px] px-3 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] active:bg-[#152a57] text-white text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-xs cursor-pointer">
                        <i data-lucide="filter" class="w-3.5 h-3.5"></i>
                        <span>Filter</span>
                    </button>
                    <a href="{{ route('permissions.index') }}" title="Reset Filter"
                        class="h-[34px] w-[34px] rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700/60 text-slate-600 dark:text-slate-300 flex items-center justify-center transition shadow-2xs shrink-0 cursor-pointer">
                        <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                    </a>
                </div>

            </div>
        </form>
    </div>

    <!-- DATA TABLE CARD (V2 STANDARD ~36PX ROW HEIGHT) -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 overflow-hidden shadow-2xs">
        <div class="overflow-x-auto max-h-[65vh] overflow-y-auto">
            <table class="w-full text-xs text-left border-separate border-spacing-0 whitespace-nowrap">
                <thead class="sticky top-0 z-10">
                    <tr class="bg-[#284c9a] text-white">
                        <th colspan="4" class="px-3.5 py-2 bg-[#284c9a] text-white border-0">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-1.5">
                                    <i data-lucide="key-round" class="w-3.5 h-3.5 text-blue-200"></i>
                                    <span class="text-xs font-bold text-white uppercase tracking-wider">Daftar Permission</span>
                                </div>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-white/15 text-white">
                                    Total: {{ number_format($permissions->count(), 0, ',', '.') }} Permissions
                                </span>
                            </div>
                        </th>
                    </tr>
                    <tr class="bg-[#1e3a78] text-white text-[11px]">
                        <th class="py-2 px-3 text-center font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 w-16">NO.</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 min-w-[240px]">PERMISSION NAME</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 min-w-[180px]">GROUP</th>
                        <th class="py-2 px-3 text-center font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 w-28">AKSI</th>
                    </tr>
                </thead>
                <tbody id="permissionTableBody" class="divide-y divide-slate-100 dark:divide-slate-800/80">
                    @forelse ($permissions as $d)
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition-colors">
                            <td class="py-1.5 px-3 text-center font-mono-num text-slate-500">
                                {{ $loop->iteration }}
                            </td>
                            <td class="py-1.5 px-3">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-mono-num font-bold bg-blue-50 text-[#284c9a] dark:bg-blue-950/60 dark:text-blue-300 border border-blue-200/70 dark:border-blue-800/60 lowercase">
                                    {{ $d->name }}
                                </span>
                            </td>
                            <td class="py-1.5 px-3 font-semibold text-slate-700 dark:text-slate-300">
                                {{ $d->group_name }}
                            </td>
                            <td class="py-1.5 px-3 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    <button type="button" id="{{ Crypt::encrypt($d->id) }}"
                                        class="detailPermission w-6 h-6 rounded-md bg-sky-50 text-sky-600 hover:bg-sky-100 dark:bg-sky-950/50 dark:text-sky-400 inline-flex items-center justify-center transition shadow-2xs cursor-pointer"
                                        title="Detail & Kelola Akses">
                                        <i data-lucide="info" class="w-3 h-3"></i>
                                    </button>
                                    <button type="button" id="{{ Crypt::encrypt($d->id) }}"
                                        class="editPermission w-6 h-6 rounded-md bg-amber-50 text-amber-600 hover:bg-amber-100 dark:bg-amber-950/50 dark:text-amber-400 inline-flex items-center justify-center transition shadow-2xs cursor-pointer"
                                        title="Edit Permission">
                                        <i data-lucide="edit-3" class="w-3 h-3"></i>
                                    </button>
                                    <form method="POST" action="{{ route('permissions.delete', Crypt::encrypt($d->id)) }}"
                                        class="inline-flex m-0 p-0 deleteform">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="delete-confirm w-6 h-6 rounded-md bg-rose-50 text-rose-600 hover:bg-rose-100 dark:bg-rose-950/50 dark:text-rose-400 inline-flex items-center justify-center transition shadow-2xs cursor-pointer"
                                            title="Hapus Permission">
                                            <i data-lucide="trash-2" class="w-3 h-3"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-8 text-center text-slate-400 dark:text-slate-500">
                                <div class="flex flex-col items-center justify-center gap-1.5">
                                    <i data-lucide="key-round" class="w-8 h-8 stroke-[1.2] text-slate-300 dark:text-slate-600"></i>
                                    <span class="text-xs font-semibold">Tidak ada data Permission ditemukan</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- MODAL PERMISSION (STATIC BACKDROP, EXPLICIT CLOSE) -->
<div class="modal fade" id="modalPermission" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered" id="modalPermissionDialog">
        <div class="modal-content rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-2xl overflow-hidden transition-all duration-150">
            <div class="flex items-center justify-between px-4 py-3 bg-white dark:bg-slate-900 border-b border-slate-200/80 dark:border-slate-800">
                <div class="flex items-center gap-2">
                    <i data-lucide="key-round" class="w-4 h-4 text-[#284c9a] dark:text-blue-400"></i>
                    <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100 modal-title">Permission</h3>
                </div>
                <button type="button" data-bs-dismiss="modal"
                    class="w-7 h-7 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition cursor-pointer">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            <div class="p-4 sm:p-5 max-h-[85vh] overflow-y-auto" id="loadmodalPermission"></div>
        </div>
    </div>
</div>

@endsection

@push('myscript')
<script>
    $(function() {
        if (typeof lucide !== 'undefined') lucide.createIcons();

        $('.select2GroupSearch').select2({ placeholder: 'Semua Group Permission', allowClear: true, width: '100%' });

        $("#tableSearch").on("keyup", function() {
            const value = $(this).val().toLowerCase();
            $("#permissionTableBody tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });

        $('#modalPermission').on('click', function(e) {
            if ($(e.target).is('#modalPermission')) {
                const $dialog = $(this).find('.modal-content');
                $dialog.addClass('scale-[1.015]');
                setTimeout(() => $dialog.removeClass('scale-[1.015]'), 150);
            }
        });

        function loading() {
            $("#loadmodalPermission").html(`
                <div class="flex flex-col items-center justify-center py-10 gap-3">
                    <svg class="animate-spin h-7 w-7 text-[#284c9a] dark:text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                    <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Memuat data...</span>
                </div>
            `);
        }

        $("#btncreatePermission").click(function(e) {
            e.preventDefault();
            $("#modalPermissionDialog").removeClass('modal-lg').addClass('modal-md');
            loading();
            $('#modalPermission').modal("show");
            $('#modalPermission').find('.modal-title').text("Tambah Permission Baru");
            $("#loadmodalPermission").load('/permissions/create', function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });

        $(".editPermission").click(function(e) {
            e.preventDefault();
            const id = $(this).attr("id");
            $("#modalPermissionDialog").removeClass('modal-lg').addClass('modal-md');
            loading();
            $('#modalPermission').modal("show");
            $('#modalPermission').find('.modal-title').text("Edit Permission");
            $("#loadmodalPermission").load(`/permissions/${id}/edit`, function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });

        $(".detailPermission").click(function(e) {
            e.preventDefault();
            const id = $(this).attr("id");
            $("#modalPermissionDialog").removeClass('modal-md').addClass('modal-lg');
            loading();
            $('#modalPermission').modal("show");
            $('#modalPermission').find('.modal-title').text("Detail & Kelola Akses Permission");
            $("#loadmodalPermission").load(`/permissions/${id}/show`, function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });
    });
</script>
@endpush
