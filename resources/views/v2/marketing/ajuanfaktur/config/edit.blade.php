@extends('layouts.app')
@section('titlepage', 'Edit Konfigurasi Approval')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Home</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-slate-400">Marketing</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <a href="{{ route('ajuanfaktur.index') }}" class="text-slate-400 hover:text-brand-600">Ajuan Faktur Kredit</a>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <a href="{{ route('ajuanfakturconfig.index') }}" class="text-slate-400 hover:text-brand-600">Konfigurasi</a>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Edit</span>
    </div>
@endsection

@section('content')
<div class="space-y-3.5 w-full max-w-4xl">

    <!-- PAGE TITLE BAR -->
    <div class="flex items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Edit Konfigurasi Approval</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Sesuaikan urutan alur persetujuan untuk ajuan faktur kredit.</p>
        </div>

        <a href="{{ route('ajuanfakturconfig.index') }}"
            class="inline-flex items-center justify-center gap-1.5 h-[34px] px-3.5 rounded-lg border border-slate-200 dark:border-slate-800 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 text-xs font-bold transition shadow-2xs cursor-pointer">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
            <span>Kembali</span>
        </a>
    </div>

    <form action="{{ route('ajuanfakturconfig.update', $config->id) }}" method="POST" id="formConfig">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-3.5 items-start">
            
            <!-- FORM BUILDER URUTAN ROLE (8 COLS) -->
            <div class="lg:col-span-8 rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-2xs overflow-hidden">
                <div class="px-4 py-2.5 border-b border-slate-200/80 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <i data-lucide="list-ordered" class="w-4 h-4 text-[#284c9a] dark:text-blue-400"></i>
                        <h4 class="text-xs font-bold text-slate-800 dark:text-slate-100">Urutan Role Persetujuan</h4>
                    </div>
                    <button type="button" id="btn-add-role"
                        class="inline-flex items-center gap-1 h-[28px] px-2.5 rounded-md bg-blue-50 dark:bg-blue-950/50 hover:bg-blue-100 text-[#284c9a] dark:text-blue-400 text-xs font-bold transition cursor-pointer">
                        <i data-lucide="plus" class="w-3 h-3"></i>
                        <span>Tambah Role</span>
                    </button>
                </div>

                <div class="p-4 space-y-2.5" id="role-container">
                    @php
                        $activeRoles = is_string($config->roles) ? json_decode($config->roles) : $config->roles;
                    @endphp
                    @foreach ($activeRoles as $activeRole)
                        <div class="flex items-center gap-2 role-row">
                            <span class="w-6 h-6 rounded-md bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 flex items-center justify-center font-mono-num font-bold text-xs shrink-0 row-number">
                                {{ $loop->iteration }}
                            </span>
                            <div class="flex-1 relative">
                                <select name="roles[]" required
                                    class="w-full h-[36px] px-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a] transition cursor-pointer">
                                    <option value="">Pilih Role Approval</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->name }}" {{ $activeRole == $role->name ? 'selected' : '' }}>
                                            {{ textUpperCase($role->name) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="button" class="btn-remove-role w-[36px] h-[36px] rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 dark:bg-rose-950/50 dark:hover:bg-rose-900/50 dark:text-rose-400 flex items-center justify-center transition cursor-pointer shrink-0">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </div>
                    @endforeach
                </div>

                <div class="p-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50/30 dark:bg-slate-900/30 flex justify-end">
                    <button type="submit" id="btnSubmitConfig"
                        class="h-[36px] px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] active:bg-[#152a56] text-white text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-xs cursor-pointer">
                        <i data-lucide="save" class="w-3.5 h-3.5"></i>
                        <span>Simpan Perubahan</span>
                    </button>
                </div>
            </div>

            <!-- PANDUAN PENGATURAN (4 COLS) -->
            <div class="lg:col-span-4 rounded-xl bg-blue-50/60 dark:bg-blue-950/20 border border-blue-200 dark:border-blue-900/60 p-4 space-y-2.5">
                <div class="flex items-center gap-2 text-blue-900 dark:text-blue-300">
                    <i data-lucide="info" class="w-4 h-4"></i>
                    <h4 class="text-xs font-bold">Panduan Pengaturan</h4>
                </div>
                <div class="space-y-1.5 text-xs text-slate-600 dark:text-slate-300">
                    <p>1. Sesuaikan alur urutan approval sesuai kebutuhan wewenang organisasi.</p>
                    <p>2. Alur persetujuan transaksi ajuan faktur akan otomatis mengalir dari role urutan teratas ke bawah.</p>
                    <p>3. Role terakhir dalam daftar akan memberikan persetujuan final (status menjadi <b>Disetujui</b>).</p>
                </div>
            </div>

        </div>
    </form>

</div>
@endsection

@push('myscript')
<script>
    $(function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        function updateRowNumbers() {
            $('#role-container .role-row').each(function(index) {
                $(this).find('.row-number').text(index + 1);
            });
        }

        // Add Role
        $('#btn-add-role').on('click', function() {
            const rowCount = $('#role-container .role-row').length + 1;
            const newRow = `
                <div class="flex items-center gap-2 role-row">
                    <span class="w-6 h-6 rounded-md bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 flex items-center justify-center font-mono-num font-bold text-xs shrink-0 row-number">
                        ${rowCount}
                    </span>
                    <div class="flex-1 relative">
                        <select name="roles[]" required
                            class="w-full h-[36px] px-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a] transition cursor-pointer">
                            <option value="">Pilih Role Approval</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->name }}">{{ textUpperCase($role->name) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="button" class="btn-remove-role w-[36px] h-[36px] rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 dark:bg-rose-950/50 dark:hover:bg-rose-900/50 dark:text-rose-400 flex items-center justify-center transition cursor-pointer shrink-0">
                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                    </button>
                </div>
            `;
            $('#role-container').append(newRow);
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
            updateRowNumbers();
        });

        // Remove Role
        $(document).on('click', '.btn-remove-role', function() {
            if ($('#role-container .role-row').length > 1) {
                $(this).closest('.role-row').remove();
                updateRowNumbers();
            } else {
                Swal.fire({
                    title: 'Peringatan',
                    text: 'Minimal harus ada 1 role persetujuan.',
                    icon: 'warning'
                });
            }
        });
    });
</script>
@endpush
