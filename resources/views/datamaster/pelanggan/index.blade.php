@extends('layouts.app')
@section('titlepage', 'Pelanggan')

@section('content')
@section('navigasi')
    <span>Pelanggan</span>
@endsection
<div class="row">
    <div class="col-xl-4 mb-4 col-lg-5 col-12">
        <div class="card">
            <div class="d-flex align-items-end row">
                <div class="col-7">
                    <div class="card-body text-nowrap">
                        <h5 class="card-title mb-0">Database Pelanggan! 🎉</h5>
                        <p class="mb-2">Jumlah Database Pelanggan</p>
                        <h4 class="text-primary mb-1">{{ formatRupiah($jmlpelanggan) }}</h4>
                    </div>
                </div>
                <div class="col-5 text-center text-sm-left">
                    <div class="card-body pb-0 px-0 px-md-4">
                        <img src="{{ asset('assets/img/illustrations/card-advance-sale.png') }}" height="140" alt="view sales">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 mb-4 col-lg-5 col-12">
        <div class="card">
            <div class="d-flex align-items-end row">
                <div class="col-7">
                    <div class="card-body text-nowrap">
                        <h5 class="card-title mb-0">Pelanggan Aktif! 🎉</h5>
                        <p class="mb-2">Jumlah Database Pelanggan Aktif</p>
                        <h4 class="text-success mb-1">{{ formatRupiah($jmlpelangganaktif) }}</h4>
                    </div>
                </div>
                <div class="col-5 text-center text-sm-left">
                    <div class="card-body pb-0 px-0 px-md-4">
                        <img src="{{ asset('assets/img/illustrations/girl-with-laptop.png') }}" height="140" alt="view sales">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 mb-4 col-lg-5 col-12">
        <div class="card">
            <div class="d-flex align-items-end row">
                <div class="col-7">
                    <div class="card-body text-nowrap">
                        <h5 class="card-title mb-0">Pelanggan Non Aktif! 🎉</h5>
                        <p class="mb-2">Jumlah Database Pelanggan Non Aktif</p>
                        <h4 class="text-danger mb-1">{{ formatRupiah($jmlpelanggannonaktif) }}</h4>
                    </div>
                </div>
                <div class="col-5 text-center text-sm-left">
                    <div class="card-body pb-0 px-0 px-md-4">
                        <img src="{{ asset('assets/img/illustrations/inactive-customer.png') }}" height="140" alt="view sales">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    @can('pelanggan.create')
                        <a href="#" class="btn btn-primary" id="btncreatePelanggan"><i class="fa fa-plus me-2"></i>
                            Tambah
                            Pelanggan</a>
                    @endcan
                    @can('pelanggan.show')
                        <form action="/pelanggan/export" method="GET" id="formCetak" target="_blank">
                            <input type="hidden" name="dari" id='dari_cetak' value="{{ Request('dari') }}" />
                            <input type="hidden" name="sampai" id="sampai_cetak" value="{{ Request('sampai') }}" />
                            <input type="hidden" name="kode_cabang" id="kode_cabang_cetak" value="{{ Request('kode_cabang') }}" />
                            <input type="hidden" name="kode_salesman" id="kode_salesman_cetak" value="{{ Request('kode_salesman') }}" />
                            <input type="hidden" name="status" id="status_cetak" value="{{ Request('status') }}" />
                            <button class="btn btn-primary"><i class="ti ti-printer me-1"></i>Cetak</button>
                            <button class="btn btn-success" name="exportButton"><i class="ti ti-download me-1"></i>Export
                                Excel</button>
                            <a href="#" class="btn btn-danger" id="btnNonaktif"><i class="ti ti-user-x me-1"></i>Nonaktifkan Pelanggan</a>
                        </form>
                    @endcan


                </div>
                {{-- @can('pelanggan.create')
                    <a href="#" class="btn btn-primary" id="btncreatePelanggan"><i class="fa fa-plus me-2"></i> Tambah
                        Pelanggan</a>
                @endcan --}}
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <form action="{{ route('pelanggan.index') }}">
                            <div class="row">
                                <div class="col">
                                    <div class="row">
                                        <div class="col-lg-6 col-sm-12 col-md-12">
                                            <x-input-with-icon label="Dari" value="{{ Request('dari') }}" name="dari" icon="ti ti-calendar"
                                                datepicker="flatpickr-date" />
                                        </div>
                                        <div class="col-lg-6 col-sm-12 col-md-12">
                                            <x-input-with-icon label="Sampai" value="{{ Request('sampai') }}" name="sampai" icon="ti ti-calendar"
                                                datepicker="flatpickr-date" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col">
                                    <select name="status" id="status" class="form-select">
                                        <option value="">Status</option>
                                        <option value="aktif" {{ Request('status') == 'aktif' ? 'selected' : '' }}>
                                            Aktif</option>
                                        <option value="nonaktif" {{ Request('status') == 'nonaktif' ? 'selected' : '' }}>Non Aktif</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                @hasanyrole($roles_show_cabang)
                                    <div class="col-lg-2 col-sm-12 col-md-12">
                                        <x-select label="Cabang" name="kode_cabang" :data="$cabang" key="kode_cabang" textShow="nama_cabang"
                                            selected="{{ Request('kode_cabang') }}" />
                                    </div>
                                @endhasanyrole
                                <div class="col-lg-2 col-sm-12 col-md-12">
                                    <div class="form-group mb-3">
                                        <select name="kode_salesman" id="kode_salesman" class="form-select">
                                            <option value="">Salesman</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-12 col-md-12">
                                    <x-input-with-icon label="Kode Pelanggan" value="{{ Request('kode_pelanggan') }}" name="kode_pelanggan"
                                        icon="ti ti-barcode" />
                                </div>
                                <div class="col-lg-3 col-sm-12 col-md-12">
                                    <x-input-with-icon label="Cari Nama Pelanggan" value="{{ Request('nama_pelanggan') }}" name="nama_pelanggan"
                                        icon="ti ti-user" />
                                </div>

                                <div class="col-lg-2 col-sm-12 col-md-12">
                                    <button class="btn btn-primary"><i class="ti ti-icons ti-search me-1"></i>Cari</button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive mb-2">
                            <table class="table">
                                <thead class="table-dark">
                                    <tr>
                                        <th class="text-center" style="width: 1%">No.</th>
                                        <th>Kode</th>
                                        <th>Nama Pelanggan</th>
                                        <th>Wilayah/Rute</th>
                                        <th>Limit</th>
                                        <th>Foto</th>
                                        <th>Salesman</th>
                                        <th>Cabang</th>
                                        <th>Tgl Register</th>
                                        <th>Status</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pelanggan as $d)
                                        <tr>
                                            <td class="text-center">
                                                {{ $loop->iteration + $pelanggan->firstItem() - 1 }}
                                            </td>
                                            <td>{{ $d->kode_pelanggan }}</td>
                                            <td>{{ textCamelCase($d->nama_pelanggan) }}</td>
                                            <td>{{ textCamelCase($d->nama_wilayah) }}</td>
                                            <td class="text-end">
                                                @if (empty($d->limit_pelanggan))
                                                    <i class="ti ti-clipboard-x text-danger"></i>
                                                @else
                                                    {{ formatRupiah($d->limit_pelanggan) }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (!empty($d->foto))
                                                    @if (Storage::disk('public')->exists('/pelanggan/' . $d->foto))
                                                        <div class="avatar avatar-xs me-2">
                                                            <img src="{{ getfotoPelanggan($d->foto) }}" alt="" class="rounded-circle">
                                                        </div>
                                                    @else
                                                        <div class="avatar avatar-xs me-2">
                                                            <img src="{{ asset('assets/img/avatars/No_Image_Available.jpg') }}" alt=""
                                                                class="rounded-circle">
                                                        </div>
                                                    @endif
                                                @else
                                                    <div class="avatar avatar-xs me-2">
                                                        <img src="{{ asset('assets/img/avatars/No_Image_Available.jpg') }}" alt=""
                                                            class="rounded-circle">
                                                    </div>
                                                @endif

                                            </td>
                                            <td>{{ textCamelCase($d->nama_salesman) }}</td>
                                            <td>{{ $d->kode_cabang }}</td>
                                            <td>{{ date('d-m-Y', strtotime($d->tanggal_register)) }}</td>
                                            <td>
                                                @if ($d->status_aktif_pelanggan == 1)
                                                    <span class="badge bg-success">Aktif</span>
                                                @else
                                                    <span class="badge bg-danger">Nonaktif</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    @can('pelanggan.edit')
                                                        <div>
                                                            <a href="#" class="me-2 editPelanggan"
                                                                kode_pelanggan="{{ Crypt::encrypt($d->kode_pelanggan) }}">
                                                                <i class="ti ti-edit text-success"></i>
                                                            </a>
                                                        </div>
                                                    @endcan
                                                    @can('pelanggan.show')
                                                        <div>
                                                            <a href="{{ route('pelanggan.show', Crypt::encrypt($d->kode_pelanggan)) }}" class="me-2">
                                                                <i class="ti ti-file-description text-info"></i>
                                                            </a>
                                                        </div>
                                                    @endcan
                                                    @can('pelanggan.delete')
                                                        <div>
                                                            <form method="POST" name="deleteform" class="deleteform"
                                                                action="{{ route('pelanggan.delete', Crypt::encrypt($d->kode_pelanggan)) }}">
                                                                @csrf
                                                                @method('DELETE')
                                                                <a href="#" class="delete-confirm ml-1">
                                                                    <i class="ti ti-trash text-danger"></i>
                                                                </a>
                                                            </form>
                                                        </div>
                                                    @endcan

                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div style="float: right;">
                            {{ $pelanggan->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<x-modal-form id="mdlcreatePelanggan" size="modal-lg" show="loadcreatePelanggan" title="Tambah Pelanggan" />
<x-modal-form id="mdleditPelanggan" size="modal-lg" show="loadeditPelanggan" title="Edit Pelanggan" />
<x-modal-form id="mdlNonaktifPelanggan" size="modal-xl" show="loadNonaktifPelanggan" title="Nonaktifkan Pelanggan" />
@endsection
@push('myscript')
{{-- <script src="{{ asset('assets/js/pages/roles/create.js') }}"></script> --}}
<script>
    $(function() {
        $("#btncreatePelanggan").click(function(e) {
            e.preventDefault();
            $('#mdlcreatePelanggan').modal("show");
            $("#loadcreatePelanggan").load('/pelanggan/create');
        });

        $("#btnNonaktif").click(function(e) {
            e.preventDefault();
            $('#mdlNonaktifPelanggan').modal("show");
            $("#loadNonaktifPelanggan").html(`
            <div class="sk-wave sk-primary" style="margin:auto">
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            </div>
            `);
            $("#loadNonaktifPelanggan").load('/pelanggan/nonaktif');
        });

        $(".editPelanggan").click(function(e) {
            var kode_pelanggan = $(this).attr("kode_pelanggan");
            e.preventDefault();
            $('#mdleditPelanggan').modal("show");
            $("#loadeditPelanggan").load('/pelanggan/' + kode_pelanggan + '/edit');
        });

        function getsalesmanbyCabang() {
            var kode_cabang = $("#kode_cabang").val();
            var kode_salesman = "{{ Request('kode_salesman') }}";
            //alert(selected);
            $.ajax({
                type: 'POST',
                url: '/salesman/getsalesmanbycabang',
                data: {
                    _token: "{{ csrf_token() }}",
                    kode_cabang: kode_cabang,
                    kode_salesman: kode_salesman
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
    });
</script>
@endpush
