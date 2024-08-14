@extends('layouts.app')
@section('titlepage', 'Penjualan')

@section('content')
@section('navigasi')
    <span>Penjualan</span>
@endsection
<div class="row">
    @livewire('penjualansalesman')
</div>
@endsection
