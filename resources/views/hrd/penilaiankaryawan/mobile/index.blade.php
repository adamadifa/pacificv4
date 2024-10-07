@extends('layouts.app')
@section('titlepage', 'Penilaian Karyawan')

@section('content')
    {{-- @section('navigasi')
    <span>Penilaian Karyawan</span>
@endsection --}}
    <div class="row">
        <div class="col-lg-12 col-sm-12 col-xs-12">
            <div class="card">
                {{-- <div class="card-header">
                    @can('penilaiankaryawan.create')
                        <a href="#" class="btn btn-primary" id="btnCreate"><i class="fa fa-plus me-2"></i>
                            Buat Penilaian Karyawan
                        </a>
                    @endcan
                </div> --}}
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <form action="{{ route('penilaiankaryawan.index') }}">
                                <div class="row">
                                    <div class="col-lg-6 col-md-12 col-sm-12">
                                        <x-input-with-icon label="Dari" value="{{ Request('dari') }}" name="dari" icon="ti ti-calendar"
                                            datepicker="flatpickr-date" />
                                    </div>
                                    <div class="col-lg-6 col-md-12 col-sm-12">
                                        <x-input-with-icon label="Sampai" value="{{ Request('sampai') }}" name="sampai" icon="ti ti-calendar"
                                            datepicker="flatpickr-date" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <x-input-with-icon label="Nama Karyawan" value="{{ Request('nama_karyawan_search') }}" name="nama_karyawan_search"
                                            icon="ti ti-user" />
                                    </div>
                                </div>
                                @if (!empty($listApprovepenilaian))
                                    <div class="row">
                                        <div class="col-lg-6 col-sm-12 col-md-12">
                                            <div class="form-group">
                                                <select name="posisi_ajuan" id="posisi_ajuan" class="form-select">
                                                    <option value="">Poisi Ajuan</option>
                                                    @foreach ($listApprovepenilaian as $d)
                                                        <option value="{{ $d }}" {{ Request('posisi_ajuan') == $d ? 'selected' : '' }}>
                                                            {{ textUpperCase($d) }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 co-sm-12 col-md-12">
                                            <div class="form-group">
                                                <select name="status" id="status" class="form-select">
                                                    <option value="">Status</option>
                                                    <option value="pending" {{ Request('status') === 'pending' ? 'selected' : '' }}>
                                                        Pending</option>
                                                    <option value="disetujui" {{ Request('status') === 'disetujui' ? 'selected' : '' }}>
                                                        Disetujui</option>

                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group mb-3">
                                                <select name="status" id="status" class="form-select">
                                                    <option value="">Status</option>
                                                    <option value="pending" {{ Request('status') === 'pending' ? 'selected' : '' }}>
                                                        Pending</option>
                                                    <option value="disetujui" {{ Request('status') === 'disetujui' ? 'selected' : '' }}>
                                                        Disetujui</option>

                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div class="row">
                                    <div class="col">
                                        <div class="form-group mb-3">
                                            <button type="submit" class="btn btn-primary w-100"><i class="ti ti-search me-1"></i>Cari Data</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            @foreach ($penilaiankaryawan as $d)
                                @php
                                    $roles_approve = cekRoleapprove($d->kode_dept, $d->kode_cabang, $d->kategori_jabatan, $d->kode_jabatan);
                                    $end_role = end($roles_approve);
                                    if ($level_user != $end_role) {
                                        $index_role = array_search($role, $roles_approve);
                                        $next_role = $roles_approve[$index_role + 1];
                                    } else {
                                        $lastindex = count($roles_approve) - 1;
                                        $next_role = $roles_approve[$lastindex];
                                    }
                                @endphp
                                <div class="card mb-2 shadow-none  border {{ $d->status == 0 ? 'border-warning' : 'border-success' }}  p-0">
                                    <div class="card-body d-flex justify-content-between p-2">
                                        <div>
                                            <h6 class="m-0">{{ $d->kode_penilaian }}</h6>
                                            <h6 class="m-0 fw-bold">{{ textUpperCase($d->nama_karyawan) }}</h6>
                                            <h7 class="m-0">{{ DateToIndo($d->tanggal) }}</h7>
                                            <p>
                                                <small>Periode : {{ formatIndo($d->kontrak_dari) }} -
                                                    {{ formatIndo($d->kontrak_sampai) }}</small>
                                            </p>
                                        </div>
                                        <div>
                                            @if ($d->status == 0)
                                                @php
                                                    $color = 'bg-warning';
                                                @endphp
                                            @else
                                                @php
                                                    $color = 'bg-success';
                                                @endphp
                                            @endif
                                            <span
                                                class="badge {{ $color }}">{{ singkatString($d->posisi_ajuan) == 'AMH' ? 'HRD' : singkatString($d->posisi_ajuan) }}</span>
                                        </div>
                                    </div>

                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div style="float: right;">
                                {{ $penilaiankaryawan->links() }}
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
                $("#loadmodal").html(`<div class="sk-wave sk-primary" style="margin:auto">
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            </div>`);
            };


            $("#btnCreate").click(function(e) {
                e.preventDefault();
                loading();
                $("#modal").modal("show");
                $(".modal-title").text("Buat Penilaian Karyawan");
                $("#loadmodal").load(`/penilaiankaryawan/create`);
                $("#modal").find(".modal-dialog").removeClass('modal-lg');
            });

            $(".btnApprove").click(function(e) {
                e.preventDefault();
                var kode_penilaian = $(this).attr("kode_penilaian");
                // var kp = $(this).attr("kp");
                // alert(kp);
                loading();
                $("#modal").modal("show");
                $(".modal-title").text("Approve Penilaian Karyawan");
                $("#loadmodal").load(`/penilaiankaryawan/${kode_penilaian}/approve`);
                $("#modal").find(".modal-dialog").addClass('modal-lg');

            });

            $(".btnCreatekontrak").click(function(e) {
                e.preventDefault();
                var kode_penilaian = $(this).attr("kode_penilaian");
                loading();
                $("#modal").modal("show");
                $(".modal-title").text("Buat Kontrak");
                $("#loadmodal").load(`/kesepakatanbersama/${kode_penilaian}/createkontrak`);
            });

            $(".btnCreatekb").click(function(e) {
                e.preventDefault();
                var kode_penilaian = $(this).attr("kode_penilaian");
                loading();
                $("#modal").modal("show");
                $(".modal-title").text("Buat Kesepakatan Bersama");
                $("#loadmodal").load(`/kesepakatanbersama/${kode_penilaian}/create`);
            });


        });
    </script>
@endpush
