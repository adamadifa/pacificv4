@extends('layouts.app')
@section('titlepage', 'Config Approval Penilaian')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Config Approval Penilaian</h4>
            <small class="text-muted">Mengatur alur persetujuan penilaian karyawan.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-folder me-1"></i>HRD</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('penilaiankaryawan.index') }}">Penilaian Karyawan</a>
                </li>
                <li class="breadcrumb-item active"><i class="ti ti-settings me-1"></i>Config Approval</li>
            </ol>
        </nav>
    </div>
@endsection

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">List Configuration</h5>
                <a href="{{ route('penilaiankaryawanconfig.create') }}" class="btn btn-primary">
                    <i class="ti ti-plus me-1"></i> Tambah Config
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Departemen</th>
                                <th>Cabang</th>
                                <th>Kategori</th>
                                <th>Jabatan</th>
                                <th>Roles (Hierarchy)</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($config as $d)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $d->nama_dept ?? 'ALL' }}</td>
                                    <td>{{ $d->nama_cabang ?? 'ALL' }}</td>
                                    <td>{{ $d->kategori_jabatan ?? 'ALL' }}</td>
                                    <td>{{ $d->nama_jabatan ?? 'ALL' }}</td>
                                    <td>
                                        @foreach ($d->roles as $role)
                                            <span class="badge bg-label-info">{{ textUpperCase($role) }}</span>
                                            @if (!$loop->last)
                                                <i class="ti ti-chevron-right mx-1"></i>
                                            @endif
                                        @endforeach
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('penilaiankaryawanconfig.edit', $d->id) }}" class="btn btn-icon btn-label-success btn-sm">
                                                <i class="ti ti-edit"></i>
                                            </a>
                                            <form action="{{ route('penilaiankaryawanconfig.destroy', $d->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-icon btn-label-danger btn-sm delete-confirm">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
