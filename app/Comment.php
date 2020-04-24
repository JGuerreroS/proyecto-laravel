<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model{

    protected $table = "comments";

    // Relación de muchos a 1
    public function user(){
        return $this->belongsTo('App\User','user_id');
    }

    // Relación de muchos a 1
    public function image(){
        return $this->belongsTo('App\Image','image_id');
    }
    
}
