@extends('layouts.app')
@section('titlepage', 'Ticket')

@section('content')
@section('navigasi')
    <span>Ticket Perubahan Data</span>
@endsection

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="nav-align-top nav-tabs-shadow mb-4">
            @include('layouts.navigation_ticket')
            <div class="tab-content">
                <div class="tab-pane fade active show" id="navs-justified-home" role="tabpanel">
                    <a href="#" class="btn btn-primary" id="btnCreate"><i class="fa fa-plus me-2"></i>
                        Tambah Data</a>
                    <div class="row mt-2">
                        <div class="col-12">
                            <form action="{{ route('ticket.index') }}">
                                @hasanyrole($roles_show_cabang)
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                            <x-select label="Semua Cabang" name="kode_cabang_search" :data="$cabang" key="kode_cabang"
                                                textShow="nama_cabang" upperCase="true" selected="{{ Request('kode_cabang_search') }}"
                                                select2="select2Kodecabangsearch" />
                                        </div>
                                    </div>
                                @endrole
                                <div class="form-group">
                                    <select name="status_search" id="status_search" class="form-select">
                                        <option value="">Semua Status</option>
                                        <option value="pending" {{ Request('status_search') == 'pending' ? 'selected' : '' }}>Belum Selesai</option>
                                        <option value="selesai" {{ Request('status_search') == 'selessai' ? 'selected' : '' }}>Selesai</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-primary w-100"><i class="ti ti-search me-1"></i>Cari
                                        Data</button>
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
                                            <th>No. Ticket</th>
                                            <th>Tanggal</th>
                                            <th>Keterangan</th>
                                            <th>User</th>
                                            <th>Cabang</th>
                                            <th>Kategori</th>
                                            <th>No. Bukti</th>
                                            <th>Approval</th>
                                            {{-- <th class="text-center">Direktur</th> --}}
                                            <th>Status</th>
                                            <th>#</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $kategori = [
                                                '1' => 'Penjualan',
                                                '2' => 'Pembayaran',
                                                '3' => 'Retur',
                                                '4' => 'DPB',
                                                '5' => 'Mutasi Persediaan',
                                            ];
                                        @endphp
                                        @foreach ($ticket as $d)
                                            <tr>
                                                <td style="width: 5%">{{ $d->kode_pengajuan }}</td>
                                                <td style="width: 10%">{{ formatIndo($d->tanggal) }}</td>
                                                <td>{{ $d->keterangan }}</td>
                                                <td style="width: 10%">{{ formatName2($d->name) }}</td>
                                                <td style="width: 5%">{{ $d->kode_cabang }}</td>
                                                <td>{{ $kategori[$d->kategori] }}</td>
                                                <td>{{ $d->no_bukti }}</td>

                                                <td>
                                                    @if ($d->gm == null)
                                                        <i class="ti ti-hourglass-low  text-warning"></i>
                                                    @else
                                                        <span class="badge bg-info">
                                                            {{ $d->approval }}
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="text-center" style="width: 3%">
                                                    @if ($d->status == '2')
                                                        <i class="ti ti-square-x  text-danger"></i>
                                                    @elseif($d->status == '1')
                                                        <i class="ti ti-check text-success"></i>
                                                    @elseif($d->status == '0')
                                                        <i class="ti ti-hourglass-low  text-warning"></i>
                                                    @endif
                                                </td>
                                                <td style="width: 5%">
                                                    <div class="d-flex">

                                                        @if ($d->gm == null)
                                                            <a href="#" class="btnEdit me-1" kode_pengajuan="{{ $d->kode_pengajuan }}"><i
                                                                    class="ti ti-edit text-success"></i>
                                                            </a>
                                                        @endif

                                                        @can('ticket.approve')
                                                            @if (in_array($level_user, ['gm administrasi', 'regional operation manager', 'super admin']))
                                                                @if ($d->status == '0')
                                                                    <a href="#" class="btnApprove me-1" kode_pengajuan="{{ $d->kode_pengajuan }}">
                                                                        <i class="ti ti-external-link text-primary"></i>
                                                                    </a>
                                                                @else
                                                                    <form method="POST" name="deleteform" class="deleteform"
                                                                        action="{{ route('ticketupdate.cancel', Crypt::encrypt($d->kode_pengajuan)) }}">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <a href="#" class="cancel-confirm ml-1">
                                                                            <i class="ti ti-square-x text-danger"></i>
                                                                        </a>
                                                                    </form>
                                                                @endif
                                                            @endif
                                                        @endcan

                                                        @if ($d->gm == null)
                                                            <form method="POST" name="deleteform" class="deleteform"
                                                                action="{{ route('ticketupdate.delete', Crypt::encrypt($d->kode_pengajuan)) }}">
                                                                @csrf
                                                                @method('DELETE')
                                                                <a href="#" class="delete-confirm ml-1">
                                                                    <i class="ti ti-trash text-danger"></i>
                                                                </a>
                                                            </form>
                                                        @endif
                                                        @if (!empty($d->link))
                                                            <a href="{{ url($d->link) }}" target="_blank">
                                                                <i class="ti ti-paperclip text-primary"></i>
                                                            </a>
                                                        @endif

                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-between">
                                {{ $ticket->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<x-modal-form id="mdlCreate" size="" show="loadCreate" title="Buat Ticket" />
@endsection


@push('myscript')
{{-- <script src="{{ asset('assets/js/pages/kirimlhp/create.js') }}"></script> --}}
<script>
    $(function() {
        $("#btnCreate").click(function(e) {
            $('#mdlCreate').modal("show");
            $("#loadCreate").load('/ticketupdate/create');
        });

        $(".btnEdit").click(function(e) {
            const kode_pengajuan = $(this).attr('kode_pengajuan');
            e.preventDefault();
            $('#mdlCreate').modal("show");
            $('#mdlCreate').find('.modal-title').text('Edit Ticket');
            $("#loadCreate").load(`/ticketupdate/${kode_pengajuan}/edit`);
        });

        $(".btnApprove").click(function(e) {
            const kode_pengajuan = $(this).attr('kode_pengajuan');
            e.preventDefault();
            $('#mdlCreate').modal("show");
            $('#mdlCreate').find('.modal-title').text('Approve Ticket');
            $("#loadCreate").load(`/ticketupdate/${kode_pengajuan}/approve`);
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
    });
</script>
@endpush
