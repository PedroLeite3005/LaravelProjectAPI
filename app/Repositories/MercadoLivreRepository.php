<?php

namespace App\Repositories;

use App\Models\Api\MercadoLivreAPI;
use App\Repositories\Interfaces\MercadoLivreInterface;
use Illuminate\Support\Facades\Log;

class MercadoLivreRepository implements MercadoLivreInterface
{
    const SITEID = 'MLB';

    private string $appId = '';
    private string $clientSecret = '';
    private string $code = '';
    private string $redirectUri = '';

    private string $refreshToken = '';
    private string $accessToken = '';

    public function __construct()
    {
        $this->appId = '3493370482053355';
        $this->clientSecret = 'x3Iwjboaxi2RPY0XZAUXISkAslhmESdc';
        $this->code = 'TG-667f05b4e181960001e3b1cb-1874764573';
        $this->redirectUri = 'https://www.google.com.br';

        $this->refreshToken = 'TG-668d712a83330700012e2572-1874764573';
        $this->accessToken = 'APP_USR-3493370482053355-070913-e61f703b863927a02b1dcb19e1c884ab-1874764573';
    }

    /**
     * Obtém o primiro token do Mercado Livre API.
     * 
     * @return array
     */
    public function getFirstToken(): array
    {
        $response = (new MercadoLivreAPI())->post('/oauth/token', [
            'headers' => [
                'accept' => 'application/json',
                'content-type' => 'application/x-www-form-urlencoded',
            ],
            'form_params' => [
                'grant_type' => 'authorization_code',
                'client_id' => $this->appId,
                'client_secret' => $this->clientSecret,
                'code' => $this->code,
                'redirect_uri' => $this->redirectUri,
            ],
        ]);

        if ($response->getStatusCode() != 200) {
            Log::error('Erro ao tentar obter primeiro token do Mercado Livre: ' . $response->getBody());
        }

        $body = json_decode($response->getBody(), true);
        
        //body possui access_token e refresh_token, precisa salva-los para usar nas outras requisições
        return $body;
    }

    /**
     * Gera um novo access_token e refresh_token. 
     * 
     * @return array
     */
    public function refreshToken(): array
    {
        $response = (new MercadoLivreAPI())->post('/oauth/token', [
            'headers' => [
                'accept' => 'application/json',
                'content-type' => 'application/x-www-form-urlencoded',
            ],
            'form_params' => [
                'grant_type' => 'refresh_token',
                'client_id' => $this->appId,
                'client_secret' => $this->clientSecret,
                'refresh_token' => $this->refreshToken,
            ],
        ]);

        if ($response->getStatusCode() != 200) {
            Log::error('Erro ao tentar atualizar tokens do Mercado Livre: ' . $response->getBody());
        }

        $body = json_decode($response->getBody(), true);
        
        //body possui access_token e refresh_token, precisa salva-los para usar nas outras requisições
        return $body;
    }

    /**
     * Gera um usuário de teste para o Mercado Livre
     * 
     * @return array
     */
    public function generateTestUser(): array
    {
        $response = (new MercadoLivreAPI())->post('/users/test_user', [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->accessToken,
                'Content-Type' => 'application/json'
            ],
            'json' => [
                'site_id' => self::SITEID
            ]
        ]);

        if ($response->getStatusCode() != 200) {
            Log::error('Erro ao tentar gerar usuário de teste do Mercado Livre: ' . $response->getBody());
        }

        $body = json_decode($response->getBody(), true);
        
