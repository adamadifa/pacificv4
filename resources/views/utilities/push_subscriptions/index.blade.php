@extends('layouts.app')
@section('titlepage', 'Push Notification Subscriptions')

@section('content')
@section('navigasi')
    <span>Push Notification Subscriptions</span>
@endsection
<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Daftar Subscription Push Notification</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <form action="{{ route('push-subscriptions.index') }}">
                            <div class="row">
                                <div class="col-lg-10 col-sm-12 col-md-10">
                                    <x-input-with-icon label="Nama Karyawan" value="{{ Request('user_name') }}" name="user_name" icon="ti ti-user" />
                                </div>
                                <div class="col-lg-2 col-sm-12 col-md-2">
                                    <div class="form-group mb-3">
                                        <button class="btn btn-primary w-100" id="btnSearch" style="margin-top: 25px"><i class="ti ti-search me-1"></i>Cari</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive mb-2">
                            <table class="table table-bordered">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Waktu Sub</th>
                                        <th>Nama Karyawan</th>
                                        <th>NIK</th>
                                        <th>Endpoint</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($subscriptions as $d)
                                        <tr>
                                            <td>{{ date('d-m-Y H:i:s', strtotime($d->created_at)) }}</td>
                                            <td>{{ $d->user_name }}</td>
                                            <td>{{ $d->nik }}</td>
                                            <td>
                                                <small class="text-muted" style="word-break: break-all;">
                                                    {{ Str::limit($d->endpoint, 50) }}
                                                </small>
                                            </td>
                                            <td>
                                                <form action="{{ route('push-subscriptions.destroy', $d->id) }}" method="POST" onsubmit="return confirm('Apakah anda yakin ingin menghapus subscription ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        <i class="ti ti-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div style="float: right;">
                            {{ $subscriptions->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
