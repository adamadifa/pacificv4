@extends('layouts.app')
@section('titlepage', 'Move Faktur')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Move Faktur</h4>
            <small class="text-muted">Kelola pemindahan faktur antar salesman.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-folder me-1"></i>Marketing</a>
                </li>
                <li class="breadcrumb-item active"><i class="ti ti-arrows-transfer me-1"></i>Move Faktur</li>
            </ol>
        </nav>
    </div>
@endsection

<style>
    #filter-card .form-group {
        margin-bottom: 0 !important;
    }
</style>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        {{-- Filter Section --}}
        <form action="{{ route('movefaktur.index') }}">
            <div class="card shadow-sm border mb-2" id="filter-card">
                <div class="card-body py-2">
                    <div class="row g-2 mb-0">
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <x-input-with-icon label="Dari" value="{{ Request('dari') }}" name="dari" icon="ti ti-calendar" datepicker="flatpickr-date" hideLabel="true" />
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <x-input-with-icon label="Sampai" value="{{ Request('sampai') }}" name="sampai" icon="ti ti-calendar" datepicker="flatpickr-date" hideLabel="true" />
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <x-select label="Semua Cabang" name="kode_cabang_search" id="kode_cabang_search" :data="$cabang" key="kode_cabang" textShow="nama_cabang" upperCase="true" selected="{{ Request('kode_cabang_search') }}" hideLabel="true" select2="select2" />
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <x-input-with-icon label="No. Faktur" value="{{ Request('no_faktur_search') }}" name="no_faktur_search" icon="ti ti-barcode" hideLabel="true" />
                        </div>
                    </div>
                    <div class="row g-2 mt-0">
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <div class="form-group">
                                <select name="kode_salesman_lama_search" id="kode_salesman_lama_search" class="form-select select2">
                                    <option value="">Salesman Lama</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <div class="form-group">
                                <select name="kode_salesman_baru_search" id="kode_salesman_baru_search" class="form-select select2">
                                    <option value="">Salesman Baru</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <div class="form-group">
                                <select name="status_warning" id="status_warning" class="form-select">
                                    <option value="">Status Warning</option>
                                    <option value="1" {{ Request('status_warning') == '1' ? 'selected' : '' }}>Ada Warning (Satu Periode)</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="form-group">
                                <button class="btn btn-primary"><i class="ti ti-search me-1"></i> Cari</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        {{-- Data Card --}}
        <div class="card shadow-sm border">
            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold text-white"><i class="ti ti-arrows-transfer me-2"></i>Data Move Faktur</h6>
        <div class="card shadow-sm border">
            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold text-white"><i class="ti ti-arrows-transfer me-2"></i>Data Move Faktur</h6>
                    <div class="d-flex gap-2">
                        @can('movefaktur.delete')
                            <button class="btn btn-danger btn-sm shadow-sm" id="btnBulkDelete">
                                <i class="ti ti-trash me-1"></i> Hapus Terpilih
                            </button>
                        @endcan
                        @can('movefaktur.create')
                            <a href="#" class="btn btn-primary btn-sm shadow-sm" id="btnCreate">
                                <i class="ti ti-plus me-1"></i> Tambah Data
                            </a>
                        @endcan
                    </div>
                </div>
            </div>
            <div class="table-responsive text-nowrap">
                <form action="{{ route('movefaktur.delete-multiple') }}" method="POST" id="formBulkDelete">
                    @csrf
                    @method('DELETE')
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th class="text-center" style="background-color: #002e65 !important; width: 5%">
                                    <input type="checkbox" class="form-check-input" id="checkAll">
                                </th>
                                <th class="text-white" style="background-color: #002e65 !important;">NO. FAKTUR</th>
                                <th class="text-white" style="background-color: #002e65 !important;">TGL FAKTUR</th>
                                <th class="text-white" style="background-color: #002e65 !important;">TGL MOVE</th>
                                <th class="text-white" style="background-color: #002e65 !important;">PELANGGAN</th>
                                <th class="text-white" style="background-color: #002e65 !important;">SALES LAMA</th>
                                <th class="text-white" style="background-color: #002e65 !important;">SALES BARU</th>
                                <th class="text-white text-center" style="background-color: #002e65 !important;">#</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @foreach ($movefaktur as $d)
                                @php
                                    $bulan_move = date('m', strtotime($d->tanggal));
                                    $tahun_move = date('Y', strtotime($d->tanggal));
                                    $bulan_faktur = date('m', strtotime($d->tanggal_faktur));
                                    $tahun_faktur = date('Y', strtotime($d->tanggal_faktur));

                                    if ($bulan_move == $bulan_faktur && $tahun_move == $tahun_faktur) {
                                        $color = '#ffccd2';
                                    } else {
                                        $color = '';
                                    }
                                @endphp
                                <tr style="background-color: {{ $color }} !important;">
                                    <td class="text-center" style="background-color: inherit !important;">
                                        <input type="checkbox" class="form-check-input checkItem" name="ids[]" value="{{ $d->id }}">
                                    </td>
                                    <td style="background-color: inherit !important;"><span class="fw-bold text-primary">{{ $d->no_faktur }}</span></td>
                                    <td style="background-color: inherit !important;">{{ date('d-m-Y', strtotime($d->tanggal_faktur)) }}</td>
                                    <td style="background-color: inherit !important;">{{ date('d-m-Y', strtotime($d->tanggal)) }}</td>
                                    <td style="background-color: inherit !important;">{{ $d->nama_pelanggan }}</td>
                                    <td style="background-color: inherit !important;">{{ $d->nama_salesman_lama }}</td>
                                    <td style="background-color: inherit !important;">{{ $d->nama_salesman_baru }}</td>
                                    <td style="background-color: inherit !important;">
                                        <div class="d-flex justify-content-center gap-2">
                                            @if ($color != '')
                                                <i class="ti ti-alert-triangle text-danger" data-bs-toggle="tooltip" title="Bulan & Tahun Move sama dengan Tanggal Faktur"></i>
                                            @endif
                                            @can('movefaktur.delete')
                                                <a href="#" class="btnDelete text-danger" id_move="{{ Crypt::encrypt($d->id) }}" data-bs-toggle="tooltip" title="Hapus">
                                                    <i class="ti ti-trash fs-5"></i>
                                                </a>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </form>
                {{-- Single Delete Form --}}
                <form method="POST" id="formDelete" style="display: none;">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
            <div class="card-footer py-2">
                <div style="float: right;">
                    {{ $movefaktur->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<x-modal-form id="modal" size="" show="loadmodal" title="Tambah Move Faktur" />
@endsection

@push('myscript')
<script>
    $(function() {
        $(".select2").select2();

        $("#btnCreate").click(function(e) {
            e.preventDefault();
            $("#modal").modal("show");
            $("#loadmodal").load("{{ route('movefaktur.create') }}");
        });

        // Check All
        $("#checkAll").click(function() {
            $(".checkItem").prop('checked', $(this).prop('checked'));
        });

        $(".checkItem").click(function() {
            if (!$(this).prop('checked')) {
                $("#checkAll").prop('checked', false);
            }
        });

        // Single Delete
        $(".btnDelete").click(function(e) {
            e.preventDefault();
            var id_move = $(this).attr('id_move');
            Swal.fire({
                title: "Apakah Anda Yakin?",
                text: "Data ini akan dihapus secara permanen!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, Hapus!",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    $("#formDelete").attr('action', '/movefaktur/' + id_move);
                    $("#formDelete").submit();
                }
            });
        });

        // Bulk Delete
        $("#btnBulkDelete").click(function(e) {
            e.preventDefault();
            var ids = [];
            $(".checkItem:checked").each(function() {
                ids.push($(this).val());
            });

            if (ids.length == 0) {
                Swal.fire("Peringatan", "Pilih data yang akan dihapus terlebih dahulu!", "warning");
                return;
            }

            Swal.fire({
                title: "Hapus Data Terpilih?",
                text: "Sebanyak " + ids.length + " data akan dihapus secara permanen!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, Hapus Semua!",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    $("#formBulkDelete").submit();
                }
            });
        });

        function getsalesmanbyCabang() {
            var kode_cabang = $("#kode_cabang_search").val();
            var kode_salesman_lama = "{{ Request('kode_salesman_lama_search') }}";
            var kode_salesman_baru = "{{ Request('kode_salesman_baru_search') }}";
            
            $.ajax({
                type: 'POST',
                url: '/salesman/getsalesmanbycabang',
                data: {
                    _token: "{{ csrf_token() }}",
                    kode_cabang: kode_cabang,
                    kode_salesman: kode_salesman_lama
                },
                cache: false,
                success: function(respond) {
                    $("#kode_salesman_lama_search").html(respond);
                }
            });

            $.ajax({
                type: 'POST',
                url: '/salesman/getsalesmanbycabang',
                data: {
                    _token: "{{ csrf_token() }}",
                    kode_cabang: kode_cabang,
                    kode_salesman: kode_salesman_baru
                },
                cache: false,
                success: function(respond) {
                    $("#kode_salesman_baru_search").html(respond);
                }
            });
        }

        getsalesmanbyCabang();
        $("#kode_cabang_search").change(function(e) {
            getsalesmanbyCabang();
        });
    });
</script>
@endpush
