@extends('layouts.app')
@section('titlepage', 'Ajuan Faktur')

@section('content')
@section('navigasi')
    <span>Ajuan Faktur</span>
@endsection
<div class="row">
    <form action="{{ route('sfa.storeajuanfaktur', Crypt::encrypt($pelanggan->kode_pelanggan)) }}" aria-autocomplete="false" id="formAjuanfaktur"
        method="POST" enctype="multipart/form-data">
        @csrf

        <table class="table mb-3">
            <tr>
                <th>Kode Pelanggan</th>
                <td>{{ $pelanggan->kode_pelanggan }}</td>
            </tr>
            <tr>
                <th>Nama Pelanggan</th>
                <td>{{ $pelanggan->nama_pelanggan }}</td>
            </tr>
            <tr>
                <th>Alamat</th>
                <td>{{ $pelanggan->alamat_pelanggan }}</td>
            </tr>
        </table>
        <x-input-with-icon icon="ti ti-calendar" label="Tanggal Pengajuan" name="tanggal" datepicker="flatpickr-date" />
        <x-input-with-icon icon="ti ti-file-copy" label="Jumlah Faktur" name="jumlah_faktur" align="right" money="true" />
        <x-textarea label="Keterangan" name="keterangan" />
        <div class="row mt-2">
            <div class="col-12">
                <div class="form-check mt-3 mb-2">
                    <input class="form-check-input cod" name="cod" value="1" type="checkbox" id="cod">
                    <label class="form-check-label" for="cod">Pembyayaran Saat Turun Barang Selanjutnya </label>
                </div>
            </div>
        </div>
        <div class="form-group">
            <button class="btn btn-primary w-100" type="submit">
                <ion-icon name="send-outline" class="me-1"></ion-icon>
                Submit
            </button>
        </div>
    </form>
</div>

@endsection
@push('myscript')
<script src="{{ asset('assets/js/pages/ajuanfaktur.js') }}"></script>
@endpush
