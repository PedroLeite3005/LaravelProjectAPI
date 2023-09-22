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
                                        <th>Preço</th>
                                        <th>Quantidade</th>
                                        <th>Modo</th>
                                        <th>Data</th>
                                    </tr>
                                </thead>
                                <div class="d-flex col-sm-6 my-2 col-xxl-8" x-data="{
                                    searchTerm: '',
                                    search() {
                                        let url = '{{ route('users.historic', [
                                            'page' => 1,
                                            'searchTerm' => ':searchTerm'
                                        ]) }}'.replace(':searchTerm', this.searchTerm)
                                        location.href = url
                                    }
                                }">
                                    <input class="form-control me-2" type="search" placeholder="Código/Modo" name="searchTerm" x-model="searchTerm">
                                    <button class="btn btn-outline-success" type="submit" x-on:click="search()">Pesquisar</button>
                                </div>
                                <tbody>
                                    @foreach ($transactions as $transaction)
                                        <tr>
                                            <td>{{ $transaction->name }}</td>
                                            <td>{{ $transaction->price }}</td>
                                            <td>{{ $transaction->quantity }}</td>
                                            <td>{{ strtoupper($transaction->type) }}</td>
                                            <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <div class="d-flex justify-content-end p-2">
                                    <p class="my-0 mx-2">Página {{ $page }} de um  total de {{ $lastPage }}</p>
                                    @if($page > 1)
                                        <a href="{{ route('users.historic', ['page' => $page-1, 'searchTerm' => $searchTerm]) }}" class="btn btn-secondary btn-sm mr-2">Anterior</a>
                                    @endif
                                    @if($page < $lastPage)      
                                        <a href="{{ route('users.historic', ['page' => $page+1, 'searchTerm' => $searchTerm]) }}" class="btn btn-secondary btn-sm">Próxima</a>
                                    @endif 
                                </div>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>

                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
@endsection