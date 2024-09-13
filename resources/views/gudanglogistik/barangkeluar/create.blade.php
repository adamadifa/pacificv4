<form action="{{ route('barangkeluargudanglogistik.store') }}" method="post" id="formBarangkeluargudanglogistik">
    @csrf
    <x-input-with-icon icon="ti ti-barcode" label="No. Bukti Pemasukan" name="no_bukti" />
    <x-input-with-icon icon="ti ti-calendar" label="Tanggal" name="tanggal" datepicker="flatpickr-date" />
    <div class="form-group mb-3">
        <select name="kode_jenis_pengeluaran" id="kode_jenis_pengeluaran" class="form-select select2Jenispengeluaran">
            <option value="">Jenis Pengeluaran</option>
            @foreach ($list_jenis_pengeluaran as $d)
                <option value="{{ $d['kode_jenis_pengeluaran'] }}"
                    {{ Request('kode_jenis_pengeluaran') == $d['kode_jenis_pengeluaran'] ? 'selected' : '' }}>
                    {{ $d['jenis_pengeluaran'] }}</option>
            @endforeach
        </select>
    </div>
    <div class="row" id="cabang-section">
        <div class="col">
            <x-select label="Cabang" name="kode_cabang" :data="$cabang" key="kode_cabang" textShow="nama_cabang" select2="select2Kodecabang"
                upperCase="true" />
        </div>
    </div>
    <div class="divider text-start">
        <div class="divider-text">Detail Barang</div>
    </div>
    <div class="row">
        <div class="col-lg-6 col-md-12 col-sm-12">
            <select name="kode_barang" id="kode_barang" class="form-select select2Kodebarang">
                <option value="">Pilih Barang</option>
                @foreach ($barang as $d)
                    <option value="{{ $d->kode_barang }}">{{ $d->kode_barang }}|{{ $d->nama_barang }} ({{ $d->satuan }})</option>
                @endforeach
            </select>
            {{-- <x-select label="Pilih Barang" name="kode_barang" :data="$barang" key="kode_barang" textShow="nama_barang" upperCase="true"
                select2="select2Kodebarang" showKey="true" /> --}}
        </div>
        <div class="col-lg-2 col-md-12 col-sm-12">
            <x-input-with-icon icon="ti ti-box" label="Jumlah" name="jumlah" align="right" numberFormat="true" />
        </div>
        <div class="col-lg-4 col-md-12 col-sm-12">
            <x-select label="Cabang" name="kode_cabang_detail" :data="$cabang" key="kode_cabang" textShow="nama_cabang"
                select2="select2Kodecabangdetail" upperCase="true" />
        </div>
    </div>
    <x-input-with-icon icon="ti ti-file-description" label="Keterangan" name="keterangan" />
    <a href="#" class="btn btn-primary w-100" id="tambahproduk"><i class="ti ti-plus me-1"></i>Tambah Produk</a>
    <div class="row mt-2">
        <div class="col">
            <table class="table table-bordered" id="tabledetail">
                <thead class="table-dark">
                    <tr>
                        <th style="width: 10%">Kode</th>
                        <th style="width: 25%">Nama Barang</th>
                        <th>Jumlah</th>
                        <th>Cabang</th>
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
        const form = $("#formBarangkeluargudanglogistik");
        const select2Kodecabang = form.find('.select2Kodecabang');
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

        const select2Kodecabangdetail = form.find('.select2Kodecabangdetail');
        if (select2Kodecabangdetail.length) {
            select2Kodecabangdetail.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Pilih Cabang',
                    dropdownParent: $this.parent(),
                    allowClear: true
                });
            });
        }

        const select2Jenispengeluaran = form.find('.select2Jenispengeluaran');
        if (select2Jenispengeluaran.length) {
            select2Jenispengeluaran.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Jenis Pengeluaran',
                    dropdownParent: $this.parent(),
                    allowClear: true
                });
            });
        }

        function loadkodecabang() {
            const kode_jenis_pengeluaran = form.find("#kode_jenis_pengeluaran").val();
            if (kode_jenis_pengeluaran == "CBG") {
                form.find("#cabang-section").show();
            } else {
                form.find("#cabang-section").hide();
            }
        }
        loadkodecabang();

        form.find("#kode_jenis_pengeluaran").change(function() {
            loadkodecabang();
        });
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
            cektutuplaporan($(this).val(), "gudanglogistik");
        });

        const select2Kodebarang = form.find('.select2Kodebarang');
        if (select2Kodebarang.length) {
            select2Kodebarang.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Pilih Barang',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

        function addProduk() {
            const dataBarang = form.find("#kode_barang :selected").select2(this.data);
            const kode_barang = $(dataBarang).val();
            const nama_barang = $(dataBarang).text().split("|");
            const jumlah = form.find("#jumlah").val();
            const kode_cabang = form.find("#kode_cabang_detail").val();
            const nama_cabang = form.find("#kode_cabang_detail :selected").text() == "Cabang" ? "" : form.find(
                "#kode_cabang_detail :selected").text();
            const keterangan = form.find("#keterangan").val();

            let produk = `
                    <tr id="index_${kode_barang}">
                        <td>
                            <input type="hidden" name="kode_barang[]" value="${kode_barang}"/>
                            ${kode_barang}
                        </td>
                        <td>${nama_barang[1]}</td>
                        <td class="text-end">
                            <input type="hidden" name="jml[]" value="${jumlah}" class="noborder-form text-end jumlah" />
                            ${jumlah}
                        </td>
                        <td>
                            <input type="hidden" name="kode_cbg[]" value="${kode_cabang}" class="noborder-form text-end harga" />
                            ${nama_cabang}
                        </td>
                        <td>
                            <input type="hidden" name="ket[]" value="${keterangan}" class="noborder-form" />
                            ${keterangan}
                        </td>
                        <td class="text-center">
                            <a href="#" kode_barang="${kode_barang}" class="delete"><i class="ti ti-trash text-danger"></i></a>
                        </td>
                    </tr>
                `;

            //append to table
            $('#loaddetail').prepend(produk);
            $('.select2Kodebarang').val('').trigger("change");
            $('.select2Kodecabangdetail').val('').trigger("change");
            $("#jumlah").val("");
            $("#keterangan").val("");
            $("#kode_barang").focus();
        }

        form.find("#tambahproduk").click(function(e) {
            e.preventDefault();
            const kode_barang = form.find("#kode_barang").val();
            const jumlah = form.find("#jumlah").val();
            if (kode_barang == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Silahkan Pilih dulu Barang !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#kode_barang").focus();
                    },

                });

            } else if (jumlah == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Jumlah Harus Diisi  !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#jumlah").focus();
                    },

                });

            } else {
                form.find("#tambahproduk").prop('disabled', true);
                addProduk();
                // if (form.find('#tabledetail').find('#index_' + kode_barang).length > 0) {
                //    Swal.fire({
                //       title: "Oops!",
                //       text: "Data Sudah Ada!",
                //       icon: "warning",
                //       showConfirmButton: true,
                //       didClose: (e) => {
                //          form.find("#kode_produk").focus();
                //       },

                //    });
                // } else {
                //    addProduk();
                // }
            }
        });

        form.on('click', '.delete', function(e) {
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
        form.find("#saveButton").hide();

        form.find('.agreement').change(function() {
            if (this.checked) {
                form.find("#saveButton").show();
            } else {
                form.find("#saveButton").hide();
            }
        });

        form.submit(function() {
            const no_bukti = form.find("#no_bukti").val();
            const tanggal = form.find("#tanggal").val();
            const kode_jenis_pengeluaran = form.find("#kode_jenis_pengeluaran").val();
            const kode_cabang = form.find("#kode_cabang").val();
            if (form.find('#loaddetail tr').length == 0) {
                Swal.fire({
                    title: "Oops!",
                    text: "Data Barang Masih Kosong !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#kode_barang").focus();
                    },
                });

                return false;
            } else if (no_bukti == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "No. Bukti Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#no_bukti").focus();
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
                        form.find("#tanggal").focus();
                    },
                });

                return false;
            } else if (kode_jenis_pengeluaran == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Jenis Pengeluaran Harus  Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#kode_jenis_pengeluaran").focus();
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
                        form.find("#kode_cabang").focus();
                    },
                });

                return false;
            }
        });
    });
</script>
