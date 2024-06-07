<form action="{{ route('ajuanfaktur.approvestore', Crypt::encrypt($ajuanfaktur->no_pengajuan)) }}" id="formApprovefaktur"
   method="POST">
   @csrf
   <table class="table">
      <tr>
         <th style="width: 40%">No. Pengajuan</th>
         <td>{{ $ajuanfaktur->no_pengajuan }}</td>
      </tr>
      <tr>
         <th>Tanggal</th>
         <td>{{ DateToIndo($ajuanfaktur->tanggal) }}</td>
      </tr>
      <tr>
         <th>Kode Pelanggan</th>
         <td>{{ $ajuanfaktur->kode_pelanggan }}</td>
      </tr>
      <tr>
         <th>Nama Pelanggan</th>
         <td>{{ $ajuanfaktur->nama_pelanggan }}</td>
      </tr>
      <tr>
         <th>Alamat Pelanggan</th>
         <td>{{ $ajuanfaktur->alamat_pelanggan }}</td>
      </tr>
      <tr>
         <th>No. HP</th>
         <td>{{ $ajuanfaktur->no_hp_pelanggan }}</td>
      </tr>
      <tr>
         <th>Jumlah Faktur</th>
         <td>{{ $ajuanfaktur->jumlah_faktur }}</td>
      </tr>
      <tr>
         <th>Cash on Delivery</th>
         <td>
            @if ($ajuanfaktur->siklus_pembayaran == '1')
               <i class="ti ti-square-check text-success"></i>
            @endif
         </td>
      </tr>
   </table>
   <div class="row mt-3">
      <div class="col">
         @if ($lastdisposisi->id_pengirim == auth()->user()->id)
            @php
               $catatan = $lastdisposisi->catatan;
            @endphp
         @else
            @php
               $catatan = '';
            @endphp
         @endif
         @if (auth()->user()->roles->pluck('name')[0] == 'sales marketing manager' || auth()->user()->roles->pluck('name')[0] == 'operation manager')
            <x-textarea label="Catatan" name="catatan" value="{{ $catatan }}" />
         @elseif(auth()->user()->roles->pluck('name')[0] != 'direktur')
            <x-textarea label="Catatan" name="catatan" value="{{ $catatan }}" />
         @endif
      </div>
   </div>
   <div class="row mt-3">
      <div class="col">
         @foreach ($disposisi as $index => $d)
            @php
               $next_role = @$disposisi[$index + 1]->role;
            @endphp
            @if ($d->role == $next_role)
               @php
                  continue;
               @endphp
            @endif
            <h6 style="line-height: 0" class="text-info">{{ $d->username }}
               ({{ textCamelCase($d->role) }})
            </h6>
            <small class="text-muted">{{ $d->created_at }}</small>
            <p>{{ $d->catatan }}</p>
         @endforeach
      </div>
   </div>
   <div class="row mt-3">
      <div class="col-lg-8 col-md-12 col-sm-12">
         <div class="form-group mb-3">
            <button class="btn btn-primary w-100"><i class="ti ti-thumb-up me-1"></i>
               @if (auth()->user()->roles->pluck('name')[0] == 'sales marketing manager' || auth()->user()->roles->pluck('name')[0] == 'operation manager')
                  Setuju & Teruskan ke RSM
               @elseif (auth()->user()->roles->pluck('name')[0] == 'regional sales manager')
                  Setuju & Teruskan ke GM
               @elseif (auth()->user()->roles->pluck('name')[0] == 'gm marketing')
                  Setuju & Teruskan ke Direktur
               @else
                  Setuju
               @endif
            </button>
         </div>
      </div>
      <div class="col-lg-4 col-sm-12 col-md-12">
         <div class="form-group mb-3">
            <button class="btn btn-danger w-100" name="decline" type="submit"
               value="decline">
               <i class="ti ti-thumb-down me-1"></i> Tolak
            </button>
         </div>
      </div>
   </div>
</form>
<script src="{{ asset('/assets/vendor/libs/@form-validation/umd/bundle/popular.min.js') }}"></script>
<script src="{{ asset('/assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js') }}"></script>
<script src="{{ asset('/assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js') }}"></script>
<script src="{{ asset('assets/js/pages/ajuanfakturapprove.js') }}"></script>
