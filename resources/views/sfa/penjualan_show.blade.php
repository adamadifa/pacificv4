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
    <div class="card mt-2">
        <div class="card-header">
            <h4 class="card-title">
                @php
                    $total_netto =
                        $penjualan->total_bruto -
                        $penjualan->total_retur -
                        $penjualan->potongan -
                        $penjualan->potongan_istimewa -
                        $penjualan->penyesuaian +
                        $penjualan->ppn;
                @endphp
                <i class="ti ti-shopping-bag me-1 text-primary" style="font-size: 36px"></i> {{ formatAngka($total_netto) }}
            </h4>
        </div>
        <div class="card-body">
            <div class="nav-align-top">
                <ul class="nav nav-tabs nav-fill" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#detailpenjualan"
                            aria-controls="detailpenjualan" aria-selected="true">
                            Detail
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#pembayaran"
                            aria-controls="pembayaran" aria-selected="false" tabindex="-1">
                            Bayar
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#transfer"
                            aria-controls="transfer" aria-selected="false" tabindex="-1">
                            Transfer
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#giro" aria-controls="giro"
                            aria-selected="false" tabindex="-1">
                            Giro
                        </button>
                    </li>
                </ul>
                <div class="tab-content px-0 mx-1 pb-0">
                    <div class="tab-pane fade show active" id="detailpenjualan" role="tabpanel">
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
                                    @if (!empty($jumlah_pack))
                                        <td> {{ formatAngka($jumlah_pack) }} Pack x {{ formatAngka($d->harga_pack) }}</td>
                                        <td class="text-end font-weight-bold"><b>{{ formatAngka($subtotal_pack) }}</b></td>
                                    @endif
                                    @if (!empty($jumlah_pcs))
                                        <td> {{ formatAngka($jumlah_pcs) }} Pcs x {{ formatAngka($d->harga_pcs) }}</td>
                                        <td class="text-end font-weight-bold"> <b>{{ formatAngka($subtotal_pcs) }}</b></td>
                                    @endif
                                </tr>
                            @endforeach
                            <tr>
                                <td>SUBTOTAL</td>
                                <td class="text-end fw-bold">{{ formatAngka($subtotal) }}</td>
                            </tr>
                            <tr>
                                <td>POTONGAN</td>
                                <td class="text-end fw-bold text-danger">{{ formatAngka($penjualan->potongan) }}</td>
                            </tr>
                            <tr>
                                <td>POT. ISTIMEWA</td>
                                <td class="text-end fw-bold text-danger">{{ formatAngka($penjualan->potongan_istimewa) }}</td>
                            </tr>
                            <tr>
                                <td>PENYESUAIAN</td>
                                <td class="text-end fw-bold text-danger">{{ formatAngka($penjualan->penyesuaian) }}</td>
                            </tr>
                            @if (!empty($penjualan->ppn))
                                <tr>
                                    <td>PPN</td>
                                    <td class="text-end fw-bold">{{ formatAngka($penjualan->ppn) }}</td>
                                </tr>
                            @endif
                            <tr>
                                <td>GRAND TOTAL</td>
                                <td class="text-end fw-bold">{{ formatAngka($total_netto) }}</td>
                            </tr>
                            <tr>
                                <td>RETUR</td>
                                <td class="text-end fw-bold text-danger">{{ formatAngka($penjualan->total_retur) }}</td>
                            </tr>
                            <tr>
                                <td>JUMLAH BAYAR</td>
                                <td class="text-end fw-bold">{{ formatAngka($penjualan->total_bayar) }}</td>
                            </tr>
                            <tr>
                                <td>SISA BAYAR</td>
                                <td id="sisabayar">{{ formatAngka($total_netto - $penjualan->total_retur - $penjualan->total_bayar) }}</td>
                            </tr>
                            <tr>
                                <td colspan="2" class="{{ $penjualan->status == '1' ? 'bg-success' : 'bg-danger' }} text-white text-center">
                                    {{ $penjualan->status == '1' ? 'LUNAS' : 'BELUM LUNAS' }}
                                </td>

                            </tr>
                        </table>
                    </div>

                    <div class="tab-pane fade p-0" id="pembayaran" role="tabpanel">
                        <table class="table">
                            @php
                                $total_bayar = 0;
                            @endphp
                            @foreach ($historibayar as $d)
                                @php
                                    $total_bayar += $d->jumlah;
                                @endphp

                                <ul class="timeline mb-0 pb-1">
                                    <li class="timeline-item ps-4 border-left-dashed pb-1">
                                        <span class="timeline-indicator-advanced timeline-indicator-success">
                                            <i class="ti ti-circle-check"></i>
                                        </span>
                                        <div class="timeline-event px-0 pb-0 d-flex justify-content-between">
                                            <div>
                                                <div class="timeline-header">
                                                    <small class="text-success text-uppercase fw-medium">{{ $d->no_bukti }} -
                                                        {{ $jenis_bayar[$d->jenis_bayar] }}</small>
                                                </div>
                                                <h6 class="mb-1">{{ DateToIndo($d->tanggal) }}</h6>
                                                <h5 class="mb-1">{{ formatAngka($d->jumlah) }}</h5>
                                                <p class="text-muted mb-0">{{ $d->nama_salesman }}</p>
                                            </div>
                                            <div class="d-flex">
                                                @if (($d->jenis_bayar == 'TP' && $penjualan->jenis_bayar != 'TN') || ($d->jenis_bayar == 'TN' && $penjualan->jenis_bayar != 'TN'))
                                                    <div>
                                                        <a href="#" class="me-1 btnEditBayar" no_bukti="{{ Crypt::encrypt($d->no_bukti) }}">
                                                            <i class="ti ti-edit text-primary"></i>
                                                        </a>
                                                    </div>
                                                    <div>
                                                        <form method="POST" name="deleteform" class="deleteform"
                                                            style="margin-bottom:0px !important; padding:0 !important"
                                                            action="{{ route('pembayaranpenjualan.delete', Crypt::encrypt($d->no_bukti)) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <a href="#" class="delete-confirm ml-1">
                                                                <i class="ti ti-trash text-danger"></i>
                                                            </a>
                                                        </form>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            @endforeach
                        </table>
                    </div>
                    <div class="tab-pane fade" id="transfer" role="tabpanel">
                        <ul class="timeline mb-0 pb-1">
                            @foreach ($transfer as $d)
                                <li class="timeline-item ps-4 border-left-dashed pb-1">
                                    <span class="timeline-indicator-advanced timeline-indicator-success">
                                        <i class="ti ti-circle-check"></i>
                                    </span>
                                    <div class="timeline-event px-0 pb-0 d-flex justify-content-between">
                                        <div>
                                            <div class="timeline-header">
                                                <small class="text-success text-uppercase fw-medium">{{ $d->kode_transfer }} -
                                                    @php
                                                        $status_transfer =
                                                            $d->status == '0' ? 'Pending' : ($d->status == '1' ? 'Diterima' : 'Ditolak');
                                                        $bg_status = $d->status == '0' ? 'warning' : ($d->status == '1' ? 'success' : 'danger');
                                                    @endphp
                                                    <span class="badge bg-{{ $bg_status }}">{{ $status_transfer }}</span>
                                                </small>
                                            </div>
                                            <h6 class="mb-1">{{ DateToIndo($d->tanggal) }}</h6>

                                            <h5 class="mb-1">{{ formatAngka($d->jumlah) }}</h5>
                                            <p class="text-muted mb-0">{{ $d->bank_pengirim }}</p>
                                        </div>
                                        <div class="d-flex">
                                            @if ($d->status == '0')
                                                <div>
                                                    <a href="#" class="me-1 btnEdittransfer"
                                                        kode_transfer="{{ Crypt::encrypt($d->kode_transfer) }}">
                                                        <i class="ti ti-edit text-primary"></i>
                                                    </a>
                                                </div>
                                                <div>
                                                    <form method="POST" name="deleteform" class="deleteform"
                                                        style="margin-bottom:0px !important; padding:0 !important"
                                                        action="{{ route('pembayarantransfer.delete', [Crypt::encrypt($d->no_faktur), Crypt::encrypt($d->kode_transfer)]) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <a href="#" class="delete-confirm ml-1">
                                                            <i class="ti ti-trash text-danger"></i>
                                                        </a>
                                                    </form>
                                                </div>
                                            @else
                                                <span class="badge bg-success">Keuangan</span>
                                            @endif
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="giro" role="tabpanel">
                        <ul class="timeline mb-0 pb-1">
                            @foreach ($giro as $d)
                                <li class="timeline-item ps-4 border-left-dashed pb-1">
                                    <span class="timeline-indicator-advanced timeline-indicator-success">
                                        <i class="ti ti-circle-check"></i>
                                    </span>
                                    <div class="timeline-event px-0 pb-0 d-flex justify-content-between">
                                        <div>
                                            <div class="timeline-header d-flex">
                                                <small class="text-success text-uppercase fw-medium">{{ $d->no_giro }} -
                                                    @php
                                                        $status_giro = $d->status == '0' ? 'Pending' : ($d->status == '1' ? 'Diterima' : 'Ditolak');
                                                        $bg_giro = $d->status == '0' ? 'warning' : ($d->status == '1' ? 'success' : 'danger');
                                                    @endphp
                                                    <span class="badge bg-{{ $bg_giro }}">{{ $status_giro }}</span>
                                                </small>
                                            </div>
                                            <h6 class="mb-1">{{ DateToIndo($d->tanggal) }}</h6>

                                            <h5 class="mb-1">{{ formatAngka($d->jumlah) }}</h5>
                                            <p class="text-muted mb-0">{{ $d->bank_pengirim }}</p>
                                        </div>
                                        @if ($d->status == '0')
                                            <div class="d-flex">


                                                <div>
                                                    <a href="#" class="me-2 btnEditgiro" kode_giro="{{ Crypt::encrypt($d->kode_giro) }}">
                                                        <i class="ti ti-edit text-success"></i>
                                                    </a>
                                                </div>



                                                <div>
                                                    <form method="POST" name="deleteform" class="deleteform"
                                                        style="margin-bottom:0px !important; padding:0 !important"
                                                        action="{{ route('pembayarangiro.delete', [Crypt::encrypt($d->no_faktur), Crypt::encrypt($d->kode_giro)]) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <a href="#" class="delete-confirm ml-1">
                                                            <i class="ti ti-trash text-danger"></i>
                                                        </a>
                                                    </form>
                                                </div>


                                            </div>
                                        @else
                                            <span class="badge bg-success">Keuangan</span>
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>
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
