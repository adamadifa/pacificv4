@extends('layouts.app')
@section('titlepage', 'Harga HPP')
@section('content')
@section('navigasi')
    <span>Harga HPP</span>
@endsection
<div class="row">
    <div class="col-lg-6">
        <div class="nav-align-top nav-tabs-shadow mb-4">
            @include('layouts.navigation_hpp')
            <div class="tab-content">
                <div class="tab-pane fade active show" id="navs-justified-home" role="tabpanel">
                    <form action="{{ route('hargaawalhpp.store') }}" method="POST" id="formHargaAwal" aria-autocomplete="off">
                        @csrf
                        <div class="row mt-2">
                            <div class="col-12">

                                <div class="row">
                                    <div class="col-lg-6 col-sm-12 col-md-12">
                                        <div class="form-group mb-3">
                                            <select name="bulan" id="bulan" class="form-select">
                                                <option value="">Bulan</option>
                                                @foreach ($list_bulan as $d)
                                                    <option {{ Request('bulan') == $d['kode_bulan'] ? 'selected' : '' }}
                                                        value="{{ $d['kode_bulan'] }}">{{ $d['nama_bulan'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-sm-12 col-md-12">
                                        <div class="form-group mb-3">
                                            <select name="tahun" id="tahun" class="form-select">
                                                <option value="">Tahun</option>
                                                @for ($t = $start_year; $t <= date('Y'); $t++)
                                                    <option {{ Request('tahun') == $t ? 'selected' : '' }} value="{{ $t }}">
                                                        {{ $t }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col">
                                        <select name="lokasi" id="lokasi" class="form-select select2lokasi">
                                            <option value="">Lokasi</option>
                                            <option value="GDG">GUDANG</option>
                                            @foreach ($cabang as $d)
                                                <option {{ Request('lokasi') == $d->kode_cabang ? 'selected' : '' }} value="{{ $d->kode_cabang }}">
                                                    {{ textUpperCase($d->nama_cabang) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="table-responsive mb-2">
                                    <table class="table  table-bordered">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>Kode</th>
                                                <th style="width: 50%">Nama Produk</th>
                                                <th>Harga</th>
                                            </tr>
                                        </thead>
                                        <tbody id="loadhargaawal">

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <button class="btn btn-primary w-100" id="btnSimpan"><i class="ti ti-send me-1"></i>Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<x-modal-form id="modal" show="loadmodal" title="" />
@endsection
@push('myscript')
{{-- <script src="{{ asset('assets/js/pages/roles/create.js') }}"></script> --}}
<script>
    $(function() {

        function buttonDisable() {
            $("#btnSimpan").prop('disabled', true);
            $("#btnSimpan").html(`<div class="spinner-border spinner-border-sm" role="status">
            <span class="visually-hidden">Loading...</span>
            </div>`);
        }
        const select2lokasi = $('.select2lokasi');
        if (select2lokasi.length) {
            select2lokasi.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Pilih  Lokasi',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

        function loading() {
            $("#loadmodal").html(`<div class="sk-wave sk-primary" style="margin:auto">
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            </div>`);
        };

        function loadhargaawal() {
            const bulan = $('#bulan').val();
            const tahun = $('#tahun').val();
            const lokasi = $('#lokasi').val();
            loading();
            $.ajax({
                url: `/hargaawalhpp/gethargaawal`,
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    bulan: bulan,
                    tahun: tahun,
                    lokasi: lokasi
                },
                success: function(respond) {
                    $("#loadhargaawal").html(respond);
                }
            });
        }

        $("#bulan,#tahun,#lokasi").change(function() {
            loadhargaawal();
        });

        loadhargaawal();

        $("#formHargaAwal").on('submit', function(e) {
            // e.preventDefault();
            const form = $(this);
            const bulan = $('#bulan').val();
            const tahun = $('#tahun').val();
            const lokasi = $('#lokasi').val();
            if (bulan == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Bulan Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find('#bulan').focus();
                    },
                })
            } else if (tahun == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Tahun Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find('#tahun').focus();
                    },
                })
            } else if (lokasi == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Lokasi Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find('#lokasi').focus();
                    },
                })
            } else {
                buttonDisable();
                // form.submit();
            }
        })

    });
</script>
@endpush
