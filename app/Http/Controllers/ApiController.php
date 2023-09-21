<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;  
use Illuminate\Http\Request;

class ApiController extends Controller
{
    
    public function stocksIndex(Request $request)
    {
        $page = $request->page ?? 1;
        $stocksPerPage = 15;

        $client = new Client();
        $headers = [
            'Accept' => 'application/json',
        ];

        $response = $client->get('https://brapi.dev/api/quote/list', [
            'headers' => $headers,
        ]);

        $stocks = json_decode($response->getBody())->stocks;

        $stocks = collect($stocks);
        $searchTerm = $request->searchTerm;

        if ($request->searchTerm) {
            $stocks = $stocks->filter(function ($s) use ($searchTerm) {
                return stristr($s->name, $searchTerm) !== false || stristr($s->stock, $searchTerm) !== false;
            });
        }

        $lastPage = ceil($stocks->count() / $stocksPerPage);
        $skip = ($page - 1) * $stocksPerPage;
        $stocks = $stocks->slice($skip, $stocksPerPage);

        return view('users.comprar', [
            'page' => $page,
            'lastPage' => $lastPage,
            'stocks' => $stocks, 
            'searchTerm' => $request->searchTerm
        ]);

    }

    public function buyStockForm(string $stock)
    {
        $client = new Client();
        $headers = [
            'Accept' => 'application/json',
        ];

        $response = $client->get('https://brapi.dev/api/quote/' . $stock, [
            'headers' => $headers,
        ]);

        $stockInfo = json_decode($response->getBody())->results[0];

        return view('form_buy_stock', [
            'stock' => $stockInfo,
        ]);

      
    }
}
