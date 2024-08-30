<form action="{{ route('klaimkaskecil.store') }}" id="formCreateKlaimKasKecil" method="POST">
    @csrf
    <div class="row">
        <div class="col-lg-6 col-sm-12 col-md-12">
            <x-input-with-icon label="Dari" name="dari" icon="ti ti-calendar" datepicker="flatpickr-date" />
        </div>
        <div class="col-lg-6 col-sm-12 col-md-12">
            <x-input-with-icon label="Sampai" name="sampai" icon="ti ti-calendar" datepicker="flatpickr-date" />
        </div>
    </div>
    @hasanyrole($roles_show_cabang)
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="form-group mb-3">
                    <select name="kode_cabang" id="kode_cabang_klaim" class="form-select select2Kodecabangklaim">
                        <option value="">Pilih Cabang</option>
                        @foreach ($cabang as $d)
                            <option value="{{ $d->kode_cabang }}">{{ textUpperCase($d->nama_cabang) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    @endrole
    <div class="row">
        <div class="col">
            <div class="form-group mb-3">
                <button class="btn btn-success w-100" id="btngetData"><i class="ti ti-search me-2"></i>Get
                    Data</button>
            </div>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col">
            <x-input-with-icon label="Tanggal" name="tanggal" icon="ti ti-calendar" datepicker="flatpickr-date" />
        </div>
    </div>
    <div class="row">
        <div class="col">
            <x-input-with-icon label="Keterangan" name="keterangan" icon="ti ti-file-text" />
        </div>
    </div>
    <div class="row">
        <div class="col-12" id="loaddata">
            <div class="alert alert-info">
                <p>Silahkan Get Data Terlebih Dahulu</p>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col">
            <div class="form-group mb-3">
                <button class="btn btn-primary w-100" id="btnSimpan"><i class="ti ti-plus me-1"></i>Buat Klaim</button>
            </div>
        </div>
    </div>
</form>
<script>
    $(function() {
        const formCreateKlaimKasKecil = $("#formCreateKlaimKasKecil");

        $(".flatpickr-date").flatpickr();

        function buttonDisable() {
            $("#btnSimpan").prop('disabled', true);
            $("#btnSimpan").html(`
            <div class="spinner-border spinner-border-sm text-white me-2" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            Loading..`);
        }
        const select2Kodecabangklaim = $('.select2Kodecabangklaim');
        if (select2Kodecabangklaim.length) {
            select2Kodecabangklaim.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Pilih Cabang',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

        $("#btngetData").on("click", function(e) {
            e.preventDefault();
            const dari = formCreateKlaimKasKecil.find("#dari").val();
            const sampai = formCreateKlaimKasKecil.find("#sampai").val();
            const kode_cabang = formCreateKlaimKasKecil.find("#kode_cabang_klaim").val();

            if (dari == "" || sampai == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Periode Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        formCreateKlaimKasKecil.find("#dari").focus();
                    },

                });
                return false;
            } else if (kode_cabang == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Cabang Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        formCreateKlaimKasKecil.find("#kode_cabang_klaim").focus();
                    },
                });
                return false;
            } else {
                $("#loaddata").html(
                    `<div class="sk-wave sk-primary" style="margin:auto">
                    <div class="sk-wave-rect"></div>
                    <div class="sk-wave-rect"></div>
                    <div class="sk-wave-rect"></div>
                    <div class="sk-wave-rect"></div>
                    <div class="sk-wave-rect"></div>
                    </div>`
                );
                $.ajax({
                    type: 'POST',
                    url: '/klaimkaskecil/getdata',
                    data: {
                        _token: "{{ csrf_token() }}",
                        dari: dari,
                        sampai: sampai,
                        kode_cabang: kode_cabang
                    },
                    cache: false,
                    success: function(data) {
                        $("#loaddata").html(data);
                    }
                });
            }
        });

        formCreateKlaimKasKecil.submit(function(e) {
            const tanggal = formCreateKlaimKasKecil.find("#tanggal").val();
            const keterangan = formCreateKlaimKasKecil.find("#keterangan").val();
            if (tanggal == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Tanggal Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        formCreateKlaimKasKecil.find("#tanggal").focus();
                    },
                });
                return false;
            } else if (keterangan == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Keterangan Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        formCreateKlaimKasKecil.find("#keterangan").focus();
                    },

                });
                return false;
            } else {
                buttonDisable();
            }
        })
    });
</script>
