@extends('layouts.app')
@section('titlepage', 'Monitoring Program')

@section('content')
@section('navigasi')
    <span>Monitoring Program</span>
@endsection
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="nav-align-top nav-tabs-shadow mb-4">
            @include('layouts.navigation_monitoringprogram')
            <div class="tab-content">
                <div class="tab-pane fade active show" id="navs-justified-home" role="tabpanel">
                    <div class="d-flex justify-content-end">
                        <form action="{{ route('monitoringprogram.cetak') }}" method="GET" id="formCetak" target="_blank">
                            <input type="hidden" name="tahun" id='tahun_cetak' value="{{ Request('tahun') }}" />
                            <input type="hidden" name="bulan" id="bulan_cetak" value="{{ Request('bulan') }}" />
                            <input type="hidden" name="kode_cabang" id="kode_cabang_cetak" value="{{ Request('kode_cabang') }}" />
                            <input type="hidden" name="kode_program" id="kode_program_cetak" value="{{ Request('kode_program') }}" />
                            <button class="btn btn-primary"><i class="ti ti-printer me-1"></i>Cetak</button>
                            <button class="btn btn-success" name="exportButton"><i class="ti ti-download me-1"></i>Export Excel</button>
                        </form>
                    </div>
                    <div class="row mt-2">
                        <div class="col-12">
                            <form action="{{ route('monitoringprogram.index') }}">
                                @hasanyrole($roles_show_cabang)
                                    <div class="form-group mb-3">
                                        <select name="kode_cabang" id="kode_cabang" class="form-select select2Kodecabang">
                                            <option value="">Semua Cabang</option>
                                            @foreach ($cabang as $d)
                                                <option {{ Request('kode_cabang') == $d->kode_cabang ? 'selected' : '' }} value="{{ $d->kode_cabang }}">
                                                    {{ textUpperCase($d->nama_cabang) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @else
                                    <input type="hidden" name="kode_cabang" value="{{ Auth::user()->kode_cabang }}" id="kode_cabang_search">
                                @endrole
                                <x-select label="Pilih Program" name="kode_program" :data="$programikatan" key="kode_program" textShow="nama_program"
                                    select2="select2Kodeprogram" upperCase="true" selected="{{ Request('kode_program') }}" />
                                <div class="row">
                                    <div class="col-lg-8 col-md-12 col-sm-12">
                                        <div class="form-group">
                                            <select name="bulan" id="bulan" class="form-select">
                                                <option value="">Bulan</option>
                                                @foreach ($list_bulan as $d)
                                                    <option {{ Request('bulan') == $d['kode_bulan'] ? 'selected' : '' }}
                                                        value="{{ $d['kode_bulan'] }}">{{ $d['nama_bulan'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                    </div>
                                    <div class="col-lg-4 col-md-12 col-sm-12">
                                        <div class="form-group">
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
                                <x-input-with-icon icon="ti ti-user" label="Nama Pelanggan" name="nama_pelanggan"
                                    value="{{ Request('nama_pelanggan') }}" />
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="form-group mb-3">
                                            <button class="btn btn-primary w-100"><i class="ti ti-heart-rate-monitor me-1"></i>Tampilkan
                                                Data</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive mb-2">
                                <table id="example" class="display nowrap table  table-bordered" style="width:100%">
                                    <thead class="table-dark">
                                        <tr>
                                            <th rowspan="2">No.</th>
                                            <th rowspan="2">Kode</th>
                                            <th rowspan="2">Nama Pelanggan</th>
                                            <th rowspan="2">Salesman</th>
                                            <th rowspan="2">Wilayah</th>
                                            <th rowspan="2" class="text-center">Target</th>
                                            <th class="text-center" colspan="3">Realisasi</th>
                                            <th colspan="3" class="text-center">Reward</th>
                                            <th rowspan="2">#</th>
                                        </tr>
                                        <tr>
                                            <th>Tunai</th>
                                            <th>Kredit</th>
                                            <th>Total</th>
                                            <th>Tunai</th>
                                            <th>Kredit</th>
                                            <th>Total</th>
                                        </tr>

                                    </thead>
                                    <tbody>
                                        @php
                                            $total_reward = 0;
                                            $color_reward = '';
                                            $status = 0;
                                        @endphp
                                        @foreach ($peserta as $d)
                                            @php
                                                $color_reward = $d->jml_dus >= $d->qty_target ? 'bg-success text-white' : 'bg-danger text-white';
                                                if ($d->jml_dus >= $d->qty_target) {
                                                    //$reward = $d->reward * $d->jml_dus;
                                                    $bb_dep = ['PRIK004', 'PRIK001'];
                                                    $reward_tunai = in_array($d->kode_program, $bb_dep)
                                                        ? ($d->budget_rsm + $d->budget_gm) * $d->jml_tunai
                                                        : $d->reward * $d->jml_tunai;
                                                    $reward_kredit = $d->reward * $d->jml_kredit;
                                                    $reward = $reward_tunai + $reward_kredit;
                                                } else {
                                                    $reward_tunai = 0;
                                                    $reward_kredit = 0;
                                                    $reward = 0;
                                                }
                                                $total_reward += $reward;
                                                $status = $reward == 0 ? 0 : 1;
                                            @endphp

                                            <tr class=" {{ $color_reward }}">
                                                <td>{{ $loop->iteration }} {{ $d->kode_program }}</td>
                                                <td>
                                                    <input type="hidden" name="kode_pelanggan[{{ $loop->index }}]"
                                                        value="{{ $d->kode_pelanggan }}">
                                                    <input type="hidden" name="status[{{ $loop->index }}]" value="{{ $status }}">
                                                    {{ $d->kode_pelanggan }}
                                                </td>
                                                <td>{{ $d->nama_pelanggan }}</td>
                                                <td>{{ $d->nama_salesman }}</td>
                                                <td>{{ $d->nama_wilayah }}</td>
                                                <td class="text-center">
                                                    {{ formatAngka($d->qty_target) }}
                                                </td>
                                                <td class="text-end">
                                                    {{-- <input type="hidden" name="jumlah[{{ $loop->index }}]" value="{{ $d->jml_dus }}">
                                                    {{ formatAngka($d->jml_dus) }} --}}

                                                    <input type="hidden" name="qty_tunai[{{ $loop->index }}]" value="{{ $d->jml_tunai }}">
                                                    {{ formatAngka($d->jml_tunai) }}
                                                </td>
                                                <td class="text-end">
                                                    {{-- <input type="hidden" name="jumlah[{{ $loop->index }}]" value="{{ $d->jml_dus }}">
                                                    {{ formatAngka($d->jml_dus) }} --}}

                                                    <input type="hidden" name="qty_kredit[{{ $loop->index }}]" value="{{ $d->jml_kredit }}">
                                                    {{ formatAngka($d->jml_kredit) }}
                                                </td>
                                                <td class="text-end">
                                                    <input type="hidden" name="jumlah[{{ $loop->index }}]" value="{{ $d->jml_dus }}">
                                                    {{ formatAngka($d->jml_dus) }}
                                                </td>
                                                <td class="text-end">
                                                    <input type="hidden" name="reward_tunai[{{ $loop->index }}]" value="{{ $reward_tunai }}">
                                                    {{ formatAngka($reward_tunai) }}
                                                </td>
                                                <td class="text-end">
                                                    <input type="hidden" name="reward_kredit[{{ $loop->index }}]" value="{{ $reward_kredit }}">
                                                    {{ formatAngka($reward_kredit) }}
                                                </td>
                                                <td class="text-end">
                                                    <input type="hidden" name="total_reward[{{ $loop->index }}]" value="{{ $reward }}">
                                                    {{ formatAngka($reward) }}
                                                </td>
                                                <td>
                                                    <a href="#" class="btnDetailfaktur" kode_pelanggan="{{ $d->kode_pelanggan }}"
                                                        bulan="{{ Request('bulan') }}" tahun="{{ Request('tahun') }}">
                                                        <i class="ti ti-file-description text-primary"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div style="float: right;">
                                {{-- {{ $barangmasuk->links() }} --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<x-modal-form id="modal" size="modal-xl" show="loadmodal" title="" />
<x-modal-form id="modalDetailfaktur" size="modal-xl" show="loadmodaldetailfaktur" title="" />
@endsection
@push('myscript')
<script>
    $(function() {
        $(document).on('click', '.btnDetailfaktur', function(e) {
            e.preventDefault();
            let kode_pelanggan = $(this).attr('kode_pelanggan');
            let bulan = "{{ Request('bulan') }}";
            let tahun = "{{ Request('tahun') }}";
            let kode_program = "{{ Request('kode_program') }}"
            $("#modalDetailfaktur").modal("show");
            $("#modalDetailfaktur").find(".modal-title").text('Detail Faktur');
            $("#modalDetailfaktur").find("#loadmodaldetailfaktur").load(
                `/monitoringprogram/${kode_pelanggan}/${kode_program}/${bulan}/${tahun}/detailfaktur`
            );
        });

        $("#formCetak").submit(function(e) {
            var tahun = $("#tahun_cetak").val();
            var bulan = $("#bulan_cetak").val();
            var kode_cabang = $("#kode_cabang_cetak").val();
            var kode_program = $("#kode_program_cetak").val();

            if (tahun == "" || bulan == "" || kode_program == "" || kode_cabang == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Silahkan Pilih Periode, Program dan Cabang Terlebih Dahulu!",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#kode_program").focus();
                    },
                });
                return false;
            }
        });
    });
</script>
@endpush
