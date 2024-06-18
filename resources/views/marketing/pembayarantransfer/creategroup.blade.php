<form action="{{ route('pembayarantransfer.storegroup') }}" method="POST" id="formCreategroupgiro">
    @csrf
    <x-input-with-icon label="Auto" icon="ti ti-barcode" disabled="true" name="kode_transfer" />
    <x-input-with-icon label="Tanggal" icon="ti ti-calendar" datepicker="flatpickr-date" name="tanggal" />
    <div class="input-group mb-3">
        <input type="text" class="form-control" name="kode_pelanggan" id="kode_pelanggan" readonly placeholder="Kode Pelanggan"
            aria-label="Kode Pelanggan" aria-describedby="kode_pelanggan_search">
        <a class="btn btn-primary waves-effect" id="kode_pelanggan_search"><i class="ti ti-search text-white"></i></a>
    </div>
    <div class="row">
        <div class="col">
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

            </table>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="form-group mb-3">
                <select name="kode_salesman" id="kode_salesman" class="form-select select2Kodesalesman">
                    <option value="">Salesman Penagih</option>
                </select>
            </div>
            <x-input-with-icon icon="ti ti-building" label="Bank Pengirim" name="bank_pengirim" />
            <x-input-with-icon label="Keterangan" name="keterangan" icon="ti ti-file-description" />
        </div>
    </div>
    <div class="divider text-start">
        <div class="divider-text">
            <i class="ti ti-sun me-2"></i> Detail Faktur
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 col-sm-12 col-md-12">
            <div class="form-group mb-3">
                <select name="no_faktur" id="no_faktur" class="form-select select2Nofaktur">
                    <option value="">Pilih Faktur</option>
                </select>
                <input type="hidden" id="sisa_piutang">
            </div>
        </div>
        <div class="col-lg-4 col-sm-12 col-md-12">
            <x-input-with-icon label="Jumlah" name="jumlah" icon="ti ti-moneybag" align="right" money="true" />
        </div>
        <div class="col-lg-2 col-sm-12 col-md-12">
            <div class="form-group mb-3">
                <button class="btn btn-primary w-100" id="tambahfaktur"><i class="ti ti-plus"></i></button>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <table class="table table-bordered" id="tabelfaktur">
                <thead class="table-dark">
                    <tr>
                        <th>No. Faktur</th>
                        <th>Jumlah</th>
                        <th>#</th>
                    </tr>
                </thead>
                <tbody id="loaddetailfaktur"></tbody>
            </table>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col-12">
            <div class="form-check mt-3 mb-3">
                <input class="form-check-input agreement" name="aggrement" value="aggrement" type="checkbox" value="" id="defaultCheck3">
                <label class="form-check-label" for="defaultCheck3"> Yakin Akan Disimpan ? </label>
            </div>
            <div class="form-group" id="saveButton">
                <button class="btn btn-primary w-100" type="submit" id="btnSimpan">
                    <ion-icon name="send-outline" class="me-1"></ion-icon>
                    Submit
                </button>
            </div>
        </div>
    </div>
