<form action="{{ route('piutangkaryawan.store') }}" id="formPiutangKaryawan" method="POST" class="space-y-3.5" autocomplete="off">
    @csrf
    <input type="hidden" name="kategori" value="{{ $kategori }}">

    <!-- Baris Header Form (No Pinjaman & Tanggal Pengajuan) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
        <div class="space-y-1">
            <label class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                No. Pinjaman
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="barcode" class="w-4 h-4"></i>
                </div>
                <input type="text" name="no_pinjaman_display" value="Auto" disabled
                    class="w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 text-xs font-bold text-slate-500 cursor-not-allowed">
            </div>
        </div>

        <div class="space-y-1">
            <label for="tanggal_piutang" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                Tanggal Pengajuan <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                </div>
                <input type="text" id="tanggal_piutang" name="tanggal" value="{{ date('Y-m-d') }}" readonly placeholder="Pilih Tanggal"
                    class="flatpickr-date w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-semibold text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
            </div>
            <p id="error-tanggal-piutang" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 items-center gap-1">
                <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
                <span>Tanggal pengajuan wajib diisi</span>
            </p>
        </div>
    </div>

    <!-- Seksi Pilih Karyawan -->
    <div class="space-y-1">
        <label for="nik_piutang" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
            Karyawan <span class="text-rose-500">*</span>
        </label>
        <div class="flex items-center gap-2">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="user" class="w-4 h-4"></i>
                </div>
                <input type="text" name="nik" id="nik_piutang" readonly placeholder="Klik cari untuk memilih karyawan..."
                    class="w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/60 text-xs font-bold text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] cursor-pointer">
            </div>
            <button type="button" id="nik_search_piutang"
                class="h-[36px] px-3.5 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold transition flex items-center gap-1.5 shadow-xs cursor-pointer shrink-0">
                <i data-lucide="search" class="w-3.5 h-3.5"></i>
                <span>Cari Karyawan</span>
            </button>
        </div>
        <p id="error-nik-piutang" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 items-center gap-1">
            <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
            <span>Silakan pilih karyawan terlebih dahulu</span>
        </p>
    </div>

    <!-- Ringkasan Profil Karyawan -->
    <div class="p-3 sm:p-3.5 rounded-xl border border-slate-200/80 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/40">
        <div class="flex items-center gap-1.5 pb-2 mb-2.5 border-b border-slate-200/80 dark:border-slate-800">
            <i data-lucide="user-check" class="w-3.5 h-3.5 text-[#284c9a] dark:text-blue-400"></i>
            <h6 class="text-xs font-bold text-slate-800 dark:text-slate-100">Profil Karyawan</h6>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-1.5 text-xs">
            <div class="flex justify-between items-center">
                <span class="text-slate-500 dark:text-slate-400">Nama Karyawan</span>
                <span id="nama_karyawan_pk" class="font-bold text-slate-800 dark:text-slate-100 text-right">-</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-slate-500 dark:text-slate-400">Masa Kerja</span>
                <span id="masa_kerja_pk" class="font-semibold text-slate-700 dark:text-slate-300 text-right">-</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-slate-500 dark:text-slate-400">Departemen</span>
                <span id="nama_dept_pk" class="font-semibold text-slate-700 dark:text-slate-300 text-right">-</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-slate-500 dark:text-slate-400">Status Karyawan</span>
                <span id="status_karyawan_pk" class="font-semibold text-slate-700 dark:text-slate-300 text-right">-</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-slate-500 dark:text-slate-400">Jabatan</span>
                <span id="nama_jabatan_pk" class="font-semibold text-slate-700 dark:text-slate-300 text-right">-</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-slate-500 dark:text-slate-400">Akhir Kontrak</span>
                <span id="akhir_kontrak_pk" class="font-semibold text-slate-700 dark:text-slate-300 text-right">-</span>
            </div>
            <div class="flex justify-between items-center sm:col-span-2 pt-1 border-t border-slate-200/60 dark:border-slate-800">
                <span class="text-slate-500 dark:text-slate-400">Kantor Cabang</span>
                <span id="nama_cabang_pk" class="font-semibold text-slate-700 dark:text-slate-300 text-right">-</span>
            </div>
        </div>
    </div>

    <!-- Jumlah Piutang -->
    <div class="space-y-1">
        <label for="jumlah_piutang" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
            Jumlah Piutang <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="dollar-sign" class="w-4 h-4"></i>
            </div>
            <input type="text" id="jumlah_piutang" name="jumlah" placeholder="0"
                class="money w-full h-[36px] pl-9 pr-3 text-right rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-bold text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
        </div>
        <p id="error-jumlah-piutang" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 items-center gap-1">
            <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
            <span>Jumlah piutang wajib diisi lebih dari 0</span>
        </p>
    </div>

    @can('piutangkaryawan.checkstatus')
        <label for="status_keuangan_pk" class="flex items-center gap-2 p-2.5 rounded-lg border border-slate-200/80 dark:border-slate-800 bg-slate-50/60 dark:bg-slate-800/40 cursor-pointer">
            <input type="checkbox" name="status" value="1" id="status_keuangan_pk"
                class="w-4 h-4 rounded border-slate-300 text-[#284c9a] focus:ring-[#284c9a]">
            <span class="text-xs font-semibold text-slate-700 dark:text-slate-200">Hanya Bisa Dilihat Keuangan?</span>
        </label>
    @endcan

    <!-- Tombol Aksi Kanan (Right-Aligned) -->
    <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100 dark:border-slate-800">
        <button type="button" id="btnCancelCreatePiutang"
            class="h-[36px] px-4 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 text-xs font-bold transition cursor-pointer">
            Batal
        </button>
        <button type="submit" id="btnSimpanPiutang"
            class="h-[36px] px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold transition flex items-center gap-1.5 shadow-xs cursor-pointer">
            <i data-lucide="send" class="w-3.5 h-3.5"></i>
            <span>Simpan Piutang</span>
        </button>
    </div>
