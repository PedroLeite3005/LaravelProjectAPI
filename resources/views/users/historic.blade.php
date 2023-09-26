@extends('layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ __('Perfil/Histórico') }}</h1>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="alert alert-light border border-dark">
                        <table class="table border border-primary">
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>Email</th>
                                    <th>Saldo</th>
                                </tr>
                            </thead>
                            <tbody>
                            
                                <tr>
                                    <td>{{ Auth::user()->name}}</td>
                                    <td>{{ Auth::user()->email }}</td>
                                    <td>R${{ Auth::user()->money }}</td>
                                </tr>
                            
                            </tbody>
                        </table>
                    </div>

                    <div class="card">
                        <div class="card-body p-0">
                            <table class="table table-striped border border-dark">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Ação</th>
                                        <th>Preço Total</th>
                                        <th>Quantidade</th>
                                        <th>Operação</th>
                                        <th>Data</th>
                                    </tr>
                                </thead>
                                <div> 
                                    <div class="d-flex col-sm-12 my-2 col-xxl-12" style="height: 7vh;" x-data="{
                                        searchTerm: '',
                                        type: '',
                                        search() {
                                            let url = '{{ route('users.historic', [
                                                'page' => 1,
                                                'type' => ':type',
                                                'searchTerm' => ':searchTerm'
                                            ]) }}'.replace(':searchTerm', this.searchTerm) 
                                            location.href = url
                                        },
                                        filter(){
                                            let url = '{{ route('users.historic', [
                                                'page' => 1,
                                                'type' => ':type',
                                            ]) }}'.replace(':type', this.type)
                                            location.href = url
                                        }
                                    }">
                                        <input class="form-control me-2 col-lg-4" type="search" placeholder="Código" name="searchTerm" x-model="searchTerm">
                                        <button class="btn btn-outline-success mx-1" type="submit" x-on:click="search()">Pesquisar</button>
                                        <form action="{{ route('users.historic') }}">
                                            <select name="type" class="ml-4 mx-1 form-select" x-model="type">
                                                <option value="" selected>Todos</option>
                                                <option value="compra">Compra</option>
                                                <option value="venda">Venda</option>
                                                <option value="deposito">Depósito</option>
                                                <option value="saque">Saque</option>
                                            </select>
                                            <button type="submit" class="btn btn-success" x-on:click="filter()">Filtrar</button>
                                        </form>
                                    </div>
                                
                                    <tbody>
                                        @foreach ($transactions as $transaction)
                                            <tr>
                                                <td>{{ $transaction->name }}</td>
                                                <td>R$ {{ $transaction->price }}</td>
                                                <td>{{ $transaction->quantity }}</td>
                                                <td>{{ strtoupper($transaction->type) }}</td>
                                                <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <div class="d-flex justify-content-end p-2">
                                        <p class="my-0 mx-2">Página {{ $page }} de um  total de {{ $lastPage }}</p>
                                        @if($page > 1)
                                            <a href="{{ route('users.historic', ['page' => $page-1, 'type' => $type,'searchTerm' => $searchTerm]) }}" class="btn btn-secondary btn-sm mr-2">Anterior</a>
                                        @endif
                                        @if($page < $lastPage)      
                                            <a href="{{ route('users.historic', ['page' => $page+1, 'type' => $type,'searchTerm' => $searchTerm]) }}" class="btn btn-secondary btn-sm">Próxima</a>
                                        @endif 
                                    </div>
                                </table>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    <!-- /.content -->
@endsection