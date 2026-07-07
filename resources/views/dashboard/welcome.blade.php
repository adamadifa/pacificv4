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