</form>
<script>
    $(function() {
        const form = $("#formCreategroupgiro");
        $(".flatpickr-date").flatpickr();
        $(".money").maskMoney();
        form.find("#saveButton").hide();

        form.find('.agreement').change(function() {
            if (this.checked) {
                form.find("#saveButton").show();
            } else {
                form.find("#saveButton").hide();
            }
        });

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

        const select2Kodesalesman = $('.select2Kodesalesman');
        if (select2Kodesalesman.length) {
            select2Kodesalesman.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Salesman Penagih',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
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
                        getlistFakturkredit(response.data.kode_pelanggan);
                        getsalesmanbyCabang(response.data.kode_cabang);
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

        function getlistFakturkredit(kode_pelanggan) {
            $.ajax({
                type: 'GET',
                url: `/pelanggan/${kode_pelanggan}/getlistfakturkreditoption`,
                cache: false,
                success: function(respond) {
                    $("#no_faktur").html(respond);
                }
            });
        }

        function getsalesmanbyCabang(kode_cabang) {
            $.ajax({
                type: 'POST',
                url: '/salesman/getsalesmanbycabang',
                data: {
                    _token: "{{ csrf_token() }}",
                    kode_cabang: kode_cabang,
                },
                cache: false,
                success: function(respond) {
                    console.log(respond);
                    form.find("#kode_salesman").html(respond);
                }
            });
        }

        function getpiutangFaktur() {
            const no_faktur = $("#no_faktur").val();
            $.ajax({
                type: 'GET',
                url: `/penjualan/${no_faktur}/getpiutangfaktur`,
                cache: false,
                success: function(respond) {
                    $("#sisa_piutang").val(respond.data.sisa_piutang);
                }
            });
        }

        form.find("#no_faktur").change(function() {
            const no_faktur = $(this).val();
            getpiutangFaktur(no_faktur);
        });

        function addProduk() {

            const no_faktur = form.find("#no_faktur").val();
            const jumlah = form.find("#jumlah").val();

            let faktur = `
            <tr id="index_${no_faktur}">
                <td>
                    <input type="hidden" name="no_faktur[]" value="${no_faktur}" class="no_faktur"/>
                    ${no_faktur}
                </td>
                <td class="text-end">
                    <input type="hidden" class="noborder-form text-end money" name="jml[]" value="${jumlah}"/>
                    ${jumlah}
                </td>
                <td class="text-center">
                    <div class="d-flex">
                        <div>
                            <a href="#" key="${no_faktur}" class="delete"><i class="ti ti-trash text-danger"></i></a>
                        </div>
                    </div>
                </td>
            </tr>`;
            $('#loaddetailfaktur').append(faktur);
            form.find("#no_faktur").val("");
            form.find("#jumlah").val("");
        }
        $("#tambahfaktur").click(function(e) {
            e.preventDefault();
            const no_faktur = form.find("#no_faktur").val();
            const jml = form.find("#jumlah").val();
            let jumlah = parseInt(jml.replace(/\./g, ''));
            if (isNaN(jumlah)) {
                jumlah = 0;
            }
            const sisa_piutang = form.find("#sisa_piutang").val();
            if (no_faktur === '') {
                Swal.fire({
                    title: "Oops!",
                    text: 'No. Faktur Harus Diisi',
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#no_faktur").focus();
                    },
                });

            } else if (jumlah === '' || jumlah === 0) {
                Swal.fire({
                    title: "Oops!",
                    text: 'Jumlah Tidak Boleh Kosong',
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#jumlah").focus();
                    },
                });

            } else if (parseInt(jumlah) > parseInt(sisa_piutang)) {
                Swal.fire({
                    title: "Oops!",
                    text: 'Jumlah Tidak Boleh Melebihi Sisa Piutang, Sisa Piutang Adalah : ' + convertToRupiah(sisa_piutang),
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#jumlah").focus();
                    },
                });
            } else if ($('#tabelfaktur').find('#index_' + no_faktur).length > 0) {
                Swal.fire({
                    title: "Oops!",
                    text: "Data Sudah Ada !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#no_faktur").focus();
                    },
                });
            } else {
                addProduk();
                $(".money").maskMoney();
            }
        });


        $(document).on('click', '.delete', function(e) {
            e.preventDefault();
            let key = $(this).attr("key");
            event.preventDefault();
            Swal.fire({
                title: `Apakah Anda Yakin Ingin Menghapus Data Ini ?`,
                text: "Jika dihapus maka data akan hilang permanent.",
                icon: "warning",
                buttons: true,
                dangerMode: true,
                showCancelButton: true,
                confirmButtonColor: "#554bbb",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, Hapus Saja!"
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    $(`#index_${key}`).remove();
                }
            });
        });

        function buttonDisable() {
            $("#btnSimpan").prop('disabled', true);
            $("#btnSimpan").html(`
            <div class="spinner-border spinner-border-sm text-white me-2" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            Loading..
         `);
        }

        function buttonEnable() {
            $("#btnSimpan").prop('disabled', false);
            $("#btnSimpan").html(`<i class="ti ti-send me-1"></i>Submit`);
        }
        form.submit(function() {

            const tanggal = form.find("#tanggal").val();
            const kode_pelanggan = form.find("#kode_pelanggan").val();
            const kode_salesman = form.find("#kode_salesman").val();
            const bank_pengirim = form.find("#bank_pengirim").val();
            if (tanggal == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Tanggal Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $(this).find("#tanggal").focus();
                    },
                });
                return false;
            } else if (kode_pelanggan == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Pelanggan Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $(this).find("#kode_pelanggan").focus();
                    },
                });
                return false;
            } else if (kode_salesman == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Salesman Penagih Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $(this).find("#kode_salesman").focus();
                    },
                });
                return false;
            } else if (bank_pengirim == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Bank Pengirim Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $(this).find("#bank_pengirim").focus();
                    },
                });
                return false;
            } else if ($('#loaddetailfaktur tr').length == 0) {
                Swal.fire({
                    title: "Oops!",
                    text: "Detail Faktur Masih Kosong !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $(this).find("#no_faktur").focus();
                    },
                });
                return false;
            } else {
                buttonDisable();
            }
        });
    });
</script>
