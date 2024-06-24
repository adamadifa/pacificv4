<div class="row">
    <div class="col-lg-5 col-md-12 col-sm-12">
        <div class="row mb-3">
            <div class="col">
                <table class="table">
                    <tr>
                        <th>No. Pinjaman</th>
                        <td class="text-end">{{ $pjp->no_pinjaman }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal</th>
                        <td class="text-end">{{ DateToIndo($pjp->tanggal) }}</td>
                    </tr>
                    <tr>
                        <th>NIK</th>
                        <td class="text-end">{{ $pjp->nik }}</td>
                    </tr>
                    <tr>
                        <th>Nama Karyawan</th>
                        <td class="text-end">{{ textUpperCase($pjp->nama_karyawan) }}</td>
                    </tr>
                    <tr>
                        <th>Jabatan</th>
                        <td class="text-end">{{ $pjp->nama_jabatan }}</td>
                    </tr>
                    <tr>
                        <th>Departemen</th>
                        <td class="text-end">{{ $pjp->nama_dept }}</td>
                    </tr>
                    <tr>
                        <th>Kantor</th>
                        <td class="text-end">{{ $pjp->nama_cabang }}</td>
                    </tr>
                    <tr>
                        <th>Masa Kerja</th>
                        <td class="text-end">
                            @php
                                $masakerja = hitungMasakerja($pjp->tanggal_masuk, $pjp->tanggal);
                            @endphp
                            {{ $masakerja['tahun'] }} Tahun {{ $masakerja['bulan'] }} Bulan {{ $masakerja['hari'] }} Hari
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col">
                @can('pembayaranpjp.create')
                    @if ($pjp->status == '1')
                        <a href="#" class="btn btn-primary mb-2" id="btnBayar" no_pinjaman="{{ Crypt::encrypt($pjp->no_pinjaman) }}"><i
                                class="ti ti-moneybag me-1"></i>Input Pembayaran PJP</a>
                    @endif
                @endcan

                <table class="table table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>No. Bukti</th>
                            <th>Tanggal</th>
                            <th>Jumlah</th>
                            <th>#</th>
                        </tr>
                    </thead>
                    <tbody id="loadhistoribayar">

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-7 col-md-12 col-sm-12">
        <div class="row mb-3">
            <div class="col">
                <table class="table">
                    <tr>
                        <th>Jumlah Pinjaman</th>
                        <td class="text-end fw-bold">{{ formatAngka($pjp->jumlah_pinjaman) }}</td>
                    </tr>
                    <tr>
                        <th>Angsuran</th>
                        <td class="text-end">{{ $pjp->angsuran }} Bulan</td>
                    </tr>
                    <tr>
                        <th>Angsuran / Bulan</th>
                        <td class="text-end">{{ formatAngka($pjp->jumlah_angsuran) }} </td>
                    </tr>
                    <tr>
                        <th>Jumlah Pembayaran</th>
                        <td class="text-end fw-bold">{{ formatAngka($pjp->totalpembayaran) }}</td>
                    </tr>
                    <tr>
                        <th>Sisa Tagihan</th>
                        <td class="text-end fw-bold">{{ formatAngka($pjp->jumlah_pinjaman - $pjp->totalpembayaran) }}</td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <table class="table table-bordered" id="tabelrencanacicilan">
                    <thead class="table-dark">
                        <tr>
                            <th>Cicilan</th>
                            <th>Bulan</th>
                            <th>Jumlah</th>
                            <th>Realisasi</th>
                            <th>Sisa Tagihan</th>
                        </tr>
                    </thead>
                    <tbody id="loadrencanacicilan">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    $(function() {
        let sisatagihan;

        function getrencaCicilan() {
            var no_pinjaman = "{{ $pjp->no_pinjaman }}";
            $.ajax({
                type: 'POST',
                url: '/pjp/getrencanacicilan',
                data: {
                    _token: "{{ csrf_token() }}",
                    no_pinjaman: no_pinjaman
                },
                cache: false,
                success: function(respond) {
                    $("#loadrencanacicilan").html(respond);
                    sisatagihan = $("#tabelrencanacicilan").find("#sisatagihan").text();
                }
            });
        }

        function gethistoribayar() {
            var no_pinjaman = "{{ $pjp->no_pinjaman }}";
            $.ajax({
                type: 'POST',
                url: '/pembayaranpjp/gethistoribayar',
                data: {
                    _token: "{{ csrf_token() }}",
                    no_pinjaman: no_pinjaman
                },
                cache: false,
                success: function(respond) {
                    $("#loadhistoribayar").html(respond);
                }
            });
        }

        getrencaCicilan();
        gethistoribayar();

        $(document).on('submit', '#formBayarpjp', function(e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            const tanggal = $(this).find('input[name="tanggal"]').val();
            let jml = $(this).find('input[name="jumlah"]').val();
            let jumlah = jml != "" ? jml.replace(/\./g, '') : 0;
            let tagihan = sisatagihan != "" ? sisatagihan.replace(/\./g, '') : 0;
            //alert(tagihan + "=" + jumlah);
            if (tanggal == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Tanggal Harus Diisi",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $(this).find('input[name="tanggal"]').focus();
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
                        $(this).find('input[name="jumlah"]').focus();
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
                        $(this).find('input[name="jumlah"]').focus();
                    },
                });
                return false;
            } else {
                $.ajax({
                    type: 'POST',
                    url: '/pembayaranpjp/store',
                    data: {
                        _token: "{{ csrf_token() }}",
                        no_pinjaman: "{{ $pjp->no_pinjaman }}",
                        tanggal: tanggal,
                        jumlah: jumlah
                    },
                    cache: false,
                    success: function(respond) {
                        if (respond == 0) {

                            Swal.fire({
                                title: "Success!",
                                text: "Data Berhasil Disimpan",
                                icon: "success",
                                showConfirmButton: true,
                                didClose: (e) => {
                                    getrencaCicilan();
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
                        url: '/pembayaranpjp/delete',
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
                                        getrencaCicilan();
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
