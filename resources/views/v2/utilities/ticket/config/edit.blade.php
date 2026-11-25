@extends('layouts.app')
@section('titlepage', 'Edit Konfigurasi Approval Tiket')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <a href="{{ route('dashboard') }}" class="text-slate-400 hover:text-[#284c9a] transition-colors">Home</a>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <a href="{{ route('ticket.index') }}" class="text-slate-400 hover:text-[#284c9a] transition-colors">Tiket</a>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <a href="{{ route('ticketconfig.index') }}" class="text-slate-400 hover:text-[#284c9a] transition-colors">Config Approval</a>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-[#284c9a] dark:text-blue-400 font-bold">Edit Config</span>
    </div>
@endsection

@section('content')
<form action="{{ route('ticketconfig.update', $config->id) }}" method="POST" id="formConfigEdit">
    @csrf
    @method('PUT')
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-start">
        <div class="lg:col-span-4 rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-4 shadow-2xs space-y-3">
            <div class="flex items-center gap-2 pb-2 mb-2.5 border-b border-slate-200/80 dark:border-slate-800">
                <i data-lucide="target" class="w-4 h-4 text-[#284c9a] dark:text-blue-400"></i>
                <h3 class="text-xs font-bold text-slate-800 dark:text-slate-100">Cakupan Konfigurasi</h3>
            </div>

            <div>
                <label for="kode_dept_config_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Departemen <span class="font-normal text-slate-400">(opsional)</span></label>
                <div class="relative select2-icon-wrapper">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none z-20 text-slate-400">
                        <i data-lucide="briefcase" class="w-3.5 h-3.5"></i>
                    </div>
                    <select name="kode_dept" id="kode_dept_config_edit" class="select2ConfigEdit w-full" data-placeholder="ALL DEPARTEMEN">
                        <option value="">ALL DEPARTEMEN</option>
                        @foreach ($departemen as $d)
                            <option value="{{ $d->kode_dept }}" {{ $config->kode_dept == $d->kode_dept ? 'selected' : '' }}>{{ $d->nama_dept }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label for="kode_cabang_config_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Cabang <span class="font-normal text-slate-400">(opsional)</span></label>
                <div class="relative select2-icon-wrapper">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none z-20 text-slate-400">
                        <i data-lucide="map-pin" class="w-3.5 h-3.5"></i>
                    </div>
                    <select name="kode_cabang" id="kode_cabang_config_edit" class="select2ConfigEdit w-full" data-placeholder="ALL CABANG">
                        <option value="">ALL CABANG</option>
                        @foreach ($cabang as $d)
                            <option value="{{ $d->kode_cabang }}" {{ $config->kode_cabang == $d->kode_cabang ? 'selected' : '' }}>{{ $d->nama_cabang }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="lg:col-span-8 rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-2xs overflow-hidden">
            <div class="p-4 space-y-3">
                <div class="flex items-center justify-between pb-2 mb-2.5 border-b border-slate-200/80 dark:border-slate-800">
                    <div class="flex items-center gap-2">
                        <i data-lucide="git-merge" class="w-4 h-4 text-[#284c9a] dark:text-blue-400"></i>
                        <h3 class="text-xs font-bold text-slate-800 dark:text-slate-100">Alur Persetujuan</h3>
                    </div>
                    <span id="step-count" class="px-2 py-0.5 rounded-md bg-blue-50 dark:bg-blue-950/60 text-[#284c9a] dark:text-blue-400 text-[11px] font-bold font-mono-num">0 Steps</span>
                </div>

                <div class="flex flex-col sm:flex-row gap-2 p-3 rounded-xl bg-slate-50 dark:bg-slate-800/40 border border-slate-200/80 dark:border-slate-800">
                    <div class="flex-1 relative select2-icon-wrapper">
                        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none z-20 text-slate-400">
                            <i data-lucide="shield" class="w-3.5 h-3.5"></i>
                        </div>
                        <select id="role-selector" class="select2ConfigEdit w-full" data-placeholder="Pilih Role...">
                            <option value="">Pilih Role...</option>
                            @foreach ($roles as $d)
                                <option value="{{ $d->name }}">{{ textUpperCase($d->name) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="button" id="btn-add-role" class="h-[36px] px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold flex items-center justify-center gap-1.5 transition shrink-0 cursor-pointer">
                        <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                        <span>Tambah Ke Alur</span>
                    </button>
                </div>

                <div id="empty-state" class="hidden rounded-xl border-2 border-dashed border-slate-200 dark:border-slate-800 p-8 text-center">
                    <i data-lucide="layers" class="w-8 h-8 text-slate-300 dark:text-slate-700 mx-auto mb-2"></i>
                    <h4 class="text-xs font-bold text-slate-600 dark:text-slate-300">Belum Ada Alur Persetujuan</h4>
                    <p class="text-[11px] text-slate-400 mt-0.5">Pilih role di atas untuk mulai merancang hierarki persetujuan tiket.</p>
                </div>

                <ul id="sortable-roles" class="space-y-2 sortable-roles">
                    @foreach ($config->roles as $role)
                        <li class="role-item list-none" data-role="{{ $role }}">
                            <div class="flex items-center gap-3 p-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg hover:border-[#284c9a] transition">
                                <i data-lucide="grip-vertical" class="w-4 h-4 text-slate-400 drag-handle cursor-move"></i>
                                <div class="w-7 h-7 rounded-lg bg-[#284c9a] text-white text-xs font-bold flex items-center justify-center shrink-0">
                                    <span class="step-num-text">{{ $loop->iteration }}</span>
                                </div>
                                <div class="flex-1 text-xs font-bold text-slate-800 dark:text-slate-100">
                                    {{ textUpperCase($role) }}
                                    <input type="hidden" name="roles[]" value="{{ $role }}">
                                </div>
                                <button type="button" class="btn-remove-role w-7 h-7 rounded-lg bg-rose-50 dark:bg-rose-950/50 text-rose-600 hover:bg-rose-600 hover:text-white flex items-center justify-center transition cursor-pointer">
                                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                </button>
                            </div>
                        </li>
                    @endforeach
                </ul>

                <div id="admin-terminal-indicator" class="rounded-lg bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-800 p-2.5 flex items-center gap-2 text-amber-800 dark:text-amber-300 text-xs font-medium">
                    <i data-lucide="terminal" class="w-4 h-4 shrink-0"></i>
                    <span>Setelah semua role di atas menyetujui, tiket akan masuk ke <strong>Admin IT</strong> untuk diproses.</span>
                </div>
            </div>

            <div class="px-4 py-3 bg-slate-50 dark:bg-slate-800/40 border-t border-slate-100 dark:border-slate-800 flex items-center justify-end gap-2">
                <a href="{{ route('ticketconfig.index') }}" class="h-[36px] px-4 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-50 flex items-center transition">Batal</a>
                <button type="submit" class="h-[36px] px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold flex items-center gap-1.5 transition cursor-pointer">
                    <i data-lucide="save" class="w-3.5 h-3.5"></i>
                    <span>Update Konfigurasi</span>
                </button>
            </div>
        </div>
    </div>
</form>
@endsection

@push('myscript')
<script>
    $(function() {
        $('.select2ConfigEdit').each(function() {
            $(this).select2({
                placeholder: $(this).data('placeholder') || 'Pilih...',
                allowClear: true
            });
        });

        $("#sortable-roles").sortable({
            handle: ".drag-handle",
            update: function() { updateStepNumbers(); }
        });

        function updateStepNumbers() {
            let count = 0;
            $(".role-item").each(function(index) {
                $(this).find(".step-num-text").text(index + 1);
                count++;
            });
            $("#step-count").text(count + " Steps");
            if (count > 0) {
                $("#empty-state").addClass('hidden').hide();
                $("#sortable-roles").show();
                $("#admin-terminal-indicator").removeClass('hidden').show();
            } else {
                $("#empty-state").removeClass('hidden').show();
                $("#sortable-roles").hide();
                $("#admin-terminal-indicator").addClass('hidden').hide();
            }
        }

        function addRole(role) {
            if (!role) return;
            let exists = false;
            $(".role-item").each(function() { if ($(this).data('role') === role) exists = true; });
            if (exists) { toastr.warning("Role sudah ada dalam alur ini."); return; }
            let roleUpper = role.toUpperCase();
            let item = `
                <li class="role-item list-none" data-role="${role}">
                    <div class="flex items-center gap-3 p-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg hover:border-[#284c9a] transition">
                        <i data-lucide="grip-vertical" class="w-4 h-4 text-slate-400 drag-handle cursor-move"></i>
                        <div class="w-7 h-7 rounded-lg bg-[#284c9a] text-white text-xs font-bold flex items-center justify-center shrink-0">
                            <span class="step-num-text">0</span>
                        </div>
                        <div class="flex-1 text-xs font-bold text-slate-800 dark:text-slate-100">
                            ${roleUpper}
                            <input type="hidden" name="roles[]" value="${role}">
                        </div>
                        <button type="button" class="btn-remove-role w-7 h-7 rounded-lg bg-rose-50 dark:bg-rose-950/50 text-rose-600 hover:bg-rose-600 hover:text-white flex items-center justify-center transition cursor-pointer">
                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                        </button>
                    </div>
                </li>
            `;
            $("#sortable-roles").append(item);
            $("#role-selector").val('').trigger('change');
            updateStepNumbers();
            if (window.lucide) lucide.createIcons();
        }

        $("#btn-add-role").click(function() { addRole($("#role-selector").val()); });
        $(document).on('click', '.btn-remove-role', function() {
            $(this).closest('li').remove();
            updateStepNumbers();
        });

        updateStepNumbers();
    });
</script>
@endpush
