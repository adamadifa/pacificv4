<form action="{{ route('pembayaranpiutangkaryawan.store') }}" method="POST" id="formPembayaranpituangkaryawan" class="space-y-3" autocomplete="off">
    @csrf

    <!-- Jenis Pembayaran -->
    <div class="space-y-1">
        <label for="jenis_bayar_pk" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
            Jenis Pembayaran <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="credit-card" class="w-4 h-4"></i>
            </div>
            <select name="jenis_bayar" id="jenis_bayar_pk"
                class="w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-semibold text-slate-800 dark:text-slate-100 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition appearance-none cursor-pointer">
                <option value="">Pilih Jenis Pembayaran</option>
                <option value="1">Potong Gaji</option>
                <option value="2">Potong Komisi</option>
                <option value="3">Titipan Pelanggan</option>
                <option value="4">Lainnya</option>
            </select>
        </div>
        <p id="error-jenis-bayar-pk" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 items-center gap-1">
            <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
            <span>Jenis pembayaran wajib dipilih</span>
        </p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 items-start">
        <!-- Bulan -->
        <div class="space-y-1">
            <label for="bulan_bayar_pk" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                Bulan <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                </div>
                <select name="bulan" id="bulan_bayar_pk"
                    class="w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-semibold text-slate-800 dark:text-slate-100 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition appearance-none cursor-pointer">
                    <option value="">Pilih Bulan</option>
                    @foreach ($list_bulan as $d)
                        <option value="{{ $d['kode_bulan'] }}" {{ Request('bulan') == $d['kode_bulan'] ? 'selected' : '' }}>
                            {{ $d['nama_bulan'] }}
                        </option>
                    @endforeach
                </select>
            </div>
            <p id="error-bulan-bayar-pk" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 items-center gap-1">
                <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
                <span>Bulan wajib dipilih</span>
            </p>
        </div>

        <!-- Tahun -->
        <div class="space-y-1">
            <label for="tahun_bayar_pk" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                Tahun <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar-days" class="w-4 h-4"></i>
                </div>
                <select name="tahun" id="tahun_bayar_pk"
                    class="w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-semibold text-slate-800 dark:text-slate-100 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition appearance-none cursor-pointer">
                    <option value="">Pilih Tahun</option>
                    @for ($t = $start_year; $t <= date('Y'); $t++)
                        <option value="{{ $t }}"
                            @if (!empty(Request('tahun'))) {{ Request('tahun') == $t ? 'selected' : '' }}
                            @else {{ date('Y') == $t ? 'selected' : '' }} @endif>
                            {{ $t }}
                        </option>
                    @endfor
                </select>
            </div>
            <p id="error-tahun-bayar-pk" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 items-center gap-1">
                <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
                <span>Tahun wajib dipilih</span>
            </p>
        </div>
    </div>

    <!-- Jumlah -->
    <div class="space-y-1">
        <label for="jumlah_bayar_pk" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
            Jumlah Pembayaran <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="dollar-sign" class="w-4 h-4"></i>
            </div>
            <input type="text" id="jumlah_bayar_pk" name="jumlah" placeholder="0"
                class="money w-full h-[36px] pl-9 pr-3 text-right rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-bold text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
        </div>
        <p id="error-jumlah-bayar-pk" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 items-center gap-1">
            <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
            <span>Jumlah pembayaran wajib diisi</span>
        </p>
    </div>

    <!-- Tombol Aksi Kanan (Right-Aligned) -->
    <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100 dark:border-slate-800">
        <button type="button" id="btnCancelBayarPkModal"
            class="h-[36px] px-4 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 text-xs font-bold transition cursor-pointer">
            Batal
        </button>
        <button type="submit" id="btnSimpanBayarPk"
            class="h-[36px] px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold transition flex items-center gap-1.5 shadow-xs cursor-pointer">
            <i data-lucide="send" class="w-3.5 h-3.5"></i>
            <span>Simpan Pembayaran</span>
        </button>
    </div>
</form>

<script>
    $(function() {
        if (typeof lucide !== 'undefined') lucide.createIcons();

        const $form = $("#formPembayaranpituangkaryawan");

        $form.find(".money").maskMoney({
            thousands: '.',
            decimal: ',',
            precision: 0
        });

        $form.find("#jenis_bayar_pk").on('change', function() {
            $(this).removeClass('border-rose-500 ring-1 ring-rose-500');
            $form.find("#error-jenis-bayar-pk").addClass('hidden').removeClass('flex');
        });
        $form.find("#bulan_bayar_pk").on('change', function() {
            $(this).removeClass('border-rose-500 ring-1 ring-rose-500');
            $form.find("#error-bulan-bayar-pk").addClass('hidden').removeClass('flex');
        });
        $form.find("#tahun_bayar_pk").on('change', function() {
            $(this).removeClass('border-rose-500 ring-1 ring-rose-500');
            $form.find("#error-tahun-bayar-pk").addClass('hidden').removeClass('flex');
        });
        $form.find("#jumlah_bayar_pk").on('input change keyup', function() {
            $(this).removeClass('border-rose-500 ring-1 ring-rose-500');
            $form.find("#error-jumlah-bayar-pk").addClass('hidden').removeClass('flex');
        });

        $form.find("#btnCancelBayarPkModal").on('click', function() {
            if (typeof modalBayarPiutangHelper !== 'undefined') {
                modalBayarPiutangHelper.close();
            }
        });
    });
</script>
