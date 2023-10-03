<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class SellController extends Controller
{
    public function sellIndex()
    {
        return view('users.sellStock');
    }
}
