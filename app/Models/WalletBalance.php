<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletBalance extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'currency',
        'amount'
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id', 'id');
    }
}