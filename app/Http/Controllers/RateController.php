<?php

namespace App\Http\Controllers;

use App\Models\CountryCurrencies;
use App\Models\ExhangeRate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class RateController extends Controller
{
    public function getRate(Request $request){

        $source = $request->source;
        $target = $request->target;
        $amount = $request->amount ?? 1;

        $sourceCountryCourrency = CountryCurrencies::with('currency')->find($source);
        $targetCountryCurrency = CountryCurrencies::with('currency')->find($target);

        $sourceCurrency = $sourceCountryCourrency->currency->code;
        $targetCurrency = $targetCountryCurrency->currency->code;

        try {
            DB::beginTransaction();
            // $url = "https://api.sandbox.transferwise.tech/v1/rates?source=$sourceCurrency&target=$targetCurrency";


            $url = "https://api.wise-sandbox.com/v1/rates?source=$sourceCurrency&target=$targetCurrency";
            
            $response = Http::withToken(env('WISE_API_TOKEN'))->get($url);

            
            // logger($response);
            if($response->failed()){
                DB::rollBack();
                return response()->json(['error' => 'API request failed']);
            }


            $rateData = $response->json()[0];
            $rate = $rateData['rate'];

            $savedRate = ExhangeRate::create([
                'user_id'   => Auth::id(),
                'sender_country_currenciy_id' => $source,
                'receiver_country_currenciy_id' => $target,
                'ex_rate'            => $rate,
                'amount' =>  $amount
            ]);

            DB::commit();

            return response()->json([
                'message' => 'rate submited',
                'data'    => $savedRate
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            //throw $th;
             return response()->json(['error' => $th->getMessage()], 500);
        }



        return $response->json()[0];
    }
}
