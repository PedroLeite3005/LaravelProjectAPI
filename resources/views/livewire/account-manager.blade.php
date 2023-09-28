<div>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-2 mx-4">{{ __('CONTA') }}</h1>
                    <h2 class="mx-4 m-2">Saldo: R${{ Auth::user()->money  }}</h2>
                    <a class="btn btn-dark disabled placeholder col-10" aria-disabled="true"></a>
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
    <div class="row col-12" style="height: 58vh;">
        {{-- Primeio card --}}
        <div class="col-lg-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <div class="text-center">
                        <h3>DEPOSITAR </h3>
                        <form wire:submit.prevent="deposit">
                            <input class="form-control me-2 my-2 border border-dark" placeholder="Valor" 
                            type="text" wire:model="depositValue" autocomplete="off">
                            @error('depositValue') <span class="error">{{ $message }}</span>@enderror
                            <br>
                            <input type="submit" class="btn btn-warning col-lg-8 
                            border border-dark" style="background-color: #E9E9E9">
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Segundo Card --}}  
        <div class="col-lg-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <div class="text-center">
                        <h3>RETIRAR</h3>
                        <form wire:submit.prevent="withdraw">
                            <input class="form-control me-2 my-2 border border-dark" placeholder="Valor" 
                            type="text" wire:model="withdrawValue">
                            @error('withdrawValue') <span class="error">{{ $message }}</span>@enderror
                            <br>
                            <input type="submit" class="btn btn-warning col-lg-8 
                            border border-dark" style="background-color: #E9E9E9">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
