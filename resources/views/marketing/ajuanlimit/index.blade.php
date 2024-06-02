@extends('layouts.app')
@section('titlepage', 'Ajuan Limit Kredit')

@section('content')
@section('navigasi')
   <span>Ajuan Limit Kredit</span>
@endsection
<div class="row">
   <div class="col-lg-12">
      <div class="nav-align-top nav-tabs-shadow mb-4">
         @include('layouts.navigation_ajuanmarketing')
         <div class="tab-content">
            <div class="tab-pane fade active show" id="navs-justified-home" role="tabpanel">
               @can('ajuanlimit.create')
                  <a href="#" class="btn btn-primary" id="btnCreate"><i class="fa fa-plus me-2"></i>
                     Ajukan Limit Kredit
                  </a>
               @endcan
               <div class="row mt-2">
                  <div class="col-12">
                     <form action="{{ route('ajuanlimit.index') }}">

                     </form>
                  </div>
               </div>
               <div class="row">
                  <div class="col-12">
                     <div class="table-responsive mb-2">
                        <table class="table table-striped table-hover table-bordered">
                           <thead class="table-dark">
                              <tr>
                                 <th>No. Pengajuan</th>
                                 <th>Tanggal</th>
                                 <th>Pelanggan</th>
                                 <th>Salesman</th>
                                 <th>Jumlah</th>
                                 <th>LJT</th>
                                 <th>Penyesuaian</th>
                                 <th>Skor</th>
                                 <th>Ket</th>
                                 <th>Posisi Ajuan</th>
                                 <th>Status</th>
                                 <th>#</th>
                              </tr>
                           </thead>
                           <tbody>

                           </tbody>
                        </table>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<x-modal-form id="modal" size="modal-lg" show="loadmodal" title="" />
<div class="modal fade" id="modalPelanggan" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
   aria-hidden="true">
   <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title" id="myModalLabel18">Data Pelanggan</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body">
            <div class="table-responsive">
               <table class="table" id="tabelpelanggan" width="100%">
                  <thead class="table-dark">
                     <tr>
                        <th>No.</th>
                        <th>Kode</th>
                        <th>Nama Pelanggan</th>
                        <th>Salesman</th>
                        <th>Wilayah</th>
                        <th>Status</th>
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
@push('myscript')
<script>
   $(function() {
      $("#btnCreate").click(function(e) {
         e.preventDefault();
         $("#modal").modal("show");
         $("#modal").find(".modal-title").text("Buan Ajuan Limit Kredit");
         $("#loadmodal").load(`/ajuanlimit/create`);
      });

      $(document).on('click', '#kode_pelanggan_search', function(e) {
         $("#modalPelanggan").modal("show");
      });

      $('#tabelpelanggan').DataTable({
         processing: true,
         serverSide: true,
         order: [
            [2, 'asc']
         ],
         ajax: "{{ route('pelanggan.getpelangganjson') }}",
         bAutoWidth: false,
         columns: [{
               data: 'DT_RowIndex',
               name: 'DT_RowIndex',
               orderable: false,
               searchable: false,
               width: '5%'
            },
            {
               data: 'kode_pelanggan',
               name: 'kode_pelanggan',
               orderable: true,
               searchable: true,
               width: '10%'
            },
            {
               data: 'nama_pelanggan',
               name: 'nama_pelanggan',
               orderable: true,
               searchable: true,
               width: '30%'
            },
            {
               data: 'nama_salesman',
               name: 'nama_salesman',
               orderable: true,
               searchable: false,
               width: '20%'
            },

            {
               data: 'nama_wilayah',
               name: 'nama_wilayah',
               orderable: true,
               searchable: false,
               width: '30%'
            },
            {
               data: 'status_pelanggan',
               name: 'status_pelanggan',
               orderable: true,
               searchable: false,
               width: '30%'
            },
            {
               data: 'action',
               name: 'action',
               orderable: false,
               searchable: false,
               width: '5%'
            }
         ],

         rowCallback: function(row, data, index) {
            if (data.status_pelanggan == "NonAktif") {
               $("td", row).addClass("bg-danger text-white");
            }
         }
      });






   });
</script>
@endpush
