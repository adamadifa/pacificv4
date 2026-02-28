<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | PORTAL CV. Makmur Permata</title>
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
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        @keyframes gradientBG {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        .input-field {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            transition: all 0.3s ease;
        }

        .input-field:focus {
            background: rgba(255, 255, 255, 0.15);
            border-color: #60a5fa;
            box-shadow: 0 0 0 4px rgba(96, 165, 250, 0.2);
            outline: none;
        }

        .input-field::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }

        .btn-primary {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(59, 130, 246, 0.5);
        }

        #character-container {
            width: 260px;
            position: absolute;
            bottom: calc(100% - 160px);
            left: 50%;
            z-index: 10;
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
            -webkit-mask-image: linear-gradient(to bottom, black 80%, transparent 100%);
            mask-image: linear-gradient(to bottom, black 80%, transparent 100%);
        }

        .state-idle {
            transform: translateX(-50%) translateY(0);
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0% {
                transform: translateX(-50%) translateY(0px) rotate(0deg);
            }

            50% {
                transform: translateX(-50%) translateY(-10px) rotate(1deg);
            }

            100% {
                transform: translateX(-50%) translateY(0px) rotate(0deg);
            }
        }

        .state-typing {
            transform: translateX(-50%) translateY(-5px);
        }

        .state-hide {
            transform: translateX(-50%) translateY(30px) scale(0.8) !important;
            opacity: 0 !important;
        }

        .state-success {
            animation: jump 0.6s ease;
        }

        @keyframes jump {
            0% {
                transform: translateX(-50%) translateY(0);
            }

            50% {
                transform: translateX(-50%) translateY(-40px) scale(1.05);
            }

            100% {
                transform: translateX(-50%) translateY(0);
            }
        }

        .state-fail {
            animation: shake 0.4s ease-in-out;
        }

        @keyframes shake {

            0%,
            100% {
                transform: translateX(-50%) rotate(0deg);
            }

            25% {
                transform: translateX(-55%) rotate(-5deg);
            }

            75% {
                transform: translateX(-45%) rotate(5deg);
            }
        }

        .logo-container {
            position: absolute;
            top: -100px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 30;
        }
    </style>
    <!-- PWA  -->
    <meta name="theme-color" content="#3b82f6" />
    <link rel="apple-touch-icon" href="{{ asset('logo.png') }}">
    <link rel="manifest" href="{{ asset('/manifest.json') }}">
</head>

<body>

    <div class="glass-card w-full max-w-md rounded-3xl p-8 relative mx-4 mt-24">

        <!-- Image Character -->
        <div id="character-container" class="state-idle">
            <img id="character-img" src="{{ asset('karakter.png') }}" alt="Login Character">
        </div>

        <div class="text-center mb-8 relative z-20 mt-10">
            <div class="mb-4">
                <img src="{{ asset('assets/img/logo/logoportal.png') }}" alt="Logo" width="140" class="mx-auto" style="filter: brightness(0) invert(1);">
            </div>
            <h2 class="text-3xl font-bold text-white mb-2">Welcome Back</h2>
            <p class="text-gray-400 text-sm">Sign in to your CV. Makmur Permata account</p>
        </div>

        @if ($errors->any())
            <div class="bg-red-500/20 border border-red-500/50 text-red-200 px-4 py-3 rounded-xl mb-6 text-sm relative z-20">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form id="login-form" class="space-y-5 relative z-20" action="{{ route('login') }}" method="POST">
            @csrf
            <div>
                <label for="id_user" class="block text-sm font-medium text-gray-300 mb-2">Email or Username</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                        </svg>
                    </div>
                    <input type="text" name="id_user" id="id_user" class="input-field w-full pl-11 pr-4 py-3 rounded-xl"
                        placeholder="Username" autocomplete="off" value="{{ old('id_user') }}" required autofocus>
                </div>
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-300 mb-2">Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <input type="password" name="password" id="password" class="input-field w-full pl-11 pr-4 py-3 rounded-xl"
                        placeholder="••••••••" required>
                </div>
            </div>

            <div class="flex items-center justify-between mt-4">
                <div class="flex items-center">
                    <input name="remember" id="remember-me" type="checkbox"
                        class="h-4 w-4 rounded border-gray-600 bg-gray-700 text-blue-500 focus:ring-blue-500 focus:ring-offset-gray-900 border-none">
                    <label for="remember-me" class="ml-2 block text-sm text-gray-400">Remember me</label>
                </div>
                <a href="#" class="text-sm font-medium text-blue-400 hover:text-blue-300">Forgot password?</a>
            </div>

            <button type="submit" class="btn-primary w-full py-3.5 px-4 text-white font-bold rounded-xl mt-6 shadow-lg">
                Sign In
            </button>
        </form>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const charContainer = document.getElementById("character-container");
            const charImg = document.getElementById("character-img");
            const idInput = document.getElementById("id_user");
            const passwordInput = document.getElementById("password");
            const loginForm = document.getElementById("login-form");

            function resetCharacter() {
                charContainer.className = "state-idle";
                charImg.style.transform = "rotate(0deg)";
            }

            // ID User Input - Look around as user types
            idInput.addEventListener("focus", () => {
                charContainer.className = "state-typing";
            });

            idInput.addEventListener("blur", resetCharacter);

            idInput.addEventListener("input", (e) => {
                const length = e.target.value.length;
                const maxRotation = 15;
                const rotation = Math.min((length * 1.5), maxRotation) - (maxRotation / 2);
                charImg.style.transform = `rotate(${rotation}deg) scale(1.05)`;
            });

            // Password Input - Removed hiding effect as requested
            passwordInput.addEventListener("focus", () => {
                charContainer.className = "state-typing";
            });

            passwordInput.addEventListener("blur", resetCharacter);

            // Handle errors
            @if ($errors->any())
                charContainer.className = "state-fail";
                setTimeout(resetCharacter, 1000);
            @endif
        });

        // Service Worker
        if ("serviceWorker" in navigator) {
            navigator.serviceWorker.register("/sw.js").then(
                (registration) => { console.log("Service worker registration succeeded:", registration); },
                (error) => { console.error(`Service worker registration failed: ${error}`); }
            );
        }
    </script>
</body>

</html>
