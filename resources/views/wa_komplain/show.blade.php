@extends('layouts.app')
@section('titlepage', 'Detail Komplain WA')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0 fw-bold text-dark">Detail Komplain #{{ $komplain->no_komplain }}</h4>
            <small class="text-muted">Kelola status dan tanggapan komplain pelanggan dari WhatsApp.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size:13px">
                <li class="breadcrumb-item"><a href="{{ route('wa-komplain.index') }}">Komplain WA</a></li>
                <li class="breadcrumb-item active">Detail</li>
            </ol>
        </nav>
    </div>
@endsection

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row mt-4">
    <!-- Left Column: Complaint details & chat history -->
    <div class="col-md-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="card-title mb-0 fw-bold text-dark"><i class="ti ti-file-text me-2"></i>Informasi Komplain</h5>
            </div>
            <div class="card-body py-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <small class="text-muted d-block">Nama Pelanggan</small>
                        <strong class="text-dark">{{ $komplain->nama_pelanggan }}</strong>
                        @if($komplain->kode_pelanggan)
                            <span class="badge bg-label-info ms-1">{{ $komplain->kode_pelanggan }}</span>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">Nomor WhatsApp</small>
                        <strong class="text-dark">{{ $komplain->wa_number }}</strong>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">Kategori AI</small>
                        <span class="badge bg-label-secondary text-uppercase">{{ $komplain->kategori_ai ?? '-' }}</span>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">Tanggal Masuk</small>
                        <strong class="text-dark">{{ $komplain->tanggal_komplain->format('d-m-Y') }}</strong>
                    </div>
                    <div class="col-12 mt-4">
                        <small class="text-muted d-block mb-1">Isi Komplain Original</small>
                        <div class="bg-light p-3 rounded text-dark" style="white-space: pre-wrap;">{{ $komplain->isi_komplain }}</div>
                    </div>
                    @if($komplain->ringkasan_ai)
                        <div class="col-12 mt-3">
                            <small class="text-muted d-block mb-1">Ringkasan AI (Gemini)</small>
                            <div class="bg-label-info p-3 rounded" style="white-space: pre-wrap;">{{ $komplain->ringkasan_ai }}</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        @if(!empty($komplain->chat_history))
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="card-title mb-0 fw-bold text-dark"><i class="ti ti-brand-whatsapp me-2"></i>Riwayat Percakapan WA</h5>
                </div>
                <div class="card-body py-4" style="max-height: 400px; overflow-y: auto;">
                    <div class="d-flex flex-column gap-3">
                        @foreach($komplain->chat_history as $chat)
                            @php
                                $isFromMe = isset($chat['fromMe']) && $chat['fromMe'];
                            @endphp
                            <div class="d-flex {{ $isFromMe ? 'justify-content-end' : 'justify-content-start' }}">
                                <div class="p-3 rounded-3 {{ $isFromMe ? 'bg-primary text-white' : 'bg-light text-dark' }}" style="max-width: 75%; white-space: pre-wrap;">
                                    <div class="small fw-semibold mb-1" style="font-size:10px;">{{ $isFromMe ? 'Gemini AI' : $komplain->nama_pelanggan }}</div>
                                    <div>{{ $chat['message'] ?? $chat['text'] ?? '' }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Right Column: Status updates & assignment -->
    <div class="col-md-4">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="card-title mb-0 fw-bold text-dark"><i class="ti ti-settings me-2"></i>Tindakan CS</h5>
            </div>
            <div class="card-body py-4">
                <form action="{{ route('wa-komplain.status', $komplain->id) }}" method="POST" class="mb-4">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label text-muted small">Update Status</label>
                        <select name="status" class="form-select">
                            <option value="baru" {{ $komplain->status == 'baru' ? 'selected' : '' }}>Baru</option>
                            <option value="diproses" {{ $komplain->status == 'diproses' ? 'selected' : '' }}>Diproses</option>
                            <option value="selesai" {{ $komplain->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="ditolak" {{ $komplain->status == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small">Catatan Penyelesaian / Tindakan</label>
                        <textarea name="catatan_cs" class="form-control" rows="4" placeholder="Tuliskan catatan penanganan di sini...">{{ $komplain->catatan_cs }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100"><i class="ti ti-device-floppy me-1"></i> Simpan Status</button>
                </form>

                <hr class="my-4">

                <form action="{{ route('wa-komplain.assign', $komplain->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label text-muted small">Tugaskan ke CS Agen</label>
                        <select name="user_id" class="form-select">
                            <option value="">Pilih Agen</option>
                            @foreach($csUsers as $user)
                                <option value="{{ $user->id }}" {{ $komplain->ditangani_oleh == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-outline-secondary w-100"><i class="ti ti-user-check me-1"></i> Tugaskan Agen</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
