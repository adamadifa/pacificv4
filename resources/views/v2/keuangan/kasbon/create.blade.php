<form action="{{ route('kasbon.store') }}" id="formKasbon" method="POST" class="space-y-3.5" autocomplete="off">
    @csrf

    <!-- Baris Header Form (No Kasbon & Tanggal Pengajuan) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
        <div class="space-y-1">
            <label class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                No. Kasbon
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="barcode" class="w-4 h-4"></i>
                </div>
                <input type="text" name="no_kasbon" value="Auto" disabled
                    class="w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 text-xs font-bold text-slate-500 cursor-not-allowed">
            </div>
        </div>

        <div class="space-y-1">
            <label for="tanggal_kasbon" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                Tanggal Pengajuan <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                </div>
                <input type="text" id="tanggal_kasbon" name="tanggal" value="{{ date('Y-m-d') }}" readonly placeholder="Pilih Tanggal"
                    class="flatpickr-date w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-semibold text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
            </div>
            <p id="error-tanggal-kasbon" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 items-center gap-1">
                <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
                <span>Tanggal pengajuan wajib diisi</span>
            </p>
        </div>
    </div>

    <!-- Seksi Pilih Karyawan -->
    <div class="space-y-1">
        <label for="nik_kasbon" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
            Karyawan <span class="text-rose-500">*</span>
        </label>
        <div class="flex items-center gap-2">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="user" class="w-4 h-4"></i>
                </div>
                <input type="text" name="nik" id="nik_kasbon" readonly placeholder="Klik cari untuk memilih karyawan..."
                    class="w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/60 text-xs font-bold text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] cursor-pointer">
            </div>
            <button type="button" id="nik_search_kasbon"
                class="h-[36px] px-3.5 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold transition flex items-center gap-1.5 shadow-xs cursor-pointer shrink-0">
                <i data-lucide="search" class="w-3.5 h-3.5"></i>
                <span>Cari Karyawan</span>
            </button>
        </div>
        <p id="error-nik-kasbon" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 items-center gap-1">
            <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
            <span>Silakan pilih karyawan terlebih dahulu</span>
        </p>
    </div>

    <!-- Ringkasan Profil Karyawan -->
    <div class="p-3 sm:p-3.5 rounded-xl border border-slate-200/80 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/40">
        <div class="flex items-center gap-1.5 pb-2 mb-2.5 border-b border-slate-200/80 dark:border-slate-800">
            <i data-lucide="user-check" class="w-3.5 h-3.5 text-[#284c9a] dark:text-blue-400"></i>
            <h6 class="text-xs font-bold text-slate-800 dark:text-slate-100">Profil & Batas Kasbon</h6>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-1.5 text-xs">
            <div class="flex justify-between items-center">
                <span class="text-slate-500 dark:text-slate-400">Nama Karyawan</span>
                <span id="nama_karyawan_kb" class="font-bold text-slate-800 dark:text-slate-100 text-right">-</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-slate-500 dark:text-slate-400">Masa Kerja</span>
                <span id="masa_kerja_kb" class="font-semibold text-slate-700 dark:text-slate-300 text-right">-</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-slate-500 dark:text-slate-400">Departemen</span>
                <span id="nama_dept_kb" class="font-semibold text-slate-700 dark:text-slate-300 text-right">-</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-slate-500 dark:text-slate-400">Status Karyawan</span>
                <span id="status_karyawan_kb" class="font-semibold text-slate-700 dark:text-slate-300 text-right">-</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-slate-500 dark:text-slate-400">Jabatan</span>
                <span id="nama_jabatan_kb" class="font-semibold text-slate-700 dark:text-slate-300 text-right">-</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-slate-500 dark:text-slate-400">Akhir Kontrak</span>
                <span id="akhir_kontrak_kb" class="font-semibold text-slate-700 dark:text-slate-300 text-right">-</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-slate-500 dark:text-slate-400">Kantor Cabang</span>
                <span id="nama_cabang_kb" class="font-semibold text-slate-700 dark:text-slate-300 text-right">-</span>
            </div>
            <div class="flex justify-between items-center pt-1 border-t border-slate-200/60 dark:border-slate-800">
                <span class="font-bold text-[#284c9a] dark:text-blue-400">Maksimal Kasbon</span>
                <span id="max_kasbon_kb" class="font-mono-num font-black text-sm text-[#284c9a] dark:text-blue-400 text-right">-</span>
            </div>
        </div>
    </div>

    <!-- Dynamic Container Load Kasbon -->
    <div id="loadkasbon" class="pt-1"></div>

</form>

