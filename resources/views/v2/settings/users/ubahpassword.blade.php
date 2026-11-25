@extends('layouts.app')
@section('titlepage', 'Ubah Password & Profil')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Home</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-slate-400">Settings</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Ubah Password</span>
    </div>
@endsection

@section('content')
<div class="space-y-4 max-w-2xl">

    <!-- PAGE TITLE BAR -->
    <div>
        <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Profil & Ubah Password</h3>
        <p class="text-[11px] text-slate-400 mt-0.5">Perbarui informasi akun dan kata sandi keamanan login Anda.</p>
    </div>

    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-4 sm:p-5 shadow-2xs">
        <form action="{{ route('users.updateprofile') }}" id="formeditUser" method="POST" class="space-y-3">
            @csrf
            @method('PUT')

            <!-- Nama User -->
            <div>
                <label for="name" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Nama User <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="user" class="w-3.5 h-3.5"></i>
                    </div>
                    <input type="text" name="name" id="name" value="{{ $user->name }}" placeholder="Nama Lengkap"
                        class="w-full h-[36px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                </div>
                @error('name')
                    <p class="text-[11px] font-semibold text-rose-600 mt-1 pl-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Username -->
            <div>
                <label for="username" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Username <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="at-sign" class="w-3.5 h-3.5"></i>
                    </div>
                    <input type="text" name="username" id="username" value="{{ $user->username }}" placeholder="Username"
                        class="w-full h-[36px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                </div>
                @error('username')
                    <p class="text-[11px] font-semibold text-rose-600 mt-1 pl-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Email <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="mail" class="w-3.5 h-3.5"></i>
                    </div>
                    <input type="email" name="email" id="email" value="{{ $user->email }}" placeholder="Email"
                        class="w-full h-[36px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                </div>
                @error('email')
                    <p class="text-[11px] font-semibold text-rose-600 mt-1 pl-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password Baru -->
            <div>
                <label for="password" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Password Baru (Kosongkan jika tidak ingin mengubah)
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="key" class="w-3.5 h-3.5"></i>
                    </div>
                    <input type="password" id="password" name="password" placeholder="Masukkan password baru" autocomplete="off"
                        class="w-full h-[36px] pl-8 pr-10 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                    <button type="button" id="togglePassword"
                        class="absolute inset-y-0 right-0 pr-2.5 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 cursor-pointer">
                        <i data-lucide="eye" id="eyeIcon" class="w-3.5 h-3.5"></i>
                    </button>
                </div>
                @error('password')
                    <p class="text-[11px] font-semibold text-rose-600 mt-1 pl-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Realtime Password Strength Indicator -->
            <div id="password-requirements" class="hidden p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200/80 dark:border-slate-700 space-y-1.5">
                <div class="flex items-center gap-1.5 text-xs font-bold text-slate-800 dark:text-slate-100 mb-1">
                    <i data-lucide="shield-check" class="w-3.5 h-3.5 text-[#284c9a] dark:text-blue-400"></i>
                    <span>Persyaratan Password:</span>
                </div>
                <ul class="space-y-1 text-[11px] font-medium">
                    <li id="req-length" class="text-rose-600 flex items-center gap-1.5">Minimal 8 karakter</li>
                    <li id="req-uppercase" class="text-rose-600 flex items-center gap-1.5">Mengandung huruf besar (A-Z)</li>
                    <li id="req-lowercase" class="text-rose-600 flex items-center gap-1.5">Mengandung huruf kecil (a-z)</li>
                    <li id="req-number" class="text-rose-600 flex items-center gap-1.5">Mengandung angka (0-9)</li>
                    <li id="req-symbol" class="text-rose-600 flex items-center gap-1.5">Mengandung simbol spesial (@$!%*#?&)</li>
                </ul>
            </div>

            <!-- Submit Button Right-Aligned -->
            <div class="pt-3 border-t border-slate-200/80 dark:border-slate-800 flex items-center justify-end gap-2">
                <button type="submit" id="btnSubmitProfile"
                    class="h-[36px] px-5 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] active:bg-[#152a57] text-white text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-xs cursor-pointer">
                    <i data-lucide="save" class="w-3.5 h-3.5"></i>
                    <span>Simpan Perubahan</span>
                </button>
            </div>

        </form>
    </div>

</div>
@endsection

@push('myscript')
<script>
    $(function() {
        if (typeof lucide !== 'undefined') lucide.createIcons();

        const passwordInput = $('#password');
        const requirementsContainer = $('#password-requirements');

        const reqs = {
            length: { regex: /.{8,}/, el: $('#req-length') },
            uppercase: { regex: /[A-Z]/, el: $('#req-uppercase') },
            lowercase: { regex: /[a-z]/, el: $('#req-lowercase') },
            number: { regex: /[0-9]/, el: $('#req-number') },
            symbol: { regex: /[@$!%*#?&]/, el: $('#req-symbol') }
        };

        passwordInput.on('input', function() {
            const val = $(this).val();
            if (val === '') {
                requirementsContainer.addClass('hidden');
                return;
            }
            requirementsContainer.removeClass('hidden');

            for (const key in reqs) {
                const req = reqs[key];
                if (req.regex.test(val)) {
                    req.el.removeClass('text-rose-600').addClass('text-emerald-600 font-bold');
                } else {
                    req.el.removeClass('text-emerald-600 font-bold').addClass('text-rose-600');
                }
            }
        });

        $('#togglePassword').on('click', function() {
            const type = passwordInput.attr('type') === 'password' ? 'text' : 'password';
            passwordInput.attr('type', type);
        });
    });
</script>
@endpush
