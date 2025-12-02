<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\CountryCurrencies;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class SuportController extends Controller
{

    //get own support currency
    public function getSenderSupportedCurrencies(){
         $user = Auth::user();

         $currencies = $user->country->currencies;

         return response()->json($currencies);
    }


    //for show the countrylist
    public function indexCountry(){
        return Country::select('id', 'name')->get();
    }

    //
    public function getReceiverCurrencies($countryId) {

        $country = Country::with('currencies')->find($countryId);

        if(!$country){
            return response()->json(['error' => 'Country not found'], 404);
        }

        return response()->json($country->currencies);
    }

    public function getAvailableCountires() {
        $sender = CountryCurrencies::with('country', 'currency')->whereIn('type', ['sending', 'both'])->get();
        $receiver = CountryCurrencies::with('country', 'currency')->whereIn('type', ['receiving', 'both'])->get();

        return response()->json([
            'sender' => $sender,
            'receiver' => $receiver
        ]);
    }

}
