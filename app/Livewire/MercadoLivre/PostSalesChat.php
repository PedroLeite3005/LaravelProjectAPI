<?php

namespace App\Livewire\MercadoLivre;

use App\Services\MercadoLivreService;
use Carbon\Carbon;
use Livewire\Component;

class PostSalesChat extends Component
{
    private MercadoLivreService $mercadoLivreService;

    public $orderId;
    public $sellerId;
    public $buyerName;
    public $orderChat;
    public $message;

    public function boot(MercadoLivreService $mercadoLivreService)
    {
        $this->mercadoLivreService = $mercadoLivreService;
    }

    public function mount(int $orderId, int $sellerId)
    {
        $this->orderId = $orderId;
        $this->sellerId = $sellerId;
        $this->buyerName = $this->mercadoLivreService->getOrder($orderId)['buyer']['nickname'];
        $this->orderChat = $this->configureChat();
    }

    private function configureChat()
    {
        $data = [];
        $results = $this->mercadoLivreService->getPostSalesChat($this->orderId, $this->sellerId);
        
        foreach ($results['messages'] as &$message) {
            $messageDate = Carbon::parse($message['message_date']['received'])->format('d/m H:i');
            
            $seller = $message['from']['user_id'] == $this->sellerId ? true : false;

            $data[] = [
                'text' => $message['text'],
                'message_date' => $messageDate,
                'seller' => $seller,
            ];
        }
        
        uasort($data, function ($a, $b) {
            return $a['message_date'] <=> $b['message_date'];
        });
       
        return [
            'data' => $data,
            'conversation_status' => $results['conversation_status']['status']
        ];
    }

    public function sendMessage()
    {
        return $this->mercadoLivreService->sendPostSaleMessage($this->orderId, $this->sellerId, $this->message);
    }

    public function render()
    {
        return view('livewire.mercado-livre.post-sales-chat');
    }
}
