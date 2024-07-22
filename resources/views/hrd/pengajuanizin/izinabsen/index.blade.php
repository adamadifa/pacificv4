@extends('layouts.app')
@section('titlepage', 'Izin Absen')

@section('content')
@section('navigasi')
    <span>Izin Absen</span>
@endsection
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="nav-align-top nav-tabs-shadow mb-4">
            @include('layouts.navigation_pengajuanizin')
            <div class="tab-content">
                <div class="tab-pane fade active show" id="navs-justified-home" role="tabpanel">
                    @can('izinabsen.create')
                        <a href="#" class="btn btn-primary" id="btnCreate"><i class="fa fa-plus me-2"></i>
                            Tambah Data</a>
                    @endcan
                    <div class="row mt-2">
                        <div class="col-12">
                            <form action="{{ route('izinabsen.index') }}">
                                <div class="row">
                                    <div class="col-lg-6 col-sm-12 col-md-12">
                                        <x-input-with-icon label="Dari" value="{{ Request('dari') }}" name="dari" icon="ti ti-calendar"
                                            datepicker="flatpickr-date" />
                                    </div>
                                    <div class="col-lg-6 col-sm-12 col-md-12">
                                        <x-input-with-icon label="Sampai" value="{{ Request('sampai') }}" name="sampai" icon="ti ti-calendar"
                                            datepicker="flatpickr-date" />
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
                                            <th>Kode</th>
                                            <th>Tanggal</th>
                                            <th>Nik</th>
                                            <th>Nama Karyawan</th>
                                            <th>Jabatan</th>
                                            <th>Dept</th>
                                            <th>Cabang</th>
                                            <th>Lama</th>
                                            <th>Keterangan</th>
                                            <th>Posisi</th>
                                            <th>Status</th>
                                            <th>#</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($izinabsen as $d)
                                            <tr>
                                                <td>{{ $d->kode_izin }}</td>
                                                <td>{{ formatIndo($d->tanggal) }}</td>
                                                <td>{{ $d->nik }}</td>
                                                <td>{{ formatName($d->nama_karyawan) }}</td>
                                                <td>{{ $d->nama_jabatan }}</td>
                                                <td>{{ $d->kode_dept }}</td>
                                                <td>{{ $d->kode_cabang }}</td>
                                                <td>
                                                    @php
                                                        $jmlhari = hitungJumlahHari($d->dari, $d->sampai);
                                                    @endphp
                                                    {{ $jmlhari }} Hari
                                                </td>
                                                <td style="width: 20%">
                                                    {{ $d->keterangan }}
                                                </td>
                                                <td>
                                                    <span class="badge bg-primary">
                                                        {{ singkatString($d->posisi_ajuan) == 'AMH' ? 'HRD' : singkatString($d->posisi_ajuan) }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    @if ($d->status == '1')
                                                        <i class="ti ti-checks text-success"></i>
                                                    @else
                                                        <i class="ti ti-hourglass-low text-warning"></i>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex">
                                                        @can('izinabsen.delete')
                                                            <form class="delete-form me-1"
                                                                action="{{ route('izinabsen.delete', Crypt::encrypt($d->kode_izin)) }}" method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <a href="#" class="delete-confirm">
                                                                    <i class="ti ti-trash text-danger"></i>
                                                                </a>
                                                            </form>
                                                        @endcan
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div style="float: right;">
                                {{-- {{ $barangmasuk->links() }} --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<x-modal-form id="modal" size="" show="loadmodal" title="" />
@endsection
@push('myscript')
<script>
    $(function() {
        function loading() {
            $("#loadmodal").html(
                `<div class="sk-wave sk-primary" style="margin:auto">
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            </div>`
            );
        }
        $("#btnCreate").click(function() {
            $("#modal").modal("show");
            loading();
            $("#modal").find(".modal-title").text("Buat Izin Absen");
            $("#loadmodal").load("/izinabsen/create");
        });
    });
</script>
@endpush
