<form action="{{ route('targetkomisi.updateposisi', Crypt::encrypt($targetkomisi->kode_target)) }}" method="POST" id="formEditPosisi" autocomplete="off" class="space-y-3">
    @csrf
    @method('PUT')

    <!-- 1. Kode Target (Readonly) -->
    <div class="field-group">
        <label for="kode_target_posisi" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Kode Target
        </label>
        <div class="relative">
            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="barcode" class="w-3.5 h-3.5"></i>
            </div>
            <input type="text" id="kode_target_posisi" value="{{ $targetkomisi->kode_target }}" readonly
                class="w-full h-[36px] pl-8 pr-3 text-xs font-mono-num font-bold rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 cursor-not-allowed">
        </div>
    </div>

    <!-- 2. Cabang (Readonly) -->
    <div class="field-group">
        <label for="cabang_posisi" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Cabang
        </label>
        <div class="relative">
            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="building-2" class="w-3.5 h-3.5"></i>
            </div>
            <input type="text" id="cabang_posisi" value="{{ textUpperCase($targetkomisi->nama_cabang) }}" readonly
                class="w-full h-[36px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 cursor-not-allowed">
        </div>
    </div>

    <!-- 3. Status Target -->
    <div class="field-group">
        <label for="status_posisi_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Status Target <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="check-circle" class="w-3.5 h-3.5"></i>
            </div>
            <select name="status" id="status_posisi_edit"
                class="w-full h-[36px] pl-8 pr-8 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition appearance-none cursor-pointer">
                <option value="0" {{ $targetkomisi->status == '0' ? 'selected' : '' }}>Pending</option>
                <option value="1" {{ $targetkomisi->status == '1' ? 'selected' : '' }}>Disetujui</option>
            </select>
            <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                <i data-lucide="chevron-down" class="w-3 h-3"></i>
            </div>
        </div>
    </div>

    <!-- 4. Posisi Ajuan / Role -->
    <div class="field-group">
        <label for="posisi_ajuan_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Posisi Ajuan <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="shield" class="w-3.5 h-3.5"></i>
            </div>
            <select name="posisi_ajuan" id="posisi_ajuan_edit"
                class="w-full h-[36px] pl-8 pr-8 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition appearance-none cursor-pointer">
                <option value="">Pilih Posisi Ajuan</option>
                @foreach ($roles as $role)
                    <option value="{{ $role->id }}" {{ $current_role_id == $role->id ? 'selected' : '' }}>
                        {{ textUpperCase($role->name) }}
                    </option>
                @endforeach
            </select>
            <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                <i data-lucide="chevron-down" class="w-3 h-3"></i>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="pt-2 border-t border-slate-200/80 dark:border-slate-800 flex items-center justify-end gap-2">
        <button type="button" class="btn-close-modal h-[34px] px-4 rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 text-xs font-bold transition cursor-pointer">
            Batal
        </button>
        <button type="submit" id="btnSubmitPosisi"
            class="inline-flex items-center justify-center gap-1.5 h-[34px] px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] active:bg-[#152a56] text-white text-xs font-bold transition shadow-xs cursor-pointer">
            <i data-lucide="check" class="w-3.5 h-3.5"></i>
            <span>Update Posisi</span>
        </button>
    </div>
</form>

<script>
    $(function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        const $form = $("#formEditPosisi");
        const $btnSubmit = $form.find("#btnSubmitPosisi");

        $form.on('submit', function() {
            $btnSubmit.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            $btnSubmit.html(`
                <svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
                <span>Menyimpan...</span>
            `);
        });
    });
</script>
