@extends('layouts.app')
@section('titlepage', 'Tracking Salesman')

@section('content')
@section('navigasi')
    <span>Tracking Salesman</span>
@endsection

<style>
    #map {
        height: 800px;
    }
</style>
<div class="row">
    @hasanyrole($roles_show_cabang)
        <div class="col-lg-4 col-sm-12 col-xs-12">
            <x-input-with-icon icon="ti ti-calendar" name="tanggal" label="Tanggal" datepicker="flatpickr-date" />
        </div>
        <div class="col-lg-3 col-sm-12 col-xs-12">
            <select name="kode_cabang" id="kode_cabang" class="form-select select2Kodecabang">
                <option value="">Pilih Cabang</option>
                @foreach ($cabang as $d)
                    <option value="{{ $d->kode_cabang }}">{{ textUpperCase($d->nama_cabang) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-3 col-sm-12 col-xs-12">
            <select name="kode_salesman" id="kode_salesman" class="form-select select2Kodesalesman">
                <option value="">Pilih Salesman</option>
            </select>
        </div>
        <div class="col-lg-2 col-sm-12 col-xs-12">
            <button class="btn btn-primary" id="btnTrackingSalesman"><i class="ti ti-search"></i></button>
        </div>
    @else
        <div class="col-lg-4 col-sm-12 col-xs-12">
            <x-input-with-icon icon="ti ti-calendar" name="tanggal" label="Tanggal" datepicker="flatpickr-date" />
        </div>
        <div class="col-lg-4 col-sm-12 col-xs-12">
            <select name="kode_salesman" id="kode_salesman" class="form-select select2Kodesalesman">
                <option value="">Pilih Salesman</option>
            </select>
        </div>
        <div class="col-lg-2 col-sm-12 col-xs-12">
            <button class="btn btn-primary" id="btnTrackingSalesman"><i class="ti ti-search"></i></button>
        </div>
    @endrole
</div>
<div class="row">
    <div class="col-12">
        <div id="map"></div>
    </div>
</div>
@endsection
@push('myscript')
<script>
    $(function() {
        const select2Kodecabang = $(".select2Kodecabang");
        if (select2Kodecabang.length) {
            select2Kodecabang.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Pilih Cabang',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

        const select2Kodesalesman = $(".select2Kodesalesman");
        if (select2Kodesalesman.length) {
            select2Kodesalesman.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Semua Salesman',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

        function getsalesmanbyCabang() {
            const kode_cabang = $("#kode_cabang").val();
            //alert(selected);
            $.ajax({
                type: 'POST',
                url: '/salesman/getsalesmanbycabang',
                data: {
                    _token: "{{ csrf_token() }}",
                    kode_cabang: kode_cabang
                },
                cache: false,
                success: function(respond) {
                    console.log(respond);
                    $("#kode_salesman").html(respond);
                }
            });
        }

        getsalesmanbyCabang();
        $("#kode_cabang").change(function(e) {
            getsalesmanbyCabang();
        });

        var latitude = "{{ $lokasi_cabang[0] }}";
        var longitude = "{{ $lokasi_cabang[1] }}";
        var map = L.map('map').setView([latitude, longitude], 14);
        var layerGroup = L.layerGroup();
        // L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        //     maxZoom: 19
        //     , attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        // }).addTo(map);

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);


        L.marker([latitude, longitude]).addTo(map);


        function show(tanggal, kode_cabang, kode_salesman) {
            if (map.hasLayer(layerGroup)) {
                console.log('already have one, clear it');
                layerGroup.clearLayers();
            } else {
                console.log('never have it before');
            }

            $.getJSON('/sfa/getlocationcheckin?tanggal=' + tanggal + '&kode_cabang=' + kode_cabang + '&kode_salesman=' + kode_salesman,
                function(
                    data) {
                    $.each(data, function(index) {
                        var salesmanicon = L.icon({
                            // iconUrl: 'app-assets/marker/' + data[index].marker,
                            iconUrl: '/assets/img/marker/default.png',
                            iconSize: [75, 75], // size of the icon
                            shadowSize: [50, 64], // size of the shadow
                            iconAnchor: [22, 94], // point of the icon which will correspond to marker's location
                            shadowAnchor: [4, 62], // the same for the shadow
                            popupAnchor: [-3, -76] // point from which the popup should open relative to the iconAnchor
                        });

                        var imagepath = "{{ Storage::url('pelanggan/') }}" + data[index].foto;

                        var marker = L.marker([parseFloat(data[index].latitude), parseFloat(data[index].longitude)], {
                            icon: salesmanicon
                        }).bindPopup("<b>" + data[index].kode_pelanggan + " - " + data[index].nama_pelanggan +
                            "</b><br><br>" + "<img width='200px' height='200px' src='" + imagepath + "'/><br><br>" +
                            "Latitude : " + data[index].latitude + " <br>Longitude : " + data[index].longitude +
                            "<br> Alamat :" + data[index].alamat_pelanggan + "<br> Checkin Time :" + data[index]
                            .checkin_time, {
                                maxWidth: 200
                            });


                        layerGroup.addLayer(marker);
                        map.addLayer(layerGroup);
                    });
                });

        }

        function loading() {
            $("#map").html(`<div class="sk-wave sk-primary" style="margin:auto">
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            </div>`);
        }
        $("#btnTrackingSalesman").click(function(e) {
            e.preventDefault();
            const tanggal = $("#tanggal").val();
            const kode_cabang = $("#kode_cabang").val();
            const kode_salesman = $("#kode_salesman").val();
            show(tanggal, kode_cabang, kode_salesman);
        });

        show(tanggal = "", kode_cabang = "", kode_salesman = "");
    });
</script>
@endpush
