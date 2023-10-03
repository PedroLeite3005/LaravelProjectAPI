<div>
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
                                    <td>{{ auth()->user()->name}}</td>
                                    <td>{{ auth()->user()->email }}</td>
                                    <td>R${{ auth()->user()->money }}</td>
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
                                <div class="d-flex justify-content-between col-sm-12 my-2 col-xxl-12">
                                    <input class="form-control me-2 col-lg-4" placeholder="Código" wire:model.live="searchTerm">
                                    <select name="type" wire:model.live="type">
                                        <option value="" selected>Todos</option>
                                        <option value="compra">Compra</option>
                                        <option value="venda">Venda</option>
                                        <option value="deposito">Depósito</option>
                                        <option value="saque">Saque</option>
                                    </select>
                                    <div class="d-flex inline-block ml-5 justify-content-end">
                                        <p class="mx-1">Página {{ $transactions->currentPage() }} de um  total de {{ $transactions->lastPage() }}</p>
                                        {{ $transactions->links()}}
                                    </div>
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
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
