<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_id',
        'seller_id',
        'buyer_id',
        'status',
    ];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public static function getTransactionItems($userId)
    {
        $items = Transaction::where(function ($query) use ($userId) {
            $query->where('buyer_id', $userId)
                ->where('status', 'chatting');
        })
        ->orWhere(function ($query) use ($userId) {
            $query->where('seller_id', $userId)
                ->where('status', 'chatting');
        })
        ->with('purchase.item')
        ->get();

        return $items;
    }
}
