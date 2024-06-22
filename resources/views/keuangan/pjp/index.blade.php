@extends('layouts.app')
@section('titlepage', 'Pinjaman Jangka Panjang (PJP)')

@section('content')
@section('navigasi')
    <span>Pinjaman Jangka Panjang (PJP)</span>
@endsection
<div class="row">
    <div class="col-lg-12">
        <div class="nav-align-top nav-tabs-shadow mb-4">
            @include('layouts.navigation_pjp')
            <div class="tab-content">
                <div class="tab-pane fade active show" id="navs-justified-home" role="tabpanel">
                    @can('pjp.create')
                        <a href="#" class="btn btn-primary" id="btnCreate"><i class="fa fa-plus me-2"></i>
                            Input PJP
                        </a>
                    @endcan

                    <div class="row mt-2">
                        <div class="col-12">
                            <form action="{{ route('pjp.index') }}">
                                @hasanyrole($roles_show_cabang)
                                    <div class="row">
                                        <div class="col-lg-12 col-sm-12 col-md-12">
                                            <x-select label="Cabang" name="kode_cabang_search" :data="$cabang" key="kode_cabang" textShow="nama_cabang"
                                                selected="{{ Request('kode_cabang_search') }}" upperCase="true" select2="select2Kodecabangsearch" />
                                        </div>
                                    </div>
                                @endhasanyrole
                                <div class="row">
                                    <div class="col-lg-12 col-sm-12 col-md-12">
                                        <x-input-with-icon label="Cari Nama Karyawan" value="{{ Request('nama_karyawan_search') }}"
                                            name="nama_karyawan_search" icon="ti ti-user" />
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col">
                                        <button class="btn btn-primary w-100"><i class="ti ti-icons ti-search me-1"></i>Cari</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive mb-2">
                                <table class="table  table-bordered">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>No. Pinjaman</th>
                                            <th>Tanggal</th>
                                            <th>NIK</th>
                                            <th>Nama Karyawan</th>
                                            <th>Jabatan</th>
                                            <th>Jumlah</th>
                                            <th>Bayar</th>
                                            <th>Sisa Tagihan</th>
                                            <th>Ket</th>
                                            <th>Status</th>
                                            <th>#</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($pjp as $d)
                                            @php
                                                $sisatagihan = $d->jumlah_pinjaman - $d->totalpembayaran;
                                            @endphp
                                            <tr>
                                                <td>{{ $d->no_pinjaman }}</td>
                                                <td>{{ formatIndo($d->tanggal) }}</td>
                                                <td>{{ $d->nik }}</td>
                                                <td>{{ $d->nama_karyawan }}</td>
                                                <td>{{ $d->nama_jabatan }}</td>
                                                <td class="text-end">{{ formatAngka($d->jumlah_pinjaman) }}</td>
                                                <td class="text-end">{{ formatRupiah($d->totalpembayaran) }}</td>
                                                <td class="text-end">{{ formatRupiah($sisatagihan) }}</td>
                                                <td>{!! $d->jumlah_pinjaman - $d->totalpembayaran == 0 ? '<span class="badge bg-success">L</span>' : '<span class="badge bg-danger">BL</span>' !!}</td>
                                                <td>
                                                    @if ($d->tanggal == '2023-05-01')
                                                        <span class="badge bg-info">Koperasi</span>
                                                    @else
                                                        @if ($d->status == 0)
                                                            <i class="ti ti-hourglass-empty text-warning"></i>
                                                        @else
                                                            <span class="badge bg-success">{{ formatIndo($d->tanggal_proses) }}</span>
                                                        @endif
                                                    @endif

                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div style="float: right;">
                                {{ $pjp->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<x-modal-form id="modal" size="modal-lg" show="loadmodal" title="" />
<div class="modal fade" id="modalKaryawan" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel18">Data Karyawan</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table" id="tabelkaryawan" width="100%">
                        <thead class="table-dark">
                            <tr>
                                <th>NIK</th>
                                <th>Nama Karyawan</th>
                                <th>Jabatan</th>
                                <th>Departemen</th>
                                <th>Kantor</th>
                                <th>Status</th>
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

@endsection
@push('myscript')
<script>
    $(function() {

        function loading() {
            $("#loadmodal,#loadmodalEdit").html(`<div class="sk-wave sk-primary" style="margin:auto">
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            </div>`);
        };

        const select2Kodecabangsearch = $('.select2Kodecabangsearch');
        if (select2Kodecabangsearch.length) {
            select2Kodecabangsearch.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Semua Cabang',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

        $("#btnCreate").click(function(e) {
            e.preventDefault();
            loading();
            $("#modal").modal("show");
            $("#modal").find(".modal-title").text('Input PJP');
            $("#loadmodal").load('/pjp/create');
        });

        $(".btnEdit").click(function(e) {
            e.preventDefault();
            loading();
            const no_bukti = $(this).attr('no_bukti');
            $("#modal").modal("show");
            $("#modal").find(".modal-title").text('Edit Mutasi Bank');
            $("#modal").find("#loadmodal").load(`/mutasibank/${no_bukti}/edit`);
        });

        $(document).on('click', '#nik_search', function(e) {
            $("#modalKaryawan").modal("show");
        });


        $('#tabelkaryawan').DataTable({
            processing: true,
            serverSide: true,
            order: [
                [1, 'asc']
            ],
            ajax: "{{ route('karyawan.getkaryawanjson') }}",
            bAutoWidth: false,
            columns: [{
                    data: 'nik',
                    name: 'nik',
                    orderable: true,
                    searchable: true,
                    width: '10%'
                },
                {
                    data: 'nama_karyawan',
                    name: 'nama_karyawan',
                    orderable: true,
                    searchable: true,
                    width: '30%'
                },
                {
                    data: 'nama_jabatan',
                    name: 'nama_jabatan',
                    orderable: true,
                    searchable: false,
                    width: '20%'
                },

                {
                    data: 'nama_dept',
                    name: 'nama_dept',
                    orderable: true,
                    searchable: false,
                    width: '20%'
                },
                {
                    data: 'nama_cabang',
                    name: 'nama_cabang',
                    orderable: true,
                    searchable: false,
                    width: '30%'
                },
                {
                    data: 'statuskaryawan',
                    name: 'statuskaryawan',
                    orderable: true,
                    searchable: false,
                    width: '10%'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    width: '5%'
                }
            ],

            rowCallback: function(row, data, index) {
                if (data.status_pelanggan == "NonAktif") {
                    $("td", row).addClass("bg-danger text-white");
                }
            }
        });

    });
</script>
@endpush
