<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Login | PORTAL CV. Makmur Permata</title>
    <!-- PWA -->
    <meta name="theme-color" content="#3b82f6" />
    <link rel="apple-touch-icon" href="{{ asset('logo.png') }}">
    <link rel="manifest" href="{{ asset('/manifest.json') }}">

    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap');

        body {
            font-family: 'Outfit', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(-45deg, #1c1c1c, #2a2a2a, #111827, #374151);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
            height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            overflow: hidden;
        }

        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .glass-container {
            background: rgba(17, 24, 39, 0.6);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            width: 100%;
            height: 100vh;
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .input-field {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white;
            transition: all 0.3s ease;
            border-radius: 12px;
        }

        .input-field:focus {
            background: rgba(255, 255, 255, 0.1);
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.2);
            outline: none;
        }

        .input-field::placeholder {
            color: rgba(255, 255, 255, 0.3);
        }

        .btn-primary {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            border-radius: 14px;
            transition: all 0.3s ease;
            font-weight: 700;
            box-shadow: 0 10px 20px rgba(59, 130, 246, 0.3);
        }

        .btn-primary:active {
            transform: scale(0.98);
        }

        #character-container {
            width: 160px;
            margin-bottom: 1rem;
            transition: all 0.5s cubic-bezier(0.68, -0.55, 0.27, 1.55);
            pointer-events: none;
        }

        #character-img {
            width: 100%;
            height: auto;
            display: block;
            object-fit: contain;
            transition: all 0.3s ease;
            filter: drop-shadow(0 15px 15px rgba(0, 0, 0, 0.5));
            -webkit-mask-image: linear-gradient(to bottom, black 85%, transparent 100%);
            mask-image: linear-gradient(to bottom, black 85%, transparent 100%);
        }

        .state-idle { transform: translateY(0); animation: float 3s ease-in-out infinite; }
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-8px) rotate(1deg); }
        }

        .state-typing { transform: translateY(-3px) scale(1.02); }
    </style>
</head>

