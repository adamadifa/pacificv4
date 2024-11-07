@extends('layouts.app')
@section('titlepage', 'Aktifitas SMM')
@section('content')
    <style>
        .webcam-capture {
            display: inline-block;
            width: 100% !important;
            margin: auto;
            height: 250px !important;
            border-radius: 15px;
            overflow: hidden;
        }

        .webcam-capture video {
            display: inline-block;
            width: 100% !important;
            margin: auto;
            height: auto !important;
            border-radius: 15px;
            object-fit: fill;

        }

        #map {
            height: 80px;
            width: 150px;
            position: absolute;
            top: 300px;
            right: 20px;
            opacity: 0.5;

        }

        .jam-digital-malasngoding {

            background-color: #27272783;
            position: absolute;
            top: 150px;
            left: 20px;
            z-index: 9999;
            width: 200px;
            border-radius: 10px;
            padding: 5px;
        }



        .jam-digital-malasngoding p {
            color: #fff;
            font-size: 16px;
            text-align: left;
            margin-top: 0;
            margin-bottom: 0;
        }
    </style>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js"></script>

    <input type="hidden" id="lokasi">
    <div class="row">
        <div class="col">
            <div class="webcam-capture"></div>
            <div class="jam-digital-malasngoding">
                <p>{{ auth()->user()->name }}</p>
                <p>{{ DateToIndo(date('Y-m-d')) }}</p>
                <p id="jam"></p>
                <p id="maptext" style="font-size:12px"></p>
            </div>
            <div id="map"></div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="form-group m-0">
                <textarea name="activity" id="activity" cols="30" rows="4" class="form-control" placeholder="Input Aktivitas"></textarea>
            </div>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col-12">
            <button class="btn w-100 btn-primary" type="submit" name="submit" id="sendactivity"><i class="ti ti-send me-2"></i>Kirim</button>
        </div>
    </div>
@endsection
@push('myscript')
    <script type="text/javascript">
        window.onload = function() {
            jam();
        }

        function jam() {
            var e = document.getElementById('jam'),
                d = new Date(),
                h, m, s;
            h = d.getHours();
            m = set(d.getMinutes());
            s = set(d.getSeconds());

            e.innerHTML = h + ':' + m + ':' + s;

            setTimeout('jam()', 1000);
        }

        function set(e) {
            e = e < 10 ? '0' + e : e;
            return e;
        }
    </script>
    <script>
        Webcam.set({
            height: 480,
            width: 640,
            image_format: 'jpeg',
            jpeg_quality: 80,
            constraints: {
                facingMode: 'environment'
            }
        });

        Webcam.attach('.webcam-capture');

        var lokasi = document.getElementById('lokasi');
        var maptext = document.getElementById('maptext');
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(successCallback, errorCallback);
        }

        function successCallback(position) {
            lokasi.value = position.coords.latitude + "," + position.coords.longitude;
            maptext.innerHTML = position.coords.latitude + ",<br>" + position.coords.longitude;
            var map = L.map('map').setView([position.coords.latitude, position.coords.longitude], 18);
            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 8,
                attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(map);
            var marker = L.marker([position.coords.latitude, position.coords.longitude]).addTo(map);
            setInterval(function() {
                map.invalidateSize();
            }, 100);
        }

        function errorCallback() {

        }
        $("#sendactivity").click(function(e) {
            Webcam.snap(function(uri) {
                image = uri;
            });
            var lokasi = $("#lokasi").val();
            var activity = $("#activity").val();
            if (activity == "") {
                Swal.fire({
                    title: 'Berhasil !',
                    text: 'Aktifitas Harus Diisi',
                    icon: 'error'
                })
            } else {
                $("#sendactivity").prop('disabled', true);
                $("#sendactivity").text('Loading..');
                $.ajax({
                    type: 'POST',
                    url: '/aktifitassmm/store',
                    data: {
                        _token: "{{ csrf_token() }}",
                        lokasi: lokasi,
                        activity: activity,
                        image: image
                    },
                    cache: false,
                    success: function(respond) {
                        Swal.fire({
                            title: 'Berhasil !',
                            text: respond.message,
                            icon: 'success'
                        })
                        setTimeout("location.href='/aktifitassmm'", 2000);
                    },
                    error: function(xhr) {
                        $("#sendactivity").prop('disabled', false);
                        $("#sendactivity").text('Kirim');
                        Swal.fire("Error", xhr.responseJSON.message, "error");
                    }
                });
            }

        });
    </script>
@endpush
