<?php

namespace App\Http\Controllers;

use App\Models\SourceFunds;
use Illuminate\Http\Request;

class SourceOfFundController extends Controller
{
    public function index(){
      $SourceOfFunds = SourceFunds::all();

      
        return response()->json($SourceOfFunds);
    }
}
