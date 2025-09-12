<form action="{{ route('piutangkaryawan.store') }}" id="formKasbon" method="POST">
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
    <div class="row mb-2">
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
            </table>
        </div>
    </div>

    <div class="row">
        <div class="col">

            <x-input-with-icon label="Jumlah Piutang" icon="ti ti-moneybag" name="jumlah" align="right" money="true" />
            <div class="form-group mb-3">
                <select name="kategori" id="kategori" class="form-select">
                    <option value="">Kategori</option>
                    <option value="KA">Karyawan</option>
                    <option value="EK">Eks Karyawan</option>
                </select>
            </div>
            @can('piutangkaryawan.checkstatus')
                <div class="form-check mb-3 mt-3">
                    <input class="form-check-input status" name="status" value="1" type="checkbox" id="status">
                    <label class="form-check-label" for="status"> Hanya Bisa dilihat Keuangan ?</label>
                </div>
            @endcan
            <div class="form-group mb-3">
                <button class="btn btn-primary w-100" id="btnSimpan"><i class="ti ti-send me-1"></i>Submit</button>
            </div>

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
        $(".money").maskMoney();

        //Get Mulai Cicilan

        //Pilih Karyawan
        $('#tabelkaryawan tbody').on('click', '.pilihkaryawan', function(e) {
            e.preventDefault();
            let nik = $(this).attr('nik');
            getKaryawan(nik);
            //getKaryawan(nik);
        });


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
                    $("#modalKaryawan").modal("hide");
                }
            });
        }



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
            let jml = form.find("#jumlah").val();
            let jumlah = jml != "" ? parseInt(jml.replace(/\./g, '')) : 0;
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
                    text: "Jumlah Piutang harus diisi!",
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


    });
</script>
