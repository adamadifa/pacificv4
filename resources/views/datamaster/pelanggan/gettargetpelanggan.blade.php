@if ($target->isEmpty())
    <div class="alert alert-danger">
        <h5>Pelanggan tidak Mengikuti Program ini</h5>
    </div>
@else
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Bulan</th>
                <th>Tahun</th>
                <th>Target</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($target as $item)
                <tr>
                    <td>{{ $namabulan[$item->bulan] }}</td>
                    <td>{{ $item->tahun }}</td>
                    <td>{{ $item->target_perbulan }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif
