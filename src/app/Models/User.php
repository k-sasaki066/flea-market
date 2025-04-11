<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'nickname',
        'post_cord',
        'address',
        'building',
        'image_url',
        'profile_completed'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'profile_completed' => 'boolean',
    ];

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function purchases() {

        return $this->hasMany(Purchase::class);
    }

    public function favorites() {

        return $this->hasMany(Favorite::class);
    }

    public function items() {

        return $this->hasMany(Item::class);
    }

    public function buyerTransactions()
    {
        return $this->hasMany(Transaction::class, 'buyer_id');
    }

    public function sellerTransactions()
    {
        return $this->hasMany(Transaction::class, 'seller_id');
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public static function getPaymentUser($userId)
    {
        $user = User::select('id', 'post_cord', 'address', 'building')->findOrFail($userId);

        return $user;
    }
}
