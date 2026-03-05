<style>
    .freeze-1 {
        position: sticky;
        left: 0;
        z-index: 2;
        min-width: 150px;
    }

    .freeze-2 {
        position: sticky;
        left: 150px;
        z-index: 2;
        min-width: 100px;
    }

    .freeze-3 {
        position: sticky;
        left: 250px;
        z-index: 2;
        min-width: 120px;
    }

    .freeze-4 {
        position: sticky;
        left: 370px;
        z-index: 2;
        min-width: 250px;
    }

    .freeze-last {
        position: sticky;
        right: 0;
        z-index: 2;
        border-left: 1px solid #dee2e6;
        box-shadow: -2px 0 5px rgba(0, 0, 0, 0.05);
    }

    /* background color for body cells to avoid transparency */
    tbody td.freeze-1,
    tbody td.freeze-2,
    tbody td.freeze-3,
    tbody td.freeze-4,
    tbody td.freeze-last {
        background-color: #fff !important;
    }

    /* background and z-index for headers */
    thead th.freeze-1,
    thead th.freeze-2,
    thead th.freeze-3,
    thead th.freeze-4,
    thead th.freeze-last {
        background-color: #002e65 !important;
        z-index: 3;
    }

    thead th {
        background-color: #002e65 !important;
    }
</style>

