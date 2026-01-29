@extends('layouts.app')
@section('titlepage', 'Internal Memo')

@section('content')
@section('navigasi')
    <span>Internal Memo</span>
@endsection


@php
    $chatCount = DB::table('internal_memo_chat')
        ->select('internal_memo_id', DB::raw('COUNT(*) as total'))
        ->groupBy('internal_memo_id')
        ->pluck('total', 'internal_memo_id');
    $superUser = ['74', '1', '29'];
    $isSuperUser = in_array(Auth::user()->id, $superUser);
@endphp
<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                @if (in_array(Auth::user()->id, $superUser))
                    <a href="#" class="btn btn-primary" id="btnCreate">
                        <i class="fa fa-plus me-2"></i> Tambah Internal Memo
                    </a>
                @endif
            </div>
            <div class="card-body">

                <form method="GET" class="row g-2 mb-3" autocomplete="off">

                    <div class="col-md-3">
                        <input type="text" name="no_im_search" value="{{ request('no_im_search') }}"
                            class="form-control" placeholder="No IM">
                    </div>

                    <div class="col-md-3">
                        <input type="text" name="judul_search" value="{{ request('judul_search') }}"
                            class="form-control" placeholder="Judul Memo">
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
                            <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>
                                Tidak Berlaku
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
                                <th width="15%">No IM</th>
                                <th>Judul Memo</th>
                                <th width="10%">Tgl Upload</th>
                                <th width="7%" class="text-center">Pemahaman</th>
                                <th width="6%" class="text-center">Dirilis</th>
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
                                        @if (isset($acks[$row->id]) && $acks[$row->id] === 'paham')
                                            <span class="btn btn-sm btn-success disabled">
                                                <i class="ti ti-check"></i>
                                            </span>
                                        @else
                                            <button class="btn btn-sm btn-outline-primary btnAckMemo"
                                                data-id="{{ $row->id }}" data-judul="{{ $row->judul }}">
                                                <i class="ti ti-checklist"></i>
                                            </button>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-semibold">{{ $row->kode_dept }}</div>
                                    </td>
                                    <td class="text-center">
                                        @if ($row->dibaca_pada)
                                            <span class="btn btn-sm btn-success">
                                                <i class="ti ti-eye"></i>
                                            </span>
                                        @else
                                            <span class="btn btn-sm btn-warning">
                                                <i class="ti ti-eye-off"></i>
                                            </span>
                                        @endif
                                    </td>

                                    <td class="text-center">
                                        @if ($isAktif)
                                            <button type="button"
                                                class="btn btn-sm btn-success {{ $isSuperUser ? 'btnToggleStatus' : '' }}"
                                                data-id="{{ $row->id }}" data-status="nonaktif"
                                                title="{{ $isSuperUser ? 'Nonaktifkan' : 'Tidak punya akses' }}">
                                                <i class="ti ti-thumb-up"></i>
                                            </button>
                                        @else
                                            <button type="button"
                                                class="btn btn-sm btn-danger {{ $isSuperUser ? 'btnToggleStatus' : '' }}"
                                                data-id="{{ $row->id }}" data-status="aktif"
                                                title="{{ $isSuperUser ? 'Aktifkan' : 'Tidak punya akses' }}">
                                                <i class="ti ti-thumb-down"></i>
                                            </button>
                                        @endif
                                    </td>

                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm">
                                            @if ($row->file_im)
                                                <a href="{{ Storage::url('internal_memo/' . $row->file_im) }}"
                                                    target="_blank" class="badge bg-info text-white">
                                                    <i class="ti ti-paperclip"></i>
                                                </a>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                            <a href="#" class="btn btn-success btnShow"
                                                data-id="{{ $row->id }}">
                                                <i class="ti ti-eye"></i>
                                            </a>
                                            <div class="btn-group btn-group-sm position-relative">
                                                <a href="#" class="btn btn-warning btnDiskusi"
                                                    data-id="{{ $row->id }}" title="Diskusi">
                                                    <i class="ti ti-message"></i>
                                                </a>
                                                @if (($chatCount[$row->id] ?? 0) > 0)
                                                    <span
                                                        class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                                                        style="z-index:10">
                                                        {{ $chatCount[$row->id] }}
                                                    </span>
                                                @endif
                                            </div>

                                            @if (in_array(Auth::user()->id, $superUser))
                                                <a href="#" class="btn btn-primary btnEdit"
                                                    data-id="{{ $row->id }}">
                                                    <i class="ti ti-edit"></i>
                                                </a>
                                                <button class="btn btn-info btn-sm btnReadLog"
                                                    data-id="{{ $row->id }}"
                                                    data-title="Log Baca - {{ $row->no_im }}">
                                                    <i class="ti ti-users"></i>
                                                </button>
                                            @endif
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
<x-modal-form id="mdlInternalMemoLogBaca" size="modal-lg" show="loadInternalMemoBaca" title="Log Baca IM" />
<x-modal-form id="mdlBelumPaham" size="modal-lg" show="loadBelumPaham" title="Diskusi Internal Memo" />

