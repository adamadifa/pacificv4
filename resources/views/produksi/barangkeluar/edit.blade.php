@extends('layouts.app')
@section('titlepage', 'Edit Barang Keluar Produksi')

@section('content')
@section('navigasi')
    <span>Edit Barang Keluar Produksi</span>
@endsection
<form action="{{ route('barangkeluarproduksi.update', Crypt::encrypt($barangkeluarproduksi->no_bukti)) }}" id="formceditBarangkeluarproduksi"
    method="POST">
    @csrf
    <input type="hidden" id="cektutuplaporan">
    <input type="hidden" id="cekdetailtemp">
    <div class="row">
        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="card-body">
                    <x-input-with-icon icon="ti ti-barcode" value="{{ $barangkeluarproduksi->no_bukti }}" label="Auto" name="no_bukti"
                        disabled="true" />
                    <x-input-with-icon icon="ti ti-calendar" label="Tanggal" name="tanggal" datepicker="flatpickr-date"
                        value="{{ $barangkeluarproduksi->tanggal }}" />
                    <div class="form-group mb-3">
                        <select name="kode_jenis_pengeluaran" id="kode_jenis_pengeluaran" class="form-select">
                            <option value="">Jenis Pengeluaran</option>
                            <option value="RO" {{ $barangkeluarproduksi->kode_jenis_pengeluaran == 'RO' ? 'selected' : '' }}>Retur Out
                            </option>
                            <option value="PK" {{ $barangkeluarproduksi->kode_jenis_pengeluaran == 'PK' ? 'selected' : '' }}>Pemakaian
                            </option>
                            <option value="LN" {{ $barangkeluarproduksi->kode_jenis_pengeluaran == 'LN' ? 'selected' : '' }}>Lainnya
                            </option>
                        </select>
                    </div>
                    <div id="supplier">
                        <x-select label="Pilih Supplier" name="kode_supplier" :data="$supplier" key="kode_supplier" textShow="nama_supplier"
                            select2="select2Kodesupplier" selected="{{ $barangkeluarproduksi->kode_supplier }}" />
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-9 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-4 col-md-12 col-sm-12">
                            <x-select label="Pilih Barang" name="kode_barang_produksi" :data="$barangproduksi" key="kode_barang_produksi"
                                textShow="nama_barang" select2="select2Kodebarangproduksi" showKey="true" />
                        </div>
                        <div class="col-lg-3 col-md-12 col-sm-12">
                            <x-input-with-icon icon="ti ti-file-description" label="Keterangan" name="keterangan" />
                        </div>
                        <div class="col-lg-2 col-md-12 col-sm-12">
                            <x-input-with-icon icon="ti ti-file" numberFormat="true" label="Jumlah" name="jumlah" align="right" />
                        </div>
                        <div class="col-lg-2 col-md-12 col-sm-12">
                            <x-input-with-icon icon="ti ti-scale" numberFormat="true" label="Berat" name="jumlah_berat" align="right" />
                        </div>
                        <div class="col-lg-1 col-md-12 col-sm-12">
                            <div class="form-group">
                                <button class="btn btn-primary " id="tambahbarang"><i class="ti ti-plus"></i></button>
                            </div>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <table class="table table-hover table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Barang</th>
                                        <th>Nama Barang</th>
                                        <th>Satuan</th>
                                        <th>Keterangan</th>
                                        <th>Jumlah</th>
                                        <th>Berat</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody id="loaddetailtemp"></tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-check mt-3 mb-3">
                                <input class="form-check-input agreement" name="aggrement" value="aggrement" type="checkbox" value=""
                                    id="defaultCheck3">
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
                </div>
            </div>
        </div>
    </div>
