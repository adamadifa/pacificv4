@extends('layouts.app')
<link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-profile.css') }}" />
<style>
    #map {
        height: 200px;
    }
</style>
@section('titlepage', 'Detail Penjualan')

@section('content')
@section('navigasi')
    <span class="text-muted">Penjualan/</span> Detail
@endsection
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="user-profile-header-banner" id="map">
                {{-- <img src="{{ asset('assets/img/pages/profile-bg.jpg') }}" alt="Banner image" class="rounded-top"> --}}
            </div>
            <div class="user-profile-header d-flex flex-column flex-sm-row text-sm-start text-center mb-4" style="z-index: 999">

                <div class="flex-shrink-0 mt-n2 mx-sm-0 mx-auto">
                    @if (Storage::disk('public')->exists('/pelanggan/' . $penjualan->foto) && !empty($penjualan->foto))
                        <img src="{{ getfotoPelanggan($penjualan->foto) }}" alt="user image" class="d-block  ms-0 ms-sm-4 rounded" height="150"
                            width="200" style="object-fit: cover">
                    @else
                        <img src="{{ asset('assets/img/avatars/No_Image_Available.jpg') }}" alt="user image"
                            class="d-block h-auto ms-0 ms-sm-4 rounded user-profile-img" width="150">
                    @endif

                </div>
                <div class="flex-grow-1 mt-3 mt-sm-5">
                    <div
                        class="d-flex align-items-md-end align-items-sm-start align-items-center justify-content-md-between justify-content-start mx-4 flex-md-row flex-column gap-4">
                        <div class="user-profile-info">
                            <h4>
                                {{ textCamelCase($penjualan->nama_pelanggan) }}
                            </h4>
                            <ul class="list-inline mb-0 d-flex align-items-center flex-wrap justify-content-sm-start justify-content-center gap-2">
                                <li class="list-inline-item d-flex gap-1">
                                    <i class="ti ti-color-swatch"></i> {{ textCamelCase($penjualan->nama_cabang) }}
                                </li>
                                <li class="list-inline-item d-flex gap-1"><i class="ti ti-user"></i>
                                    <span class="badge bg-info"> {{ textCamelCase($penjualan->nama_salesman) }}</span>
                                </li>
                                <li class="list-inline-item d-flex gap-1">
                                    <i class="ti ti-map-pin"></i> {{ textCamelCase($penjualan->nama_wilayah) }}
                                </li>
                            </ul>
                        </div>
                        <div>
                            <a href="{{ route('penjualan.create') }}" class="btn btn-primary"><i class="ti ti-plus me-1"></i>Input Penjualan Baru</a>
                            @can('penjualan.updatelockprint')
                                @if ($penjualan->lock_print == 0)
                                    <a href="{{ route('penjualan.updatelockprint', Crypt::encrypt($penjualan->no_faktur)) }}" class="btn btn-danger"><i
                                            class="ti ti-printer-off me-1"></i>Buka
                                        Kunci Print</a>
                                @else
                                    <a href="{{ route('penjualan.updatelockprint', Crypt::encrypt($penjualan->no_faktur)) }}" class="btn btn-success"><i
                                            class="ti ti-printer me-1"></i>Kunci Print</a>
                                @endif
                            @endcan


                            @if ($penjualan->status_aktif_pelanggan === '1')
                                <a href="javascript:void(0)" class="btn btn-success waves-effect waves-light">
                                    <i class="ti ti-check me-1"></i> Aktif
                                </a>
                            @else
                                <a href="javascript:void(0)" class="btn btn-danger waves-effect waves-light">
                                    <i class="ti ti-check me-1"></i> Nonaktif
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- User Profile Content -->
<div class="row">
    <div class="col-xl-3 col-lg-5 col-md-5">
        <!-- About User -->
        <div class="card mb-4">
            <div class="card-body">
                <small class="card-text text-uppercase">Data Pelanggan</small>
                <ul class="list-unstyled mb-4 mt-3">
                    <li class="d-flex align-items-center mb-3">
                        <i class="ti ti-barcode text-heading"></i><span class="fw-medium mx-2 text-heading">Kode
                            Pelanggan:</span> <span>{{ $penjualan->kode_pelanggan }}</span>
                    </li>
                    <li class="d-flex align-items-center">
                        <i class="ti ti-user text-heading"></i><span class="fw-medium mx-2 text-heading">
                            Nama Pelanggan:</span>
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <span>{{ textUpperCase($penjualan->nama_pelanggan) }}</span>
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <i class="ti ti-credit-card text-heading"></i><span class="fw-medium mx-2 text-heading">NIK:</span>
                        <span>{{ $penjualan->nik }}</span>
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <i class="ti ti-credit-card text-heading"></i><span class="fw-medium mx-2 text-heading">No.
                            KK:</span>
                        <span>{{ $penjualan->no_kk }}</span>
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <i class="ti ti-calendar text-heading"></i><span class="fw-medium mx-2 text-heading">Tanggal
                            Lahir:</span>
                        <span>{{ !empty($penjualan->tanggal_lahir) ? DateToIndo($penjualan->tanggal_lahir) : '' }}</span>
                    </li>
                    <li class="d-flex align-items-center mb-1">
                        <i class="ti ti-map-pin text-heading"></i><span class="fw-medium mx-2 text-heading">Alamat
                            Pelanggan:</span>
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <span>{{ textCamelCase($penjualan->alamat_pelanggan) }}</span>
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <i class="ti ti-map-pin text-heading"></i><span class="fw-medium mx-2 text-heading">Alamat
                            Toko:</span>
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <span>{{ textCamelCase($penjualan->alamat_toko) }}</span>
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <i class="ti ti-map-pin text-heading"></i><span class="fw-medium mx-2 text-heading">Wilayah/Rute
                            :</span> <span>{{ textCamelCase($penjualan->nama_wilayah) }}</span>
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <i class="ti ti-phone text-heading"></i><span class="fw-medium mx-2 text-heading">No. HP
                            :</span> <span>{{ textCamelCase($penjualan->no_hp_pelanggan) }}</span>
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <i class="ti ti-file text-heading"></i><span class="fw-medium mx-2 text-heading">Hari
                            :</span> <span>{{ textCamelCase($penjualan->hari) }}</span>
                    </li>

                    <li class="d-flex align-items-center mb-3">
                        <i class="ti ti-file text-heading"></i><span class="fw-medium mx-2 text-heading">LJT
                            :</span> <span>{{ $penjualan->ljt }} Hari</span>
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <i class="ti ti-file text-heading"></i><span class="fw-medium mx-2 text-heading">Kepemilikan
                            :</span>
                        <span>{{ !empty($penjualan->kepemilikan) ? $kepemilikan[$penjualan->kepemilikan] : '' }}
                        </span>
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <i class="ti ti-file text-heading"></i><span class="fw-medium mx-2 text-heading">Lama Berjualan
                            :</span>
                        <span>{{ !empty($penjualan->lama_berjualan) ? $lama_berjualan[$penjualan->lama_berjualan] : '' }}
                        </span>
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <i class="ti ti-file text-heading"></i><span class="fw-medium mx-2 text-heading">Status Outlet
                            :</span>
                        <span>{{ !empty($penjualan->status_outlet) ? $status_outlet[$penjualan->status_outlet] : '' }}
                        </span>
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <i class="ti ti-file text-heading"></i><span class="fw-medium mx-2 text-heading">Type Outlet
                            :</span>
                        <span>{{ !empty($penjualan->type_outlet) ? $type_outlet[$penjualan->type_outlet] : '' }}
                        </span>
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <i class="ti ti-file text-heading"></i><span class="fw-medium mx-2 text-heading">Cara Pembayaran
                            :</span>
                        <span>{{ !empty($penjualan->cara_pembayaran) ? $cara_pembayaran[$penjualan->cara_pembayaran] : '' }}
                        </span>
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <i class="ti ti-file text-heading"></i><span class="fw-medium mx-2 text-heading">Lama Langganan
                            :</span>
                        <span>{{ !empty($penjualan->lama_langganan) ? $lama_langganan[$penjualan->lama_langganan] : '' }}
                        </span>
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <i class="ti ti-file text-heading"></i><span class="fw-medium mx-2 text-heading">Jaminan
                            :</span> <span>{{ $penjualan->jaminan == 1 ? 'Ada' : 'Tidak Ada' }} </span>
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <i class="ti ti-file text-heading"></i><span class="fw-medium mx-2 text-heading">Omset Toko
                            :</span> <span>{{ formatRupiah($penjualan->omset_toko) }} </span>
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <i class="ti ti-file text-heading"></i><span class="fw-medium mx-2 text-heading">Limit
                            Pelanggan
                            :</span> <span>{{ formatRupiah($penjualan->limit_pelanggan) }} </span>
                    </li>
                </ul>

            </div>
        </div>
        @if (Storage::disk('public')->exists('/signature/' . $penjualan->signature) && !empty($penjualan->signature))
            <div class="card">
                <div class="card-body">
                    <img src="{{ getSignature($penjualan->signature) }}" alt="user image" class="m-auto" height="150" width="300">
                </div>
            </div>
        @endif
        <!--/ About User -->

    </div>
    <div class="col-xl-9 col-lg-7 col-md-7">
        <!-- Activity Timeline -->
        <div class="card card-action mb-4">
            <div class="card-header align-items-center">
                <h5 class="card-action-title mb-0">Data Penjualan</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6 col-md-12 col-sm-12">
                        <table class="table">
                            <tr>
                                <th>No. Faktur</th>
                                <td class="font-weight-bold">{{ $penjualan->no_faktur }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal</th>
                                <td>{{ DateToIndo($penjualan->tanggal) }}</td>
                            </tr>
                            <tr>
                                <th>Jenis Transaksi</th>
                                <td>
                                    @if ($penjualan->jenis_transaksi == 'T')
                                        <span class="badge bg-success">TUNAI</span>
                                    @else
                                        <span class="badge bg-warning">KREDIT</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Jenis Bayar</th>
                                <td>{{ $jenis_bayar[$penjualan->jenis_bayar] }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-lg-6 col-sm-12 col-md-12 d-flex justify-content-between">
                        <div>
                            <i class="ti ti-shopping-bag text-primary" style="font-size: 8rem"></i>
                        </div>
                        <div class="m-auto">
                            @php
                                $total_netto =
                                    $penjualan->total_bruto -
                                    $penjualan->total_retur -
                                    $penjualan->potongan -
                                    $penjualan->potongan_istimewa -
                                    $penjualan->penyesuaian +
                                    $penjualan->ppn;
                            @endphp
                            <h1 style="font-size: 4rem">{{ formatAngka($total_netto) }}</h1>
                        </div>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Kode</th>
                                        <th>Nama Produk</th>
                                        <th>Dus</th>
                                        <th>Harga</th>
                                        <th>Pack</th>
                                        <th>Harga</th>
                                        <th>Pcs</th>
                                        <th>Harga</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
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

                                            if ($d->status_promosi == '1') {
                                                $color_row = 'bg-warning';
                                            } else {
                                                $color_row = '';
                                            }
                                        @endphp
                                        <tr class="{{ $color_row }}">
                                            <td>{{ $d->kode_produk }}</td>
                                            <td>{{ $d->nama_produk }}</td>
                                            <td class="text-end">{{ formatAngka($jumlah_dus) }}</td>
                                            <td class="text-end">{{ formatAngka($d->harga_dus) }}</td>
                                            <td class="text-end">{{ formatAngka($jumlah_pack) }}</td>
                                            <td class="text-end">{{ formatAngka($d->harga_pack) }}</td>
                                            <td class="text-end">{{ formatAngka($jumlah_pcs) }}</td>
                                            <td class="text-end">{{ formatAngka($d->harga_pcs) }}</td>
                                            <td class="text-end">{{ formatAngka($d->subtotal) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-dark">
                                    <tr>
                                        <td colspan="8">SUBTOTAL</td>
                                        <td class="text-end">{{ formatAngka($subtotal) }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="8">POTONGAN</td>
                                        <td class="text-end">{{ formatAngka($penjualan->potongan) }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="8">POTONGAN ISTIMEWA</td>
                                        <td class="text-end">{{ formatAngka($penjualan->potongan_istimewa) }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="8">PENYESUAIAN</td>
                                        <td class="text-end">{{ formatAngka($penjualan->penyesuaian) }}</td>
                                    </tr>
                                    <tr>
                                        @php
                                            $total_dpp =
                                                $penjualan->total_bruto -
                                                $penjualan->potongan -
                                                $penjualan->potongan_istimewa -
                                                $penjualan->penyesuaian;

                                        @endphp
                                        <td colspan="8">TOTAL</td>
                                        <td class="text-end">{{ formatAngka($total_dpp) }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="8">PPN</td>
                                        <td class="text-end">{{ formatAngka($penjualan->ppn) }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="8">RETUR</td>
                                        <td class="text-end">{{ formatAngka($penjualan->total_retur) }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="8">GRAND TOTAL</td>
                                        <td class="text-end">{{ formatAngka($total_netto) }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="8">JUMLAH BAYAR</td>
                                        <td class="text-end">{{ formatAngka($penjualan->total_bayar) }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="8">SISA BAYAR</td>
                                        <td class="text-end" id="sisabayar">{{ formatAngka($total_netto - $penjualan->total_bayar) }}</td>
                                    </tr>
                                    <tr>
                                        @php
                                            if ($penjualan->status_batal == 0) {
                                                if ($penjualan->total_bayar == $total_netto) {
                                                    $color = 'success';
                                                    $ket = 'LUNAS';
                                                } elseif ($penjualan->total_bayar > $total_netto) {
                                                    $color = 'info';
                                                    $ket = 'LEBIH BAYAR';
                                                } else {
                                                    $color = 'danger';
                                                    $ket = 'BELUM LUNAS';
                                                }
                                            } else {
                                                $color = 'danger';
                                                $ket = 'FAKTUR BATAL';
                                            }

                                        @endphp
                                        <td colspan="9" class="bg-{{ $color }} text-center">
                                            {{ $ket }}
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-dark">
                                    <tr>
                                        <th colspan="11">Data Retur</th>
                                    </tr>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Kode</th>
                                        <th>Nama Produk</th>
                                        <th>Jenis</th>
                                        <th>Dus</th>
                                        <th>Harga</th>
                                        <th>Pack</th>
                                        <th>Harga</th>
                                        <th>Pcs</th>
                                        <th>Harga</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $subtotal_retur = 0;
                                        $subtotal_retur_gb = 0;
                                    @endphp
                                    @foreach ($retur as $d)
                                        @php
                                            $jumlah = explode('|', convertToduspackpcsv2($d->isi_pcs_dus, $d->isi_pcs_pack, $d->jumlah));
                                            $jumlah_dus = $jumlah[0];
                                            $jumlah_pack = $jumlah[1];
                                            $jumlah_pcs = $jumlah[2];
                                            $subtotal_retur += $d->subtotal;
                                            if ($d->jenis_retur == 'GB') {
                                                $subtotal_retur_gb += $d->subtotal;
                                            }
                                        @endphp
                                        <tr>
                                            <td>{{ date('d-m-y', strtotime($d->tanggal)) }}</td>
                                            <td>{{ $d->kode_produk }}</td>
                                            <td>{{ $d->nama_produk }}</td>
                                            <td>{{ $d->jenis_retur }}</td>
                                            <td class="text-end">{{ formatAngka($jumlah_dus) }}</td>
                                            <td class="text-end">{{ formatAngka($d->harga_dus) }}</td>
                                            <td class="text-end">{{ formatAngka($jumlah_pack) }}</td>
                                            <td class="text-end">{{ formatAngka($d->harga_pack) }}</td>
                                            <td class="text-end">{{ formatAngka($jumlah_pcs) }}</td>
                                            <td class="text-end">{{ formatAngka($d->harga_pcs) }}</td>
                                            <td class="text-end">{{ formatAngka($d->subtotal) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-dark">
                                    <tr>
                                        <td colspan="10">TOTAL</td>
                                        <td class="text-end">{{ formatAngka($subtotal_retur) }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="10">GANTI BARANG</td>
                                        <td class="text-end">{{ formatAngka($subtotal_retur_gb) }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="10">POTONG FAKTUR</td>
                                        <td class="text-end">{{ formatAngka($subtotal_retur - $subtotal_retur_gb) }}
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row mb-2 mt-3">
                    <div class="col">
                        @if ($penjualan->status_batal == 0)
                            @can('pembayaranpenjualan.create')
                                <a href="#" class="btn btn-primary" id="btnCreateBayar"><i class="ti ti-plus me-1"></i>Input Pembayaran</a>
                            @endcan
                        @endif

                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-dark">
                                    <tr>
                                        <th colspan="7">Histori Bayar</th>
                                    </tr>
                                    <tr>
                                        <th>No. Bukti</th>
                                        <th>Tanggal</th>
                                        <th>Jenis Bayar</th>
                                        <th>Jumlah</th>
                                        <th>Keterangan</th>
                                        <th>Salesman</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $total_bayar = 0;
                                    @endphp
                                    @foreach ($historibayar as $d)
                                        @php
                                            $total_bayar += $d->jumlah;
                                        @endphp
                                        <tr>
                                            <td>{{ $d->no_bukti }}</td>
                                            <td>{{ date('d-m-y', strtotime($d->tanggal)) }}</td>
                                            <td>{{ $jenis_bayar[$d->jenis_bayar] }}</td>
                                            <td class="text-end">{{ formatAngka($d->jumlah) }}</td>
                                            <td>

                                                @if ($d->voucher == '1')
                                                    <span class="badge bg-success">{{ $d->nama_voucher }}
                                                        @if ($d->voucher_reward == '1')
                                                            Voucher Reward
                                                        @endif
                                                    </span>
                                                @elseif ($d->giro_to_cash == '1')
                                                    <span class="badge bg-success">Ganti Giro Ke Cash
                                                        {{ $d->no_giro }}</span>
                                                @endif

                                            </td>
                                            <td>{{ $d->nama_salesman }}</td>
                                            <td>
                                                <div class="d-flex">
                                                    @if (in_array($d->jenis_bayar, ['TN', 'TP']))
                                                        @if ($d->voucher == 0)
                                                            @can('pembayaranpenjualan.edit')
                                                                <div>
                                                                    <a href="#" class="me-2 btnEditBayar"
                                                                        no_bukti="{{ Crypt::encrypt($d->no_bukti) }}">
                                                                        <i class="ti ti-edit text-success"></i>
                                                                    </a>
                                                                </div>
                                                            @endcan
                                                        @endif


                                                        @can('pembayaranpenjualan.delete')
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
                                                        @endcan
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-dark">
                                    <tr>
                                        <td colspan="3">TOTAL</td>
                                        <td class="text-end">{{ formatAngka($total_bayar) }}</td>
                                        <td colspan="3"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                    </div>
                </div>
                <div class="row mb-2 mt-3">
                    <div class="col">
                        @if ($penjualan->status_batal == 0)
                            @can('pembayarangiro.create')
                                <a href="#" class="btn btn-primary" id="btnCreategiro"><i class="ti ti-plus me-1"></i>Input Giro</a>
                            @endcan
                        @endif

                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-dark">
                                    <tr>
                                        <th colspan="8">Histori Pembayaran Giro</th>
                                    </tr>
                                    <tr>
                                        <th>No. Giro</th>
                                        <th>Tanggal</th>
                                        <th>Bank</th>
                                        <th>Jumlah</th>
                                        <th>Jatuh Tempo</th>
                                        <th class="text-center">Status</th>
                                        <th>Salesman</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($giro as $d)
                                        <tr>
                                            <td>{{ $d->no_giro }} </td>
                                            <td>{{ date('d-m-y', strtotime($d->tanggal)) }}</td>
                                            <td>{{ $d->bank_pengirim }}</td>
                                            <td class="text-end">{{ formatAngka($d->jumlah) }}</td>
                                            <td>{{ date('d-m-y', strtotime($d->jatuh_tempo)) }}</td>
                                            <td class="text-center">
                                                @if ($d->status == '0')
                                                    <i class="ti ti-hourglass-low text-warning"></i>
                                                @elseif ($d->status == '1')
                                                    <span class="badge bg-success">Diterima
                                                        {{ date('d-m-y', strtotime($d->tanggal_diterima)) }}</span>
                                                @elseif ($d->status == '2')
                                                    <span class="badge bg-danger">Ditolak
                                                        {{ date('d-m-y', strtotime($d->tanggal_ditolak)) }}</span>
                                                @endif
                                            </td>
                                            <td>{{ $d->nama_salesman }}</td>
                                            <td>
                                                @if ($d->status == '0')
                                                    <div class="d-flex">

                                                        @can('pembayarangiro.edit')
                                                            <div>
                                                                <a href="#" class="me-2 btnEditgiro"
                                                                    kode_giro="{{ Crypt::encrypt($d->kode_giro) }}">
                                                                    <i class="ti ti-edit text-success"></i>
                                                                </a>
                                                            </div>
                                                        @endcan

                                                        @can('pembayarangiro.delete')
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
                                                        @endcan

                                                    </div>
                                                @else
                                                    <span class="badge bg-success">Keuangan</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
                <div class="row mb-2 mt-3">
                    <div class="col">
                        @if ($penjualan->status_batal == 0)
                            @can('pembayarantransfer.create')
                                <a href="#" class="btn btn-primary" id="btnCreatetransfer"><i class="ti ti-plus me-1"></i>Input Transfer</a>
                            @endcan
                        @endif
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-dark">
                                    <tr>
                                        <th colspan="8">Histori Pembayaran Transfer</th>
                                    </tr>
                                    <tr>
                                        <th>Kode Transfer</th>
                                        <th>Tanggal</th>
                                        <th>Bank</th>
                                        <th>Jumlah</th>
                                        <th>Jatuh Tempo</th>
                                        <th class="text-center">Status</th>
                                        <th>Salesman</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($transfer as $d)
                                        <tr>
                                            <td>{{ $d->kode_transfer }} </td>
                                            <td>{{ date('d-m-y', strtotime($d->tanggal)) }}</td>
                                            <td>{{ $d->bank_pengirim }}</td>
                                            <td class="text-end">{{ formatAngka($d->jumlah) }}</td>
                                            <td>{{ date('d-m-y', strtotime($d->jatuh_tempo)) }}</td>
                                            <td class="text-center">
                                                @if ($d->status == '0')
                                                    <i class="ti ti-hourglass-low text-warning"></i>
                                                @elseif ($d->status == '1')
                                                    <span class="badge bg-success">Diterima
                                                        {{ date('d-m-y', strtotime($d->tanggal_diterima)) }}</span>
                                                @elseif ($d->status == '2')
                                                    <span class="badge bg-danger">Ditolak
                                                        {{ date('d-m-y', strtotime($d->tanggal_ditolak)) }}</span>
                                                @endif
                                            </td>
                                            <td>{{ $d->nama_salesman }}</td>
                                            <td>
                                                <div class="d-flex">


                                                    @if ($d->status == '0')
                                                        @can('pembayarantransfer.edit')
                                                            <div>
                                                                <a href="#" class="me-2 btnEdittransfer"
                                                                    kode_transfer="{{ Crypt::encrypt($d->kode_transfer) }}">
                                                                    <i class="ti ti-edit text-success"></i>
                                                                </a>
                                                            </div>
                                                        @endcan

                                                        @can('pembayarantransfer.delete')
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
                                                        @endcan
                                                    @else
                                                        <span class="badge bg-success">Keuangan</span>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!--/ Activity Timeline -->
    </div>
</div>

<!--/ User Profile Content -->
<x-modal-form id="modal" size="" show="loadmodal" title="" />
@endsection

@push('myscript')
<script>
    var latitude = "{{ !empty($penjualan->latitude) ? $penjualan->latitude : '-7.3665114' }}";
    var longitude = "{{ !empty($penjualan->longitude) ? $penjualan->longitude : '108.2148793' }}";
    var latitudecheckin = "{{ $checkin != null ? $checkin->latitude : '-7.3665114' }}";
    var longitudecheckin = "{{ $checkin != null ? $checkin->longitude : '108.2148793' }}";
    //    var markericon = "{{ $penjualan->marker }}";

    var map = L.map('map').setView([latitude, longitude], 18);
    L.tileLayer('http://{s}.google.com/vt?lyrs=m&x={x}&y={y}&z={z}', {
        maxZoom: 20,
        subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
    }).addTo(map);
    var marker = L.marker([latitude, longitude]).addTo(map);
    var circle = L.circle([latitude, longitude], {
        color: 'red',
        fillColor: '#f03',
        fillOpacity: 0.5,
        radius: 10
    }).addTo(map);
    //   var salesmanicon = L.icon({
    //     iconUrl: '/app-assets/marker/' + markericon,
    //     iconSize: [75, 75], // size of the icon
    //     shadowSize: [50, 64], // size of the shadow
    //     iconAnchor: [22, 94], // point of the icon which will correspond to marker's location
    //     shadowAnchor: [4, 62], // the same for the shadow
    //     popupAnchor: [-3, -76] // point from which the popup should open relative to the iconAnchor
    //   });
    //   var marker = L.marker([latitudecheckin, longitudecheckin], {
    //     icon: salesmanicon
    //   }).addTo(map);

    var polygon = L.polygon([
        [latitude, longitude],
        [latitudecheckin, longitudecheckin]
    ]).addTo(map);
</script>

<script>
    $(document).ready(function() {
        function loading() {
            $("#loadmodal").html(`<div class="sk-wave sk-primary" style="margin:auto">
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            </div>`);
        };
        $("#btnCreateBayar").click(function(e) {
            e.preventDefault();
            loading();
            const no_faktur = "{{ Crypt::encrypt($penjualan->no_faktur) }}";
            $("#modal").modal("show");
            $(".modal-title").text("Input Pembayaran");
            $("#loadmodal").load(`/pembayaranpenjualan/${no_faktur}/create`);
        });


        $(".btnEditBayar").click(function(e) {
            e.preventDefault();
            loading();
            const no_bukti = $(this).attr('no_bukti');
            $("#modal").modal("show");
            $(".modal-title").text("Edit Pembayaran");
            $("#loadmodal").load(`/pembayaranpenjualan/${no_bukti}/edit`);
        });


        $("#btnCreategiro").click(function(e) {
            e.preventDefault();
            loading();
            const no_faktur = "{{ Crypt::encrypt($penjualan->no_faktur) }}";
            $("#modal").modal("show");
            $(".modal-title").text("Input Giro");
            $("#loadmodal").load(`/pembayarangiro/${no_faktur}/create`);
        });

        $(".btnEditgiro").click(function(e) {
            e.preventDefault();
            loading();
            const no_faktur = "{{ Crypt::encrypt($penjualan->no_faktur) }}";
            const kode_giro = $(this).attr("kode_giro");
            $("#modal").modal("show");
            $(".modal-title").text("Edit Giro");
            $("#loadmodal").load(`/pembayarangiro/${no_faktur}/${kode_giro}/edit`);
        });


        $("#btnCreatetransfer").click(function(e) {
            e.preventDefault();
            loading();
            const no_faktur = "{{ Crypt::encrypt($penjualan->no_faktur) }}";
            $("#modal").modal("show");
            $(".modal-title").text("Input Transfer");
            $("#loadmodal").load(`/pembayarantransfer/${no_faktur}/create`);
        });

        $(".btnEdittransfer").click(function(e) {
            e.preventDefault();
            loading();
            const no_faktur = "{{ Crypt::encrypt($penjualan->no_faktur) }}";
            const kode_transfer = $(this).attr("kode_transfer");
            $("#modal").modal("show");
            $(".modal-title").text("Edit Transfer");
            $("#loadmodal").load(`/pembayarantransfer/${no_faktur}/${kode_transfer}/edit`);
        });
    });
</script>
@endpush
