<form action="{{ route('pjp.store') }}" id="formPJP" method="POST" class="space-y-3.5" autocomplete="off">
    @csrf

    <!-- Baris Header Form (No PJP & Tanggal Pengajuan) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
        <div class="space-y-1">
            <label class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                No. PJP
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="barcode" class="w-4 h-4"></i>
                </div>
                <input type="text" name="no_pjp" value="Auto" disabled
                    class="w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 text-xs font-bold text-slate-500 cursor-not-allowed">
            </div>
        </div>

        <div class="space-y-1">
            <label for="tanggal_pjp" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                Tanggal Pengajuan <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                </div>
                <input type="text" id="tanggal_pjp" name="tanggal" value="{{ date('Y-m-d') }}" readonly placeholder="Pilih Tanggal"
                    class="flatpickr-date w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-semibold text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
            </div>
            <p id="error-tanggal-pjp" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 items-center gap-1">
                <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
                <span>Tanggal pengajuan wajib diisi</span>
            </p>
        </div>
    </div>

    <!-- Seksi Pilih Karyawan -->
    <div class="space-y-1">
        <label for="nik_pjp" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
            Karyawan <span class="text-rose-500">*</span>
        </label>
        <div class="flex items-center gap-2">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="user" class="w-4 h-4"></i>
                </div>
                <input type="text" name="nik" id="nik_pjp" readonly placeholder="Klik cari untuk memilih karyawan..."
                    class="w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/60 text-xs font-bold text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] cursor-pointer">
            </div>
            <button type="button" id="nik_search_pjp"
                class="h-[36px] px-3.5 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold transition flex items-center gap-1.5 shadow-xs cursor-pointer shrink-0">
                <i data-lucide="search" class="w-3.5 h-3.5"></i>
                <span>Cari Karyawan</span>
            </button>
        </div>
        <p id="error-nik-pjp" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 items-center gap-1">
            <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
            <span>Silakan pilih karyawan terlebih dahulu</span>
        </p>
    </div>

    <!-- Ringkasan Profil Karyawan & Kapasitas Pinjaman -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-3 pt-1">
        <!-- Identitas Karyawan -->
        <div class="p-3 sm:p-3.5 rounded-xl border border-slate-200/80 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/40">
            <div class="flex items-center gap-1.5 pb-2 mb-2.5 border-b border-slate-200/80 dark:border-slate-800">
                <i data-lucide="user-check" class="w-3.5 h-3.5 text-[#284c9a] dark:text-blue-400"></i>
                <h6 class="text-xs font-bold text-slate-800 dark:text-slate-100">Profil Karyawan</h6>
            </div>
            <dl class="space-y-1.5 text-xs">
                <div class="flex justify-between items-center">
                    <dt class="text-slate-500 dark:text-slate-400">Nama Karyawan</dt>
                    <dd id="nama_karyawan" class="font-bold text-slate-800 dark:text-slate-100 text-right">-</dd>
                </div>
                <div class="flex justify-between items-center">
                    <dt class="text-slate-500 dark:text-slate-400">Departemen</dt>
                    <dd id="nama_dept" class="font-semibold text-slate-700 dark:text-slate-300 text-right">-</dd>
                </div>
                <div class="flex justify-between items-center">
                    <dt class="text-slate-500 dark:text-slate-400">Jabatan</dt>
                    <dd id="nama_jabatan" class="font-semibold text-slate-700 dark:text-slate-300 text-right">-</dd>
                </div>
                <div class="flex justify-between items-center">
                    <dt class="text-slate-500 dark:text-slate-400">Kantor Cabang</dt>
                    <dd id="nama_cabang" class="font-semibold text-slate-700 dark:text-slate-300 text-right">-</dd>
                </div>
                <div class="flex justify-between items-center">
                    <dt class="text-slate-500 dark:text-slate-400">Masa Kerja</dt>
                    <dd id="masa_kerja" class="font-semibold text-slate-700 dark:text-slate-300 text-right">-</dd>
                </div>
                <div class="flex justify-between items-center">
                    <dt class="text-slate-500 dark:text-slate-400">Status Karyawan</dt>
                    <dd id="status_karyawan" class="font-semibold text-slate-700 dark:text-slate-300 text-right">-</dd>
                </div>
                <div class="flex justify-between items-center">
                    <dt class="text-slate-500 dark:text-slate-400">Akhir Kontrak</dt>
                    <dd id="akhir_kontrak" class="font-semibold text-slate-700 dark:text-slate-300 text-right">-</dd>
                </div>
            </dl>
        </div>

        <!-- Kapasitas Finansial & Plafon -->
        <div class="p-3 sm:p-3.5 rounded-xl border border-slate-200/80 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/40">
            <div class="flex items-center gap-1.5 pb-2 mb-2.5 border-b border-slate-200/80 dark:border-slate-800">
                <i data-lucide="calculator" class="w-3.5 h-3.5 text-[#284c9a] dark:text-blue-400"></i>
                <h6 class="text-xs font-bold text-slate-800 dark:text-slate-100">Kapasitas & Plafon Pinjaman</h6>
            </div>
            <dl class="space-y-1.5 text-xs">
                <div class="flex justify-between items-center">
                    <dt class="text-slate-500 dark:text-slate-400">Gapok + Tunjangan</dt>
                    <dd id="gapok_tunjangan" class="font-mono-num font-semibold text-slate-800 dark:text-slate-200 text-right">-</dd>
                </div>
                <div class="flex justify-between items-center">
                    <dt class="text-slate-500 dark:text-slate-400">Tenor Maksimal</dt>
                    <dd id="tenor_max" class="font-mono-num font-semibold text-slate-800 dark:text-slate-200 text-right">-</dd>
                </div>
                <div class="flex justify-between items-center">
                    <dt class="text-slate-500 dark:text-slate-400">Angsuran Maksimal</dt>
                    <dd id="angsuran_max" class="font-mono-num font-semibold text-slate-800 dark:text-slate-200 text-right">-</dd>
                </div>
                <div class="flex justify-between items-center">
                    <dt class="text-slate-500 dark:text-slate-400">Plafon Perhitungan</dt>
                    <dd id="plafon" class="font-mono-num font-semibold text-slate-800 dark:text-slate-200 text-right">-</dd>
                </div>
                <div class="flex justify-between items-center">
                    <dt class="text-slate-500 dark:text-slate-400">JMK</dt>
                    <dd id="jmk" class="font-mono-num font-semibold text-slate-800 dark:text-slate-200 text-right">-</dd>
                </div>
                <div class="flex justify-between items-center">
                    <dt class="text-slate-500 dark:text-slate-400">JMK Sudah Dibayar</dt>
                    <dd id="jmk_dibayar" class="font-mono-num font-semibold text-slate-800 dark:text-slate-200 text-right">-</dd>
                </div>
                <div class="flex justify-between items-center pt-1 border-t border-slate-200/60 dark:border-slate-800">
                    <dt class="font-bold text-[#284c9a] dark:text-blue-400">Plafon Maksimal</dt>
                    <dd id="plafon_max" class="font-mono-num font-black text-sm text-[#284c9a] dark:text-blue-400 text-right">-</dd>
                </div>
            </dl>
        </div>
    </div>

    <!-- Dynamic Container Load PJP -->
    <div id="loadpjp" class="pt-1"></div>

