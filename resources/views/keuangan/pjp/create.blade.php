<form action="{{ route('pjp.store') }}" id="formPJP" method="POST">
    @csrf
    <x-input-with-icon icon="ti ti-barcode" label="Auto" disabled="true" name="no_pjp" />
    <x-input-with-icon icon="ti ti-calendar" label="Tanggal Pengajuan" name="tanggal" datepicker="flatpickr-date" />
    <div class="divider">
        <div class="divider-text">Data Karyawan</div>
    </div>
    <div class="input-group mb-3">
        <input type="text" class="form-control" name="nik" id="nik" readonly placeholder="Cari Karyawan"
            aria-label="Cari Karyawan" aria-describedby="nik_search">
        <a class="btn btn-primary waves-effect" id="nik_search"><i class="ti ti-search text-white"></i></a>
    </div>
    <div class="row mb-3">
        <div class="col-lg-6 col-md-12 col-sm-12">
            <table class="table mb-3">
                <tr>
                    <th>Nama Karyawan</th>
                    <td id="nama_karyawan"></td>
                </tr>
                <tr>
                    <th>Departemen</th>
                    <td id="nama_dept"></td>
                </tr>
                <tr>
                    <th>Jabatan</th>
                    <td id="nama_jabatan"></td>
                </tr>
                <tr>
                    <th>Kantor</th>
                    <td id="nama_cabang"></td>
                </tr>
                <tr>
                    <th>Masa Kerja</th>
                    <td id="masa_kerja"></td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td id="status_karyawan"></td>
                </tr>
                <tr>
                    <th>Akhir Kontrak</th>
                    <td id="akhir_kontrak"></td>
                </tr>
            </table>
        </div>
        <div class="col-lg-6 col-sm-12 col-md-12">
            <table class="table">
                <tr>
                    <th>Gaji Pokok + Tunjangan</th>
                    <td id="gapok_tunjangan" class="text-end"></td>
                </tr>
                <tr>
                    <th>Tenor Maksimal</th>
                    <td id="tenor_max" class="text-end"></td>
                </tr>
                <tr>
                    <th>Angsuran Maksimal</th>
                    <td id="angsuran_max" class="text-end"></td>
                </tr>
                <tr>
                    <th>Plafon</th>
                    <td id="plafon" class="text-end"></td>
                </tr>
                <tr>
                    <th>JMK</th>
                    <td id="jmk" class="text-end"></td>
                </tr>
                <tr>
                    <th>JMK Dibayar</th>
                    <td id="jmk_dibayar" class="text-end"></td>
                </tr>
                <tr>
                    <th>Plafon Maksimal</th>
                    <td id="plafon_max" class="text-end"></td>
                </tr>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col" id="loadpjp">

        </div>
    </div>

