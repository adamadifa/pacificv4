@extends('layouts.app')
@section('titlepage', 'COA Cabang')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">COA Cabang</h4>
            <small class="text-muted">Pengaturan Chart of Account untuk masing-masing cabang.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-folder me-1"></i>Accounting</a>
                </li>
                <li class="breadcrumb-item active"><i class="ti ti-settings me-1"></i>COA Cabang</li>
            </ol>
        </nav>
    </div>
@endsection

<div class="row mb-4">
    <div class="col-lg-8 col-md-12 col-12">
        <div class="form-group">
            <label for="filter_cabang" class="form-label fw-bold">Pilih Cabang</label>
            <select name="filter_cabang" id="filter_cabang" class="form-select select2Cabang">
                <option value="">Pilih Cabang</option>
                @foreach ($cabang as $d)
                    <option value="{{ $d->kode_cabang }}" @selected(request('kode_cabang') == $d->kode_cabang)>
                        {{ $d->kode_cabang }} - {{ $d->nama_cabang }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8 col-md-12 col-12">
        <div class="card shadow-none border">
            <div class="card-header border-bottom py-3 d-flex justify-content-between align-items-center"
                style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                <h6 class="m-0 fw-bold text-white"><i class="ti ti-list me-2"></i>Daftar COA Cabang</h6>
                <div class="d-flex align-items-center">
                    @can('coacabang.create')
                        <a href="#" class="btn btn-primary btn-sm me-2" id="btnCreate" style="display: none;">
                            <i class="ti ti-plus me-1"></i>Tambah
                        </a>
                    @endcan
                </div>
            </div>
            <div class="card-body p-0">
                <div id="loading" style="display: none;" class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <div class="mt-2 text-muted">Memuat data...</div>
                </div>

                <div id="data-container">
                    <div class="p-3">
                        <div class="alert alert-info text-center d-flex align-items-center justify-content-center mb-0">
                            <i class="ti ti-info-circle me-2 fs-4"></i>
                            Silakan pilih cabang terlebih dahulu untuk menampilkan data
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<x-modal-form id="modal" size="" show="loadmodal" title="" />
@push('myscript')
<script>
    $(function() {
        const select2Cabang = $('.select2Cabang');
        if (select2Cabang.length) {
            select2Cabang.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Pilih Cabang',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

        // Load initial data if kode_cabang exists
        @if (request('kode_cabang'))
            loadData('{{ request('kode_cabang') }}');
        @endif

        // Filter cabang on change with AJAX
        $("#filter_cabang").on('change', function() {
            const kode_cabang = $(this).val();
            if (kode_cabang) {
                loadData(kode_cabang);
                // Update URL without reload
                const url = new URL(window.location);
                url.searchParams.set('kode_cabang', kode_cabang);
                window.history.pushState({}, '', url);
            } else {
                $("#data-container").html(
                    '<div class="p-3"><div class="alert alert-info text-center mb-0">Silakan pilih cabang terlebih dahulu untuk menampilkan data</div></div>'
                );
                $("#btnCreate").hide();
                // Remove kode_cabang from URL
                const url = new URL(window.location);
                url.searchParams.delete('kode_cabang');
                window.history.pushState({}, '', url);
            }
        });

        function loadData(kode_cabang) {
            $("#loading").show();
            $("#data-container").hide();
            $("#btnCreate").hide();

            $.ajax({
                url: "{{ route('coacabang.getdata') }}",
                type: 'GET',
                data: {
                    kode_cabang: kode_cabang
                },
                success: function(response) {
                    $("#data-container").html(response);
                    $("#loading").hide();
                    $("#data-container").show();
                    if (kode_cabang) {
                        $("#btnCreate").show();
                    }
                },
                error: function(xhr) {
                    $("#loading").hide();
                    $("#data-container").html(
                        '<div class="p-3"><div class="alert alert-danger text-center mb-0">Terjadi kesalahan saat memuat data</div></div>'
                    );
                    $("#data-container").show();
                }
            });
        }

        $("#btnCreate").click(function(e) {
            e.preventDefault();
            const kode_cabang = $("#filter_cabang").val();
            $("#modal").modal("show");
            $("#modal").find(".modal-title").text("Tambah COA Cabang");
            $("#loadmodal").load(`/coacabang/create?kode_cabang=${kode_cabang}`);
        });

        // Event delegation untuk delete confirmation (untuk elemen yang dimuat via AJAX)
        $(document).on('click', '#data-container .delete-confirm', function(event) {
            var form = $(this).closest("form");
            event.preventDefault();
            Swal.fire({
                title: `Apakah Anda Yakin Ingin Menghapus Data Ini ?`,
                text: "Jika dihapus maka data akan hilang permanent.",
                icon: "warning",
                buttons: true,
                dangerMode: true,
                showCancelButton: true,
                confirmButtonColor: "#554bbb",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, Hapus Saja!"
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endpush
