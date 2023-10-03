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
</div>
<!-- /.content-header -->

<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="d-flex col-sm-6 my-2 col-xxl-8" x-data="{
                        searchTerm: '',
                        search() {
                            let url = '{{ route('stocks.index', [
                                'page' => 1,
                                'searchTerm' => ':searchTerm'
                            ]) }}'.replace(':searchTerm', this.searchTerm)
                            location.href = url
                        }
                    }">
                        <input class="form-control me-2" type="search" placeholder="Nome/Código" name="searchCode" x-model="searchTerm">
                        <button class="btn btn-outline-success" type="submit" x-on:click="search()">Pesquisar</button>
                    </div>
                    <div class="d-flex justify-content-end p-2">
                        <p class="my-0 mx-2">Página {{ $page }} de um  total de {{ $lastPage }}</p>
                        @if($page > 1)
                            <a href="{{ route('stocks.index', ['page' => $page-1, 'searchTerm' => $searchTerm]) }}" class="btn btn-secondary btn-sm mr-2">Anterior</a>
                        @endif
                        @if($page < $lastPage)      
                            <a href="{{ route('stocks.index', ['page' => $page+1, 'searchTerm' => $searchTerm]) }}" class="btn btn-secondary btn-sm">Próxima</a>
                        @endif
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
                                        data-stock="{{$stock->stock}}"
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
<!-- /.content -->
<div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true" x-data="{ quantity: 1, close: {{ $stock->close }} }">
    <div class="modal-dialog" id="form-buy-stock-modal-dialog">
        <div class="modal-content text-center">
            {{-- <img src="/images/loading.gif" alt="Carregando"> --}}
            @livewire('buy-form-manager')
        </div>
    </div>
</div>  

@endsection

@section('scripts')
{{-- <script type="module">
    $(document).ready(function () {

function openBuyModal(event) {
    let stock = $(event.target).data('stock');
    axios.get('/stock/buy/' + stock)
        .then((response) => {
            let html = response.data;
            $('#form-buy-stock-modal-dialog').html(html);
        });
}

$('.buy-button').on('click', openBuyModal); 

$('.buy-button').on('click', function () {
    var stockStock = $(this).data('stock-stock');
    var stockClose = $(this).data('stock-close');

    $('#staticBackdropLabel').text('Comprar ' + stockStock + '?');
    $('#stock-close').text(stockClose);
    $('#stock-stock').text(stockStock);

    let modalContent =
        `
        <div class='text-center'>
            <img src="/images/loading.gif" alt="Carregando">
        </div>
    `;

    $('#staticBackdrop .modal-content').html(modalContent); 
    $('#staticBackdrop').modal('hide'); 

    @if (session('status')) 
        $('#staticBackdrop .modal-content').html(modalContent); 
        $('#staticBackdrop').modal('show');
    @endif
    
});
        
});

</script> --}}
@endsection