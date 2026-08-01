<style>
    .welcome-card-premium {
        background: linear-gradient(135deg, #284c9a 0%, #162a5b 100%);
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

    .welcome-waves {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 60px;
        pointer-events: none;
        z-index: 0;
        border-radius: 0 0 20px 20px;
        overflow: hidden;
    }

    .welcome-waves svg {
        width: 100%;
        height: 100%;
        display: block;
    }

    .welcome-waves .wave-1 {
        animation: wave-move-1 12s linear infinite;
    }

    .welcome-waves .wave-2 {
        animation: wave-move-2 18s linear infinite;
    }

    @keyframes wave-move-1 {
        0% { transform: translateX(0) scaleY(1); }
        50% { transform: translateX(-15%) scaleY(1.08); }
        100% { transform: translateX(0) scaleY(1); }
    }

    @keyframes wave-move-2 {
        0% { transform: translateX(0) scaleY(1); }
        50% { transform: translateX(15%) scaleY(0.92); }
        100% { transform: translateX(0) scaleY(1); }
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

    <div class="welcome-waves">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320" preserveAspectRatio="none">
            <path class="wave-1" fill="rgba(255, 255, 255, 0.05)" d="M0,96L48,112C96,128,192,160,288,186.7C384,213,480,235,576,213.3C672,192,768,128,864,122.7C960,117,1056,171,1152,197.3C1248,224,1344,224,1392,224L1440,224L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
            <path class="wave-2" fill="rgba(255, 255, 255, 0.03)" d="M0,192L48,197.3C96,203,192,213,288,202.7C384,192,480,160,576,138.7C672,117,768,107,864,122.7C960,139,1056,181,1152,181.3C1248,181,1344,139,1392,117.3L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
        </svg>
    </div>
</div>
