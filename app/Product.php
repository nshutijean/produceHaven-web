<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

        protected $fillable = [
            'name', 'price', 'units', 'user_id', 'description', 'image', 'category', 'qrcodeUrl'
        ];

        public function orders(){
            return $this->hasMany(Order::class);
        }

        public function user()
        {
            return $this->belongsTo(User::class, 'user_id');
        }
}
