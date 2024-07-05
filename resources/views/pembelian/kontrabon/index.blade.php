@extends('layouts.app')
@section('titlepage', 'Kontrabon Pembelian')

@section('content')
@section('navigasi')
    <span>Kontrabon Pembelian</span>
@endsection
<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    @can('kontrabonpmb.create')
                        <a href="{{ route('kontrabonpmb.create') }}" class="btn btn-primary" id="btnCreate"><i class="fa fa-plus me-2"></i> Buat
                            Kontra Bon</a>
                    @endcan
                </div>
            </div>
            <div class="card-body">
                <div class="row mt-2">
                    <div class="col-12">
                        <form action="{{ route('kontrabonpmb.index') }}" id="formSearch">

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

                            <div class="row">
                                <div class="col-lg-4 col-md-12 col-sm-12">
                                    <x-select label="Semua Supplier" name="kode_supplier_search" :data="$supplier" key="kode_supplier"
                                        textShow="nama_supplier" upperCase="true" selected="{{ Request('kode_supplier_search') }}"
                                        select2="select2Kodesupplier" />
                                </div>
                                <div class="col-lg-4 col-md-12 col-sm-12">
                                    <select name="status_search" id="status_search" class="form-select">
                                        <option value="">Status</option>
                                        <option value="1" {{ Request('status') == '1' ? 'selected' : '' }}>Sudah di Proses</option>
                                        <option value="0" {{ Request('status') === '0' ? 'selected' : '' }}>Belum di Proses</option>
                                    </select>
                                </div>
                                <div class="col-lg-4 col-md-12 col-sm-12">
                                    <select name="kategori_search" id="kategori_search" class="form-select">
                                        <option value="">Jenis Pengajuan</option>
                                        <option {{ Request('kategori') == 'KB' ? 'selected' : '' }} value="KB">Kontra BON</option>
                                        <option {{ Request('kategori') == 'IM' ? 'selected' : '' }} value="IM">Internal Memo</option>
                                        <option {{ Request('kategori') == 'TN' ? 'selected' : '' }} value="TN">Tunai</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="form-group mb-3">
                                        <button class="btn btn-primary w-100"><i class="ti ti-search me-1"></i>Cari
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
                            <table class="table  table-hover table-bordered">
                                <thead class="table-dark">
                                    <tr>
                                        <th>No. Kontra BON</th>
                                        <th>No Dok</th>
                                        <th>Tanggal</th>
                                        <th>Kategori</th>
                                        <th>Supplier</th>
                                        <th>Total Bayar</th>
                                        <th>Status Bayar</th>
                                        <th>Jenis Bayar</th>
                                        <th class="text-center">Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($kontrabon as $d)
                                        <tr>
                                            <td>{{ $d->no_kontrabon }}</td>
                                            <td>{{ $d->no_dokumen }}</td>
                                            <td>{{ formatIndo($d->tanggal) }}</td>
                                            <td>
                                                @if ($d->kategori == 'TN')
                                                    <span class="badge bg-success">Tunai</span>
                                                @elseif ($d->kategori == 'KB')
                                                    <span class="badge bg-primary">Kontra Bon</span>
                                                @elseif ($d->kategori == 'IM')
                                                    <span class="badge bg-info">Internal Memo</span>
                                                @endif
                                            </td>
                                            <td>{{ $d->nama_supplier }}</td>
                                            <td class="text-end">{{ formatAngkaDesimal($d->jumlah) }}</td>
                                            <td>
                                                @if (empty($d->tglbayar))
                                                    <span class="badge bg-danger">Belum Bayar</span>
                                                @else
                                                    <span class="badge bg-success">{{ formatIndo($d->tglbayar) }}</span>
                                                @endif
                                            </td>
                                            <td>{{ $d->jenis_bayar == 'TN' ? 'Tunai' : 'Transfer' }}</td>
                                            <td class="text-center">
                                                @if ($d->status == 1)
                                                    @if (!empty($d->tglbayar))
                                                        <span class="badge bg-success">Selesai</span>
                                                    @else
                                                        <span class="badge bg-primary">Approved</span>
                                                    @endif
                                                @else
                                                    @if (!empty($d->tglbayar))
                                                        <span class="badge bg-success">Selesai</span>
                                                    @else
                                                        <i class="ti ti-hourglass-empty text-warning"></i>
                                                    @endif
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    @can('kontrabonpmb.show')
                                                        <a href="{{ route('kontrabonpmb.cetak', Crypt::encrypt($d->no_kontrabon)) }}" target="_blank"
                                                            class="me-1">
                                                            <i class="ti ti-printer text-primary"></i>
                                                        </a>
                                                        <a href="#" no_kontrabon="{{ Crypt::encrypt($d->no_kontrabon) }}" class="btnShow me-1">
                                                            <i class="ti ti-file-description text-info"></i>
                                                        </a>
                                                    @endcan
                                                    @can('kontrabonpmb.approve')
                                                        @if ($d->kategori != 'TN')
                                                            @if ($d->status === '0')
                                                                <a href="{{ route('kontrabonpmb.approve', Crypt::encrypt($d->no_kontrabon)) }}"
                                                                    class="me-1">
                                                                    <i class="ti ti-checks text-success"></i>
                                                                </a>
                                                            @else
                                                                @if (empty($d->tglbayar))
                                                                    <a href="{{ route('kontrabonpmb.cancel', Crypt::encrypt($d->no_kontrabon)) }}"
                                                                        class="me-1">
                                                                        <i class="ti ti-square-rounded-x text-danger"></i>
                                                                    </a>
                                                                @endif
                                                            @endif
                                                        @endif
                                                    @endcan
                                                    @can('kontrabonpmb.delete')
                                                        @if ($d->kategori != 'TN')
                                                            @if ($d->status === '0')
                                                                <form method="POST" name="deleteform" class="deleteform"
                                                                    action="{{ route('kontrabonpmb.delete', Crypt::encrypt($d->no_kontrabon)) }}">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <a href="#" class="delete-confirm me-1">
                                                                        <i class="ti ti-trash text-danger"></i>
                                                                    </a>
                                                                </form>
                                                            @endif
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
                            {{ $kontrabon->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<x-modal-form id="modal" show="loadmodal" title="" />
<x-modal-form id="modalDetailpembelian" show="loadmodalDetailpembelian" title="" />
@endsection
@push('myscript')
<script>
    $(function() {
        const select2Kodesupplier = $('.select2Kodesupplier');
        if (select2Kodesupplier.length) {
            select2Kodesupplier.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Semua Supplier',
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

        $(".btnShow").click(function(e) {
            e.preventDefault();
            loading();
            var no_kontrabon = $(this).attr("no_kontrabon");
            $("#modal").modal("show");
            $("#modal").find(".modal-title").text("Detail Kontrabon");
            $("#modal").find("#loadmodal").load(`/kontrabonpembelian/${no_kontrabon}/show`);
            $("#modal").find(".modal-dialog").addClass('modal-lg');
        });

        $(document).on('click', '.btnShowpembelian', function(e) {
            e.preventDefault();
            //loading();
            var no_bukti = $(this).attr("no_bukti");
            $("#modalDetailpembelian").modal("show");
            $("#modalDetailpembelian").find(".modal-title").text("Detail Pembelian");
            $("#modalDetailpembelian").find("#loadmodalDetailpembelian").load(`/pembelian/${no_bukti}/show`);
            $("#modalDetailpembelian").find(".modal-dialog").addClass('modal-xl');
        });



    });
</script>
@endpush
