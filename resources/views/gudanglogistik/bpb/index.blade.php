@extends('layouts.app')
@section('titlepage', 'Bukti Permintaan Barang')

@section('content')
@section('navigasi')
    <span>Bukti Permintaan Barang</span>
@endsection
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="nav-align-top nav-tabs-shadow mb-4">
            @include('layouts.navigation_mutasigudanglogistik')
            <div class="tab-content">
                <div class="tab-pane fade active show" id="navs-justified-home" role="tabpanel">
                    {{-- @can('bpb.create') --}}
                    <a href="#" class="btn btn-primary" id="btnCreate"><i class="fa fa-plus me-2"></i>
                        Tambah Data</a>
                    {{-- @endcan --}}
                    <div class="row mt-2">
                        <div class="col-12">
                            <form action="{{ route('bpb.index') }}">
                                <div class="row">
                                    <div class="col-lg-6 col-sm-12 col-md-12">
                                        <x-input-with-icon label="Dari" value="{{ Request('dari') }}" name="dari"
                                            icon="ti ti-calendar" datepicker="flatpickr-date" />
                                    </div>
                                    <div class="col-lg-6 col-sm-12 col-md-12">
                                        <x-input-with-icon label="Sampai" value="{{ Request('sampai') }}" name="sampai"
                                            icon="ti ti-calendar" datepicker="flatpickr-date" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-3">
                                        <x-input-with-icon icon="ti ti-barcode" label="No. Bukti" name="no_bpb_search"
                                            value="{{ Request('no_bpb_search') }}" />
                                    </div>
                                    <div class="col-3">
                                        <select name="status" class="form-control">
                                            <option value="">-- Semua Status --</option>
                                            <option value="selesai"
                                                {{ request('status') == 'selesai' ? 'selected' : '' }}>
                                                Selesai
                                            </option>
                                            <option value="proses"
                                                {{ request('status') == 'proses' ? 'selected' : '' }}>
                                                Proses
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-3">
                                        <select name="kode_cabang" class="form-control">
                                            <option value="">-- Semua Cabang --</option>
                                            @foreach ($cabangList as $c)
                                                <option value="{{ $c->kode_cabang }}"
                                                    {{ request('kode_cabang') == $c->kode_cabang ? 'selected' : '' }}>
                                                    {{ $c->nama_cabang }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-3">
                                        <select name="kode_dept" class="form-control">
                                            <option value="">-- Semua Departemen --</option>
                                            @foreach ($deptList as $d)
                                                <option value="{{ $d->kode_dept }}"
                                                    {{ request('kode_dept') == $d->kode_dept ? 'selected' : '' }}>
                                                    {{ $d->nama_dept }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="form-group mb-3">
                                            <button class="btn btn-primary w-100"><i class="ti ti-search me-1"></i>Cari
                                                Data</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive mb-2">
                                <table class="table table-striped table-hover table-bordered">
                                    <thead class="table-dark">
                                        <tr>
                                            <th style="width:12%">No. Bukti</th>
                                            <th style="width:12%">Tgl Pengajuan</th>
                                            <th style="width:15%">Department</th>
                                            <th style="width:5%">Cabang</th>
                                            <th style="width:10%">Yang Mengajukan</th>
                                            <th style="width:5%">Head Dept</th>
                                            <th style="width:5%">Gudang</th>
                                            <th style="width:5%">Status</th>
                                            <th style="width:8%">#</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($bpb as $d)
                                            <tr>
                                                <td>{{ $d->no_bpb }}</td>
                                                <td>{{ !empty($d->tanggal) ? DateToIndo($d->tanggal) : '' }}</td>
                                                <td>{{ $d->nama_dept }}</td>
                                                <td>{{ $d->kode_cabang }}</td>
                                                <td>{{ $d->nama_user }}</td>
                                                <td>
                                                    @if ($d->approve_head_dept == '1')
                                                        <button class="btn btn-sm btn-success">
                                                            <i class="ti ti-check"></i>
                                                        </button>
                                                    @else
                                                        <button class="btn btn-sm btn-warning text-dark">
                                                            <i class="ti ti-clock"></i>
                                                        </button>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($d->approve_gudang == '1')
                                                        <button class="btn btn-sm btn-success">
                                                            <i class="ti ti-check"></i>
                                                        </button>
                                                    @else
                                                        <button class="btn btn-sm btn-warning">
                                                            <i class="ti ti-clock"></i>
                                                        </button>
                                                    @endif
                                                </td>

                                                <td>
                                                    @if ($d->total_serah_terima == $d->total_bpb)
                                                        <button class="btn btn-sm btn-success">
                                                            <i class="ti ti-check"></i> Selesai
                                                        </button>
                                                    @else
                                                        <button class="btn btn-sm btn-secondary">
                                                            <i class="ti ti-clock"></i> Proses
                                                        </button>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex">
                                                        @php
                                                            $cabang = Auth::user()->kode_cabang;
                                                            $dept = Auth::user()->kode_dept;
                                                            if ($cabang == 'PST' && $dept == 'AKT') {
                                                                $user = '194';
                                                            } elseif ($dept == 'KEU') {
                                                                $user = '57';
                                                            } elseif ($dept == 'PMB') {
                                                                $user = '9';
                                                            } elseif ($dept == 'HRD') {
                                                                $user = '109';
                                                            } elseif ($dept == 'ADT') {
                                                                $user = '196';
                                                            } elseif ($dept == 'GAF') {
                                                                $user = '64';
                                                            } elseif ($dept == 'GDG') {
                                                                $user = '28';
                                                            } elseif ($dept == 'PRD') {
                                                                $user = '71';
                                                            } elseif ($dept == 'PDQ') {
                                                                $user = '62';
                                                            } elseif ($dept == 'MTC') {
                                                                $user = '61';
                                                            } else {
                                                                $user = '';
                                                            }
                                                        @endphp
                                                        @if (Auth::user()->id == $user && $d->approve_head_dept == '0')
                                                            <a href="#" class="me-1 btnApprove"
                                                                no_bpb="{{ Crypt::encrypt($d->no_bpb) }}">
                                                                <i class="ti ti-external-link text-success"></i>
                                                            </a>
                                                        @endif
                                                        @if (Auth::user()->id == '67' && $d->approve_head_dept == '1' && $d->approve_gudang == '0')
                                                            <a href="#" class="me-1 btnApprove" data-approve="1"
                                                                no_bpb="{{ Crypt::encrypt($d->no_bpb) }}">
                                                                <i class="ti ti-external-link text-success"></i>
                                                            </a>
                                                        @endif
                                                        {{-- @can('bpb.edit') --}}
                                                        @if (empty($d->tanggal_pembelian))
                                                            <a href="#" class="me-2 btnEdit"
                                                                no_bpb="{{ Crypt::encrypt($d->no_bpb) }}">
                                                                <i class="ti ti-edit text-success"></i>
                                                            </a>
                                                        @endif
                                                        {{-- @endcan --}}

                                                        {{-- @can('bpb.show') --}}
                                                        <a href="#" class="me-2 btnShow"
                                                            no_bpb="{{ Crypt::encrypt($d->no_bpb) }}">
                                                            <i class="ti ti-file-description text-info"></i>
                                                        </a>
                                                        {{-- @endcan --}}

                                                        {{-- @can('bpb.delete') --}}
                                                        @if ($d->approve_head_dept == '0')
                                                            <form method="POST" class="deleteform"
                                                                action="{{ route('bpb.delete', Crypt::encrypt($d->no_bpb)) }}">
                                                                @csrf
                                                                @method('DELETE')
                                                                <a href="#" class="delete-confirm ml-1">
                                                                    <i class="ti ti-trash text-danger"></i>
                                                                </a>
                                                            </form>
                                                        @endif
                                                        {{-- @endcan --}}
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                            </div>
                            <div style="float: right;">
                                {{ $bpb->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<x-modal-form id="modal" size="modal-xl" show="loadmodal" title="" />
@endsection
@push('myscript')
{{-- <script src="{{ asset('assets/js/pages/roles/create.js') }}"></script> --}}
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

        $("#btnCreate").click(function(e) {
            e.preventDefault();
            $("#modal").modal("show");
            $(".modal-title").text("Tambah Data BPB");
            $("#loadmodal").html(loadingElement());
            $("#loadmodal").load(`/bpb/create`);
        });

        $(".btnShow").click(function(e) {
            e.preventDefault();
            var no_bpb = $(this).attr("no_bpb");
            e.preventDefault();
            $("#modal").modal("show");
            $(".modal-title").text("Detail BPB");
            $("#loadmodal").html(loadingElement());
            $("#loadmodal").load(`/bpb/${no_bpb}/show`);
        });

        $(".btnApprove").click(function(e) {
            e.preventDefault();

            let no_bpb = $(this).attr("no_bpb");
            let approve = $(this).attr("data-approve");

            Swal.fire({
                title: 'Approve BPB?',
                text: 'Pastikan data BPB sudah benar',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Approve',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#198754', // hijau
                cancelButtonColor: '#dc3545', // merah
            }).then((result) => {
                if (result.isConfirmed) {

                    Swal.fire({
                        title: 'Memproses...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();

                            $.ajax({
                                url: `/bpb/${no_bpb}/storeapprove`,
                                type: 'POST',
                                data: {
                                    approve: approve,
                                    _token: $('meta[name="csrf-token"]')
                                        .attr('content')
                                },
                                success: function(res) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil!',
                                        text: res.message,
                                        timer: 1500,
                                        showConfirmButton: false
                                    }).then(() => location.reload());
                                },
                                error: function(xhr) {
                                    Swal.fire(
                                        'Gagal!',
                                        xhr.responseJSON?.message ??
                                        'Terjadi kesalahan',
                                        'error'
                                    );
                                }
                            });
                        }
                    });

                }
            });
        });

        $(".btnEdit").click(function(e) {
            e.preventDefault();
            var no_bpb = $(this).attr("no_bpb");
            e.preventDefault();
            $("#modal").modal("show");
            $(".modal-title").text("Edit BPB");
            $("#loadmodal").html(loadingElement());
            $("#loadmodal").load(`/bpb/${no_bpb}/edit`);
        });
    });
</script>
@endpush
