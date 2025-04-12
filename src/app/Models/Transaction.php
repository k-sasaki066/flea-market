<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Message;
use Illuminate\Support\Facades\DB;

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

    public function sentMessages()
    {
        return $this->hasMany(Message::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public static function getUnreadMessageCount($userId)
    {
        $transactionIds = Transaction::where('buyer_id', $userId)
            ->orWhere('seller_id', $userId)
            ->pluck('id');

        $unreadCount = Message::whereIn('transaction_id', $transactionIds)
            ->whereNull('read_at')
            ->where('sender_id', '!=', $userId)
            ->count();

        return $unreadCount;
    }

    public static function getTransactionItemsWithUnreadCount($userId)
    {
        $items = Transaction::where(function ($query) use ($userId) {
            $query->where('buyer_id', $userId)
                ->where('status', 'chatting');
        })
        ->orWhere(function ($query) use ($userId) {
            $query->where('seller_id', $userId)
                ->where('status', 'chatting');
        })
        ->with(['purchase.item', 'sentMessages' => function ($query) use ($userId) {
            $query->whereNull('read_at')
                ->where('sender_id', '!=', $userId);
        }])
        ->withCount(['sentMessages as latest_message_at' => function ($q) {
            $q->select(DB::raw('MAX(created_at)'));
        }])
        ->orderByDesc('latest_message_at')
        ->get();

        $items->each(function ($item) {
            $item->unread_count = $item->sentMessages->count();
        });

        return $items;
    }
}