</form>
<script>
    $(function() {
        const form = $("#formPJP");
        const hariini = "{{ date('Y-m-d') }}";
        $(".flatpickr-date").flatpickr({
            enable: [{
                from: hariini,
                to: "{{ $end_periode }}"
            }, ]
        });

        let max_pinjaman;
        let max_cicilan;
        let max_angsuran;

        function getMulaicicilan() {
            var tanggal_pinjaman = form.find("#tanggal").val();
            var tanggal = tanggal_pinjaman.split("-");
            var tgl = tanggal[2];
            var bulan = tanggal[1];
            var tahun = tanggal[0];

            if (tanggal_pinjaman != "") {
                if (tgl == 19 || tgl == 20) {
                    Swal.fire({
                        title: "Oops!",
                        text: "Tidak Bisa Melakukan Ajuan Pada Tanggal 19 & 20",
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: (e) => {
                            forn.find("#mulai_cicilan").val("");
                            form.find("#tanggal").val("");
                        },
                    });

                } else {
                    if (tgl <= 18 && bulan <= 10) {
                        var nextbulan = parseInt(bulan) + 1;
                        var nexttahun = parseInt(tahun);
                    } else if (tgl <= 18 && bulan == 12) {
                        var nextbulan = 1;
                        var nexttahun = parseInt(tahun) + 1;
                    } else if (parseInt(tgl) >= 21 && parseInt(bulan) <= 10) {
                        var nextbulan = parseInt(bulan) + 2;
                        var nexttahun = parseInt(tahun);
                    } else if (parseInt(tgl) <= 18 && parseInt(bulan) <= 11) {
                        var nextbulan = parseInt(bulan) + 1;
                        var nexttahun = parseInt(tahun);
                    } else if (parseInt(tgl) >= 21 && parseInt(bulan) == 11) {
                        var nextbulan = 1;
                        var nexttahun = parseInt(tahun) + 1;
                    } else if (parseInt(tgl) >= 21 && parseInt(bulan) == 12) {
                        var nextbulan = 2;
                        var nexttahun = parseInt(tahun) + 1;
                    }
                    if (nextbulan <= 9) {
                        var nextbulan = "0" + nextbulan;
                    }
                    var mulai_cicilan = nexttahun + "-" + nextbulan + "-01";
                    form.find("#mulai_cicilan").val(mulai_cicilan);
                }
            }
        }

        form.find("#tanggal").change(function(e) {
            getMulaicicilan();
        });

        $('#tabelkaryawan tbody').on('click', '.pilihkaryawan', function(e) {
            e.preventDefault();
            let nik = $(this).attr('nik');
            getKaryawan(nik);
            //getKaryawan(nik);
        });

        function convertToRupiah(number) {
            if (number) {
                var rupiah = "";
                var numberrev = number
                    .toString()
                    .split("")
                    .reverse()
                    .join("");
                for (var i = 0; i < numberrev.length; i++)
                    if (i % 3 == 0) rupiah += numberrev.substr(i, 3) + ".";
                return (
                    rupiah
                    .split("", rupiah.length - 1)
                    .reverse()
                    .join("")
                );
            } else {
                return number;
            }
        }

        function convertDateFormatToIndonesian(dateStr) {
            // Membuat objek Date dari string
            let dateObj = new Date(dateStr);

            // Mengambil hari, bulan, dan tahun
            let day = dateObj.getDate();
            let month = dateObj.getMonth(); // Bulan dimulai dari 0
            let year = dateObj.getFullYear();

            // Array nama bulan dalam bahasa Indonesia
            const monthsIndonesian = [
                "Januari", "Februari", "Maret", "April", "Mei", "Juni",
                "Juli", "Agustus", "September", "Oktober", "November", "Desember"
            ];

            // Mengambil nama bulan berdasarkan indeks
            let monthName = monthsIndonesian[month];

            // Memastikan format dua digit untuk hari
            day = day < 10 ? '0' + day : day;

            // Menyusun kembali dalam format d M Y (dd NamaBulan yyyy)
            let formattedDate = day + ' ' + monthName + ' ' + year;

            return formattedDate;
        }


        function calculateMonthDifference(startDate, endDate) {
            // Pastikan startDate dan endDate adalah objek Date
            if (!(startDate instanceof Date) || !(endDate instanceof Date)) {
                throw new Error("Input harus berupa objek Date");
            }

            // Ekstrak tahun dan bulan dari kedua tanggal
            const startYear = startDate.getFullYear();
            const startMonth = startDate.getMonth();
            const endYear = endDate.getFullYear();
            const endMonth = endDate.getMonth();

            // Hitung perbedaan tahun dan bulan
            const yearDifference = endYear - startYear;
            const monthDifference = endMonth - startMonth;

            // Hitung total jumlah bulan
            const totalMonths = yearDifference * 12 + monthDifference;

            return totalMonths;
        }


        function calculateWorkDuration(startDate, endDate) {
            // Pastikan startDate dan endDate adalah objek Date
            if (!(startDate instanceof Date) || !(endDate instanceof Date)) {
                throw new Error("Input harus berupa objek Date");
            }

            // Hitung selisih waktu dalam milidetik
            let diff = endDate - startDate;

            // Satu hari dalam milidetik
            const oneDay = 1000 * 60 * 60 * 24;

            // Total hari
            let days = Math.floor(diff / oneDay);

            // Hitung tahun
            const years = Math.floor(days / 365);
            days -= years * 365;

            // Hitung bulan
            const months = Math.floor(days / 30);
            days -= months * 30;

            return {
                years,
                months,
                days
            };
        }

        function hitungJmk(masa_kerja) {
            let jmlkali;
            switch (true) {
                case masa_kerja >= 3 && masa_kerja < 6:
                    jmlkali = 2;
                    break;
                case masa_kerja >= 6 && masa_kerja < 9:
                    jmlkali = 3;
                    break;
                case masa_kerja >= 9 && masa_kerja < 12:
                    jmlkali = 4;
                    break;
                case masa_kerja >= 12 && masa_kerja < 15:
                    jmlkali = 5;
                    break;
                case masa_kerja >= 15 && masa_kerja < 18:
                    jmlkali = 6;
                    break;
                case masa_kerja >= 18 && masa_kerja < 21:
                    jmlkali = 7;
                    break;
                case masa_kerja >= 21 && masa_kerja < 24:
                    jmlkali = 8;
                    break;
                case masa_kerja >= 24:
                    jmlkali = 10;
                    break;
                default:
                    jmlkali = 1;

            }

            return jmlkali;
        }

        function getKaryawan(nik) {
            $.ajax({
                url: `/karyawan/${nik}/getkaryawan`,
                type: "GET",
                cache: false,
                success: function(response) {
                    //fill data to form
                    console.log(response);
                    form.find("#nik").val(response.data.nik);
                    form.find("#nama_karyawan").text(response.data.nama_karyawan);
                    form.find("#nama_dept").text(response.data.nama_dept);
                    form.find("#nama_jabatan").text(response.data.nama_jabatan);
                    form.find("#nama_cabang").text(response.data.nama_cabang);

                    //Hitung Jumlah Bulan Kerja
                    const startDate = new Date(response.data.tanggal_masuk);
                    const endDate = new Date();
                    const jumlahBulankerja = calculateMonthDifference(startDate, endDate);
                    const masaKerja = calculateWorkDuration(startDate, endDate);

                    form.find("#masa_kerja").text(
                        `${masaKerja.years} Tahun, ${masaKerja.months} Bulan`);
                    form.find("#status_karyawan").text(response.data.statuskaryawan);
                    form.find("#gapok_tunjangan").text(convertToRupiah(response.data
                        .gapok_tunjangan));

                    let tenor_max;
                    if (response.data.status_karyawan == 'T') {
                        tenor_max = 20;
                        form.find('#akhir_kontrak').html('<i class="ti ti-infinity"></i>');
                    } else {
                        tenor_max = calculateMonthDifference(new Date(), new Date(response.data
                            .akhir_kontrak));
                        tenor_max = tenor_max > 0 ? tenor_max : 0;
                        form.find("#akhir_kontrak").text(convertDateFormatToIndonesian(response.data
                            .akhir_kontrak));
                    }
                    max_cicilan = tenor_max;
                    form.find("#tenor_max").text(tenor_max + ' Bulan');

                    let angsuran_max = Math.round(40 / 100 * response.data.gapok_tunjangan);
                    form.find("#angsuran_max").text(convertToRupiah(angsuran_max));
                    max_angsuran = angsuran_max;


                    let plafon = angsuran_max * tenor_max;
                    form.find("#plafon").text(convertToRupiah(plafon));
                    form.find("#jmk_dibayar").text(convertToRupiah(response.data
                        .total_jmk_dibayar));

                    let jmlkali_jmk = hitungJmk(masaKerja.years);
                    let masa_kerja_bulan = (parseInt(masaKerja.years) * 12) + parseInt(masaKerja
                        .months);
                    let total_upah = 0;
                    let persentase_jmk = 0;
                    if (parseInt(masa_kerja_bulan) <= 23) {
                        total_upah = response.data.gaji_pokok;
                        persentase_jmk = 25;
                    } else if (parseInt(masa_kerja_bulan) <= 28) {
                        total_upah = response.data.gaji_pokok;
                        persentase_jmk = 50;
                    } else {
                        total_upah = response.data.gapok_tunjangan;
                        persentase_jmk = 100;
                    }


                    let total_jmk;
                    // if (masaKerja.years <= 2) {
                    //     total_jmk = jmlkali_jmk * response.data.gaji_pokok;
                    // } else {
                    //     total_jmk = jmlkali_jmk * response.data.gapok_tunjangan;
                    // }
                    total_jmk = (persentase_jmk / 100) * parseInt(total_upah) * parseInt(
                        jmlkali_jmk);
                    form.find("#jmk").text(convertToRupiah(total_jmk));

                    let sisa_jmk = total_jmk - response.data.total_jmk_dibayar;
                    let plafon_max;
                    if (plafon < sisa_jmk) {
                        plafon_max = plafon;
                    } else {
                        plafon_max = sisa_jmk;
                    }
                    form.find("#plafon_max").text(convertToRupiah(plafon_max));
                    max_pinjaman = plafon_max;


                    const sp_pusat = ['SP2', 'SP3'];
                    const sp_cabang = ['SP1', 'SP2', 'SP3'];

                    const minimal_bayar = 75 / 100 * response.data.total_pinjaman;
                    const persentase_bayar = Math.round(response.data.total_pembayaran / response
                        .data.total_pinjaman * 100);
                    //Kondisi Karyawan Tidak Bisa Melakukan PJP
                    if (response.data.status_karyawan == 'O') {
                        // Jika Status Karyawan Outsourcing
                        $("#loadpjp").html(`
                            <div class="alert alert-danger d-flex align-items-center" role="alert">
                                <span class="alert-icon text-danger me-2">
                                <i class="ti ti-ban ti-xs"></i>
                                </span>
                                Tidak Dapat Melakukan Ajuan PJP, Karena Status Karyawan Sebagai Karyawan Outsourcing
                            </div>`);
                    } else if (jumlahBulankerja < 15) {
                        //Masa Kerja Kurang dari 15 Bulan
                        $("#loadpjp").html(`
                        <div class="alert alert-danger d-flex align-items-center" role="alert">
                            <span class="alert-icon text-danger me-2">
                            <i class="ti ti-ban ti-xs"></i>
                            </span>
                            Tidak Dapat Melakukan Ajuan PJP, Masa Kerja Karyawan Kurang dari 1,3 Tahun atau 15 Bulan, Masa Kerja Karyawan Saat Ini ${jumlahBulankerja} Bulan
                        </div>`);
                    } else if (tenor_max <= 0) {
                        //Kontrak Habis
                        $("#loadpjp").html(`
                        <div class="alert alert-danger d-flex align-items-center" role="alert">
                            <span class="alert-icon text-danger me-2">
                            <i class="ti ti-ban ti-xs"></i>
                            </span>
                            Tidak Dapat Melakukan Ajuan PJP, Karena Kontrak Karyawan Habis pada Tanggal ${convertDateFormatToIndonesian(response.data.akhir_kontrak)}, Silahkan Hubungi Departemen HRD
                        </div>`);
                    } else if (response.data.kode_cabang == 'PST' && sp_pusat.includes(response.data
                            .jenis_sp) || response.data
                        .kode_cabang != 'PST' && sp_cabang.includes(response.data.jenis_sp)) {
                        //Masih dalam Masa SP
                        $("#loadpjp").html(`
                        <div class="alert alert-danger d-flex align-items-center" role="alert">
                            <span class="alert-icon text-danger me-2">
                            <i class="ti ti-ban ti-xs"></i>
                            </span>
                            Tidak Dapat Melakukan Ajuan PJP, Karena Kontrak Karyawan Masih Dalam Masa ${response.data.jenis_sp}, Yang Berakhir Pada
                            ${convertDateFormatToIndonesian(response.data.tanggal_berakhir_sp)}
                        </div>`);
                    } else if (response.data.total_pembayaran < minimal_bayar) {
                        $("#loadpjp").html(`
                        <div class="alert alert-danger d-flex align-items-center" role="alert">
                            <span class="alert-icon text-danger me-2">
                            <i class="ti ti-ban ti-xs"></i>
                            </span>
                            Tidak Dapat Melakukan Ajuan PJP, Karana Karyawan Masih Memiliki Pinjaman, Untuk Melakukan Ajuan PJP kembali, Karyawan harus sudah membayar 75% dari Pinjaman Sebelumnya, Total Pinjaman Sebesar ${convertToRupiah(response.data.total_pinjaman)} dan Total Yang Sudah Dibayarkan Sebesar ${convertToRupiah(response.data.total_pembayaran)} (${persentase_bayar}%)
                        </div>`);
                    } else {
                        $("#loadpjp").html(`
                        <x-input-with-icon label="Jumlah Pinjaman" icon="ti ti-moneybag" name="jumlah_pinjaman" align="right" money="true" />
                        <x-input-with-icon label="Jumlah Cicilan" name="angsuran" icon="ti ti-box" align="right" />
                        <x-input-with-icon label="Jumlah Angsuran / Bulan" name="jumlah_angsuran" icon="ti ti-moneybag" align="right" readonly="true" />
                        <x-input-with-icon label="Mulai Cicilan" name="mulai_cicilan" icon="ti ti-calendar" readonly="true" />
                        <div class="form-group mb-3">
                            <button class="btn btn-primary w-100" id="btnSimpan"><i class="ti ti-send me-1"></i>Submit</button>
                        </div>
                        `);
                        $(".money").maskMoney();
                        form.find("#angsuran").mask("##");
                        getMulaicicilan();
                    }
                    $("#modalKaryawan").modal("hide");
                }
            });
        }


        function calculateAngsuranperbulan() {
            let jmlpinjaman = form.find("#jumlah_pinjaman").val();
            let jumlah_pinjaman = jmlpinjaman != "" ? jmlpinjaman.replace(/\./g, '') : 0;
            let angsuran = form.find("#angsuran").val();
            let angsuranperbulan = angsuran != "" && angsuran !== '0' ? Math.round(jumlah_pinjaman / angsuran) :
                0;
            angsuranperbulan = isNaN(angsuranperbulan) ? 0 : angsuranperbulan;
            form.find("#jumlah_angsuran").val(convertToRupiah(angsuranperbulan));
        }

        $(document).on('keyup keydown', '#jumlah_pinjaman', function(e) {
            let jmlpinjaman = $(this).val();
            let jumlah_pinjaman = jmlpinjaman != "" ? parseInt(jmlpinjaman.replace(/\./g, '')) : 0;
            calculateAngsuranperbulan();
            if (jumlah_pinjaman > max_pinjaman) {
                Swal.fire({
                    title: "Oops!",
                    text: "Jumlah Pinjaman Melebihi Plafon Maksimal!",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#jumlah_pinjaman").focus();
                        form.find("#jumlah_pinjaman").val(0);
                        calculateAngsuranperbulan();
                    },
                });
            }
        });

        $(document).on('keyup keydown', '#angsuran', function(e) {
            let angsuran = $(this).val();
            calculateAngsuranperbulan();
            if (angsuran > max_cicilan) {
                Swal.fire({
                    title: "Oops!",
                    text: "Jumlah Cicilan Melebihi Tenor Maksimal!",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#angsuran").focus();
                        form.find("#angsuran").val(0);
                        calculateAngsuranperbulan();
                    },
                });
            }
        });




        function buttonDisable() {
            $("#btnSimpan").prop('disabled', true);
            $("#btnSimpan").html(`
            <div class="spinner-border spinner-border-sm text-white me-2" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            Loading..`);
        }
        form.submit(function() {
            const tanggal = form.find("#tanggal").val();
            const nik = form.find("#nik").val();
            const jumlah_pinjaman = form.find("#jumlah_pinjaman").val();
            const angsuran = form.find("#angsuran").val();
            let jmlangsuran = form.find("#jumlah_angsuran").val();
            let jumlah_angsuran = jmlangsuran != "" ? parseInt(jmlangsuran.replace(/\./g, '')) : 0;
            const mulai_cicilan = form.find("#mulai_cicilan").val();
            if (tanggal == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Tanggal harus diisi!",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#tanggal").focus();
                    },
                });
                return false;
            } else if (nik == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "NIK harus diisi!",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#nik").focus();
                    },
                });
                return false;
            } else if (jumlah_pinjaman == "" || jumlah_pinjaman === 0) {
                Swal.fire({
                    title: "Oops!",
                    text: "Jumlah Pinjaman harus diisi!",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#jumlah_pinjaman").focus();
                    },
                });
                return false;
            } else if (angsuran == "" || angsuran === 0) {
                Swal.fire({
                    title: "Oops!",
                    text: "Angsuran harus diisi!",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#angsuran").focus();
                    },
                });
                return false;
            } else if (jumlah_angsuran == "" || jumlah_angsuran === 0) {
                Swal.fire({
                    title: "Oops!",
                    text: "Jumlah Angsuran harus diisi!",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#jumlah_angsuran").focus();
                    },
                });
                return false;
            } else if (jumlah_angsuran > max_angsuran) {
                Swal.fire({
                    title: "Oops!",
                    text: "Melebihi Angsuran Maksimal Perbulan",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#jumlah_angsuran").focus();
                        form.find("#jumlah_angsuran").val(0);
                    },
                });
                return false;
            } else if (mulai_cicilan == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Tanggal Mulai Cicilan harus diisi!",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#mulai_cicilan").focus();
                    },
                });
                return false;
            } else {
                buttonDisable();
            }
        });
    });
</script>
