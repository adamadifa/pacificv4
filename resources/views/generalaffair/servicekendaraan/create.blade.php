@extends('layouts.app')
@section('titlepage', 'Input Service Kendaraan')
@section('content')

    <style>
        .nonaktif {
            background-color: red;
        }
    </style>
@section('navigasi')
    <span class="text-muted">Service Kendaraan</span> / <span>Input Service Kendaraan</span>
@endsection
<form action="{{ route('servicekendaraan.store') }}" method="POST" id="formServicekendaraan">
    @csrf
    <div class="row">
        <div class="col-lg-4 col-sm-12 col-xs-12">
            <div class="row mb-3">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <x-input-with-icon label="No. Invoice" name="no_invoice" icon="ti ti-receipt" />
                            <x-input-with-icon label="Tanggal" name="tanggal" icon="ti ti-calendar" datepicker="flatpickr-date" />
                            <div class="form-group mb-3">
                                <select name="kode_kendaraan" id="kode_kendaraan" class="form-select select2Kendaraan">
                                    <option value="">Pilih Kendaraan</option>
                                    @foreach ($kendaraan as $d)
                                        <option value="{{ $d->kode_kendaraan }}">
                                            {{ $d->no_polisi . '  ' . $d->merek . ' ' . $d->tipe_kendaraan . '  ' . ' ' . $d->tipe }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="row">
                                <div class="col-lg-10 col-md-12 col-sm-12">
                                    <div class="form-group mb-3">
                                        <select name="kode_bengkel" id="kode_bengkel" class="form-select select2Kodebengkel">
                                            <option value="">Pilih Bengkel</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-12 col-sm-12">
                                    <div class="form-group mb-3">
                                        <a href="#" class="btn btn-primary" id="tambahbengkel"><i class="ti ti-plus"></i></a>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <x-textarea label="Keterangan" name="keterangan" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8 col-md-12 col-sm-12">

            <div class="row mb-3">
                <div class="col">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between">
                                <h5 class="card-title">Detail Pembelian</h5>
                                <div class="d-flex justify-content-between">
                                    <i class="ti ti-shopping-cart text-primary me-5" style="font-size: 2em;"></i>
                                    <h4 id="grandtotal_text">0</h4>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-4 col-md-12 col-sm-12">
                                    <div class="form-group mb-3">
                                        <select name="kode_item" id="kode_item" class="form-select select2KodeItem">
                                            <option value="">Pilih Item</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-1 col-md-12 col-sm-12">
                                    <div class="form-group mb-3">
                                        <a href="#" class="btn btn-primary" id="tambahitemservice"><i class="ti ti-plus"></i></a>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-12 col-sm-12">
                                    <x-input-with-icon label="Qty" name="jumlah" align="right" money="true" icon="ti ti-box" />
                                </div>
                                <div class="col-lg-3 col-md-12 col-sm-12">
                                    <x-input-with-icon label="Harga" name="harga" align="right" money="true" icon="ti ti-moneybag" />
                                </div>
                                <div class="col-lg-2 col-md-12 col-sm-12">
                                    <div class="form-group mb-3">
                                        <a href="#" class="btn btn-primary" id="tambahitem"><i class="ti ti-shopping-cart-plus"></i></a>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <table class="table table-bordered">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>Item</th>
                                                <th>Qty</th>
                                                <th>Harga</th>
                                                <th>Total</th>
                                                <th>#</th>
                                            </tr>
                                        </thead>
                                        <tbody id="loaditem"></tbody>
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
<x-modal-form id="modalbengkel" show="loadmodalbengkel" title="" />
<x-modal-form id="modalItem" show="loadmodalItem" title="" />
@endsection
@push('myscript')
<script src="{{ asset('assets/js/helper/helper.js') }}"></script>
<script>
    $(document).ready(function() {

        const form = $('#formServicekendaraan');

        function buttonDisabled() {
            $("#btnSimpan").prop('disabled', true);
            $("#btnSimpan").html(`
                <div class="spinner-border spinner-border-sm text-white me-2" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                Loading..
            `);
        }
        const select2Kendaraan = $('.select2Kendaraan');
        if (select2Kendaraan.length) {
            select2Kendaraan.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Pilih Kendaraan',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

        const select2KodeItem = $('.select2KodeItem');
        if (select2KodeItem.length) {
            select2KodeItem.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Pilih Item',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }



        const select2Kodebengkel = $('.select2Kodebengkel');
        if (select2Kodebengkel.length) {
            select2Kodebengkel.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Pilih Bengkel',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

        function getBengkel() {
            $("#kode_bengkel").load('/bengkel/getbengkel');
        }

        function getItem() {
            $("#kode_item").load('/itemservicekendaraan/getitem');
        }

        getBengkel();
        getItem();

        $("#tambahbengkel").click(function() {
            $("#modalbengkel").modal("show");
            $("#modalbengkel").find(".modal-title").text("Input Bengkel");
            $("#loadmodalbengkel").load('/bengkel/create');
        });

        $(document).on('submit', '#formBengkel', function(e) {
            e.preventDefault();
            const nama_bengkel = $(this).find("#nama_bengkel").val();
            const alamat = $(this).find("#alamat").val();
            // alert('nama_bengkel');
            $.ajax({
                type: "POST",
                url: "/bengkel/store",
                data: {
                    _token: "{{ csrf_token() }}",
                    nama_bengkel: nama_bengkel,
                    alamat: alamat
                },
                cache: false,
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message,
                        });
                        getBengkel();
                        $("#modalbengkel").modal("hide");
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message,
                        });
                    }
                },
                error: function(response) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error: ' + response.responseJSON.message,
                    });
                }
            });
        });

        $("#tambahitemservice").click(function() {
            $("#modalItem").modal("show");
            $("#modalItem").find(".modal-title").text("Input Item");
            $("#loadmodalItem").load('/itemservicekendaraan/create');
        });

        $(document).on('submit', '#formItemservicekendaraan', function(e) {
            e.preventDefault();
            const nama_item = $(this).find("#nama_item").val();
            const jenis_item = $(this).find("#jenis_item").val();
            if (nama_item == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Nama Item harus diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $(this).find("#nama_item").focus();
                    },

                });
            } else if (jenis_item == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Jenis Item harus diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $(this).find("#jenis_item").focus();
                    },

                });
            } else {
                $.ajax({
                    type: "POST",
                    url: "/itemservicekendaraan/store",
                    data: {
                        _token: "{{ csrf_token() }}",
                        nama_item: nama_item,
                        jenis_item: jenis_item
                    },
                    cache: false,
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: response.message,
                            });
                            getItem();
                            $("#modalItem").modal("hide");
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message,
                            });
                        }
                    },
                    error: function(response) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error: ' + response.responseJSON.message,
                        });
                    }

                });
            }

        });

        $("#tambahitem").click(function() {
            const kode_item = form.find("#kode_item").val();
            const nama_item = form.find("#kode_item option:selected").text();
            const jumlah = form.find("#jumlah").val();
            const harga = form.find("#harga").val();
            const total = parseInt(convertNumber(harga)) * parseInt(convertNumber(jumlah));
            // alert(total);
            if (kode_item == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Silahkan Pilih dulu Item !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#kode_item").focus();
                    },

                });
            } else if (jumlah == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Jumlah harus diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#jumlah").focus();
                    },

                });
            } else if (harga == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Harga harus diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#harga").focus();
                    },

                });
            } else if ($("#loaditem").find(`#${kode_item}`).length > 0) {
                Swal.fire({
                    title: "Oops!",
                    text: "Item sudah ada !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#kode_item").focus();
                    },
                });
            } else {
                let newItem = `<tr id='${kode_item}'>
                    <input type="hidden" name="kode_item_service[]" value="${kode_item}"/>
                    <input type="hidden" name="harga_item_service[]" value="${harga}"/>
                    <input type="hidden" name="jumlah_item_service[]" value="${jumlah}"/>
                    <td>${kode_item} ${nama_item}</td>
                    <td>${jumlah}</td>
                    <td class="text-end">${harga}</td>
                    <td class="text-end totalharga">${convertToRupiah(total)}</td>
                    <td>
                    <a href="#" kode_item="${kode_item}" class="delete"><i class="ti ti-trash text-danger"></i></a>
                    </td>
                </tr>`;
                form.find("#loaditem").append(newItem);
                calculateTotal();
                resetForm();
            }
        });

        $(document).on('click', '.delete', function() {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $(this).closest('tr').remove();
                    calculateTotal();
                    resetForm();
                }
            })
        });

        function calculateTotal() {
            let grandTotal = 0;
            $('.totalharga').each(function() {
                grandTotal += parseFloat(convertNumber($(this).text())) || 0;
            });
            $('#grandtotal').text(convertToRupiah(grandTotal));
            $('#grandtotal_text').text(convertToRupiah(grandTotal));
        }

        function resetForm() {
            form.find('.select2Kodeitem').val('').trigger("change");
            form.find("#jumlah").val("");
            form.find("#harga").val("");
        }

        $("#saveButton").hide();
        form.find('.agreement').change(function() {
            if (this.checked) {
                form.find("#saveButton").show();
            } else {
                form.find("#saveButton").hide();
            }
        });

        form.submit(function(e) {
            const no_invoice = form.find("#no_invoice").val();
            const tanggal = form.find("#tanggal").val();
            const kode_kendaraan = form.find("#kode_kendaraan").val();
            const kode_bengkel = form.find("#kode_bengkel").val();
            if (no_invoice == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "No. Invoice harus diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#no_invoice").focus();
                    },
                });
                return false;
            } else if (tanggal == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Tanggal harus diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#tanggal").focus();
                    },
                });
                return false;
            } else if (kode_kendaraan == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Kendaraan harus diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#kode_kendaraan").focus();
                    },
                });
                return false;
            } else if (kode_bengkel == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Bengkel harus diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#kode_bengkel").focus();
                    },
                });
                return false;
            } else {
                buttonDisabled();
            }
        })
    });
</script>
@endpush
