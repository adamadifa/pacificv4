<div class="row mb-3">
    <div class="col">
        <h4 class="p-0 m-0">
            Selamat Datang, <br>
            {{ textCamelCase(Auth::user()->name) }} 🎉
        </h4>
        <h5>Anda Login Sebagai, <span class="text-primary">{{ textCamelCase($level_user) }}</span></h3>
    </div>
</div>
