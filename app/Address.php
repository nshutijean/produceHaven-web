<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = [
        'user_id', 'province', 'district', 'streetNumber', 'houseNumber',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