<script>
    $(function() {
        if (typeof lucide !== 'undefined') lucide.createIcons();

        const $form = $("#formKasbon");

        $form.find(".flatpickr-date").flatpickr({
            dateFormat: "Y-m-d",
            allowInput: false
        });

        let max_kasbon = 0;

        function getMulaicicilan() {
            var tanggal_pinjaman = $form.find("#tanggal_kasbon").val();
            if (!tanggal_pinjaman) return;

            var parts = tanggal_pinjaman.split("-");
            var tgl = parseInt(parts[2]);
            var bulan = parseInt(parts[1]);
            var tahun = parseInt(parts[0]);

            if (tgl === 19 || tgl === 20) {
                Swal.fire({
                    title: "Tanggal Tidak Valid",
                    text: "Tidak dapat melakukan pengajuan kasbon pada tanggal 19 dan 20!",
                    icon: "warning",
                    confirmButtonColor: '#284c9a'
                }).then(() => {
                    $form.find("#mulai_cicilan").val("");
                    $form.find("#tanggal_kasbon").val("");
                });
                return;
            }

            var nextbulan, nexttahun;
            if (tgl <= 18 && bulan <= 10) {
                nextbulan = bulan + 1;
                nexttahun = tahun;
            } else if (tgl <= 18 && bulan === 12) {
                nextbulan = 1;
                nexttahun = tahun + 1;
            } else if (tgl >= 21 && bulan <= 10) {
                nextbulan = bulan + 2;
                nexttahun = tahun;
            } else if (tgl <= 18 && bulan <= 11) {
                nextbulan = bulan + 1;
                nexttahun = tahun;
            } else if (tgl >= 21 && bulan === 11) {
                nextbulan = 1;
                nexttahun = tahun + 1;
            } else if (tgl >= 21 && bulan === 12) {
                nextbulan = 2;
                nexttahun = tahun + 1;
            } else {
                nextbulan = bulan + 1;
                nexttahun = tahun;
            }

            if (nextbulan <= 9) nextbulan = "0" + nextbulan;
            $form.find("#mulai_cicilan").val(nexttahun + "-" + nextbulan + "-01");
        }

        $form.find("#tanggal_kasbon").on('change', getMulaicicilan);

        function convertToRupiah(number) {
            if (!number) return '0';
            return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        function calculateMonthDifference(startDate, endDate) {
            if (!(startDate instanceof Date) || !(endDate instanceof Date)) return 0;
            const diffYear = endDate.getFullYear() - startDate.getFullYear();
            const diffMonth = endDate.getMonth() - startDate.getMonth();
            return diffYear * 12 + diffMonth;
        }

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
                    $form.find("#nik_kasbon").val(data.nik);
                    $form.find("#nama_karyawan_kb").text(data.nama_karyawan);
                    $form.find("#nama_dept_kb").text(data.nama_dept);
                    $form.find("#nama_jabatan_kb").text(data.nama_jabatan);
                    $form.find("#nama_cabang_kb").text(data.nama_cabang);

                    const startDate = new Date(data.tanggal_masuk);
                    const endDate = new Date();
                    const masaKerja = calculateWorkDuration(startDate, endDate);
                    const jumlahBulankerja = calculateMonthDifference(startDate, endDate);

                    $form.find("#masa_kerja_kb").text(`${masaKerja.years} Tahun, ${masaKerja.months} Bulan`);
                    $form.find("#status_karyawan_kb").text(data.statuskaryawan);
                    $form.find("#akhir_kontrak_kb").text(data.status_karyawan === 'T' ? 'Tetap' : (data.akhir_kontrak || '-'));

                    // Hitung batas maksimal kasbon
                    if (jumlahBulankerja < 9) {
                        max_kasbon = 200000;
                    } else if (jumlahBulankerja <= 15) {
                        max_kasbon = 400000;
                    } else {
                        max_kasbon = 600000;
                    }

                    if (data.kasbon_max !== 0 && typeof data.kasbon_max !== 'undefined') {
                        if (data.kasbon_max < max_kasbon) {
                            max_kasbon = data.kasbon_max;
                        }
                    }

                    $form.find("#max_kasbon_kb").text('Rp ' + convertToRupiah(max_kasbon));

                    // Cek jika masih ada pinjaman kasbon belum lunas
                    if (data.cekkasbon > 0) {
                        $form.find("#loadkasbon").html(`
                            <div class="p-3.5 rounded-xl border border-rose-200 dark:border-rose-900/60 bg-rose-50/80 dark:bg-rose-950/30 text-rose-700 dark:text-rose-300 flex items-center gap-2.5 text-xs font-semibold">
                                <i data-lucide="alert-octagon" class="w-5 h-5 shrink-0 text-rose-500"></i>
                                <span>Tidak dapat mengajukan kasbon karena karyawan masih memiliki ajuan kasbon yang belum lunas.</span>
                            </div>
                        `);
                    } else {
                        $form.find("#loadkasbon").html(`
                            <div class="p-3.5 sm:p-4 rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 space-y-3">
                                <div class="flex items-center gap-1.5 pb-2 border-b border-slate-100 dark:border-slate-800">
                                    <i data-lucide="badge-dollar-sign" class="w-4 h-4 text-[#284c9a] dark:text-blue-400"></i>
                                    <h6 class="text-xs font-bold text-slate-800 dark:text-slate-100">Form Pengajuan Kasbon</h6>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                                    <!-- Jumlah Kasbon -->
                                    <div class="space-y-1">
                                        <label for="jumlah_kasbon" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                                            Jumlah Kasbon (Maks: Rp ${convertToRupiah(max_kasbon)}) <span class="text-rose-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                                <i data-lucide="dollar-sign" class="w-4 h-4"></i>
                                            </div>
                                            <input type="text" id="jumlah_kasbon" name="jumlah" placeholder="0"
                                                class="money w-full h-[36px] pl-9 pr-3 text-right rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-bold text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
                                        </div>
                                        <p id="error-jumlah-kasbon" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 items-center gap-1">
                                            <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
                                            <span>Jumlah kasbon melebihi batas maksimal</span>
                                        </p>
                                    </div>

                                    <!-- Mulai Cicilan (Jatuh Tempo) -->
                                    <div class="space-y-1">
                                        <label for="mulai_cicilan" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                                            Mulai Cicilan / Jatuh Tempo
                                        </label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                                <i data-lucide="calendar-clock" class="w-4 h-4"></i>
                                            </div>
                                            <input type="text" id="mulai_cicilan" name="mulai_cicilan" readonly placeholder="Tanggal jatuh tempo"
                                                class="w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/60 text-xs font-bold text-slate-800 dark:text-slate-100 font-mono-num cursor-not-allowed">
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Buttons Right-Aligned -->
                                <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100 dark:border-slate-800">
                                    <button type="button" id="btnCancelKasbon"
                                        class="h-[36px] px-4 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 text-xs font-bold transition cursor-pointer">
                                        Batal
                                    </button>
                                    <button type="submit" id="btnSimpanKasbon"
                                        class="h-[36px] px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold transition flex items-center gap-1.5 shadow-xs cursor-pointer">
                                        <i data-lucide="send" class="w-3.5 h-3.5"></i>
                                        <span>Simpan Ajuan Kasbon</span>
                                    </button>
                                </div>
                            </div>
                        `);

                        $form.find(".money").maskMoney({
                            thousands: '.',
                            decimal: ',',
                            precision: 0
                        });
                        getMulaicicilan();
                    }

                    if (typeof lucide !== 'undefined') lucide.createIcons();
                }
            });
        }

        // Listener klik pilihan karyawan dari DataTable
        $(document).on('click', '.pilihkaryawan', function(e) {
            e.preventDefault();
            const nik = $(this).attr('nik') || $(this).data('nik');
            getKaryawan(nik);
            if (typeof modalKaryawanKasbonHelper !== 'undefined') {
                modalKaryawanKasbonHelper.close();
            }
        });

        $(document).on('keyup keydown change', '#jumlah_kasbon', function() {
            let jml = $(this).val();
            let val = jml ? parseInt(jml.replace(/\./g, '')) || 0 : 0;

            if (val > max_kasbon && max_kasbon > 0) {
                $form.find("#error-jumlah-kasbon").removeClass('hidden').addClass('flex').find('span').text(`Maksimal kasbon adalah Rp ${convertToRupiah(max_kasbon)}`);
                $(this).addClass('border-rose-500 ring-1 ring-rose-500');
            } else {
                $form.find("#error-jumlah-kasbon").addClass('hidden').removeClass('flex');
                $(this).removeClass('border-rose-500 ring-1 ring-rose-500');
            }
        });

        $(document).on('click', '#btnCancelKasbon', function() {
            if (typeof modalKasbonHelper !== 'undefined') {
                modalKasbonHelper.close();
            }
        });

        // Submit Handler with Strict Inline Validation
        $form.on('submit', function(e) {
            e.preventDefault();
            let hasError = false;
            let $firstError = null;

            const tanggal = $form.find("#tanggal_kasbon").val().trim();
            if (!tanggal) {
                $form.find("#error-tanggal-kasbon").removeClass('hidden').addClass('flex');
                $form.find("#tanggal_kasbon").addClass('border-rose-500 ring-1 ring-rose-500');
                hasError = true;
                if (!$firstError) $firstError = $form.find("#tanggal_kasbon");
            }

            const nik = $form.find("#nik_kasbon").val().trim();
            if (!nik) {
                $form.find("#error-nik-kasbon").removeClass('hidden').addClass('flex');
                $form.find("#nik_kasbon").addClass('border-rose-500 ring-1 ring-rose-500');
                hasError = true;
                if (!$firstError) $firstError = $form.find("#nik_kasbon");
            }

            const $jmlKasbon = $form.find("#jumlah_kasbon");
            const jml = $jmlKasbon.val() ? parseInt($jmlKasbon.val().replace(/\./g, '')) || 0 : 0;
            if (jml <= 0 || (max_kasbon > 0 && jml > max_kasbon)) {
                $form.find("#error-jumlah-kasbon").removeClass('hidden').addClass('flex');
                $jmlKasbon.addClass('border-rose-500 ring-1 ring-rose-500');
                hasError = true;
                if (!$firstError) $firstError = $jmlKasbon;
            }

            if (hasError) {
                if ($firstError) $firstError.focus();
                return false;
            }

            const $btn = $form.find("#btnSimpanKasbon");
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