        return $body;
    }

    /**
     * Retorna as informações da conta logada
     * 
     * @return array
     */
    public function getMyAccountInfo(): array
    {   
        //Para pegar só atributos selecionados, basta informar os atributos na requisição: ?attributes=id,nickname...
        $response = (new MercadoLivreAPI())->get('/users/me', [ 
            'headers' => [
                'Authorization' => 'Bearer ' . $this->accessToken
            ]
        ]);

        if ($response->getStatusCode() != 200) {
            Log::error('Erro ao tentar obter dados do usuário do Mercado Livre: ' . $response->getBody());
        }

        $body = json_decode($response->getBody(), true);

        return $body;
    }

    /**
     * Retorna as informações da aplicação
     * 
     * @return array
     */
    public function getAppInfo(): array
    {
        $response = (new MercadoLivreAPI())->get('v1/account/balance', [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->accessToken
            ]
        ]);

        if ($response->getStatusCode() != 200) {
            Log::error('Erro ao tentar obter dados da aplicação do Mercado Livre: ' . $response->getBody());
        }

        $body = json_decode($response->getBody(), true);
        dd($body);
        return $body;
    }

    /**
     * Retorna os tipos de publicações
     * 
     * @return array
     */
    public function getPublicationTypes(): array
    {
        $response = (new MercadoLivreAPI())->get('/sites/' . self::SITEID . '/listing_types', [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->accessToken
            ]
        ]);

        if ($response->getStatusCode() != 200) {
            Log::error('Erro ao tentar obter tipos de publicações do Mercado Livre: ' . $response->getBody());
        }

        $body = json_decode($response->getBody(), true);

        return $body;
    }

    /**
     * Retorna as categorias do Mercado Livre
     * 
     * @return array
     */
    public function getCategories(): array
    {
        $response = (new MercadoLivreAPI())->get('/sites/' . self::SITEID . '/categories', [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->accessToken
            ]
        ]);

        if ($response->getStatusCode() != 200) {
            Log::error('Erro ao tentar obter categorias do Mercado Livre: ' . $response->getBody());
        }

        $body = json_decode($response->getBody(), true);

        return $body;
    }

    /**
     * Retorna os atributos de uma categoria 
     * 
     * @param string $categoryId
     * @return array
     */
    public function getCategoryAttributes(string $categoryId): array
    {
        $response = (new MercadoLivreAPI())->get('/categories/' . $categoryId . '/attributes', [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->accessToken
            ]
        ]);

        if ($response->getStatusCode() != 200) {
            Log::error('Erro ao tentar obter categoria do Mercado Livre: ' . $response->getBody());
        }

        $body = json_decode($response->getBody(), true);
        dd($body);
        return $body;
    }

    /**
     * Cria um anúncio no Mercado Livre
     * 
     * @param array $data
     * @return array
     */
    public function createPublication(array $data): array
    {
        $response = (new MercadoLivreAPI())->post('/items', [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->accessToken,
                'Content-Type' => 'application/json'
            ],
            'json' => [
                'title' => $data['title'],
                'descriptions' => $data['description'], //plain_text
                'category_id' => $data['category_id'],
                'price' => floatval($data['price']),
                'currency_id' => $data['currency'],
                'condition' => $data['condition'],
                'available_quantity' => intval($data['stock']),
                'buying_mode' => 'buy_it_now',
                'listing_type_id' => $data['type'],
                'pictures' => [
                    [
                        'source' => $data['images']
                    ]
                ],
                // 'shipping' => [
                //     'mode' => 'me2',
                //     'local_pick_up' => false,
                //     'free_shipping' => false,
                //     'free_methods' => []
                // ],
                'sale_terms' => [
                    (object) [
                        'id' => "WARRANTY_TYPE",
                        'value_name' => "Garantia do vendedor"
                    ],
                    (object) [
                        'id' => "WARRANTY_TIME",
                        'value_name' => "12 meses"
                    ]
                ],
                'attributes' => [
                    [
                        'id' => "BRAND",
                        'value_name' => 'Marca'
                    ],
                    [
                        'id' => "GTIN",
                        'value_name' => '7898095297749'
                    ],
                    [
                        'id' => "MODEL",
                        'value_name' => 'Modelo'
                    ]
                ]
            ]
        ]);

        if ($response->getStatusCode() != 200) {
            Log::error('Erro ao tentar criar anúncio no Mercado Livre: ' . $response->getBody());
        }

        $body = json_decode($response->getBody(), true);

        return $body;
    }

     /**
     * Edita um anúncio no Mercado Livre
     * 
     * @param string $publicationId
     * @param array $data
     * @return array
     */
    public function editPublication(string $itemId, array $data): array
    {
        $response = (new MercadoLivreAPI())->put('/items/' . $itemId, [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->accessToken,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
                ],  
            'json' => $data
            ]);

        if ($response->getStatusCode() != 200) {
            Log::error('Erro ao tentar editar anúncio no Mercado Livre: ' . $response->getBody());
        }

        $body = json_decode($response->getBody(), true);

        return $body;
    }

    /**
     * Retorna os anúncios do Mercado Livre
     * 
     * @return array
     */
    public function getPublications(): array
    {
        $userId = $this->getMyAccountInfo()['id'] ?? null;

        $response = (new MercadoLivreAPI())->get('/users/' . $userId . '/items/search?attributes=results', [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->accessToken
            ]
        ]);
        
        if ($response->getStatusCode() != 200) {
            Log::error('Erro ao tentar obter anúncios do Mercado Livre: ' . $response->getBody());
        }

        $body = json_decode($response->getBody(), true);
        
        return $body;
    }

    /**
     * Retorna as informações de um anúncio
     * 
     * @param string $publicationId
     * @return array
     */
    public function getPublicationDetails(string $publicationId): array
    {
        $response = (new MercadoLivreAPI())->get('/items/' . $publicationId, [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->accessToken
            ]
        ]);

        if ($response->getStatusCode() != 200) {
            Log::error('Erro ao tentar obter anúncio do Mercado Livre: ' . $response->getBody());
        }

        $body = json_decode($response->getBody(), true);
        
        return $body;
    }

    /**
     * Retorna os pedidos do Mercado Livre
     * 
     * @return array
     */
    public function getOrders(): array
    {
        $userId = $this->getMyAccountInfo()['id'] ?? null;
        $response = (new MercadoLivreAPI())->get('/orders/search?attributes=results&seller=' . $userId, [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->accessToken
            ]
        ]);

        if ($response->getStatusCode() != 200) {
            Log::error('Erro ao tentar obter vendas do Mercado Livre: ' . $response->getBody());
        }

        $body = json_decode($response->getBody(), true);
        
        return $body;
    }

    /**
     * Retorna um pedido do Mercado Livre
     * 
     * @param int $orderId
     * @return array
     */
    public function getOrder(int $orderId): array
    {
        $response = (new MercadoLivreAPI())->get('/orders/' . $orderId, [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->accessToken
            ]
        ]);

        if ($response->getStatusCode() != 200) {
            Log::error('Erro ao tentar obter pedido do Mercado Livre: ' . $response->getBody());
        }
        
        $body = json_decode($response->getBody(), true);
        
        return $body;
    }

    /**
     * Retorna todas perguntas do Mercado Livre
     * 
     * @return array
     */
    public function getQuestions(): array
    {
        $userId = $this->getMyAccountInfo()['id'] ?? null;
        $response = (new MercadoLivreAPI())->get('/questions/search?seller_id=' . $userId . '&api_version=4' , [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->accessToken
            ]
        ]);

        if ($response->getStatusCode() != 200) {
            Log::error('Erro ao tentar obter perguntas do Mercado Livre: ' . $response->getBody());
        }

        $body = json_decode($response->getBody(), true);
        
        return $body;
    }

    /**
     * Responde uma pergunta do Mercado Livre
     * 
     * @param string $message
     * @return array
     */
    public function answerQuestion(string $questionId, string $message): array
    {
        $response = (new MercadoLivreAPI())->post('/answers', [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->accessToken,
                'Content-Type' => 'application/json'
            ],
            'json' => [
                'question_id' => $questionId,
                'text' => $message
            ]
        ]);

        if ($response->getStatusCode() != 200) {
            Log::error('Erro ao tentar responder pergunta do Mercado Livre: ' . $response->getBody());
        }

        $body = json_decode($response->getBody(), true);

        return $body;
    }

    /**
     * Deleta uma pergunta do Mercado Livre
     * 
     * @param string $questionId
     * @return array
     */
    public function deleteQuestion(string $questionId): array
    {
        $response = (new MercadoLivreAPI())->delete('/questions/' . $questionId, [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->accessToken
            ]
        ]);

        if ($response->getStatusCode() != 200) {
            Log::error('Erro ao tentar deletar pergunta do Mercado Livre: ' . $response->getBody());
        }

        $body = json_decode($response->getBody(), true);

        return $body;
    }

    /**
     * Obtém chat pós-vendas do Mercado Livre
     * 
     * @param int $packId
     * @param int $sellerId
     * @return array
     */
    public function getPostSalesChat(int $packId, int $sellerId): array
    {
        $response = (new MercadoLivreAPI())->get('/messages/packs/' . $packId . '/sellers/' . $sellerId . '?tag=post_sale', [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->accessToken
            ]
        ]);

        if ($response->getStatusCode() != 200) {
            Log::error('Erro ao tentar obter chat pós-vendas do Mercado Livre: ' . $response->getBody());
        }

        $body = json_decode($response->getBody(), true);
        
        return $body;
    }

    /**
     * Envia uma mensagem no chat pós-vendas do Mercado Livre
     * 
     * @param int $orderId
     * @param int $sellerId
     * @param string $message
     * @return array
     */
    public function sendPostSaleMessage(int $orderId, int $sellerId, string $message): array
    {
        $buyerId = $this->getOrder($orderId)['buyer']['id'] ?? null;

        $response = (new MercadoLivreAPI())->post('/messages/packs/' . $orderId . '/sellers/' . $sellerId . '?tag=post_sale', [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->accessToken,
                'Content-Type' => 'application/json'
            ],
            'json' => [
                'from' => [
                    'user_id' => $sellerId,
                ],
                'to' => [
                    'user_id' => $buyerId,
                ],
                'text' => $message
            ]
        ]);

        if ($response->getStatusCode() != 200) {
            Log::error('Erro ao tentar enviar mensagem no chat pós-vendas do Mercado Livre: ' . $response->getBody());
        }

        $body = json_decode($response->getBody(), true);

        return $body;
    }
}