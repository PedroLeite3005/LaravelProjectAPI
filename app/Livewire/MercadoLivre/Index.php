<?php

namespace App\Livewire\MercadoLivre;

use App\Services\MercadoLivreService;
use Livewire\Component;

class Index extends Component
{
    private MercadoLivreService $mercadoLivreService;

    public $publicationTypes = [];
    public $categories = [];
    public $userPublications;
    public $sales;
    public $questions;

    public $title;
    public $description;
    public $type;
    public $images;
    public $categoryId;
    public $stock;
    public $mode;
    public $currency;
    public $condition;
    public $price;

    public $variations = [];

    public $message;

    public function boot(MercadoLivreService $mercadoLivreService)
    {
        $this->mercadoLivreService = $mercadoLivreService;
    }

    public function mount()
    {
        $this->publicationTypes = $this->mercadoLivreService->getPublicationTypes() ?? [];
        $this->userPublications = $this->mercadoLivreService->getPublications() ?? [];
        $this->questions = $this->mercadoLivreService->getQuestions() ?? [];
    }

    public function getFirstToken()
    {
        $result = $this->mercadoLivreService->getFirstToken();
        dd($result);
    }

    public function refreshToken()
    {
        $result = $this->mercadoLivreService->refreshToken();
        dd($result);
    }

    public function generateTestUser()
    {
        $result = $this->mercadoLivreService->generateTestUser();
        dd($result);
    }

    public function getMyAccountInfo()
    {
        $result = $this->mercadoLivreService->getMyAccountInfo();
        dd($result);
    }

    public function getAppInfo()
    {
        $result = $this->mercadoLivreService->getAppInfo();
        dd($result);
    }

    public function getCategoryAttributes()
    {
        $result = $this->mercadoLivreService->getCategoryAttributes($this->categoryId);
        dd($result);
    }

    public function createPublication()
    {
        $data = [
            'title' => $this->title,
            'description' => $this->description,
            'type' => $this->type,
            'images' => $this->images,
            'category_id' => $this->categoryId,
            'stock' => $this->stock,
            'currency' => $this->currency,
            'condition' => $this->condition,
            'price' => $this->price
        ];
        
        $result = $this->mercadoLivreService->createPublication($data);
        dd($result);
    }

    public function editPublication(string $itemId, array $data)
    {
        return $this->mercadoLivreService->editPublication($itemId, $data);
    }

    public function getPublicationDetails(string $publicationId)
    {
        return $this->mercadoLivreService->getPublicationDetails($publicationId);
    }

    public function getOrders()
    {
        $this->sales = $this->mercadoLivreService->getOrders();
    }

    public function answerQuestion(string $questionId)
    {
        $result = $this->mercadoLivreService->answerQuestion($questionId, $this->message);
        dd($result);
    }

    public function deleteQuestion(string $questionId)
    {
        $result = $this->mercadoLivreService->deleteQuestion($questionId);
        dd($result);
    }

    public function render()
    {
        return view('livewire.mercado-livre.index');
    }
}
