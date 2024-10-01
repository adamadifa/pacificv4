<form action="{{ route('tutuplaporan.store') }}" id="formTutupLaporan" method="POST">
    @csrf
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
    <div class="row">
        <div class="form-group mb-3">
            <select name="jenis_laporan" id="jenis_laporan" class="form-select">
                <option value="">Pilih Laporan</option>
                <option value="penjualan">Penjualan</option>
                <option value="pembelian">Pembelian</option>
                <option value="kaskecil">Kas Kecil</option>
                <option value="ledger">Ledger</option>
                <option value="gudangcabang">Gudang Cabang</option>
                <option value="gudangpusat">Gudang Pusat</option>
                <option value="gudangbahan">Gudang Bahan</option>
                <option value="gudanglogistik">Gudang Logistik</option>
                <option value="costratio">Cost Ratio</option>
            </select>
        </div>
    </div>
    <x-input-with-icon label="Tanggal Tutup Laporan" name="tanggal" icon="ti ti-calendar" datepicker="flatpickr-date" />
    <div class="form-group mb-3">
        <button class="btn btn-primary w-100" id="btnSimpan"><i class="ti ti-send me-1"></i>Tutup Laporan</button>
    </div>
</form>
<script>
    $(document).ready(function() {
        const formTutuplaporan = $("#formTutuplaporan");
        $(".flatpickr-date").flatpickr();


        function buttonDisable() {
            $("#btnSimpan").prop('disabled', true);
            $("#btnSimpan").html(`
            <div class="spinner-border spinner-border-sm text-white me-2" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            Loading..`);
        }

        formTutuplaporan.submit(function(e) {
            const bulan = formKirimlhp.find("#bulan").val();
            const tahun = formKirimlhp.find("#tahun").val();
            const tanggal = formKirimlhp.find("#tanggal").val();
            const jenis_laporan = formKirimlhp.find("#jenis_laporan").val();
            if (bulan == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Bulan Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        formKirimlhp.find("#bulan").focus();
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
                        formKirimlhp.find("#tahun").focus();
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
                        formKirimlhp.find("#tanggal").focus();
                    },
                });
                return false;
            } else if (jenis_laporan == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Jenis Laporan Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        formKirimlhp.find("#jam_kirim").focus();
                    },
                });
                return false;
            } else {
                buttonDisable();
            }
        });
    });
</script>
