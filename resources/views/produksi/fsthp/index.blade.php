{{-- Filter Section (Below Navigation) --}}
<form action="{{ route('fsthp.index') }}">
    <div class="row g-2 align-items-end mb-3">
        <div class="col-lg-10 col-md-10 col-sm-12">
            <x-input-with-icon label="Tanggal Mutasi" value="{{ Request('tanggal_mutasi_search') }}" name="tanggal_mutasi_search"
                icon="ti ti-calendar" datepicker="flatpickr-date" hideLabel="true" />
        </div>
        <div class="col-lg-2 col-md-2 col-sm-12">
            <div class="form-group mb-3">
                <button class="btn btn-primary w-100"><i class="ti ti-search"></i></button>
            </div>
        </div>
    </div>
</form>

{{-- Data Card --}}
<div class="card shadow-sm border">
    <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
        <div class="d-flex justify-content-between align-items-center">
            <h6 class="m-0 fw-bold text-white"><i class="ti ti-package-export me-2"></i>Data FSTHP</h6>
            @can('fsthp.create')
                <a href="#" class="btn btn-primary btn-sm shadow-sm" id="btncreateFsthp">
                    <i class="ti ti-plus me-1"></i> Tambah FSTHP
                </a>
            @endcan
        </div>
    </div>
    <div class="table-responsive text-nowrap">
        <table class="table table-hover table-striped">
            <thead>
                <tr>
                    <th class="text-white" style="background-color: #002e65 !important;">NO. FSTHP</th>
                    <th class="text-white" style="background-color: #002e65 !important;">TANGGAL</th>
                    <th class="text-white" style="background-color: #002e65 !important;">UNIT</th>
                    <th class="text-white" style="background-color: #002e65 !important;">STATUS</th>
                    <th class="text-white text-center" style="background-color: #002e65 !important;">#</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @foreach ($fsthp as $d)
                    <tr>
                        <td><span class="fw-bold text-primary">{{ $d->no_mutasi }}</span></td>
                        <td>{{ date('d-m-Y', strtotime($d->tanggal_mutasi)) }}</td>
                        <td>{{ $d->unit }}</td>
                        <td>
                            @if ($d->status === '1')
                                <span class="badge bg-success shadow-none">Diterima Gudang</span>
                            @else
                                <span class="badge bg-danger shadow-none">Belum Diterima Gudang</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex justify-content-center gap-2">
                                @can('fsthp.show')
                                    <a href="#" class="showFsthp text-info" data-bs-toggle="tooltip" title="Detail"
                                        no_mutasi="{{ Crypt::encrypt($d->no_mutasi) }}">
                                        <i class="ti ti-file-description fs-5"></i>
                                    </a>
                                @endcan

                                @can('fsthp.delete')
                                    @if ($d->status !== '1')
                                        <form method="POST" name="deleteform" class="deleteform d-inline"
                                            action="{{ route('fsthp.delete', Crypt::encrypt($d->no_mutasi)) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="delete-confirm bg-transparent border-0 text-danger p-0"
                                                data-bs-toggle="tooltip" title="Hapus">
                                                <i class="ti ti-trash fs-5"></i>
                                            </button>
                                        </form>
                                    @endif
                                @endcan

                                @can('fsthp.approve')
                                    @if ($d->status !== '1')
                                        <a href="{{ route('fsthp.approve', Crypt::encrypt($d->no_mutasi)) }}" class="text-success"
                                            data-bs-toggle="tooltip" title="Terima">
                                            <i class="ti ti-square-rounded-check fs-5"></i>
                                        </a>
                                    @else
                                        <form method="POST" name="deleteform" class="deleteform d-inline"
                                            action="{{ route('fsthp.cancel', Crypt::encrypt($d->no_mutasi)) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="cancel-confirm bg-transparent border-0 text-warning p-0"
                                                data-bs-toggle="tooltip" title="Batalkan">
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
            {{ $fsthp->links() }}
        </div>
    </div>
</div>
@push('myscript')
@endpush
