@extends('layouts.app')
@section('titlepage', 'Buat Kontra Bon')
@section('content')

    <style>
        .nonaktif {
            background-color: red;
        }
    </style>
@section('navigasi')
    <span class="text-muted">Kontrabon</span> / <span>Buat Kontra Bon</span>
@endsection
<form action="{{ route('kontrabonpmb.store') }}" method="POST" id="formKontrabon">
    @csrf
    <div class="row">
        <div class="col-lg-3 col-sm-12 col-xs-12">
            <div class="row mb-3">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <x-input-with-icon label="Auto" name="no_kontrabon" icon="ti ti-barcode" disabled="true" />
                            <x-input-with-icon label="Tanggal" name="tanggal" icon="ti ti-calendar" datepicker="flatpickr-date" />
                            <x-select label="Supplier" name="kode_supplier" :data="$supplier" key="kode_supplier" textShow="nama_supplier"
                                upperCase="true" select2="select2Kodesupplier" />
                            <div class="form-group mb-3">
                                <select name="kategori" id="kategori" class="form-select">
                                    <option value="">Jenis Pengajuan</option>
                                    <option value="KB">Kontra Bon</option>
                                    <option value="IM">Interal Memo</option>
                                </select>
                            </div>
                            <x-input-with-icon label="No. Dokumen" name="no_dokumen" icon="ti ti-barcode" />
                            <div class="form-group mb-3">
                                <select name="jenis_bayar" id="jenis_bayar" class="form-select">
                                    <option value="">Jenis Bayar</option>
                                    <option value="TN">Tunai</option>
                                    <option value="TF">Transfer</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-9 col-md-12 col-sm-12">

            <div class="row mb-3">
                <div class="col">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between">
                                <h5 class="card-title">Detail Kontrabon</h5>
                                <div class="d-flex justify-content-between">
                                    <i class="ti ti-shopping-cart text-primary me-5" style="font-size: 2em;"></i>
                                    <h4 id="grandtotal_text">0</h4>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-3 col-md-12 col-sm-12">
                                    <x-input-with-icon label="No. Bukti Pembelian" name="no_bukti" icon="ti ti-barcode" readonly="true" />
                                </div>
                                <div class="col-lg-2 col-md-12 col-sm-12">
                                    <x-input-with-icon label="Total Pembelian" name="total_pembelian" icon="ti ti-box" align="right"
                                        readonly="true" />
                                </div>
                                <div class="col-lg-2 col-md-12 col-sm-12">
                                    <x-input-with-icon label="Jumlah Bayar" name="jumlah" icon="ti ti-moneybag" align="right" numberFormat="true" />
                                </div>
                                <div class="col-lg-5 col-md-12 col-sm-12">
                                    <x-input-with-icon label="Keterangan" name="keterangan" icon="ti ti-file-description" align="right"
                                        numberFormat="true" />
                                </div>
                            </div>

                            <div class="row">
                                <div class="col">
                                    <div class="form-group mb-3">
                                        <button class="btn btn-primary w-100" id="btnTambahitem">
                                            <i class="ti ti-plus me-1"></i>Tambah Item
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">

                                    <table class="table table-bordered">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>No. Bukti</th>
                                                <th>Keterangan</th>
                                                <th>Jumlah</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody id="loadpembelian"></tbody>
                                        <tfoot class="table-dark">
                                            <tr>
                                                <td colspan="2">TOTAL</td>
                                                <td id="grandtotal" class="text-end"></td>
                                                <td></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            <div class="row mt-2">
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

        </div>
    </div>
</form>
<div class="modal fade" id="modalPembelian" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel18">Data Pembelian</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table" id="tabelpembelian" width="100%">
                        <thead class="table-dark">
                            <tr>
                                <th>No. Bukti</th>
                                <th>Tanggal</th>
                                <th>Asal Ajuan</th>
                                <th>PPN</th>
                                <th>Subtotal</th>
                                <th>Peny JK</th>
                                <th>Total</th>
                                <th>#</th>
                            </tr>
                        </thead>
                        <tbody></tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('myscript')
