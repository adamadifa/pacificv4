<form action="#" id="frmKaryawan">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-sm btn-primary" id="tambahkansemua">
                <i class="ti ti-plus me-1"></i> Tambahkan Semua
            </button>
            <button type="button" class="btn btn-sm btn-danger" id="batalkansemua">
                <i class="ti ti-minus me-1"></i> Batalkan Semua
            </button>
        </div>
    </div>

    <div class="row g-2 mb-3">
        <div class="col-lg-6 col-md-12 col-sm-12">
            <x-select label="Group" name="kode_group" :data="$group" key="kode_group" textShow="nama_group"
                select2="select2Group" upperCase="true" />
        </div>
        <div class="col-lg-6 col-md-12 col-sm-12">
            <x-input-with-icon label="Nama Karyawan" name="nama_karyawan" icon="ti ti-search" />
        </div>
    </div>

    <div class="table-responsive" style="max-height: 420px; overflow-y: auto;">
        <table class="table table-bordered table-striped table-hover mb-0" id="tabelkaryawan">
            <thead class="text-white sticky-top">
                <tr style="background-color: #284c9a;">
                    <th class="text-white text-center" style="width: 50px;">No.</th>
                    <th class="text-white" style="width: 130px;">NIK</th>
                    <th class="text-white">Nama Karyawan</th>
                    <th class="text-white" style="width: 140px;">Group</th>
                    <th class="text-white text-center" style="width: 70px;">#</th>
                </tr>
            </thead>
            <tbody id="loadkaryawan">
                <tr>
                    <td colspan="5" class="text-center py-3 text-muted">Memuat data...</td>
                </tr>
            </tbody>
        </table>
    </div>
</form>
<script>
    $(document).ready(function() {
        const form = $('#frmKaryawan');


        function loadliburkaryawan() {
            const kode_libur = "{{ Crypt::encrypt($harilibur->kode_libur) }}";
            $("#loadliburkaryawan").html(`<tr><td colspan="4" class="text-center">Loading...</td></tr>`);
            $("#loadliburkaryawan").load(`/harilibur/${kode_libur}/getkaryawanlibur`);
        }

        function loadkaryawan() {
            const kode_libur = "{{ Crypt::encrypt($harilibur->kode_libur) }}";
            const kode_group = form.find("#kode_group").val();
            const nama_karyawan = form.find("#nama_karyawan").val();
            // $("#loadkaryawan").html(`<tr><td colspan="5" class="text-center">Tunggu Sebentar...</td></tr>`);
            $.ajax({
                type: 'POST',
                url: `/harilibur/getkaryawan`,
                data: {
                    _token: "{{ csrf_token() }}",
                    kode_libur: kode_libur,
                    kode_group: kode_group,
                    nama_karyawan: nama_karyawan
                },
                cache: false,
                success: function(respond) {
                    $("#loadkaryawan").html(respond);
                    loadliburkaryawan();
                }
            })
        }

        loadkaryawan();

        form.find("#kode_group").change(function() {
            $("#loadkaryawan").html(
                `<tr><td colspan="5" class="text-center">Tunggu Sebentar...</td></tr>`);
            loadkaryawan();
        });

        form.find("#nama_karyawan").keyup(function() {
            $("#loadkaryawan").html(
                `<tr><td colspan="5" class="text-center">Tunggu Sebentar...</td></tr>`);
            loadkaryawan();
        });

        $(document).off('click').on('click', '#tabelkaryawan .updateLibur', function(e) {
            e.preventDefault();
            const nik = $(this).attr('nik');
            const kode_libur = "{{ $harilibur->kode_libur }}";
            //Ubah pada kolom Status Jadwal menjadi loading
            $(this).html('<i class="fas fa-spinner fa-spin"></i>');
            $.ajax({
                type: 'POST',
                url: `/harilibur/updateliburkaryawan`,
                data: {
                    _token: "{{ csrf_token() }}",
                    nik: nik,
                    kode_libur: kode_libur
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
            const kode_libur = "{{ $harilibur->kode_libur }}";
            const kode_group = form.find("#kode_group").val();
            $("#loadkaryawan").html(
                `<tr><td colspan="5" class="text-center">Tunggu Sebentar....</td></tr>`);
            $.ajax({
                type: 'POST',
                url: `/harilibur/tambahkansemua`,
                data: {
                    _token: "{{ csrf_token() }}",
                    kode_libur: kode_libur,
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
            const kode_libur = "{{ $harilibur->kode_libur }}";
            const kode_group = form.find("#kode_group").val();
            $("#loadkaryawan").html(
                `<tr><td colspan="5" class="text-center">Tunggu Sebentar....</td></tr>`);
            $.ajax({
                type: 'POST',
                url: `/harilibur/batalkansemua`,
                data: {
                    _token: "{{ csrf_token() }}",
                    kode_libur: kode_libur,
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
