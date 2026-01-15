<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecipientAttribute extends Model
{
    protected $fillable = [
        'recipient_id',
        'key',
        'value',
    ];

    public function recipient(){
        return $this->belongsTo(Recipient::class, 'recipient_id');
    }
}
