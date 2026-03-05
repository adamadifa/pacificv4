@extends('layouts.app')
@section('titlepage', 'Setoran Transfer')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Setoran Transfer</h4>
            <small class="text-muted">Manajemen setoran dari transfer pelanggan.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-cash me-1"></i>Keuangan</a>
                </li>
                <li class="breadcrumb-item active"><i class="ti ti-transfer-in me-1"></i>Setoran Transfer</li>
            </ol>
        </nav>
    </div>
@endsection

<div class="row">
    <div class="col-lg-12">
        {{-- Information Alert --}}
        <div class="alert alert-primary alert-dismissible d-flex align-items-baseline" role="alert">
            <span class="alert-icon alert-icon-lg text-primary me-2">
                <i class="ti ti-info-circle ti-sm"></i>
            </span>
            <div class="d-flex flex-column ps-1">
                <h5 class="alert-heading mb-2">Petunjuk Penggunaan</h5>
                <p class="mb-0">
                    Klik icon <i class="ti ti-external-link text-success mx-1"></i> untuk melakukan setoran hasil transfer pelanggan.
                </p>
                <p class="mb-0">
                    Klik icon <i class="ti ti-square-rounded-x text-danger mx-1"></i> untuk membatalkan setoran yang sudah diinput.
                </p>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>

        {{-- Navigation --}}
        <div class="mb-3">
            @include('layouts.navigation_kasbesar')
        </div>

        {{-- Filter Section --}}
        <div class="card shadow-none border-0 bg-transparent mb-4">
            <div class="card-body p-0">
                <form action="{{ route('setorantransfer.index') }}">
                    <div class="row g-2 align-items-end">
                        <div class="col-lg-6 col-md-6">
                            <x-input-with-icon label="Dari" value="{{ Request('dari') }}" name="dari" icon="ti ti-calendar"
                                datepicker="flatpickr-date" hideLabel="true" />
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <x-input-with-icon label="Sampai" value="{{ Request('sampai') }}" name="sampai" icon="ti ti-calendar"
                                datepicker="flatpickr-date" hideLabel="true" />
                        </div>
                    </div>
                    <div class="row g-2 align-items-end">
                        @hasanyrole($roles_show_cabang)
                            <div class="col-lg col-md-6">
                                <x-select label="Semua Cabang" name="kode_cabang_search" :data="$cabang" key="kode_cabang"
                                    textShow="nama_cabang" upperCase="true" selected="{{ Request('kode_cabang_search') }}"
                                    select2="select2Kodecabangsearch" hideLabel="true" />
                            </div>
                        @endrole
                        <div class="col-lg col-md-6">
                            <x-input-with-icon label="Pelanggan" value="{{ Request('nama_pelanggan_search') }}" icon="ti ti-user"
                                name="nama_pelanggan_search" hideLabel="true" />
                        </div>
                        <div class="col-lg col-md-6">
                            <div class="form-group mb-3">
                                <select name="kode_salesman_search" id="kode_salesman_search"
                                    class="form-select select2Kodesalesmansearch">
                                    <option value="">Semua Salesman</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg col-md-6">
                            <div class="form-group mb-3">
                                <select name="status" id="status" class="form-select">
                                    <option value="">Status</option>
                                    <option value="0" {{ Request('status') === '0' ? 'selected' : '' }}>Pending</option>
                                    <option value="1" {{ Request('status') === '1' ? 'selected' : '' }}>Disetujui</option>
                                    <option value="2" {{ Request('status') === '2' ? 'selected' : '' }}>Ditolak</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg col-md-12">
                            <div class="form-group mb-3">
                                <button type="submit" class="btn btn-primary w-100"><i class="ti ti-search"></i></button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Data Table Section --}}
        <div class="card shadow-sm border">
            <div class="card-header border-bottom py-3"
                style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                <h6 class="m-0 fw-bold text-white"><i class="ti ti-table me-2"></i>Data Setoran Transfer</h6>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover table-bordered table-striped text-center align-middle">
                    <thead>
                        <tr>
                            <th class="text-white" style="background-color: #002e65 !important;">KODE</th>
                            <th class="text-white" style="background-color: #002e65 !important;">TANGGAL</th>
                            <th class="text-white" style="background-color: #002e65 !important;">PELANGGAN</th>
                            <th class="text-white" style="background-color: #002e65 !important;">JUMLAH</th>
                            <th class="text-white" style="background-color: #002e65 !important;">BANK PENGIRIM</th>
                            <th class="text-white" style="background-color: #002e65 !important;">BANK PENERIMA</th>
                            <th class="text-white" style="background-color: #002e65 !important;">JATUH TEMPO</th>
                            <th class="text-white" style="background-color: #002e65 !important;">STATUS</th>
                            <th class="text-white" style="background-color: #002e65 !important;">DISETORKAN</th>
                            <th class="text-white" style="background-color: #002e65 !important;">#</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($transfer as $d)
                            <tr>
                                <td><span class="fw-bold">{{ $d->kode_transfer }}</span></td>
                                <td>{{ date('d-m-Y', strtotime($d->tanggal)) }}</td>
                                <td class="text-start" style="max-width: 200px; white-space: normal;">{{ $d->nama_pelanggan }}</td>
                                <td class="text-end fw-bold text-primary">{{ formatAngka($d->total) }}</td>
                                <td>{{ textUpperCase($d->bank_pengirim) }}</td>
                                <td>{{ !empty($d->nama_bank_alias) ? $d->nama_bank_alias : $d->nama_bank }}</td>
                                <td>{{ date('d-m-Y', strtotime($d->jatuh_tempo)) }}</td>
                                <td>
                                    @if ($d->status == '1')
                                        <span class="badge bg-success" data-bs-toggle="tooltip" data-bs-placement="top"
                                            title="{{ $d->no_bukti }}">{{ date('d-m-y', strtotime($d->tanggal_diterima)) }}</span>
                                    @elseif($d->status == '2')
                                        <span class="badge bg-danger"><i class="ti ti-x"></i></span>
                                    @else
                                        <span class="badge bg-warning"><i class="ti ti-hourglass-empty"></i></span>
                                    @endif
                                </td>
                                <td>
                                    @if (!empty($d->tanggal_disetorkan))
                                        <span class="badge bg-info">{{ date('d-m-Y', strtotime($d->tanggal_disetorkan)) }}</span>
                                    @else
                                        <span class="badge bg-secondary">Belum Setor</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        @can('setorantransfer.create')
                                            @if (empty($d->tanggal_disetorkan))
                                                <a href="#" class="btnCreate text-success"
                                                    kode_transfer="{{ Crypt::encrypt($d->kode_transfer) }}">
                                                    <i class="ti ti-external-link fs-4"></i>
                                                </a>
                                            @else
                                                @can('setorantransfer.delete')
                                                    <form method="POST" name="deleteform" class="deleteform d-inline"
                                                        action="{{ route('setorantransfer.delete', Crypt::encrypt($d->kode_setoran)) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <a href="#" class="delete-confirm text-danger">
                                                            <i class="ti ti-square-rounded-x fs-4"></i>
                                                        </a>
                                                    </form>
                                                @endcan
                                            @endif
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer border-top py-3">
                <div class="row align-items-center">
                    <div class="col-sm-12 col-md-12 d-flex justify-content-end">
                        {{ $transfer->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<x-modal-form id="modal" show="loadmodal" title="" />

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

        const select2Kodesalesmansearch = $('.select2Kodesalesmansearch');
        if (select2Kodesalesmansearch.length) {
            select2Kodesalesmansearch.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Semua Salesman',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

        function getsalesmanbyCabang() {
            var kode_cabang = $("#kode_cabang_search").val();
            var kode_salesman = "{{ Request('kode_salesman_search') }}";
            //alert(selected);
            $.ajax({
                type: 'POST',
                url: '/salesman/getsalesmanbycabang',
                data: {
                    _token: "{{ csrf_token() }}",
                    kode_cabang: kode_cabang,
                    kode_salesman: kode_salesman
                },
                cache: false,
                success: function(respond) {
                    console.log(respond);
                    $("#kode_salesman_search").html(respond);
                }
            });
        }

        $("#kode_cabang_search").change(function(e) {
            getsalesmanbyCabang();
        });

        getsalesmanbyCabang();

        $(".btnCreate").click(function(e) {
            e.preventDefault();
            loading();
            const kode_transfer = $(this).attr("kode_transfer");
            $('#modal').modal("show");
            $("#loadmodal").load(`/setorantransfer/${kode_transfer}/create`);
            $("#modal").find(".modal-title").text("Setoran Transfer");
        });
    });
</script>
@endpush
