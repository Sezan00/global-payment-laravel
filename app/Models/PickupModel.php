<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PickupModel extends Model
{
     protected $table = 'pickup_methods';
     protected $fillable = ['name', 'type', 'is_active'];
}
