<div class="row">
  <div class="col">
    <table class="table">
      <tr>
        <th style="width: 20%">Kode Target</th>
        <td>{{ $targetkomisi->kode_target }}</td>
      </tr>
      <tr>
        <th>Bulan</th>
        <td>{{ $namabulan[$targetkomisi->bulan] }}</td>
      </tr>
      <tr>
        <th>Tahun</th>
        <td>{{ $targetkomisi->tahun }}</td>
      </tr>
      <tr>
        <th>Cabang</th>
        <td>{{ $targetkomisi->nama_cabang }}</td>
      </tr>
    </table>

  </div>
</div>
<div class="row mt-2">
  <div class="col">
    <table class="table table-bordered table-striped table-hover">
      <thead class="table-dark">
        <tr>
          <th rowspan="2" align="middle">Kode</th>
          <th rowspan="2" align="middle">Salesman</th>
          <th colspan="{{ count($produk) }}" class="text-center">Produk</th>
        </tr>
        <tr>
          @foreach ($produk as $d)
            <th class="text-center">
              {{ $d->kode_produk }}
            </th>
          @endforeach
        </tr>
      </thead>
      <tbody>
        @foreach ($detail as $d)
          <tr>
            <td>{{ $d->kode_salesman }}</td>
            <td>{{ $d->nama_salesman }}</td>
            @foreach ($produk as $p)
              <td class="text-end">{{ formatAngka($d->{"target_$p->kode_produk"}) }}</td>
            @endforeach
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
