@extends('layouts.app')
@section('titlepage', 'Data Pelanggan')

@section('content')
@section('navigasi')
    <span>Data Pelanggan</span>
@endsection
<style>
    #map {
        height: 180px;
    }
</style>

<div class="row">
    <div class="col d-flex justify-content-between">
        <div class="btn-group" role="group" aria-label="First group">
            <a href="{{ route('sfa.editpelanggan', Crypt::encrypt($pelanggan->kode_pelanggan)) }}" class="btn btn-success waves-effect text-white p-3">
                <i class="ti ti-edit"></i>
            </a>
            <a href="{{ route('sfa.capture', Crypt::encrypt($pelanggan->kode_pelanggan)) }}" class="btn btn-primary waves-effect text-white p-2">
                <i class="ti ti-camera"></i>
            </a>
            <a href="{{ route('sfa.createajuanlimit', Crypt::encrypt($pelanggan->kode_pelanggan)) }}"
                class="btn btn-primary waves-effect text-white p-3">
                <i class="ti ti-receipt-2"></i>
            </a>
            <a href="{{ route('sfa.createajuanfaktur', Crypt::encrypt($pelanggan->kode_pelanggan)) }}"
                class="btn btn-primary waves-effect text-white p-3">
                <i class="ti ti-receipt"></i>
            </a>
        </div>
        <div>
            @if (!empty(Cookie::get('kodepelanggan')))
                @if (Crypt::decrypt(Cookie::get('kodepelanggan')) == $pelanggan->kode_pelanggan)
                    <a href="{{ route('sfa.checkout', Crypt::encrypt($pelanggan->kode_pelanggan)) }}"
                        class="btn btn-danger waves-effect text-white p-2">
                        <i class="ti ti-logout me-1"></i> CheckOut
                    </a>
                @endif
            @endif
        </div>
    </div>
</div>

<div class="row mt-2">
    <div class="col">
        @if ($pelanggan->status_aktif_pelanggan == 0)
            <div class="alert alert-danger" role="alert">
                <h4 class="alert-heading">Peringatan</h4>
                <p class="mb-0">
                    Pelanggan Tidak Aktif, Silahkan Hubungi Admin Untuk Mengaktifkan Pelanggan Tersebut!
                </p>
            </div>
        @else
            <div class="card p-0 m-0">
                <div class="card-content p-0">
                    @if (Storage::disk('public')->exists('/pelanggan/' . $pelanggan->foto) && !empty($pelanggan->foto))
                        <img src="{{ getfotoPelanggan($pelanggan->foto) }}" class="card-img-top img-fluid" alt="user image"
                            style="height: 220px; object-fit: cover" id="foto">
                    @else
                        <img src="{{ asset('assets/img/elements/1.jpg') }}"class="card-img-top img-fluid" alt="user image"
                            style="height: 220px; object-fit: cover" id="foto">
                    @endif
                    {{-- <img class="card-img-top img-fluid" style="height: 220px; object-fit: cover" id="foto"
                src="https://sfa.pacific-tasikmalaya.com/storage/pelanggan/TSM-03620.png" alt="Card image cap"> --}}
                    <div class="card-img-overlay" style="background-color: #00000097;">
                        <h5 class="card-title text-white m-0">{{ $pelanggan->kode_pelanggan }}</h5>
                        <h5 class="card-title text-white m-0">{{ $pelanggan->nama_pelanggan }}</h5>
                        <p class="card-text text-white m-0">{{ textCamelCase($pelanggan->alamat_pelanggan) }}</p>
                        <p class="card-text text-white m-0">Limit Pelanggan : {{ formatAngka($pelanggan->limit_pelanggan) }}</p>
                        <p class="card-text text-white m-0">Jumlah Faktur Max : {{ $ajuanfaktur != null ? $ajuanfaktur->jumlah_faktur : 0 }}</p>
                        @php
                            $cod = $ajuanfaktur != null ? $ajuanfaktur->siklus_pembayaran : '0';
                        @endphp
                        {{-- <p class="card-text text-white m-0">COD : {{ $cod == '0' ? 'Tidak' : 'Ya' }}</p> --}}
                        <p class="card-text text-white m-0">Faktur Belum Lunas : {{ $fakturkredit }}</p>

                    </div>

                </div>
                <div class="card-content p-0" style="background: white !important">
                    <div id="map"></div>
                </div>
            </div>
        @endif
    </div>
</div>
<div class="row mt-3 mb-3">

    @if ($checkin == 0)
        <div class="col" id="checkinsection">
            <a href="#" id="checkin" class="btn btn-success w-100"><i class="ti ti-fingerprint me-2"></i>Checkin</a>
        </div>
    @else
        @if (!empty(Cookie::get('kodepelanggan')))
            @if (Crypt::decrypt(Cookie::get('kodepelanggan')) == $pelanggan->kode_pelanggan)
                <div class="col-6">
                    <a href="/sfa/penjualan/create" class="btn btn-primary w-100"><i class="ti ti-shopping-bag me-1"></i>Penjualan</a>
                </div>
                <div class="col-6">
                    <a href="/sfa/retur/create" class="btn btn-danger w-100"><i class="ti ti-reload me-1"></i>Retur</a>
                </div>
            @else
                <div class="col" id="checkinsection">
                    <a href="#" id="checkin" class="btn btn-success w-100"><i class="ti ti-fingerprint me-2"></i>Checkin</a>
                </div>
            @endif
        @else
            <div class="col" id="checkinsection">
                <a href="#" id="checkin" class="btn btn-success w-100"><i class="ti ti-fingerprint me-2"></i>Checkin</a>
            </div>
        @endif
    @endif

