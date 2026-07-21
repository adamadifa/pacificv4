@extends('layouts.app')
@section('titlepage', $artikel->judul)

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">{{ $artikel->judul }}</h4>
            <small class="text-muted">Kategori: <b>{{ $artikel->kategori }}</b></small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="{{ route('panduan.index') }}"><i class="ti ti-folder me-1"></i>Buku Panduan</a>
                </li>
                <li class="breadcrumb-item active"><i class="ti ti-file-text me-1"></i>Detail Panduan</li>
            </ol>
        </nav>
    </div>
@endsection

<div class="row">
    <!-- Main Content -->
    <div class="col-lg-8 col-md-12 col-sm-12 mb-4">
        
        <!-- Back Button -->
        <a href="{{ route('panduan.index') }}" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-1 mb-3">
            <i class="ti ti-arrow-left"></i> Kembali ke Daftar Panduan
        </a>

        <!-- Article Card -->
        <div class="card shadow-sm border">
            <div class="card-header py-3 d-flex align-items-center gap-3" style="background-color: #fafbfc; border-bottom: 1px solid #e2e8f0;">
                <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background-color: #eff6ff;">
                    <span class="fs-3">{{ $artikel->icon }}</span>
                </div>
                <div>
                    <h5 class="m-0 fw-bold text-dark fs-5">{{ $artikel->judul }}</h5>
                    <small class="text-muted">Terakhir diperbarui: {{ $artikel->updated_at->diffForHumans() }}</small>
                </div>
            </div>
            <div class="card-body p-4 text-dark text-break lh-lg" style="font-size: 10.5pt;">
                {!! $artikel->konten !!}
            </div>
        </div>

    </div>

    <!-- Right Sidebar (Related articles & Categories) -->
    <div class="col-lg-4 col-md-12 col-sm-12">
        
        <!-- Related Articles -->
        @if(!$related->isEmpty())
            <div class="card shadow-sm border mb-4">
                <div class="card-header py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                    <h6 class="m-0 fw-bold text-white d-flex align-items-center gap-2">
                        <i class="ti ti-list fs-4"></i>
                        <span>Panduan Terkait</span>
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @foreach($related as $rel)
                            <a href="{{ route('panduan.show', $rel->slug) }}" class="list-group-item list-group-item-action d-flex align-items-center gap-2 py-3 border-0 border-bottom">
                                <span class="fs-4">{{ $rel->icon }}</span>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0 fw-semibold text-dark small">{{ $rel->judul }}</h6>
                                </div>
                                <i class="ti ti-chevron-right text-muted small"></i>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- Categories Navigation List -->
        <div class="card shadow-sm border mb-4">
            <div class="card-header py-3" style="background-color: #fafbfc; border-bottom: 1px solid #e2e8f0;">
                <h6 class="m-0 fw-bold text-dark d-flex align-items-center gap-2">
                    <i class="ti ti-folder fs-4"></i>
                    <span>Kategori Panduan</span>
                </h6>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @foreach($allCategories as $cat)
                        <a href="{{ route('panduan.index', ['search' => $cat]) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-2_5 border-0 border-bottom">
                            <span class="small text-dark fw-semibold">📂 {{ $cat }}</span>
                            <i class="ti ti-chevron-right text-muted small"></i>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Chat Notification Help Card -->
        <div class="card shadow-sm border text-center p-4" style="background-color: #f8fafc;">
            <div class="fs-1 mb-2">💬</div>
            <h6 class="fw-bold text-dark mb-1">Butuh Bantuan Lain?</h6>
            <p class="text-muted small mb-3">Tanyakan langsung ke asisten bot kami di panel chat melayang untuk penjelasan detail fitur lainnya.</p>
            <button class="btn btn-primary btn-sm mx-auto d-flex align-items-center gap-1" onclick="document.getElementById('chat-widget-toggle').click()">
                <i class="ti ti-messages"></i> Tanya Asisten
            </button>
        </div>

    </div>
</div>
@endsection
