<form action="{{ route('barangmasukgudangbahan.update', Crypt::encrypt($barangmasuk->no_bukti)) }}" method="post" id="formcreateBarangmasukgudangbahan">
    @csrf
    @method('PUT')
    <x-input-with-icon icon="ti ti-barcode" label="No. Bukti Pemasukan" name="no_bukti" value="{{ $barangmasuk->no_bukti }}" />
    <x-input-with-icon icon="ti ti-calendar" label="Tanggal" name="tanggal" datepicker="flatpickr-date" value="{{ $barangmasuk->tanggal }}" />
    <div class="form-group mb-3">
        <select name="kode_asal_barang" id="kode_asal_barang" class="form-select">
            <option value="">Asal Barang</option>
            @foreach ($list_asal_barang as $d)
                <option value="{{ $d['kode_asal_barang'] }}" {{ $barangmasuk->kode_asal_barang == $d['kode_asal_barang'] ? 'selected' : '' }}>
                    {{ $d['asal_barang'] }}</option>
            @endforeach
        </select>
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
    <a href="#" class="btn btn-primary w-100" id="tambahproduk"><i class="ti ti-plus me-1"></i>Tambah
        Produk</a>
    <div class="row mt-2">
        <div class="col">
            <table class="table table-bordered" id="tabledetail">
                <thead class="table-dark">
                    <tr>
                        <th style="width: 10%">Kode</th>
                        <th style="width: 25%">Nama Barang</th>
                        <th>Qty Unit</th>
                        <th>Qty Berat</th>
                        <th>Qty Lebih</th>
                        <th style="width: 20%">Keterangan</th>
                        <th>#</th>
                    </tr>
                </thead>
                <tbody id="loaddetail">
                    @foreach ($detail as $d)
                        @php
                            $index = rand(10, 10000);
                        @endphp
                        <tr id="index_{{ $index }}">
                            <td>
                                <input type="hidden" name="kode_barang[]" value="{{ $d->kode_barang }}">
                                {{ $d->kode_barang }}
                            </td>
                            <td>{{ textUpperCase($d->nama_barang) }}</td>
                            <td class="text-end">
                                <input type="text" name="qty_unit[]" value="{{ formatAngkaDesimal($d->qty_unit) }}"
                                    class="noborder-form text-end qty_unit number-separator">
                                {{-- {{ formatAngkaDesimal($d->qty_unit) }} --}}
                            </td>
                            <td class="text-end">
                                <input type="text" name="qty_berat[]" value="{{ formatAngkaDesimal($d->qty_berat) }}"
                                    class="noborder-form text-end qty_berat number-separator">
                                {{-- {{ formatAngkaDesimal($d->qty_berat) }} --}}
                            </td>
                            <td class="text-end">
                                <input type="text" name="qty_lebih[]" value="{{ formatAngkaDesimal($d->qty_lebih) }}"
                                    class="noborder-form text-end qty_lebih number-separator">
                                {{-- {{ formatAngkaDesimal($d->qty_lebih) }} --}}
                            </td>
                            <td>
                                <input type="hidden" name="ket[]" value="{{ $d->keterangan }}">
                                {{ $d->keterangan }}
                            </td>
                            <td class="text-center">
                                <a href="#" kode_barang="{{ $index }}" class="delete"><i class="ti ti-trash text-danger"></i></a>
                            </td>
                        </tr>
                    @endforeach
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
        const formCreate = $("#formcreateBarangmasukgudangbahan");
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
            const kode_asal_barang = formCreate.find("#kode_asal_barang").val();
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
            } else if (no_bukti == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "No. Bukti Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        formCreate.find("#no_bukti").focus();
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
            } else if (kode_asal_barang == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Asal Barang Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        formCreate.find("#kode_asal_barang").focus();
                    },
                });

                return false;
                a
            }
        });
    });
</script>
