@extends('layouts.app')
@section('titlepage', 'COA Cabang')

@section('content')
@section('navigasi')
    <span>COA Cabang</span>
@endsection
<div class="row">
    <div class="col-lg-6 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-12">
                        <div class="form-group mb-0">
                            <label for="filter_cabang" class="form-label">Filter Cabang</label>
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
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 mb-2">
                        @can('coacabang.create')
                            <a href="#" class="btn btn-primary" id="btnCreate" style="display: none;"><i
                                    class="ti ti-plus me-1"></i>Tambah Data</a>
                        @endcan
                    </div>
                </div>
                <div id="loading" style="display: none;" class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <div id="data-container">
                    <div class="alert alert-info text-center">
                        Silakan pilih cabang terlebih dahulu untuk menampilkan data
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
                    '<div class="alert alert-info text-center">Silakan pilih cabang terlebih dahulu untuk menampilkan data</div>'
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
                        '<div class="alert alert-danger text-center">Terjadi kesalahan saat memuat data</div>'
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
