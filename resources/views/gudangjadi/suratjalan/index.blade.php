{{-- Filter Section --}}
<form action="{{ request()->is(['suratjalancabang']) ? route('suratjalancabang.index') : route('suratjalan.index') }}">
    <div class="row g-2 mb-3 align-items-end">
        <div class="col-lg-2 col-md-4 col-sm-6">
            <x-input-with-icon label="Dari" value="{{ Request('dari') }}" name="dari" icon="ti ti-calendar"
                datepicker="flatpickr-date" />
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6">
            <x-input-with-icon label="Sampai" value="{{ Request('sampai') }}" name="sampai" icon="ti ti-calendar"
                datepicker="flatpickr-date" />
        </div>
        <div class="col-lg-2 col-md-4 col-sm-12">
            <x-input-with-icon label="No. Dokumen" name="no_dok_search" icon="ti ti-barcode"
                value="{{ Request('no_dok_search') }}" />
        </div>
        @hasanyrole($roles_show_cabang)
            <div class="col-lg-2 col-md-12 col-sm-12">
                <x-select label="Cabang" name="kode_cabang_search" :data="$cabang" key="kode_cabang" textShow="nama_cabang"
                    upperCase="true" selected="{{ Request('kode_cabang_search') }}" select2="select2Kodecabangsearch" />
            </div>
        @endrole
        <div class="col-lg-2 col-md-6 col-sm-12">
            <div class="form-group mb-3">
                <select name="status_search" id="status_search" class="form-select">
                    <option value="">Semua Status</option>
                    <option {{ Request('status_search') == '0' ? 'selected' : '' }} value="0">Belum Diterima Cabang
                    </option>
                    <option {{ Request('status_search') == '1' ? 'selected' : '' }} value="1">Sudah Diterima Cabang
                    </option>
                    <option {{ Request('status_search') == '2' ? 'selected' : '' }} value="2">Transit Out</option>
                </select>
            </div>
        </div>
        <div class="col-lg-2 col-md-6 col-sm-12">
            <div class="form-group mb-3">
                <button class="btn btn-primary w-100"><i class="ti ti-search me-1"></i> Cari</button>
            </div>
        </div>
    </div>
</form>

{{-- Data Card --}}
<div class="card shadow-sm border mt-2">
    <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
        <div class="d-flex justify-content-between align-items-center">
            <h6 class="m-0 fw-bold text-white"><i class="ti ti-truck me-2"></i>Data Surat Jalan</h6>
            @can('suratjalan.create')
                <a href="{{ route('permintaankiriman.index') }}" class="btn btn-primary btn-sm shadow-sm">
                    <i class="ti ti-plus me-1"></i> Tambah Data
                </a>
            @endcan
        </div>
    </div>
    <div class="table-responsive text-nowrap">
        <table class="table table-hover table-striped">
            <thead style="background-color: #002e65;">
                <tr>
                    <th class="text-white">NO. SURAT JALAN</th>
                    <th class="text-white">NO. DOKUMEN</th>
                    <th class="text-white">TANGGAL</th>
                    <th class="text-white">CABANG</th>
                    <th class="text-white">STATUS</th>
                    <th class="text-white">TGL DITERIMA</th>
                    <th class="text-white text-center">#</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @foreach ($surat_jalan as $d)
                    <tr>
                        <td><span class="fw-bold text-primary">{{ $d->no_mutasi }}</span></td>
                        <td>{{ $d->no_dok }}</td>
                        <td>{{ date('d-m-Y', strtotime($d->tanggal)) }}</td>
                        <td>{{ textUpperCase($d->nama_cabang) }}</td>
                        <td>
                            @if ($d->status_surat_jalan == 0)
                                <span class="badge bg-danger">Belum Diterima</span>
                            @elseif($d->status_surat_jalan == 1)
                                <span class="badge bg-success">Sudah Diterima</span>
                            @elseif($d->status_surat_jalan == 2)
                                <span class="badge bg-info">Transit Out</span>
                            @endif
                        </td>
                        <td>
                            @if (!empty($d->tanggal_mutasi_cabang))
                                @if ($d->status_surat_jalan == '1')
                                    @if (empty($d->tanggal_transit_in))
                                        <span class="badge bg-success">
                                            {{ date('d-m-Y', strtotime($d->tanggal_mutasi_cabang)) }}
                                        </span>
                                    @else
                                        <span class="badge bg-success">
                                            {{ date('d-m-Y', strtotime($d->tanggal_transit_in)) }}
                                        </span>
                                    @endif
                                @elseif($d->status_surat_jalan == '2')
                                    <span class="badge bg-info">
                                        {{ date('d-m-Y', strtotime($d->tanggal_mutasi_cabang)) }}
                                    </span>
                                @endif
                            @else
                                <i class="ti ti-refresh text-warning"></i>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex justify-content-center gap-2">
                                @can('suratjalan.edit')
                                    @if ($d->status_surat_jalan == '0')
                                        <a href="#" class="btnEdit text-success" data-bs-toggle="tooltip" title="Edit"
                                            no_mutasi="{{ Crypt::encrypt($d->no_mutasi) }}">
                                            <i class="ti ti-edit fs-5"></i>
                                        </a>
                                    @endif
                                @endcan
                                @can('suratjalan.show')
                                    <a href="#" class="btnShow text-info" data-bs-toggle="tooltip" title="Detail"
                                        no_mutasi="{{ Crypt::encrypt($d->no_mutasi) }}">
                                        <i class="ti ti-file-description fs-5"></i>
                                    </a>
                                @endcan
                                @can('suratjalan.delete')
                                    @if ($d->status_surat_jalan == '0')
                                        <form method="POST" name="deleteform" class="deleteform d-inline"
                                            action="{{ route('suratjalan.delete', Crypt::encrypt($d->no_mutasi)) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="delete-confirm bg-transparent border-0 text-danger p-0"
                                                data-bs-toggle="tooltip" title="Hapus">
                                                <i class="ti ti-trash fs-5"></i>
                                            </button>
                                        </form>
                                    @endif
                                @endcan
                                @can('suratjalan.approve')
                                    @if ($d->status_surat_jalan === '0')
                                        <a href="#" class="btnApprove text-primary" data-bs-toggle="tooltip" title="Terima"
                                            no_mutasi="{{ Crypt::encrypt($d->no_mutasi) }}">
                                            <i class="ti ti-external-link fs-5"></i>
                                        </a>
                                    @else
                                        <form method="POST" name="deleteform" class="deleteform d-inline"
                                            action="{{ route('suratjalan.cancel', Crypt::encrypt($d->no_mutasi)) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="cancel-confirm bg-transparent border-0 text-warning p-0"
                                                data-bs-toggle="tooltip" title="Batalkan Penerimaan">
                                                <i class="ti ti-square-rounded-minus fs-5"></i>
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
            {{ $surat_jalan->links() }}
        </div>
    </div>
</div>
