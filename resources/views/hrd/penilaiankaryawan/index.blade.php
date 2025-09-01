@extends('layouts.app')
@section('titlepage', 'Penilaian Karyawan')

@section('content')
@section('navigasi')
    <span>Penilaian Karyawan</span>
@endsection
<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                @can('penilaiankaryawan.create')
                    <a href="#" class="btn btn-primary" id="btnCreate"><i class="fa fa-plus me-2"></i>
                        Buat Penilaian Karyawan
                    </a>
                @endcan
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <form action="{{ route('penilaiankaryawan.index') }}">
                            <div class="row">
                                <div class="col-lg-6 col-md-12 col-sm-12">
                                    <x-input-with-icon label="Dari" value="{{ Request('dari') }}" name="dari"
                                        icon="ti ti-calendar" datepicker="flatpickr-date" />
                                </div>
                                <div class="col-lg-6 col-md-12 col-sm-12">
                                    <x-input-with-icon label="Sampai" value="{{ Request('sampai') }}" name="sampai"
                                        icon="ti ti-calendar" datepicker="flatpickr-date" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <x-input-with-icon label="Nama Karyawan"
                                        value="{{ Request('nama_karyawan_search') }}" name="nama_karyawan_search"
                                        icon="ti ti-user" />
                                </div>
                            </div>
                            @if (!empty($listApprovepenilaian))
                                <div class="row">
                                    <div class="col-lg-6 col-sm-12 col-md-12">
                                        <div class="form-grou mb-3">
                                            <select name="posisi_ajuan" id="posisi_ajuan" class="form-select">
                                                <option value="">Poisi Ajuan</option>
                                                @foreach ($listApprovepenilaian as $d)
                                                    <option value="{{ $d }}"
                                                        {{ Request('posisi_ajuan') == $d ? 'selected' : '' }}>
                                                        {{ textUpperCase($d) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 co-sm-12 col-md-12">
                                        <div class="form-group mb-3">
                                            <select name="status" id="status" class="form-select">
                                                <option value="">Status</option>
                                                <option value="pending"
                                                    {{ Request('status') === 'pending' ? 'selected' : '' }}>
                                                    Pending</option>
                                                <option value="disetujui"
                                                    {{ Request('status') === 'disetujui' ? 'selected' : '' }}>
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
                                                <option value="pending"
                                                    {{ Request('status') === 'pending' ? 'selected' : '' }}>
                                                    Pending</option>
                                                <option value="disetujui"
                                                    {{ Request('status') === 'disetujui' ? 'selected' : '' }}>
                                                    Disetujui</option>

                                            </select>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="row">
                                <div class="col">
                                    <div class="form-group mb-3">
                                        <button type="submit" class="btn btn-primary w-100"><i
                                                class="ti ti-search me-1"></i>Cari Data</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive mb-2">
                            <table class="table table-bordered">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Kode</th>
                                        <th>Tanggal</th>
                                        <th>NIK</th>
                                        <th>Nama</th>
                                        <th>Jabatan</th>
                                        <th>Dept.</th>
                                        <th>CBG</th>
                                        <th>Periode</th>
                                        <th class="text-center">KB</th>
                                        <th>Posisi</th>
                                        <th class="text-center">Status</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($penilaiankaryawan as $d)
                                        @php
                                            $roles_approve = cekRoleapprove(
                                                $d->kode_dept,
                                                $d->kode_cabang,
                                                $d->kategori_jabatan,
                                                $d->kode_jabatan,
                                            );
                                            $end_role = end($roles_approve);
                                            if ($level_user != $end_role) {
                                                $index_role = array_search($role, $roles_approve);
                                                $next_role = $roles_approve[$index_role + 1];
                                            } else {
                                                $lastindex = count($roles_approve) - 1;
                                                $next_role = $roles_approve[$lastindex];
                                            }

                                        @endphp
                                        <tr>
                                            <td>{{ $d->kode_penilaian }} </td>
                                            <td>{{ formatIndo($d->tanggal) }}</td>
                                            <td>{{ $d->nik }}</td>
                                            <td>{{ formatName($d->nama_karyawan) }}</td>
                                            <td>
                                                @if (!empty($d->alias_jabatan))
                                                    {{ $d->alias_jabatan }}
                                                @else
                                                    {{ $d->nama_jabatan }}
                                                @endif
                                            </td>
                                            <td>{{ $d->kode_dept }}</td>
                                            <td>{{ $d->kode_cabang }}</td>
                                            <td>{{ formatIndo($d->kontrak_dari) }} -
                                                {{ formatIndo($d->kontrak_sampai) }}</td>
                                            <td class="text-center">
                                                {{-- {{ $d->no_kb }} --}}
                                                @if ($d->status == 1)
                                                    @if ($d->status_pemutihan == 1 && empty($d->no_kb))
                                                        @can('kb.create')
                                                            <a href="#"
                                                                kode_penilaian="{{ Crypt::encrypt($d->kode_penilaian) }}"
                                                                class="btnCreatekb me-1"><i
                                                                    class="ti ti-file-plus text-warning"></i>
                                                            </a>
                                                        @else
                                                            <i class="ti ti-checks text-success"></i>
                                                        @endcan
                                                    @else
                                                        @if (!empty($d->no_kb))
                                                            <a href="{{ route('kesepakatanbersama.cetak', Crypt::encrypt($d->no_kb)) }}"
                                                                target="_blank">
                                                                <i class="ti ti-printer text-warning"></i>
                                                            </a>
                                                        @endif
                                                    @endif
                                                @else
                                                    <i class="ti ti-checks text-success"></i>
                                                @endif

                                            </td>
                                            <td>
                                                <span
                                                    class="badge bg-primary">{{ singkatString($d->posisi_ajuan) == 'AMH' ? 'HRD' : singkatString($d->posisi_ajuan) }}</span>
                                            </td>
                                            <td class="text-center">
                                                @if ($d->status == '1')
                                                    <i class="ti ti-checks text-success"></i>
                                                @else
                                                    <i class="ti ti-hourglass-low text-warning"></i>
                                                @endif
                                            </td>
                                            <td>
                                                {{-- {{ $d->kode_penilaian }} --}}
                                                <div class="d-flex">
                                                    {{-- {{ $level_user }} --}}
                                                    @can('penilaiankaryawan.edit')
                                                        @if (
                                                            ($d->status === '0' && $d->id_penerima === $d->id_user) ||
                                                                ($d->status === '0' &&
                                                                    $d->id_pengirim === $d->id_user &&
                                                                    $d->posisi_ajuan === $next_role &&
                                                                    $level_user != 'direktur') ||
                                                                ($level_user == 'asst. manager hrd' && $d->status === '0') ||
                                                                ($level_user == 'spv presensi' && $d->status === '0') ||
                                                                ($level_user == 'direktur' && $d->status === '0') ||
                                                                ($level_user == 'gm marketing' && $d->status === '0') ||
                                                                ($level_user == $d->posisi_ajuan && $d->status == '0'))
                                                            <a href="{{ route('penilaiankaryawan.edit', Crypt::encrypt($d->kode_penilaian)) }}"
                                                                class="me-1">
                                                                <i class="ti ti-edit text-success"></i>
                                                            </a>
                                                        @else
                                                            @if (in_array($level_user, ['spv presensi', 'super admin']))
                                                                <a href="{{ route('penilaiankaryawan.edit', Crypt::encrypt($d->kode_penilaian)) }}"
                                                                    class="me-1">
                                                                    <i class="ti ti-edit text-success"></i>
                                                                </a>
                                                            @endif
                                                        @endif
                                                    @endcan
                                                    @can('penilaiankaryawan.show')
                                                        <a href=" {{ route('penilaiankaryawan.cetak', Crypt::encrypt($d->kode_penilaian)) }}"
                                                            class="me-1" target="_blank">
                                                            <i class="ti ti-printer text-primary"></i>
                                                        </a>
                                                    @endcan
                                                    @can('kontrakkerja.create')
                                                        {{-- {{ $d->no_kontrak_baru }} --}}
                                                        @if ($d->status == '1' && empty($d->no_kontrak_baru) && $d->status_pemutihan === '0')
                                                            <a href="#" class="btnCreatekontrak me-1"
                                                                kode_penilaian="{{ Crypt::encrypt($d->kode_penilaian) }}">
                                                                <i class="ti ti-file-plus text-danger"></i>
                                                            </a>
                                                        @endif
                                                    @endcan
                                                    @can('kontrakkerja.show')
                                                        @if ($d->status == '1' && !empty($d->no_kontrak_baru))
                                                            <a href="{{ route('kontrakkerja.cetak', Crypt::encrypt($d->no_kontrak_baru)) }}"
                                                                class="me-1" target="_blank">
                                                                <i class="ti ti-printer text-success"></i>
                                                            </a>
                                                        @endif
                                                    @endcan
                                                    @can('penilaiankaryawan.approve')
                                                        @if ($level_user == $d->posisi_ajuan && $d->status === '0')
                                                            <a href="#" class="btnApprove me-1"
                                                                kode_penilaian="{{ Crypt::encrypt($d->kode_penilaian) }}"
                                                                kp = "{{ $d->kode_penilaian }}">
                                                                <i class="ti ti-external-link text-success"></i>
                                                            </a>
                                                        @elseif ($d->posisi_ajuan == $next_role && $d->status === '0')
                                                            <form method="POST" name="deleteform" class="deleteform"
                                                                action="{{ route('penilaiankaryawan.cancel', Crypt::encrypt($d->kode_penilaian)) }}">
                                                                @csrf
                                                                @method('DELETE')
                                                                <a href="#" class="cancel-confirm me-1">
                                                                    <i class="ti ti-square-rounded-x text-danger"></i>
                                                                </a>
                                                            </form>
                                                        @elseif ($level_user == $d->posisi_ajuan && $d->status === '1')
                                                            <form method="POST" name="deleteform" class="deleteform"
                                                                action="{{ route('penilaiankaryawan.cancel', Crypt::encrypt($d->kode_penilaian)) }}">
                                                                @csrf
                                                                @method('DELETE')
                                                                <a href="#" class="cancel-confirm me-1">
                                                                    <i class="ti ti-square-rounded-x text-danger"></i>
                                                                </a>
                                                            </form>
                                                        @endif
                                                    @endcan
                                                    {{-- {{ $d->id_penerima }} - {{ $d->id_user }} --}}
                                                    @can('penilaiankaryawan.delete')
                                                        @if (
                                                            ($d->status === '0' && $d->id_penerima === $d->id_user) ||
                                                                ($d->status === '0' &&
                                                                    $d->id_pengirim === $d->id_user &&
                                                                    $d->posisi_ajuan === $next_role &&
                                                                    $level_user != 'direktur'))
                                                            <form method="POST" name="deleteform" class="deleteform"
                                                                action="{{ route('penilaiankaryawan.delete', Crypt::encrypt($d->kode_penilaian)) }}">
                                                                @csrf
                                                                @method('DELETE')
                                                                <a href="#" class="delete-confirm me-1">
                                                                    <i class="ti ti-trash text-danger"></i>
                                                                </a>
                                                            </form>
                                                        @endif
                                                    @endcan

                                                    @hasanyrole('asst. manager hrd')
                                                        <form method="POST" name="deleteform" class="deleteform"
                                                            action="{{ route('penilaiankaryawan.delete', Crypt::encrypt($d->kode_penilaian)) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <a href="#" class="delete-confirm me-1">
                                                                <i class="ti ti-trash text-danger"></i>
                                                            </a>
                                                        </form>
                                                    @endhasanyrole

                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div style="float: right;">
                            {{ $penilaiankaryawan->links() }}
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
