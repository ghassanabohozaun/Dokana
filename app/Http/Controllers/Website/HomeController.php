<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct()
    {
    }

    public function index()
    {
        return view('website.index');
    }



  
}
