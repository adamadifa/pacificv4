@extends('layouts.app')
@section('titlepage', 'Detail Retur')

@section('content')
@section('navigasi')
    <span>Detail Retur</span>
@endsection


<div class="card p-0 m-0">
    <div class="card-content p-0">
        @if (Storage::disk('public')->exists('/penjualan/' . $retur->foto) && !empty($retur->foto))
            <img src="{{ getfotoPenjualan($retur->foto) }}" class="card-img-top img-fluid" alt="user image" style="height: 150px; object-fit: cover"
                id="foto">
        @else
            <img src="{{ asset('assets/img/elements/1.jpg') }}"class="card-img-top img-fluid" alt="user image" style="height: 150px; object-fit: cover"
                id="foto">
        @endif
        <div class="card-img-overlay" style="background-color: #00000097;">
            <h5 class="card-title text-white m-0">{{ $retur->no_retur }}</h5>
            <h5 class="card-title text-white m-0">{{ $retur->no_faktur }}</h5>
            <h5 class="card-title text-white m-0">{{ $retur->kode_pelanggan }} - {{ $retur->nama_pelanggan }}</h5>
            <p class="card-text text-white m-0">{{ DateToIndo($retur->tanggal) }}</p>
            @if ($retur->jenis_retur == 'PF')
                <span class="badge bg-danger">POTONG FAKTUR</span>
            @else
                <span class="badge bg-success">GANTI BARANG</span>
            @endif
        </div>
    </div>
</div>
@if ($retur->tanggal == date('Y-m-d'))
    <div class="row mb-1 mt-2">
        <div class="col">
            <form method="POST" class="deleteform" action="/retur/{{ Crypt::encrypt($retur->no_retur) }}/delete">
                @csrf
                @method('DELETE')
                <a href=" #" class="btn btn-danger w-100  delete-confirm">
                    <i class="ti ti-trash"></i> Hapus
                </a>
            </form>
        </div>
    </div>
@endif

<div class="card mt-2">
    <div class="card-header">
        <h4 class="card-title">
            <i class="ti ti-shopping-bag me-1 text-primary" style="font-size: 36px"></i> {{ formatAngka($retur->total_retur) }}
        </h4>
    </div>
    <div class="card-body">
        <table class="table">
            @php
                $subtotal = 0;
            @endphp
            @foreach ($detail as $d)
                @php
                    $jumlah = explode('|', convertToduspackpcsv2($d->isi_pcs_dus, $d->isi_pcs_pack, $d->jumlah));
                    $jumlah_dus = $jumlah[0];
                    $jumlah_pack = $jumlah[1];
                    $jumlah_pcs = $jumlah[2];
                    $subtotal += $d->subtotal;
                    $subtotal_dus = $jumlah_dus * $d->harga_dus;
                    $subtotal_pack = $jumlah_pack * $d->harga_pack;
                    $subtotal_pcs = $jumlah_pcs * $d->harga_pcs;

                    if ($d->status_promosi == '1') {
                        $color_row = 'bg-warning';
                    } else {
                        $color_row = '';
                    }
                @endphp
                <tr class="{{ $color_row }}">
                    <td colspan="2">{{ $d->kode_produk }} - {{ $d->nama_produk }}</td>
                </tr>
                <tr>
                    @if (!empty($jumlah_dus))
                        <td> {{ formatAngka($jumlah_dus) }} Dus x {{ formatAngka($d->harga_dus) }}</td>
                        <td class="text-end font-weight-bold"><b>{{ formatAngka($subtotal_dus) }}</b></td>
                    @endif
                </tr>
                <tr>
                    @if (!empty($jumlah_pack))
                        <td> {{ formatAngka($jumlah_pack) }} Pack x {{ formatAngka($d->harga_pack) }}</td>
                        <td class="text-end font-weight-bold"><b>{{ formatAngka($subtotal_pack) }}</b></td>
                    @endif
                </tr>
                <tr>
                    @if (!empty($jumlah_pcs))
                        <td> {{ formatAngka($jumlah_pcs) }} Pcs x {{ formatAngka($d->harga_pcs) }}</td>
                        <td class="text-end font-weight-bold"> <b>{{ formatAngka($subtotal_pcs) }}</b></td>
                    @endif
                </tr>
            @endforeach
            <tr>
                <td>TOTAL</td>
                <td class="text-end fw-bold">{{ formatAngka($subtotal) }}</td>
            </tr>
        </table>
    </div>
</div>
@endsection
