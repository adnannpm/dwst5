<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class transactions extends Model
{
    protected $guarded = [];
    protected $hidden = ['wallet'];

    public function wallet(): BelongsTo {
        return $this->belongsTo(wallets::class);
    }

    public function category(): BelongsTo {
        return $this->belongsTo(categories::class);
    }
}
