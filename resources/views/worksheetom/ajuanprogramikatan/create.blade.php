<form action="{{ route('ajuanprogramikatan.store') }}" method="POST" id="formAjuanprogram" enctype="multipart/form-data">
    @csrf
    {{-- <x-input-with-icon label="No. Dokumen" name="no_dokumen" icon="ti ti-barcode" /> --}}
    <input type="hidden" name="no_dokumen" id="no_dokumen" value="-">
    <x-input-with-icon label="Tanggal" name="tanggal" icon="ti ti-calendar" value="{{ date('Y-m-d') }}" readonly />
    @hasanyrole($roles_show_cabang)
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <x-select label="Pilih Cabang" name="kode_cabang" :data="$cabang" key="kode_cabang" textShow="nama_cabang" upperCase="true"
                    select2="select2Kodecabangsearch" />
            </div>
        </div>
    @endrole

    <div class="form-group mb-3">
        <select name="kode_program" id="kode_program" class="form-select">
            <option value="">Pilih Program</option>
            @foreach ($programikatan as $d)
                <option value="{{ $d->kode_program }}">{{ $d->nama_program }}</option>
            @endforeach
        </select>
    </div>
    <div class="row">
        <div class="col-lg-8 col-md-12 col-sm-12">
            <div class="form-group">
                <select name="bulan_dari" id="bulan_dari" class="form-select">
                    <option value="">Bulan</option>
                    @foreach ($list_bulan as $d)
                        <option value="{{ $d['kode_bulan'] }}">{{ $d['nama_bulan'] }}</option>
                    @endforeach
                </select>
            </div>

        </div>
        <div class="col-lg-4 col-md-12 col-sm-12">
            <div class="form-group">
                <select name="tahun_dari" id="tahun_dari" class="form-select">
                    <option value="">Tahun</option>
                    @for ($t = $start_year; $t <= date('Y'); $t++)
                        <option value="{{ $t }}">{{ $t }}</option>
                    @endfor
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-8 col-md-12 col-sm-12">
            <div class="form-group">
                <select name="bulan_sampai" id="bulan_sampai" class="form-select">
                    <option value="">Bulan</option>
                    @foreach ($list_bulan as $d)
                        <option value="{{ $d['kode_bulan'] }}">{{ $d['nama_bulan'] }}</option>
                    @endforeach
                </select>
            </div>

        </div>
        <div class="col-lg-4 col-md-12 col-sm-12">
            <div class="form-group">
                <select name="tahun_sampai" id="tahun_sampai" class="form-select">
                    <option value="">Tahun</option>
                    @for ($t = $start_year; $t <= date('Y'); $t++)
                        <option value="{{ $t }}">{{ $t }}</option>
                    @endfor
                </select>
            </div>
        </div>
    </div>
    <x-textarea label="Keterangan" name="keterangan" />

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
