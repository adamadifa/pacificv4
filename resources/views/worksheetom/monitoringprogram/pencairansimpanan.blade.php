@extends('layouts.app')
@section('titlepage', 'Pencairan Simpanan')

@section('content')
@section('navigasi')
    <span>Saldo Simpanan</span>
@endsection
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="nav-align-top nav-tabs-shadow mb-4">
            @include('layouts.navigation_monitoringprogram')
            @include('layouts.navigation_simpanan')
            <div class="tab-content">
                <div class="tab-pane fade active show" id="navs-justified-home" role="tabpanel">
                    <div class="row mt-2">
                        <div class="col-12">
                            <form action="{{ URL::current() }}">
                                @hasanyrole($roles_show_cabang)
                                    <div class="form-group mb-3">
                                        <select name="kode_cabang" id="kode_cabang" class="form-select select2Kodecabang">
                                            <option value="">Semua Cabang</option>
                                            @foreach ($cabang as $d)
                                                <option {{ Request('kode_cabang') == $d->kode_cabang ? 'selected' : '' }}
                                                    value="{{ $d->kode_cabang }}">
                                                    {{ textUpperCase($d->nama_cabang) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endrole
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="form-group mb-3">
                                            <button class="btn btn-primary w-100"><i
                                                    class="ti ti-heart-rate-monitor me-1"></i>Tampilkan
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
                                <table id="example" class="display nowrap table  table-bordered" style="width:100%">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>No. Ajuan</th>
                                            <th>Kode</th>
                                            <th>Nama Pelanggan</th>
                                            <th>Salesman</th>
                                            <th>Jumlah</th>
                                            <th>Metode Bayar</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Bukti</th>
                                            <th class="text-center">#</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($pencairan as $d)
                                            <tr>
                                                <td>{{ $d->kode_pencairan }}</td>
                                                <td>{{ $d->kode_pelanggan }}</td>
                                                <td>{{ $d->nama_pelanggan }}</td>
                                                <td>{{ $d->nama_salesman }}</td>
                                                <td class="text-end">{{ formatAngka($d->jumlah) }}</td>
                                                <td>
                                                    {{ $d->metode_pembayaran }}
                                                </td>
                                                <td class="text-center">
                                                    @if ($d->status == 0)
                                                        <i class="ti ti-hourglass-low text-warning"></i>
                                                    @elseif ($d->status == 1)
                                                        <i class="ti ti-check text-success"></i>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if (!empty($d->bukti))
                                                        <a href="{{ url($d->bukti) }}" target="_blank">
                                                            <i class="ti ti-receipt text-success"></i>
                                                        </a>
                                                    @else
                                                        <i class="ti ti-hourglass-empty text-warning"></i>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex">
                                                        @if (
                                                            $d->status == 0 &&
                                                                auth()->user()->hasRole(['kepala admin', 'super admin']))
                                                            <form method="POST" name="deleteform" class="deleteform"
                                                                action="{{ route('monitoringprogram.deletepencairansimpanan', Crypt::encrypt($d->kode_pencairan)) }}">
                                                                @csrf
                                                                @method('DELETE')
                                                                <a href="#" class="delete-confirm ml-1">
                                                                    <i class="ti ti-trash text-danger"></i>
                                                                </a>
                                                            </form>
                                                        @endif

                                                        @if (auth()->user()->hasRole(['manager keuangan', 'staff keuangan']))
                                                            <a href="{{ route('monitoringprogram.cetakpencairansimpanan', Crypt::encrypt($d->kode_pencairan)) }}"
                                                                class="btnCetak me-1"><i
                                                                    class="ti ti-printer text-primary"></i></a>
                                                            <a href="#" class="btnApprove me-1"
                                                                kode_pencairan="{{ Crypt::encrypt($d->kode_pencairan) }}">
                                                                <i class="ti ti-external-link text-success"></i>
                                                            </a>
                                                        @endif

                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                            <div style="float: right;">
                                {{ $pencairan->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<x-modal-form id="modal" size="" show="loadmodal" title="" />
<x-modal-form id="modalDetailfaktur" size="modal-xl" show="loadmodaldetailfaktur" title="" />
<x-modal-form id="modalApprove" size="" show="loadmodalapprove" title="" />
@endsection
@push('myscript')
<script>
    $(".btnApprove").click(function(e) {
        const kode_pencairan = $(this).attr('kode_pencairan');
        e.preventDefault();
        $('#modalApprove').modal("show");
        $("#modalApprove").find(".modal-title").text("Approve Pencairan Simpanan");
        $("#loadmodalapprove").html(`<div class="sk-wave sk-primary" style="margin:auto">
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            </div>`);
        $("#loadmodalapprove").load('/monitoringprogram/' + kode_pencairan + '/approvepencairansimpanan');
    });
</script>
@endpush
