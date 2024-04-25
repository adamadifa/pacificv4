<form action="{{ route('barangmasukgudanglogistik.update',Crypt::encrypt($barangmasuk->no_bukti)) }}" method="post" id="formeditbarangmasukgudanglogistik">
    @csrf
    @method('PUT')
    <x-input-with-icon icon="ti ti-barcode" label="No. Bukti Pemasukan" name="no_bukti" value="{{ $barangmasuk->no_bukti }}" />
    <x-input-with-icon icon="ti ti-calendar" label="Tanggal" name="tanggal" datepicker="flatpickr-date" value="{{ $barangmasuk->tanggal }}" />
    <div class="divider text-start">
        <div class="divider-text">Detail Barang</div>
    </div>
    <div class="row">
        <div class="col-lg-6 col-md-12 col-sm-12">
            <x-select label="Pilih Barang" name="kode_barang" :data="$barang" key="kode_barang" textShow="nama_barang"
                upperCase="true" select2="select2Kodebarang" showKey="true" />
        </div>
        <div class="col-lg-2 col-md-12 col-sm-12">
            <x-input-with-icon icon="ti ti-box" label="Jumlah" name="jumlah" align="right" numberFormat="true" />
        </div>
        <div class="col-lg-4 col-md-12 col-sm-12">
            <x-input-with-icon icon="ti ti-box" label="Harga" name="harga" align="right" numberFormat="true" />
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
                        <th>Harga</th>
                        <th>Total</th>
                        <th style="width: 20%">Keterangan</th>
                        <th>#</th>
                    </tr>
                </thead>
                <tbody id="loaddetail">
                    @foreach ($detail as $d)
                    <tr id="index_{{ $d->kode_barang }}">
                        <td>
                           <input type="hidden" name="kode_barang[]" value="{{ $d->kode_barang }}">
                           {{ $d->kode_barang }}
                        </td>
                        <td>{{ textUpperCase($d->nama_barang) }}</td>
                        <td class="text-end">
                           <input type="hidden" name="jml[]" value="{{ $d->jumlah }}">
                           {{ formatAngkaDesimal($d->jumlah) }}
                        </td>
                        <td class="text-end">
                           <input type="hidden" name="harga[]" value="{{ $d->harga }}">
                           {{ formatAngkaDesimal($d->harga) }}
                        </td>
                        <td class="text-end">
                          @php
                            $subtotal = $d->jumlah * $d->harga;
                          @endphp
                           {{ formatAngkaDesimal($subtotal) }}
                        </td>
                        <td>
                           <input type="hidden" name="ket[]" value="{{ $d->keterangan }}">
                           {{ $d->keterangan }}
                        </td>
                        <td class="text-center">
                           <a href="#" kode_barang="{{ $d->kode_barang }}" class="delete"><i
                                 class="ti ti-trash text-danger"></i></a>
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
                <input class="form-check-input agreement" name="aggrement" value="aggrement" type="checkbox"
                    value="" id="defaultCheck3">
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
        const form = $("#formeditbarangmasukgudanglogistik");
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
            const harga = form.find("#harga").val();
            const jml = jumlah.replaceAll(".", "").replaceAll(",",".");
            const hrg = harga.replaceAll(".", "").replaceAll(",",".");
            const subtotal = convertToRupiah( parseFloat(jml) * parseFloat(hrg));
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
                        <td class="text-end">
                            <input type="hidden" name="harga[]" value="${harga}" class="noborder-form text-end harga" />
                            ${harga}
                        </td>
                        <td class="text-end">
                            <input type="hidden" name="subtotal[]" value="${subtotal}" class="noborder-form text-end subtotal" />
                            ${subtotal}
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
            $("#keterangan").val("");
            $("#kode_barang").focus();
        }

        form.find("#tambahproduk").click(function(e) {
            e.preventDefault();
            const kode_barang = form.find("#kode_barang").val();
            const jumlah = form.find("#jumlah").val();
            const harga = form.find("#harga").val();
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

            }else if(jumlah==""){
                Swal.fire({
                    title: "Oops!",
                    text: "Jumlah Harus Diisi  !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#jumlah").focus();
                    },

                });

            }else if(harga==""){
                Swal.fire({
                    title: "Oops!",
                    text: "Harga Harus Diisi  !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#harga").focus();
                    },

                });

            } else {
                form.find("#tambahproduk").prop('disabled', true);
                if (form.find('#tabledetail').find('#index_' + kode_barang).length > 0) {
                    Swal.fire({
                        title: "Oops!",
                        text: "Data Sudah Ada!",
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: (e) => {
                            form.find("#kode_produk").focus();
                        },

                    });
                } else {
                    addProduk();
                }
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
            const kode_asal_barang = form.find("#kode_asal_barang").val();
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
            } else if (kode_asal_barang == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Asal Barang Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#kode_asal_barang").focus();
                    },
                });

                return false;
            }
        });
    });
</script>
