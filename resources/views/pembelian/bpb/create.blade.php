@extends('layouts.app')
@section('titlepage', 'Input BPPB')

@section('content')
@section('navigasi')
    <span>Input BPPB</span>
@endsection

<div class="modal fade" id="modalBarang" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel18">Data Barang</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table" id="tabelbarang" width="100%">
                        <thead class="table-dark">
                            <tr>
                                <th>Kode</th>
                                <th>Nama Barang</th>
                                <th>Satuan</th>
                                <th>Jenis Barang</th>
                                <th>Kategori</th>
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

<div class="row">
    <div class="col-lg-12">
        <div class="nav-align-top nav-tabs-shadow mb-4">
            <div class="tab-content">

                <form action="{{ route('bpbpembelian.store') }}" method="post" id="formcreatebpb">
                    @csrf
                    <div class="row">
                        <div class="col-12">
                            <x-input-with-icon icon="ti ti-calendar" label="Tanggal" name="tanggal"
                                value="{{ Date('Y-m-d') }}" datepicker="flatpickr-date" />
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
                        <div class="col-12">
                            <a href="#" class="btn btn-primary w-100 btnCariBarang"><i
                                    class="ti ti-plus me-1"></i>Cari
                                Barang</a>
                        </div>
                    </div>
                    {{-- <select class="form-select select2KodeDept" name="tujuan" id="tujuan">
        <option value="">Pilih Tujuan</option>
        <option value="GDL" {{ Request('tujuan') == 'GDL' ? 'selected' : '' }}>Gudang Logistik</option>
        <option value="GDB" {{ Request('tujuan') == 'GDB' ? 'selected' : '' }}>Gudang Bahan</option>
        <option value="GAF" {{ Request('tujuan') == 'GAF' ? 'selected' : '' }}>General Afair</option>
    </select> --}}
                    <div class="divider text-start">
                        <div class="divider-text">Detail Barang</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <input type="text" id="searchBarang" class="form-control mb-2"
                                placeholder="Cari kode atau nama barang...">
                        </div>
                        <div class="col-md-6">
                            <select id="filterKategori" class="form-control">
                                <option value="">-- Filter Kategori --</option>
                                @foreach ($kategori as $k)
                                    <option value="{{ $k->kode_kategori }}">
                                        {{ textUpperCase($k->nama_kategori) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <table class="table table-bordered" id="tableBarang">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center">
                                    <input type="checkbox" id="checkAllBarang">
                                </th>
                                <th>Kode</th>
                                <th>Nama Barang</th>
                                <th>Jenis Barang</th>
                                <th>Kategori</th>
                                <th>Satuan</th>
                                <th style="width:15%">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($barang as $b)
                                <tr data-kode_kategori="{{ $b->kode_kategori }}">
                                    <td class="text-center">
                                        <input type="checkbox" class="pilih-barang" data-kode="{{ $b->kode_barang }}"
                                            data-nama="{{ $b->nama_barang }}" data-ket="{{ $b->keterangan }}"
                                            data-satuan="{{ $b->satuan }}" data-sisa="{{ $b->sisa }}">
                                    </td>
                                    <td>{{ $b->kode_barang }}</td>
                                    <td>{{ strtoupper($b->nama_barang) }}</td>
                                    <td>{{ strtoupper($b->kode_group) }}</td>
                                    <td>{{ strtoupper($b->nama_kategori) }}</td>
                                    <td>{{ strtoupper($b->satuan) }}</td>
                                    <td>
                                        <input type="text"
                                            class="form-control form-control-sm jumlah-barang number-separator"
                                            value="{{ $b->sisa }}" disabled>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{-- <x-input-with-icon icon="ti ti-file-description" label="Keterangan" name="keterangan" /> --}}
                    <a href="#" class="btn btn-primary w-100" id="tambahproduk"><i
                            class="ti ti-plus me-1"></i>Tambah Produk</a>
                    <div class="row mt-2">
                        <div class="col">
                            <table class="table table-bordered" id="tabledetail">
                                <thead class="table-dark">
                                    <tr>
                                        <th style="width: 10%">Kode</th>
                                        <th style="width: 30%">Nama Barang</th>
                                        <th style="width: 5%">Jumlah</th>
                                        <th>Keterangan</th>
                                        <th style="width: 5%">#</th>
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
                                <input class="form-check-input agreement" name="aggrement" value="aggrement"
                                    type="checkbox" value="" id="defaultCheck3">
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

        let tableBarang = null; // Variabel global untuk menyimpan instance DataTable

        // 🔥 Fungsi load DataTable dengan proper destroy
        function loadTablebarang(kode_group = "000") {
            // Destroy jika sudah ada instance sebelumnya

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
                destroy: true, // ✅ Gunakan 'destroy', bukan 'bDestroy'
                columns: [{
                        data: 'kode_barang',
                        name: 'kode_barang',
                        width: '10%'
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
                        width: '20%'
                    },
                    {
                        data: 'nama_kategori',
                        name: 'nama_kategori',
                        width: '20%'
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
                    // Highlight jika barang sudah ada di keranjang
                    if ($('#index_' + data.kode_barang).length > 0) {
                        $(row).addClass('in-cart').css('opacity', '0.6');
                    }
                }
            });
        }



        // 🔥 Tombol "Cari Barang" membuka modal
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
                if (kode_group = 'GDG') {
                    kode_group = 'GDL';
                } else {
                    kode_group = "{{ Auth::user()->kode_dept }}";
                }

            }

            // Reset modal sebelum dibuka
            $('#searchBarang').val('');
            $('#tableBarang tbody tr').show().removeClass('in-cart');
            $('.pilih-barang').prop('checked', false);
            $('.jumlah-barang').prop('disabled', true).val('');
            // Load DataTable & tampilkan modal
            loadTablebarang(kode_group);

            $('#modalBarang').modal('show');
        });

        // 🔥 Pastikan DataTable reload saat modal benar-benar terbuka
        $('#modalBarang').on('shown.bs.modal', function() {
            if (tableBarang) {
                tableBarang.ajax.reload();
            }
        });

        // 🔥 Reset saat modal ditutup (opsional, untuk kebersihan)
        $('#modalBarang').on('hidden.bs.modal', function() {
            if (tableBarang) {
                tableBarang.clear().destroy();
                tableBarang = null;
            }
        });

        // 🔥 Event pilih barang dari DataTable (jika pakai render action)
        $('#tabelbarang tbody').on('click', '.pilihBarang', function(e) {
            e.preventDefault();
            const kode_barang = $(this).attr('kode_barang');
            const nama_barang = $(this).attr('nama_barang');

            // Contoh: tambahkan ke keranjang/detail
            tambahKeKeranjang(kode_barang, nama_barang);

            // Opsional: tutup modal atau biarkan terbuka untuk pilih lagi
            // $('#modalBarang').modal('hide');
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
                        ${kode}
                    </td>
                    <td>${nama.toUpperCase()}</td>
                    <td>
                        <input type="text"
                            class="form-control form-control-sm number-separator"
                            name="jml[]"
                            value="1">
                    </td>
                    <td>
                        <input type="text"
                            class="form-control form-control-sm"
                            name="ket[]"
                            placeholder="Keterangan...">
                    </td>
                    <td class="text-center">
                        <a href="#" class="hapus" data-kode="${kode}">
                            <i class="ti ti-trash text-danger"></i>
                        </a>
                    </td>
                </tr>
            `;

            $('#loaddetail').append(html);
            $('#modalBarang').modal('hide');
        }



        function addProduk() {
            const dataBarang = formCreate.find("#kode_barang :selected").select2(this.data);
            const kode_barang = $(dataBarang).val();
            const nama_barang = $(dataBarang).text().split("|");
            const jumlah = formCreate.find("#jumlah").val();
            const jml = jumlah.replaceAll(".", "").replaceAll(",", ".");
            const keterangan = formCreate.find("#keterangan").val();

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
            $("#jumlah").val("");
            $("#harga").val("");
            $("#keterangan").val("");
            $("#kode_barang").focus();
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

                // 🚫 jangan tampilkan yang sudah di keranjang
                if (row.hasClass('in-cart')) {
                    row.hide();
                    return;
                }

                // 🔍 filter normal
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

                // 🔥 TANDAI SUDAH DI KERANJANG
                row.addClass('in-cart').hide();

                if ($('#index_' + kode).length === 0) {
                    let html = `
                    <tr id="index_${kode}">
                        <td>
                            <input type="hidden" name="kode_barang[]" value="${kode}">
                            ${kode}
                        </td>
                        <td>${nama.toUpperCase()} (${satuan.toUpperCase()})</td>
                        <td>
                            <input type="text"
                                class="form-control form-control-sm number-separator jml-keranjang"
                                name="jml[]"
                                value="${sisa}"
                                data-sisa="${sisa}"
                                data-kode="${kode}">
                        </td>
                        <td>
                            <input type="text"
                                class="form-control form-control-sm"
                                name="ket[]" value="${ket ?? ''}"
                                placeholder="Keterangan...">
                        </td>
                        <td class="text-center">
                            <a href="#" class="hapus" data-kode="${kode}">
                                <i class="ti ti-trash text-danger"></i>
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
            const kode = $(this).data('kode');

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

                // ❌ lewati yang sudah di keranjang
                if (row.hasClass('in-cart')) return;

                // ❌ lewati yang lagi tersembunyi karena search
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
