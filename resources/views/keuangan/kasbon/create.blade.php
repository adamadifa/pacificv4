<form action="{{ route('kasbon.store') }}" id="formKasbon" method="POST">
    @csrf
    <x-input-with-icon icon="ti ti-barcode" label="Auto" disabled="true" name="no_kasbon" />
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
        <div class="col-lg-12 col-md-12 col-sm-12">
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
                <tr>
                    <th>Maksimal Kasbon</th>
                    <td id="max_kasbon"></td>
                </tr>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col" id="loadkasbon">

        </div>
    </div>

</form>
<script src="{{ asset('assets/js/helper/helper.js') }}"></script>
<script>
    $(function() {
        const form = $("#formKasbon");
        const hariini = "{{ date('Y-m-d') }}";
        $(".flatpickr-date").flatpickr({
            enable: [{
                from: hariini,
                to: "{{ $end_periode }}"
            }, ]
        });


        //Get Mulai Cicilan
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

        //Pilih Karyawan
        $('#tabelkaryawan tbody').on('click', '.pilihkaryawan', function(e) {
            e.preventDefault();
            let nik = $(this).attr('nik');
            getKaryawan(nik);
            //getKaryawan(nik);
        });

        let max_kasbon = 0;
        //Get Data Karyawan
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
                    const masaKerja = calculateWorkDuration(startDate, endDate);
                    const jumlahBulankerja = calculateMonthDifference(startDate, endDate);


                    form.find("#masa_kerja").text(`${masaKerja.years} Tahun, ${masaKerja.months} Bulan`);
                    form.find("#status_karyawan").text(response.data.statuskaryawan);

                    if (response.data.status_karyawan == 'T') {
                        form.find('#akhir_kontrak').html('<i class="ti ti-infinity"></i>');
                    } else {
                        form.find("#akhir_kontrak").text(convertDateFormatToIndonesian(response.data.akhir_kontrak));
                    }



                    if (response.data.cekkasbon > 0) {
                        max_kasbon = 0;
                        $("#loadkasbon").html(`
                            <div class="alert alert-danger d-flex align-items-center" role="alert">
                                <span class="alert-icon text-danger me-2">
                                <i class="ti ti-ban ti-xs"></i>
                                </span>
                                Tidak Dapat Melakukan Ajuan Kasbon, Karena Masih Memiliki Ajuan Kasbon Yang Belum Lunas
                            </div>`);
                    } else {
                        if (jumlahBulankerja < 9) {
                            max_kasbon = 200000;
                        } else if (jumlahBulankerja <= 15) {
                            max_kasbon = 400000;
                        } else {
                            max_kasbon = 600000;
                        }

                        if (response.data.kasbon_max !== 0) {
                            if (response.data.kasbon_max > max_kasbon) {
                                max_kasbon = max_kasbon;
                            } else {
                                max_kasbon = response.data.kasbon_max;
                            }
                        } else {
                            max_kasbon = max_kasbon;
                        }

                        $("#loadkasbon").html(`
                        <x-input-with-icon label="Jumlah Kasbon" icon="ti ti-moneybag" name="jumlah" align="right" money="true" />
                        <x-input-with-icon label="Mulai Cicilan" name="mulai_cicilan" icon="ti ti-calendar" readonly="true" />
                        <div class="form-group mb-3">
                            <button class="btn btn-primary w-100" id="btnSimpan"><i class="ti ti-send me-1"></i>Submit</button>
                        </div>
                        `);
                        $(".money").maskMoney();
                        getMulaicicilan();
                    }


                    form.find("#max_kasbon").text(convertToRupiah(max_kasbon));

                    $("#modalKaryawan").modal("hide");
                }
            });
        }

        $(document).on('keyup keydown', '#jumlah', function(e) {
            let jmlkasbon = $(this).val();
            let jumlah = jmlkasbon != "" ? parseInt(jmlkasbon.replace(/\./g, '')) : 0;
            if (jumlah > max_kasbon) {
                Swal.fire({
                    title: "Oops!",
                    text: "Jumlah Kasbon Melebihi Kasbon Maksimal!",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#jumlah").focus();
                        form.find("#jumlah").val(0);
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
            let jmlkasbon = form.find("#jumlah").val();
            let jumlah = jmlkasbon != "" ? parseInt(jmlkasbon.replace(/\./g, '')) : 0;
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
            } else if (jumlah == "" || jumlah === 0) {
                Swal.fire({
                    title: "Oops!",
                    text: "Jumlah Kasbon harus diisi!",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#jumlah").focus();
                    },
                });
                return false;
            } else if (jumlah > max_kasbon) {
                Swal.fire({
                    title: "Oops!",
                    text: "Jumlah Kasbon Melebihi Maksimal Kasbon!",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#jumlah").focus();
                    },
                });
                return false;
            } else {
                buttonDisable();
            }
        });

        form.find("#tanggal").change(function(e) {
            getMulaicicilan();
        });
    });
</script>
