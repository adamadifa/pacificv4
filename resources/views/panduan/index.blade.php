@extends('layouts.app')
@section('titlepage', 'Buku Panduan & Bantuan')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Buku Panduan &amp; Bantuan</h4>
            <small class="text-muted">Temukan informasi langkah-langkah penggunaan fitur portal di sini.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-folder me-1"></i>Bantuan</a>
                </li>
                <li class="breadcrumb-item active"><i class="ti ti-book me-1"></i>Buku Panduan</li>
            </ol>
        </nav>
    </div>
@endsection

<div class="row">
    <!-- Main Content Area -->
    <div class="col-lg-8 col-md-12 col-sm-12">
        
        <!-- Search Card -->
        <div class="card shadow-sm border-0 mb-4 text-white" style="background: linear-gradient(135deg, #002e65 0%, #004d99 100%);">
            <div class="card-body p-4 text-center">
                <h5 class="fw-bold text-white mb-2 fs-4">Ada yang bisa kami bantu?</h5>
                <p class="text-white-50 small mb-4">Cari panduan penggunaan modul sistem di bawah ini.</p>
                <form action="{{ route('panduan.index') }}" method="GET" class="max-w-600 mx-auto">
                    <div class="input-group input-group-merge shadow-sm" style="border-radius: 50px; overflow: hidden;">
                        <span class="input-group-text bg-white border-0" id="basic-addon-search31"><i class="ti ti-search text-muted fs-4"></i></span>
                        <input type="text" class="form-control border-0 bg-white" name="search" value="{{ $search }}" placeholder="Ketik kata kunci (misal: BPB, Pembelian, dll)..." aria-label="Search" aria-describedby="basic-addon-search31" style="height: 46px;">
                        @if($search)
                            <a href="{{ route('panduan.index') }}" class="btn btn-light bg-white border-0 text-muted d-flex align-items-center justify-content-center px-3" title="Clear Search"><i class="ti ti-x"></i></a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        @if($kategoriGroup->isEmpty())
            <div class="card shadow-sm border text-center p-5 mb-4">
                <div class="fs-1 mb-3">🔍</div>
                <h6 class="fw-bold text-dark mb-1">Panduan Tidak Ditemukan</h6>
                <p class="text-muted small">Kata kunci <b>"{{ $search }}"</b> tidak cocok dengan artikel apa pun. Coba kata kunci lainnya atau gunakan Q&A Chat.</p>
                <a href="{{ route('panduan.index') }}" class="btn btn-primary btn-sm mx-auto" style="width: fit-content;">Lihat Semua Panduan</a>
            </div>
        @else
            <!-- Categories Grid -->
            @foreach($kategoriGroup as $kategori => $artikels)
                <div class="card shadow-sm border mb-4">
                    <div class="card-header py-3 d-flex align-items-center gap-2" style="background-color: #fafbfc; border-bottom: 1px solid #e2e8f0;">
                        <h6 class="m-0 fw-bold text-dark d-flex align-items-center gap-2">
                            <span class="fs-4">📂</span>
                            <span>Kategori: {{ $kategori }}</span>
                        </h6>
                        <span class="badge bg-secondary text-white rounded-pill ms-auto">{{ $artikels->count() }} Panduan</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            @foreach($artikels as $artikel)
                                <a href="{{ route('panduan.show', $artikel->slug) }}" class="list-group-item list-group-item-action d-flex align-items-center gap-3 py-3 border-0 border-bottom">
                                    <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; flex-shrink: 0; background-color: #eff6ff;">
                                        <span class="fs-4">{{ $artikel->icon }}</span>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1 fw-bold text-dark fs-6">{{ $artikel->judul }}</h6>
                                        <p class="mb-0 text-muted small">{{ $artikel->deskripsi_singkat }}</p>
                                    </div>
                                    <i class="ti ti-chevron-right text-muted fs-4"></i>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        @endif

    </div>

    <!-- Right Sidebar (FAQs & Quick Help) -->
    <div class="col-lg-4 col-md-12 col-sm-12">
        
        <!-- Quick Q&A Card -->
        <div class="card shadow-sm border mb-4">
            <div class="card-header py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                <h6 class="m-0 fw-bold text-white d-flex align-items-center gap-2">
                    <i class="ti ti-help fs-4"></i>
                    <span>Tanya Jawab Populer</span>
                </h6>
            </div>
            <div class="card-body p-3">
                <p class="text-muted small mb-3">Klik pada pertanyaan di bawah ini untuk melihat jawaban cepat.</p>
                <div class="accordion accordion-header-primary shadow-none border-0" id="accordionFaqs">
                    @foreach($faqs as $index => $faq)
                        <div class="accordion-item border mb-2" style="border-radius: 8px; overflow: hidden;">
                            <h2 class="accordion-header" id="heading-{{ $index }}">
                                <button class="accordion-button collapsed py-2_5 px-3 fw-semibold small text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $index }}" aria-expanded="false" aria-controls="collapse-{{ $index }}" style="font-size: 13px;">
                                    {{ $faq->pertanyaan }}
                                </button>
                            </h2>
                            <div id="collapse-{{ $index }}" class="accordion-collapse collapse" aria-labelledby="heading-{{ $index }}" data-bs-parent="#accordionFaqs">
                                <div class="accordion-body p-3 bg-light text-muted small" style="line-height: 1.6; border-top: 1px solid #e2e8f0;">
                                    {!! $faq->jawaban !!}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Chat Notification Help Card -->
        <div class="card shadow-sm border text-center p-4" style="background-color: #f8fafc;">
            <div class="fs-1 mb-2">💬</div>
            <h6 class="fw-bold text-dark mb-1">Masih Bingung?</h6>
            <p class="text-muted small mb-3">Gunakan <b>Q&A Assistant Chat</b> di pojok kanan bawah halaman untuk bertanya langsung kepada bot asisten kami secara interaktif.</p>
            <button class="btn btn-primary btn-sm mx-auto d-flex align-items-center gap-1" onclick="document.getElementById('chat-widget-toggle').click()">
                <i class="ti ti-messages"></i> Mulai Bertanya
            </button>
        </div>

    </div>
</div>
@endsection
