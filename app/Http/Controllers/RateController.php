<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RateController extends Controller
{
    public function getRate(Request $request){

        $source = $request->source;
        $target = $request->target;

        $url = "https://api.sandbox.transferwise.tech/v1/rates?source=$source&target=$target";

        $response = Http::withToken(env('WISE_API_TOKEN'))->get($url);

        if($response->failed()){
            return response()->json(['error' => 'API request failed']);
        }

        return $response->json()[0];
    }
}
