@extends('layouts.app')
@section('titlepage', 'Customer Service WA AI')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0 fw-bold text-dark">Komplain Pelanggan WA</h4>
            <small class="text-muted">Manajemen pengaduan pelanggan otomatis yang masuk melalui WhatsApp + Gemini AI.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size:13px">
                <li class="breadcrumb-item"><a href="#"><i class="ti ti-folder me-1"></i>CS AI</a></li>
                <li class="breadcrumb-item active">Komplain</li>
            </ol>
        </nav>
    </div>
@endsection

<div class="row mt-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 pt-4">
                <form action="{{ route('wa-komplain.index') }}" method="GET">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="ti ti-search text-muted"></i></span>
                                <input type="text" name="keyword" class="form-control bg-light border-0" placeholder="Cari komplain, nama, nomor WA..." value="{{ request('keyword') }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select name="status" class="form-select bg-light border-0">
                                <option value="">Semua Status</option>
                                <option value="baru" {{ request('status') == 'baru' ? 'selected' : '' }}>Baru</option>
                                <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Diproses</option>
                                <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="kode_cabang" class="form-select bg-light border-0">
                                <option value="">Semua Cabang</option>
                                @foreach($cabangs as $c)
                                    <option value="{{ $c->kode_cabang }}" {{ request('kode_cabang') == $c->kode_cabang ? 'selected' : '' }}>{{ $c->nama_cabang }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100"><i class="ti ti-filter me-1"></i> Filter</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>No. Komplain</th>
                                <th>Tanggal</th>
                                <th>Pelanggan</th>
                                <th>WA Number</th>
                                <th>Cabang</th>
                                <th>Kategori (AI)</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($komplains as $k)
                                <tr>
                                    <td><strong>{{ $k->no_komplain }}</strong></td>
                                    <td>{{ $k->tanggal_komplain->format('d-m-Y') }}</td>
                                    <td>
                                        <div>{{ $k->nama_pelanggan }}</div>
                                        @if($k->kode_pelanggan)
                                            <span class="badge bg-label-info" style="font-size:10px">{{ $k->kode_pelanggan }}</span>
                                        @else
                                            <span class="badge bg-label-warning" style="font-size:10px">Belum Terdaftar</span>
                                        @endif
                                    </td>
                                    <td>{{ $k->wa_number }}</td>
                                    <td>{{ $k->cabang ? $k->cabang->nama_cabang : '-' }}</td>
                                    <td><span class="badge bg-label-secondary text-uppercase">{{ $k->kategori_ai ?? '-' }}</span></td>
                                    <td>
                                        @if($k->status == 'baru')
                                            <span class="badge bg-label-danger">Baru</span>
                                        @elseif($k->status == 'diproses')
                                            <span class="badge bg-label-warning">Diproses</span>
                                        @elseif($k->status == 'selesai')
                                            <span class="badge bg-label-success">Selesai</span>
                                        @else
                                            <span class="badge bg-label-secondary">Ditolak</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('wa-komplain.show', $k->id) }}" class="btn btn-sm btn-icon btn-label-primary">
                                            <i class="ti ti-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <div class="text-muted">Tidak ada data komplain ditemukan.</div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-end mt-4">
                    {{ $komplains->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
