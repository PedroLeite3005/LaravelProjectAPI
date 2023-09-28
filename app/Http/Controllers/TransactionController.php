<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class TransactionController extends Controller
{
    public function index()
    {
        return view('users.transaction');
    }

}