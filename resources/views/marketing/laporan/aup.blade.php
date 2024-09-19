<form action="{{ route('laporanmarketing.cetakaup') }}" method="POST" target="_blank" id="formAup">
    @csrf
    @hasanyrole($roles_show_cabang)
        <div class="form-group mb-3">
            <select name="kode_cabang" id="kode_cabang_aup" class="form-select select2Kodecabangaup">
                <option value="">Semua Cabang</option>
                @foreach ($cabang as $d)
                    <option value="{{ $d->kode_cabang }}">{{ textUpperCase($d->nama_cabang) }}</option>
                @endforeach
            </select>
        </div>
    @endrole
    <div class="form-group mb-3">
        <select name="kode_salesman" id="kode_salesman_aup" class="select2Kodesalesmanaup form-select">
        </select>
    </div>
    <div class="form-group mb-3">
        <select name="kode_pelanggan" id="kode_pelanggan_aup" class="select2Kodepelangganaup form-select">
        </select>
    </div>

    <x-input-with-icon icon="ti ti-calendar" label="Lihat Per tanggal" name="tanggal" datepicker="flatpickr-date" />
    <div class="row">
        <div class="col-lg-10 col-md-12 col-sm-12">
            <button type="submit" name="submitButton" class="btn btn-primary w-100" id="submitButton">
                <i class="ti ti-printer me-1"></i> Cetak
            </button>
        </div>
        <div class="col-lg-2 col-md-12 col-sm-12">
            <button type="submit" name="exportButton" class="btn btn-success w-100" id="exportButton">
                <i class="ti ti-download"></i>
            </button>
        </div>
    </div>
</form>
@push('myscript')
    <script>
        $(document).ready(function() {
            const formAup = $("#formAup");
            const select2Kodecabangaup = $(".select2Kodecabangaup");
            if (select2Kodecabangaup.length) {
                select2Kodecabangaup.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Semua Cabang',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }

            const select2Kodesalesmanaup = $(".select2Kodesalesmanaup");
            if (select2Kodesalesmanaup.length) {
                select2Kodesalesmanaup.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Semua Salesman',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }

            const select2Kodepelangganaup = $(".select2Kodepelangganaup");
            if (select2Kodepelangganaup.length) {
                select2Kodepelangganaup.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Semua Pelanggan',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }

            function getsalesmanbyCabang() {
                var kode_cabang = formAup.find("#kode_cabang_aup").val();
                //alert(selected);
                $.ajax({
                    type: 'POST',
                    url: '/salesman/getsalesmanbycabang',
                    data: {
                        _token: "{{ csrf_token() }}",
                        kode_cabang: kode_cabang
                    },
                    cache: false,
                    success: function(respond) {
                        console.log(respond);
                        formAup.find("#kode_salesman_aup").html(respond);
                    }
                });
            }

            function getpelangganbySalesman() {
                var kode_salesman = formAup.find("#kode_salesman_aup").val();
                var kode_cabang = formAup.find("#kode_cabang_aup").val();
                //alert(selected);
                $.ajax({
                    type: 'POST',
                    url: '/pelanggan/getpelangganbysalesman',
                    data: {
                        _token: "{{ csrf_token() }}",
                        kode_salesman: kode_salesman,
                        kode_cabang: kode_cabang
                    },
                    cache: false,
                    success: function(respond) {
                        console.log(respond);
                        formAup.find("#kode_pelanggan_aup").html(respond);
                    }
                });
            }
            getpelangganbySalesman();
            getsalesmanbyCabang();
            formAup.find("#kode_cabang_aup").change(function(e) {
                getsalesmanbyCabang();

                getpelangganbySalesman();
            });

            formAup.find("#kode_salesman_aup").change(function(e) {
                getpelangganbySalesman();
            });




            formAup.submit(function(e) {
                const tanggal = formAup.find("#tanggal").val();

                if (tanggal == "") {
                    Swal.fire({
                        title: "Oops!",
                        text: " Tanggal Harus Diisi !",
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: (e) => {
                            $(this).find("#dari").focus();
                        },
                    });
                    return false;
                }
            })
        });
    </script>
@endpush