<div class="row">
    <div class="col-12">
        <form action="{{ request()->url() }}" id="formSearch" method="GET">
            <div class="row g-2 mb-1">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <x-input-with-icon label="Dari" value="{{ Request('dari') }}" name="dari" icon="ti ti-calendar" datepicker="flatpickr-date" hideLabel="true" />
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <x-input-with-icon label="Sampai" value="{{ Request('sampai') }}" name="sampai" icon="ti ti-calendar" datepicker="flatpickr-date" hideLabel="true" />
                </div>
            </div>

            <div class="row g-2 mb-1">
                <div class="col-lg-4 col-md-12 col-sm-12">
                    <x-select label="Semua Supplier" name="kode_supplier_search" :data="$supplier" key="kode_supplier" textShow="nama_supplier"
                        upperCase="true" selected="{{ Request('kode_supplier_search') }}" select2="select2Kodesupplier" hideLabel="true" />
                </div>
                <div class="col-lg-4 col-md-12 col-sm-12">
                    <select name="status_search" id="status_search" class="form-select text-uppercase">
                        <option value="">Status Proses</option>
                        <option value="SP" {{ Request('status_search') == 'SP' ? 'selected' : '' }}>Sudah di Proses</option>
                        <option value="BP" {{ Request('status_search') === 'BP' ? 'selected' : '' }}>Belum di Proses</option>
                    </select>
                </div>
                <div class="col-lg-4 col-md-12 col-sm-12">
                    <select name="kategori_search" id="kategori_search" class="form-select text-uppercase">
                        <option value="">Jenis Pengajuan</option>
                        <option {{ Request('kategori_search') == 'KB' ? 'selected' : '' }} value="KB">Kontra BON</option>
                        <option {{ Request('kategori_search') == 'IM' ? 'selected' : '' }} value="IM">Internal Memo</option>
                        <option {{ Request('kategori_search') == 'TN' ? 'selected' : '' }} value="TN">Tunai</option>
                    </select>
                </div>
            </div>

            <div class="row g-2 mb-2">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="form-group mb-1">
                        <button class="btn btn-primary w-100"><i class="ti ti-search me-1"></i>Cari Data</button>
                    </div>
                </div>
            </div>
        </form>

        {{-- Card Data --}}
        <div class="card shadow-sm border mt-2">
            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold text-white"><i class="ti ti-file-text me-2"></i>Data Kontra Bon</h6>
                    <div class="d-flex gap-2">
                        @can('kontrabonpmb.create')
                            <a href="{{ route('kontrabonpmb.create') }}" class="btn btn-primary btn-sm" id="btnCreate"><i class="ti ti-plus me-1"></i> Buat Kontra Bon</a>
                        @endcan
                    </div>
                </div>
            </div>

            <div class="table-responsive text-nowrap">
                <table class="table table-hover table-bordered">
                    <thead style="background-color: #002e65;">
                        <tr>
                            <th class="text-white freeze-1">No. Kontra BON</th>
                            <th class="text-white freeze-2" style="width: 10%">No Dok</th>
                            <th class="text-white freeze-3" style="width: 10%">Tanggal</th>
                            <th class="text-white freeze-4" style="width: 25%">Supplier</th>
                            <th class="text-white">Kategori</th>
                            <th class="text-white text-end">Total Bayar</th>
                            <th class="text-white text-center">Status Bayar</th>
                            <th class="text-white text-center">Jenis Bayar</th>
                            <th class="text-white text-center">Status</th>
                            <th class="text-white text-center freeze-last" style="width: 5%">#</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($kontrabon as $d)
                            <tr>
                                <td class="freeze-1"><span class="fw-bold">{{ $d->no_kontrabon }}</span></td>
                                <td class="freeze-2">{{ $d->no_dokumen }}</td>
                                <td class="freeze-3">{{ formatIndo($d->tanggal) }}</td>
                                <td class="freeze-4">{{ $d->nama_supplier }}</td>
                                <td>
                                    @if ($d->kategori == 'TN')
                                        <span class="badge bg-label-success shadow-sm">Tunai</span>
                                    @elseif ($d->kategori == 'KB')
                                        <span class="badge bg-label-primary shadow-sm">Kontra Bon</span>
                                    @elseif ($d->kategori == 'IM')
                                        <span class="badge bg-label-info shadow-sm">Internal Memo</span>
                                    @endif
                                </td>
                                <td class="text-end fw-bold">{{ formatAngkaDesimal($d->jumlah) }}</td>
                                <td class="text-center">
                                    @if (empty($d->tglbayar))
                                        <span class="badge bg-label-danger shadow-sm">Belum Bayar</span>
                                    @else
                                        <span class="badge bg-label-success shadow-sm">{{ formatIndo($d->tglbayar) }}</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="badge {{ $d->jenis_bayar == 'TN' ? 'bg-label-info' : 'bg-label-secondary' }}">
                                        {{ $d->jenis_bayar == 'TN' ? 'Tunai' : 'Transfer' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    @if ($d->status == 1)
                                        @if (!empty($d->tglbayar))
                                            <span class="badge bg-success shadow-sm">Selesai ({{ $d->nama_bank }})</span>
                                        @else
                                            <span class="badge bg-primary shadow-sm">Approved</span>
                                        @endif
                                    @else
                                        @if (!empty($d->tglbayar))
                                            <span class="badge bg-success shadow-sm">Selesai ({{ $d->nama_bank }})</span>
                                        @else
                                            <i class="ti ti-hourglass-empty text-warning" data-bs-toggle="tooltip" title="Menunggu Approval"></i>
                                        @endif
                                    @endif
                                </td>
                                <td class="freeze-last">
                                    <div class="d-flex justify-content-center gap-2">
                                        @can('kontrabonpmb.show')
                                            <a href="{{ route('kontrabonpmb.cetak', Crypt::encrypt($d->no_kontrabon)) }}" target="_blank" data-bs-toggle="tooltip" title="Cetak">
                                                <i class="ti ti-printer text-primary fs-5"></i>
                                            </a>
                                            <a href="#" no_kontrabon="{{ Crypt::encrypt($d->no_kontrabon) }}" class="btnShow text-info" data-bs-toggle="tooltip" title="Detail">
                                                <i class="ti ti-file-description fs-5"></i>
                                            </a>
                                        @endcan
                                        @can('kontrabonpmb.edit')
                                            @if ($d->kategori != 'TN' && $d->status === '0')
                                                <a href="{{ route('kontrabonpmb.edit', Crypt::encrypt($d->no_kontrabon)) }}" class="text-success" data-bs-toggle="tooltip" title="Edit">
                                                    <i class="ti ti-edit fs-5"></i>
                                                </a>
                                            @endif
                                        @endcan
                                        @can('kontrabonpmb.approve')
                                            @if ($d->kategori != 'TN')
                                                @if ($d->status === '0')
                                                    <a href="{{ route('kontrabonpmb.approve', Crypt::encrypt($d->no_kontrabon)) }}" class="text-success" data-bs-toggle="tooltip" title="Approve">
                                                        <i class="ti ti-checks fs-5"></i>
                                                    </a>
                                                @elseif (empty($d->tglbayar))
                                                    <a href="{{ route('kontrabonpmb.cancel', Crypt::encrypt($d->no_kontrabon)) }}" class="text-danger" data-bs-toggle="tooltip" title="Batalkan Approval">
                                                        <i class="ti ti-square-rounded-x fs-5"></i>
                                                    </a>
                                                @endif
                                            @endif
                                        @endcan
                                        @can('kontrabonpmb.proses')
                                            @if (($d->status === '1' && $d->kategori != 'TN' && empty($d->tglbayar)) || ($d->status === '0' && $d->kategori == 'TN' && empty($d->tglbayar)))
                                                <a href="#" no_kontrabon="{{ Crypt::encrypt($d->no_kontrabon) }}" class="btnProses text-warning" data-bs-toggle="tooltip" title="Proses Bayar">
                                                    <i class="ti ti-external-link fs-5"></i>
                                                </a>
                                            @elseif (($d->status === '1' && $d->kategori != 'TN' && !empty($d->tglbayar)) || ($d->status === '0' && $d->kategori == 'TN' && !empty($d->tglbayar)))
                                                <form method="POST" name="deleteform" class="deleteform d-inline" action="{{ route('kontrabonpmb.cancelproses', Crypt::encrypt($d->no_kontrabon)) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="cancel-confirm bg-transparent border-0 text-danger p-0" data-bs-toggle="tooltip" title="Batalkan Proses">
                                                        <i class="ti ti-xbox-x fs-5"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        @endcan
                                        @can('kontrabonpmb.delete')
                                            @if ($d->kategori != 'TN' && $d->status === '0')
                                                <form method="POST" name="deleteform" class="deleteform d-inline" action="{{ route('kontrabonpmb.delete', Crypt::encrypt($d->no_kontrabon)) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="delete-confirm bg-transparent border-0 text-danger p-0" data-bs-toggle="tooltip" title="Hapus">
                                                        <i class="ti ti-trash fs-5"></i>
                                                    </button>
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
                    {{ $kontrabon->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<x-modal-form id="modal" show="loadmodal" title="" />
<x-modal-form id="modalDetailpembelian" show="loadmodalDetailpembelian" title="" />

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

            $(".btnProses").click(function(e) {
                e.preventDefault();
                loading();
                var no_kontrabon = $(this).attr("no_kontrabon");
                $("#modal").modal("show");
                $("#modal").find(".modal-title").text("Proses Kontrabon");
                $("#modal").find("#loadmodal").load(`/kontrabonpembelian/${no_kontrabon}/proses`);
                $("#modal").find(".modal-dialog").addClass('modal-lg');
            });

            $(document).on('click', '.btnShowpembelian', function(e) {
                e.preventDefault();
                var no_bukti = $(this).attr("no_bukti");
                $("#modalDetailpembelian").modal("show");
                $("#modalDetailpembelian").find(".modal-title").text("Detail Pembelian");
                $("#modalDetailpembelian").find("#loadmodalDetailpembelian").load(`/pembelian/${no_bukti}/show`);
                $("#modalDetailpembelian").find(".modal-dialog").addClass('modal-xl');
            });
        });
    </script>
@endpush
