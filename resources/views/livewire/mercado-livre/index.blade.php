<div x-cloak x-data="{ 
    seePublishForm: false, 
    seePublications: false
}">
    <div class="row mx-2">
        <a class="btn btn-success mx-2 my-2" href="https://auth.mercadolivre.com.br/authorization?response_type=code&client_id=3493370482053355&redirect_uri=https://www.google.com.br" target="_blank">
            Autenticar</a>
        <button class="btn btn-primary mx-2 my-2" wire:click="getFirstToken">
            Pegar primeiro token
        </button>
        <button class="btn btn-primary mx-2 my-2" wire:click="refreshToken">
            Atualizar token
        </button>
        <button class="btn btn-primary mx-2 my-2" wire:click="generateTestUser">
            Gerar usuário de teste 
        </button>
        <button class="btn btn-primary mx-2 my-2" wire:click="getMyAccountInfo">
            Dados da minha conta
        </button>
        <button class="btn btn-primary mx-2 my-2" wire:click="getAppInfo">
            Dados da minha aplicação
        </button>
    </div>
    <hr>
    {{-- Lista de Anúncios --}}
    <div class="row mx-2">
        <div class="card col-12">
            <div class="card-title d-flex justify-content-between align-items-center">
                <h5 class="mx-2"><strong>Anúncios criados:</strong></h5>
                <button class="btn btn-sm btn-primary py-0 px-1 mx-2" x-on:click="seePublications = !seePublications">
                    <span x-text="seePublications ? 'Fechar' : 'Abrir'"></span>
                </button>
            </div>
            <hr class="my-0">
            <div class="card-body" x-show="seePublications">
                <ul>
                    @foreach ($userPublications as $publication)
                    <div x-data="{ 
                        publication: null,
                        openEditForm: false,
                        formData: {
                            price: '',
                            available_quantity: '',
                            status: '',
                        },
                        openEditPublication(id){
                            $wire.getPublicationDetails(id)
                            .then(publication => {
                                this.publication = publication;
                                this.formData.price = publication.price;
                                this.formData.available_quantity = publication.available_quantity;
                                this.formData.status = publication.status;
                                this.openEditForm = true;
                            });
                        },
                        editPublication(id){
                            $wire.editPublication(id, this.formData).then(() => {
                                this.openEditForm = false;
                            });
                        }
                    }">
                        <li>{{ $publication }} 
                            <button class="btn btn-sm btn-primary py-0 px-1 ml-2" x-on:click="openEditPublication('{{ $publication }}')" x-show="!openEditForm">Detalhes</button>
                            <button class="btn btn-sm btn-secondary py-0 px-1" x-show="openEditForm" x-on:click="openEditForm = false">Fechar</button>
                        </li>
                        <div class="col-12 border border-dark my-1" x-show="openEditForm" x-transition.duration.500ms>
                            <div class="row">
                                <div class="col-3 d-flex flex-column mb-1">
                                    <label for="priceEdit">Preço:</label>
                                    <input type="number" id="priceEdit" x-model="formData.price">
                                </div>
                                <div class="col-3 d-flex flex-column mb-1">
                                    <label for="stockEdit">Estoque:</label>
                                    <input type="number" id="stockEdit" x-model="formData.available_quantity">
                                </div>
                                <div class="col-3 d-flex flex-column">
                                    <label for="titleEdit">Título:</label>
                                    <span id="titleEdit" x-text="publication ? publication.title : ''"></span>
                                </div>
                                <div class="col-3 d-flex flex-column">
                                    <label for="statusEdit">Status:</label>
                                    <select class="form-control" id="statusEdit" x-model="formData.status">
                                        <option value="active">Ativo</option>
                                        <option value="paused">Pausado</option>
                                        <option value="closed">Encerrado</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-3 d-flex flex-column mb-1">
                                    <label for="shippingEdit">Frete:</label>
                                    <span id="shippingEdit" x-text="publication ? publication.shipping.mode : ''"></span>
                                </div>
                            </div>
                            <div class="row d-flex justify-content-center">
                                <button class="btn btn-sm my-1 btn-primary col-6 text-center" x-on:click="editPublication('{{ $publication }}')">Enviar</button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    <hr>
    {{-- Formulário de criação de Anúncios --}}
    <div class="row mx-2">
        <div class="card col-12">
            <div class="card-title d-flex justify-content-between align-items-center">
                <h5 class="mx-2"><strong>Formulário de Anúncios</strong></h5>
                <button class="btn btn-sm btn-primary py-0 px-1 mx-2" x-on:click="seePublishForm = !seePublishForm">
                    <span x-text="seePublishForm ? 'Fechar' : 'Abrir'"></span>
                </button>
            </div>
            <hr class="my-0">
            <div class="card-body" x-data="{ numberOfVariations: 0 }" x-show="seePublishForm">
                <label for="title">Título:</label>
                <input class="col-12 form-control" type="text" placeholder="<Nome> + teste - não ofertar" id="title" wire:model="title"><br>
                <label for="description">Descricão:</label>
                <textarea class="col-12 my-1 form-control" id="description" wire:model="description"></textarea>
                <label for="type">Tipo de anúncio</label>
                <select class="form-control" id="type" wire:model.live="type">
                    @if (isset($publicationTypes['status']))
                        <option disabled>Token inválido</option>
                    @else
                        @foreach ($publicationTypes as $type)
                            <option value="{{ $type['id'] }}">{{ $type['name'] }}</option>
                        @endforeach      
                    @endif              
                </select>
                <a href="https://api.mercadolibre.com/sites/MLB/listing_types" target="_blank">Tipos de anúncio</a><br>
                <label for="imageUrl">Url das imagens:</label>
                <input type="text" id="imageUrl" class="col-12 form-control" wire:model="images">
                <label for="parentCategoryId">ID da Categoria:</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <button class="btn btn-primary" wire:click="getCategoryAttributes">Selecionar</button>
                    </div>
                    <input type="text" id="categoryId" class="form-control" wire:model="categoryId">
                </div>
                <a href="https://api.mercadolibre.com/sites/MLB/categories" target="_blank">Todas categorias</a><br> 
                <a href="https://api.mercadolibre.com/categories/ID_DA_CATEGORIA/attributes" target="_blank">Atributos da categoria selecionada</a><br>
                <label for="quantity">Estoque:</label>
                <input type="number" id="quantity" class="col-12 form-control" wire:model="stock">
                <label for="currency">Moeda:</label>
                <select class="form-control" id="currency" wire:model.live="currency">
                    <option value="">SELECIONAR</option>
                    <option value="BRL">BRL</option>
                </select>
                <label for="condition">Condição do produto:</label>
                <select class="form-control" id="condition" wire:model.live="condition">
                    <option value="">SELECIONAR</option>
                    <option value="new">Novo</option>
                    <option value="used">Usado</option>
                </select>
                <label for="price">Preço:</label>
                <input class="col-12 form-control" type="text" id="price" wire:model="price"> 
                <label for="variations">Variações:</label>
                <button class="btn btn-sm btn-success my-1 py-0 px-1" x-on:click="numberOfVariations++">Mais</button>
                <button class="btn btn-sm btn-danger my-1 py-0 px-1" x-on:click="numberOfVariations--" :disabled="numberOfVariations <= 0">Menos</button>
                <template x-for="(variation, index) in numberOfVariations" :key="index">
                    <div class="row my-1">
                        <div class="col-12 d-flex align-items-center" id="variations">
                            <p x-text="index + ' -'" class="my-0 mx-2"></p>
                            <select>
                                <option value="BRAND" selected>Marca</option>
                            </select>
                            <input class="col-9 mx-2" type="text" placeholder="Marca">
                        </div>
                    </div>
                    <hr>
                </template><br>
                <button class="btn btn-success mx-2 my-2" wire:click="createPublication" wire:target="createPublication" wire:loading.attr="disabled">Criar anúncio</button>
            </div>
        </div>
    </div>
    <hr>
    {{-- Lista de vendas --}}
    <div class="row mx-2">
        <div class="col-12 my-1 d-flex align-items-center">
            <h1 class="text-center">Vendas: </h1>
            <button class="btn btn-success mx-2 my-2" wire:click="getOrders" wire:target="getOrders" wire:loading.attr="disabled">Listar vendas</button><br>
        </div>
        <div class="d-flex align-items-center col-12">
            @if (!empty($sales))
                @foreach ($sales as $sale)
                    <div class="card border border-dark col-3">
                        <div class="card-title">
                            <h5 class="mx-1">ID: {{ $sale['id'] }}</h5>
                        </div>
                        <div class="card-body">
                            <p>Preço total: {{ $sale['total_amount'] }}</p>
                            <p>Valor pago: {{ $sale['paid_amount'] }}</p>
                            <p>Comprador: {{ $sale['buyer']['nickname'] }}</p>
                            <p>Status: {{ $sale['status'] }}</p>
                            <p>Tags:</p>
                            <ul>
                                @foreach ($sale['tags'] as $tags)
                                    <li>{{ $tags }}</li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="col-6 d-flex justify-content-center my-2">
                            <a class="btn btn-sm btn-primary py-0 px-1" href="{{ route('mercado-livre.postSalesChat', ['orderId' => $sale['id'], 'sellerId' => $sale['seller']['id']]) }}" target="_blank">
                                Conversa
                            </a>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
    <hr>
    {{-- Lista de perguntas --}}
    <div class="row mx-2">
        <h1 class="text-center">Perguntas:</h1><br>
        <div class="col-12 my-1 d-flex align-items-center">
            @foreach ($questions as $question)
                @if ($question['status'] == 'UNANSWERED')
                    <div class="card col-6">
                        <div class="card-title">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mx-1">ID: {{ $question['id'] }}</h5>
                                <button class="btn btn-sm btn-danger my-1 py-0 px-1" wire:click="deleteQuestion('{{ $question['id'] }}')" wire:loading.attr="disabled">Excluir</button>
                            </div>
                            <h5 class="mx-1">Produto: {{ $question['item_id'] }}</h5>
                        </div>
                        <div class="card-body">
                            <p>Pergunta: {{ $question['text'] }}</p>
                            <textarea class="col-12" wire:model.defer="message"></textarea>
                            <button class="btn btn-success mx-2 my-2" wire:click="answerQuestion('{{ $question['id'] }}')" wire:loading.attr="disabled">Responder</button>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</div>
