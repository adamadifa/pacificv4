<div class="row mt-2">
    <div class="col-12">
        <form action="{{ request()->url() }}" id="formSearch" method="GET">

            <div class="row">
                <div class="col-lg-6 col-sm-12 col-md-12">
                    <x-input-with-icon label="Dari" value="{{ Request('dari') }}" name="dari" icon="ti ti-calendar" datepicker="flatpickr-date" />
                </div>
                <div class="col-lg-6 col-sm-12 col-md-12">
                    <x-input-with-icon label="Sampai" value="{{ Request('sampai') }}" name="sampai" icon="ti ti-calendar"
                        datepicker="flatpickr-date" />
                </div>
            </div>

            <div class="row">
                <div class="col-lg-4 col-md-12 col-sm-12">
                    <x-select label="Semua Supplier" name="kode_supplier_search" :data="$supplier" key="kode_supplier" textShow="nama_supplier"
                        upperCase="true" selected="{{ Request('kode_supplier_search') }}" select2="select2Kodesupplier" />
                </div>
                <div class="col-lg-4 col-md-12 col-sm-12">
                    <select name="status_search" id="status_search" class="form-select">
                        <option value="">Status</option>
                        <option value="SP" {{ Request('status_search') == 'SP' ? 'selected' : '' }}>Sudah di Proses</option>
                        <option value="BP" {{ Request('status_search') === 'BP' ? 'selected' : '' }}>Belum di Proses</option>
                    </select>
                </div>
                <div class="col-lg-4 col-md-12 col-sm-12">
                    <select name="kategori_search" id="kategori_search" class="form-select">
                        <option value="">Jenis Pengajuan</option>
                        <option {{ Request('kategori_search') == 'KB' ? 'selected' : '' }} value="KB">Kontra BON</option>
                        <option {{ Request('kategori_search') == 'IM' ? 'selected' : '' }} value="IM">Internal Memo</option>
                        <option {{ Request('kategori_search') == 'TN' ? 'selected' : '' }} value="TN">Tunai</option>
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
                        <th style="width: 10%">No Dok</th>
                        <th style="width: 10%">Tanggal</th>
                        <th>Kategori</th>
                        <th style="width: 25%">Supplier</th>
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
                                        <span class="badge bg-success">Selesai ({{ $d->nama_bank }})</span>
                                    @else
                                        <span class="badge bg-primary">Approved</span>
                                    @endif
                                @else
                                    @if (!empty($d->tglbayar))
                                        <span class="badge bg-success">Selesai ({{ $d->nama_bank }})</span>
                                    @else
                                        <i class="ti ti-hourglass-empty text-warning"></i>
                                    @endif
                                @endif
                            </td>
                            <td>
                                <div class="d-flex">
                                    @can('kontrabonpmb.show')
                                        <a href="{{ route('kontrabonpmb.cetak', Crypt::encrypt($d->no_kontrabon)) }}" target="_blank" class="me-1">
                                            <i class="ti ti-printer text-primary"></i>
                                        </a>
                                        <a href="#" no_kontrabon="{{ Crypt::encrypt($d->no_kontrabon) }}" class="btnShow me-1">
                                            <i class="ti ti-file-description text-info"></i>
                                        </a>
                                    @endcan
                                    @can('kontrabonpmb.edit')
                                        @if ($d->kategori != 'TN')
                                            @if ($d->status === '0')
                                                <a href="{{ route('kontrabonpmb.edit', Crypt::encrypt($d->no_kontrabon)) }}" class="me-1">
                                                    <i class="ti ti-edit text-success"></i>
                                                </a>
                                            @endif
                                        @endif
                                    @endcan
                                    @can('kontrabonpmb.approve')
                                        @if ($d->kategori != 'TN')
                                            @if ($d->status === '0')
                                                <a href="{{ route('kontrabonpmb.approve', Crypt::encrypt($d->no_kontrabon)) }}" class="me-1">
                                                    <i class="ti ti-checks text-success"></i>
                                                </a>
                                            @else
                                                @if (empty($d->tglbayar))
                                                    <a href="{{ route('kontrabonpmb.cancel', Crypt::encrypt($d->no_kontrabon)) }}" class="me-1">
                                                        <i class="ti ti-square-rounded-x text-danger"></i>
                                                    </a>
                                                @endif
                                            @endif
                                        @endif
                                    @endcan
                                    @can('kontrabonpmb.proses')
                                        @if (($d->status === '1' && $d->kategori != 'TN' && empty($d->tglbayar)) || ($d->status === '0' && $d->kategori == 'TN' && empty($d->tglbayar)))
                                            <a href="#" no_kontrabon ="{{ Crypt::encrypt($d->no_kontrabon) }}" class="btnProses me-1">
                                                <i class="ti ti-external-link text-success"></i>
                                            </a>
                                        @else
                                            @if (($d->status === '1' && $d->kategori != 'TN' && !empty($d->tglbayar)) || ($d->status === '0' && $d->kategori == 'TN' && !empty($d->tglbayar)))
                                                <form method="POST" name="deleteform" class="deleteform"
                                                    action="{{ route('kontrabonpmb.cancelproses', Crypt::encrypt($d->no_kontrabon)) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <a href="#" class="cancel-confirm me-1">
                                                        <i class="ti ti-xbox-x text-danger"></i>
                                                    </a>
                                                </form>
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
