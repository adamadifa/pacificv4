<li style="border-bottom: 1px solid #ccc; line-height:2rem">

    <div class="d-flex justify-content-between">
        <div>
            @if (!empty($account->subAccounts))
                <strong>{{ $account->kode_akun }} - {{ $account->nama_akun }}</strong>
            @else
                {{ $account->kode_akun }} - {{ $account->nama_akun }}
            @endif
        </div>
        <div>
            <div class="d-flex">
                @can('coa.edit')
                    <a href="#" class="btnEdit" kode_akun="{{ Crypt::encrypt($account->kode_akun) }}"><i class="ti ti-edit text-success"></i></a>
                @endcan
                @can('coa.delete')
                    <form method="POST" name="deleteform" class="deleteform" action="{{ route('coa.delete', Crypt::encrypt($account->kode_akun)) }}">
                        @csrf
                        @method('DELETE')
                        <a href="#" class="delete-confirm me-1">
                            <i class="ti ti-trash text-danger"></i>
                        </a>
                    </form>
                @endcan
            </div>
        </div>
    </div>
    @if (!empty($account->subAccounts))
        <ul class="nested">
            @foreach ($account->subAccounts as $subAccount)
                @include('accounting.coa.account', ['account' => $subAccount])
            @endforeach
        </ul>
    @endif
</li>
