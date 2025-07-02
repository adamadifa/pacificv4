{{-- File: resources/views/coa/partials/account_row.blade.php --}}

{{-- 1. Tampilkan baris untuk akun saat ini --}}
<tr>
    <td>{{ $currentAccount->kode_akun }}</td>
    <td>
        <span style="padding-left: {{ $level * 25 }}px;">
            {{ $currentAccount->nama_akun }}
        </span>
    </td>
</tr>

{{-- 2. Cari anak dari akun saat ini dengan memindai ulang SEMUA akun --}}
@foreach ($allAccounts as $childAccount)
    {{-- Jika 'sub_akun' dari child sama dengan 'kode_akun' dari akun saat ini --}}
    @if ($childAccount->sub_akun == $currentAccount->kode_akun)
        {{-- Panggil lagi view ini untuk si anak --}}
        @include('accounting.coa.partial_account_row', [
            'currentAccount' => $childAccount,
            'allAccounts' => $allAccounts,
            'level' => $level + 1,
        ])
    @endif
@endforeach
