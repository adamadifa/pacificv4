@extends('layouts.app')
@section('titlepage', 'Pelanggan')

@section('content')
@section('navigasi')
    <span>Pelanggan</span>
@endsection
<div class="row">
    @livewire('pelanggan')
</div>
@endsection