</form>
<x-modal-form id="mdledit" show="loadedit" title="Edit Barang" />
@endsection
@push('myscript')
<script>
    $(function() {
        const tanggal = $("#tanggal").val();
        easyNumberSeparator({
            selector: '.number-separator',
            separator: '.',
            decimalSeparator: ',',
        });

        const select2Kodebarangproduksi = $('.select2Kodebarangproduksi');
        const select2Kodesupplier = $('.select2Kodesupplier');

        function initselect2Kodebarangproduksi() {
            if (select2Kodebarangproduksi.length) {
                select2Kodebarangproduksi.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Pilih Barang',
                        dropdownParent: $this.parent(),

                    });
                });
            }
        }

        function initselect2Kodesupplier() {
            if (select2Kodesupplier.length) {
                select2Kodesupplier.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Pilih Supplier',
                        dropdownParent: $this.parent(),

                    });
                });
            }
        }




        function showSupplier() {
            var kode_jenis_pengeluaran = $("#kode_jenis_pengeluaran").val();
            if (kode_jenis_pengeluaran == "RO") {
                $("#supplier").show();
            } else {
                $("#supplier").hide();
            }
        }

        showSupplier();

        function cekdetailtemp() {
            var no_bukti = $("#no_bukti").val();
            $.ajax({
                type: 'POST',
                url: '/barangkeluarproduksi/cekdetailedit',
                data: {
                    _token: "{{ csrf_token() }}",
                    no_bukti: no_bukti
                },
                cache: false,
                success: function(respond) {
                    $("#cekdetailtemp").val(respond);
                }
            });
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

        function loaddetailtemp() {
            const no_bukti = "{{ Crypt::encrypt($barangkeluarproduksi->no_bukti) }}";
            $("#loaddetailtemp").load("/barangkeluarproduksi/" + no_bukti + "/getdetailedit");
            cekdetailtemp();
        }

        loaddetailtemp();
        initselect2Kodebarangproduksi();
        initselect2Kodesupplier();
        cektutuplaporan(tanggal, "produksi");
        $("#tanggal").change(function() {
            cektutuplaporan($(this).val(), "produksi");
        });



        //Tambah Barang
        $("#tambahbarang").click(function(e) {
            e.preventDefault();
            var kode_barang_produksi = $("#kode_barang_produksi").val();
            var no_bukti = "{{ $barangkeluarproduksi->no_bukti }}";
            //alert(kode_barang_produksi);
            var keterangan = $("#keterangan").val();
            var jumlah = $("#jumlah").val();
            var jumlah_berat = $("#jumlah_berat").val();
            if (kode_barang_produksi == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Silahkan Pilih dulu Barang !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $("#kode_barang_produksi").focus();
                    },

                });
            } else if (jumlah === "" || jumlah === "0") {
                Swal.fire({
                    title: "Oops!",
                    text: "Jumlah Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $("#jumlah").focus();
                    },

                });
            } else {
                $.ajax({
                    type: "POST",
                    url: "{{ route('barangkeluarproduksi.storedetailedit') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        no_bukti: no_bukti,
                        kode_barang_produksi: kode_barang_produksi,
                        keterangan: keterangan,
                        jumlah: jumlah,
                        jumlah_berat: jumlah_berat
                    },
                    cache: false,
                    success: function(respond) {
                        Swal.fire({
                            title: "Berhasil!",
                            text: respond.message,
                            icon: "success",
                            showConfirmButton: true,
                            didClose: (e) => {
                                $("#kode_barang_produksi").focus();
                                $('#kode_barang_produksi').val(null)
                                    .trigger('change');
                                $("#nama_barang").val("");
                                $("#keterangan").val("");
                                $("#jumlah").val("");
                                $("#jumlah_berat").val("");
                                loaddetailtemp();
                            },
                        });
                    },
                    error: function(xhr) {
                        Swal.fire("Error", xhr.responseJSON.message, "error");
                    }
                });
            }
        });

        //Hapus Barang
        $('body').on('click', '.delete', function() {
            var id = $(this).attr('id');
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
                    $.ajax({
                        type: 'POST',
                        url: '/barangkeluarproduksi/deleteedit',
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: id,
                        },
                        cache: false,
                        success: function(respond) {
                            Swal.fire({
                                title: "Berhasil",
                                text: "Data Berhasil Dihapus",
                                icon: "success"
                            });
                            loaddetailtemp();
                        },
                        error: function(xhr) {
                            Swal.fire("Error", xhr.responseJSON.message, "error");
                        }
                    });
                }
            });
        });

        $('body').on('click', '.edit', function() {
            var id = $(this).attr('id');
            event.preventDefault();
            $('#mdledit').modal("show");
            $("#loadedit").load('/barangkeluarproduksi/' + id + '/editbarang');

        });


        $('body').on('click', '#updatebarang', function() {
            event.preventDefault();
            var id = $(this).attr('id_edit');
            var keterangan = $("#keterangan_edit").val();
            var jumlah = $("#jumlah_edit").val();
            var jumlah_berat = $("#jumlah_berat_edit").val();
            if (jumlah === "0" || jumlah === "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Jumlah Tidak Boleh Kosong",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $("#jumlah_edit").focus();
                    },
                });
            } else {
                $.ajax({
                    type: "POST",
                    url: "/barangkeluarproduksi/updatebarang",
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: id,
                        keterangan: keterangan,
                        jumlah: jumlah,
                        jumlah_berat: jumlah_berat
                    },
                    cache: false,
                    success: function(respond) {
                        Swal.fire({
                            title: "Berhasil",
                            text: respond.message,
                            icon: "success"
                        });
                        $('#mdledit').modal("hide");
                        loaddetailtemp();
                    },
                    error: function(xhr) {
                        Swal.fire("Error", xhr.responseJSON.message, "error");
                    }
                });
            }

        });

        $("#saveButton").hide();

        $('.agreement').change(function() {
            if (this.checked) {
                $("#saveButton").show();
            } else {
                $("#saveButton").hide();
            }
        });

        $("#formceditbarangkeluarproduksi").submit(function() {
            const tanggal = $("#tanggal").val();
            const kode_asal_barang = $("#kode_asal_barang").val();
            const cektutuplaporan = $("#cektutuplaporan").val();
            const cekdetailtemp = $("#cekdetailtemp").val();
            if (tanggal == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Tanggal Tidak Boleh Kosong !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $("#tanggal").focus();
                    },
                });

                return false;
            } else if (kode_asal_barang == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Asal Barang Tidak Boleh Kosong !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $("#kode_asal_barang").focus();
                    },
                });

                return false;
            } else if (cektutuplaporan === '1') {
                Swal.fire("Oops!", "Laporan Untuk Periode Ini Sudah Ditutup", "warning");
                return false;
            } else if (cekdetailtemp === '0' || cekdetailtemp === '') {
                Swal.fire("Oops!", "Data Masih Kosong", "warning");
                return false;
            } else {
                $("#btnSimpan").prop('disabled', true);
            }


        });

    });
</script>
@endpush
