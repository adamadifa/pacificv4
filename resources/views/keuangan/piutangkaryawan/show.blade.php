<div class="row">
    <div class="col-lg-6 col-md-12 col-sm-12">
        <div class="row mb-3">
            <div class="col">
                <table class="table">
                    <tr>
                        <th>No. Pinjaman</th>
                        <td class="text-end" id="no_pinjaman_val">{{ $piutangkaryawan->no_pinjaman }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal</th>
                        <td class="text-end">{{ DateToIndo($piutangkaryawan->tanggal) }}</td>
                    </tr>
                    <tr>
                        <th>NIK</th>
                        <td class="text-end">{{ $piutangkaryawan->nik }}</td>
                    </tr>
                    <tr>
                        <th>Nama Karyawan</th>
                        <td class="text-end">{{ textUpperCase($piutangkaryawan->nama_karyawan) }}</td>
                    </tr>
                    <tr>
                        <th>Jabatan</th>
                        <td class="text-end">{{ $piutangkaryawan->nama_jabatan }}</td>
                    </tr>
                    <tr>
                        <th>Departemen</th>
                        <td class="text-end">{{ $piutangkaryawan->nama_dept }}</td>
                    </tr>
                    <tr>
                        <th>Kantor</th>
                        <td class="text-end">{{ $piutangkaryawan->nama_cabang }}</td>
                    </tr>
                    <tr>
                        <th>Masa Kerja</th>
                        <td class="text-end">
                            @php
                                $masakerja = hitungMasakerja($piutangkaryawan->tanggal_masuk, $piutangkaryawan->tanggal);
                            @endphp
                            {{ $masakerja['tahun'] }} Tahun {{ $masakerja['bulan'] }} Bulan {{ $masakerja['hari'] }} Hari
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-12 col-sm-12">
        <div class="row mb-3">
            <div class="col" id="loadpiutang">

            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col">
        @can('pembayaranpk.create')
            <a href="#" class="btn btn-primary mb-2" id="btnBayar" no_pinjaman="{{ Crypt::encrypt($piutangkaryawan->no_pinjaman) }}">
                <i class="ti ti-moneybag me-1"></i>Input Pembayaran
            </a>
        @endcan

        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>No. Bukti</th>
                    <th>Tanggal</th>
                    <th>Jumlah</th>
                    <th>Jenis Bayar</th>
                    <th>#</th>
                </tr>
            </thead>
            <tbody id="loadhistoribayar">

            </tbody>
        </table>
    </div>
</div>
<script>
    $(function() {
        let sisatagihan = "{{ $piutangkaryawan->jumlah - $piutangkaryawan->totalpembayaran }}";
        let no_pinjaman = "{{ Crypt::encrypt($piutangkaryawan->no_pinjaman) }}";

        function getpiutang() {
            $("#loadpiutang").load(`/piutangkaryawan/${no_pinjaman}/getpiutangkaryawan`);
        }

        getpiutang();

        function gethistoribayar() {
            var no_pinjaman = "{{ $piutangkaryawan->no_pinjaman }}";
            $.ajax({
                type: 'POST',
                url: '/pembayaranpiutangkaryawan/gethistoribayar',
                data: {
                    _token: "{{ csrf_token() }}",
                    no_pinjaman: no_pinjaman
                },
                cache: false,
                success: function(respond) {
                    $("#loadhistoribayar").html(respond);
                    getpiutang();
                }
            });
        }


        gethistoribayar();

        $(document).on('submit', '#formPembayaranpituangkaryawan', function(e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            const jenis_bayar = $(this).find("#jenis_bayar").val();
            const bulan = $(this).find("#bulan").val();
            const tahun = $(this).find("#tahun").val();
            let jml = $(this).find('input[name="jumlah"]').val();
            let jumlah = jml != "" ? jml.replace(/\./g, '') : 0;
            let tagihan = sisatagihan != "" ? sisatagihan.replace(/\./g, '') : 0;
            let no_pinjaman = $("#no_pinjaman_val").text();
            //alert(tagihan + "=" + jumlah);
            if (jenis_bayar == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Jenis Bayar Harus Diisi",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $(this).find("#jenis_bayar").focus();
                    },
                });
                return false;
            } else if (bulan == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Bulan Harus Diisi",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $(this).find("#bulan").focus();
                    },
                });
                return false;
            } else if (tahun == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Tahun Harus Diisi",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $(this).find("#tahun").focus();
                    },
                });
                return false;
            } else if (jumlah == "" || jumlah === "0") {
                Swal.fire({
                    title: "Oops!",
                    text: "Jumlah Harus Diisi",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $(this).find("#jumlah").focus();
                    },
                });
                return false;
            } else if (parseInt(jumlah) > parseInt(tagihan)) {
                Swal.fire({
                    title: "Oops!",
                    text: "Melebihi Sisa Tagihan",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $(this).find("#jenis_bayar").focus();
                    },
                });
                return false;
            } else {
                $.ajax({
                    type: 'POST',
                    url: '/pembayaranpiutangkaryawan/store',
                    data: {
                        _token: "{{ csrf_token() }}",
                        no_pinjaman: no_pinjaman,
                        jenis_bayar: jenis_bayar,
                        bulan: bulan,
                        tahun: tahun,
                        jumlah: jumlah
                    },
                    cache: false,
                    success: function(respond) {
                        console.log(respond);
                        if (respond == 0) {

                            Swal.fire({
                                title: "Success!",
                                text: "Data Berhasil Disimpan",
                                icon: "success",
                                showConfirmButton: true,
                                didClose: (e) => {
                                    gethistoribayar();
                                    $("#modalBayar").modal("hide");
                                }
                            });

                        } else if (respond == 2) {
                            Swal.fire({
                                title: "Oops!",
                                text: "Melebihi Sisa Tagihan",
                                icon: "warning",
                                showConfirmButton: true,
                                didClose: (e) => {
                                    $(this).find('input[name="jumlah"]').focus();
                                },
                            });
                            return false;
                        } else if (respond == 3) {
                            Swal.fire({
                                title: "Oops!",
                                text: "Pembayaran Potongan Gaji Untuk Bulan Ini Sudah Ada",
                                icon: "warning",
                                showConfirmButton: true,
                                didClose: (e) => {
                                    $(this).find('#jenis_bayar').focus();
                                },
                            });
                            return false;
                        } else if (respond == 4) {
                            Swal.fire({
                                title: "Oops!",
                                text: "Pembayaran Potongan Komisi Untuk Bulan Ini Sudah Ada",
                                icon: "warning",
                                showConfirmButton: true,
                                didClose: (e) => {
                                    $(this).find('#jenis_bayar').focus();
                                },
                            });
                            return false;
                        } else if (respond == 3) {
                            Swal.fire({
                                title: "Oops!",
                                text: "Pembayaran Titipan Pelanggan Untuk Bulan Ini Sudah Ada",
                                icon: "warning",
                                showConfirmButton: true,
                                didClose: (e) => {
                                    $(this).find('#jenis_bayar').focus();
                                },
                            });
                            return false;
                        } else {
                            Swal.fire({
                                title: "Error!",
                                text: "Silahkan Hubungi IT",
                                icon: "warning",
                                showConfirmButton: true,
                                didClose: (e) => {
                                    $(this).find('input[name="jumlah"]').focus();
                                },
                            });
                        }
                    }
                });


            }
        });

        $(document).on('click', '.btnDeletebayar', function(e) {
            e.preventDefault();
            const no_bukti = $(this).attr("no_bukti");
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
                        url: '/pembayaranpiutangkaryawan/delete',
                        data: {
                            _token: "{{ csrf_token() }}",
                            no_bukti: no_bukti
                        },
                        cache: false,
                        success: function(respond) {
                            if (respond == 0) {
                                Swal.fire({
                                    title: "Success!",
                                    text: "Data Berhasil Dihapus",
                                    icon: "success",
                                    showConfirmButton: true,
                                    didClose: (e) => {
                                        gethistoribayar();
                                        $("#modalBayar").modal("hide");
                                    }
                                });

                            }
                        }
                    });
                }
            });
        });
    });
</script>
