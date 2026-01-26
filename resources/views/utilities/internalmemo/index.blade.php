@extends('layouts.app')
@section('titlepage', 'Internal Memo')

@section('content')
@section('navigasi')
    <span>Internal Memo</span>
@endsection

<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                <a href="#" class="btn btn-primary" id="btnCreate">
                    <i class="fa fa-plus me-2"></i> Tambah Internal Memo
                </a>
            </div>
            <div class="card-body">

                <form method="GET" class="row g-2 mb-3" autocomplete="off">

                    <div class="col-md-3">
                        <input type="text" name="no_im_search" value="{{ request('no_im_search') }}" class="form-control"
                            placeholder="No IM">
                    </div>

                    <div class="col-md-3">
                        <input type="text" name="judul_search" value="{{ request('judul_search') }}" class="form-control"
                            placeholder="Judul Memo">
                    </div>

                    <div class="col-md-2">
                        <select name="status" class="form-select">
                            <option value="">- Status -</option>
                            <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>
                                Aktif
                            </option>
                            <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>
                                Nonaktif
                            </option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <select name="dibaca" class="form-select">
                            <option value="">- Dibaca -</option>
                            <option value="sudah" {{ request('dibaca') == 'sudah' ? 'selected' : '' }}>
                                Sudah
                            </option>
                            <option value="belum" {{ request('dibaca') == 'belum' ? 'selected' : '' }}>
                                Belum
                            </option>
                        </select>
                    </div>

                    <div class="col-md-2 d-flex gap-1">
                        <button class="btn btn-primary w-100">
                            <i class="ti ti-search"></i>
                        </button>

                        <a href="{{ route('internalmemo.index') }}" class="btn btn-secondary w-100">
                            <i class="ti ti-refresh"></i>
                        </a>
                    </div>

                </form>
                <div class="table-responsive mb-2">
                    <table class="table table-hover table-bordered align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th width="2%" class="text-center">#</th>
                                <th width="12%">No IM</th>
                                <th>Judul Memo</th>
                                <th width="10%">Tgl Upload</th>
                                <th width="5%" class="text-center">
                                    <i class="ti ti-paperclip"></i>
                                </th>
                                <th width="6%" class="text-center">Dibaca</th>
                                <th width="6%" class="text-center">Status</th>
                                <th width="8%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($internalMemos as $row)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>

                                    <td>
                                        <strong>{{ $row->no_im }}</strong>
                                    </td>

                                    <td>
                                        <div class="fw-semibold">{{ $row->judul }}</div>
                                    </td>

                                    <td class="text-center">
                                        {{ formatIndo($row->tanggal_im) }}
                                    </td>
                                    @php
                                        $isExpired = false;
                                        if (!empty($row->berlaku_sampai) && $row->berlaku_sampai < date('Y-m-d')) {
                                            $isExpired = true;
                                        }

                                        $isAktif = $row->status === 'aktif' && !$isExpired;
                                    @endphp
                                    {{-- <td>
                                        <button class="btn btn-sm {{ $isAktif ? 'btn-primary' : 'btn-danger' }}">
                                            {{ $row->berlaku_dari != '' ? formatIndo($row->berlaku_dari) : formatIndo($row->tanggal_im) }}

                                        </button>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm {{ $isAktif ? 'btn-primary' : 'btn-danger' }}">
                                            {{ $row->berlaku_sampai != '' ? formatIndo($row->berlaku_sampai) : 'Tidak Ditentukan' }}
                                        </button>
                                    </td> --}}
                                    <td class="text-center">
                                        @if ($row->file_im)
                                            <a href="{{ Storage::url('internal_memo/' . $row->file_im) }}"
                                                target="_blank" class="badge bg-info text-white">
                                                <i class="ti ti-paperclip"></i>
                                            </a>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>

                                    <td class="text-center">
                                        @if ($row->dibaca_pada)
                                            <button class="btn btn-sm btn-success">Sudah</button>
                                        @else
                                            <button class="btn btn-sm btn-warning">Belum</button>
                                        @endif
                                    </td>

                                    <td class="text-center">
                                        @if ($isAktif)
                                            <button type="button" class="btn btn-sm btn-success btnToggleStatus"
                                                data-id="{{ $row->id }}" data-status="nonaktif"
                                                title="Nonaktifkan">
                                                <i class="ti ti-thumb-up"></i>
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-sm btn-danger btnToggleStatus"
                                                data-id="{{ $row->id }}" data-status="aktif" title="Aktifkan">
                                                <i class="ti ti-thumb-down"></i>
                                            </button>
                                        @endif
                                    </td>


                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm">
                                            <a href="#" class="btn btn-success btnShow"
                                                data-id="{{ $row->id }}">
                                                <i class="ti ti-eye"></i>
                                            </a>
                                            <a href="#" class="btn btn-primary btnEdit"
                                                data-id="{{ $row->id }}">
                                                <i class="ti ti-edit"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted">
                                        <i class="ti ti-file-off fs-3"></i><br>
                                        Tidak ada Internal Memo
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{ $internalMemos->links() }}
            </div>
        </div>
    </div>
</div>

{{-- MODAL --}}
<x-modal-form id="mdlInternalMemo" size="modal-xl" show="loadInternalMemo" title="Internal Memo" />

@endsection

@push('myscript')
<script>
    $(function() {

        function openModal(url) {
            const modal = $("#mdlInternalMemo");
            const container = $("#loadInternalMemo");

            // HENTIKAN request sebelumnya
            container.stop(true, true);

            // KOSONGKAN isi dulu
            container.html(`
            <div class="text-center p-4 text-muted">
                <i class="ti ti-loader ti-spin fs-3"></i><br>
                Loading...
            </div>
        `);

            modal.modal("show");

            // LOAD konten baru
            container.load(url);
        }

        // CREATE
        $("#btnCreate").click(function(e) {
            e.preventDefault();
            openModal("{{ route('internalmemo.create') }}");
        });

        // SHOW
        $(".btnShow").click(function(e) {
            e.preventDefault();
            const id = $(this).data('id');
            openModal(`/internalmemo/${id}/show`);
        });

        // EDIT
        $(".btnEdit").click(function(e) {
            e.preventDefault();
            const id = $(this).data('id');
            openModal(`/internalmemo/${id}/edit`);
        });

        $(document).on('click', '.btnToggleStatus', function(e) {
            e.preventDefault();

            const id = $(this).data('id');
            const status = $(this).data('status');

            const url = status === 'aktif' ?
                `/internalmemo/${id}/aktifkan` :
                `/internalmemo/${id}/nonaktifkan`;

            Swal.fire({
                title: 'Yakin?',
                text: status === 'aktif' ?
                    'Aktifkan Internal Memo ini?' : 'Nonaktifkan Internal Memo ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('<form>', {
                            method: 'POST',
                            action: url
                        })
                        .append(
                            `<input type="hidden" name="_token" value="{{ csrf_token() }}">`)
                        .appendTo('body')
                        .submit();
                }
            });
        });


    });
</script>
@endpush
