@extends('layouts.app')
@section('titlepage', 'Ajuan Limit Kredit')

@section('content')
@section('navigasi')
    <span>Ajuan Limit Kredit</span>
@endsection
<div class="row">
    <div class="col-lg-12">
        <div class="nav-align-top nav-tabs-shadow mb-4">
            @include('layouts.navigation_ajuanmarketing')
            <div class="tab-content">
                <div class="tab-pane fade active show" id="navs-justified-home" role="tabpanel">
                    @can('ajuanlimit.create')
                        <a href="#" class="btn btn-primary" id="btnCreate"><i class="fa fa-plus me-2"></i>
                            Ajukan Limit Kredit
                        </a>
                    @endcan
                    <div class="row mt-2">
                        <div class="col-12">
                            <form action="{{ route('ajuanlimit.index') }}">
                                <div class="row">
                                    <div class="col-lg-6 col-sm-12 col-md-12">
                                        <x-input-with-icon label="Dari" value="{{ Request('dari') }}" name="dari" icon="ti ti-calendar"
                                            datepicker="flatpickr-date" />
                                    </div>
                                    <div class="col-lg-6 col-sm-12 col-md-12">
                                        <x-input-with-icon label="Sampai" value="{{ Request('sampai') }}" name="sampai" icon="ti ti-calendar"
                                            datepicker="flatpickr-date" />
                                    </div>
                                </div>
                                @hasanyrole($roles_show_cabang)
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                            <x-select label="Semua Cabang" name="kode_cabang_search" :data="$cabang" key="kode_cabang"
                                                textShow="nama_cabang" upperCase="true" selected="{{ Request('kode_cabang_search') }}"
                                                select2="select2Kodecabangsearch" />
                                        </div>
                                    </div>
                                @endrole
                                <div class="row">
                                    <div class="col-lg-6 col-sm-12 col-md-12">
                                        <div class="form-grou mb-3">
                                            <select name="posisi_ajuan" id="posisi_ajuan" class="form-select">
                                                <option value="">Poisi Ajuan</option>
                                                @foreach ($roles_approve_ajuanlimitkredit as $role)
                                                    <option value="{{ $role }}" {{ Request('posisi_ajuan') == $role ? 'selected' : '' }}>
                                                        {{ textUpperCase($role) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 co-sm-12 col-md-12">
                                        <div class="form-group mb-3">
                                            <select name="status" id="status" class="form-select">
                                                <option value="">Status</option>
                                                <option value="0" {{ Request('status') === '0' ? 'selected' : '' }}>
                                                    Pending</option>
                                                <option value="1" {{ Request('status') === '1' ? 'selected' : '' }}>
                                                    Disetujui</option>
                                                <option value="2" {{ Request('status') === '2' ? 'selected' : '' }}>
                                                    Ditolak</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group mb-3">
                                            <button class="btn btn-primary w-100"><i class="ti ti-search me-2"></i>Cari
                                                Data</button>
                                        </div>

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
                                            <th>No. Pengajuan</th>
                                            <th style="width: 8%">Tanggal</th>
                                            <th>Pelanggan</th>
                                            <th style="width: 13%">Jumlah</th>
                                            <th style="width: 7%">LJT</th>
                                            <th style="width: 7%" class="text-center"><i class="ti ti-adjustments me-1"></i></th>
                                            <th class="text-center">Skor</th>
                                            <th>Ket</th>
                                            <th>Posisi</th>
                                            <th class="text-center">Status</th>
                                            <th>#</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($ajuanlimit as $d)
                                            @php
                                                if ($level_user == 'sales marketing manager') {
                                                    $nextlevel = 'regional sales manager';
                                                } elseif ($level_user == 'regional sales manager') {
                                                    $nextlevel = 'gm marketing';
                                                } elseif ($level_user == 'gm marketing') {
                                                    $nextlevel = 'direktur';
                                                } else {
                                                    $nextlevel = '';
                                                }
                                            @endphp
                                            <tr>
                                                <td>{{ $d->no_pengajuan }}</td>
                                                <td>{{ date('d-m-y', strtotime($d->tanggal)) }}</td>
                                                <td>{{ $d->nama_pelanggan }}</td>
                                                <td class="text-end">
                                                    @if (!empty($d->jumlah_rekomendasi))
                                                        <span style="text-decoration: line-through red;">{{ formatAngka($d->jumlah) }}</span>
                                                        /
                                                        {{ formatAngka($d->jumlah_rekomendasi) }}
                                                    @else
                                                        {{ formatAngka($d->jumlah) }}
                                                    @endif

                                                </td>
                                                <td>{{ $d->ljt }} Hari</td>
                                                <td class="text-center">
                                                    @php
                                                        $selisih = $d->jumlah - $d->jumlah_rekomendasi;
                                                        $selisih = $selisih < 0 ? $selisih * -1 : $selisih;
                                                        $persentase = !empty($d->jumlah) ? ($selisih / $d->jumlah) * 100 : 0;
                                                    @endphp
                                                    @can('ajuanlimit.adjust')
                                                        @if (empty($d->jumlah_rekomendasi))
                                                            <a href="#" class="adjustlimit" no_pengajuan="{{ Crypt::encrypt($d->no_pengajuan) }}">
                                                                <i class="ti ti-adjustments text-warning"></i>
                                                            </a>
                                                        @else
                                                            @if ($d->status != '2')
                                                                <a href="#" class="adjustlimit"
                                                                    no_pengajuan="{{ Crypt::encrypt($d->no_pengajuan) }}">
                                                                    @if ($d->jumlah_rekomendasi < $d->jumlah)
                                                                        <span class="text-danger"><i class="ti ti-trending-down me-1"></i>
                                                                            {{ ROUND($persentase) }} %</span>
                                                                    @else
                                                                        <span class="text-success"><i class="ti ti-trending-up me-1"></i>
                                                                            {{ ROUND($persentase) }} %</span>
                                                                    @endif
                                                                </a>
                                                            @else
                                                                @if ($d->jumlah_rekomendasi < $d->jumlah)
                                                                    <span class="text-danger"><i class="ti ti-trending-down me-1"></i>
                                                                        {{ ROUND($persentase) }} %</span>
                                                                @else
                                                                    <span class="text-success"><i class="ti ti-trending-up me-1"></i>
                                                                        {{ ROUND($persentase) }} %</span>
                                                                @endif
                                                            @endif
                                                        @endif
                                                    @else
                                                        @if (!empty($d->jumlah_rekomendasi))
                                                            @if ($d->jumlah_rekomendasi < $d->jumlah)
                                                                <span class="text-danger"><i class="ti ti-trending-down me-1"></i>
                                                                    {{ ROUND($persentase) }} %</span>
                                                            @else
                                                                <span class="text-success"><i class="ti ti-trending-up me-1"></i>
                                                                    {{ ROUND($persentase) }} %</span>
                                                            @endif
                                                        @endif
                                                    @endcan

                                                </td>
                                                <td class="text-center">{{ formatAngkaDesimal($d->skor) }}</td>
                                                <td>

                                                    @php
                                                        if ($d->skor <= 2) {
                                                            $rekomendasi = 'TL';
                                                        } elseif ($d->skor > 2 && $d->skor <= 4) {
                                                            $rekomendasi = 'TD';
                                                        } elseif ($d->skor > 4 && $d->skor <= 6) {
                                                            $rekomendasi = 'B';
                                                        } elseif ($d->skor > 6 && $d->skor <= 8.5) {
                                                            $rekomendasi = 'LDP';
                                                        } elseif ($d->skor > 8.5 && $d->skor <= 10) {
                                                            $rekomendasi = 'L';
                                                        } else {
                                                            $rekomendasi = '';
                                                        }

                                                        if ($d->skor <= 4) {
                                                            $bg = 'danger';
                                                        } elseif ($d->skor <= 6) {
                                                            $bg = 'warning';
                                                        } else {
                                                            $bg = 'success';
                                                        }
                                                    @endphp
                                                    <span class="badge bg-{{ $bg }}">
                                                        {{ $rekomendasi }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if ($d->role == 'sales marketing manager')
                                                        @php
                                                            $color = 'bg-warning';
                                                            $text_role = 'SMM';
                                                        @endphp
                                                    @elseif ($d->role == 'regional sales manager')
                                                        @php
                                                            $color = 'bg-info';
                                                            $text_role = 'RSM';
                                                        @endphp
                                                    @elseif($d->role == 'gm marketing')
                                                        @php
                                                            $color = 'bg-primary';
                                                            $text_role = 'GM Marketing';
                                                        @endphp
                                                    @elseif($d->role == 'direktur')
                                                        @php
                                                            $color = 'bg-success';
                                                            $text_role = 'Direktur';
                                                        @endphp
                                                    @else
                                                        @php
                                                            $text_role = '';
                                                            $color = '';
                                                        @endphp
                                                    @endif

                                                    <span class="badge {{ $color }}">
                                                        {{ $text_role }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    @if ($d->status === '0')
                                                        <i class="ti ti-hourglass-empty text-warning"></i>
                                                    @elseif($d->status == '1')
                                                        <i class="ti ti-checks text-success"></i>
                                                    @elseif($d->status == '2')
                                                        <span class="badge bg-danger">Ditolak</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex">

                                                        @can('ajuanlimit.approve')
                                                            <div>
                                                                @if ($d->status_disposisi != null)
                                                                    @if ($d->status_disposisi == '0')
                                                                        <a href="#" class="me-2 btnApprove"
                                                                            no_pengajuan="{{ Crypt::encrypt($d->no_pengajuan) }}">
                                                                            <i class="ti ti-send text-info"></i>
                                                                        </a>
                                                                    @else
                                                                        <!-- Proses Cancel -->
                                                                        @if ($level_user == 'direktur')
                                                                            <form method="POST" name="deleteform" class="deleteform"
                                                                                action="{{ route('ajuanlimit.cancel', Crypt::encrypt($d->no_pengajuan)) }}">
                                                                                @csrf
                                                                                @method('DELETE')
                                                                                <a href="#" class="cancel-confirm me-1">
                                                                                    <i class="ti ti-square-rounded-x text-danger"></i>

                                                                                </a>
                                                                            </form>
                                                                        @elseif (($d->status_ajuan == '0' && $d->role == $nextlevel) || $d->role == $level_user)
                                                                            <form method="POST" name="deleteform" class="deleteform"
                                                                                action="{{ route('ajuanlimit.cancel', Crypt::encrypt($d->no_pengajuan)) }}">
                                                                                @csrf
                                                                                @method('DELETE')
                                                                                <a href="#" class="cancel-confirm me-1">
                                                                                    <i class="ti ti-square-rounded-x text-danger"></i>
                                                                                </a>
                                                                            </form>
                                                                        @endif
                                                                    @endif
                                                                @else
                                                                    @if ($d->role == 'sales marketing manager' && $level_user == 'operation manager' && $d->status_ajuan == '0')
                                                                        <a href="#" class="me-2 btnApprove"
                                                                            no_pengajuan="{{ Crypt::encrypt($d->no_pengajuan) }}">
                                                                            <i class="ti ti-send text-info"></i>
                                                                        </a>
                                                                    @elseif(($d->status_ajuan == '0' && $d->role == 'regional sales manager') || $d->role == 'sales marketing manager')
                                                                        <form method="POST" name="deleteform" class="deleteform"
                                                                            action="{{ route('ajuanlimit.cancel', Crypt::encrypt($d->no_pengajuan)) }}">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <a href="#" class="cancel-confirm me-1">
                                                                                <i class="ti ti-square-rounded-x text-danger"></i>
                                                                            </a>
                                                                        </form>
                                                                    @endif
                                                                @endif
                                                            </div>
                                                        @endcan
                                                        {{-- @can('ajuanlimit.edit')
                                                            @if ($d->id_pengirim == auth()->user()->id && !in_array($level_user, $roles_approve_ajuanlimitkredit) && $d->status == '0')
                                                                <div>
                                                                    <a href="#" class="me-2 btnEdit"
                                                                        no_pengajuan ="{{ Crypt::encrypt($d->no_pengajuan) }}">
                                                                        <i class="ti ti-edit text-success"></i>
                                                                    </a>
                                                                </div>
                                                            @elseif (
                                                                //Jika Level User Memiliki Hak Akses Approve dan Status Disposisi 0
                                                                //Atau Jika Level User Memilik Hak Akse Approve dan id Pengirim == User Aktif dan Status Ajuan 0

                                                                (in_array($level_user, $roles_approve_ajuanlimitkredit) && $d->status_disposisi == '0') ||
                                                                    (in_array($level_user, $roles_approve_ajuanlimitkredit) &&
                                                                        $d->id_pengirim == auth()->user()->id &&
                                                                        $d->status_ajuan == '0'))
                                                                <div>
                                                                    <a href="#" class="me-2 btnEdit"
                                                                        no_pengajuan="{{ Crypt::encrypt($d->no_pengajuan) }}">
                                                                        <i class="ti ti-edit text-success"></i>
                                                                    </a>
                                                                </div>
                                                            @endif
                                                        @endcan --}}

                                                        @can('ajuanlimit.show')
                                                            <div>
                                                                <a href="#" class="me-2 btnShow"
                                                                    no_pengajuan="{{ Crypt::encrypt($d->no_pengajuan) }}">
                                                                    <i class="ti ti-file-description text-info"></i>
                                                                </a>
                                                            </div>
                                                            <div>
                                                                <a href="{{ route('ajuanlimit.cetak', Crypt::encrypt($d->no_pengajuan)) }}"
                                                                    class="me-2" target="_blank">
                                                                    <i class="ti ti-printer text-info"></i>
                                                                </a>
                                                            </div>
                                                        @endcan


                                                        @can('ajuanlimit.delete')
                                                            {{-- {{ $d->status_ajuan }} --}}
                                                            @if (($d->id_pengirim == auth()->user()->id && $d->status == '0') || ($level_user == 'operation manager' && $d->status_ajuan == '0'))
                                                                <div>
                                                                    <form method="POST" name="deleteform" class="deleteform"
                                                                        action="{{ route('ajuanlimit.delete', Crypt::encrypt($d->no_pengajuan)) }}">
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
                                {{ $ajuanlimit->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<x-modal-form id="modal" size="modal-lg" show="loadmodal" title="" />
<x-modal-form id="modalAdjust" size="" show="loadmodalAdjust" title="" />
<x-modal-form id="modalApprove" size="modal-xl" show="loadmodalApprove" title="" />
<div class="modal fade" id="modalPelanggan" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel18">Data Pelanggan</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table" id="tabelpelanggan" width="100%">
                        <thead class="table-dark">
                            <tr>
                                <th>No.</th>
                                <th>Kode</th>
                                <th>Nama Pelanggan</th>
                                <th>Salesman</th>
                                <th>Wilayah</th>
                                <th>Status</th>
                                <th>#</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('myscript')
<script>
    $(function() {
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
            $("#modal").modal("show");
            $("#modal").find(".modal-title").text("Buan Ajuan Limit Kredit");
            $("#loadmodal").load(`/ajuanlimit/create`);
        });

        $(".btnApprove").click(function(e) {
            e.preventDefault();
            loading();
            const no_pengajuan = $(this).attr("no_pengajuan");
            $('#modalApprove').modal("show");
            $("#loadmodalApprove").load(`/ajuanlimit/${no_pengajuan}/approve`);
            $("#modalApprove").find(".modal-title").text("Persetujuan Ajuan Limit Kredit");
        });

        $(".btnShow").click(function(e) {
            e.preventDefault();
            loading();
            const no_pengajuan = $(this).attr("no_pengajuan");
            $('#modalApprove').modal("show");
            $("#loadmodalApprove").load(`/ajuanlimit/${no_pengajuan}/show`);
            $("#modalApprove").find(".modal-title").text("Data Ajuan Limit Kredit");
        });

        $(".btnEdit").click(function(e) {
            e.preventDefault();
            loading();
            const no_pengajuan = $(this).attr("no_pengajuan");
            $('#modal').modal("show");
            $("#loadmodal").load(`/ajuanlimit/${no_pengajuan}/edit`);
            $("#modal").find(".modal-title").text("Edit Ajuan Limit Kredit");
        });

        $(document).on('click', '#kode_pelanggan_search', function(e) {
            $("#modalPelanggan").modal("show");
        });

        $('#tabelpelanggan').DataTable({
            processing: true,
            serverSide: true,
            order: [
                [2, 'asc']
            ],
            ajax: "{{ route('pelanggan.getpelangganjson') }}",
            bAutoWidth: false,
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false,
                    width: '5%'
                },
                {
                    data: 'kode_pelanggan',
                    name: 'kode_pelanggan',
                    orderable: true,
                    searchable: true,
                    width: '10%'
                },
                {
                    data: 'nama_pelanggan',
                    name: 'nama_pelanggan',
                    orderable: true,
                    searchable: true,
                    width: '30%'
                },
                {
                    data: 'nama_salesman',
                    name: 'nama_salesman',
                    orderable: true,
                    searchable: false,
                    width: '20%'
                },

                {
                    data: 'nama_wilayah',
                    name: 'nama_wilayah',
                    orderable: true,
                    searchable: false,
                    width: '30%'
                },
                {
                    data: 'status_pelanggan',
                    name: 'status_pelanggan',
                    orderable: true,
                    searchable: false,
                    width: '30%'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    width: '5%'
                }
            ],

            rowCallback: function(row, data, index) {
                if (data.status_pelanggan == "NonAktif") {
                    $("td", row).addClass("bg-danger text-white");
                }
            }
        });

        $(".adjustlimit").click(function(e) {
            e.preventDefault();
            const no_pengajuan = $(this).attr("no_pengajuan");
            $("#modalAdjust").modal("show");
            $("#modalAdjust").find(".modal-title").text("Penyesuaian Limit");
            $("#loadmodalAdjust").load(`/ajuanlimit/${no_pengajuan}/adjust`);
        });
    });
</script>
@endpush
