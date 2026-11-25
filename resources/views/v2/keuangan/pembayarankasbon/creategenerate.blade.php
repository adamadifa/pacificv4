<form action="{{ route('pembayarankasbon.generatekasbon') }}" method="POST" id="formGeneratekasbon" class="space-y-3.5" autocomplete="off">
    @csrf

    <!-- Bulan -->
    <div class="space-y-1">
        <label for="bulan_generate_kasbon" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
            Bulan Pemotongan Kasbon <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="calendar" class="w-4 h-4"></i>
            </div>
            <select name="bulan" id="bulan_generate_kasbon"
                class="w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-semibold text-slate-800 dark:text-slate-100 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition appearance-none cursor-pointer">
                <option value="">Pilih Bulan</option>
                @foreach ($list_bulan as $d)
                    <option value="{{ $d['kode_bulan'] }}">{{ $d['nama_bulan'] }}</option>
                @endforeach
            </select>
        </div>
        <p id="error-bulan-generate-kasbon" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 items-center gap-1">
            <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
            <span>Bulan pemotongan wajib dipilih</span>
        </p>
    </div>

    <!-- Tahun -->
    <div class="space-y-1">
        <label for="tahun_generate_kasbon" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
            Tahun Pemotongan Kasbon <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="calendar-days" class="w-4 h-4"></i>
            </div>
            <select name="tahun" id="tahun_generate_kasbon"
                class="w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-semibold text-slate-800 dark:text-slate-100 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition appearance-none cursor-pointer">
                <option value="">Pilih Tahun</option>
                @for ($t = $start_year; $t <= date('Y'); $t++)
                    <option value="{{ $t }}" {{ date('Y') == $t ? 'selected' : '' }}>{{ $t }}</option>
                @endfor
            </select>
        </div>
        <p id="error-tahun-generate-kasbon" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 items-center gap-1">
            <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
            <span>Tahun pemotongan wajib dipilih</span>
        </p>
    </div>

    <!-- Tombol Aksi Kanan -->
    <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100 dark:border-slate-800">
        <button type="button" id="btnCancelGenerateKasbonModal"
            class="h-[36px] px-4 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 text-xs font-bold transition cursor-pointer">
            Batal
        </button>
        <button type="submit" id="btnSubmitGenerateKasbon"
            class="h-[36px] px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold transition flex items-center gap-1.5 shadow-xs cursor-pointer">
            <i data-lucide="layers" class="w-3.5 h-3.5"></i>
            <span>Generate Kasbon</span>
        </button>
    </div>
</form>

<script>
    $(function() {
        if (typeof lucide !== 'undefined') lucide.createIcons();

        const $form = $("#formGeneratekasbon");

        $form.find("#bulan_generate_kasbon").on('change', function() {
            $(this).removeClass('border-rose-500 ring-1 ring-rose-500');
            $form.find("#error-bulan-generate-kasbon").addClass('hidden').removeClass('flex');
        });

        $form.find("#tahun_generate_kasbon").on('change', function() {
            $(this).removeClass('border-rose-500 ring-1 ring-rose-500');
            $form.find("#error-tahun-generate-kasbon").addClass('hidden').removeClass('flex');
        });

        $form.find("#btnCancelGenerateKasbonModal").on('click', function() {
            if (typeof modalGenerateKasbonHelper !== 'undefined') {
                modalGenerateKasbonHelper.close();
            }
        });

        $form.on('submit', function(e) {
            e.preventDefault();
            let hasError = false;

            const bulan = $form.find("#bulan_generate_kasbon").val();
            if (!bulan) {
                $form.find("#error-bulan-generate-kasbon").removeClass('hidden').addClass('flex');
                $form.find("#bulan_generate_kasbon").addClass('border-rose-500 ring-1 ring-rose-500');
                hasError = true;
            }

            const tahun = $form.find("#tahun_generate_kasbon").val();
            if (!tahun) {
                $form.find("#error-tahun-generate-kasbon").removeClass('hidden').addClass('flex');
                $form.find("#tahun_generate_kasbon").addClass('border-rose-500 ring-1 ring-rose-500');
                hasError = true;
            }

            if (hasError) return false;

            const $btn = $form.find("#btnSubmitGenerateKasbon");
            $btn.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            $btn.html(`
                <svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Memproses...</span>
            `);

            this.submit();
        });
    });
</script>
