<div class="row">
    <div class="col-12">
        <form action="{{ request()->url() }}" id="formSearch" method="GET">
            <div class="row g-2 mb-3 align-items-end">
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <x-input-with-icon label="Dari" value="{{ Request('dari') }}" name="dari" icon="ti ti-calendar"
                        datepicker="flatpickr-date" hideLabel="true" />
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <x-input-with-icon label="Sampai" value="{{ Request('sampai') }}" name="sampai" icon="ti ti-calendar"
                        datepicker="flatpickr-date" hideLabel="true" />
                </div>
                <div class="col-lg-4 col-md-8 col-sm-12">
                    <x-select label="Semua Angkutan" name="kode_angkutan_search" :data="$angkutan" key="kode_angkutan" textShow="nama_angkutan"
                        upperCase="true" selected="{{ Request('kode_angkutan_search') }}" select2="select2Kodeangkutansearch" hideLabel="true" />
                </div>
                <div class="col-lg-2 col-md-4 col-sm-12">
                    <div class="form-group mb-3">
                        <select name="status_search" id="status_search" class="form-select">
                            <option value="">Status</option>
                            <option value="SP" {{ Request('status_search') == 'SP' ? 'selected' : '' }}>Sudah di Proses</option>
                            <option value="BP" {{ Request('status_search') === 'BP' ? 'selected' : '' }}>Belum di Proses</option>
                        </select>
                    </div>
                </div>
                <div class="col-12 mt-0">
                    <div class="form-group mb-3">
                        <button class="btn btn-primary w-100"><i class="ti ti-search me-1"></i>Cari Data</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border mt-2">
            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold text-white"><i class="ti ti-file-text me-2"></i>Data Kontrabon Angkutan</h6>
                    @if (request()->is('kontrabonangkutan'))
                        @can('kontrabonangkutan.create')
                            <a href="{{ route('kontrabonangkutan.create') }}" class="btn btn-primary btn-sm" id="btnCreate">
                                <i class="ti ti-plus me-1"></i> Buat Kontra Bon
                            </a>
                        @endcan
                    @endif
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead style="background-color: #002e65;">
                        <tr>
                            <th class="text-white">NO. KONTRA BON</th>
                            <th class="text-white">TANGGAL</th>
                            <th class="text-white">ANGKUTAN</th>
                            <th class="text-white text-center">STATUS</th>
                            <th class="text-white text-center">#</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($kontrabon as $d)
                            <tr>
                                <td><span class="fw-bold text-primary">{{ $d->no_kontrabon }}</span></td>
                                <td>{{ formatIndo($d->tanggal) }}</td>
                                <td>{{ $d->nama_angkutan }}</td>
                                <td class="text-center">
                                    @if (!empty($d->tanggal_bayar) || !empty($d->tanggal_bayar_hutang))
                                        <span class="badge bg-success">
                                            {{ formatIndo($d->tanggal_bayar ?? $d->tanggal_bayar_hutang) }}
                                        </span>
                                    @else
                                        <i class="ti ti-hourglass-empty text-warning"></i>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        @can('kontrabonangkutan.show')
                                            <a href="#" class="btnShow text-info" no_kontrabon="{{ Crypt::encrypt($d->no_kontrabon) }}"
                                                data-bs-toggle="tooltip" title="Detail">
                                                <i class="ti ti-file-description"></i>
                                            </a>
                                        @endcan
                                        @can('kontrabonangkutan.delete')
                                            @if (empty($d->tanggal_bayar) && empty($d->tanggal_bayar_hutang))
                                                <form method="POST" name="deleteform" class="deleteform d-inline"
                                                    action="{{ route('kontrabonangkutan.delete', Crypt::encrypt($d->no_kontrabon)) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <a href="#" class="delete-confirm text-danger" data-bs-toggle="tooltip" title="Hapus">
                                                        <i class="ti ti-trash"></i>
                                                    </a>
                                                </form>
                                            @endif
                                        @endcan
                                        @can('kontrabonangkutan.proses')
                                            @if (empty($d->tanggal_bayar) && empty($d->tanggal_bayar_hutang))
                                                <a href="#" class="btnProses text-primary" no_kontrabon="{{ Crypt::encrypt($d->no_kontrabon) }}"
                                                    data-bs-toggle="tooltip" title="Proses">
                                                    <i class="ti ti-external-link"></i>
                                                </a>
                                            @else
                                                <form method="POST" name="deleteform" class="deleteform d-inline"
                                                    action="{{ route('kontrabonangkutan.cancelproses', Crypt::encrypt($d->no_kontrabon)) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <a href="#" class="cancel-confirm text-danger" data-bs-toggle="tooltip" title="Batalkan">
                                                        <i class="ti ti-square-rounded-x"></i>
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
            const select2Kodeangkutansearch = $('.select2Kodeangkutansearch');
            if (select2Kodeangkutansearch.length) {
                select2Kodeangkutansearch.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Semua Angkutan',
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
                $("#modal").find("#loadmodal").load(`/kontrabonangkutan/${no_kontrabon}/show`);
                $("#modal").find(".modal-dialog").addClass('modal-xl');
            });

            $(".btnProses").click(function(e) {
                e.preventDefault();
                loading();
                var no_kontrabon = $(this).attr("no_kontrabon");
                $("#modal").modal("show");
                $("#modal").find(".modal-title").text("Proses Kontrabon");
                $("#modal").find("#loadmodal").load(`/kontrabonangkutan/${no_kontrabon}/proses`);
                $("#modal").find(".modal-dialog").addClass('modal-xl');
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
