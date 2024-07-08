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
                <div class="col-lg-8 col-md-12 col-sm-12">
                    <x-select label="Semua Angkutan" name="kode_angkutan_search" :data="$angkutan" key="kode_angkutan" textShow="nama_angkutan"
                        upperCase="true" selected="{{ Request('kode_angkutan_search') }}" select2="select2Kodeangkutansearch" />
                </div>
                <div class="col-lg-4 col-md-12 col-sm-12">
                    <select name="status_search" id="status_search" class="form-select">
                        <option value="">Status</option>
                        <option value="SP" {{ Request('status_search') == 'SP' ? 'selected' : '' }}>Sudah di Proses</option>
                        <option value="BP" {{ Request('status_search') === 'BP' ? 'selected' : '' }}>Belum di Proses</option>
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
                        <th>Tanggal</th>
                        <th>Angkutan</th>
                        <th class="text-center">Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($kontrabon as $d)
                        <tr>
                            <td>{{ $d->no_kontrabon }}</td>
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
                            <td>
                                <div class="d-flex">
                                    @can('kontrabonangkutan.show')
                                        <a href="#" class="btnShow" no_kontrabon="{{ Crypt::encrypt($d->no_kontrabon) }}">
                                            <i class="ti ti-file-description text-info"></i>
                                        </a>
                                    @endcan
                                    @can('kontrabonangkutan.delete')
                                        @if (empty($d->tanggal_bayar) && empty($d->tanggal_bayar_hutang))
                                            <form method="POST" name="deleteform" class="deleteform"
                                                action="{{ route('kontrabonangkutan.delete', Crypt::encrypt($d->no_kontrabon)) }}">
                                                @csrf
                                                @method('DELETE')
                                                <a href="#" class="delete-confirm me-1">
                                                    <i class="ti ti-trash text-danger"></i>
                                                </a>
                                            </form>
                                        @endif
                                    @endcan
                                    @can('kontrabonangkutan.proses')
                                        @if (empty($d->tanggal_bayar) && empty($d->tanggal_bayar_hutang))
                                            <a href="#" class="btnProses me-1" no_kontrabon="{{ Crypt::encrypt($d->no_kontrabon) }}">
                                                <i class="ti ti-external-link text-primary"></i>
                                            </a>
                                        @else
                                            <form method="POST" name="deleteform" class="deleteform"
                                                action="{{ route('kontrabonangkutan.cancelproses', Crypt::encrypt($d->no_kontrabon)) }}">
                                                @csrf
                                                @method('DELETE')
                                                <a href="#" class="cancel-confirm me-1">
                                                    <i class="ti ti-square-rounded-x text-danger"></i>
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
