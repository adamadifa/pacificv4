<style>
    ul.ui-autocomplete {
        z-index: 1100;
    }

    .ui-autocomplete {
        position: absolute;
        top: 100%;
        left: 0;
        z-index: 1200 !important;
        display: none;
        float: left;
        min-width: 160px;
        padding: 5px 0;
        margin: 2px 0 0;
        list-style: none;
        background-color: #ffffff;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }

    .ui-autocomplete li {
        padding: 10px;
        cursor: pointer;
    }

    .ui-autocomplete li:hover {
        background-color: #f0f0f0;
    }

    .ui-autocomplete li.ui-autocomplete-category {
        font-weight: bold;
        padding: 15px 10px;
        margin-top: 2px;
        background-color: #f0f0f0;
        border-top: 1px solid #ccc;
    }

    .ui-autocomplete li.ui-autocomplete-category a {
        text-decoration: none;
    }

    .ui-autocomplete li a {
        text-decoration: none;
    }
</style>
<form action="{{ route('pelunasanretur.store', Crypt::encrypt($no_retur)) }}" id="formPelunasanRetur" method="POST">
    @csrf
    <div class="row">
        <div class="col">
            <table class="table bordered table-hover table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th rowspan="2">Kode</th>
                        <th rowspan="2">Nama Produk</th>
                        <th colspan="3">Retur</th>
                        <th colspan="3" class="bg-success">Pelunasan</th>
                        <th colspan="3" class="bg-danger">Sisa</th>
                    </tr>
                    <tr>
                        <th class="text-center">Dus</th>
                        <th class="text-center">Pack</th>
                        <th class="text-center">Pcs</th>

                        <th class="text-center bg-success">Dus</th>
                        <th class="text-center bg-success">Pack</th>
                        <th class="text-center bg-success">Pcs</th>

                        <th class="text-center bg-danger">Dus</th>
                        <th class="text-center bg-danger">Pack</th>
                        <th class="text-center bg-danger">Pcs</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $total = 0;
                    @endphp
                    @foreach ($detail as $d)
                        @php
                            $jumlah = explode('|', convertToduspackpcsv2($d->isi_pcs_dus, $d->isi_pcs_pack, $d->jumlah));
                            $jumlah_pelunasan = explode('|', convertToduspackpcsv2($d->isi_pcs_dus, $d->isi_pcs_pack, $d->jumlah_pelunasan));
                            $sisa = $d->jumlah - $d->jumlah_pelunasan;
                            $sisa_retur = explode('|', convertToduspackpcsv2($d->isi_pcs_dus, $d->isi_pcs_pack, $sisa));
                            $jumlah_dus = $jumlah[0];
                            $jumlah_pack = $jumlah[1];
                            $jumlah_pcs = $jumlah[2];

                            $jumlah_dus_pelunasan = $jumlah_pelunasan[0];
                            $jumlah_pack_pelunasan = $jumlah_pelunasan[1];
                            $jumlah_pcs_pelunasan = $jumlah_pelunasan[2];

                            $jumlah_dus_sisa_retur = $sisa_retur[0];
                            $jumlah_pack_sisa_retur = $sisa_retur[1];
                            $jumlah_pcs_sisa_retur = $sisa_retur[2];

                        @endphp
                        <tr>
                            <td>{{ $d->kode_produk }}</td>
                            <td>{{ $d->nama_produk }}</td>
                            <td class="text-center">{{ formatAngka($jumlah_dus) }}</td>
                            <td class="text-center">{{ formatAngka($jumlah_pack) }}</td>
                            <td class="text-center">{{ formatAngka($jumlah_pcs) }}</td>
                            <td class="text-center">{{ formatAngka($jumlah_dus_pelunasan) }}</td>
                            <td class="text-center">{{ formatAngka($jumlah_pack_pelunasan) }}</td>
                            <td class="text-center">{{ formatAngka($jumlah_pcs_pelunasan) }}</td>
                            <td class="text-center">{{ formatAngka($jumlah_dus_sisa_retur) }}</td>
                            <td class="text-center">{{ formatAngka($jumlah_pack_sisa_retur) }}</td>
                            <td class="text-center">{{ formatAngka($jumlah_pcs_sisa_retur) }}</td>


                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
    <div class="row mt-3">
        <div class="col">
            <div class="row">
                <div class="col-lg-6 col-md-12 col-sm-12">
                    <div class="form-group mb-3">
                        <select name="kode_harga" id="kode_harga" class="form-select">
                            <option value="">Produk</option>
                            @foreach ($detail as $d)
                                <option jumlah="{{ $d->jumlah }}" isi_pcs_dus="{{ $d->isi_pcs_dus }}" isi_pcs_pack ="{{ $d->isi_pcs_pack }}"
                                    value="{{ $d->kode_harga }}">
                                    {{ $d->nama_produk }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-2 col-md-12 col-sm-12">
                    <x-input-with-icon name="jml_dus" icon="ti ti-box" label="Dus" align="right" />
                </div>
                <div class="col-lg-2 col-md-12 col-sm-12">
                    <x-input-with-icon name="jml_pack" icon="ti ti-box" label="Pack" align="right" />
                </div>
                <div class="col-lg-2 col-md-12 col-sm-12">
                    <x-input-with-icon name="jml_pcs" icon="ti ti-box" label="Pcs" align="right" />
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <x-input-with-icon label="No. DPB" name="no_dpb" icon="ti ti-barcode" />
                    <input type="hidden" id="no_dpb_val" name="no_dpb_val">
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <button class="btn btn-primary w-100" id="tambahproduk"><i class="ti ti-plus me-1"></i>Tambah Produk</button>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col">
            <table class="table table-bordered table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Kode</th>
                        <th>Nama Produk</th>
                        <th class="text-center">Dus</th>
                        <th class="text-center">Pack</th>
                        <th class="text-center">Pcs</th>
                        <th class="text-center">No.DPB</th>
                        <th>#</th>
                    </tr>
                </thead>
                <tbody id="loadproduk">
                    @foreach ($pelunasan as $d)
                        @php
                            $jumlah = explode('|', convertToduspackpcsv2($d->isi_pcs_dus, $d->isi_pcs_pack, $d->jumlah));
                            $jumlah_dus = $jumlah[0];
                            $jumlah_pack = $jumlah[1];
                            $jumlah_pcs = $jumlah[2];
                        @endphp
                        <tr id="index_{{ $d->kode_harga }}">
                            <td>
                                <input type="hidden" name="kode_harga_item[]" value="{{ $d->kode_harga }}">
                                <input type="hidden" name="jml_item[]" value="{{ $d->jumlah }}">

                                <input type="hidden" name="isi_pcs_pack_item[]" value="{{ $d->isi_pcs_pack }}">
                                <input type="hidden" name="isi_pcs_dus_item[]" value="{{ $d->isi_pcs_dus }}">
                                <input type="hidden" name="no_dpb_item[]" value="{{ $d->no_dpb }}">
                                {{ $d->kode_harga }}
                            </td>
                            <td>{{ $d->nama_produk }}</td>
                            <td class="text-center">{{ formatAngka($jumlah_dus) }}</td>
                            <td class="text-center">{{ formatAngka($jumlah_pack) }}</td>
                            <td class="text-center">{{ formatAngka($jumlah_pcs) }}</td>
                            <td class="text-center">{{ $d->no_dpb }}</td>
                            <td>
                                <a href="#" key="{{ $d->kode_harga }}" class="delete"><i class="ti ti-trash text-danger"></i></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="row mt-3"></div>
    <div class="col">
        <button class="btn btn-primary w-100" id="btnSimpan"><i class="ti ti-send me-1"></i>Submit</button>
    </div>
    </div>
</form>
<script>
    $(function() {
        const formpelunasanretur = $('#formPelunasanRetur');

        $("#kode_harga").change(function() {
            const isi_pcs_pack = $(this).find('option:selected').attr('isi_pcs_pack');
            if (isi_pcs_pack == 0) {
                formpelunasanretur.find("#jml_pack").prop('disabled', true);
            } else {
                formpelunasanretur.find("#jml_pack").prop('disabled', false);
            }
            console.log(isi_pcs_pack);
        })
        $('#tambahproduk').click(function(e) {
            e.preventDefault();
            const kode_harga = formpelunasanretur.find('#kode_harga').val();
            const nama_produk = formpelunasanretur.find('#kode_harga option:selected').text();
            const jumlah = formpelunasanretur.find('#kode_harga option:selected').attr('jumlah');
            const jml_dus = formpelunasanretur.find('#jml_dus').val();
            const jml_pack = formpelunasanretur.find('#jml_pack').val();
            const jml_pcs = formpelunasanretur.find('#jml_pcs').val();

            const jmldus = jml_dus != "" ? parseInt(jml_dus) : 0;
            const jmlpack = jml_pack != "" ? parseInt(jml_pack) : 0;
            const jmlpcs = jml_pcs != "" ? parseInt(jml_pcs) : 0;

            const no_dpb = formpelunasanretur.find('#no_dpb_val').val();
            const isi_pcs_pack = formpelunasanretur.find('#kode_harga option:selected').attr('isi_pcs_pack');
            const isi_pcs_dus = formpelunasanretur.find('#kode_harga option:selected').attr('isi_pcs_dus');

            let jml = (parseInt(jmldus) * parseInt(isi_pcs_dus)) + (parseInt(jmlpack) * parseInt(isi_pcs_pack)) + parseInt(jmlpcs);

            //alert(jml);
            if (kode_harga == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Produk Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        formpelunasanretur.find("#kode_harga").focus();
                    },
                });
                return false;
            } else if (parseInt(jml) > parseInt(jumlah)) {
                Swal.fire({
                    title: "Oops!",
                    text: "Jumlah Melebihi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        formpelunasanretur.find("#jml_dus").focus();
                    },
                });
                return false;
            } else if (no_dpb == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "No. DPB Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        formpelunasanretur.find("#no_dpb").focus();
                    },
                });
                return false;
            } else if ($('#loadproduk').find('#index_' + kode_harga).length > 0) {
                Swal.fire({
                    title: "Oops!",
                    text: "Data Sudah Ada!",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $("#kode_harga").focus();
                    },

                });
            } else {
                let produk = ` <tr id="index_${kode_harga}">
                    <td>
                        <input type="hidden" name="kode_harga_item[]" value="${kode_harga}">
                        <input type="hidden" name="jml_item[]" value="${jml}">

                        <input type="hidden" name="isi_pcs_pack_item[]" value="${isi_pcs_pack}">
                        <input type="hidden" name="isi_pcs_dus_item[]" value="${isi_pcs_dus}">
                        <input type="hidden" name="no_dpb_item[]" value="${no_dpb}">
                        ${kode_harga}
                    </td>
                    <td>${nama_produk}</td>
                    <td class="text-center">${jml_dus}</td>
                    <td class="text-center">${jml_pack}</td>
                    <td class="text-center">${jml_pcs}</td>
                    <td class="text-center">${no_dpb}</td>
                    <td>
                         <a href="#" key="${kode_harga}" class="delete"><i class="ti ti-trash text-danger"></i></a>
                    </td>
                </tr>`;
                $('#loadproduk').append(produk);
                formpelunasanretur.find('#kode_harga').val('');
                formpelunasanretur.find('#no_dpb').val('');
                formpelunasanretur.find('#no_dpb_val').val('');
                formpelunasanretur.find('#jml_dus').val('');
                formpelunasanretur.find('#jml_pack').val('');
                formpelunasanretur.find('#jml_pcs').val('');
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


        $("#no_dpb").autocomplete({
            source: function(request, response) {
                // Fetch data
                $.ajax({
                    url: "/dpb/getautocompletedpb",
                    type: 'post',
                    dataType: "json",
                    data: {
                        _token: "{{ csrf_token() }}",
                        search: request.term,

                    },
                    success: function(data) {
                        response(data);
                    }
                });
            },
            select: function(event, ui) {
                $("#no_dpb").val(ui.item.label);
                $("#no_dpb_val").val(ui.item.val);
                var no_dpb = ui.item.val;
                return false;
            }
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

        formpelunasanretur.submit(function(e) {
            // e.preventDefault();
            if ($('#loadproduk tr').length == 0) {
                SwalWarning('nama_produk', 'Detail Produk Tidak Boleh Kosong');
                return false;
            } else {
                buttonDisable();
            }
        });


    });
</script>
