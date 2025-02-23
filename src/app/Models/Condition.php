<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Condition extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function items() {

        return $this->hasMany(Item::class);
    }

    public static function getConditions()
    {
        $conditions = Condition::all();
        
        return $conditions;
    }
}
