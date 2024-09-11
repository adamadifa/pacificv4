<div class="row mb-3">
    <div class="col-6">
        <table class="table">
            <tr>
                <th style="width: 30%">No. DPB</th>
                <td id="no_dpb_text">{{ $dpb->no_dpb }}</td>
            </tr>
            <tr>
                <th>Tanggal</th>
                <td>{{ DateToIndo($dpb->tanggal_ambil) }}</td>
            </tr>
            <tr>
                <th>Salesman</th>
                <td>{{ $dpb->nama_salesman }}</td>
            </tr>
            <tr>
                <th>Cabang</th>
                <td>{{ textUpperCase($dpb->nama_cabang) }}</td>
            </tr>
            <tr>
                <th>No. Kendaraan</th>
                <td>{{ $dpb->no_polisi }}</td>
            </tr>
        </table>

    </div>
    @if (!$driverhelper->isEmpty())
        <div class="col-lg-6 col-md-12 col-sm-12">
            <div class="row">
                <div class="col">
                    <table class="table table-striped table-bordered table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>DRIVER</th>
                                <td colspan="2">{{ $driver != null ? $driver->kode_driver_helper : '-' }} -
                                    {{ $driver != null ? $driver->nama_driver_helper : '-' }}</td>
                            </tr>
                            <tr>
                                <th>Kode</th>
                                <th>Nama Helper</th>
                                <th>Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($driverhelper as $dh)
                                <tr>
                                    <td>{{ $dh->kode_driver_helper }}</td>
                                    <td>{{ $dh->nama_driver_helper }}</td>
                                    <td class="text-end">{{ formatAngkaDesimal3($dh->jumlah) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

</div>
<ul class="nav nav-tabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#detaildpb" aria-controls="detaildpb"
            aria-selected="true">
            <i class="tf-icons ti ti-file-description ti-xs me-1"></i> Detail DPB
        </button>
    </li>
    @can('mutasidpb.index')
        <li class="nav-item" role="presentation">
            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#mutasidpb" aria-controls="mutasidpb"
                aria-selected="false" tabindex="-1">
                <i class="tf-icons ti ti-stack-push ti-xs me-1"></i> Mutasi DPB
            </button>
        </li>
    @endcan

</ul>
<div class="tab-content">
    <div class="tab-pane fade show active" id="detaildpb" role="tabpanel">
        <div class="row mt-3">
            <div class="col">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th rowspan="3">kode</th>
                            <th rowspan="3">Nama Produk</th>
                            <th colspan="3" class="text-center">Pengambilan</th>
                            <th colspan="3" class="text-center bg-success">Pengembalian</th>
                            <th rowspan="2" colspan="3" class="text-center bg-danger">Barang Keluar</th>
                        </tr>
                        <tr>
                            <th colspan="3" class="text-center">{{ DateToIndo($dpb->tanggal_ambil) }}</th>
                            <th colspan="3" class="text-center bg-success">{!! !empty($dpb->tanggal_kembali) ? DateToIndo($dpb->tanggal_kembali) : '<span class="badge bg-warning">Waiting</span>' !!} </th>
                        </tr>
                        <tr>
                            <th>Dus / Ball</th>
                            <th>Pack</th>
                            <th>Pcs</th>

                            <th class="bg-success">Dus / Ball</th>
                            <th class="bg-success">Pack</th>
                            <th class="bg-success">Pcs</th>

                            <th class="bg-danger">Dus / Ball</th>
                            <th class="bg-danger">Pack</th>
                            <th class="bg-danger">Pcs</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($detail as $d)
                            @php
                                //Pengambilan

                                $jml_dus_ambil = floor($d->jml_ambil / $d->isi_pcs_dus);
                                $sisa_dus_ambil = $d->jml_ambil % $d->isi_pcs_dus;
                                if (!empty($d->isi_pack_dus)) {
                                    $jml_pack_ambil = floor($sisa_dus_ambil / $d->isi_pcs_pack);
                                    $sisa_pack_ambil = $sisa_dus_ambil % $d->isi_pcs_pack;
                                } else {
                                    $jml_pack_ambil = 0;
                                    $sisa_pack_ambil = $sisa_dus_ambil;
                                }
                                $jml_pcs_ambil = $sisa_pack_ambil;

                                //Pengembalian

                                $jml_dus_kembali = floor($d->jml_kembali / $d->isi_pcs_dus);
                                $sisa_dus_kembali = $d->jml_kembali % $d->isi_pcs_dus;
                                if (!empty($d->isi_pack_dus)) {
                                    $jml_pack_kembali = floor($sisa_dus_kembali / $d->isi_pcs_pack);
                                    $sisa_pack_kembali = $sisa_dus_kembali % $d->isi_pcs_pack;
                                } else {
                                    $jml_pack_kembali = 0;
                                    $sisa_pack_kembali = $sisa_dus_kembali;
                                }
                                $jml_pcs_kembali = $sisa_pack_kembali;

                                //Barang Kleuar

                                $jml_dus_keluar = floor($d->jml_penjualan / $d->isi_pcs_dus);
                                $sisa_dus_keluar = $d->jml_penjualan % $d->isi_pcs_dus;
                                if (!empty($d->isi_pack_dus)) {
                                    $jml_pack_keluar = floor($sisa_dus_keluar / $d->isi_pcs_pack);
                                    $sisa_pack_keluar = $sisa_dus_keluar % $d->isi_pcs_pack;
                                } else {
                                    $jml_pack_keluar = 0;
                                    $sisa_pack_keluar = $sisa_dus_keluar;
                                }
                                $jml_pcs_keluar = $sisa_pack_keluar;
                            @endphp
                            <tr>
                                <td>{{ $d->kode_produk }}</td>
                                <td>{{ $d->nama_produk }}</td>

                                <td class="text-end">{{ formatAngka($jml_dus_ambil) }}</td>
                                <td class="text-end">{{ formatAngka($jml_pack_ambil) }}</td>
                                <td class="text-end">{{ formatAngka($jml_pcs_ambil) }}</td>

                                <td class="text-end" style="background-color:#28c76f1a">
                                    {{ formatAngka($jml_dus_kembali) }}</td>
                                <td class="text-end" style="background-color:#28c76f1a">
                                    {{ formatAngka($jml_pack_kembali) }}</td>
                                <td class="text-end" style="background-color:#28c76f1a">
                                    {{ formatAngka($jml_pcs_kembali) }}</td>

                                <td class="text-end" style="background-color: #ea54552e">
                                    {{ formatAngka($jml_dus_keluar) }}</td>
                                <td class="text-end" style="background-color: #ea54552e">
                                    {{ formatAngka($jml_pack_keluar) }}</td>
                                <td class="text-end" style="background-color: #ea54552e">
                                    {{ formatAngka($jml_pcs_keluar) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col" id="loaddetailmutasidpb">
            </div>
        </div>
    </div>
    @can('mutasidpb.index')
        <div class="tab-pane fade" id="mutasidpb" role="tabpanel">
            @can('mutasidpb.create')
                <div class="row mb-2">
                    <div class="col">
                        <a href="#" class="btn btn-primary" id="btnCreatemutasidpb"><i class="ti ti-plus me-1"></i>Tambah
                            Data Mutasi</a>
                    </div>
                </div>
            @endcan

            <div class="row mb-2">
                <div class="col">
                    <x-select label="Jenis Mutasi" name="jenis_mutasi_search" :data="$jenis_mutasi" key="kode_jenis_mutasi" textShow="jenis_mutasi"
                        upperCase="true" select2="select2Jenismutasisearch" />
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <table class="table table-hover table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>No. Mutasi</th>
                                <th>Tanggal</th>
                                <th>Jenis Mutasi</th>
                                <th>#</th>
                            </tr>
                        </thead>
                        <tbody id="loadmutasidpb">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endcan
</div>
<script>
    $(function() {

        function loadingElement() {
            const loading = `<div class="sk-wave sk-primary" style="margin:auto">
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            </div>`;

            return loading;
        };

        function loadingonTable(colspan = 0) {
            const loading = `
         <tr>
            <td colspan="${colspan}">
                <div class="sk-wave sk-primary" style="margin:auto">
                <div class="sk-wave-rect"></div>
                <div class="sk-wave-rect"></div>
                <div class="sk-wave-rect"></div>
                <div class="sk-wave-rect"></div>
                <div class="sk-wave-rect"></div>
                </div>
            </td>
        </tr>`;

            return loading;
        };

        $("#btnCreatemutasidpb").click(function(e) {
            e.preventDefault();
            const no_dpb = $("#no_dpb_text").text();
            $("#loadmodalMutasi").html(loadingElement());
            $("#modalMutasi").modal("show");
            $("#modalMutasi").find(".modal-title").text("Tambah Data Mutasi DPB. " + no_dpb);
            $("#loadmodalMutasi").load(`/mutasidpb/create`);
        });




        $("#jenis_mutasi_search").change(function() {
            getmutasidpb();
        });



        function getdetailmutasidpb() {
            const no_dpb = "{{ Crypt::encrypt($dpb->no_dpb) }}";
            $("#loaddetailmutasidpb").html(loadingElement());
            $("#loaddetailmutasidpb").load(`/dpb/${no_dpb}/getdetailmutasidpb`);
        }


        function getmutasidpb() {
            const jenis_mutasi = $("#jenis_mutasi_search").val();
            const jm = jenis_mutasi != '' ? jenis_mutasi : null;
            const no_dpb = $("#no_dpb_text").text();
            $("#loadmutasidpb").html(loadingonTable(4));
            $("#loadmutasidpb").load(`/mutasidpb/${no_dpb}/${jm}/getmutasidpb`);
        }

        getmutasidpb();
        getdetailmutasidpb();
        $(document).on('submit', '#formMutasiDPB', function(e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            const tanggal = $(this).find("#tanggal").val();
            const jenis_mutasi = $(this).find("#jenis_mutasi").val();
            let kode_produk = [];
            let jml_dus = [];
            let jml_pack = [];
            let jml_pcs = [];
            let isi_pcs_dus = [];
            let isi_pcs_pack = [];
            const no_dpb = $("#no_dpb_text").text();


            // alert(no_dpb);

            // return false;
            $(".kode_produk").each(function() {
                kode_produk.push($(this).val());
            });
            $(".jml_dus").each(function() {
                jml_dus.push($(this).val());
            });

            $(".jml_pack").each(function() {
                jml_pack.push($(this).val());
            });

            $(".jml_pcs").each(function() {
                jml_pcs.push($(this).val());
            });

            $(".isi_pcs_dus").each(function() {
                isi_pcs_dus.push($(this).val());
            });

            $(".isi_pcs_pack").each(function() {
                isi_pcs_pack.push($(this).val());
            });

            //  console.log(kode_produk);
            //  console.log(jml_dus);
            //  console.log(jml_pack);
            //  console.log(jml_pcs);
            if (tanggal == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Tanggal Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $(this).find("#tanggal").focus();
                        $(this).find("#submitMutasiDpb").prop('disabled', false);
                    },
                });

            } else if (jenis_mutasi == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Jenis Mutasi Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $(this).find("#jenis_mutasi").focus();
                        $(this).find("#submitMutasiDpb").prop('disabled', false);
                    },

                });
            } else {
                //alert(no_dpb);
                //return false;
                let baris = 1;
                $(this).find("#submitMutasiDpb").prop('disabled', true);
                //  alert(`Baris Ke ${baris+1}`);
                $.ajax({
                    type: "POST",
                    url: "{{ route('mutasidpb.store') }}",
                    cache: false,
                    data: {
                        _token: "{{ csrf_token() }}",
                        no_dpb: no_dpb,
                        tanggal: tanggal,
                        jenis_mutasi: jenis_mutasi,
                        kode_produk: kode_produk,
                        jml_dus: jml_dus,
                        jml_pack: jml_pack,
                        jml_pcs: jml_pcs,
                        isi_pcs_dus: isi_pcs_dus,
                        isi_pcs_pack: isi_pcs_pack
                    },
                    success: function(respond) {
                        if (respond.status == 'success') {
                            Swal.fire({
                                title: "Success!",
                                text: respond.message,
                                icon: "success",
                                showConfirmButton: true,
                                didClose: (e) => {
                                    getmutasidpb();
                                    getdetailmutasidpb();
                                    $("#modalMutasi").modal("hide");
                                },
                            });
                        } else {
                            Swal.fire({
                                title: "Oops!",
                                text: respond.message,
                                icon: "error",
                                showConfirmButton: true,
                                didClose: (e) => {
                                    getmutasidpb();
                                    getdetailmutasidpb();
                                    $(this).find("#submitMutasiDpb").prop('disabled', false);
                                },
                            });
                        }


                    }
                });
            }

        });


        $(document).on('submit', '#formupdateMutasiDPB', function(e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            const no_mutasi = $(this).find("#no_mutasi").val();
            const tanggal = $(this).find("#tanggal").val();
            const jenis_mutasi = $(this).find("#jenis_mutasi").val();
            $(this).find("#submitMutasiDpb").prop('disabled', true);
            let kode_produk = [];
            let jml_dus = [];
            let jml_pack = [];
            let jml_pcs = [];
            let isi_pcs_dus = [];
            let isi_pcs_pack = [];
            const no_dpb = $("#no_dpb_text").text();

            $(".kode_produk").each(function() {
                kode_produk.push($(this).val());
            });
            $(".jml_dus").each(function() {
                jml_dus.push($(this).val());
            });

            $(".jml_pack").each(function() {
                jml_pack.push($(this).val());
            });

            $(".jml_pcs").each(function() {
                jml_pcs.push($(this).val());
            });

            $(".isi_pcs_dus").each(function() {
                isi_pcs_dus.push($(this).val());
            });

            $(".isi_pcs_pack").each(function() {
                isi_pcs_pack.push($(this).val());
            });

            //  console.log(kode_produk);
            //  console.log(jml_dus);
            //  console.log(jml_pack);
            //  console.log(jml_pcs);
            if (tanggal == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Tanggal Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $(this).find("#tanggal").focus();
                    },

                });
            } else if (jenis_mutasi == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Jenis Mutasi Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $(this).find("#jenis_mutasi").focus();
                    },

                });
            } else {
                $.ajax({
                    type: "POST",
                    url: "{{ route('mutasidpb.update') }}",
                    cache: false,
                    data: {
                        _token: "{{ csrf_token() }}",
                        no_mutasi: no_mutasi,
                        tanggal: tanggal,
                        jenis_mutasi: jenis_mutasi,
                        kode_produk: kode_produk,
                        jml_dus: jml_dus,
                        jml_pack: jml_pack,
                        jml_pcs: jml_pcs,
                        isi_pcs_dus: isi_pcs_dus,
                        isi_pcs_pack: isi_pcs_pack
                    },
                    success: function(respond) {
                        if (respond.status == 'success') {
                            Swal.fire({
                                title: "Success!",
                                text: respond.message,
                                icon: "success",
                                showConfirmButton: true,
                                didClose: (e) => {
                                    getmutasidpb();
                                    getdetailmutasidpb();
                                    $("#modalMutasi").modal("hide");
                                },
                            });
                        } else {
                            Swal.fire({
                                title: "Oops!",
                                text: respond.message,
                                icon: "error",
                                showConfirmButton: true,
                                didClose: (e) => {
                                    getmutasidpb();
                                    getdetailmutasidpb();
                                    $(this).find("#submitMutasiDpb").prop('disabled', false);
                                },
                            });
                        }
                    }
                });
            }

        });

        const select2Jenismutasisearch = $('.select2Jenismutasisearch');
        if (select2Jenismutasisearch.length) {
            select2Jenismutasisearch.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Jenis Mutasi',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

        $(document).on("click", ".btnShowmutasidpb", function(e) {
            e.preventDefault();
            const no_mutasi = $(this).attr('no_mutasi');
            $("#modalMutasi").modal("show");
            $("#modalMutasi").find(".modal-title").text("Detail Mutasi");
            $("#loadmodalMutasi").load(`/mutasidpb/${no_mutasi}/show`);
        });

        $(document).on("click", ".btnEditmutasidpb", function(e) {
            e.preventDefault();
            const no_dpb = $("#no_dpb_text").text();
            const no_mutasi = $(this).attr('no_mutasi');
            $("#loadmodalMutasi").html(loadingElement());
            $("#modalMutasi").modal("show");
            $("#modalMutasi").find(".modal-title").text("Edit Mutasi DPB. " + no_dpb);
            $("#loadmodalMutasi").load(`/mutasidpb/${no_mutasi}/edit`);
        });


        $(document).on("click", ".btnDeletemutasidpb", function(e) {
            e.preventDefault();
            var no_mutasi = $(this).attr("no_mutasi");
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
                        url: '/mutasidpb/delete',
                        data: {
                            _token: "{{ csrf_token() }}",
                            no_mutasi: no_mutasi
                        },
                        cache: false,
                        success: function(respond) {
                            const msg = respond.split("|");
                            Swal.fire({
                                title: msg[2],
                                text: msg[1],
                                icon: msg[0],
                            });

                            getmutasidpb();
                            getdetailmutasidpb();
                        }
                    });
                }
            });
        });
    });
</script>
