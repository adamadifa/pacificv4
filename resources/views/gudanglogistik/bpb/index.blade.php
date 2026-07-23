@extends('layouts.app')
@section('titlepage', 'Bukti Permintaan Barang')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Bukti Permintaan Barang</h4>
            <small class="text-muted">Kelola data bukti permintaan barang (BPB).</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-folder me-1"></i>Gudang Logistik</a>
                </li>
                <li class="breadcrumb-item active"><i class="ti ti-receipt me-1"></i>BPB</li>
            </ol>
        </nav>
    </div>
@endsection

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        {{-- Filter Section --}}
        <form action="{{ route('bpb.index') }}" id="formSearch">
            <div class="row g-2 mb-1">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <x-input-with-icon label="No. Bukti" value="{{ Request('no_bpb_search') }}" name="no_bpb_search"
                        icon="ti ti-barcode" hideLabel="true" />
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6">
                    <x-input-with-icon label="Dari" value="{{ Request('dari') }}" name="dari" icon="ti ti-calendar"
                        datepicker="flatpickr-date" hideLabel="true" />
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6">
                    <x-input-with-icon label="Sampai" value="{{ Request('sampai') }}" name="sampai"
                        icon="ti ti-calendar" datepicker="flatpickr-date" hideLabel="true" />
                </div>
            </div>

            <div class="row g-2 mb-1">
                <div class="col-lg-4 col-md-4 col-sm-12">
                    <div class="form-group mb-1">
                        <select name="status" id="status" class="form-select">
                            <option value="">Status</option>
                            <option value="selesai" {{ Request('status') == 'selesai' ? 'selected' : '' }}>Selesai
                            </option>
                            <option value="proses" {{ Request('status') == 'proses' ? 'selected' : '' }}>Proses</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12">
                    <div class="form-group mb-1">
                        <select name="kode_cabang" id="kode_cabang" class="form-select">
                            <option value="">Semua Cabang</option>
                            @foreach ($cabangList as $c)
                                <option value="{{ $c->kode_cabang }}"
                                    {{ Request('kode_cabang') == $c->kode_cabang ? 'selected' : '' }}>
                                    {{ $c->nama_cabang }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12">
                    <div class="form-group mb-1">
                        <select name="kode_dept" id="kode_dept" class="form-select">
                            <option value="">Semua Departemen</option>
                            @foreach ($deptList as $d)
                                <option value="{{ $d->kode_dept }}"
                                    {{ Request('kode_dept') == $d->kode_dept ? 'selected' : '' }}>
                                    {{ $d->nama_dept }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="row g-2 mb-2">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="form-group mb-1">
                        <button class="btn btn-primary w-100"><i class="ti ti-search me-1"></i>Cari Data</button>
                    </div>
                </div>
            </div>
        </form>

        {{-- Card Data --}}
        <div class="card shadow-sm border mt-2">
            <div class="card-header border-bottom py-3"
                style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold text-white"><i class="ti ti-receipt me-2"></i>Data Bukti Permintaan Barang
                    </h6>
                    <div class="d-flex gap-2">
                        <a href="#" class="btn btn-primary btn-sm" id="btnCreate"><i class="ti ti-plus me-1"></i>
                            Tambah Data</a>
                    </div>
                </div>
            </div>

            <div class="table-responsive text-nowrap">
                <table class="table table-hover table-bordered">
                    <thead style="background-color: #002e65;">
                        <tr>
                            <th class="text-white" style="width: 12%">No. Bukti</th>
                            <th class="text-white" style="width: 12%">Tanggal</th>
                            <th class="text-white" style="width: 20%">Departemen</th>
                            <th class="text-white" style="width: 8%">Cabang</th>
                            <th class="text-white" style="width: 18%">Yang Mengajukan</th>
                            <th class="text-white text-center" style="width: 10%">Head Dept</th>
                            <th class="text-white text-center" style="width: 10%">Gudang</th>
                            <th class="text-white text-center" style="width: 10%">Status</th>
                            <th class="text-white text-center" style="width: 10%">#</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($bpb as $d)
                            <tr>
                                <td><span class="fw-bold">{{ $d->no_bpb }}</span></td>
                                <td>{{ !empty($d->tanggal) ? DateToIndo($d->tanggal) : '' }}</td>
                                <td>{{ $d->nama_dept }}</td>
                                <td>{{ $d->kode_cabang }}</td>
                                <td>{{ $d->nama_user }}</td>
                                <td class="text-center">
                                    @if ($d->kode_dept == 'MTC')
                                        <div class="d-flex flex-column gap-1 align-items-center">
                                            <div>
                                                <span
                                                    class="badge bg-{{ $d->approve_manager == '1' ? 'success' : 'warning text-dark' }} px-2 py-1"
                                                    style="font-size: 10px;" data-bs-toggle="tooltip"
                                                    title="Manager MTC">
                                                    Mngr: {!! $d->approve_manager == '1' ? '<i class="ti ti-check"></i>' : '<i class="ti ti-clock"></i>' !!}
                                                </span>
                                            </div>
                                            <div>
                                                <span
                                                    class="badge bg-{{ $d->approve_direktur == '1' ? 'success' : 'warning text-dark' }} px-2 py-1"
                                                    style="font-size: 10px;" data-bs-toggle="tooltip"
                                                    title="Direktur">
                                                    Dir: {!! $d->approve_direktur == '1' ? '<i class="ti ti-check"></i>' : '<i class="ti ti-clock"></i>' !!}
                                                </span>
                                            </div>
                                        </div>
                                    @else
                                        @if ($d->approve_head_dept == '1')
                                            <button class="btn btn-sm btn-success" type="button"
                                                style="padding: 2px 6px;" data-bs-toggle="tooltip"
                                                title="Disetujui Head Dept">
                                                <i class="ti ti-check" style="font-size: 13px;"></i>
                                            </button>
                                        @else
                                            <button class="btn btn-sm btn-warning text-dark" type="button"
                                                style="padding: 2px 6px;" data-bs-toggle="tooltip"
                                                title="Menunggu Head Dept">
                                                <i class="ti ti-clock" style="font-size: 13px;"></i>
                                            </button>
                                        @endif
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($d->approve_gudang == '1')
                                        <button class="btn btn-sm btn-success" type="button"
                                            style="padding: 2px 6px;">
                                            <i class="ti ti-check" style="font-size: 13px;"></i>
                                        </button>
                                    @else
                                        <button class="btn btn-sm btn-warning" type="button"
                                            style="padding: 2px 6px;">
                                            <i class="ti ti-clock" style="font-size: 13px;"></i>
                                        </button>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($d->total_serah_terima == $d->total_bpb)
                                        <span class="badge bg-success shadow-sm">Selesai</span>
                                    @else
                                        <span class="badge bg-danger shadow-sm">Proses</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
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

                                        @if ($d->kode_dept == 'MTC')
                                            @if (Auth::user()->id == '61' && $d->approve_manager == '0')
                                                <a href="#" class="btnApprove text-success"
                                                    data-approve="manager" no_bpb="{{ Crypt::encrypt($d->no_bpb) }}"
                                                    data-bs-toggle="tooltip" title="Approve Manager MTC">
                                                    <i class="ti ti-circle-check fs-5"></i>
                                                </a>
                                            @endif
                                            @if (
                                                (Auth::user()->id == '29' || Auth::user()->hasRole('direktur')) &&
                                                    $d->approve_manager == '1' &&
                                                    $d->approve_direktur == '0')
                                                <a href="#" class="btnApprove text-success"
                                                    data-approve="direktur" no_bpb="{{ Crypt::encrypt($d->no_bpb) }}"
                                                    data-bs-toggle="tooltip" title="Approve Direktur">
                                                    <i class="ti ti-circle-check fs-5"></i>
                                                </a>
                                            @endif
                                            @if (Auth::user()->id == '67' && $d->approve_manager == '1' && $d->approve_direktur == '1' && $d->approve_gudang == '0')
                                                <a href="#" class="btnApprove text-success" data-approve="1"
                                                    no_bpb="{{ Crypt::encrypt($d->no_bpb) }}"
                                                    data-bs-toggle="tooltip" title="Approve Gudang">
                                                    <i class="ti ti-circle-check fs-5"></i>
                                                </a>
                                            @endif
                                        @else
                                            @if (Auth::user()->id == $user && $d->approve_head_dept == '0')
                                                <a href="#" class="btnApprove text-success"
                                                    no_bpb="{{ Crypt::encrypt($d->no_bpb) }}"
                                                    data-bs-toggle="tooltip" title="Approve Head Dept">
                                                    <i class="ti ti-circle-check fs-5"></i>
                                                </a>
                                            @endif
                                            @if (Auth::user()->id == '67' && $d->approve_head_dept == '1' && $d->approve_gudang == '0')
                                                <a href="#" class="btnApprove text-success" data-approve="1"
                                                    no_bpb="{{ Crypt::encrypt($d->no_bpb) }}"
                                                    data-bs-toggle="tooltip" title="Approve Gudang">
                                                    <i class="ti ti-circle-check fs-5"></i>
                                                </a>
                                            @endif
                                        @endif

                                        @if (empty($d->tanggal_pembelian))
                                            <a href="#" class="btnEdit text-success"
                                                no_bpb="{{ Crypt::encrypt($d->no_bpb) }}" data-bs-toggle="tooltip"
                                                title="Edit">
                                                <i class="ti ti-edit fs-5"></i>
                                            </a>
                                        @endif
                                        <a href="#" class="btnShow text-info"
                                            no_bpb="{{ Crypt::encrypt($d->no_bpb) }}" data-bs-toggle="tooltip"
                                            title="Detail">
                                            <i class="ti ti-file-description fs-5"></i>
                                        </a>
                                        @php
                                            $canDelete =
                                                $d->kode_dept == 'MTC'
                                                    ? $d->approve_manager == '0'
                                                    : $d->approve_head_dept == '0';
                                        @endphp
                                        @if ($canDelete)
                                            <form method="POST" class="deleteform d-inline"
                                                action="{{ route('bpb.delete', Crypt::encrypt($d->no_bpb)) }}">
                                                @csrf
                                                @method('DELETE')
                                                <a href="#" class="delete-confirm text-danger"
                                                    data-bs-toggle="tooltip" title="Hapus">
                                                    <i class="ti ti-trash fs-5"></i>
                                                </a>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="card-footer py-2">
                <div style="float: right;">
                    {{ $bpb->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<x-modal-form id="modal" size="modal-xl" show="loadmodal" title="" />
@endsection

@push('myscript')
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
            $("#modal").modal("show");
            $(".modal-title").text("Edit BPB");
            $("#loadmodal").html(loadingElement());
            $("#loadmodal").load(`/bpb/${no_bpb}/edit`);
        });
    });
</script>
@endpush
