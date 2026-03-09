@extends('layouts.app')
@section('titlepage', 'Target Komisi')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Target Komisi</h4>
            <small class="text-muted">Kelola target komisi salesman per cabang.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-target-arrow me-1"></i>Marketing</a>
                </li>
                <li class="breadcrumb-item active">Target Komisi</li>
            </ol>
        </nav>
    </div>
@endsection

<div class="row">
    <div class="col-lg-10 col-md-12 col-sm-12">
        {{-- Modern Navigation Header --}}
        <div class="mb-3">
            @include('layouts.navigation_targetkomisi')
        </div>

        {{-- Filter Section --}}
        <form action="{{ route('targetkomisi.index') }}">
            <div class="card shadow-none border-0 bg-transparent mb-3">
                <div class="card-body p-0">
                    <div class="row">
                        @hasanyrole($roles_show_cabang)
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="form-group">
                                    <x-select label="Semua Cabang" name="kode_cabang_search" :data="$cabang" key="kode_cabang"
                                        textShow="nama_cabang" upperCase="true" selected="{{ Request('kode_cabang_search') }}"
                                        select2="select2Kodecabangsearch" hideLabel="true" />
                                </div>
                            </div>
                        @endrole
                        <div class="col-lg-3 col-sm-12 col-md-12">
                            <div class="form-group">
                                <select name="posisi_ajuan" id="posisi_ajuan" class="form-select">
                                    <option value="">Posisi Ajuan</option>
                                    @foreach ($roles_approve_targetkomisi as $role)
                                        <option value="{{ $role }}"
                                            {{ Request('posisi_ajuan') == $role ? 'selected' : '' }}>
                                            {{ textUpperCase($role) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-12 col-md-12">
                            <div class="form-group">
                                <select name="bulan" id="bulan" class="form-select">
                                    <option value="">Bulan</option>
                                    @foreach ($list_bulan as $d)
                                        <option {{ Request('bulan') == $d['kode_bulan'] ? 'selected' : '' }}
                                            value="{{ $d['kode_bulan'] }}">
                                            {{ $d['nama_bulan'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-2 col-sm-12 col-md-12">
                            <div class="form-group">
                                <select name="tahun" id="tahun" class="form-select">
                                    <option value="">Tahun</option>
                                    @for ($t = $start_year; $t <= date('Y'); $t++)
                                        <option @if (!empty(Request('tahun'))) {{ Request('tahun') == $t ? 'selected' : '' }}
                                                @else {{ date('Y') == $t ? 'selected' : '' }} @endif
                                            value="{{ $t }}">{{ $t }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 co-sm-12 col-md-12">
                            <div class="form-group">
                                <select name="status" id="status" class="form-select">
                                    <option value="">Status</option>
                                    <option value="0" {{ Request('status') === '0' ? 'selected' : '' }}>Pending</option>
                                    <option value="1" {{ Request('status') === '1' ? 'selected' : '' }}>Disetujui</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-1 col-sm-12 col-md-12">
                            <div class="form-group">
                                <button class="btn btn-primary w-100"><i class="ti ti-search"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        {{-- Data Card --}}
        <div class="card shadow-sm border">
            <div class="card-header border-bottom py-3"
                style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold text-white"><i class="ti ti-target-arrow me-2"></i>Data Target Komisi</h6>
                    @can('targetkomisi.create')
                        <a href="#" class="btn btn-primary btnCreate btn-sm"><i class="ti ti-plus me-1"></i> Buat Target</a>
                    @endcan
                </div>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover table-striped table-bordered">
                    <thead>
                        <tr>
                            <th class="text-white" style="background-color: #002e65 !important;">Kode</th>
                            <th class="text-white" style="background-color: #002e65 !important;">Bulan</th>
                            <th class="text-white" style="background-color: #002e65 !important;">Tahun</th>
                            <th class="text-white" style="background-color: #002e65 !important;">Cabang</th>
                            <th class="text-white" style="background-color: #002e65 !important;">Posisi Ajuan</th>
                            <th class="text-center text-white" style="background-color: #002e65 !important;">Status</th>
                            <th class="text-white" style="background-color: #002e65 !important;">Tanggal</th>
                            <th class="text-center text-white" style="background-color: #002e65 !important;">#</th>
                        </tr>
                    </thead>
                    <tbody>
                                    @foreach ($targetkomisi as $d)
                                        @php
                                            if ($level_user == 'regional sales manager') {
                                                $nextlevel = 'gm marketing';
                                            } elseif ($level_user == 'gm marketing') {
                                                $nextlevel = 'direktur';
                                            }
                                        @endphp
                                        <tr>
                                            <td>{{ $d->kode_target }}</td>
                                            <td>{{ $nama_bulan[$d->bulan] }}</td>
                                            <td>{{ $d->tahun }}</td>
                                            <td>{{ textUpperCase($d->nama_cabang) }}</td>
                                            <td>
                                                @php
                                                    $color = match ($d->role) {
                                                        'regional sales manager' => 'bg-info',
                                                        'gm marketing' => 'bg-primary',
                                                        'direktur' => 'bg-success',
                                                        default => 'bg-secondary',
                                                    };
                                                @endphp
                                                <span class="badge {{ $color }}">{{ textCamelCase($d->role) }}</span>
                                            </td>
                                            <td class="text-center">
                                                @if ($d->status == '0')
                                                    <i class="ti ti-hourglass-empty text-warning fs-4"></i>
                                                @else
                                                    <i class="ti ti-checks text-success fs-4"></i>
                                                @endif
                                            </td>
                                            <td>{{ !empty($d->created_at) ? date('d-m-y H:i', strtotime($d->created_at)) : '' }}</td>
                                            <td>
                                                <div class="d-flex justify-content-center gap-2">
                                                    @can('targetkomisi.approve')
                                                        @if ($d->status_disposisi == '0' || $level_user == 'regional sales manager')
                                                            <a href="#" class="btnApprove text-info"
                                                                kode_target="{{ Crypt::encrypt($d->kode_target) }}"
                                                                data-bs-toggle="tooltip" title="Approve">
                                                                <i class="ti ti-send fs-4"></i>
                                                            </a>
                                                        @else
                                                            @if ($level_user == 'direktur' && $d->status_disposisi == '1')
                                                                <form method="POST"
                                                                    action="{{ route('targetkomisi.cancel', Crypt::encrypt($d->kode_target)) }}">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <a href="#" class="cancel-confirm text-danger"
                                                                        data-bs-toggle="tooltip" title="Cancel">
                                                                        <i class="ti ti-square-rounded-x fs-4"></i>
                                                                    </a>
                                                                </form>
                                                            @elseif ($d->status_ajuan == '0' && $d->role == $nextlevel)
                                                                <form method="POST"
                                                                    action="{{ route('targetkomisi.cancel', Crypt::encrypt($d->kode_target)) }}">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <a href="#" class="cancel-confirm text-danger"
                                                                        data-bs-toggle="tooltip" title="Cancel">
                                                                        <i class="ti ti-square-rounded-x fs-4"></i>
                                                                    </a>
                                                                </form>
                                                            @endif
                                                        @endif
                                                    @endcan

                                                    @can('targetkomisi.edit')
                                                        @if (
                                                            ($d->id_pengirim == auth()->user()->id && !in_array($level_user, $roles_approve_targetkomisi)) ||
                                                                $level_user == 'super admin' ||
                                                                $level_user == 'regional sales manager' ||
                                                                (in_array($level_user, $roles_approve_targetkomisi) && $d->status_disposisi == '0') ||
                                                                (in_array($level_user, $roles_approve_targetkomisi) &&
                                                                    $d->id_pengirim == auth()->user()->id &&
                                                                    $d->status_ajuan == '0'))
                                                            <a href="#" class="btnEdit text-success"
                                                                kode_target="{{ Crypt::encrypt($d->kode_target) }}"
                                                                data-bs-toggle="tooltip" title="Edit">
                                                                <i class="ti ti-edit fs-4"></i>
                                                            </a>
                                                        @endif
                                                    @endcan

                                                    @can('targetkomisi.show')
                                                        <a href="#" class="btnShow text-info"
                                                            kode_target="{{ Crypt::encrypt($d->kode_target) }}"
                                                            data-bs-toggle="tooltip" title="Detail">
                                                            <i class="ti ti-file-description fs-4"></i>
                                                        </a>
                                                    @endcan

                                                    @can('targetkomisi.delete')
                                                        @if ($d->id_pengirim == auth()->user()->id)
                                                            <form method="POST"
                                                                action="{{ route('targetkomisi.delete', Crypt::encrypt($d->kode_target)) }}">
                                                                @csrf
                                                                @method('DELETE')
                                                                <a href="#" class="delete-confirm text-danger"
                                                                    data-bs-toggle="tooltip" title="Hapus">
                                                                    <i class="ti ti-trash fs-4"></i>
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
                    {{ $targetkomisi->links() }}
                </div>
            </div>
        </div>
    </div>
</div>


<x-modal-form id="modal" size="modal-fullscreen" show="loadmodal" title="" />
<x-modal-form id="modalDetail" size="modal-fullscreen" show="loadmodalDetail" title="" />
@endsection
@push('myscript')
{{-- <script src="{{ asset('assets/js/pages/roles/create.js') }}"></script> --}}
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

        $(".btnCreate").click(function(e) {
            e.preventDefault();
            loading();
            $('#modal').modal("show");
            $("#loadmodal").load("{{ route('targetkomisi.create') }}");
            $(".modal-title").text("Buat Target");
        });

        $(".btnShow").click(function(e) {
            e.preventDefault();
            loading();
            const kode_target = $(this).attr("kode_target");
            $('#modalDetail').modal("show");
            $("#loadmodalDetail").load(`/targetkomisi/${kode_target}/show`);
            $(".modal-title").text("Detail Target");
        });

        $(".btnApprove").click(function(e) {
            e.preventDefault();
            loading();
            const kode_target = $(this).attr("kode_target");
            $('#modalDetail').modal("show");
            $("#loadmodalDetail").load(`/targetkomisi/${kode_target}/approve`);
            $(".modal-title").text("Persetujuan Target");
        });

        $(".btnEdit").click(function(e) {
            e.preventDefault();
            loading();
            const kode_target = $(this).attr("kode_target");
            $('#modal').modal("show");
            $("#loadmodal").load(`/targetkomisi/${kode_target}/edit`);
            $(".modal-title").text("Edit Target");
        });
    });
</script>
@endpush
