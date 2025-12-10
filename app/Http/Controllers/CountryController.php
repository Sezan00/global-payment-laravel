<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\CountryCurrencies;
use App\Models\Quotation;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function index(){
        $countries = Country::whereHas('currencies')
                    ->with('currencies:id,name,code')
                    ->get(['id', 'name', 'iso2', 'iso3']);
                    
        return response()->json($countries);
    }

    public function showCountryCurrencieFromQuation($id){
         $quotation = Quotation::with(
            'sourceCurrency.country',
            'sourceCurrency.currency',
            'targetCurrency.country',
            'targetCurrency.currency'
         )->findOrFail($id);

        return response()->json([
            'data' => $quotation ,
        ]);    
    }
}
