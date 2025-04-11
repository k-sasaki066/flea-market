<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'transaction_id',
        'sender_id',
        'message',
        'image_url',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public static function markAsRead($transactionId, $user)
    {
        $messages = Message::where('transaction_id', $transactionId)
        ->where('sender_id', '!=', $user['id'])
        ->whereNull('read_at')
        ->update(['read_at' => now()]);
    }
}
