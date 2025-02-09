@extends('layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-2 mx-4">{{ __('CENTRAL') }}</h1>
                    <a class="btn btn-dark disabled placeholder col-8" aria-disabled="true"></a>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <div class="row col-12 text-center">
    {{-- Primeio card --}}
        <div class="col-lg-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>Saldo: R${{ auth()->user()->money }} </h3>
                    <p>Saldo total: R$ {{ auth()->user()->money + auth()->user()->userStock()->sum('stock_price')}}</p>
                </div>
                <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
                <a href="{{ route('transaction') }}" class="small-box-footer">Mais informações <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

    {{-- Segundo Card --}}  
    <div class="col-lg-6">
        <div class="small-box bg-secondary">
            <div class="inner">
                <h3>Perfil</h3>
                <p>Ver histórico e informações</p>
            </div>
            <div class="icon">
                <i class="ion ion-person-add"></i>
            </div>
            <a href="{{ route('users.historic') }}" class="small-box-footer">Mais Informações  
                <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>

    {{-- Terceiro Card --}}
    <div class="col-lg-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>Comprar</h3>
                <p></p>
            </div>
            <div class="icon">
                <i class="ion ion-stats-bars"></i>
            </div>
            <a href="{{ route('stocks.index') }}" class="small-box-footer">Mais informações <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>        

    {{-- Quarto Card --}}
    <div class="col-lg-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>Vender</h3>
                <p></p>
            </div>
            <div class="icon">
                <i class="ion ion-pie-graph"></i>
            </div>
            <a href="{{ route('stocks.sellList') }}" class="small-box-footer">Mais informações <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    {{--  --}}
    </div>
@endsection