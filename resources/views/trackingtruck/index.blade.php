@extends('layouts.app')
@section('titlepage', 'Tracking Truck')

@section('content')
@section('navigasi')
    <span>Tracking Truck</span>
@endsection
<div class="row mb-3">
    <div class="col-12">
        <div class="card shadow-sm border">
            <div class="card-body py-2 px-3">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div class="d-flex align-items-center gap-2">
                        <i class="ti ti-info-circle text-primary fs-4"></i>
                        <span class="fw-bold text-dark">Akses Login Tracksolid</span>
                    </div>
                    <div class="d-flex gap-4 flex-wrap">
                        <!-- Username Section -->
                        <div class="d-flex align-items-center gap-2">
                            <span class="text-muted small">Username:</span>
                            <div class="input-group input-group-sm" style="width: 200px;">
                                <input type="text" id="track_user" class="form-control bg-light border-0" value="cvmakmurpermata" readonly>
                                <button class="btn btn-outline-primary border-0" type="button" onclick="copyText('track_user')">
                                    <i class="ti ti-copy"></i>
                                </button>
                            </div>
                        </div>
                        <!-- Password Section -->
                        <div class="d-flex align-items-center gap-2">
                            <span class="text-muted small">Password:</span>
                            <div class="input-group input-group-sm" style="width: 200px;">
                                <input type="password" id="track_pass" class="form-control bg-light border-0" value="Makmurpermata@160" readonly>
                                <button class="btn btn-outline-primary border-0" type="button" onclick="togglePass()">
                                    <i class="ti ti-eye" id="eye_icon"></i>
                                </button>
                                <button class="btn btn-outline-primary border-0" type="button" onclick="copyText('track_pass')">
                                    <i class="ti ti-copy"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="text-muted small italic">
                        <i class="ti ti-help me-1"></i>Salin & tempel pada form login di bawah
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border">
            <div class="card-body p-0" style="height: 75vh;">
                <iframe src="https://www.tracksolidpro.com/resource/dev/index.html?t=247023#/live" 
                        frameborder="0" 
                        width="100%" 
                        height="100%" 
                        allowfullscreen>
                </iframe>
            </div>
        </div>
    </div>
</div>
@endsection

@push('myscript')
<script>
    function copyText(id) {
        var copyText = document.getElementById(id);
        var originalType = copyText.type;
        
        // Temporarily change type to text to copy password
        copyText.type = "text";
        copyText.select();
        copyText.setSelectionRange(0, 99999);
        navigator.clipboard.writeText(copyText.value);
        copyText.type = originalType;

        // Visual feedback
        Swal.fire({
            icon: 'success',
            title: 'Tersalin!',
            text: 'Data berhasil disalin ke clipboard',
            timer: 1000,
            showConfirmButton: false,
            toast: true,
            position: 'top-end'
        });
    }

    function togglePass() {
        var x = document.getElementById("track_pass");
        var icon = document.getElementById("eye_icon");
        if (x.type === "password") {
            x.type = "text";
            icon.classList.remove("ti-eye");
            icon.classList.add("ti-eye-off");
        } else {
            x.type = "password";
            icon.classList.remove("ti-eye-off");
            icon.classList.add("ti-eye");
        }
    }
</script>
@endpush
