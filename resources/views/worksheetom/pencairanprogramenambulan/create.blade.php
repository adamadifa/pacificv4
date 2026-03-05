<form action="{{ route('pencairanprogramenambulan.store') }}" method="POST" id="formPencairanProgramenambulan">
    @csrf
    <x-input-with-icon label="Tanggal" name="tanggal" icon="ti ti-calendar" datepicker="flatpickr-date"
        value="{{ date('Y-m-d') }}" readonly hideLabel="true" />

    <div class="form-group mb-3">
        <x-select label="Pilih Program" name="kode_program" :data="$programikatan" key="kode_program" textShow="nama_program" hideLabel="true" />
    </div>
    @hasanyrole($roles_show_cabang)
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <x-select label="Pilih Cabang" name="kode_cabang" :data="$cabang" key="kode_cabang" textShow="nama_cabang"
                    upperCase="true" select2="select2Kodecabangsearch" hideLabel="true" />
            </div>
        </div>
    @endrole
    <div class="row">
        <div class="col-lg-8 col-md-12 col-sm-12">
            <div class="form-group">
                <x-select label="Semester" name="semester" :data="[
                    (object)['kode' => '1', 'nama' => 'Semester 1'],
                    (object)['kode' => '2', 'nama' => 'Semester 2']
                ]" key="kode" textShow="nama" hideLabel="true" />
            </div>

        </div>
        <div class="col-lg-4 col-md-12 col-sm-12">
            <div class="form-group">
                <x-select label="Tahun" name="tahun" :data="$list_tahun" key="tahun" textShow="tahun" hideLabel="true" />
            </div>
        </div>
    </div>

    <x-textarea label="Keterangan" name="keterangan" hideLabel="true" />
    <div class="form-group mb3">
        <button class="btn btn-primary w-100" id="btnSimpan"><i class="ti ti-send me-1"></i>Submit</button>
    </div>


</form>
<script>
    $(function() {
        // $(".flatpickr-date").flatpickr();

        const select2Kodecabangsearch = $('.select2Kodecabangsearch');
        if (select2Kodecabangsearch.length) {
            select2Kodecabangsearch.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Pilih Cabang',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }
        $("#formPencairanProgramenambulan").submit(function(e) {
            let tanggal = $(this).find('input[name="tanggal"]').val();
            let keterangan = $(this).find('textarea[name="keterangan"]').val();
            let semester = $(this).find('select[name="semester"]').val();
            let tahun = $(this).find('select[name="tahun"]').val();
            let kode_program = $(this).find('select[name="kode_program"]').val();
            let kode_cabang = $(this).find('select[name="kode_cabang"]').val();

            if (tanggal == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Tanggal harus diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        $(this).find("#tanggal").focus();
                    },
                });
                return false;
            } else if (kode_program == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Program harus diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        $(this).find("#kode_program").focus();
                    },
                });
                return false;
            } else if (kode_cabang == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Cabang harus diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        $(this).find("#kode_cabang").focus();
                    }
                });
                return false;
            } else if (semester == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Semester harus diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        $(this).find("#bulan").focus();
                    }
                });
                return false;
            } else if (tahun == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Tahun harus diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        $(this).find("#tahun").focus();
                    }
                });
                return false;
            } else if (keterangan == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Keterangan harus diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        $(this).find("#keterangan").focus();
                    }
                });
                return false;
            } else {
                $("#btnSimpan").prop('disabled', true);
                $("#btnSimpan").html(
                    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...'
                );
            }
        });
    });
</script>
