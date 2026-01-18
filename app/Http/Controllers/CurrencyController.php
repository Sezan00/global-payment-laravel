<?php

namespace App\Http\Controllers;

use App\Models\CountryCurrencies;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    

  // public function UserSourceCurrencySelect(Request $request){
  //   $user = $request->user();

  //   $currencies = $user->country->currencies;

  //   return response()->json($currencies);

//     public function UserSourceCurrencySelect(Request $request){
//     $user = $request->user();

//     $currencies = $user->country->currencies->map(function($c){
//     return [
//         'id'   => $c->pivot->id ?? $c->id,
//         'name' => $c->name,
//         'code' => $c->code
//     ];
// });

//     return response()->json($currencies);

//   }


    public function UserSourceCurrencySelect(Request $request){
      $user = $request->user();
      $currencyIds  = $user->country->currencies->pluck('id');
      $sourceCountryCurrency = CountryCurrencies::with('country', 'currency')->whereIn('currency_id', $currencyIds)
        ->whereIn('type', ['both', 'sending'])
        ->get();

      return response()->json([
        'data' => $sourceCountryCurrency
      ]);
    }

  }


