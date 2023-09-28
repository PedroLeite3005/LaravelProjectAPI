@extends('layouts.app')

@section('content')
{{-- <div class="container" >
    <h1>Minhas Ações</h1>
    <h2 class="col-sm-4">{{ __('Saldo: R$') }}{{ Auth::user()->money }}</h2>
    <div class="d-flex col-sm-6 my-2 col-xxl-8" x-data="{
        searchTerm: '',
        search() {
            let url = '{{ route('stocks.sellList', [
                'page' => 1,
                'searchTerm' => ':searchTerm'
            ]) }}'.replace(':searchTerm', this.searchTerm)
            location.href = url
        }
    }">
        <input class="form-control me-2" type="search" placeholder="Código" name="searchTerm" x-model="searchTerm">
        <button class="btn btn-outline-success" type="submit" x-on:click="search()">Pesquisar</button>
    </div>
    <div class="d-flex allign-items justify-content-end ml-5">
        <p class="my-0 ml-5">Página {{ $page }} de um  total de {{ $lastPage }}</p>
        @if($page > 1)
            <a href="{{ route('stocks.sellList', ['page' => $page-1, 'searchTerm' => $searchTerm]) }}" class="btn btn-secondary btn-sm ml-1 my-1">Anterior</a>
        @endif
        @if($page < $lastPage)      
            <a href="{{ route('stocks.sellList', ['page' => $page+1, 'searchTerm' => $searchTerm]) }}" class="btn btn-secondary btn-sm ml-1 my-1">Próxima</a>
        @endif
    </div>
    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif
    @if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif
    <div>
        <table class="table table-striped border border-dark">
            <thead class="thead-dark">
                <tr>
                    <th>Nome da Ação</th>
                    <th>Preço por Ação</th>
                    <th>Quantidade</th>
                    <th>Valor Total</th>
                    <th>Valor</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($userStocks as $userStock)
                <tr x-data="{ quantity:1 }">
                    <td>{{ $userStock->stock_name }}</td>
                    <td>R$ {{ $userStock->stock_price }}</td>
                    <td>{{ $userStock->stock_quantity }}</td>
                    <td>R$  <span x-text="{{ $userStock->stock_price * $userStock->stock_quantity}}"></span></td>
                    <td>
                        R$ <span x-text="(parseFloat('{{ $userStock->stock_price }}') * quantity).toFixed(2)"></span>
                    </td>
                    <td>
                        <form action="{{ route('stocks.sell') }}" method="POST">
                            @csrf
                            <input type="hidden" name="stock_id" value="{{ $userStock->id }}">
                            <input type="hidden" name="stock_name" value="{{ $userStock->stock_name }}">
                            <input type="number" min="1" max="{{ $userStock->stock_quantity }}" x-model="quantity" name="quantity">
                            <input type="hidden" name="sellAmount" x-bind:value="quantity * {{ $userStock->stock_price }}">
                            <button type="submit" class="btn btn-success">Vender</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div> --}}
    @livewire('sell-manager')
@endsection
