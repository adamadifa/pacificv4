<form action="{{ route('ajuanprogramenambulan.store') }}" method="POST" id="formAjuanprogram"
    enctype="multipart/form-data">
    @csrf
    {{-- <x-input-with-icon label="No. Dokumen" name="no_dokumen" icon="ti ti-barcode" /> --}}
    <input type="hidden" name="no_dokumen" id="no_dokumen" value="-">
    <x-input-with-icon label="Tanggal" name="tanggal" icon="ti ti-calendar" value="{{ date('Y-m-d') }}" readonly hideLabel="true" />
    @hasanyrole($roles_show_cabang)
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <x-select label="Pilih Cabang" name="kode_cabang" :data="$cabang" key="kode_cabang" textShow="nama_cabang"
                    upperCase="true" select2="select2Kodecabangsearch" hideLabel="true" />
            </div>
        </div>
    @endrole

    <x-select label="Pilih Program" name="kode_program" :data="$programikatan" key="kode_program" textShow="nama_program"
        hideLabel="true" />
    <div class="row">
        <div class="col-lg-8 col-md-12 col-sm-12">
            @php
                $semester_data = [(object) ['kode' => '1', 'nama' => 'Semester 1'], (object) ['kode' => '2', 'nama' => 'Semester 2']];
            @endphp
            <x-select label="Semester" name="semester" :data="$semester_data" key="kode" textShow="nama" hideLabel="true" />
        </div>
        <div class="col-lg-4 col-md-12 col-sm-12">
            @php
                $tahun_data = [];
                for ($t = $start_year; $t <= date('Y'); $t++) {
                    $tahun_data[] = (object) ['tahun' => $t];
                }
            @endphp
            <x-select label="Tahun" name="tahun" :data="$tahun_data" key="tahun" textShow="tahun" hideLabel="true" />
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            @php
                $pencairan_data = [(object) ['kode' => '1', 'nama' => 'Akhir Semester'], (object) ['kode' => '2', 'nama' => 'Perbulan']];
            @endphp
            <x-select label="Periode Pencairan" name="periode_pencairan" :data="$pencairan_data" key="kode" textShow="nama" hideLabel="true" />
        </div>
    </div>
    <x-textarea label="Keterangan" name="keterangan" hideLabel="true" />

    <div class="form-group mb3">
        <button class="btn btn-primary w-100" id="btnSimpan"><i class="ti ti-send me-1"></i>Submit</button>
    </div>
</form>
<script>
    $(document).ready(function() {
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
        $(".flatpickr-date").flatpickr();

        function buttonDisable() {
            $("#btnSimpan").prop('disabled', true);
            $("#btnSimpan").html(`
            <div class="spinner-border spinner-border-sm text-white me-2" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            Loading..
         `);
        }

        const form = $('#formAjuanprogram');
        form.submit(function(e) {
            let no_dokumen = form.find('input[name="no_dokumen"]').val();
            let tanggal = form.find('input[name="tanggal"]').val();
            let kode_cabang = form.find('select[name="kode_cabang"]').val();
            let kode_program = form.find('select[name="kode_program"]').val();
            let semester = form.find('select[name="semester"]').val();
            let tahun = form.find('select[name="tahun"]').val();
            if (no_dokumen == '') {
                Swal.fire({
                    title: "Oops!",
                    text: "No Dokumen harus diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        form.find("#no_dok").focus();
                    },
                });
                return false;
            } else if (tanggal == '') {
                Swal.fire({
                    title: "Oops!",
                    text: "Tanggal harus diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        form.find("#tanggal").focus();
                    },
                });
                return false;
            } else if (kode_cabang == '') {
                Swal.fire({
                    title: "Oops!",
                    text: "Cabang harus diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        form.find("#kode_cabang").focus();
                    },
                });
                return false;
            } else if (kode_program == '') {
                Swal.fire({
                    title: "Oops!",
                    text: "Program harus diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        form.find("#kode_program").focus();
                    },
                });
                return false;
            } else if (semester == '' || tahun == '') {
                Swal.fire({
                    title: "Oops!",
                    text: "Periode harus diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        form.find("#semester").focus();
                    }
                });
                return false;
            } else {
                buttonDisable();
            }
        });
    });
</script>
