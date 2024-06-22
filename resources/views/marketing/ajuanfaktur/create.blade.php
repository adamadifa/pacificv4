<form action="{{ route('ajuanfaktur.store') }}" aria-autocomplete="false" id="formAjuanfaktur" method="POST" enctype="multipart/form-data">
    @csrf
    <x-input-with-icon icon="ti ti-barcode" label="Auto" disabled="true" name="no_pengajuan" />
    <x-input-with-icon icon="ti ti-calendar" label="Tanggal Pengajuan" name="tanggal" datepicker="flatpickr-date" />
    <div class="divider">
        <div class="divider-text">Data Pelanggan</div>
    </div>
    <div class="input-group mb-3">
        <input type="text" class="form-control" name="kode_pelanggan" id="kode_pelanggan" readonly placeholder="Kode Pelanggan"
            aria-label="Cari Pelanggan" aria-describedby="kode_pelanggan_search">
        <a class="btn btn-primary waves-effect" id="kode_pelanggan_search"><i class="ti ti-search text-white"></i></a>
    </div>
    <table class="table mb-3">
        <tr>
            <th style="width:40%">Nama Pelanggan</th>
            <td id="nama_pelanggan"></td>
        </tr>
        <tr>
            <th>Alamat Pelanggan</th>
            <td id="alamat_pelanggan"></td>
        </tr>
        <tr>
            <th>No. HP</th>
            <td id="no_hp_pelanggan"></td>
        </tr>
        <tr>
            <th>Salesman</th>
            <td id="nama_salesman"></td>
        </tr>
        <tr>
            <th>Limit Pelanggan</th>
            <td id="limit_pelanggan" class="text-end"></td>
        </tr>
    </table>
    <x-input-with-icon icon="ti ti-file-copy" label="Jumlah Faktur" name="jumlah_faktur" align="right" money="true" />
    <x-textarea label="Keterangan" name="keterangan" />
    <div class="row mt-2">
        <div class="col-12">
            <div class="form-check mt-3 mb-2">
                <input class="form-check-input cod" name="cod" value="1" type="checkbox" id="cod">
                <label class="form-check-label" for="cod">Pembyayaran Saat Turun Barang Selanjutnya </label>
            </div>
        </div>
    </div>
    <div class="form-group">
        <button class="btn btn-primary w-100" type="submit">
            <ion-icon name="send-outline" class="me-1"></ion-icon>
            Submit
        </button>
    </div>
</form>
<script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
<script src="{{ asset('/assets/vendor/libs/@form-validation/umd/bundle/popular.min.js') }}"></script>
<script src="{{ asset('/assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js') }}"></script>
<script src="{{ asset('/assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js') }}"></script>
<script src="{{ asset('assets/js/pages/ajuanfaktur.js') }}"></script>
<script>
    $(function() {
        const form = $("#formAjuanfaktur");
        $(".flatpickr-date").flatpickr();
        $(".money").maskMoney();

        function convertToRupiah(number) {
            if (number) {
                var rupiah = "";
                var numberrev = number
                    .toString()
                    .split("")
                    .reverse()
                    .join("");
                for (var i = 0; i < numberrev.length; i++)
                    if (i % 3 == 0) rupiah += numberrev.substr(i, 3) + ".";
                return (
                    rupiah
                    .split("", rupiah.length - 1)
                    .reverse()
                    .join("")
                );
            } else {
                return number;
            }
        }

        function getPelanggan(kode_pelanggan) {
            $.ajax({
                url: `/pelanggan/${kode_pelanggan}/getPelanggan`,
                type: "GET",
                cache: false,
                success: function(response) {
                    //fill data to form
                    const status_aktif_pelanggan = response.data.status_aktif_pelanggan;
                    if (status_aktif_pelanggan === '0') {
                        Swal.fire({
                            title: "Oops!",
                            text: "Pelanggan Tidak Dapat Bertransaksi, Silahkan Hubungi Admin Untuk Mengaktifkan Pelanggan !",
                            icon: "warning",
                            showConfirmButton: true,
                        });
                    } else {
                        form.find("#kode_pelanggan").val(response.data.kode_pelanggan);
                        form.find("#nama_pelanggan").text(response.data.nama_pelanggan);
                        form.find("#alamat_pelanggan").text(response.data.alamat_pelanggan);
                        form.find("#no_hp_pelanggan").text(response.data.no_hp_pelanggan);
                        form.find("#nama_salesman").text(response.data.nama_salesman);
                        form.find("#limit_pelanggan").text(convertToRupiah(response.data.limit_pelanggan));
                        $('#modalPelanggan').modal('hide');
                    }

                }
            });
        }
        $('#tabelpelanggan tbody').on('click', '.pilihpelanggan', function(e) {
            e.preventDefault();
            let kode_pelanggan = $(this).attr('kode_pelanggan');
            getPelanggan(kode_pelanggan);
        });
    });
</script>
