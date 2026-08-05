@extends('layouts.app')
@section('titlepage', 'Broadcast Tagihan')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Broadcast Tagihan</h4>
            <small class="text-muted">Kirim pengingat tagihan piutang lewat jatuh tempo kepada pelanggan via WhatsApp.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-folder me-1"></i>Marketing</a>
                </li>
                <li class="breadcrumb-item active"><i class="ti ti-building-broadcast-tower me-1"></i>Broadcast Tagihan</li>
            </ol>
        </nav>
    </div>
@endsection

<div class="row">
    <div class="col-12">
        {{-- Card Filter --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header border-bottom py-3" style="background-color: #284c9a; border-radius: 0.375rem 0.375rem 0 0;">
                <h6 class="m-0 fw-bold text-white"><i class="ti ti-adjustments me-2"></i>Filter Tagihan Piutang</h6>
            </div>
            
            <div class="card-body py-4">
                <form action="{{ route('broadcasttagihan.index') }}" id="formSearch">
                    {{-- Baris Pertama: Bulan dan Tahun --}}
                    <div class="row g-3 mb-3">
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <select name="bulan" id="bulan" class="form-select">
                                <option value="">Pilih Bulan</option>
                                @for ($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ Request('bulan') == $i ? 'selected' : '' }}>{{ $list_bulan[$i] }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <select name="tahun" id="tahun" class="form-select">
                                <option value="">Pilih Tahun</option>
                                @for ($y = date('Y') + 1; $y >= $start_year; $y--)
                                    <option value="{{ $y }}" {{ Request('tahun') == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    
                    {{-- Baris Kedua: Sisa Filter --}}
                    <div class="row g-3 align-items-center">
                        @hasanyrole($roles_show_cabang)
                            <div class="col-lg-3 col-md-4 col-sm-12">
                                <x-select label="Pilih Cabang" name="kode_cabang" :data="$cabang" key="kode_cabang"
                                    textShow="nama_cabang" upperCase="true" selected="{{ Request('kode_cabang') }}"
                                    select2="select2Kodecabang" hideLabel="true" />
                            </div>
                        @endhasanyrole
                        <div class="col-lg-3 col-md-4 col-sm-12">
                            <select name="kode_salesman" id="kode_salesman" class="form-select">
                                <option value="">Semua Salesman</option>
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-12">
                            <x-input-with-icon label="Nama Pelanggan" value="{{ Request('nama_pelanggan') }}" name="nama_pelanggan"
                                icon="ti ti-user" hideLabel="true" placeholder="Nama Pelanggan" />
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-12">
                            <select name="status_jatuh_tempo" id="status_jatuh_tempo" class="form-select">
                                <option value="">Semua Status JT</option>
                                <option value="sudah" {{ Request('status_jatuh_tempo') == 'sudah' ? 'selected' : '' }}>Sudah JT</option>
                                <option value="belum" {{ Request('status_jatuh_tempo') == 'belum' ? 'selected' : '' }}>Belum JT</option>
                            </select>
                        </div>
                        <div class="col-lg-1 col-md-4 col-sm-12">
                            <button class="btn btn-primary w-100"><i class="ti ti-search me-1"></i> Cari</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Data Section (List Card) --}}
        @if ($is_filtered)
            <h5 class="fw-bold mb-3 text-dark"><i class="ti ti-list me-2"></i>Daftar Tagihan ({{ $data->count() }} Data)</h5>
            
            @forelse ($data as $d)
                @php
                    $is_past_due = $d->jatuh_tempo < $today;
                @endphp
                <div class="card mb-3 border-0 shadow-sm">
                    <div class="card-body py-3">
                        <div class="row align-items-center">
                            {{-- Faktur Info --}}
                            <div class="col-lg-2 col-md-6 col-sm-12 mb-2 mb-lg-0">
                                <div class="d-flex align-items-center">
                                    <div class="badge bg-label-primary p-2 me-3 rounded">
                                        <i class="ti ti-file-description fs-4"></i>
                                    </div>
                                    <div>
                                        <span class="d-block fw-bold text-dark">{{ $d->no_faktur }}</span>
                                        <small class="text-muted">Tgl: {{ date('d-m-Y', strtotime($d->tanggal)) }}</small>
                                    </div>
                                </div>
                            </div>
                            
                            {{-- Pelanggan Info --}}
                            <div class="col-lg-3 col-md-6 col-sm-12 mb-2 mb-lg-0">
                                <span class="d-block fw-bold text-dark">{{ $d->nama_pelanggan }}</span>
                                <small class="text-muted">{{ $d->kode_pelanggan }} • <i class="ti ti-phone fs-6"></i> {{ $d->no_hp_pelanggan ?: '-' }}</small>
                            </div>
                            
                            {{-- Cabang & Salesman --}}
                            <div class="col-lg-2 col-md-4 col-sm-12 mb-2 mb-lg-0">
                                <span class="d-block text-dark fw-semibold">{{ $d->nama_cabang }}</span>
                                <small class="text-muted"><i class="ti ti-user fs-6"></i> {{ $d->nama_salesman }}</small>
                            </div>
                            
                            {{-- Sisa Piutang & Jatuh Tempo --}}
                            <div class="col-lg-2 col-md-4 col-sm-12 mb-2 mb-lg-0">
                                <span class="d-block fw-bold text-danger fs-5">Rp {{ number_format($d->sisa_piutang, 0, ',', '.') }}</span>
                                <small class="text-muted">JT: {{ date('d-m-Y', strtotime($d->jatuh_tempo)) }}</small>
                            </div>
                            
                            {{-- Status Badge --}}
                            <div class="col-lg-2 col-md-4 col-sm-12 mb-3 mb-lg-0 text-lg-center">
                                @if ($is_past_due)
                                    <span class="badge bg-label-danger py-2 px-3">Lewat Jatuh Tempo</span>
                                @else
                                    <span class="badge bg-label-success py-2 px-3">Belum Jatuh Tempo</span>
                                @endif
                            </div>
                            
                            {{-- Action Button --}}
                            <div class="col-lg-1 col-md-12 col-sm-12 text-lg-end">
                                @if (empty($d->no_hp_pelanggan))
                                    <button class="btn btn-sm btn-secondary w-100" disabled title="No HP Kosong">
                                        <i class="ti ti-brand-whatsapp"></i> Kosong
                                    </button>
                                @else
                                    <button class="btn btn-sm btn-success btn-send-wa w-100" data-faktur="{{ $d->no_faktur }}">
                                        <i class="ti ti-brand-whatsapp me-1"></i> Kirim
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5 text-muted">
                        <i class="ti ti-mood-empty fs-1 d-block mb-2"></i>
                        Data tagihan piutang tidak ditemukan untuk filter ini.
                    </div>
                </div>
            @endforelse
        @else
            <div class="alert alert-info d-flex align-items-center" role="alert">
                <span class="alert-icon text-info me-2">
                    <i class="ti ti-info-circle ti-xs"></i>
                </span>
                <div>
                    <strong>Pemberitahuan:</strong> Silakan tentukan filter 
                    <strong>@hasanyrole($roles_show_cabang) Cabang, @endhasanyrole Bulan, dan Tahun</strong> 
                    terlebih dahulu untuk menampilkan data tagihan piutang.
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('myscript')
<script>
    $(function() {
        function getsalesmanbyCabang() {
            var kode_cabang = $("#kode_cabang").val();
            var kode_salesman = "{{ Request('kode_salesman') }}";
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
                    $("#kode_salesman").html('<option value="">Semua Salesman</option>' + respond);
                }
            });
        }

        getsalesmanbyCabang();
        $("#kode_cabang").change(function(e) {
            getsalesmanbyCabang();
        });

        // Handler kirim WA
        $(document).on('click', '.btn-send-wa', function(e) {
            e.preventDefault();
            var btn = $(this);
            var no_faktur = btn.data('faktur');

            Swal.fire({
                title: 'Apakah Anda Yakin?',
                text: "Kirim tagihan untuk faktur " + no_faktur + " ke WhatsApp pelanggan?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Kirim!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>...');
                    
                    $.ajax({
                        type: 'POST',
                        url: "{{ route('broadcasttagihan.send') }}",
                        data: {
                            _token: "{{ csrf_token() }}",
                            no_faktur: no_faktur
                        },
                        success: function(respond) {
                            btn.prop('disabled', false).html('<i class="ti ti-brand-whatsapp me-1"></i> Kirim');
                            if (respond.success) {
                                Swal.fire({
                                    title: 'Berhasil!',
                                    text: respond.message,
                                    icon: 'success',
                                    showConfirmButton: true
                                });
                            } else {
                                Swal.fire({
                                    title: 'Gagal!',
                                    text: respond.message,
                                    icon: 'error',
                                    showConfirmButton: true
                                });
                            }
                        },
                        error: function(xhr) {
                            btn.prop('disabled', false).html('<i class="ti ti-brand-whatsapp me-1"></i> Kirim');
                            var msg = 'Terjadi kesalahan pada server.';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                msg = xhr.responseJSON.message;
                            }
                            Swal.fire({
                                title: 'Gagal!',
                                text: msg,
                                icon: 'error',
                                showConfirmButton: true
                            });
                        }
                    });
                }
            });
        });
    });
</script>
@endpush
