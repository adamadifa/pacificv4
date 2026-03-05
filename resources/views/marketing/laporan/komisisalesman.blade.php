@php
    use Jenssegers\Agent\Agent;
    $agent = new Agent();
    $isMobile = $agent->isMobile();
@endphp

<form action="{{ route('laporanmarketing.cetakkomisisalesman') }}" method="POST" id="formKomisisalesman" target="{{ $isMobile ? '_self' : '_blank' }}">
    @csrf
    @hasanyrole($roles_show_cabang)
        <div class="form-group mb-3">
            <x-select label="Pilih Cabang" name="kode_cabang" id="kode_cabang_komisisalesman" :data="$cabang"
                key="kode_cabang" textShow="nama_cabang" select2="select2Kodecabangkomisisalesman" upperCase="true"
                hideLabel="true" />
        </div>
    @endrole
    <div class="row">
        <div class="col">
            @php
                $bulan_data = collect($list_bulan)->map(function ($item) {
                    return (object) $item;
                });
            @endphp
            <x-select label="Bulan" name="bulan" id="bulan" :data="$bulan_data" key="kode_bulan"
                textShow="nama_bulan" hideLabel="true" />
        </div>
    </div>
    <div class="row">
        <div class="col">
            @php
                $tahun_data = [];
                for ($t = $start_year; $t <= date('Y'); $t++) {
                    $tahun_data[] = (object) ['tahun' => $t];
                }
            @endphp
            <x-select label="Tahun" name="tahun" id="tahun" :data="$tahun_data" key="tahun" textShow="tahun"
                hideLabel="true" />
        </div>
    </div>



    <div class="row">
        <div class="col-lg-10 col-md-12 col-sm-12">
            <button type="submit" name="submitButton" class="btn btn-primary w-100" id="submitButtonlhp">
                <i class="ti ti-printer me-1"></i> Cetak
            </button>
        </div>
        <div class="col-lg-2 col-md-12 col-sm-12">
            <button type="submit" name="exportButton" class="btn btn-success w-100" id="exportButtonlhp">
                <i class="ti ti-download"></i>
            </button>
        </div>
    </div>
</form>
@push('myscript')
    <script>
        $(document).ready(function() {
            const formKomisisalesman = $("#formKomisisalesman");
            const select2Kodecabangkomisisalesman = $(".select2Kodecabangkomisisalesman");
            if (select2Kodecabangkomisisalesman.length) {
                select2Kodecabangkomisisalesman.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Pilih Cabang',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }



            formKomisisalesman.submit(function(e) {
                const kode_cabang = formKomisisalesman.find('#kode_cabang_komisisalesman').val();
                const bulan = formKomisisalesman.find('#bulan').val();
                const tahun = formKomisisalesman.find('#tahun').val();
                if (kode_cabang == "") {
                    Swal.fire({
                        title: "Oops!",
                        text: "Cabang Harus Diisi !",
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: (e) => {
                            $(this).find("#kode_cabang_komisisalesman").focus();
                        },
                    });
                    return false;
                } else if (bulan == "") {
                    Swal.fire({
                        title: "Oops!",
                        text: "Bulan Harus Diisi !",
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: (e) => {
                            $(this).find("#bulan").focus();
                        },
                    });
                    return false;
                } else if (tahun == "") {
                    Swal.fire({
                        title: "Oops!",
                        text: "Tahun Harus Diisi !",
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: (e) => {
                            $(this).find("#tahun").focus();
                        },
                    })
                    return false;
                }
            });
        });
    </script>
@endpush
