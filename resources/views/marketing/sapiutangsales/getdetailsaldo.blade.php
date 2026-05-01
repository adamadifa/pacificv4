@foreach ($rekappenjualan as $d)
    <tr>
        <td>
            <input type="hidden" name="kode_salesman[]" class="kode_salesman" value="{{ $d['kode_salesman'] }}">
            {{ $d['kode_salesman'] }}
        </td>
        <td>{{ $d['nama_salesman'] }}</td>
        <td class="text-end">
            <input type="hidden" name="saldo_akhir[]" class="saldo_akhir" value="{{ $d['saldo_akhir'] }}">
            {{ formatAngka($d['saldo_akhir']) }}
        </td>
    </tr>
@endforeach
