<form action="#" id="frmKaryawan">
    <div class="row mb-3">
        <div class="col">
            <div class="d-flex justify-content-between">
                <button class="btn btn-primary" id="tambahkansemua"><i class="ti ti-plus me-1"></i> Tambahkan Semua </button>
                <button class="btn btn-danger" id="batalkansemua"><i class="ti ti-circle-minus me-1"></i> Batalkan Semua </button>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-lg-6 col-md-12 col-sm-12">
            <x-select label="Group" name="kode_group" :data="$group" key="kode_group" textShow="nama_group" select2="select2Group"
                upperCase="true" />
        </div>
        <div class="col-lg-6 col-md-12 col-sm-12">
            <x-input-with-icon label="Nama Karyawan" name="nama_karyawan" icon="ti ti-user" />
        </div>
    </div>

    <div class="row">
        <div class="col">
            <table class="table table-bordered table-striped table-hover" id="tabelkaryawan">
                <thead class="table-dark">
                    <tr>
                        <th>No.</th>
                        <th>NIK</th>
                        <th>Nama Karyawan</th>
                        <th>Group</th>
                        <th>#</th>
                    </tr>
                </thead>
                <tbody id="loadkaryawan">

                </tbody>
            </table>
        </div>
    </div>
</form>
<script>
    $(document).ready(function() {
        const form = $('#frmKaryawan');


        function loadlemburkaryawan() {
            const kode_lembur = "{{ Crypt::encrypt($lembur->kode_lembur) }}";
            $("#loadlemburkaryawan").html(`<tr><td colspan="4" class="text-center">Loading...</td></tr>`);
            $("#loadlemburkaryawan").load(`/lembur/${kode_lembur}/getkaryawanlembur`);
        }

        function loadkaryawan() {
            const kode_lembur = "{{ Crypt::encrypt($lembur->kode_lembur) }}";
            const kode_group = form.find("#kode_group").val();
            const nama_karyawan = form.find("#nama_karyawan").val();
            // $("#loadkaryawan").html(`<tr><td colspan="5" class="text-center">Tunggu Sebentar...</td></tr>`);
            $.ajax({
                type: 'POST',
                url: `/lembur/getkaryawan`,
                data: {
                    _token: "{{ csrf_token() }}",
                    kode_lembur: kode_lembur,
                    kode_group: kode_group,
                    nama_karyawan: nama_karyawan
                },
                cache: false,
                success: function(respond) {
                    $("#loadkaryawan").html(respond);
                    loadlemburkaryawan();
                }
            })
        }

        loadkaryawan();

        form.find("#kode_group").change(function() {
            $("#loadkaryawan").html(`<tr><td colspan="5" class="text-center">Tunggu Sebentar...</td></tr>`);
            loadkaryawan();
        });

        form.find("#nama_karyawan").keyup(function() {
            $("#loadkaryawan").html(`<tr><td colspan="5" class="text-center">Tunggu Sebentar...</td></tr>`);
            loadkaryawan();
        });

        $(document).off('click').on('click', '#tabelkaryawan .updateLembur', function(e) {
            e.preventDefault();
            const nik = $(this).attr('nik');
            const kode_lembur = "{{ $lembur->kode_lembur }}";
            //Ubah pada kolom Status Jadwal menjadi loading
            $(this).html('<i class="fas fa-spinner fa-spin"></i>');
            $.ajax({
                type: 'POST',
                url: `/lembur/updatelemburkaryawan`,
                data: {
                    _token: "{{ csrf_token() }}",
                    nik: nik,
                    kode_lembur: kode_lembur
                },
                cache: false,
                success: function(respond) {
                    if (respond.success == true) {
                        loadkaryawan();
                    } else {
                        Swal.fire({
                            title: "Oops!",
                            text: respond.message,
                            icon: "warning",
                            showConfirmButton: true,
                        });

                    }
                }
            });
        });

        $("#tambahkansemua").click(function(e) {
            e.preventDefault();
            const kode_lembur = "{{ $lembur->kode_lembur }}";
            const kode_group = form.find("#kode_group").val();
            $("#loadkaryawan").html(`<tr><td colspan="5" class="text-center">Tunggu Sebentar....</td></tr>`);
            $.ajax({
                type: 'POST',
                url: `/lembur/tambahkansemua`,
                data: {
                    _token: "{{ csrf_token() }}",
                    kode_lembur: kode_lembur,
                    kode_group: kode_group
                },
                cache: false,
                success: function(respond) {
                    if (respond.success == true) {
                        loadkaryawan();
                    } else {
                        Swal.fire({
                            title: "Oops!",
                            text: respond.message,
                            icon: "warning",
                            showConfirmButton: true,
                        });
                    }
                }
            });
        });

        $("#batalkansemua").click(function(e) {
            e.preventDefault();
            const kode_lembur = "{{ $lembur->kode_lembur }}";
            const kode_group = form.find("#kode_group").val();
            $("#loadkaryawan").html(`<tr><td colspan="5" class="text-center">Tunggu Sebentar....</td></tr>`);
            $.ajax({
                type: 'POST',
                url: `/lembur/batalkansemua`,
                data: {
                    _token: "{{ csrf_token() }}",
                    kode_lembur: kode_lembur,
                    kode_group: kode_group
                },
                cache: false,
                success: function(respond) {
                    if (respond.success == true) {
                        loadkaryawan();
                    } else {
                        Swal.fire({
                            title: "Oops!",
                            text: respond.message,
                            icon: "warning",
                            showConfirmButton: true,
                        });
                    }
                }
            });
        });
    });
</script>
