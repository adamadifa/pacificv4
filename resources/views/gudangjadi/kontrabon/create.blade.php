@extends('layouts.app')
@section('titlepage', 'Buat Kontra Bon Angkutan')
@section('content')

    <style>
        .nonaktif {
            background-color: red;
        }
    </style>
@section('navigasi')
    <span class="text-muted">Kontrabon</span> / <span>Buat Kontra Bon Angkutan</span>
@endsection
<form action="{{ route('kontrabonangkutan.store') }}" method="POST" id="formKontrabon">
    @csrf
    <div class="row">
        <div class="col-lg-3 col-sm-12 col-xs-12">
            <div class="row mb-3">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <x-input-with-icon label="Auto" name="no_kontrabon" icon="ti ti-barcode" />
                            <x-input-with-icon label="Tanggal" name="tanggal" icon="ti ti-calendar" datepicker="flatpickr-date" />
                            <x-select label="Angkutan" name="kode_angkutan" :data="$angkutan" key="kode_angkutan" textShow="nama_angkutan"
                                upperCase="true" select2="select2Kodeangkutan" />

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
                                <div class="col">
                                    <div class="form-group mb-3">
                                        <select name="no_dok" id="no_dok" class="form-select select2Nodokumen">
                                            <option value="">
                                                No. Dokumen | Tanggal | Tujuan | Tarif
                                            </option>
                                        </select>
                                    </div>
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
                                                <th>No. Dokumen</th>
                                                <th>Tanggal</th>
                                                <th>Tujuan</th>
                                                <th>Tarif</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody id="loadsuratjalan"></tbody>
                                        <tfoot class="table-dark">
                                            <tr>
                                                <td colspan="3">TOTAL</td>
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

@endsection
@push('myscript')
<script>
    $(function() {
        const form = $("#formKontrabon");
        let total_tarif = 0;
        let total_bayar = 0;
        let sisa_bayar = 0;

        function removeSpecialCharacters(str) {
            // Ekspresi reguler untuk mencari karakter yang bukan huruf atau angka
            const regex = /[^a-zA-Z0-9]/g;
            // Mengganti karakter yang cocok dengan ekspresi reguler dengan string kosong
            return str.replace(regex, '');
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



        const select2Kodeangkutan = $('.select2Kodeangkutan');
        if (select2Kodeangkutan.length) {
            select2Kodeangkutan.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Angkutan',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

        const select2Nodokumen = $('.select2Nodokumen');
        if (select2Nodokumen.length) {
            select2Nodokumen.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'No. Dokumen | Tanggal | Tujuan | Tarif',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

        function getsuratjalanbyangkutan() {
            const kode_angkutan = form.find("#kode_angkutan").val();
            $("#no_dok").load(`/suratjalanangkutan/${kode_angkutan}/getsuratjalanbyangkutan`);
        }

        form.find("#kode_angkutan").change(function(e) {
            getsuratjalanbyangkutan();
        });



        function calculateTotal() {
            let grandTotal = 0;
            $('.totaltarif').each(function() {
                grandTotal += parseFloat(convertNumber($(this).text())) || 0;
            });
            $('#grandtotal').text(numberFormat(grandTotal, '0', ',', '.'));
            $('#grandtotal_text').text(numberFormat(grandTotal, '0', ',', '.'));
        }

        function resetForm() {
            form.find('.select2Nodok').val('').trigger("change");
        }


        form.find("#btnTambahitem").click(function(e) {
            e.preventDefault();
            const no_dok = form.find("#no_dok").val();
            const no_dok_index = removeSpecialCharacters(no_dok);

            const selectedNodok = $("#no_dok option:selected");
            const tanggal = selectedNodok.attr("tanggal");
            const tujuan = selectedNodok.attr("tujuan");
            const tarif = selectedNodok.attr("tarif");

            if (no_dok == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Silahkan Pilih No. Dokumen Terlebih Dahulu!",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#no_dok").focus();
                    },
                });
            } else if ($('#loadsuratjalan').find('#' + no_dok_index).length > 0) {
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
                    <tr id='${no_dok_index}'>
                        <input type="hidden" name="no_dok_item[]" value="${no_dok}"/>
                        <td>${no_dok}</td>
                        <td>${tanggal}</td>
                        <td>${tujuan}</td>
                        <td class='text-end totaltarif'>${numberFormat(tarif, '0', ',', '.')}</td>
                        <td>
                            <a href="#" id="${no_dok_index}" class="delete"><i class="ti ti-trash text-danger"></i></a>
                        </td>
                    </tr>;
                `;

                form.find("#loadsuratjalan").append(newItem);
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

        form.find("#kode_angkutan").change(function() {
            form.find("#loadsuratjalan").html("");
        });
        form.submit(function(e) {
            const tanggal = form.find("#tanggal").val();
            const kode_angkutan = form.find("#kode_angkutan").val();

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
            } else if (kode_angkutan == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Angkutan Harus Diisi!",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#kode_angkutan").focus();
                    },
                });
                return false;
            } else if ($('#loadsuratjalan tr').length == 0) {
                Swal.fire({
                    title: "Oops!",
                    text: "Detail Kontrabon Tidak Boleh Kosong!",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        form.find("#no_dok").focus();
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
