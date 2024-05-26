@extends('layouts.app')
@section('titlepage', 'Input Penjualan')

@section('content')
@section('navigasi')
   <span class="text-muted">Penjualan</span> / <span>Input Penjualan</span>
@endsection
<form action="{{ route('penjualan.cetaksuratjalanrange') }}" target="_blank" method="POST" id="formCetakfaktur">
   <div class="row">
      <div class="col-lg-3 col-sm-12 col-xs-12">
         <div class="row mb-3">
            <div class="col">
               <div class="card">
                  <div class="card-body">
                     <x-input-with-icon label="No. Faktur" name="no_faktur" icon="ti ti-barcode" />
                     <x-input-with-icon label="Tanggal" name="tanggal" icon="ti ti-calendar" datepicker="flatpickr-date" />
                     <x-input-with-icon label="Pelanggan" name="nama_pelanggan" icon="ti ti-user" readonly="true" />
                     <input type="hidden" name="kode_pelanggan">
                     <x-input-with-icon label="Salesman" name="nama_salesman" icon="ti ti-user" readonly="true" />
                     <input type="hidden" name="kode_salesman">
                  </div>
               </div>
            </div>
         </div>
         <div class="row">
            <div class="col">
               <div class="card h-100">
                  <img class="card-img-top" src="../../assets/img/elements/2.jpg" alt="Card image cap"
                     style="height:250px; object-fit:cover" id="foto">
                  <div class="card-body">
                     <p class="card-text" id="alamat_pelanggan">
                        Some quick example text to build on the card title and make up the bulk of the card's content.
                     </p>
                     <table class="table">
                        <tr>
                           <th>No. HP</th>
                           <td id="no_hp_pelanggan"></td>
                        </tr>
                        <tr>
                           <th>Titik Koordinat</th>
                           <td id="lokasi"></td>
                        </tr>
                        <tr>
                           <th>Limit Pelanggan</th>
                           <td id="limit_pelanggan"></td>
                        </tr>
                        <tr>
                           <th>Piutang</th>
                           <td id="piutang_pelanggan"></td>
                        </tr>
                        <tr>
                           <th>Faktur Belum Lunas</th>
                           <td></td>
                        </tr>
                     </table>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div class="col-lg-9 col-md-12 col-sm-12">
         <div class="row mb-3">
            <div class="col">
               <div class="card">
                  <div class="card-body">
                     <div class="d-flex justify-content-between">
                        <div class="icon-cart mt-3">
                           <i class="ti ti-shopping-bag text-primary" style="font-size: 8rem"></i>
                        </div>
                        <div class="mt-2">
                           <h1 style="font-size: 6.5rem">0</h1>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="row">
            <div class="col">
               <div class="card">
                  <div class="card-header">
                     <h5 class="card-title">Detail Penjualan</h5>
                  </div>
                  <div class="card-body">
                     <div class="row">
                        <div class="col-lg-4 col-md-12 col-sm12">
                           <x-input-with-icon label="Produk" name="nama_produk" icon="ti ti-barcode" height="80px" />
                           <input type="hidden" id="kode_produk" name="kode_produk">
                           <input type="hidden" id="isi_pcs_dus" name="isi_pcs_dus">
                           <input type="hidden" id="isi_pcs_pack" name="isi_pcs_pack">
                        </div>
                        <div class="col-lg-2 col-md-12 col-sm-12">
                           <div class="row">
                              <div class="col">
                                 <x-input-with-icon label="Dus" name="jml_dus" icon="ti ti-box" align="right" money="true" />
                              </div>
                           </div>
                           <div class="row">
                              <div class="col">
                                 <x-input-with-icon label="Harga / Dus" name="harga_dus" icon="ti ti-moneybag" align="right" money="true" />
                              </div>
                           </div>
                        </div>
                        <div class="col-lg-2 col-md-12 col-sm-12">
                           <div class="row">
                              <div class="col">
                                 <x-input-with-icon label="Pack" name="jml_pack" icon="ti ti-box" align="right" money="true" />
                              </div>
                           </div>
                           <div class="row">
                              <div class="col">
                                 <x-input-with-icon label="Harga / Pack" name="harga_pack" icon="ti ti-moneybag" align="right" money="true" />
                              </div>
                           </div>
                        </div>
                        <div class="col-lg-2 col-md-12 col-sm-12">
                           <div class="row">
                              <div class="col">
                                 <x-input-with-icon label="Pack" name="jml_pcs" icon="ti ti-box" align="right" money="true" />
                              </div>
                           </div>
                           <div class="row">
                              <div class="col">
                                 <x-input-with-icon label="Harga / Pcs" name="harga_pcs" icon="ti ti-moneybag" align="right" money="true" />
                              </div>
                           </div>
                        </div>
                        <div class="col-2">
                           <div class="form-check mt-3 mb-3">
                              <input class="form-check-input staatus_promosi" name="a" value="1" type="checkbox"
                                 value="" id="staatus_promosi">
                              <label class="form-check-label" for="staatus_promosi"> Promosi </label>
                           </div>
                        </div>
                     </div>
                     <div class="row mb-3">
                        <div class="col">
                           <a href="#" id="tambahproduk" class="btn btn-primary w-100"><i class="ti ti-plus me-1"></i>Tambah Produk</a>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col">
                           <div class="table-responsive">
                              <table class="table table-bordered">
                                 <thead class="text-center table-dark">
                                    <tr>
                                       <th rowspan="2">Kode</th>
                                       <th rowspan="2">Nama Barang</th>
                                       <th colspan="6">Quantity</th>
                                       <th rowspan="2">Subtotal</th>
                                       <th rowspan="2">Aksi</th>
                                    </tr>
                                    <tr>
                                       <th>Dus</th>
                                       <th>Harga</th>
                                       <th>Pack</th>
                                       <th>Harga</th>
                                       <th>Pcs</th>
                                       <th>Harga</th>
                                    </tr>
                                 </thead>
                                 <tbody id="loadproduk"></tbody>
                              </table>
                           </div>
                        </div>
                     </div>
                     <div class="row mt-3">
                        <div class="col-lg-3 col-sm-12">
                           <div class="row">
                              <div class="col">
                                 <div class="divider text-start divider-primary">
                                    <div class="divider-text" style="font-size: 1rem">
                                       <i class="ti ti-discount"></i> Potongan
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col">
                                 <x-input-with-group label="AIDA" placeholder="Potongan AIDA" name="potongan_aida" align="right" />
                                 <x-input-with-group label="SWAN" placeholder="Potongan SWAN" name="potongan_swan" align="right" />
                                 <x-input-with-group label="STICK" placeholder="Potongan STICK" name="potongan_stick" align="right" />
                                 <x-input-with-group label="SAMBAL" placeholder="Potongan SAMBAL" name="potpngan_sambal" align="right" />
                              </div>
                           </div>
                        </div>
                        <div class="col-lg-3 col-sm-12">
                           <div class="row">
                              <div class="col">
                                 <div class="divider text-start divider-primary">
                                    <div class="divider-text" style="font-size: 1rem">
                                       <i class="ti ti-discount"></i> Potongan Istimewa
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col">
                                 <x-input-with-group label="AIDA" placeholder="Potongan Istimewa AIDA" name="potis_aida" align="right" />
                                 <x-input-with-group label="SWAN" placeholder="Potongan Istimewa SWAN" name="potis_swan" align="right" />
                                 <x-input-with-group label="STICK" placeholder="Potongan Istimewa STICK" name="potis_stick" align="right" />
                              </div>
                           </div>
                        </div>
                        <div class="col-lg-3 col-sm-12">
                           <div class="row">
                              <div class="col">
                                 <div class="divider text-start divider-primary">
                                    <div class="divider-text" style="font-size: 1rem">
                                       <i class="ti ti-tag"></i> Penyesuaian
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col">
                                 <x-input-with-group label="AIDA" placeholder="Penyesuaian AIDA" name="peny_aida" align="right" />
                                 <x-input-with-group label="SWAN" placeholder="Penyesuaian SWAN" name="peny_swan" align="right" />
                                 <x-input-with-group label="STICK" placeholder="Penyesuaian STICK" name="peny_stick" align="right" />
                              </div>
                           </div>
                        </div>
                        <div class="col-lg-3 col-sm-12">
                           <div class="row">
                              <div class="col">
                                 <div class="divider text-start divider-primary">
                                    <div class="divider-text" style="font-size: 1rem">
                                       <i class="ti ti-moneybag"></i> Pembayaran
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col">
                                 <div class="form-group mb-3">
                                    <select name="jenis_transaksi" id="jenis_transaksi" class="form-select">
                                       <option value="">Jenis Transaksi</option>
                                       <option value="T">TUNAI</option>
                                       <option value="K">KREDIT</option>
                                    </select>
                                 </div>
                                 <x-input-with-icon label="Grand Total" name="grandtotal" icon="ti ti-shopping-cart" align="right" />
                              </div>
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
<x-modal-form id="modal" size="" show="loadmodal" title="" />
@endsection
