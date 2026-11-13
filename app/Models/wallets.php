<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class wallets extends Model
{
    protected $guarded = [];
    protected $hidden = ['currency_id', 'balance'];

    public function currency(){
        return $this->belongsTo(currencies::class, 'currency_id');
    }
}