<body>
    <div class="glass-container">
        <!-- Logo -->
        <div class="mb-4">
            <img src="{{ asset('assets/img/logo/logoportal.png') }}" alt="Logo" width="100" style="filter: brightness(0) invert(1);">
        </div>

        <!-- Character -->
        <div id="character-container" class="state-idle">
            <img id="character-img" src="{{ asset('karakter.png') }}" alt="Character">
        </div>

        <!-- Form Title -->
        <div class="w-full mb-6 text-center">
            <h1 class="text-2xl font-bold text-white mb-1">Welcome Back</h1>
            <p class="text-gray-400 text-xs">Sign in to your account</p>
        </div>

        @if ($errors->any())
            <div class="w-full bg-red-500/20 border border-red-500/50 text-red-200 px-4 py-2 rounded-xl mb-4 text-xs">
                @foreach ($errors->all() as $error)
                    <p>• {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <!-- Login Form -->
        <form id="login-form" action="{{ route('login') }}" method="POST" class="w-full space-y-4">
            @csrf
            <div>
                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1.5 ml-1">Username</label>
                <input type="text" name="id_user" id="id_user" placeholder="Username" 
                    class="input-field w-full px-4 py-3.5 text-sm font-medium" required value="{{ old('id_user') }}" autocomplete="off">
            </div>

            <div>
                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1.5 ml-1">Password</label>
                <div class="relative">
                    <input type="password" name="password" id="password" placeholder="••••••••" 
                        class="input-field w-full px-4 py-3.5 text-sm font-medium" required>
                </div>
            </div>

            <div class="flex justify-between items-center px-1">
                <div class="flex items-center">
                    <input type="checkbox" name="remember" id="remember" class="w-3.5 h-3.5 rounded border-gray-600 bg-gray-800 text-blue-600 focus:ring-blue-500">
                    <label for="remember" class="ml-2 text-xs text-gray-400">Remember me</label>
                </div>
                <a href="#" class="text-xs font-bold text-blue-400">Forgot Password?</a>
            </div>

            <button type="submit" class="btn-primary w-full py-5 text-white text-base mt-2 uppercase tracking-widest">
                Sign In
            </button>

            <p class="text-gray-500 text-center text-[11px] mt-6">
                Don't have an account? <a href="#" class="text-blue-400 font-bold ml-1">Contact Admin</a>
            </p>
        </form>
    </div>

    <!-- PWA Install Prompt Banner -->
    <div id="pwa-install-prompt" class="fixed bottom-0 left-0 right-0 z-50 p-4 transform translate-y-full transition-all duration-500 ease-out hidden">
        <div class="max-w-md mx-auto bg-gray-900/95 backdrop-blur-xl border border-white/10 rounded-2xl p-4 shadow-2xl flex flex-col space-y-4">
            <div class="flex items-start space-x-3.5">
                <div class="flex-shrink-0 bg-blue-600/20 p-2.5 rounded-xl border border-blue-500/30 flex items-center justify-center">
                    <img src="{{ asset('logo.png') }}" alt="Portal Logo" class="w-10 h-10 object-contain rounded-lg">
                </div>
                <div class="flex-grow">
                    <h3 class="text-white font-bold text-base">Install Portal App</h3>
                    <p class="text-gray-400 text-xs mt-0.5 leading-relaxed">Akses Portal CV. Makmur Permata lebih cepat dan stabil langsung dari layar utama handphone Anda.</p>
                </div>
            </div>
            <div class="flex space-x-3">
                <button id="pwa-btn-cancel" class="flex-1 py-2.5 border border-white/10 text-gray-300 hover:text-white rounded-xl text-xs font-semibold transition duration-200">
                    Nanti Saja
                </button>
                <button id="pwa-btn-install" class="flex-1 btn-primary py-2.5 text-white rounded-xl text-xs font-bold shadow-md hover:brightness-110 transition duration-200">
                    Install
                </button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const charContainer = document.getElementById("character-container");
            const charImg = document.getElementById("character-img");
            const idInput = document.getElementById("id_user");
            const passwordInput = document.getElementById("password");

            function resetCharacter() {
                charContainer.className = "state-idle";
                charImg.style.transform = "rotate(0deg)";
                charImg.src = "{{ asset('karakter.png') }}";
            }

            idInput.addEventListener("focus", () => {
                charContainer.className = "state-typing";
            });

            idInput.addEventListener("blur", resetCharacter);

            idInput.addEventListener("input", (e) => {
                const length = e.target.value.length;
                const rotation = Math.min((length * 1.5), 10) - 5;
                charImg.style.transform = `rotate(${rotation}deg) scale(1.05)`;
            });

            passwordInput.addEventListener("focus", () => {
                charContainer.className = "state-typing";
                charImg.src = "{{ asset('karaktertutupmata.png') }}";
            });

            passwordInput.addEventListener("blur", resetCharacter);

            // Service Worker Registration
            if ("serviceWorker" in navigator) {
                navigator.serviceWorker.register("/sw.js").then(
                    (registration) => { console.log("Service worker registration succeeded:", registration); },
                    (error) => { console.error(`Service worker registration failed: ${error}`); }
                );
            }

            // PWA Install Prompt Logic
            let deferredPrompt;
            const pwaPrompt = document.getElementById('pwa-install-prompt');
            const btnInstall = document.getElementById('pwa-btn-install');
            const btnCancel = document.getElementById('pwa-btn-cancel');

            window.addEventListener('beforeinstallprompt', (e) => {
                e.preventDefault();
                deferredPrompt = e;
                if (!sessionStorage.getItem('pwa-prompt-dismissed')) {
                    pwaPrompt.classList.remove('hidden');
                    setTimeout(() => {
                        pwaPrompt.classList.remove('translate-y-full');
                    }, 100);
                }
            });

            btnInstall.addEventListener('click', async () => {
                if (!deferredPrompt) return;
                deferredPrompt.prompt();
                const { outcome } = await deferredPrompt.userChoice;
                console.log(`User choice outcome: ${outcome}`);
                deferredPrompt = null;
                hidePwaPrompt();
            });

            btnCancel.addEventListener('click', () => {
                sessionStorage.setItem('pwa-prompt-dismissed', 'true');
                hidePwaPrompt();
            });

            function hidePwaPrompt() {
                pwaPrompt.classList.add('translate-y-full');
                setTimeout(() => {
                    pwaPrompt.classList.add('hidden');
                }, 500);
            }

            window.addEventListener('appinstalled', (event) => {
                console.log('PWA installed successfully');
                hidePwaPrompt();
            });
        });
    </script>
</body>

</html>
