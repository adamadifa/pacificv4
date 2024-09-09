<form action="{{ route('barangkeluargudangbahan.store') }}" method="post" id="formcreateBarangkeluargudangbahan">
    @csrf
    <x-input-with-icon icon="ti ti-barcode" label="Auto" name="no_bukti" readonly="true" />
    <x-input-with-icon icon="ti ti-calendar" label="Tanggal" name="tanggal" datepicker="flatpickr-date" />
    <div class="form-group mb-3">
        <select name="kode_jenis_pengeluaran" id="kode_jenis_pengeluaran" class="form-select">
            <option value="">Jenis Pengeluaran</option>
            @foreach ($list_jenis_pengeluaran as $d)
                <option value="{{ $d['kode_jenis_pengeluaran'] }}">{{ $d['jenis_pengeluaran'] }}</option>
            @endforeach
        </select>
    </div>
    <div class="row" id="cabang-section">
        <div class="col">
            <x-select label="Cabang" name="kode_cabang" :data="$cabang" key="kode_cabang" textShow="nama_cabang" select2="select2Kodecabang"
                upperCase="true" />
        </div>
    </div>
    <div class="row" id="unit-section">
        <div class="col">
            <div class="form-group mb-3">
                <select name="unit" id="unit" class="form-select">
                    <option value="">Unit</option>
                    <option value="1">Unit 1</option>
                    <option value="2">Unit 2</option>
                </select>
            </div>
        </div>

    </div>
    <div class="row" id="keterangan-section">
        <div class="col">
            <x-input-with-icon icon="ti ti-file-description" label="Keterangan" name="keterangan_barang_keluar" />
        </div>
    </div>


    <div class="divider text-start">
        <div class="divider-text">Detail Barang</div>
    </div>
    <div class="row">
        <div class="col-lg-6 col-md-12 col-sm-12">
            <x-select label="Pilih Barang" name="kode_barang" :data="$barang" key="kode_barang" textShow="nama_barang" upperCase="true"
                select2="select2Kodebarang" showKey="true" />
        </div>
        <div class="col-lg-2 col-md-12 col-sm-12">
            <x-input-with-icon icon="ti ti-box" label="Qty Unit" name="qty_unit" align="right" numberFormat="true" />
        </div>
        <div class="col-lg-2 col-md-12 col-sm-12">
            <x-input-with-icon icon="ti ti-box" label="Qty Berat" name="qty_berat" align="right" numberFormat="true" />
        </div>
        <div class="col-lg-2 col-md-12 col-sm-12">
            <x-input-with-icon icon="ti ti-box" label="Qty Lebih" name="qty_lebih" align="right" numberFormat="true" />
        </div>
    </div>
    <x-input-with-icon icon="ti ti-file-description" label="Keterangan" name="keterangan" />
    <a href="#" class="btn btn-primary w-100" id="tambahproduk"><i class="ti ti-plus me-1"></i>Tambah Produk</a>
    <div class="row mt-2">
        <div class="col">
            <table class="table table-bordered" id="tabledetail">
                <thead class="table-dark">
                    <tr>
                        <th style="width: 15%">Kode</th>
                        <th style="width: 30%">Nama Barang</th>
                        <th>Qty Unit</th>
                        <th>Qty Berat</th>
                        <th>Qty Lebih</th>
                        <th style="width: 20%">Keterangan</th>
                        <th>#</th>
                    </tr>
                </thead>
                <tbody id="loaddetail">
                </tbody>
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
        const formCreate = $("#formcreateBarangkeluargudangbahan");
        const select2Kodecabang = formCreate.find('.select2Kodecabang');
        if (select2Kodecabang.length) {
            select2Kodecabang.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Pilih Cabang',
                    dropdownParent: $this.parent(),
                    allowClear: true
                });
            });
        }

        $(".flatpickr-date").flatpickr({
            enable: [{
                from: "{{ $start_periode }}",
                to: "{{ $end_periode }}"
            }, ]
        });

        easyNumberSeparator({
            selector: '.number-separator',
            separator: '.',
            decimalSeparator: ',',
        });


        function loadketerangan() {
            const kode_jenis_pengeluaran = $("#kode_jenis_pengeluaran").val();
            if (kode_jenis_pengeluaran == "CBG" || kode_jenis_pengeluaran == "PRD") {
                formCreate.find("#keterangan-section").hide();
            } else {
                formCreate.find("#keterangan-section").show();
            }
        }

        function loadkodecabang() {
            const kode_jenis_pengeluaran = $("#kode_jenis_pengeluaran").val();
            if (kode_jenis_pengeluaran == "CBG") {
                formCreate.find("#cabang-section").show();
            } else {
                formCreate.find("#cabang-section").hide();
            }
        }

        function loadunit() {
            const kode_jenis_pengeluaran = $("#kode_jenis_pengeluaran").val();
            if (kode_jenis_pengeluaran == "PRD") {
                formCreate.find("#unit-section").show();
            } else {
                formCreate.find("#unit-section").hide();
            }
        }

        loadkodecabang();
        loadunit();
        loadketerangan();
        $("#kode_jenis_pengeluaran").change(function() {
            loadkodecabang();
            loadunit();
            loadketerangan();
        });

        function cektutuplaporan(tanggal, jenis_laporan) {
            $.ajax({

                type: "POST",
                url: "/tutuplaporan/cektutuplaporan",
                data: {
                    _token: "{{ csrf_token() }}",
                    tanggal: tanggal,
                    jenis_laporan: jenis_laporan
                },
                cache: false,
                success: function(respond) {
                    $("#cektutuplaporan").val(respond);
                }
            });
        }

        $("#tanggal").change(function(e) {
            cektutuplaporan($(this).val(), "gudangbahan");
        });

        const select2Kodebarang = formCreate.find('.select2Kodebarang');
        if (select2Kodebarang.length) {
            select2Kodebarang.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Pilih Produk',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

        function addProduk() {
            const dataBarang = formCreate.find("#kode_barang :selected").select2(this.data);
            const kode_barang = $(dataBarang).val();
            const nama_barang = $(dataBarang).text().split("|");
            const qty_unit = formCreate.find("#qty_unit").val();
            const qty_berat = formCreate.find("#qty_berat").val();
            const qty_lebih = formCreate.find("#qty_lebih").val();
            const keterangan = formCreate.find("#keterangan").val();
            const index = Math.floor(Math.random() * 10000);
            let produk = `
                    <tr id="index_${index}">
                        <td>
                            <input type="hidden" name="kode_barang[]" value="${kode_barang}"/>
                            ${kode_barang}
                        </td>
                        <td>${nama_barang[1]}</td>
                        <td class="text-end">
                            <input type="hidden" name="qty_unit[]" value="${qty_unit}" class="noborder-form text-end qty_unit" />
                            ${qty_unit}
                        </td>
                        <td class="text-end">
                            <input type="hidden" name="qty_berat[]" value="${qty_berat}" class="noborder-form text-end qty_berat" />
                            ${qty_berat}
                        </td>
                        <td class="text-end">
                            <input type="hidden" name="qty_lebih[]" value="${qty_lebih}" class="noborder-form text-end qty_lebih" />
                            ${qty_lebih}
                        </td>
                        <td>
                            <input type="hidden" name="ket[]" value="${keterangan}" class="noborder-form" />
                            ${keterangan}
                        </td>
                        <td class="text-center">
                            <a href="#" kode_barang="${index}" class="delete"><i class="ti ti-trash text-danger"></i></a>
                        </td>
                    </tr>
                `;

            //append to table
            $('#loaddetail').prepend(produk);
            $('.select2Kodebarang').val('').trigger("change");
            $("#qty_unit").val("");
            $("#qty_berat").val("");
            $("#qty_lebih").val("");
            $("#keterangan").val("");
            $("#kode_barang").focus();
        }

        formCreate.find("#tambahproduk").click(function(e) {
            e.preventDefault();
            const kode_barang = formCreate.find("#kode_barang").val();
            const qty_unit = formCreate.find("#qty_unit").val();
            const qty_berat = formCreate.find("#qty_berat").val();
            const qty_lebih = formCreate.find("#qty_lebih").val();
            if (kode_barang == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Silahkan Pilih dulu Barang !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        formCreate.find("#kode_barang").focus();
                    },

                });

            } else {
                formCreate.find("#tambahproduk").prop('disabled', true);
                addProduk();
                // if (formCreate.find('#tabledetail').find('#index_' + kode_barang).length > 0) {
                //     Swal.fire({
                //         title: "Oops!",
                //         text: "Data Sudah Ada!",
                //         icon: "warning",
                //         showConfirmButton: true,
                //         didClose: (e) => {
                //             formCreate.find("#kode_produk").focus();
                //         },

                //     });
                // } else {
                //     addProduk();
                // }
            }
        });

        formCreate.on('click', '.delete', function(e) {
            e.preventDefault();
            var kode_barang = $(this).attr("kode_barang");
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
                    $(`#index_${kode_barang}`).remove();
                }
            });
        });
        formCreate.find("#saveButton").hide();

        formCreate.find('.agreement').change(function() {
            if (this.checked) {
                formCreate.find("#saveButton").show();
            } else {
                formCreate.find("#saveButton").hide();
            }
        });

        formCreate.submit(function() {
            const no_bukti = formCreate.find("#no_bukti").val();
            const tanggal = formCreate.find("#tanggal").val();
            const kode_jenis_pengeluaran = formCreate.find("#kode_jenis_pengeluaran").val();
            const kode_cabang = formCreate.find("#kode_cabang").val();
            const keterangan = formCreate.find("#keterangan_barang_keluar").val();
            const unit = formCreate.find("#unit").val();
            if (formCreate.find('#loaddetail tr').length == 0) {
                Swal.fire({
                    title: "Oops!",
                    text: "Data Barang Masih Kosong !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        formCreate.find("#kode_barang").focus();
                    },
                });

                return false;
            } else if (tanggal == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Tanggal Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        formCreate.find("#tanggal").focus();
                    },
                });

                return false;
            } else if (kode_jenis_pengeluaran == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Jenis Pengeluaran Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        formCreate.find("#kode_jenis_pengeluaran").focus();
                    },
                });

                return false;

            } else if (kode_jenis_pengeluaran == "CBG" && kode_cabang == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Cabang Harus  Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        formCreate.find("#kode_cabang").focus();
                    },
                });

                return false;
            } else if (kode_jenis_pengeluaran == "PRD" && unit == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Unit Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        formCreate.find("#unit").focus();
                    },
                });

                return false;
            }
        });
    });
</script>
