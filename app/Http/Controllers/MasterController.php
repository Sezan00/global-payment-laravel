<?php

namespace App\Http\Controllers;

use App\Models\PurposeTransfer;
use App\Models\Relation;
use App\Models\SourceFunds;
use Illuminate\Http\Request;

class MasterController extends Controller
{
    public function index(){
        $SourceOfFund = SourceFunds::all();
        $Realton      = Relation::all();
        $purposeOfTransfer = PurposeTransfer::all();

        return response()->json([
            'SourceOfFund'      => $SourceOfFund,
            'Realton'           => $Realton,
            'purposeOfTransfer' => $purposeOfTransfer
        ]);
    }
}
