<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailySummary extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_id',
        'date',
        'balance',
        'equity',
        'drawdown',
        'deposits',
        'withdrawals',
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }
}
