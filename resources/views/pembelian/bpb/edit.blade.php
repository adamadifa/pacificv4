<form action="{{ route('bpbpembelian.update', Crypt::encrypt($bpb->no_bpb)) }}" method="post" id="formeditbpb">
    @csrf
    @method('PUT')
    
    <div class="row mb-3">
        <div class="col-md-6 col-sm-12">
            <x-input-with-icon icon="ti ti-barcode" label="No. BPB" name="no_bpb" value="{{ $bpb->no_bpb }}" readonly />
        </div>
        <div class="col-md-6 col-sm-12">
            <x-input-with-icon icon="ti ti-calendar" label="Tanggal" name="tanggal" datepicker="flatpickr-date"
                value="{{ $bpb->tanggal }}" />
        </div>
    </div>
        
    <div class="divider text-start mt-4 mb-4">
        <div class="divider-text fw-bold text-dark fs-6"><i class="ti ti-box me-1 text-primary"></i> Detail Barang</div>
    </div>
    
    <div class="row g-3 align-items-end mb-4">
        <div class="col-lg-4 col-md-4 col-sm-12">
            <x-select label="Pilih Barang" name="kode_barang" :data="$barang" key="kode_barang" textShow="nama_barang"
                upperCase="true" select2="select2Kodebarang" showKey="true" />
        </div>
        <div class="col-lg-2 col-md-2 col-sm-6">
            <x-input-with-icon icon="ti ti-box" label="Jumlah" name="jumlah" align="right" numberFormat="true" />
        </div>
        <div class="col-lg-4 col-md-4 col-sm-6">
            <x-input-with-icon icon="ti ti-file-description" label="Keterangan" name="keterangan" />
        </div>
        <div class="col-lg-2 col-md-2 col-sm-12">
            <button type="button" class="btn btn-primary w-100 d-flex align-items-center justify-content-center gap-1" id="tambahproduk" style="height: 38px;">
                <i class="ti ti-plus"></i> <span>Tambah</span>
            </button>
        </div>
    </div>
    
    <div class="card shadow-sm border mb-4">
        <div class="card-header py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
            <h6 class="m-0 fw-bold text-white d-flex align-items-center gap-2">
                <i class="ti ti-list fs-4"></i>
                <span>Daftar Barang yang Diajukan</span>
            </h6>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table table-sm table-hover table-bordered align-middle mb-0" id="tabledetail">
                <thead style="background-color: #002e65;">
                    <tr>
                        <th class="text-white" style="width: 15%">Kode</th>
                        <th class="text-white" style="width: 35%">Nama Barang</th>
                        <th class="text-white text-end" style="width: 15%">Jumlah</th>
                        <th class="text-white" style="width: 25%">Keterangan</th>
                        <th class="text-white text-center" style="width: 10%">#</th>
                    </tr>
                </thead>
                <tbody id="loaddetail" class="table-border-bottom-0">
                    @foreach ($detail as $d)
                        <tr id="index_{{ $d->kode_barang }}">
                            <td>
                                <input type="hidden" name="kode_barang[]" value="{{ $d->kode_barang }}">
                                <span class="badge bg-secondary text-white font-monospace shadow-xs">{{ $d->kode_barang }}</span>
                            </td>
                            <td class="fw-semibold text-dark">{{ textUpperCase($d->nama_barang) }}</td>
                            <td class="text-end fw-bold text-dark">
                                <input type="hidden" name="jml[]" value="{{ $d->jumlah }}">
                                {{ formatAngkaDesimal($d->jumlah) }}
                            </td>
                            <td>
                                <input type="hidden" name="ket[]" value="{{ $d->keterangan }}">
                                <span class="text-muted small">{{ $d->keterangan ?: '-' }}</span>
                            </td>
                            <td class="text-center">
                                <a href="#" kode_barang="{{ $d->kode_barang }}" class="delete text-danger d-inline-block p-1" title="Hapus"><i
                                        class="ti ti-trash fs-5"></i></a>
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
                    id="defaultCheck3">
                <label class="form-check-label fw-semibold text-dark" for="defaultCheck3"> Yakin Akan Disimpan ? </label>
            </div>
            <div class="form-group" id="saveButton">
                <button class="btn btn-success w-100 d-flex align-items-center justify-content-center gap-1" type="submit" id="btnSimpan">
                    <i class="ti ti-send"></i> <span>Submit</span>
                </button>
            </div>
        </div>
    </div>
</form>

<script>
    $(function() {
        const form = $("#formeditbpb");
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
            const keterangan = form.find("#keterangan").val();

            let produk = `
                    <tr id="index_${kode_barang}">
                        <td>
                            <input type="hidden" name="kode_barang[]" value="${kode_barang}"/>
                            <span class="badge bg-secondary text-white font-monospace shadow-xs">${kode_barang}</span>
                        </td>
                        <td class="fw-semibold text-dark">${nama_barang[1]}</td>
                        <td class="text-end fw-bold text-dark">
                            <input type="hidden" name="jml[]" value="${jumlah}" class="noborder-form text-end jumlah" />
                            ${jumlah}
                        </td>
                        <td>
                            <input type="hidden" name="ket[]" value="${keterangan}" class="noborder-form" />
                            <span class="text-muted small">${keterangan || '-'}</span>
                        </td>
                        <td class="text-center">
                            <a href="#" kode_barang="${kode_barang}" class="delete text-danger d-inline-block p-1" title="Hapus"><i class="ti ti-trash fs-5"></i></a>
                        </td>
                    </tr>
                `;

            //append to table
            $('#loaddetail').prepend(produk);
            $('.select2Kodebarang').val('').trigger("change");
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
            const no_bpb = form.find("#no_bpb").val();
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
            } else if (no_bpb == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "No. Bukti Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#no_bpb").focus();
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