</form>

<script>
    $(function() {
        if (typeof lucide !== 'undefined') lucide.createIcons();

        const $form = $("#formPiutangKaryawan");
        const hariini = "{{ date('Y-m-d') }}";
        const kategori = "{{ $kategori }}";
        const endPeriode = "{{ $end_periode ?? '' }}";

        if (kategori === 'EK' || !endPeriode) {
            $form.find(".flatpickr-date").flatpickr({
                dateFormat: "Y-m-d",
                allowInput: false
            });
        } else {
            $form.find(".flatpickr-date").flatpickr({
                dateFormat: "Y-m-d",
                allowInput: false,
                enable: [{
                    from: hariini,
                    to: endPeriode
                }]
            });
        }

        $form.find(".money").maskMoney({
            thousands: '.',
            decimal: ',',
            precision: 0
        });

        function calculateWorkDuration(startDate, endDate) {
            let diff = endDate - startDate;
            const oneDay = 1000 * 60 * 60 * 24;
            let days = Math.floor(diff / oneDay);
            let years = Math.floor(days / 365);
            days -= years * 365;
            let months = Math.floor(days / 30);
            return { years, months, days };
        }

        function getKaryawan(nik) {
            $.ajax({
                url: `/karyawan/${nik}/getkaryawan`,
                type: "GET",
                cache: false,
                success: function(response) {
                    const data = response.data;
                    $form.find("#nik_piutang").val(data.nik).removeClass('border-rose-500 ring-1 ring-rose-500');
                    $form.find("#error-nik-piutang").addClass('hidden').removeClass('flex');
                    $form.find("#nama_karyawan_pk").text(data.nama_karyawan);
                    $form.find("#nama_dept_pk").text(data.nama_dept);
                    $form.find("#nama_jabatan_pk").text(data.nama_jabatan);
                    $form.find("#nama_cabang_pk").text(data.nama_cabang);

                    const startDate = new Date(data.tanggal_masuk);
                    const endDate = new Date();
                    const masaKerja = calculateWorkDuration(startDate, endDate);

                    $form.find("#masa_kerja_pk").text(`${masaKerja.years} Tahun, ${masaKerja.months} Bulan`);
                    $form.find("#status_karyawan_pk").text(data.statuskaryawan);

                    if (data.status_karyawan === 'T') {
                        $form.find('#akhir_kontrak_pk').html('<span class="text-xs font-bold text-emerald-600">Tetap (Permanen)</span>');
                    } else {
                        $form.find("#akhir_kontrak_pk").text(data.akhir_kontrak || '-');
                    }

                    if (typeof modalKaryawanPiutangHelper !== 'undefined') {
                        modalKaryawanPiutangHelper.close();
                    }
                }
            });
        }

        // Listener pilih karyawan dari DataTable
        $(document).off('click.pkSelect', '#tabelKaryawanPiutang tbody .pilihkaryawan').on('click.pkSelect', '#tabelKaryawanPiutang tbody .pilihkaryawan', function(e) {
            e.preventDefault();
            const nik = $(this).attr('nik') || $(this).data('nik');
            getKaryawan(nik);
        });

        // Live cleanup
        $form.find("#tanggal_piutang").on('input change', function() {
            $(this).removeClass('border-rose-500 ring-1 ring-rose-500');
            $form.find("#error-tanggal-piutang").addClass('hidden').removeClass('flex');
        });
        $form.find("#jumlah_piutang").on('input change keyup', function() {
            $(this).removeClass('border-rose-500 ring-1 ring-rose-500');
            $form.find("#error-jumlah-piutang").addClass('hidden').removeClass('flex');
        });

        $form.find("#btnCancelCreatePiutang").on('click', function() {
            if (typeof modalPiutangHelper !== 'undefined') {
                modalPiutangHelper.close();
            }
        });

        // Submit handler with inline validation
        $form.on('submit', function(e) {
            e.preventDefault();
            let hasError = false;
            let $firstError = null;

            const tanggal = $form.find("#tanggal_piutang").val().trim();
            if (!tanggal) {
                $form.find("#error-tanggal-piutang").removeClass('hidden').addClass('flex');
                $form.find("#tanggal_piutang").addClass('border-rose-500 ring-1 ring-rose-500');
                hasError = true;
                if (!$firstError) $firstError = $form.find("#tanggal_piutang");
            }

            const nik = $form.find("#nik_piutang").val().trim();
            if (!nik) {
                $form.find("#error-nik-piutang").removeClass('hidden').addClass('flex');
                $form.find("#nik_piutang").addClass('border-rose-500 ring-1 ring-rose-500');
                hasError = true;
                if (!$firstError) $firstError = $form.find("#nik_piutang");
            }

            const jmlStr = $form.find("#jumlah_piutang").val();
            const jml = jmlStr ? parseInt(jmlStr.replace(/\./g, '')) || 0 : 0;
            if (jml <= 0) {
                $form.find("#error-jumlah-piutang").removeClass('hidden').addClass('flex');
                $form.find("#jumlah_piutang").addClass('border-rose-500 ring-1 ring-rose-500');
                hasError = true;
                if (!$firstError) $firstError = $form.find("#jumlah_piutang");
            }

            if (hasError) {
                if ($firstError) $firstError.focus();
                return false;
            }

            const $btn = $form.find("#btnSimpanPiutang");
            $btn.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            $btn.html(`
                <svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Menyimpan...</span>
            `);

            this.submit();
        });
    });
</script>
