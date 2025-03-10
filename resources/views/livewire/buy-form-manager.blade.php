<div>
    <div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog" id="form-buy-stock-modal-dialog">
            <div class="modal-content text-center">
                <div class="modal-content"> 
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Comprar <span>{{ $stock_name }}</span>?</h1>
                        <div>
                            <p><strong>Preço R$ <span>{{$stock_price}}</span></strong></p>
                        </div>
                    </div>
                    <form wire:submit.prevent="index">
                        <div class="modal-body">
                            <div class="input-group input-group-lg">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-lg">Quantidade</span>
                                </div>
                                <input wire:model.live="quantity" wire:loading.attr="disabled" type="number" class="form-control" aria-label="Large" 
                                aria-describedby="inputGroup-sizing-sm" min="1">
                            </div>
                            <br>
                            <h2>Valor: R$ <span wire:model='totalAmount'>{{ round($quantity * $stock_price, 2) }}</span></h2>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal" wire:loading.remove>Cancelar</button>
                            <button type="submit" class="btn btn-success" wire:loading.remove>Comprar</button>
                            <p wire:loading>Carregando...</p> 
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div> 
</div>
