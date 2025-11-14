<?php

namespace App\Http\Controllers;

use App\Models\PurposeTransfer;
use Illuminate\Http\Request;

class PurposeOfTransferController extends Controller
{
    public function index(){
        $purposeOfTransfer = PurposeTransfer::all();
        return response()->json($purposeOfTransfer);
    }
}
