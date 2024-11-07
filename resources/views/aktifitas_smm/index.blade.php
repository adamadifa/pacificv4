@extends('layouts.app')
@section('titlepage', 'Aktifitas SMM')
@section('content')
    <div class="row">
        <div class="col">
            <form action="#">
                <x-input-with-icon label="Tanggal" name="tanggal" value="{{ date('Y-m-d') }}" datepicker="flatpickr-date" icon="ti ti-calendar" />
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col" id="getaktifitas"></div>
    </div>
    <div class="floating-button">
        <a href="{{ route('aktifitassmm.create') }}" class="btn btn-primary btn-circle btn-lg">
            <i class="fas fa-plus"></i>
        </a>
    </div>

    <style>
        .floating-button {
            position: fixed;
            bottom: 90px;
            right: 20px;
            z-index: 1000;
        }

        .btn-circle {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
@endsection
@push('myscript')
    <script>
        $(function() {
            function getAktifitas() {
                let tanggal = $('#tanggal').val();
                $.ajax({
                    method: "POST",
                    url: "{{ route('aktifitassmm.getaktifitas') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        tanggal: tanggal
                    },
                    cache: false,
                    success: function(data) {
                        $('#getaktifitas').html(data);
                    }
                })
            }

            getAktifitas();
            $('#tanggal').change(function() {
                getAktifitas();
            });
        });
    </script>
@endpush
