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
            'decode_content' => false
        ]);

        $stocks = json_decode($response->getBody())->stocks;
        $stocks = collect($stocks);
        $searchTerm = $request->searchTerm;

        if ($searchTerm) {
            $stocks = $stocks->filter(function ($s) use ($searchTerm) {
                return stristr($s->name, $searchTerm) !== false || stristr($s->stock, $searchTerm) !== false;
            });
        }

        $lastPage = ceil($stocks->count() / $stocksPerPage);
        $skip = ($page - 1) * $stocksPerPage;
        $stocks = $stocks->slice($skip, $stocksPerPage);

        return view('users.buyStocks', [   
            'page' => $page,
            'lastPage' => $lastPage,
            'stocks' => $stocks, 
            'searchTerm' => $searchTerm
        ]);
    }

    public function buyStockForm()
    {
        return view('form_buy_stock');      
    }
}
