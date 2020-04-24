<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $table = "images";

    // Relación de 1 a muchos
    public function comments()    {
        return $this->hasMany('App\Comment');
    }

    // Relación de 1 a muchos
    public function likes(){
        return $this->hasMany('App\Like');
    }

    // Relación de muchos a 1
    public function user(){
        return $this->belongsTo('App\User','user_id');
    }
    
}