@php
    $readLogs = DB::table('internal_memo_log_baca as lb')
        ->join('users as u', 'u.id', '=', 'lb.user_id')
        ->leftJoin('internal_memo_ack as ack', function ($join) {
            $join->on('ack.internal_memo_id', '=', 'lb.internal_memo_id')->on('ack.user_id', '=', 'lb.user_id');
        })
        ->select('lb.internal_memo_id', 'u.name as nama_user', 'lb.dibaca_pada', 'ack.status as status_paham')
        ->orderBy('lb.dibaca_pada', 'desc')
        ->get()
        ->groupBy('internal_memo_id');
@endphp
@foreach ($internalMemos as $row)
    <div class="d-none" id="readLog{{ $row->id }}">
        <div class="table-responsive">
            <table class="table table-bordered table-sm mb-0">
                <thead class="table-dark">
                    <tr>
                        <th width="5%">#</th>
                        <th>Nama</th>
                        <th width="35%">Dibaca Pada</th>
                        <th width="20%">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $logs = $readLogs[$row->id] ?? collect();
                    @endphp

                    @forelse ($logs as $log)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $log->nama_user }}</td>
                            <td>{{ $log->dibaca_pada }}</td>
                            <td class="text-center">
                                @if ($log->status_paham === 'paham')
                                    <span class="badge bg-success">Sudah Paham</span>
                                @elseif ($log->status_paham === 'belum')
                                    <span class="badge bg-danger">Belum Paham</span>
                                @else
                                    <span class="badge bg-secondary">Belum Konfirmasi</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted">
                                Belum ada yang membaca
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endforeach
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

        $(document).on('click', '.btnReadLog', function() {
            const id = $(this).data('id');
            const title = $(this).data('title');

            // set judul modal
            $('#mdlInternalMemoLogBaca .modal-title').text(title);

            // ambil HTML log baca
            const content = $('#readLog' + id).html();

            // masukkan ke body modal
            $('#loadInternalMemoBaca').html(content);

            // tampilkan modal
            $('#mdlInternalMemoLogBaca').modal('show');
        });

        $(document).on('click', '.btnAckMemo', function() {

            const id = $(this).data('id');
            const judul = $(this).data('judul');

            Swal.fire({
                title: 'Konfirmasi Pemahaman',
                text: judul,
                icon: 'question',
                showDenyButton: true,
                confirmButtonText: '✅ Sudah Paham',
                denyButtonText: '❓ Belum Paham',
                confirmButtonColor: '#198754',
                denyButtonColor: '#dc3545'
            }).then((result) => {

                // =========================
                // SUDAH PAHAM
                // =========================
                if (result.isConfirmed) {

                    $.post(`/internalmemo/${id}/paham`, {
                        _token: '{{ csrf_token() }}'
                    }, function() {

                        Swal.fire({
                            icon: 'success',
                            title: 'Tercatat',
                            text: 'Status berhasil disimpan'
                        }).then(() => {
                            location.reload();
                        });

                    });

                }

                // =========================
                // BELUM PAHAM
                // =========================
                if (result.isDenied) {

                    // set status belum paham
                    $.post(`/internalmemo/${id}/paham`, {
                        _token: '{{ csrf_token() }}',
                        status: 'belum'
                    }, function() {

                        // buka modal diskusi
                        $('#mdlBelumPaham').modal('show');

                        $('#loadBelumPaham').load(
                            `/internalmemo/${id}/diskusi`
                        );
                    });

                }

            });
        });

        $(document).on('click', '.btnDiskusi', function(e) {
            e.preventDefault();

            const id = $(this).data('id');

            $('#mdlBelumPaham .modal-title').text('Diskusi Internal Memo');

            $('#mdlBelumPaham').modal('show');
            $('#loadBelumPaham').html(`
                <div class="text-center p-4 text-muted">
                    <i class="ti ti-loader ti-spin fs-3"></i><br>
                    Loading...
                </div>
            `);

            $('#loadBelumPaham').load(`/internalmemo/${id}/diskusi`);
        });

    });
</script>
@endpush
