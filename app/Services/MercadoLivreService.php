<?php

namespace App\Services;

use App\Repositories\Interfaces\MercadoLivreInterface;
use Illuminate\Support\Facades\Log;

class MercadoLivreService
{
    public function __construct(protected MercadoLivreInterface $mercadoLivreRepository)
    {
    }

    public function getFirstToken()
    {
        return $this->mercadoLivreRepository->getFirstToken();
    }

    public function refreshToken()
    {
        return $this->mercadoLivreRepository->refreshToken();
    }

    public function generateTestUser()
    {
        return $this->mercadoLivreRepository->generateTestUser();
    }

    public function getMyAccountInfo()
    {
        return $this->mercadoLivreRepository->getMyAccountInfo();
    }

    public function getAppInfo()
    {
        return $this->mercadoLivreRepository->getAppInfo();
    }

    public function getPublicationTypes()
    {
        return $this->mercadoLivreRepository->getPublicationTypes();
    }

    public function getCategories()
    {
        return $this->mercadoLivreRepository->getCategories();
    }

    public function getCategoryAttributes(string $categoryId)
    {
        return $this->mercadoLivreRepository->getCategoryAttributes($categoryId);
    }

    public function createPublication(array $data)
    {
        return $this->mercadoLivreRepository->createPublication($data);
    }

    public function editPublication(string $itemId, array $data)
    {
        return $this->mercadoLivreRepository->editPublication($itemId, $data);
    }

    public function getPublications()
    {
        $response = $this->mercadoLivreRepository->getPublications();

        if (!array_key_exists('results', $response)) {
            Log::error('Erro ao tentar obter anúncios do Mercado Livre: ' . json_encode($response));
            return [];
        }

        return $response['results'] ?? [];
    }

    public function getPublicationDetails(string $publicationId)
    {
        return $this->mercadoLivreRepository->getPublicationDetails($publicationId);
    }

    public function getOrders()
    {
        $response = $this->mercadoLivreRepository->getOrders();
        
        if (!array_key_exists('results', $response)) {
            Log::error('Erro ao tentar obter vendas do Mercado Livre: ' . json_encode($response));
            return [];
        }

        return $response['results'] ?? [];
    }

    public function getOrder(int $orderId)
    {
        return $this->mercadoLivreRepository->getOrder($orderId);
    }

    public function getQuestions()
    {
        $response = $this->mercadoLivreRepository->getQuestions();
        
        if (!array_key_exists('questions', $response)) {
            Log::error('Erro ao tentar obter perguntas do Mercado Livre: ' . json_encode($response));
            return [];
        }
    
        return $response['questions'] ?? [];
    }

    public function answerQuestion(string $questionId, string $message)
    {
        return $this->mercadoLivreRepository->answerQuestion($questionId, $message);
    }

    public function deleteQuestion(string $questionId)
    {
        return $this->mercadoLivreRepository->deleteQuestion($questionId);
    }

    public function getPostSalesChat(int $packId, int $sellerId)
    {
        $response = $this->mercadoLivreRepository->getPostSalesChat($packId, $sellerId);
        
        if (!array_key_exists('messages', $response)) {
            Log::error('Erro ao tentar obter chat pós-vendas do Mercado Livre: ' . json_encode($response));
            return [];
        }
       
        return [
            'messages' => $response['messages'],
            'conversation_status' => $response['conversation_status']
        ] ?? [];
    }

    public function sendPostSaleMessage(int $orderId, int $sellerId, string $message)
    {
        return $this->mercadoLivreRepository->sendPostSaleMessage($orderId, $sellerId, $message);
    }
}