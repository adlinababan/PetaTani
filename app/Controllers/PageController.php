<?php

namespace App\Controllers; 
use App\Models\ProfileModel;

class PageController extends BaseController 
{ 
    public function home()  
    {
        return view('home');
    } 
    public function tentang() 
    { 
        return view('tentang'); 
    }
    public function login() 
    { 
        return view('login'); 
    }    
    
}