@extends('layouts.app')
@section('titlepage', 'Tambah Konfigurasi Approval')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Home</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-slate-400">Marketing</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <a href="{{ route('ajuanlimit.index') }}" class="text-slate-400 hover:text-brand-600">Ajuan Limit Kredit</a>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <a href="{{ route('ajuanlimitconfig.index') }}" class="text-slate-400 hover:text-brand-600">Konfigurasi</a>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Tambah</span>
    </div>
@endsection

@section('content')
<div class="space-y-3.5 w-full max-w-4xl">

    <!-- PAGE TITLE BAR -->
    <div class="flex items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Tambah Konfigurasi Approval</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Buat alur approval baru berdasarkan rentang nominal limit kredit.</p>
        </div>

        <a href="{{ route('ajuanlimitconfig.index') }}"
            class="inline-flex items-center justify-center gap-1.5 h-[34px] px-3.5 rounded-lg border border-slate-200 dark:border-slate-800 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 text-xs font-bold transition shadow-2xs cursor-pointer">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
            <span>Kembali</span>
        </a>
    </div>

    <form action="{{ route('ajuanlimitconfig.store') }}" method="POST" id="formConfig" autocomplete="off">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-3.5 items-start">
            
            <!-- KOLOM KIRI (8 COLS) -->
            <div class="lg:col-span-8 space-y-3.5">

                <!-- CARD 1: RENTANG NOMINAL -->
                <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-2xs overflow-hidden">
                    <div class="px-4 py-2.5 border-b border-slate-200/80 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50 flex items-center gap-2">
                        <i data-lucide="coins" class="w-4 h-4 text-[#284c9a] dark:text-blue-400"></i>
                        <h4 class="text-xs font-bold text-slate-800 dark:text-slate-100">Rentang Nominal Limit Kredit</h4>
                    </div>

                    <div class="p-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 items-start">
                            
                            <!-- Minimal Nominal -->
                            <div class="field-group">
                                <label for="min_limit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                                    Minimal Nominal <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                                        <i data-lucide="coins" class="w-3.5 h-3.5"></i>
                                    </div>
                                    <input type="text" name="min_limit" id="min_limit" placeholder="0"
                                        class="money w-full h-[36px] pl-8 pr-3 text-xs text-right font-mono-num font-bold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a] transition">
                                </div>
                            </div>

                            <!-- Maksimal Nominal -->
                            <div class="field-group">
                                <label for="max_limit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                                    Maksimal Nominal <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                                        <i data-lucide="coins" class="w-3.5 h-3.5"></i>
                                    </div>
                                    <input type="text" name="max_limit" id="max_limit" placeholder="0"
                                        class="money w-full h-[36px] pl-8 pr-3 text-xs text-right font-mono-num font-bold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a] transition">
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- CARD 2: URUTAN ROLE APPROVAL -->
                <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-2xs overflow-hidden">
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
                        <!-- Baris Default 1 -->
                        <div class="flex items-start gap-2 role-row">
                            <span class="w-[36px] h-[36px] rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 flex items-center justify-center font-mono-num font-bold text-xs shrink-0 row-number">
                                1
                            </span>
                            <div class="flex-1 relative field-group">
                                <select name="roles[]"
                                    class="role-select w-full h-[36px] px-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a] transition cursor-pointer">
                                    <option value="">Pilih Role Approval</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->name }}">{{ textUpperCase($role->name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="button" class="btn-remove-role w-[36px] h-[36px] rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 dark:bg-rose-950/50 dark:hover:bg-rose-900/50 dark:text-rose-400 flex items-center justify-center transition cursor-pointer shrink-0" title="Hapus Baris">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>

                    <div class="p-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50/30 dark:bg-slate-900/30 flex justify-end">
                        <button type="submit" id="btnSubmitConfig"
                            class="h-[36px] px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] active:bg-[#152a56] text-white text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-xs cursor-pointer">
                            <i data-lucide="save" class="w-3.5 h-3.5"></i>
                            <span>Simpan Konfigurasi</span>
                        </button>
                    </div>
                </div>

            </div>

            <!-- PANDUAN PENGATURAN (4 COLS) -->
            <div class="lg:col-span-4 rounded-xl bg-blue-50/60 dark:bg-blue-950/20 border border-blue-200 dark:border-blue-900/60 p-4 space-y-2.5">
                <div class="flex items-center gap-2 text-blue-900 dark:text-blue-300">
                    <i data-lucide="info" class="w-4 h-4"></i>
                    <h4 class="text-xs font-bold">Panduan Pengaturan</h4>
                </div>
                <div class="space-y-1.5 text-xs text-slate-600 dark:text-slate-300">
                    <p>1. Tentukan rentang nominal limit kredit (misal: <b>Rp 0</b> s/d <b>Rp 5.000.000</b>).</p>
                    <p>2. Tambahkan role sesuai urutan tahapan approval yang diinginkan (dimulai dari role urutan ke-1).</p>
                    <p>3. Alur persetujuan transaksi ajuan limit akan otomatis mengalir dari role teratas ke bawah.</p>
                    <p>4. Role terakhir dalam daftar akan memberikan persetujuan final (status menjadi <b>Disetujui</b>).</p>
                    <p>5. Pastikan rentang nominal tidak tumpang tindih dengan konfigurasi rentang lainnya.</p>
                </div>
            </div>

        </div>
    </form>

