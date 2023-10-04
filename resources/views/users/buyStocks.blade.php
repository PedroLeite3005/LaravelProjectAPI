@extends('layouts.app')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h1 class="col-sm-4">{{ __('Comprar ações') }}</h1>
                <h2 class="col-sm-4"> {{__('Saldo: R$')  }}{{ auth()->user()->money }}</h2>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
    @if (session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
</div>
<!-- /.content-header -->

<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="d-flex col-sm-12 my-2 col-xxl-8 justify-content-between" x-data="{
                        searchTerm: '',
                        search() {
                            let url = '{{ route('stocks.index', [
                                'page' => 1,
                                'searchTerm' => ':searchTerm'
                            ]) }}'.replace(':searchTerm', this.searchTerm)
                            location.href = url
                        }
                    }">
                        <div class="d-flex justify-content-start col-sm-12 my-2 col-xxl-12">
                            <input class="form-control me-2 col-sm-6" type="search" placeholder="Nome/Código" name="searchCode" x-model="searchTerm">
                            <button class="btn btn-outline-success" type="submit" x-on:click="search()">Pesquisar</button>
                        </div>
                        <div class="d-flex inline-block mx-1">
                            <p class="my-0 mx-2">Página {{ $page }} de um  total de {{ $lastPage }}</p>
                            @if($page > 1)
                                <a href="{{ route('stocks.index', ['page' => $page-1, 'searchTerm' => $searchTerm]) }}" class="btn btn-secondary btn-sm mr-2">Anterior</a>
                            @endif
                            @if($page < $lastPage)      
                                <a href="{{ route('stocks.index', ['page' => $page+1, 'searchTerm' => $searchTerm]) }}" class="btn btn-secondary btn-sm">Próxima</a>
                            @endif
                        </div>
                    </div>
                    <div class="card-body border border-success text-center" style="background-color: #F0F0F0">
                        @foreach ($stocks as $stock)
                        <div class="card d-inline-block border border-dark col-sm-2 mx-2">
                            <img src="{{ $stock->logo }}" class="card-img-top mt-2 border border-dark"
                                style="height: 12vh; width: 15vh; border-radius:10%;">
                            <div class="card-body">
                                <h2 class="card-title my-0 block-inline"
                                    style="overflow: auto; white-space:nowrap">
                                    {{ $stock->name }}</h2>
                            </div>
                            <div class="card-text">
                                <h6 class="card-subtitle text-body-secondary text-muted ">{{ $stock->stock }}</h6>
                                <h5 class="card-text mx-3">R$ {{ $stock->close }}</h5>
                                <button type="button" class="btn btn-primary my-1 buy-button"
                                        data-stock="{{ $stock->stock }}"
                                        data-toggle="modal"
                                        data-target="#staticBackdrop">Comprar</button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</div>

@livewire('buy-form-manager', ['stock' => $stock->stock])

@endsection

@section('scripts')

@endsection