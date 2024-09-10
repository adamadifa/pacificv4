@extends('layouts.app')
@section('titlepage', 'Data Pelanggan')

@section('content')
@section('navigasi')
    <span>Capture Pelanggan</span>
@endsection
<style>
    .webcam-capture,
    .webcam-capture video {
        display: inline-block;
        width: 100% !important;
        height: 100% !important;
        margin: auto;
        text-align: center;
        border-radius: 15px;
        overflow: hidden;
    }

    #map {
        height: 100px;
        z-index: 9999;
        /* position: absolute;
        top: 300px; */
    }
</style>
<div class="row">
    <div class="col">
        <div class="webcam-capture"></div>
    </div>
</div>
<div id="map"></div>
<div class="row mb-2">
    <div class="col">
        <a href="#" class="btn btn-primary shadow-sm w-100 text-white" id="takeabsen"><i class="ti ti-camera me-1"></i>Capture</a>
    </div>
</div>

@endsection
@push('myscript')
<script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.25/webcam.min.js"></script>
<script>
    $(document).ready(function() {
        let currentLocation;
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
            let koordinat_pelanggan = currentLocation.split(",");
            var map = L.map('map').setView([koordinat_pelanggan[0], koordinat_pelanggan[1]], 15);
            L.tileLayer('http://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
                maxZoom: 20,
                subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
            }).addTo(map);

            var marker = L.marker([koordinat_pelanggan[0], koordinat_pelanggan[1]]).addTo(map);
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





        Webcam.set({
            width: 590,
            height: 460,
            image_format: 'jpeg',
            jpeg_quality: 80,
        });

        var cameras = new Array(); //create empty array to later insert available devices
        navigator.mediaDevices.enumerateDevices() // get the available devices found in the machine
            .then(function(devices) {
                devices.forEach(function(device) {
                    var i = 0;
                    if (device.kind === "videoinput") { //filter video devices only
                        cameras[i] = device.deviceId; // save the camera id's in the camera array
                        i++;
                    }
                });
            })

        Webcam.set('constraints', {
            width: 590,
            height: 460,
            image_format: 'jpeg',
            jpeg_quality: 80,
            sourceId: cameras[0],
            facingMode: {
                exact: 'environment'
            },

        });

        Webcam.attach('.webcam-capture');

        $("#takeabsen").click(function() {
            Webcam.snap(function(data_uri) {
                console.log(data_uri);
                image = data_uri;
            });
            var kode_pelanggan = "{{ Crypt::encrypt($pelanggan->kode_pelanggan) }}";

            $.ajax({
                type: 'POST',
                url: '/sfa/storepelanggancapture',
                data: {
                    _token: "{{ csrf_token() }}",
                    kode_pelanggan: "{{ $pelanggan->kode_pelanggan }}",
                    image: image,
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
                                $("#takeabsen").hide();
                                window.location.href = `/sfa/pelanggan/${kode_pelanggan}/show`;
                            },
                        });
                    } else {
                        Swal.fire({
                            title: "Gagal",
                            text: respond.message,
                            icon: "danger",
                            showConfirmButton: true,
                            didClose: (e) => {
                                //location.reload();
                            },
                        });
                    }
                }
            });
        });


    });
</script>
@endpush
