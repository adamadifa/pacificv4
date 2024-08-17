<form action="{{ route('laporanmarketing.cetakrekapwilayah') }}" id="formRekapwilayah" target="_blank" method="POST">
    @csrf
    @hasanyrole($roles_show_cabang)
        <div class="form-group mb-3">
            <select name="kode_cabang" id="kode_cabang_rekapwilayah" class="form-select select2Kodecabangrekapwilayah">
                <option value="">Cabang</option>
                @foreach ($cabang as $d)
                    <option value="{{ $d->kode_cabang }}">{{ textUpperCase($d->nama_cabang) }}</option>
                @endforeach
            </select>
        </div>
    @endrole
    <div class="row">
        <div class="col">
            <div class="form-group mb-3">
                <select name="tahun" id="tahun" class="form-select">
                    <option value="">Tahun</option>
                    @for ($t = $start_year; $t <= date('Y'); $t++)
                        <option value="{{ $t }}">{{ $t }}</option>
                    @endfor
                </select>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-10 col-md-12 col-sm-12">
            <button type="submit" class="btn btn-primary w-100">
                <i class="ti ti-printer me-1"></i> Cetak
            </button>
        </div>
        <div class="col-lg-2 col-md-12 col-sm-12">
            <button type="submit" name="exportButton" class="btn btn-success w-100">
                <i class="ti ti-download"></i>
            </button>
        </div>
    </div>
</form>

@push('myscript')
    <script>
        $(function() {
            const formRekapwilayah = $("#formRekapwilayah");
            const select2Kodecabangrekapwilayah = $(".select2Kodecabangrekapwilayah");
            if (select2Kodecabangrekapwilayah.length) {
                select2Kodecabangrekapwilayah.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Pilih Cabang',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }

            formRekapwilayah.submit(function(e) {
                const kode_cabang = formRekapwilayah.find("#kode_cabang_rekapwilayah").val();
                const tahun = formRekapwilayah.find("#tahun").val();
                if (kode_cabang == "") {
                    Swal.fire({
                        title: "Oops!",
                        text: 'Cabang Harus Diisi !',
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: (e) => {
                            formRekapwilayah.find("#kode_cabang_rekapwilayah").focus();
                        },
                    });
                    return false;
                } else if (tahun == "") {
                    Swal.fire({
                        title: "Oops!",
                        text: 'Tahun Harus Diisi !',
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: (e) => {
                            formRekapwilayah.find("#tahun").focus();
                        },
                    });
                    return false;
                }
            });
        });
    </script>
@endpush
