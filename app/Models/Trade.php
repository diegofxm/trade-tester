<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Trade extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_id',
        'ticket',
        'symbol',
        'type',
        'lots',
        'open_time',
        'close_time',
        'open_price',
        'close_price',
        'profit',
        'swap',
        'commission',
        'magic_number',
    ];

    public function account(): BelongsTo          
    {
        return $this->belongsTo(Account::class);
    }
}
