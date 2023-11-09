<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id', 'currency', 'reference', 'transaction_type', 'status', 'amount', 'paid_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'customer_id', 'id');
    }
}