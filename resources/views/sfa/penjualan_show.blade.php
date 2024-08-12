@extends('layouts.app')
@section('titlepage', 'Detail Faktur')

@section('content')
@section('navigasi')
    <span>Detail Faktur</span>
@endsection
<style>
    #map {
        height: 180px;
    }
</style>

<div class="card p-0 m-0">
    <div class="card-content p-0">
        @if (Storage::disk('public')->exists('/penjualan/' . $penjualan->foto) && !empty($penjualan->foto))
            <img src="{{ getfotoPenjualan($penjualan->foto) }}" class="card-img-top img-fluid" alt="user image" style="height: 150px; object-fit: cover"
                id="foto">
        @else
            <img src="{{ asset('assets/img/elements/1.jpg') }}"class="card-img-top img-fluid" alt="user image" style="height: 150px; object-fit: cover"
                id="foto">
        @endif
        <div class="card-img-overlay" style="background-color: #00000097;">
            <h5 class="card-title text-white m-0">{{ $penjualan->no_faktur }}</h5>
            <h5 class="card-title text-white m-0">{{ $penjualan->kode_pelanggan }} - {{ $penjualan->nama_pelanggan }}</h5>
            <p class="card-text text-white m-0">{{ DateToIndo($penjualan->tanggal) }}</p>
            @if ($penjualan->jenis_transaksi == 'T')
                <span class="badge bg-success">TUNAI</span>
            @else
                <span class="badge bg-warning">KREDIT</span>
            @endif
        </div>

    </div>
</div>

@if ($penjualan->status_batal == '1')
    <div class="alert alert-warning mt-3">
        <h4 class="alert-heading">Faktur Batal</h4>
        <p>Faktur Ini Sudah Di Batalkan, dan Akan Segera di Proses Oleh OM</p>
    </div>
@else
    @if (date('Y-m-d', strtotime($penjualan->created_at)) != date('Y-m-d'))
        <div class="row mt-2">
            <div class="col">
                <a href="#" onclick="ajax_print('/sfa/penjualan/{{ Crypt::encrypt($penjualan->no_faktur) }}/cetak',this)"
                    class="btn btn-primary w-100">
                    <i class="ti ti-printer me-1"></i>
                    Cetak Faktur
                </a>

            </div>
        </div>
    @else
    @endif
@endif
@endsection
@push('myscript')
<script>
    function ajax_print(url, btn) {
        b = $(btn);
        b.attr('data-old', b.text());
        b.text('wait');
        $.get(url, function(data) {
            window.location.href = data; // main action
        }).fail(function() {
            alert("ajax error");
        }).always(function() {
            b.text(b.attr('data-old'));
        })
    }
</script>
@endpush