</form>

<script>
    $(function() {
        if (typeof lucide !== 'undefined') lucide.createIcons();

        const $form = $("#formPJP");
        const hariini = "{{ date('Y-m-d') }}";

        $form.find(".flatpickr-date").flatpickr({
            dateFormat: "Y-m-d",
            allowInput: false
        });

        let max_pinjaman = 0;
        let max_cicilan = 0;
        let max_angsuran = 0;

        function getMulaicicilan() {
            var tanggal_pinjaman = $form.find("#tanggal_pjp").val();
            if (!tanggal_pinjaman) return;

            var parts = tanggal_pinjaman.split("-");
            var tgl = parseInt(parts[2]);
            var bulan = parseInt(parts[1]);
            var tahun = parseInt(parts[0]);

            if (tgl === 19 || tgl === 20) {
                Swal.fire({
                    title: "Tanggal Tidak Valid",
                    text: "Tidak dapat melakukan pengajuan pinjaman pada tanggal 19 dan 20!",
                    icon: "warning",
                    confirmButtonColor: '#284c9a'
                }).then(() => {
                    $form.find("#mulai_cicilan").val("");
                    $form.find("#tanggal_pjp").val("");
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

        $form.find("#tanggal_pjp").on('change', getMulaicicilan);

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

        function hitungJmk(masa_kerja) {
            if (masa_kerja >= 24) return 10;
            if (masa_kerja >= 21) return 8;
            if (masa_kerja >= 18) return 7;
            if (masa_kerja >= 15) return 6;
            if (masa_kerja >= 12) return 5;
            if (masa_kerja >= 9) return 4;
            if (masa_kerja >= 6) return 3;
            if (masa_kerja >= 3) return 2;
            return 1;
        }

        function getKaryawan(nik) {
            $.ajax({
                url: `/karyawan/${nik}/getkaryawan`,
                type: "GET",
                cache: false,
                success: function(response) {
                    const data = response.data;
                    $form.find("#nik_pjp").val(data.nik);
                    $form.find("#nama_karyawan").text(data.nama_karyawan);
                    $form.find("#nama_dept").text(data.nama_dept);
                    $form.find("#nama_jabatan").text(data.nama_jabatan);
                    $form.find("#nama_cabang").text(data.nama_cabang);

                    const startDate = new Date(data.tanggal_masuk);
                    const endDate = new Date();
                    const jumlahBulankerja = calculateMonthDifference(startDate, endDate);
                    const masaKerja = calculateWorkDuration(startDate, endDate);

                    $form.find("#masa_kerja").text(`${masaKerja.years} Tahun, ${masaKerja.months} Bulan`);
                    $form.find("#status_karyawan").text(data.statuskaryawan);
                    $form.find("#gapok_tunjangan").text(convertToRupiah(data.gapok_tunjangan));

                    let tenor_max = 0;
                    if (data.status_karyawan === 'T') {
                        tenor_max = 20;
                        $form.find('#akhir_kontrak').html('<span class="text-xs font-bold text-emerald-600">Tetap (Permanen)</span>');
                    } else {
                        tenor_max = calculateMonthDifference(new Date(), new Date(data.akhir_kontrak));
                        tenor_max = tenor_max > 0 ? tenor_max : 0;
                        $form.find("#akhir_kontrak").text(data.akhir_kontrak || '-');
                    }
                    max_cicilan = tenor_max;
                    $form.find("#tenor_max").text(tenor_max + ' Bulan');

                    let angsuran_max = Math.round(40 / 100 * data.gapok_tunjangan);
                    $form.find("#angsuran_max").text(convertToRupiah(angsuran_max));
                    max_angsuran = angsuran_max;

                    let plafon = angsuran_max * tenor_max;
                    $form.find("#plafon").text(convertToRupiah(plafon));
                    $form.find("#jmk_dibayar").text(convertToRupiah(data.total_jmk_dibayar));

                    let jmlkali_jmk = hitungJmk(masaKerja.years);
                    let masa_kerja_bulan = (parseInt(masaKerja.years) * 12) + parseInt(masaKerja.months);
                    let total_upah = 0;
                    let persentase_jmk = 0;
                    if (parseInt(masa_kerja_bulan) <= 23) {
                        total_upah = data.gaji_pokok;
                        persentase_jmk = 25;
                    } else if (parseInt(masa_kerja_bulan) <= 28) {
                        total_upah = data.gaji_pokok;
                        persentase_jmk = 50;
                    } else {
                        total_upah = data.gapok_tunjangan;
                        persentase_jmk = 100;
                    }

                    let total_jmk = (persentase_jmk / 100) * parseInt(total_upah) * parseInt(jmlkali_jmk);
                    $form.find("#jmk").text(convertToRupiah(total_jmk));

                    let sisa_jmk = total_jmk - data.total_jmk_dibayar;
                    let plafon_max = plafon < sisa_jmk ? plafon : sisa_jmk;
                    $form.find("#plafon_max").text(convertToRupiah(plafon_max));
                    max_pinjaman = plafon_max;

                    const sp_pusat = ['SP2', 'SP3'];
                    const sp_cabang = ['SP1', 'SP2', 'SP3'];
                    const minimal_bayar = 75 / 100 * data.total_pinjaman;
                    const persentase_bayar = data.total_pinjaman > 0 ? Math.round(data.total_pembayaran / data.total_pinjaman * 100) : 100;

                    if (data.status_karyawan === 'O') {
                        $form.find("#loadpjp").html(`
                            <div class="p-3.5 rounded-xl border border-rose-200 dark:border-rose-900/60 bg-rose-50/80 dark:bg-rose-950/30 text-rose-700 dark:text-rose-300 flex items-center gap-2.5 text-xs font-semibold">
                                <i data-lucide="shield-alert" class="w-5 h-5 shrink-0 text-rose-500"></i>
                                <span>Tidak dapat mengajukan PJP karena status karyawan adalah Outsourcing.</span>
                            </div>
                        `);
                    } else if (jumlahBulankerja < 15) {
                        $form.find("#loadpjp").html(`
                            <div class="p-3.5 rounded-xl border border-rose-200 dark:border-rose-900/60 bg-rose-50/80 dark:bg-rose-950/30 text-rose-700 dark:text-rose-300 flex items-center gap-2.5 text-xs font-semibold">
                                <i data-lucide="clock" class="w-5 h-5 shrink-0 text-rose-500"></i>
                                <span>Masa kerja kurang dari 15 bulan (saat ini ${jumlahBulankerja} bulan). Karyawan belum memenuhi syarat masa kerja minimal.</span>
                            </div>
                        `);
                    } else if (tenor_max <= 0) {
                        $form.find("#loadpjp").html(`
                            <div class="p-3.5 rounded-xl border border-rose-200 dark:border-rose-900/60 bg-rose-50/80 dark:bg-rose-950/30 text-rose-700 dark:text-rose-300 flex items-center gap-2.5 text-xs font-semibold">
                                <i data-lucide="calendar-x" class="w-5 h-5 shrink-0 text-rose-500"></i>
                                <span>Kontrak karyawan telah habis atau kurang dari 1 bulan (${data.akhir_kontrak}). Silakan hubungi Departemen HRD.</span>
                            </div>
                        `);
                    } else if ((data.kode_cabang === 'PST' && sp_pusat.includes(data.jenis_sp)) || (data.kode_cabang !== 'PST' && sp_cabang.includes(data.jenis_sp))) {
                        $form.find("#loadpjp").html(`
                            <div class="p-3.5 rounded-xl border border-rose-200 dark:border-rose-900/60 bg-rose-50/80 dark:bg-rose-950/30 text-rose-700 dark:text-rose-300 flex items-center gap-2.5 text-xs font-semibold">
                                <i data-lucide="alert-octagon" class="w-5 h-5 shrink-0 text-rose-500"></i>
                                <span>Karyawan masih dalam masa sanksi ${data.jenis_sp} (berakhir ${data.tanggal_berakhir_sp}). Tidak dapat mengajukan pinjaman.</span>
                            </div>
                        `);
                    } else if (data.total_pembayaran < minimal_bayar) {
                        $form.find("#loadpjp").html(`
                            <div class="p-3.5 rounded-xl border border-amber-200 dark:border-amber-900/60 bg-amber-50/80 dark:bg-amber-950/30 text-amber-800 dark:text-amber-300 flex items-center gap-2.5 text-xs font-semibold">
                                <i data-lucide="alert-triangle" class="w-5 h-5 shrink-0 text-amber-500"></i>
                                <span>Karyawan masih memiliki pinjaman berjalan dan belum mencapai minimal pelunasan 75% (Baru terbayar Rp ${convertToRupiah(data.total_pembayaran)} dari Rp ${convertToRupiah(data.total_pinjaman)} / ${persentase_bayar}%).</span>
                            </div>
                        `);
                    } else {
                        $form.find("#loadpjp").html(`
                            <div class="p-3.5 sm:p-4 rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 space-y-3">
                                <div class="flex items-center gap-1.5 pb-2 border-b border-slate-100 dark:border-slate-800">
                                    <i data-lucide="badge-dollar-sign" class="w-4 h-4 text-[#284c9a] dark:text-blue-400"></i>
                                    <h6 class="text-xs font-bold text-slate-800 dark:text-slate-100">Form Pengajuan Pinjaman</h6>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                                    <!-- Jumlah Pinjaman -->
                                    <div class="space-y-1">
                                        <label for="jumlah_pinjaman" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                                            Jumlah Pinjaman (Maks: Rp ${convertToRupiah(plafon_max)}) <span class="text-rose-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                                <i data-lucide="dollar-sign" class="w-4 h-4"></i>
                                            </div>
                                            <input type="text" id="jumlah_pinjaman" name="jumlah_pinjaman" placeholder="0"
                                                class="money w-full h-[36px] pl-9 pr-3 text-right rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-bold text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
                                        </div>
                                        <p id="error-jumlah-pjp" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 items-center gap-1">
                                            <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
                                            <span>Jumlah pinjaman tidak valid</span>
                                        </p>
                                    </div>

                                    <!-- Jumlah Cicilan (Tenor) -->
                                    <div class="space-y-1">
                                        <label for="angsuran" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                                            Tenor Cicilan (Maks: ${tenor_max} Bulan) <span class="text-rose-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                                <i data-lucide="layers" class="w-4 h-4"></i>
                                            </div>
                                            <input type="number" id="angsuran" name="angsuran" min="1" max="${tenor_max}" placeholder="Jumlah bulan"
                                                class="w-full h-[36px] pl-9 pr-3 text-right rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-bold text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
                                        </div>
                                        <p id="error-angsuran-pjp" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 items-center gap-1">
                                            <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
                                            <span>Tenor cicilan tidak valid</span>
                                        </p>
                                    </div>

                                    <!-- Jumlah Angsuran / Bulan -->
                                    <div class="space-y-1">
                                        <label for="jumlah_angsuran" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                                            Estimasi Angsuran / Bulan (Maks: Rp ${convertToRupiah(angsuran_max)})
                                        </label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                                <i data-lucide="credit-card" class="w-4 h-4"></i>
                                            </div>
                                            <input type="text" id="jumlah_angsuran" name="jumlah_angsuran" readonly placeholder="0"
                                                class="w-full h-[36px] pl-9 pr-3 text-right rounded-lg border border-slate-200/80 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/60 text-xs font-bold text-emerald-600 dark:text-emerald-400 font-mono-num cursor-not-allowed">
                                        </div>
                                    </div>

                                    <!-- Mulai Cicilan -->
                                    <div class="space-y-1">
                                        <label for="mulai_cicilan" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                                            Mulai Cicilan
                                        </label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                                <i data-lucide="calendar-clock" class="w-4 h-4"></i>
                                            </div>
                                            <input type="text" id="mulai_cicilan" name="mulai_cicilan" readonly placeholder="Tanggal mulai cicilan"
                                                class="w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/60 text-xs font-bold text-slate-800 dark:text-slate-100 font-mono-num cursor-not-allowed">
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Buttons Right-Aligned -->
                                <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100 dark:border-slate-800">
                                    <button type="button" id="btnCancelPjp"
                                        class="h-[36px] px-4 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 text-xs font-bold transition cursor-pointer">
                                        Batal
                                    </button>
                                    <button type="submit" id="btnSimpanPjp"
                                        class="h-[36px] px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold transition flex items-center gap-1.5 shadow-xs cursor-pointer">
                                        <i data-lucide="send" class="w-3.5 h-3.5"></i>
                                        <span>Simpan Ajuan PJP</span>
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
            if (typeof modalKaryawanPjpHelper !== 'undefined') {
                modalKaryawanPjpHelper.close();
            }
        });

        function calculateAngsuranperbulan() {
            let jmlpinjaman = $form.find("#jumlah_pinjaman").val();
            let jumlah_pinjaman = jmlpinjaman ? parseInt(jmlpinjaman.replace(/\./g, '')) || 0 : 0;
            let angsuran = parseInt($form.find("#angsuran").val()) || 0;
            let angsuranperbulan = (angsuran > 0 && jumlah_pinjaman > 0) ? Math.round(jumlah_pinjaman / angsuran) : 0;
            $form.find("#jumlah_angsuran").val(convertToRupiah(angsuranperbulan));
        }

        $(document).on('keyup keydown change', '#jumlah_pinjaman', function() {
            let jml = $(this).val();
            let val = jml ? parseInt(jml.replace(/\./g, '')) || 0 : 0;
            calculateAngsuranperbulan();

            if (val > max_pinjaman && max_pinjaman > 0) {
                $form.find("#error-jumlah-pjp").removeClass('hidden').addClass('flex').find('span').text(`Maksimal pinjaman adalah Rp ${convertToRupiah(max_pinjaman)}`);
                $(this).addClass('border-rose-500 ring-1 ring-rose-500');
            } else {
                $form.find("#error-jumlah-pjp").addClass('hidden').removeClass('flex');
                $(this).removeClass('border-rose-500 ring-1 ring-rose-500');
            }
        });

        $(document).on('keyup keydown change', '#angsuran', function() {
            let val = parseInt($(this).val()) || 0;
            calculateAngsuranperbulan();

            if (val > max_cicilan && max_cicilan > 0) {
                $form.find("#error-angsuran-pjp").removeClass('hidden').addClass('flex').find('span').text(`Maksimal tenor cicilan adalah ${max_cicilan} Bulan`);
                $(this).addClass('border-rose-500 ring-1 ring-rose-500');
            } else {
                $form.find("#error-angsuran-pjp").addClass('hidden').removeClass('flex');
                $(this).removeClass('border-rose-500 ring-1 ring-rose-500');
            }
        });

        $(document).on('click', '#btnCancelPjp', function() {
            if (typeof modalPjpHelper !== 'undefined') {
                modalPjpHelper.close();
            }
        });

        // Submit Handler with Strict Inline Validation
        $form.on('submit', function(e) {
            e.preventDefault();
            let hasError = false;
            let $firstError = null;

            const tanggal = $form.find("#tanggal_pjp").val().trim();
            if (!tanggal) {
                $form.find("#error-tanggal-pjp").removeClass('hidden').addClass('flex');
                $form.find("#tanggal_pjp").addClass('border-rose-500 ring-1 ring-rose-500');
                hasError = true;
                if (!$firstError) $firstError = $form.find("#tanggal_pjp");
            }

            const nik = $form.find("#nik_pjp").val().trim();
            if (!nik) {
                $form.find("#error-nik-pjp").removeClass('hidden').addClass('flex');
                $form.find("#nik_pjp").addClass('border-rose-500 ring-1 ring-rose-500');
                hasError = true;
                if (!$firstError) $firstError = $form.find("#nik_pjp");
            }

            const $jmlPinjaman = $form.find("#jumlah_pinjaman");
            const jml = $jmlPinjaman.val() ? parseInt($jmlPinjaman.val().replace(/\./g, '')) || 0 : 0;
            if (jml <= 0 || (max_pinjaman > 0 && jml > max_pinjaman)) {
                $form.find("#error-jumlah-pjp").removeClass('hidden').addClass('flex');
                $jmlPinjaman.addClass('border-rose-500 ring-1 ring-rose-500');
                hasError = true;
                if (!$firstError) $firstError = $jmlPinjaman;
            }

            const $angsuran = $form.find("#angsuran");
            const cicilan = parseInt($angsuran.val()) || 0;
            if (cicilan <= 0 || (max_cicilan > 0 && cicilan > max_cicilan)) {
                $form.find("#error-angsuran-pjp").removeClass('hidden').addClass('flex');
                $angsuran.addClass('border-rose-500 ring-1 ring-rose-500');
                hasError = true;
                if (!$firstError) $firstError = $angsuran;
            }

            if (hasError) {
                if ($firstError) $firstError.focus();
                return false;
            }

            const $btn = $form.find("#btnSimpanPjp");
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
