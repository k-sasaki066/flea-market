<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'message_id',
        'image_url',
    ];

    public function message()
    {
        return $this->belongsTo(Message::class);
    }
}