<script>
    $(function() {
        const form = $("#formKontrabon");
        let total_pembelian = 0;
        let total_bayar = 0;
        let sisa_bayar = 0;

        function removeSpecialCharacters(str) {
            // Ekspresi reguler untuk mencari karakter yang bukan huruf atau angka
            const regex = /[^a-zA-Z0-9]/g;
            // Mengganti karakter yang cocok dengan ekspresi reguler dengan string kosong
            return str.replace(regex, '');
        }



        function loadTabelpembelian(kode_supplier) {
            $('#tabelpembelian').DataTable({
                processing: true,
                serverSide: true,
                order: [
                    [0, 'asc']
                ],
                ajax: `/pembelian/${kode_supplier}/getpembelianbysupplierjson`,
                bAutoWidth: false,
                bDestroy: true,
                columns: [{
                        data: 'no_bukti',
                        name: 'no_bukti',
                        orderable: true,
                        searchable: true,
                        width: '15%'
                    },
                    {
                        data: 'tanggal',
                        name: 'tanggal',
                        orderable: true,
                        searchable: false,
                        width: '10%'
                    },
                    {
                        data: 'asal_pengajuan',
                        name: 'kode_asal_pengajuan',
                        orderable: true,
                        searchable: false,
                        width: '20%'
                    },

                    {
                        data: 'cekppn',
                        name: 'ppn',
                        orderable: true,
                        searchable: false,
                        width: '5%'
                    },
                    {
                        data: 'subtotal',
                        name: 'subtotal',
                        orderable: true,
                        searchable: false,
                        width: '15%'
                    },
                    {
                        data: 'penyesuaianjk',
                        name: 'penyesuaian_jk',
                        orderable: true,
                        searchable: false,
                        width: '10%'
                    },
                    {
                        data: 'totalpembelian',
                        name: 'total_pembelian',
                        orderable: true,
                        searchable: false,
                        width: '15%'
                    },

                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        width: '5%'
                    }
                ],
                columnDefs: [{
                    "targets": [4, 5, 6], // kolom ke-6 (Salary)
                    "className": "text-end"
                }],
                rowCallback: function(row, data, index) {

                }
            });
        }

        easyNumberSeparator({
            selector: '.number-separator',
            separator: '.',
            decimalSeparator: ',',
        });

        function convertNumber(number) {
            // Hilangkan semua titik
            let formatted = number.replace(/\./g, '');
            // Ganti semua koma dengan titik
            formatted = formatted.replace(/,/g, '.');
            return formatted || 0;
        }


        function numberFormat(number, decimals, dec_point, thousands_sep) {
            number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
            var n = !isFinite(+number) ? 0 : +number,
                prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
                sep = typeof thousands_sep === 'undefined' ? ',' : thousands_sep,
                dec = typeof dec_point === 'undefined' ? '.' : dec_point,
                s = '',
                toFixedFix = function(n, prec) {
                    var k = Math.pow(10, prec);
                    return '' + Math.round(n * k) / k;
                };
            // Fix for IE parseFloat(0.55).toFixed(0) = 0;
            s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
            if (s[0].length > 3) {
                s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
            }
            if ((s[1] || '').length < prec) {
                s[1] = s[1] || '';
                s[1] += new Array(prec - s[1].length + 1).join('0');
            }
            return s.join(dec);
        };

        form.find("#no_bukti").click(function(e) {
            const kode_supplier = form.find("#kode_supplier").val();
            if (kode_supplier == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Supplier Harus Diisi Terlebih Dahulu!",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#kode_supplier").focus();
                    },
                });
            } else {
                $("#modalPembelian").modal("show");
                loadTabelpembelian(kode_supplier);
            }

        });

        const select2Kodesupplier = $('.select2Kodesupplier');
        if (select2Kodesupplier.length) {
            select2Kodesupplier.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Supplier',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

        $('#tabelpembelian tbody').on('click', '.pilihnobukti', function(e) {
            e.preventDefault();
            const no_bukti = $(this).attr('no_bukti');
            total_pembelian = $(this).attr('total_pembelian') || 0;
            total_bayar = $(this).attr('total_bayar') || 0;
            sisa_bayar = parseFloat(total_pembelian) - parseFloat(total_bayar);
            //alert(sisa_bayar);
            form.find("#no_bukti").val(no_bukti);
            form.find("#total_pembelian").val(numberFormat(total_pembelian, '2', ',', '.'));
            $("#modalPembelian").modal("hide");
            form.find("#jumlah").focus();

        });

        function calculateTotal() {
            let grandTotal = 0;
            $('.totalbayar').each(function() {
                grandTotal += parseFloat(convertNumber($(this).text())) || 0;
            });
            $('#grandtotal').text(numberFormat(grandTotal, '2', ',', '.'));
            $('#grandtotal_text').text(numberFormat(grandTotal, '2', ',', '.'));
        }

        function resetForm() {
            form.find("#no_bukti").val("");
            form.find("#jumlah").val("");
            form.find("#keterangan").val("");
            form.find("#total_pembelian").val("");
        }


        form.find("#btnTambahitem").click(function(e) {
            e.preventDefault();
            const no_bukti = form.find("#no_bukti").val();
            const jumlah = form.find("#jumlah").val();
            const jml = convertNumber(jumlah);
            const keterangan = form.find("#keterangan").val();
            const no_bukti_index = removeSpecialCharacters(no_bukti);
            if (no_bukti == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Silahkan Pilih No. Bukti Terlebih Dahulu!",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#no_bukti").focus();
                    },
                });
            } else if (jumlah == "" || jumlah == 0) {
                Swal.fire({
                    title: "Oops!",
                    text: "Jumlah Harus Diisi Tidak Boleh 0!",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#jumlah").focus();
                    },
                });
            } else if (jml > sisa_bayar) {
                Swal.fire({
                    title: "Oops!",
                    text: "Jumlah Bayar Melebihi Sisa Pembayaran!",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#jumlah").focus();
                    },
                });
            } else if ($('#loadpembelian').find('#' + no_bukti_index).length > 0) {
                Swal.fire({
                    title: "Oops!",
                    text: "Data Sudah Ada!",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#no_bukti").focus();
                    },

                });
            } else {
                let newItem = `
                    <tr id='${no_bukti_index}'>
                        <input type="hidden" name="no_bukti_item[]" value="${no_bukti}"/>
                        <input type="hidden" name="keterangan_item[]" value="${keterangan}"/>
                        <input type="hidden" name="jumlah_item[]" value="${jumlah}"/>
                        <td>${no_bukti}</td>
                        <td>${keterangan}</td>
                        <td class='text-end totalbayar'>${jumlah}</td>
                        <td>
                            <a href="#" id="${no_bukti_index}" class="delete"><i class="ti ti-trash text-danger"></i></a>
                        </td>
                    </tr>;
                `;

                form.find("#loadpembelian").append(newItem);
                calculateTotal();
                resetForm();
            }
        });

        $(document).on('click', '.delete', function(e) {
            e.preventDefault();
            let id = $(this).attr("id");
            //alert(id);
            // event.preventDefault();
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
                    $(`#${id}`).remove();
                    calculateTotal();
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

        form.find("#saveButton").hide();

        form.find('.agreement').change(function() {
            if (this.checked) {
                form.find("#saveButton").show();
            } else {
                form.find("#saveButton").hide();
            }
        });

        form.find("#kode_supplier").change(function() {
            form.find("#loadpembelian").html("");
        });
        form.submit(function(e) {
            const tanggal = form.find("#tanggal").val();
            const kode_supplier = form.find("#kode_supplier").val();
            const kategori = form.find("#kategori").val();
            const jenis_bayar = form.find("#jenis_bayar").val();
            if (tanggal == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Tanggal Harus Diisi!",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#tanggal").focus();
                    },
                });
                return false;
            } else if (kode_supplier == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Supplier Harus Diisi!",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#kode_supplier").focus();
                    },
                });
                return false;
            } else if (kategori == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Jenis Pengajuan Harus Diisi!",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#kategori").focus();
                    },
                });
                return false;
            } else if (jenis_bayar == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Jenis Bayar Harus Diisi!",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#jenis_bayar").focus();
                    },
                });
                return false;
            } else if ($('#loadpembelian tr').length == 0) {
                Swal.fire({
                    title: "Oops!",
                    text: "Detail Pembelian Tidak Boleh Kosong!",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        form.find("#no_bukti").focus();
                    },
                });
                return false;
            } else {
                buttonDisable();
            }
        });
    });
</script>
@endpush
