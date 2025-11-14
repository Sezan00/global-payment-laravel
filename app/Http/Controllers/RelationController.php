<?php

namespace App\Http\Controllers;

use App\Models\Relation;
use Illuminate\Http\Request;

class RelationController extends Controller
{
    public function index(){
        $relations = Relation::all();

        return response()->json($relations);
    }
}
