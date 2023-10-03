<div>
    <div class="modal-content" x-data="{ 
        buttonVisible: true,
        quantity: 1,
        showAlert: false,
        alertMessage: '',
        alertClass: '',
        buttonClass: 'btn-close btn-close-white',
        stock: { 
            symbol: '{{ $stock->symbol }}', 
            regularMarketPrice: {{ $stock->regularMarketPrice }} 
        }, 
    }"> 
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="staticBackdropLabel">Comprar <span x-text="stock.symbol"></span>?</h1>
            <div>
                <p><strong>Preço R$ <span x-text="stock.regularMarketPrice"></span></strong></p>
            </div>
        </div>
    
        <form wire:submit.prevent="index">
            <div class="modal-body">
                <div class="input-group input-group-lg">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="inputGroup-sizing-lg">Quantidade</span>
                    </div>
                    <input wire:model="quantity" type="number" class="form-control" aria-label="Large" aria-describedby="inputGroup-sizing-sm" min="1">
                </div>
                <br>
                <h2>Valor: R$ <span x-text="(quantity * stock.regularMarketPrice).toFixed(2)"></span></h2>
                <input type="hidden" wire:model="totalAmount" x-bind:value="(quantity * stock.regularMarketPrice).toFixed(2)">
    
            </div>
            <div class="modal-footer">
                <div class="alert col-lg-12" :class="alertClass" role="alert" x-show="showAlert">
                    <span x-text="alertMessage"></span>
                    <button type="button" :class="buttonClass" data-bs-dismiss="alert" aria-label="Close" 
                    x-on:click="showAlert = false, window.location.href = '/stock/index'"></button>
                </div>
                <button type="button" class="btn btn-danger" data-dismiss="modal"  x-show="buttonVisible" 
                x-on:click="buttonVisible = false">Cancelar</button>
                <button type="submit" class="btn btn-success" x-show="buttonVisible" 
                x-on:click="buttonVisible = false">Comprar</button>
            </div>
        </form>
    </div>
</div>
