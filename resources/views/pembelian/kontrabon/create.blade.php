@extends('layouts.app')
@section('titlepage', 'Buat Kontra Bon')
@section('content')

    <style>
        .nonaktif {
            background-color: red;
        }
    </style>
@section('navigasi')
    <span class="text-muted">Kontrabon</span> / <span>Buat Kontra Bon</span>
@endsection
<form action="{{ route('kontrabonpmb.store') }}" method="POST" id="formPembelian">
    @csrf
    <div class="row">
        <div class="col-lg-3 col-sm-12 col-xs-12">
            <div class="row mb-3">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <x-input-with-icon label="Auto" name="no_kontrabon" icon="ti ti-barcode" />
                            <x-input-with-icon label="Tanggal" name="tanggal" icon="ti ti-calendar" datepicker="flatpickr-date" />
                            <x-select label="Supplier" name="kode_supplier" :data="$supplier" key="kode_supplier" textShow="nama_supplier"
                                upperCase="true" select2="select2Kodesupplier" />
                            <div class="form-group mb-3">
                                <select name="kategori" id="kategori" class="form-select">
                                    <option value="">Jenis Pengajuan</option>
                                    <option value="KB">Kontra Bon</option>
                                    <option value="IM">Interal Memo</option>
                                </select>
                            </div>
                            <x-input-with-icon label="No. Dokumen" name="no_dokumen" icon="ti ti-barcode" />
                            <div class="form-group mb-3">
                                <select name="jenis_bayar" id="jenis_bayar" class="form-select">
                                    <option value="">Jenis Bayar</option>
                                    <option value="TN">Tunai</option>
                                    <option value="TF">Transfer</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-9 col-md-12 col-sm-12">

            <div class="row mb-3">
                <div class="col">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between">
                                <h5 class="card-title">Detail Kontrabon</h5>
                                <div class="d-flex justify-content-between">
                                    <i class="ti ti-shopping-cart text-primary me-5" style="font-size: 2em;"></i>
                                    <h4 id="grandtotal_text">0</h4>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-3 col-md-12 col-sm-12">
                                    <x-input-with-icon label="No. Bukti Pembelian" name="no_bukti" icon="ti ti-barcode" readonly="true" />
                                </div>
                                <div class="col-lg-2 col-md-12 col-sm-12">
                                    <x-input-with-icon label="Total Pembelian" name="total_pembelian" icon="ti ti-box" align="right"
                                        numberFormat="true" />
                                </div>
                                <div class="col-lg-2 col-md-12 col-sm-12">
                                    <x-input-with-icon label="Jumlah Bayar" name="jumlah" icon="ti ti-moneybag" align="right" numberFormat="true" />
                                </div>
                                <div class="col-lg-5 col-md-12 col-sm-12">
                                    <x-input-with-icon label="Keterangan" name="keterangan" icon="ti ti-file-description" align="right"
                                        numberFormat="true" />
                                </div>
                            </div>

                            <div class="row">
                                <div class="col">
                                    <div class="form-group mb-3">
                                        <button class="btn btn-primary w-100" id="btnTambahbarang">
                                            <i class="ti ti-plus me-1"></i>Tambah Barang
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">

                                    <table class="table table-bordered">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>No. Bukti</th>
                                                <th>Jumlah</th>
                                                <th>Keterangan</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody id="loadbarang"></tbody>
                                        <tfoot class="table-dark">
                                            <tr>
                                                <td colspan="6">TOTAL</td>
                                                <td id="grandtotal" class="text-end"></td>
                                                <td colspan="3"></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-12">
                                    <div class="form-check mt-3 mb-3">
                                        <input class="form-check-input agreement" name="aggrement" value="aggrement" type="checkbox" value=""
                                            id="defaultCheck3">
                                        <label class="form-check-label" for="defaultCheck3"> Yakin Akan Disimpan ? </label>
                                    </div>
                                    <div class="form-group" id="saveButton">
                                        <button class="btn btn-primary w-100" type="submit" id="btnSimpan">
                                            <ion-icon name="send-outline" class="me-1"></ion-icon>
                                            Submit
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</form>
<div class="modal fade" id="modalBarang" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel18">Data Barang</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table" id="tabelbarang" width="100%">
                        <thead class="table-dark">
                            <tr>
                                <th>Kode</th>
                                <th>Nama Barang</th>
                                <th>Satuan</th>
                                <th>Jenis Barang</th>
                                <th>Kategori</th>
                                <th>#</th>
                            </tr>
                        </thead>
                        <tbody></tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
