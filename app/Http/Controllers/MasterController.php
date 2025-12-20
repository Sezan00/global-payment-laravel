<?php

namespace App\Http\Controllers;

use App\Models\PurposeTransfer;
use App\Models\Relation;
use App\Models\SourceFunds;
use App\Models\Transaction;
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

    public function Update(Request $request, Transaction $transaction){
        $request->validate([
            'purpose_of_transfer_id' => 'required|exists:purpose_of_transfers,id',
            'source_of_fund_id'      => 'required|exists:source_of_funds,id'
        ]);
        
        $transaction->update([
            'purpose_of_transfer_id' => $request->purpose_of_transfer_id,
            'source_of_fund_id'      => $request->source_of_fund_id,
        ]);

        return response()->json([
            'status' => 'success',
            'data'   => $transaction 
        ]);

    }
}
