@extends('layouts.app')
@section('titlepage', 'Karyawan')

@section('content')
@section('navigasi')
    <span>Karyawan</span>
@endsection

<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                @can('karyawan.create')
                    <a href="#" class="btn btn-primary" id="btncreateKaryawan"><i class="fa fa-plus me-2"></i> Tambah
                        Karyawan</a>
                @endcan
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <form action="{{ route('karyawan.index') }}">
                            @hasanyrole($roles_show_cabang)
                                <div class="row">
                                    <div class="col-lg-12 col-sm-12 col-md-12">
                                        <x-select label="Cabang" name="kode_cabang_search" :data="$cabang"
                                            key="kode_cabang" textShow="nama_cabang"
                                            selected="{{ Request('kode_cabang_search') }}" upperCase="true"
                                            select2="select2Kodecabangsearch" />
                                    </div>
                                </div>
                            @endhasanyrole
                            <div class="row">
                                <div class="col-lg-6 col-sm-12 col-md-12">
                                    <x-input-with-icon label="Cari Nama Karyawan" value="{{ Request('nama_karyawan') }}"
                                        name="nama_karyawan" icon="ti ti-search" />
                                </div>

                                <div class="col-lg-4 col-sm-12 col-md-12">
                                    <x-select label="Departemen" name="kode_dept" :data="$departemen" key="kode_dept"
                                        textShow="nama_dept" selected="{{ Request('kode_dept') }}" upperCase="true"
                                        select2="select2Kodedeptsearch" />
                                </div>
                                <div class="col-lg-2 col-sm-12 col-md-12">
                                    <x-select label="Group" name="kode_group" :data="$group" key="kode_group"
                                        textShow="nama_group" selected="{{ Request('kode_group') }}" upperCase="true" />
                                </div>

                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <button class="btn btn-primary w-100"><i
                                            class="ti ti-icons ti-search me-1"></i>Cari</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive mb-2">
                            <table class="table  table-hover table-bordered">
                                <thead class="table-dark">
                                    <tr>
                                        <th>No.</th>
                                        <th>NIK</th>
                                        <th>Nama Karyawan</th>
                                        <th>Dept</th>
                                        <th>Jabatan</th>
                                        <th>MP/PCF</th>
                                        <th>Cabang</th>
                                        <th>Klasifikasi</th>
                                        <th>Status</th>
                                        <th>Loc</th>
                                        <th>Pin</th>
                                        <th>Foto</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($karyawan as $d)
                                        <tr
                                            class="{{ $d->status_aktif_karyawan === '0' ? 'bg-danger text-white' : '' }}">
                                            <td class="text-center">
                                                {{ $loop->iteration + $karyawan->firstItem() - 1 }}
                                            </td>
                                            <td>{{ $d->nik }}</td>
                                            <td>{!! textCamelCase($d->nama_karyawan) !!}</td>
                                            <td>{{ $d->kode_dept }}</td>
                                            <td>{{ $d->nama_jabatan }}</td>
                                            <td>{{ $d->kode_perusahaan == 'MP' ? 'MP' : 'PCF' }}</td>
                                            <td>{{ $d->kode_cabang }}</td>
                                            <td>{{ $d->klasifikasi }}</td>
                                            <td>
                                                @if ($d->status_karyawan == 'T')
                                                    <span class="badge bg-success">T</span>
                                                @elseif ($d->status_karyawan == 'K')
                                                    <span class="badge bg-warning">K</span>
                                                @else
                                                    <span class="badge bg-danger">Outsource</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($d->lock_location == 1)
                                                    <a
                                                        href="{{ route('karyawan.unlocklocation', Crypt::encrypt($d->nik)) }}">
                                                        <i class="ti ti-lock-open text-success"></i>
                                                    </a>
                                                @else
                                                    <a
                                                        href="{{ route('karyawan.unlocklocation', Crypt::encrypt($d->nik)) }}">
                                                        <i class="ti ti-lock text-danger"></i>
                                                    </a>
                                                @endif
                                            </td>
                                            <td>{{ !empty($d->pin) ? $d->pin : '' }}</td>
                                            <td>
                                                @if (!empty($d->foto))
                                                    @if (Storage::disk('public')->exists('/karyawan/' . $d->foto))
                                                        <div class="avatar avatar-xs me-2">
                                                            <img src="{{ getfotoKaryawan($d->foto) }}" alt=""
                                                                class="rounded-circle">
                                                        </div>
                                                    @else
                                                        <div class="avatar avatar-xs me-2">
                                                            <img src="{{ asset('assets/img/avatars/No_Image_Available.jpg') }}"
                                                                alt="" class="rounded-circle">
                                                        </div>
                                                    @endif
                                                @else
                                                    <div class="avatar avatar-xs me-2">
                                                        <img src="{{ asset('assets/img/avatars/No_Image_Available.jpg') }}"
                                                            alt="" class="rounded-circle">
                                                    </div>
                                                @endif

                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    @can('karyawan.edit')
                                                        <div>
                                                            <a href="#" class="me-2 editKaryawan"
                                                                nik="{{ Crypt::encrypt($d->nik) }}">
                                                                <i class="ti ti-edit text-success"></i>
                                                            </a>
                                                        </div>
                                                    @endcan
                                                    @can('karyawan.show')
                                                        <div>
                                                            <a href="{{ route('karyawan.show', Crypt::encrypt($d->nik)) }}"
                                                                class="me-2">
                                                                <i class="ti ti-file-description text-info"></i>
                                                            </a>
                                                        </div>
                                                    @endcan
                                                    @can('karyawan.delete')
                                                        <div>
                                                            <form method="POST" name="deleteform" class="deleteform"
                                                                action="{{ route('karyawan.delete', Crypt::encrypt($d->nik)) }}">
                                                                @csrf
                                                                @method('DELETE')
                                                                <a href="#" class="delete-confirm ml-1">
                                                                    <i class="ti ti-trash text-danger"></i>
                                                                </a>
                                                            </form>
                                                        </div>
                                                    @endcan


                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                        <div style="float: right;">
                            {{ $karyawan->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<x-modal-form id="mdlcreateKaryawan" size="" show="loadcreateKaryawan" title="Tambah Karyawan" />
<x-modal-form id="mdleditKaryawan" size="" show="loadeditKaryawan" title="Edit Karyawan" />
@endsection
@push('myscript')
{{-- <script src="{{ asset('assets/js/pages/roles/create.js') }}"></script> --}}
<script>
    $(function() {
        $("#btncreateKaryawan").click(function(e) {
            $('#mdlcreateKaryawan').modal("show");
            $("#loadcreateKaryawan").load('/karyawan/create');
        });

        $(".editKaryawan").click(function(e) {
            var nik = $(this).attr("nik");
            e.preventDefault();
            $('#mdleditKaryawan').modal("show");
            $("#loadeditKaryawan").load('/karyawan/' + nik + '/edit');
        });

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

        const select2Kodedeptsearch = $('.select2Kodedeptsearch');
        if (select2Kodedeptsearch.length) {
            select2Kodedeptsearch.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Semua Departemen',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }
    });
</script>
@endpush
