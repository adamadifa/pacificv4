@extends('layouts.app')
@section('titlepage', 'Input BPPB')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Input BPPB (Bukti Permintaan & Penerimaan Barang)</h4>
            <small class="text-muted">Buat pengajuan Bukti Permintaan & Penerimaan Barang (BPPB) Pembelian baru.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-folder me-1"></i>Pembelian</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('bpbpembelian.index') }}"><i class="ti ti-receipt me-1"></i>BPPB</a>
                </li>
                <li class="breadcrumb-item active"><i class="ti ti-plus me-1"></i>Input BPPB</li>
            </ol>
        </nav>
    </div>
@endsection

<div class="modal fade" id="modalBarang" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
    aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-bottom py-3" style="background-color: #002e65;">
                <h5 class="modal-title text-white" id="myModalLabel18"><i class="ti ti-box me-1"></i> Data Barang</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body pt-3">
                <div class="table-responsive text-nowrap rounded border">
                    <table class="table table-hover table-bordered align-middle mb-0" id="tabelbarang" width="100%">
                        <thead style="background-color: #002e65;">
                            <tr>
                                <th class="text-white" style="width: 15%">Kode</th>
                                <th class="text-white">Nama Barang</th>
                                <th class="text-white" style="width: 10%">Satuan</th>
                                <th class="text-white" style="width: 20%">Jenis Barang</th>
                                <th class="text-white" style="width: 20%">Kategori</th>
                                <th class="text-white text-center" style="width: 10%">#</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card shadow-sm border">
            <div class="card-body">
                <form action="{{ route('bpbpembelian.store') }}" method="post" id="formcreatebpb">
                    @csrf

                    <div class="row g-3 align-items-end mb-4">
                        <div class="col-md-6 col-sm-12">
                            <x-input-with-icon icon="ti ti-calendar" label="Tanggal" name="tanggal"
                                value="{{ Date('Y-m-d') }}" datepicker="flatpickr-date" />
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <button type="button"
                                class="btn btn-primary w-100 btnCariBarang d-flex align-items-center justify-content-center gap-1"
                                style="height: 38px;">
                                <i class="ti ti-search"></i> <span>Cari Barang (Modal)</span>
                            </button>
                        </div>
                        <div class="col-6" hidden>
                            <select name="kode_supplier" class="form-control select2KodeSupplier">
                                <option value=""></option>
                                @foreach ($supplier as $s)
                                    <option value="{{ $s->kode_supplier }}">
                                        {{ textUpperCase($s->nama_supplier) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="divider text-start mt-4 mb-4">
                        <div class="divider-text fw-bold text-dark fs-6"><i class="ti ti-box me-1 text-primary"></i>
                            Detail Barang</div>
                    </div>

                    {{-- Daftar Stok Barang Card --}}
                    <div class="card shadow-sm border mb-4">
                        <div class="card-header py-3"
                            style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                            <h6 class="m-0 fw-bold text-white d-flex align-items-center gap-2">
                                <i class="ti ti-box fs-4"></i>
                                <span>Daftar Stok Barang</span>
                            </h6>
                        </div>
                        <div class="card-body pt-3">
                            <div class="row g-2 mb-3">
                                <div class="col-md-6 col-sm-12">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="ti ti-search text-muted"></i></span>
                                        <input type="text" id="searchBarang" class="form-control"
                                            placeholder="Cari kode atau nama barang...">
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <select id="filterKategori" class="form-select">
                                        <option value="">-- Filter Kategori --</option>
                                        @foreach ($kategori as $k)
                                            <option value="{{ $k->kode_kategori }}">
                                                {{ textUpperCase($k->nama_kategori) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="table-responsive text-nowrap rounded border">
                                <table class="table table-sm table-hover table-bordered align-middle mb-0"
                                    id="tableBarang">
                                    <thead style="background-color: #002e65;">
                                        <tr>
                                            <th class="text-white text-center" style="width: 5%">
                                                <input type="checkbox" id="checkAllBarang" class="form-check-input">
                                            </th>
                                            <th class="text-white" style="width: 15%">Kode</th>
                                            <th class="text-white">Nama Barang</th>
                                            <th class="text-white" style="width: 15%">Jenis Barang</th>
                                            <th class="text-white" style="width: 15%">Kategori</th>
                                            <th class="text-white" style="width: 10%">Satuan</th>
                                            <th class="text-white text-end" style="width: 15%">Jumlah</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($barang as $b)
                                            <tr data-kode_kategori="{{ $b->kode_kategori }}">
                                                <td class="text-center">
                                                    <input type="checkbox" class="form-check-input pilih-barang"
                                                        data-kode="{{ $b->kode_barang }}"
                                                        data-nama="{{ $b->nama_barang }}"
                                                        data-ket="{{ $b->keterangan }}"
                                                        data-satuan="{{ $b->satuan }}"
                                                        data-sisa="{{ $b->sisa }}">
                                                </td>
                                                <td><span
                                                        class="badge bg-secondary text-white font-monospace shadow-xs">{{ $b->kode_barang }}</span>
                                                </td>
                                                <td class="fw-semibold text-dark">{{ strtoupper($b->nama_barang) }}
                                                </td>
                                                <td>{{ strtoupper($b->kode_group) }}</td>
                                                <td>{{ strtoupper($b->nama_kategori) }}</td>
                                                <td><span
                                                        class="badge bg-info text-white shadow-xs">{{ strtoupper($b->satuan) }}</span>
                                                </td>
                                                <td>
                                                    <input type="text"
                                                        class="form-control form-control-sm text-end jumlah-barang number-separator"
                                                        value="{{ $b->sisa }}" style="border-color: #cbd5e1;"
                                                        disabled>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- Daftar Barang yang Diajukan Card --}}
                    <div class="card shadow-sm border mb-4">
                        <div class="card-header py-3"
                            style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                            <h6 class="m-0 fw-bold text-white d-flex align-items-center gap-2">
                                <i class="ti ti-list fs-4"></i>
                                <span>Daftar Barang yang Diajukan</span>
                            </h6>
                        </div>
                        <div class="table-responsive text-nowrap">
                            <table class="table table-sm table-hover table-bordered align-middle mb-0"
                                id="tabledetail">
                                <thead style="background-color: #002e65;">
                                    <tr>
                                        <th class="text-white" style="width: 15%">Kode</th>
                                        <th class="text-white" style="width: 35%">Nama Barang</th>
                                        <th class="text-white text-end" style="width: 15%">Jumlah</th>
                                        <th class="text-white">Keterangan</th>
                                        <th class="text-white text-center" style="width: 10%">#</th>
                                    </tr>
                                </thead>
                                <tbody id="loaddetail" class="table-border-bottom-0">
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-12">
                            <div class="form-check mt-3 mb-3">
                                <input class="form-check-input agreement" name="aggrement" value="aggrement"
                                    type="checkbox" id="defaultCheck3">
                                <label class="form-check-label fw-semibold text-dark" for="defaultCheck3"> Yakin Akan
                                    Disimpan ? </label>
                            </div>
                            <div class="form-group" id="saveButton">
                                <button
                                    class="btn btn-success w-100 d-flex align-items-center justify-content-center gap-1"
                                    type="submit" id="btnSimpan">
                                    <i class="ti ti-send"></i> <span>Submit</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('myscript')
<script>
    $(function() {
        const formCreate = $("#formcreatebpb");
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

        $('.select2KodeSupplier')
            .wrap('<div class="position-relative"></div>')
            .select2({
                placeholder: 'Pilih Supplier',
                allowClear: true,
                dropdownParent: $('.select2KodeSupplier').parent()
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

        const select2Kodebarang = formCreate.find('.select2Kodebarang');
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

        let tableBarang = null;

        function loadTablebarang(kode_group = "000") {
            if (tableBarang) {
                tableBarang.clear().destroy();
                $('#tabelbarang tbody').empty();
            }

            tableBarang = $('#tabelbarang').DataTable({
                processing: true,
                serverSide: true,
                order: [
                    [0, 'asc']
                ],
                ajax: `/barangpembelian/${kode_group}/getbarangjson`,
                bAutoWidth: false,
                destroy: true,
                columns: [{
                        data: 'kode_barang',
                        name: 'kode_barang',
                        width: '15%'
                    },
                    {
                        data: 'namabarang',
                        name: 'nama_barang',
                        width: '40%'
                    },
                    {
                        data: 'satuan',
                        name: 'satuan',
                        width: '10%'
                    },
                    {
                        data: 'jenisbarang',
                        name: 'jenisbarang',
                        width: '15%'
                    },
                    {
                        data: 'nama_kategori',
                        name: 'nama_kategori',
                        width: '15%'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        width: '5%',
                        render: function(data, type, row) {
                            return `<button class="btn btn-sm btn-primary pilihBarang"
                                kode_barang="${row.kode_barang}"
                                nama_barang="${row.namabarang}">Pilih</button>`;
                        }
                    }
                ],
                rowCallback: function(row, data, index) {
                    if ($('#index_' + data.kode_barang).length > 0) {
                        $(row).addClass('in-cart').css('opacity', '0.6');
                    }
                }
            });
        }

        $('.btnCariBarang').on('click', function(e) {
            e.preventDefault();

            let id_user = "{{ Auth::user()->id }}";
            let kode_group;

            if (id_user == '74') {
                kode_group = "MKT";
            } else if (id_user == '54') {
                kode_group = "000";
            } else {
                kode_group = "{{ Auth::user()->kode_dept }}";
                if (kode_group == 'GDG') {
                    kode_group = 'GDL';
                }
            }

            $('#searchBarang').val('');
            $('#tableBarang tbody tr').show().removeClass('in-cart');
            $('.pilih-barang').prop('checked', false);
            $('.jumlah-barang').prop('disabled', true).val('');
            loadTablebarang(kode_group);
            $('#modalBarang').modal('show');
        });

        $('#modalBarang').on('shown.bs.modal', function() {
            if (tableBarang) {
                tableBarang.ajax.reload();
            }
        });

        $('#modalBarang').on('hidden.bs.modal', function() {
            if (tableBarang) {
                tableBarang.clear().destroy();
                tableBarang = null;
            }
        });

        $('#tabelbarang tbody').on('click', '.pilihBarang', function(e) {
            e.preventDefault();
            const kode_barang = $(this).attr('kode_barang');
            const nama_barang = $(this).attr('nama_barang');
            tambahKeKeranjang(kode_barang, nama_barang);
        });

        function tambahKeKeranjang(kode, nama) {
            if ($('#index_' + kode).length > 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Barang sudah dipilih'
                });
                return;
            }

            let html = `
                <tr id="index_${kode}">
                    <td>
                        <input type="hidden" name="kode_barang[]" value="${kode}">
                        <span class="badge bg-secondary text-white font-monospace shadow-xs">${kode}</span>
                    </td>
                    <td class="fw-semibold text-dark">${nama.toUpperCase()}</td>
                    <td>
                        <input type="text"
                            class="form-control form-control-sm text-end number-separator"
                            name="jml[]"
                            style="border-color: #cbd5e1;"
                            value="1">
                    </td>
                    <td>
                        <input type="text"
                            class="form-control form-control-sm"
                            name="ket[]"
                            style="border-color: #cbd5e1;"
                            placeholder="Keterangan...">
                    </td>
                    <td class="text-center">
                        <a href="#" class="hapus text-danger d-inline-block p-1" data-kode="${kode}" title="Hapus">
                            <i class="ti ti-trash fs-5"></i>
                        </a>
                    </td>
                </tr>
            `;

            $('#loaddetail').append(html);
            $('#modalBarang').modal('hide');
        }

        $("#filterKategori").on("change", function() {
            let kategori = $(this).val();

            $("#tableBarang tbody tr").each(function() {
                let rowKategori = $(this).data("kode_kategori");

                if (kategori === "" || rowKategori == kategori) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });

        $('#searchBarang').on('keyup', function() {
            let keyword = $(this).val().toLowerCase();

            $('#tableBarang tbody tr').each(function() {
                const row = $(this);

                if (row.hasClass('in-cart')) {
                    row.hide();
                    return;
                }

                row.toggle(
                    row.text().toLowerCase().indexOf(keyword) > -1
                );
            });
        });

        $(document).on('change', '.pilih-barang', function() {
            const row = $(this).closest('tr');
            const kode = $(this).data('kode');
            const nama = $(this).data('nama');
            const satuan = $(this).data('satuan');
            const sisa = $(this).data('sisa');
            const jumlahInput = row.find('.jumlah-barang');
            const ket = $(this).data('ket');

            if (this.checked) {
                jumlahInput.prop('disabled', false).val(sisa);
                row.addClass('in-cart').hide();

                if ($('#index_' + kode).length === 0) {
                    let html = `
                    <tr id="index_${kode}">
                        <td>
                            <input type="hidden" name="kode_barang[]" value="${kode}">
                            <span class="badge bg-secondary text-white font-monospace shadow-xs">${kode}</span>
                        </td>
                        <td class="fw-semibold text-dark">${nama.toUpperCase()} (${satuan.toUpperCase()})</td>
                        <td>
                            <input type="text"
                                class="form-control form-control-sm text-end number-separator jml-keranjang"
                                name="jml[]"
                                value="${sisa}"
                                data-sisa="${sisa}"
                                data-kode="${kode}"
                                style="border-color: #cbd5e1;">
                        </td>
                        <td>
                            <input type="text"
                                class="form-control form-control-sm"
                                name="ket[]" value="${ket ?? ''}"
                                style="border-color: #cbd5e1;"
                                placeholder="Keterangan...">
                        </td>
                        <td class="text-center">
                            <a href="#" class="hapus text-danger d-inline-block p-1" data-kode="${kode}" title="Hapus">
                                <i class="ti ti-trash fs-5"></i>
                            </a>
                        </td>
                    </tr>
                    `;
                    $('#loaddetail').append(html);
                }
            }
        });

        $(document).on('keyup change', '.jml-keranjang', function() {
            let value = $(this).val().replaceAll('.', '');
            let jumlah = parseInt(value || 0);
            const sisa = parseInt($(this).data('sisa'));

            if (jumlah > sisa) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Jumlah Melebihi Sisa',
                    text: `Maksimal ${sisa}`,
                });
                jumlah = sisa;
                $(this).val(sisa);
            }

            if (jumlah <= 0) {
                $(this).val(1);
            }
        });

        $('#checkAllBarang').on('change', function() {
            const checked = this.checked;

            $('#tableBarang tbody tr').each(function() {
                const row = $(this);

                if (row.hasClass('in-cart')) return;
                if (!row.is(':visible')) return;

                const checkbox = row.find('.pilih-barang');

                if (checked && !checkbox.prop('checked')) {
                    checkbox.prop('checked', true).trigger('change');
                }

                if (!checked && checkbox.prop('checked')) {
                    checkbox.prop('checked', false);
                }
            });
        });

        $(document).on('change', '.pilih-barang', function() {
            const totalVisible = $('#tableBarang tbody tr:visible:not(.in-cart)').length;
            const checkedVisible = $('#tableBarang tbody tr:visible:not(.in-cart)')
                .find('.pilih-barang:checked').length;

            $('#checkAllBarang').prop('checked', totalVisible > 0 && totalVisible === checkedVisible);
        });

        function toggleCheckAll() {
            $('#checkAllBarang').prop(
                'disabled',
                $('#tableBarang tbody tr:visible:not(.in-cart)').length === 0
            );
        }

        $('#searchBarang').on('keyup', toggleCheckAll);

        $(document).on('keyup change', '.jumlah-barang', function() {
            const row = $(this).closest('tr');
            const kode = row.find('.pilih-barang').data('kode');
            const sisa = row.find('.pilih-barang').data('sisa');
            let jumlah = parseFloat($(this).val().replaceAll('.', '') || 0);

            if (jumlah > sisa) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Jumlah Melebihi Sisa',
                    text: `Maksimal ${sisa}`,
                });
                jumlah = sisa;
                $(this).val(sisa);
            }

            $('#index_' + kode).find('.jml-text').text(jumlah);
            $('#index_' + kode).find('.jml-hidden').val(jumlah);
        });

        $(document).on('click', '.hapus', function(e) {
            e.preventDefault();
            const kode = $(this).data('kode');

            $('#index_' + kode).remove();

            const row = $(`.pilih-barang[data-kode="${kode}"]`).closest('tr');
            row.removeClass('in-cart')
                .show()
                .find('.pilih-barang')
                .prop('checked', false);

            row.find('.jumlah-barang').prop('disabled', true).val('');
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
            const no_bpb = formCreate.find("#no_bpb").val();
            const tanggal = formCreate.find("#tanggal").val();
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
            } else if (no_bpb == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "No. Bukti Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        formCreate.find("#no_bpb").focus();
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
            }
        });
    });
</script>
@endpush
