@extends('layouts.app')
@section('titlepage', 'Dashboard')
@section('content')
    {{-- <style>
        .table-modal {
            height: auto;
            max-height: 550px;
            overflow-y: scroll;

        }
    </style> --}}
    <style>
        .detail {
            cursor: pointer;
        }
    </style>
@section('navigasi')
    @include('dashboard.navigasi')
@endsection
<div class="row">
    <div class="col-xl-12">
        @include('dashboard.welcome')
    </div>
</div>
<div class="row">
    <div class="col">
        <form action="{{ URL::current() }}" method="get">
            <div class="row">
                <div class="col">
                    <div class="form-group mb-3">
                        <select name="bulan" id="bulan" class="form-select">
                            <option value="">Bulan</option>
                            @foreach ($list_bulan as $d)
                                <option value="{{ $d['kode_bulan'] }}"
                                    {{ date('m') == $d['kode_bulan'] && empty(Request('bulan')) ? 'selected' : '' }}
                                    {{ Request('bulan') == $d['kode_bulan'] ? 'selected' : '' }}>{{ $d['nama_bulan'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group mb-3">
                        <select name="tahun" id="tahun" class="form-select">
                            <option value="">Tahun</option>
                            @for ($t = $start_year; $t <= date('Y'); $t++)
                                <option value="{{ $t }}"
                                    {{ date('Y') == $t && empty(Request('tahun')) ? 'selected' : '' }}
                                    {{ Request('tahun') == $t ? 'selected' : '' }}>{{ $t }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div class="col">
                    <button type="submit" name="submit" class="btn btn-primary"><i
                            class="ti ti-search me-1"></i></button>
                </div>
            </div>

        </form>
    </div>
</div>
<div class="row">
    <div class="col">
        <div class="table-modal">
            <table class="table table-bordered" style="width: 150%">
                <thead class="table-dark">
                    <tr>
                        <th rowspan="2">Nama</th>
                        <th rowspan="2">Cabang</th>
                        <th colspan="{{ $jmlhari }}" class="text-center">Bulan {{ $namabulan[$bulan * 1] }}
                            {{ $tahun }}</th>
                    </tr>
                    <tr>
                        @php
                            $dari = $start_date;
                        @endphp
                        @while (strtotime($dari) <= strtotime($end_date))
                            <th>{{ date('d', strtotime($dari)) }}</th>
                            @php
                                $dari = date('Y-m-d', strtotime('+1 day', strtotime($dari)));
                            @endphp
                        @endwhile
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $d)
                        <tr>
                            <td>{{ $d->name }}</td>
                            <td>{{ $d->kode_cabang }}</td>
                            @php
                                $start = $start_date;
                                $i = 1;
                            @endphp
                            @while (strtotime($start) <= strtotime($end_date))
                                @php
                                    $bgcolor = !empty($d->{"tgl_$i"}) ? 'bg-success text-white' : '';
                                @endphp
                                <td class="{{ $bgcolor }} detail text-center" id_user="{{ $d->id }}"
                                    tanggal="{{ $start }}">
                                    {{ !empty($d->{"tgl_$i"}) ? $d->{"tgl_$i"} : '' }}</td>
                                @php
                                    $i++;
                                    $start = date('Y-m-d', strtotime('+1 day', strtotime($start)));
                                @endphp
                            @endwhile
                        </tr>
                    @endforeach
                    @if ($user->hasRole(['super admin', 'direktur', 'gm marketing', 'gm administrasi']))


                        <tr class="table-dark">
                            <th colspan="{{ $jmlhari + 2 }}">RSM</th>
                        </tr>
                        @foreach ($rsm as $d)
                            <tr>
                                <td>{{ $d->name }}</td>
                                <td>{{ $d->nama_regional }}</td>
                                @php
                                    $start = $start_date;
                                    $i = 1;
                                @endphp
                                @while (strtotime($start) <= strtotime($end_date))
                                    @php
                                        $bgcolor = !empty($d->{"tgl_$i"}) ? 'bg-success text-white' : '';
                                    @endphp
                                    <td class="{{ $bgcolor }} detail text-center" tanggal="{{ $start }}"
                                        id_user="{{ $d->id }}">
                                        {{ !empty($d->{"tgl_$i"}) ? $d->{"tgl_$i"} : '' }}</td>
                                    @php
                                        $i++;
                                        $start = date('Y-m-d', strtotime('+1 day', strtotime($start)));
                                    @endphp
                                @endwhile
                            </tr>
                        @endforeach
                        <tr class="table-dark">
                            <th colspan="{{ $jmlhari + 2 }}">GM MARKETING</th>
                        </tr>
                        @foreach ($gm as $d)
                            <tr>
                                <td>{{ $d->name }}</td>
                                <td>{{ $d->kode_cabang }}</td>
                                @php
                                    $start = $start_date;
                                    $i = 1;
                                @endphp
                                @while (strtotime($start) <= strtotime($end_date))
                                    @php
                                        $bgcolor = !empty($d->{"tgl_$i"}) ? 'bg-success text-white' : '';
                                    @endphp
                                    <td class="{{ $bgcolor }} detail text-center" id_user="{{ $d->id }}"
                                        tanggal="{{ $start }}">
                                        {{ !empty($d->{"tgl_$i"}) ? $d->{"tgl_$i"} : '' }}</td>
                                    @php
                                        $i++;
                                        $start = date('Y-m-d', strtotime('+1 day', strtotime($start)));
                                    @endphp
                                @endwhile
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
<x-modal-form id="modal" show="loadmodal" title="Detail Aktifitas" size="modal-lg" />
@endsection
@push('myscript')
<script>
    $(function() {
        $(".table-modal, .table-modal2").freezeTable({
            'scrollable': true,
            // 'freezeColumn': false,
            'freezeHead': false,
            'columnNum': 2,

        });

        $(".detail").click(function() {
            let id_user = $(this).attr('id_user');
            let tanggal = $(this).attr('tanggal');
            $("#modal").modal("show");
            $("#loadmodal").load('/aktifitassmm/' + id_user + '/' + tanggal + '/getdetailaktifitas');
        })
    });
</script>
@endpush
