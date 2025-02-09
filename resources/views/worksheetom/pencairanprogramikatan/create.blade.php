<form action="{{ route('pencairanprogramikatan.store') }}" method="POST" id="formPencairanProgramikatan">
    @csrf
    <x-input-with-icon label="Tanggal" name="tanggal" icon="ti ti-calendar" datepicker="flatpickr-date" value="{{ date('Y-m-d') }}" readonly />
    {{-- <div class="input-group mb-2">
        <input type="text" class="form-control" name="no_pengajuan" id="no_pengajuan" readonly placeholder="Cari Ajuan Program"
            aria-label="Cari Ajuan Program" aria-describedby="no_pengajuan">
        <a class="btn btn-primary waves-effect" id="no_pengajuan_search"><i class="ti ti-search text-white"></i></a>
    </div>
    <table class="table table-striped mb-2" id="tabeldataajuan">
        <tr>
            <th>No. Pengajuan</th>
            <td id="no_pengajuan_text"></td>
        </tr>
        <tr>
            <th>No. Dokumen</th>
            <td id="nomor_dokumen"></td>
        </tr>
        <tr>
            <th>Program</th>
            <td id="nama_program"></td>
        </tr>
        <tr>
            <th>Cabang</th>
            <td id="nama_cabang"></td>
        </tr>
        <tr>
            <th>Periode Kontrak</th>
            <td id="periode"></td>
        </tr>
    </table> --}}
    {{-- <div class="row">
        <div class="col">
            <div class="form-group mb-3">
                <select name="periodepencairan" id="periodepencairan" class="form-select">
                    <option value="">Periode</option>
                </select>
            </div>
        </div>
    </div> --}}
    <div class="form-group mb-3">
        <select name="kode_program" id="kode_program" class="form-select">
            <option value="">Pilih Program</option>
            @foreach ($programikatan as $d)
                <option value="{{ $d->kode_program }}">{{ $d->nama_program }}</option>
            @endforeach
        </select>
    </div>
    @hasanyrole($roles_show_cabang)
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <x-select label="Pilih Cabang" name="kode_cabang" :data="$cabang" key="kode_cabang" textShow="nama_cabang" upperCase="true"
                    select2="select2Kodecabangsearch" />
            </div>
        </div>
    @endrole
    <div class="row">
        <div class="col-lg-8 col-md-12 col-sm-12">
            <div class="form-group">
                <select name="bulan" id="bulan" class="form-select">
                    <option value="">Bulan</option>
                    @foreach ($list_bulan as $d)
                        <option value="{{ $d['kode_bulan'] }}">{{ $d['nama_bulan'] }}</option>
                    @endforeach
                </select>
            </div>

        </div>
        <div class="col-lg-4 col-md-12 col-sm-12">
            <div class="form-group">
                <select name="tahun" id="tahun" class="form-select">
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
        $("#formPencairanProgramikatan").submit(function(e) {
            let tanggal = $(this).find('input[name="tanggal"]').val();
            let keterangan = $(this).find('textarea[name="keterangan"]').val();
            let bulan = $(this).find('select[name="bulan"]').val();
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
            } else if (bulan == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Bulan harus diisi !",
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
            }
        });
    });
</script>
