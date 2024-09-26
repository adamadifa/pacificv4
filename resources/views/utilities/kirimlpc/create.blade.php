<form action="{{ route('kirimlpc.store') }}" method="POST" id="formKirimlpc" enctype="multipart/form-data">
    <input type="hidden" id="cektutuplaporan">
    @csrf
    @hasanyrole($roles_show_cabang)
        <div class="form-group mb-4">
            <select name="kode_cabang" id="kode_cabang" class="form-select select2Kodecabang">
                <option value="">Pilih Cabang</option>
                @foreach ($cabang as $d)
                    <option value="{{ $d->kode_cabang }}">{{ textuppercase($d->nama_cabang) }}</option>
                @endforeach
            </select>
        </div>
    @endhasanyrole
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
    <x-input-with-icon label="Tanggal Kirim lpc" name="tanggal" icon="ti ti-calendar" datepicker="flatpickr-date" />
    <x-input-with-icon label="Jam Kirim (00:00)" name="jam_kirim" icon="ti ti-clock" />
    <x-input-file name="foto" label="Foto" />
    <div class="form-group mb-3">
        <button class="btn btn-primary w-100" id="btnSimpan"><i class="ti ti-send me-1"></i>Kirim lpc</button>
    </div>
</form>
<script>
    $(document).ready(function() {
        const formKirimlpc = $("#formKirimlpc");
        const select2Kodecabang = $('.select2Kodecabang');
        $("#jam_kirim").mask("99:99");
        $(".flatpickr-date").flatpickr();
        if (select2Kodecabang.length) {
            select2Kodecabang.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Pilih Cabang',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

        function buttonDisable() {
            $("#btnSimpan").prop('disabled', true);
            $("#btnSimpan").html(`
            <div class="spinner-border spinner-border-sm text-white me-2" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            Loading..`);
        }

        formKirimlpc.submit(function(e) {
            const kode_cabang = formKirimlpc.find("#kode_cabang").val();
            const bulan = formKirimlpc.find("#bulan").val();
            const tahun = formKirimlpc.find("#tahun").val();
            const tanggal = formKirimlpc.find("#tanggal").val();
            const jam_kirim = formKirimlpc.find("#jam_kirim").val();
            if (kode_cabang == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Cabang Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        formKirimlpc.find("#kode_cabang").focus();
                    },
                });
                return false;
            } else if (bulan == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Bulan Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        formKirimlpc.find("#bulan").focus();
                    },
                });
                return false;
            } else if (tahun == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Tahun Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        formKirimlpc.find("#tahun").focus();
                    },
                });
                return false;
            } else if (tanggal == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Tanggal Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        formKirimlpc.find("#tanggal").focus();
                    },
                });
                return false;
            } else if (jam_kirim == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Jam Kirim Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        formKirimlpc.find("#jam_kirim").focus();
                    },
                });
                return false;
            } else {
                buttonDisable();
            }
        });
    });
</script>
