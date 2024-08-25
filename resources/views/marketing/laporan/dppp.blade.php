<form action="{{ route('laporanmarketing.cetakdppp') }}" method="POST" target="_blank" id="formDppp">
    @csrf
    @hasanyrole($roles_show_cabang)
        <div class="form-group mb-3">
            <select name="kode_cabang" id="kode_cabang_dppp" class="form-select select2Kodecabangdppp">
                <option value="">Pilih Cabang</option>
                @foreach ($cabang as $d)
                    <option value="{{ $d->kode_cabang }}">{{ textUpperCase($d->nama_cabang) }}</option>
                @endforeach
            </select>
        </div>
    @endrole

    <div class="row">
        <div class="col">
            <div class="form-group mb-3">
                <select name="bulan" id="bulan" class="form-select">
                    <option value="">Bulan</option>
                    @foreach ($list_bulan as $d)
                        <option value="{{ $d['kode_bulan'] }}">{{ $d['nama_bulan'] }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
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
    {{-- <div class="row">
        <div class="col">
            <div class="form-group mb-3">
                <select name="formatlaporan" id="formatlaporan" class="form-select">
                    <option value="">Format Laporan</option>
                    <option value="1">Berdasarkan Selling Out</option>
                    <option value="2">Berdasarkan Tunai Kredit</option>
                </select>
            </div>
        </div>
    </div> --}}
    <div class="row">
        <div class="col-lg-10 col-md-12 col-sm-12">
            <button type="submit" name="submitButton" class="btn btn-primary w-100" id="submitButtonDppp">
                <i class="ti ti-printer me-1"></i> Cetak
            </button>
        </div>
        <div class="col-lg-2 col-md-12 col-sm-12">
            <button type="submit" name="exportButton" class="btn btn-success w-100" id="exportButtonDppp">
                <i class="ti ti-download"></i>
            </button>
        </div>
    </div>
</form>
@push('myscript')
    <script>
        $(document).ready(function() {
            const formdppp = $("#formDppp");
            const select2Kodecabangdppp = $(".select2Kodecabangdppp");
            if (select2Kodecabangdppp.length) {
                select2Kodecabangdppp.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Semua Cabang',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }



            formdppp.submit(function(e) {

                const bulan = formdppp.find('#bulan').val();
                const tahun = formdppp.find('#tahun').val();
                if (bulan == "") {
                    Swal.fire({
                        title: "Oops!",
                        text: 'Bulan Harus Diisi !',
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: (e) => {
                            formdppp.find("#bulan").focus();
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
                            formdppp.find("#tahun").focus();
                        },
                    });
                    return false;
                }
            })
        });
    </script>
@endpush
