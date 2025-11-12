<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PickupMethod extends Model
{
    protected $fillable = ['method', 'details'];

    
    public function user(){
        return $this->belongsTo(User::class);
    }
}
