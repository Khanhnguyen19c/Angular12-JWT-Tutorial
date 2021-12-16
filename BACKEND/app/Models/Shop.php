<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;
    protected $table = 'shop';
    protected $fillable = ['shopname','user_id','address','hotline','images','taxcode','time_start','time_end'];

    public function user(){
        return $this->belongsTo(Shop::class);
    }
}
