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
          @if (Storage::disk('public')->exists('/pelanggan/' . $penjualan->foto))
            <img src="{{ getfotoPelanggan($penjualan->foto) }}" alt="user image"
              class="d-block  ms-0 ms-sm-4 rounded user-profile-img" height="150">
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
              <ul
                class="list-inline mb-0 d-flex align-items-center flex-wrap justify-content-sm-start justify-content-center gap-2">
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
<!-- User Profile Content -->
<div class="row">
  <div class="col-xl-4 col-lg-5 col-md-5">
    <!-- About User -->
    <div class="card mb-4">
      <div class="card-body">
        <small class="card-text text-uppercase">Data Pelanggan</small>
        <ul class="list-unstyled mb-4 mt-3">
          <li class="d-flex align-items-center mb-3">
            <i class="ti ti-barcode text-heading"></i><span class="fw-medium mx-2 text-heading">Kode
              Pelanggan:</span> <span>{{ $penjualan->kode_pelanggan }}</span>
          </li>
          <li class="d-flex align-items-center mb-3">
            <i class="ti ti-user text-heading"></i><span class="fw-medium mx-2 text-heading">
              Nama Pelanggan:</span> <span>{{ textCamelCase($penjualan->nama_pelanggan) }}</span>
          </li>
          <li class="d-flex align-items-center mb-3">
            <i class="ti ti-credit-card text-heading"></i><span
              class="fw-medium mx-2 text-heading">NIK:</span>
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
          <li class="d-flex align-items-center mb-3">
            <i class="ti ti-map-pin text-heading"></i><span class="fw-medium mx-2 text-heading">Alamat
              Pelanggan:</span> <span>{{ textCamelCase($penjualan->alamat_pelanggan) }}</span>
          </li>
          <li class="d-flex align-items-center mb-3">
            <i class="ti ti-map-pin text-heading"></i><span class="fw-medium mx-2 text-heading">Alamat
              Toko:</span> <span>{{ textCamelCase($penjualan->alamat_toko) }}</span>
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
    <!--/ About User -->

  </div>
  <div class="col-xl-8 col-lg-7 col-md-7">
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
                $total_netto = $penjualan->total_bruto - $penjualan->total_retur - $penjualan->potongan - $penjualan->potongan_istimewa - $penjualan->penyesuaian + $penjualan->ppn;
              @endphp
              <h1 style="font-size: 4rem">{{ formatAngka($total_netto) }}</h1>
            </div>
          </div>
        </div>
        <div class="row mt-2">
          <div class="col">
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
                  @endphp
                  <tr>
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

                  <td colspan="8">TOTAL</td>
                  <td class="text-end">{{ formatAngka($penjualan->penyesuaian) }}</td>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
    </div>
    <!--/ Activity Timeline -->
  </div>
</div>
<!--/ User Profile Content -->
@endsection

@push('myscript')
<script>
  var latitude = "{{ !empty($penjualan->latitude) ? $penjualan->latitude : '-7.3665114' }}";
  var longitude = "{{ !empty($penjualan->longitude) ? $penjualan->longitude : '108.2148793' }}";
  //   var latitudecheckin = "{{ $checkin != null ? $checkin->latitude : '-7.3665114' }}";
  //   var longitudecheckin = "{{ $checkin != null ? $checkin->longitude : '108.2148793' }}";
  //   var markericon = "{{ $penjualan->marker }}";

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

  //   var polygon = L.polygon([
  //     [latitude, longitude],
  //     [latitudecheckin, longitudecheckin]
  //   ]).addTo(map);
</script>
@endpush
