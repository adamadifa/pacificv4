@extends('layouts.app')
@section('titlepage', 'Laporan Produksi')

@section('content')
@section('navigasi')
    <span>Laporan Produksi</span>
@endsection
<div class="row">
    <div class="col-xl-6">
        <h6 class="text-muted">Vertical</h6>
        <div class="nav-align-left nav-tabs-shadow mb-4">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                        data-bs-target="#navs-left-home" aria-controls="navs-left-home" aria-selected="false"
                        tabindex="-1">
                        Home
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab"
                        data-bs-target="#navs-left-profile" aria-controls="navs-left-profile" aria-selected="true">
                        Profile
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                        data-bs-target="#navs-left-messages" aria-controls="navs-left-messages" aria-selected="false"
                        tabindex="-1">
                        Messages
                    </button>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane fade" id="navs-left-home" role="tabpanel">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Mutasi Produksi</h4>
                        </div>
                        <div class="card-body">

                        </div>
                    </div>
                </div>
                <div class="tab-pane fade active show" id="navs-left-profile" role="tabpanel">
                    <p>
                        Donut dragée jelly pie halvah. Danish gingerbread bonbon cookie wafer candy oat cake ice
                        cream. Gummies halvah tootsie roll muffin biscuit icing dessert gingerbread. Pastry ice cream
                        cheesecake fruitcake.
                    </p>
                    <p class="mb-0">
                        Jelly-o jelly beans icing pastry cake cake lemon drops. Muffin muffin pie tiramisu halvah
                        cotton candy liquorice caramels.
                    </p>
                </div>
                <div class="tab-pane fade" id="navs-left-messages" role="tabpanel">
                    <p>
                        Oat cake chupa chups dragée donut toffee. Sweet cotton candy jelly beans macaroon gummies
                        cupcake gummi bears cake chocolate.
                    </p>
                    <p class="mb-0">
                        Cake chocolate bar cotton candy apple pie tootsie roll ice cream apple pie brownie cake. Sweet
                        roll icing sesame snaps caramels danish toffee. Brownie biscuit dessert dessert. Pudding jelly
                        jelly-o tart brownie jelly.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
