@can('fsthp.create')
    <a href="#" class="btn btn-primary" id="btncreateFsthp"><i class="fa fa-plus me-2"></i>
        Tambah FSTHP</a>
@endcan
<div class="row mt-2">
    <div class="col-12">
        <form action="{{ route('fsthp.index') }}">
            <div class="row">
                <div class="col-lg-10 col-sm-12 col-md-12">
                    <x-input-with-icon label="Tanggal Mutasi" value="{{ Request('tanggal_mutasi_search') }}" name="tanggal_mutasi_search"
                        icon="ti ti-calendar" datepicker="flatpickr-date" />
                </div>

                <div class="col-lg-2 col-sm-12 col-md-12">
                    <button class="btn btn-primary"><i class="ti ti-icons ti-search me-1"></i></button>
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
                        <th>No. FSTHP</th>
                        <th>Tanggal</th>
                        <th>Unit</th>
                        <th>Status</th>
                        <th>#</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($fsthp as $d)
                        <tr>
                            <td>{{ $d->no_mutasi }}</td>
                            <td>{{ date('d-m-Y', strtotime($d->tanggal_mutasi)) }}</td>
                            <td>{{ $d->unit }}</td>
                            <td>
                                @if ($d->status === '1')
                                    <span class="badge bg-success">Diterima Gudang</span>
                                @else
                                    <span class="badge bg-danger">Belum Diterima Gudang</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex">
                                    @can('fsthp.show')
                                        <div>
                                            <a href="#" class="me-1 showFsthp" no_mutasi="{{ Crypt::encrypt($d->no_mutasi) }}">
                                                <i class="ti ti-file-description text-info"></i>
                                            </a>
                                        </div>
                                    @endcan

                                    @can('fsthp.delete')
                                        @if ($d->status !== '1')
                                            <div>
                                                <form method="POST" name="deleteform" class="deleteform me-1"
                                                    action="{{ route('fsthp.delete', Crypt::encrypt($d->no_mutasi)) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <a href="#" class="delete-confirm ml-1">
                                                        <i class="ti ti-trash text-danger"></i>
                                                    </a>
                                                </form>
                                            </div>
                                        @endif
                                    @endcan
                                    @can('fsthp.approve')
                                        @if ($d->status !== '1')
                                            <div>
                                                <a href="{{ route('fsthp.approve', Crypt::encrypt($d->no_mutasi)) }}" class="me-1">
                                                    <i class="ti ti-square-rounded-check text-success"></i>
                                                </a>
                                            </div>
                                        @else
                                            <div>
                                                <form method="POST" name="deleteform" class="deleteform me-1"
                                                    action="{{ route('fsthp.cancel', Crypt::encrypt($d->no_mutasi)) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <a href="#" class="cancel-confirm me-1">
                                                        <i class="ti ti-square-rounded-minus text-warning"></i>
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
            {{ $fsthp->links() }}
        </div>
    </div>
</div>
@push('myscript')
@endpush