</div>
@endsection

@push('myscript')
<script>
    $(function() {
        const $form = $('#formConfig');

        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        // Inisialisasi Mask Money
        function initMoney() {
            if ($.fn.maskMoney) {
                $form.find(".money").maskMoney({
                    prefix: '',
                    thousands: '.',
                    decimal: ',',
                    precision: 0
                });
            } else if ($.fn.mask) {
                $form.find(".money").mask("#.##0", {
                    reverse: true
                });
            }
        }
        initMoney();

        function updateRowNumbers() {
            $('#role-container .role-row').each(function(index) {
                $(this).find('.row-number').text(index + 1);
            });
        }

        // Live validation cleanup helper
        function markError($el, msg) {
            $el.addClass('!border-rose-500 !ring-1 !ring-rose-500');
            $el.closest('.relative').find('.input-prefix-icon').addClass('!text-rose-500');
            const $parent = $el.closest('.field-group');
            if ($parent.find('.field-error-msg').length === 0) {
                $parent.append(`
                    <p class="field-error-msg text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 flex items-center gap-1">
                        <svg class="w-3 h-3 text-rose-500 shrink-0 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" stroke-width="2"/>
                            <line x1="12" y1="8" x2="12" y2="12" stroke-width="2"/>
                            <line x1="12" y1="16" x2="12.01" y2="16" stroke-width="2"/>
                        </svg>
                        <span>${msg}</span>
                    </p>
                `);
            }
        }

        function clearError($el) {
            $el.removeClass('!border-rose-500 !ring-1 !ring-rose-500');
            $el.closest('.relative').find('.input-prefix-icon').removeClass('!text-rose-500');
            $el.closest('.field-group').find('.field-error-msg').remove();
        }

        $form.on('input change', 'input, select', function() {
            clearError($(this));
        });

        // Add Role
        $('#btn-add-role').on('click', function() {
            const rowCount = $('#role-container .role-row').length + 1;
            const newRow = `
                <div class="flex items-start gap-2 role-row">
                    <span class="w-[36px] h-[36px] rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 flex items-center justify-center font-mono-num font-bold text-xs shrink-0 row-number">
                        ${rowCount}
                    </span>
                    <div class="flex-1 relative field-group">
                        <select name="roles[]"
                            class="role-select w-full h-[36px] px-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a] transition cursor-pointer">
                            <option value="">Pilih Role Approval</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->name }}">{{ textUpperCase($role->name) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="button" class="btn-remove-role w-[36px] h-[36px] rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 dark:bg-rose-950/50 dark:hover:bg-rose-900/50 dark:text-rose-400 flex items-center justify-center transition cursor-pointer shrink-0" title="Hapus Baris">
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
                const $select = $(this).closest('.role-row').find('select');
                $select.val('');
                markError($select, 'Minimal harus ada 1 role persetujuan.');
            }
        });

        // Client-side Validation on Submit
        $form.on('submit', function(e) {
            let hasError = false;
            let $firstError = null;

            // Clear previous errors
            $form.find('.field-error-msg').remove();
            $form.find('!border-rose-500').removeClass('!border-rose-500 !ring-1 !ring-rose-500');

            const minLimit = $('#min_limit').val().trim();
            const maxLimit = $('#max_limit').val().trim();

            if (!minLimit) {
                hasError = true;
                markError($('#min_limit'), 'Minimal nominal wajib diisi');
                if (!$firstError) $firstError = $('#min_limit');
            }

            if (!maxLimit) {
                hasError = true;
                markError($('#max_limit'), 'Maksimal nominal wajib diisi');
                if (!$firstError) $firstError = $('#max_limit');
            }

            // Validasi roles
            const $roleSelects = $('#role-container select[name="roles[]"]');
            if ($roleSelects.length === 0) {
                hasError = true;
                markError($('#btn-add-role'), 'Tambahkan minimal 1 role approval');
                if (!$firstError) $firstError = $('#btn-add-role');
            } else {
                $roleSelects.each(function() {
                    const val = $(this).val();
                    if (!val) {
                        hasError = true;
                        markError($(this), 'Pilih role untuk tahapan ini');
                        if (!$firstError) $firstError = $(this);
                    }
                });
            }

            if (hasError) {
                e.preventDefault();
                if ($firstError) {
                    $firstError.focus();
                }
                return false;
            }

            // Anti Double Submit
            const $btn = $('#btnSubmitConfig');
            $btn.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            $btn.html(`
                <svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Menyimpan...</span>
            `);
        });
    });
</script>
@endpush
