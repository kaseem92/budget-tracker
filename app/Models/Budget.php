<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['user_id', 'month', 'year', 'amount'])]
class Budget extends Model
{

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    
    protected function casts()
    {
        return [
            'month' => 'integer',
            'year' => 'integer',
            'amount' => 'decimal:2',
        ];
    }
}
