@extends('layouts.app')
@section('titlepage', 'Buffer & Max Stok')

@section('content')
@section('navigasi')
    <span>Buffer & Max Stok</span>
@endsection
<div class="row">
    <div class="col-lg-6 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('bufferstok.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-12">
                            @hasanyrole($roles_show_cabang)
                                <x-select label="Cabang" name="kode_cabang" :data="$cabang" key="kode_cabang"
                                    textShow="nama_cabang" selected="{{ Request('kode_cabang') }}" />
                            @endhasanyrole
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive mb-2">
                                <table class="table table-striped table-bordered">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Kode Produk</th>
                                            <th>Nama Produk</th>
                                            <th>Buffer Stok</th>
                                            <th>Max. Stok</th>
                                        </tr>
                                    </thead>
                                    <tbody id="loadbufferstok">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <button class="btn btn-primary w-100" type="submit">
                                    <i class="ti ti-refresh"></i>
                                    Update
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@push('myscript')
<script>
    $(function() {
        function loadbufferstok(kode_cabang = "PST") {
            $("#loadbufferstok").load('/bufferstok/' + kode_cabang + '/getbufferstok');
        }

        loadbufferstok();

        $("#kode_cabang").change(function() {
            const kode_cabang = $(this).val();
            loadbufferstok(kode_cabang);
        });
    });
</script>
@endpush
