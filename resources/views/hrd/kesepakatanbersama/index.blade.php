@extends('layouts.app')
@section('titlepage', 'Kesepakatan Bersama')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Kesepakatan Bersama</h4>
            <small class="text-muted">Mengelola data kesepakatan bersama (KB) karyawan.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-folder me-1"></i>Human Resources</a>
                </li>
                <li class="breadcrumb-item active"><i class="ti ti-file-text me-1"></i>Kesepakatan Bersama</li>
            </ol>
        </nav>
    </div>
@endsection

<div class="row">
    <div class="col-lg-12 col-md-12">
        {{-- Filter Section --}}
        <form action="{{ route('kesepakatanbersama.index') }}" class="mb-2">
            <div class="row g-2">
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <x-input-with-icon label="Dari" value="{{ Request('dari') }}" name="dari" icon="ti ti-calendar" datepicker="flatpickr-date" />
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <x-input-with-icon label="Sampai" value="{{ Request('sampai') }}" name="sampai" icon="ti ti-calendar" datepicker="flatpickr-date" />
                </div>
                <div class="col-lg-5 col-md-6 col-sm-12">
                    <x-input-with-icon label="Nama Karyawan" value="{{ Request('nama_karyawan_search') }}" name="nama_karyawan_search" icon="ti ti-user" />
                </div>
                <div class="col-lg-1 col-md-6 col-sm-12">
                    <div class="form-group mb-3">
                        <button type="submit" class="btn btn-primary w-100"><i class="ti ti-search"></i></button>
                    </div>
                </div>
            </div>
        </form>

        {{-- Data Card --}}
        <div class="card shadow-sm border">
            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold text-white"><i class="ti ti-file-text me-2"></i>Data Kesepakatan Bersama</h6>
                </div>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover table-striped">
                    <thead class="text-white">
                        <tr style="background-color: #002e65;">
                            <th class="text-white">No.KB</th>
                            <th class="text-white">Tanggal</th>
                            <th class="text-white">NIK</th>
                            <th class="text-white" style="width: 15%">Nama</th>
                            <th class="text-white">Jabatan</th>
                            <th class="text-white">Dept.</th>
                            <th class="text-white">Cabang</th>
                            <th class="text-white text-center">Jeda</th>
                            <th class="text-white text-center">#</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($kesepakatanbersama as $d)
                            @php
                                $jmlhari = hitungJumlahHari($d->tanggal, date('Y-m-d'));
                            @endphp
                            <tr>
                                <td>{{ $d->no_kb }}</td>
                                <td>{{ formatIndo($d->tanggal) }}</td>
                                <td>{{ $d->nik }}</td>
                                <td><span class="fw-bold">{{ $d->nama_karyawan }}</span></td>
                                <td>{{ $d->nama_jabatan }}</td>
                                <td>{{ $d->kode_dept }}</td>
                                <td>{{ $d->kode_cabang }}</td>
                                <td class="text-center">
                                    <span class="badge bg-label-secondary">{{ $jmlhari }} Hari</span>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        @can('kb.show')
                                            <a href="{{ route('kesepakatanbersama.cetak', Crypt::encrypt($d->no_kb)) }}" class="text-primary" target="_blank" title="Cetak KB">
                                                <i class="ti ti-printer fs-5"></i>
                                            </a>
                                        @endcan

                                        @can('kontrakkerja.create')
                                            @if (empty($d->no_kontrak_baru))
                                                <a href="#" class="btnCreatekontrak text-danger" kode_penilaian="{{ Crypt::encrypt($d->kode_penilaian) }}" title="Buat Kontrak">
                                                    <i class="ti ti-file-plus fs-5"></i>
                                                </a>
                                            @else
                                                <a href="{{ route('kontrakkerja.cetak', Crypt::encrypt($d->no_kontrak_baru)) }}" class="text-info" target="_blank" title="Cetak Kontrak">
                                                    <i class="ti ti-printer fs-5"></i>
                                                </a>
                                            @endif
                                        @endcan

                                        @can('kb.edit')
                                            <a href="#" class="btnPotongan text-warning" no_kb="{{ Crypt::encrypt($d->no_kb) }}" title="Potongan">
                                                <i class="ti ti-tag fs-5"></i>
                                            </a>
                                        @endcan

                                        @can('kb.delete')
                                            @if (empty($d->no_kontrak_baru))
                                                <form method="POST" class="d-inline" action="{{ route('kesepakatanbersama.delete', Crypt::encrypt($d->no_kb)) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <a href="#" class="delete-confirm text-danger" title="Hapus">
                                                        <i class="ti ti-trash fs-5"></i>
                                                    </a>
                                                </form>
                                            @endif
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer py-2">
                <div style="float: right;">
                    {{ $kesepakatanbersama->links() }}
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


        $(".btnPotongan").click(function(e) {
            e.preventDefault();
            var no_kb = $(this).attr("no_kb");
            loading();
            $("#modal").modal("show");
            $(".modal-title").text("Input Potongan");
            $("#loadmodal").load(`/kesepakatanbersama/${no_kb}/potongan`);
        });

        $(".btnPotongan").click(function(e) {
            e.preventDefault();
            var no_kb = $(this).attr("no_kb");
            loading();
            $("#modal").modal("show");
            $(".modal-title").text("Input Potongan");
            $("#loadmodal").load(`/kesepakatanbersama/${no_kb}/potongan`);
        });

        $(".btnCreatekontrak").click(function(e) {
            e.preventDefault();
            var kode_penilaian = $(this).attr("kode_penilaian");
            loading();
            $("#modal").modal("show");
            $(".modal-title").text("Buat Kontrak");
            $("#loadmodal").load(`/kesepakatanbersama/${kode_penilaian}/createkontrak`);
        });
    });
</script>
@endpush
