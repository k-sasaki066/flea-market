<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'way',
    ];

    public function purchase()
    {
        return $this->hasMany(Purchase::class);
    }

    public static function getPayments()
    {
        $payments = Payment::all();

        return $payments;
    }
}
