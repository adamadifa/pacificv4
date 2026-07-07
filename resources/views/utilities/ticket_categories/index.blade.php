@extends('layouts.app')
@section('titlepage', 'Master Kategori Tiket')

@section('content')
@section('navigasi')
    <span>Master Kategori Tiket</span>
@endsection
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="nav-align-top nav-tabs-shadow mb-4">
            @include('layouts.navigation_ticket')
            <div class="tab-content">
                <div class="tab-pane fade active show" id="navs-justified-home" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0"><i class="ti ti-category me-2 text-primary"></i>Daftar Master Kategori Tiket</h5>
                        <button class="btn btn-primary" id="btnCreateCategory">
                            <i class="ti ti-plus me-1"></i>Tambah Kategori Baru
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover table-bordered align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th style="width: 5%">No</th>
                                    <th style="width: 15%">Kode Kategori</th>
                                    <th>Nama Kategori</th>
                                    <th class="text-center">Manager Dept</th>
                                    <th class="text-center">SMM</th>
                                    <th class="text-center">RSM</th>
                                    <th class="text-center">GM</th>
                                    <th class="text-center">Wajib Lampiran</th>
                                    <th class="text-center">Template File</th>
                                    <th class="text-center" style="width: 10%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($categories as $index => $c)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td><span class="badge bg-label-primary font-monospace fs-6">{{ $c->kode_kategori }}</span></td>
                                        <td>
                                            <strong class="text-dark">{{ $c->nama_kategori }}</strong>
                                            @if ($c->keterangan)
                                                <br><small class="text-muted">{{ $c->keterangan }}</small>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            {!! $c->perlu_manager_dept ? '<i class="ti ti-check text-success fs-4"></i>' : '<i class="ti ti-minus text-muted"></i>' !!}
                                        </td>
                                        <td class="text-center">
                                            {!! $c->perlu_smm ? '<i class="ti ti-check text-success fs-4"></i>' : '<i class="ti ti-minus text-muted"></i>' !!}
                                        </td>
                                        <td class="text-center">
                                            {!! $c->perlu_rsm ? '<i class="ti ti-check text-success fs-4"></i>' : '<i class="ti ti-minus text-muted"></i>' !!}
                                        </td>
                                        <td class="text-center">
                                            {!! $c->perlu_gm ? '<i class="ti ti-check text-success fs-4"></i>' : '<i class="ti ti-minus text-muted"></i>' !!}
                                        </td>
                                        <td class="text-center">
                                            {!! $c->wajib_lampiran ? '<span class="badge bg-danger">Wajib</span>' : '<span class="badge bg-secondary">Opsional</span>' !!}
                                        </td>
                                        <td class="text-center">
                                            @if ($c->template_file)
                                                <a href="{{ asset($c->template_file) }}" target="_blank" class="btn btn-sm btn-outline-info">
                                                    <i class="ti ti-download me-1"></i>Template
                                                </a>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-1">
                                                <button class="btn btn-sm btn-warning btnEditCategory" data-id="{{ $c->id }}">
                                                    <i class="ti ti-edit"></i>
                                                </button>
                                                <form action="{{ route('ticketcategory.delete', $c->id) }}" method="POST" class="deleteform">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger delete-confirm">
                                                        <i class="ti ti-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center py-4 text-muted">Belum ada master kategori tiket. Klik "Tambah Kategori Baru" untuk menambahkan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Category --}}
<x-modal-form id="modalCategory" size="modal-lg" show="loadmodalform" title="Master Kategori Tiket" />

@endsection

@push('myscript')
<script>
    $(function() {
        $("#btnCreateCategory").click(function(e) {
            e.preventDefault();
            $("#modalCategory").modal("show");
            $("#modalCategory").find(".modal-title").text("Tambah Master Kategori Tiket");
            $("#modalCategory").find(".loadmodalform").load("{{ route('ticketcategory.create') }}");
        });

        $(".btnEditCategory").click(function(e) {
            e.preventDefault();
            let id = $(this).data("id");
            $("#modalCategory").modal("show");
            $("#modalCategory").find(".modal-title").text("Edit Master Kategori Tiket");
            $("#modalCategory").find(".loadmodalform").load("/ticketcategory/" + id + "/edit");
        });
    });
</script>
@endpush
