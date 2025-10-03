@extends('layouts.app')
@section('titlepage', 'Target Komisi')

@section('content')
@section('navigasi')
    <span>Target Komisi</span>
@endsection
<div class="col-lg-10">
    <div class="nav-align-top nav-tabs-shadow mb-4">
        @include('layouts.navigation_targetkomisi')
        <div class="tab-content">
            <div class="tab-pane fade active show" id="navs-justified-home" role="tabpanel">
                @can('targetkomisi.create')
                    <a href="#" class="btn btn-primary btnCreate"><i class="fa fa-plus me-2"></i> Buat Target</a>
                @endcan
                <div class="row mt-2">
                    <div class="col-12">
                        <form action="{{ route('targetkomisi.index') }}">

                            @hasanyrole($roles_show_cabang)
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <x-select label="Semua Cabang" name="kode_cabang_search" :data="$cabang"
                                            key="kode_cabang" textShow="nama_cabang" upperCase="true"
                                            selected="{{ Request('kode_cabang_search') }}"
                                            select2="select2Kodecabangsearch" />
                                    </div>
                                </div>
                            @endrole

                            <div class="row">
                                <div class="col-lg-3 col-sm-12 col-md-12">
                                    <div class="form-grou mb-3">
                                        <select name="posisi_ajuan" id="posisi_ajuan" class="form-select">
                                            <option value="">Poisi Ajuan</option>
                                            @foreach ($roles_approve_targetkomisi as $role)
                                                <option value="{{ $role }}"
                                                    {{ Request('posisi_ajuan') == $role ? 'selected' : '' }}>
                                                    {{ textUpperCase($role) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-12 col-md-12">
                                    <div class="form-group mb-3">
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
                                    <div class="form-group mb-3">
                                        <select name="tahun" id="tahun" class="form-select">
                                            <option value="">Tahun</option>
                                            @for ($t = $start_year; $t <= date('Y'); $t++)
                                                <option
                                                    @if (!empty(Request('tahun'))) {{ Request('tahun') == $t ? 'selected' : '' }}
                                                @else {{ date('Y') == $t ? 'selected' : '' }} @endif
                                                    value="{{ $t }}">{{ $t }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-2 co-sm-12 col-md-12">
                                    <div class="form-group mb-3">
                                        <select name="status" id="status" class="form-select">
                                            <option value="">Status</option>
                                            <option value="0" {{ Request('status') === '0' ? 'selected' : '' }}>
                                                Pending</option>
                                            <option value="1" {{ Request('status') === '1' ? 'selected' : '' }}>
                                                Disetujui</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-sm-12 col-md-12">
                                    <button class="btn btn-primary"><i class="ti ti-icons ti-search me-1"></i></button>
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
                                        <th>Bulan</th>
                                        <th>Tahun</th>
                                        <th>Cabang</th>
                                        <th>Posisi Ajuan</th>
                                        <th class="text-center">Status</th>
                                        <th>Tanggal</th>
                                        <th>#</th>
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
                                                @if ($d->role == 'regional sales manager')
                                                    @php
                                                        $color = 'bg-info';
                                                    @endphp
                                                @elseif($d->role == 'gm marketing')
                                                    @php
                                                        $color = 'bg-primary';
                                                    @endphp
                                                @elseif($d->role == 'direktur')
                                                    @php
                                                        $color = 'bg-success';
                                                    @endphp
                                                @else
                                                    @php
                                                        $color = '';
                                                    @endphp
                                                @endif

                                                <span
                                                    class="badge {{ $color }}">{{ textCamelCase($d->role) }}</span>
                                            </td>
                                            <td class="text-center">
                                                @if ($d->status == '0')
                                                    <i class="ti ti-hourglass-empty text-warning"></i>
                                                @else
                                                    <i class="ti ti-checks text-success"></i>
                                                @endif
                                            </td>
                                            <td>{{ !empty($d->created_at) ? date('d-m-Y H:i:s', strtotime($d->created_at)) : '' }}
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    @can('targetkomisi.approve')
                                                        <div>
                                                            @if ($d->status_disposisi == '0' || $level_user == 'regional sales manager')
                                                                <a href="#" class="me-2 btnApprove"
                                                                    kode_target="{{ Crypt::encrypt($d->kode_target) }}">
                                                                    <i class="ti ti-send text-info"></i>
                                                                </a>
                                                            @else
                                                                @if ($level_user == 'direktur')
                                                                    @if ($d->status_disposisi == '1')
                                                                        <form method="POST" name="deleteform"
                                                                            class="deleteform"
                                                                            action="{{ route('targetkomisi.cancel', Crypt::encrypt($d->kode_target)) }}">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <a href="#" class="cancel-confirm me-1">
                                                                                <i
                                                                                    class="ti ti-square-rounded-x text-danger"></i>
                                                                            </a>
                                                                        </form>
                                                                    @endif
                                                                @else
                                                                    @if ($d->status_ajuan == '0' && $d->role == $nextlevel)
                                                                        <form method="POST" name="deleteform"
                                                                            class="deleteform"
                                                                            action="{{ route('targetkomisi.cancel', Crypt::encrypt($d->kode_target)) }}">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <a href="#" class="cancel-confirm me-1">
                                                                                <i
                                                                                    class="ti ti-square-rounded-x text-danger"></i>
                                                                            </a>
                                                                        </form>
                                                                    @endif
                                                                @endif
                                                            @endif
                                                        </div>
                                                    @endcan
                                                    @can('targetkomisi.edit')
                                                        @if (
                                                            ($d->id_pengirim == auth()->user()->id && !in_array($level_user, $roles_approve_targetkomisi)) ||
                                                                $level_user == 'super admin' ||
                                                                $level_user == 'regional sales manager')
                                                            <div>
                                                                <a href="#" class="me-2 btnEdit"
                                                                    kode_target="{{ Crypt::encrypt($d->kode_target) }}">
                                                                    <i class="ti ti-edit text-success"></i>
                                                                </a>
                                                            </div>
                                                        @elseif (
                                                            (in_array($level_user, $roles_approve_targetkomisi) && $d->status_disposisi == '0') ||
                                                                (in_array($level_user, $roles_approve_targetkomisi) &&
                                                                    $d->id_pengirim == auth()->user()->id &&
                                                                    $d->status_ajuan == '0'))
                                                            <div>
                                                                <a href="#" class="me-2 btnEdit"
                                                                    kode_target="{{ Crypt::encrypt($d->kode_target) }}">
                                                                    <i class="ti ti-edit text-success"></i>
                                                                </a>
                                                            </div>
                                                        @endif
                                                    @endcan
                                                    {{-- {{ $d->status_disposisi }}
                                                    {{ $d->status_disposisi == '0' ? 'true' : 'false' }} --}}
                                                    @can('targetkomisi.show')
                                                        <div>
                                                            <a href="#" class="me-2 btnShow"
                                                                kode_target="{{ Crypt::encrypt($d->kode_target) }}">
                                                                <i class="ti ti-file-description text-info"></i>
                                                            </a>
                                                        </div>
                                                    @endcan
                                                    @can('targetkomisi.delete')
                                                        @if ($d->id_pengirim == auth()->user()->id)
                                                            <div>
                                                                <form method="POST" name="deleteform" class="deleteform"
                                                                    action="{{ route('targetkomisi.delete', Crypt::encrypt($d->kode_target)) }}">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <a href="#" class="delete-confirm ml-1">
                                                                        <i class="ti ti-trash text-danger"></i>
                                                                    </a>
                                                                </form>
                                                            </div>
                                                        @endif
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div style="float: right;">
                            {{ $targetkomisi->links() }}
                        </div>
                    </div>
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
