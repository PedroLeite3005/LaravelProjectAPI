<?php

namespace App\Repositories\Interfaces;

interface MercadoLivreInterface
{
    /**
     * Busca o primeiro Access e Refresh Token do Mercado Livre
     */
    public function getFirstToken(): array;

    /**
     * Gera um novo Access e Refresh Token
     */
    public function refreshToken(): array;

    /**
     * Gera um usuário de teste para o Mercado Livre
     */ 
    public function generateTestUser(): array;

    /**
     * Retorna as informações da conta logada
     */
    public function getMyAccountInfo(): array;

    /**
     * Retorna as informações da aplicação
     */
    public function getAppInfo(): array;

    /**
     * Retorna os tipos de publicações
     */
    public function getPublicationTypes(): array;

    /**
     * Retorna as categorias do Mercado Livre
     */
    public function getCategories(): array;

    /**
     * Retorna os atributos de uma categoria
     */
    public function getCategoryAttributes(string $categoryId): array;

    /**
     * Cria um anúncio no Mercado Livre
     */
    public function createPublication(array $data): array;

    /**
     * Retorna os anúncios do Mercado Livre
     */
    public function getPublications(): array;

    /**
     * Retorna as informçãoes de um anúncio
     */
    public function getPublicationDetails(string $publicationId): array;

    /**
     * Edita um anúncio no Mercado Livre
    */
    public function editPublication(string $itemId, array $data): array;

    /**
     * Retorna as vendas do Mercado Livre
     */
    public function getOrders(): array;

    /**
     * Retorna um pedido do Mercado Livre
     */
    public function getOrder(int $orderId): array;

    /**
     * Retorna todas perguntas do Mercado Livre
     */
    public function getQuestions(): array;

    /**
     * Responde uma pergunta do Mercado Livre
     */
    public function answerQuestion(string $questionId, string $message): array;

    /**
     * Deleta uma pergunta do Mercado Livre
     */
    public function deleteQuestion(string $questionId): array;

    /**
     * Obtém chat pós-vendas do Mercado Livre
     */
    public function getPostSalesChat(int $packId, int $sellerId): array;

    /**
     * Envia uma mensagem no chat pós-vendas do Mercado Livre
     */
    public function sendPostSaleMessage(int $orderId, int $sellerId, string $message): array;
}