</div>
<div class="nav-align-top mt-2 mb-3">
    <ul class="nav nav-tabs nav-fill" role="tablist">
        <li class="nav-item" role="presentation">
            <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#penjualan" aria-controls="penjualan"
                aria-selected="true">
                Penjualan
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#retur" aria-controls="retur"
                aria-selected="false" tabindex="-1">
                Retur
            </button>
        </li>
    </ul>
    <div class="tab-content p-0 bg-transparent">
        <div class="tab-pane fade show active" id="penjualan" role="tabpanel">
            @if (!empty(Cookie::get('kodepelanggan')))
                @if (Crypt::decrypt(Cookie::get('kodepelanggan')) == $pelanggan->kode_pelanggan)
                    <livewire:penjualanpelanggan :kode_pelanggan="$pelanggan->kode_pelanggan" />
                @endif
            @endif
        </div>
        <div class="tab-pane fade" id="retur" role="tabpanel">
            @if (!empty(Cookie::get('kodepelanggan')))
                @if (Crypt::decrypt(Cookie::get('kodepelanggan')) == $pelanggan->kode_pelanggan)
                    <livewire:returpelanggan :kode_pelanggan="$pelanggan->kode_pelanggan" />
                @endif
            @endif
        </div>
    </div>
</div>


@endsection
@push('myscript')
<script>
    $(function() {
        const status_lokasi = '{{ $pelanggan->status_lokasi }}';
        const lokasi_pelanggan = '{{ $pelanggan->latitude }}' + ',' + '{{ $pelanggan->longitude }}';
        let currentLocation;
        let kode_pelanggan = "{{ Cookie::get('kodepelanggan') }}";

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(successCallback, errorCallback);
        } else {
            swal({
                title: 'Oops!',
                text: 'Maaf, browser Anda tidak mendukung geolokasi HTML5.',
                icon: 'error',
                timer: 3000,
            });
        }

        function successCallback(position) {
            currentLocation = position.coords.latitude + "," + position.coords.longitude;
            // lokasi.value = "" + position.coords.latitude + "," + position.coords.longitude + "";
            let currentLatitude = position.coords.latitude;
            let currentLongitude = position.coords.longitude;

            let koordinat_pelanggan = lokasi_pelanggan.split(",");
            if (status_lokasi == 1) {
                var latitudePelanggan = koordinat_pelanggan[0];
                var longitudePelanggan = koordinat_pelanggan[1];
            } else {
                var latitudePelanggan = currentLatitude;
                var longitudePelanggan = currentLongitude;
            }

            // alert(latitudetoko);
            var map = L.map('map').setView([currentLatitude, currentLongitude], 18);
            L.tileLayer('http://{s}.google.com/vt?lyrs=m&x={x}&y={y}&z={z}', {
                maxZoom: 20,
                subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
            }).addTo(map);
            var marker = L.marker([currentLatitude, currentLongitude]).addTo(map);
            var circle = L.circle([latitudePelanggan, longitudePelanggan], {
                color: 'red',
                fillColor: '#f03',
                fillOpacity: 0.5,
                radius: 30
            }).addTo(map);
        }


        // Define callback function for failed attempt
        function errorCallback(error) {
            if (error.code == 1) {
                swal({
                    title: 'Oops!',
                    text: 'Anda telah memutuskan untuk tidak membagikan posisi Anda, tetapi tidak apa-apa. Kami tidak akan meminta Anda lagi.',
                    icon: 'error',
                    timer: 3000,
                });
            } else if (error.code == 2) {
                swal({
                    title: 'Oops!',
                    text: 'Jaringan tidak aktif atau layanan penentuan posisi tidak dapat dijangkau.',
                    icon: 'error',
                    timer: 3000,
                });
            } else if (error.code == 3) {
                swal({
                    title: 'Oops!',
                    text: 'Waktu percobaan habis sebelum bisa mendapatkan data lokasi.',
                    icon: 'error',
                    timer: 3000,
                });
            } else {
                swal({
                    title: 'Oops!',
                    text: 'Waktu percobaan habis sebelum bisa mendapatkan data lokasi.',
                    icon: 'error',
                    timer: 3000,
                });
            }
        }

        $("#checkin").click(function() {
            let kodepel = "{{ $pelanggan->kode_pelanggan }}";
            $("#checkinsection").hide();
            $.ajax({
                type: 'POST',
                url: '/sfa/checkinstore',
                data: {
                    _token: "{{ csrf_token() }}",
                    kode_pelanggan: kodepel,
                    lokasi: currentLocation
                },
                cache: false,
                success: function(respond) {
                    if (respond.success == true) {
                        Swal.fire({
                            title: "Berhasil",
                            text: respond.message,
                            icon: "success",
                            showConfirmButton: true,

                            didClose: (e) => {
                                $("#checkinsection").hide();
                                location.reload();

                            },
                        });
                    } else {
                        Swal.fire({
                            title: "Gagal",
                            text: respond.message,
                            icon: "danger",
                            showConfirmButton: true,
                            didClose: (e) => {
                                location.reload();
                            },
                        });
                    }
                }
            });
        });
    });
</script>
@endpush
