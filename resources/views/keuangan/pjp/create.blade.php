<form action="#" id="formPJP">
    <x-input-with-icon icon="ti ti-barcode" label="Auto" disabled="true" name="no_pjp" />
    <x-input-with-icon icon="ti ti-calendar" label="Tanggal Pengajuan" name="tanggal" datepicker="flatpickr-date" />
    <div class="divider">
        <div class="divider-text">Data Karyawan</div>
    </div>
    <div class="input-group mb-3">
        <input type="text" class="form-control" name="nik" id="nik" readonly placeholder="Cari Karyawan" aria-label="Cari Karyawan"
            aria-describedby="nik_search">
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

    <x-input-with-icon label="Jumlah Pinjaman" icon="ti ti-moneybag" name="jumlah_pinjaman" align="right" money="true" />
    <x-input-with-icon label="Angsuran" name="angsuran" icon="ti ti-box" align="right" />
    <x-input-with-icon label="Jumlah Angsuran / Bulan" name="jumlah_angsuran" icon="ti ti-moneybag" align="right" />
    <x-input-with-icon label="Mulai Cicilan" name="mulai_cicilan" icon="ti ti-calendar" />
</form>
<script>
    $(function() {

        const form = $("#formPJP");

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

                    form.find("#masa_kerja").text(`${masaKerja.years} Tahun, ${masaKerja.months} Bulan`);
                    form.find("#status_karyawan").text(response.data.statuskaryawan);
                    form.find("#gapok_tunjangan").text(convertToRupiah(response.data.gapok_tunjangan));

                    let tenor_max;
                    if (response.data.status_karyawan == 'T') {
                        tenor_max = 20;
                        form.find('#akhir_kontrak').html('<i class="ti ti-infinity"></i>');
                    } else {
                        tenor_max = calculateMonthDifference(new Date(), new Date(response.data.akhir_kontrak));
                        form.find("#akhir_kontrak").text(convertDateFormatToIndonesian(response.data.akhir_kontrak));
                    }

                    form.find("#tenor_max").text(tenor_max + ' Bulan');

                    let angsuran_max = Math.round(40 / 100 * response.data.gapok_tunjangan);
                    form.find("#angsuran_max").text(convertToRupiah(angsuran_max));

                    let plafon = angsuran_max * tenor_max;
                    form.find("#plafon").text(convertToRupiah(plafon));
                    console.log(jumlahBulankerja);
                    $("#modalKaryawan").modal("hide");
                }
            });
        }
    });
</script>
