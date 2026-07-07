<style>
    .welcome-card-premium {
        background: linear-gradient(135deg, #002e65 0%, #001a3d 100%);
        border-radius: 20px;
        position: relative;
        overflow: visible;
        color: #fff;
        padding: 1.5rem 2rem;
        margin-top: 3rem;
        margin-bottom: 2rem;
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }

    .welcome-card-premium::before {
        content: '';
        position: absolute;
        top: -10%;
        right: -5%;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(0, 210, 255, 0.1) 0%, transparent 70%);
        z-index: 0;
    }

    .welcome-content {
        position: relative;
        z-index: 2;
        max-width: 60%;
    }

    .welcome-illustration {
        position: absolute;
        right: 1.5rem;
        bottom: -2px;
        height: 140%;
        z-index: 1;
        opacity: 1;
        filter: drop-shadow(0 15px 25px rgba(0,0,0,0.4));
        -webkit-mask-image: linear-gradient(to bottom, black 80%, transparent 100%);
        mask-image: linear-gradient(to bottom, black 80%, transparent 100%);
    }

    .welcome-greeting {
        font-size: 1.85rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        letter-spacing: -0.5px;
        color: #fff !important;
    }

    .welcome-subtext {
        font-size: 0.95rem;
        color: rgba(255, 255, 255, 0.7);
        margin-bottom: 1rem;
        line-height: 1.4;
    }

    .welcome-date-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        padding: 0.4rem 0.85rem;
        border-radius: 50px;
        font-size: 0.8rem;
        border: 1px solid rgba(255, 255, 255, 0.1);
        margin-bottom: 1rem;
    }

    .welcome-role-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: #00d2ff;
        font-size: 0.9rem;
        font-weight: 600;
    }

    .welcome-role-badge i {
        font-size: 1.1rem;
    }

    @media (max-width: 991.98px) {
        .welcome-content {
            max-width: 100%;
            text-align: center;
        }
        .welcome-illustration {
            display: none;
        }
        .welcome-card-premium {
            padding: 2rem 1.5rem;
        }
    }
</style>

<div class="welcome-card-premium">
    <img src="{{ asset('karakter.png') }}" alt="Welcome" class="welcome-illustration">
    
    <div class="welcome-content">
        <div class="welcome-date-badge">
            <i class="ti ti-calendar"></i>
            <span>{{ date('l, d F Y') }}</span>
        </div>
        
        <h2 class="welcome-greeting">
            Selamat Datang, 
            @php
                $nameParts = explode(' ', trim(Auth::user()->name));
                echo count($nameParts) > 1 ? $nameParts[0] . ' ' . $nameParts[1] : $nameParts[0];
            @endphp! 🎉
        </h2>
        
        <p class="welcome-subtext">
            Siap untuk mengelola performa hari ini? Pantau metrik pemasaran Anda dan capai target lebih efisien.
        </p>
        
        <div class="welcome-role-badge">
            <i class="ti ti-shield"></i>
            <span>{{ strtolower($level_user) }}</span>
        </div>
    </div>
</div>

<!-- Modal Informasi Ganti Password Default -->
<div class="modal fade" id="defaultCredentialsModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-header bg-danger text-white py-3 border-0">
                <h5 class="modal-title text-white d-flex align-items-center gap-2 fw-bold" id="defaultCredentialsModalLabel">
                    <i class="ti ti-shield-lock" style="font-size: 1.5rem;"></i>
                    Pemberitahuan Keamanan Penting
                </h5>
            </div>
            <div class="modal-body p-4">
                <div class="text-center mb-3">
                    <div class="avatar avatar-xl bg-label-danger mb-2 mx-auto d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; border-radius: 50%; background-color: rgba(234, 84, 85, 0.16);">
                        <i class="ti ti-alert-triangle text-danger" style="font-size: 3rem;"></i>
                    </div>
                    <h4 class="fw-bold text-danger">Segera Ubah Kredensial Default!</h4>
                </div>
                
                <p class="text-secondary leading-relaxed mb-3">
                    Untuk menjaga keamanan data operasional perusahaan, seluruh pengguna diwajibkan untuk segera <strong>mengganti username dan password bawaan (default)</strong> masing-masing.
                </p>

                <!-- Cara Mengubah Password -->
                <div class="card bg-light border-0 mb-3" style="border-radius: 10px; background-color: #f8f9fa;">
                    <div class="card-body p-3">
                        <h6 class="fw-bold mb-2 text-dark"><i class="ti ti-list-numbers me-1"></i> Cara Mengubah Password:</h6>
                        <ol class="ps-3 mb-0 text-secondary" style="font-size: 0.9rem; line-height: 1.5;">
                            <li class="mb-1">Klik tombol <strong>Ubah Password Sekarang</strong> di bawah (atau akses menu profil di pojok kanan atas).</li>
                            <li class="mb-1">Masukkan password lama/default Anda saat ini.</li>
                            <li class="mb-1">Ketik password baru Anda yang aman dan sulit ditebak.</li>
                            <li>Klik tombol update untuk menyelesaikan pembaruan data.</li>
                        </ol>
                    </div>
                </div>
                
                <div class="alert alert-warning border-0 p-3 d-flex align-items-start gap-3" style="border-radius: 10px; background-color: rgba(255, 193, 7, 0.15);">
                    <i class="ti ti-info-circle text-warning mt-1" style="font-size: 1.3rem;"></i>
                    <div class="text-warning-dark" style="color: #664d03; font-size: 0.9rem;">
                        <strong>PENTING:</strong> Keamanan akun serta setiap aktivitas/transaksi yang terjadi menggunakan akun Anda sepenuhnya merupakan <strong>tanggung jawab masing-masing user</strong>. Jangan bagikan username atau password Anda kepada siapapun.
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 p-3 bg-light d-flex justify-content-between gap-2">
                <a href="{{ route('users.ubahpassword') }}" class="btn btn-outline-danger px-3 py-2 fw-bold flex-grow-1" style="border-radius: 8px;">
                    <i class="ti ti-key me-1"></i> Ubah Password Sekarang
                </a>
                <button type="button" class="btn btn-danger px-3 py-2 fw-bold flex-grow-1" id="btnUnderstandCredentials" style="border-radius: 8px;">
                    Saya Mengerti
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (!sessionStorage.getItem('dismissed_default_credentials_warning')) {
            var myModal = new bootstrap.Modal(document.getElementById('defaultCredentialsModal'), {
                keyboard: false,
                backdrop: 'static'
            });
            myModal.show();

            document.getElementById('btnUnderstandCredentials').addEventListener('click', function() {
                sessionStorage.setItem('dismissed_default_credentials_warning', 'true');
                myModal.hide();
            });
        }
    });
</script>
