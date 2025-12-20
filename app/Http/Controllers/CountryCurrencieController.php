<?php

namespace App\Http\Controllers;

use App\Models\CountryCurrencies;
use Illuminate\Http\Request;

class CountryCurrencieController extends Controller
{
    public function index(){
        
     $data = CountryCurrencies::with([
        'country:id,name',
        'currency:id,name,code'
     ])
     ->whereIn('type', ['both', 'receiving'])->get()->groupBy('country_id');

     return response()->json($data);
    }
}
