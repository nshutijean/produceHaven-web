<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

class Product extends Model implements Searchable
{
    use SoftDeletes;

        protected $fillable = [
            'name', 'price', 'units', 'user_id', 'description', 'image', 'category', 'certificate', 'qrcodeUrl', 'expires_at'
        ];

        public function getSearchResult(): SearchResult {
            $title = "$this->name $this->price $this->units";
            // $url = route("/api/products/$this->id", $this->id);
            return new SearchResult($this, $title);
        }

        public function orders(){
            return $this->hasMany(Order::class);
        }

        public function user()
        {
            return $this->belongsTo(User::class, 'user_id');
        }
}
