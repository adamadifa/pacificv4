<form action="{{ route('programikatan2026.store') }}" method="POST" id="formAjuanprogram" enctype="multipart/form-data">
    @csrf
    {{-- <x-input-with-icon label="No. Dokumen" name="no_dokumen" icon="ti ti-barcode" /> --}}
    <input type="hidden" name="no_dokumen" id="no_dokumen" value="-">
    <x-input-with-icon label="Tanggal" name="tanggal" icon="ti ti-calendar" value="{{ date('Y-m-d') }}" readonly hideLabel="true" />
    @hasanyrole($roles_access_all_cabang)
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <x-select label="Pilih Cabang" name="kode_cabang" :data="$cabang" key="kode_cabang" textShow="nama_cabang" upperCase="true"
                    select2="select2Kodecabangsearch" hideLabel="true" />
            </div>
        </div>
    @endrole

    <div class="form-group mb-3">
        <x-select label="Pilih Program" name="kode_program" :data="$programikatan" key="kode_program" textShow="nama_program" hideLabel="true" />
    </div>
    <div class="form-group mb-3">
        <x-select label="Pilih Semester" name="pilih_semester" :data="[
            (object)['kode' => '1', 'nama' => 'Semester 1'],
            (object)['kode' => '2', 'nama' => 'Semester 2']
        ]" key="kode" textShow="nama" hideLabel="true" />
    </div>
    <div class="row">
        <div class="col-lg-8 col-md-12 col-sm-12">
            <div class="form-group">
                @php
                    $bulan_data = collect($list_bulan)->map(function ($item) {
                        return (object) $item;
                    });
                @endphp
                <x-select label="Bulan Dari" name="bulan_dari" :data="$bulan_data" key="kode_bulan" textShow="nama_bulan" hideLabel="true" />
            </div>

        </div>
        <div class="col-lg-4 col-md-12 col-sm-12">
            <div class="form-group">
                @php
                    $tahun_data = collect($list_tahun)->map(function ($item) {
                        return (object) ['tahun' => $item];
                    });
                @endphp
                <x-select label="Tahun Dari" name="tahun_dari" :data="$tahun_data" key="tahun" textShow="tahun" hideLabel="true" />
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-8 col-md-12 col-sm-12">
            <div class="form-group">
                <x-select label="Bulan Sampai" name="bulan_sampai" :data="$bulan_data" key="kode_bulan" textShow="nama_bulan" hideLabel="true" />
            </div>

        </div>
        <div class="col-lg-4 col-md-12 col-sm-12">
            <div class="form-group">
                <x-select label="Tahun Sampai" name="tahun_sampai" :data="$tahun_data" key="tahun" textShow="tahun" hideLabel="true" />
            </div>
        </div>
    </div>
    <x-textarea label="Keterangan" name="keterangan" hideLabel="true" />

    <div class="form-group mb3">
        <button class="btn btn-primary w-100" id="btnSimpan"><i class="ti ti-send me-1"></i>Submit</button>
    </div>
</form>
<script>
    $(document).ready(function() {
        function checkBulan() {
            var semester = $("#pilih_semester").val();
            var tahun = $("#tahun_dari").val();
            var currentYear = new Date().getFullYear();
            var currentMonth = new Date().getMonth() + 1;

            $("#bulan_dari").val("");
            $("#bulan_dari option").prop('disabled', false).show();

            if (semester == 1) {
                $("#bulan_sampai").val(6);
                $("#bulan_dari option").each(function() {
                    var val = parseInt($(this).val());
                    if (val > 6) {
                        $(this).prop('disabled', true).hide();
                    }
                });
            } else if (semester == 2) {
                $("#bulan_sampai").val(12);
                $("#bulan_dari option").each(function() {
                    var val = parseInt($(this).val());
                    if (val <= 6 && !isNaN(val)) {
                        $(this).prop('disabled', true).hide();
                    }
                });
            }

            if (tahun == currentYear) {
                $("#bulan_dari option").each(function() {
                    var val = parseInt($(this).val());
                    if (val < currentMonth && !isNaN(val)) {
                        $(this).prop('disabled', true).hide();
                    }
                });
            }
        }

        $("#pilih_semester, #tahun_dari").change(function() {
            checkBulan();
        });

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
            let bulan_dari = form.find('select[name="bulan_dari"]').val();
            let tahun_dari = form.find('select[name="tahun_dari"]').val();
            let bulan_sampai = form.find('select[name="bulan_sampai"]').val();
            let tahun_sampai = form.find('select[name="tahun_sampai"]').val();
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
            } else if (bulan_dari == '' || tahun_dari == '' || bulan_sampai == '' || tahun_sampai == '') {
                Swal.fire({
                    title: "Oops!",
                    text: "Periode harus diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        form.find("#bulan_dari").focus();
                    }
                });
                return false;
            } else {
                buttonDisable();
            }
        });
    });
</script>
