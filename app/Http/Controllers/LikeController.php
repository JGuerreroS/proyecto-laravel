<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Like;

class LikeController extends Controller{

    // Restringir acceso a solo usuarios identificados
    public function __construct(){
        $this->middleware('auth');
    }

    // Método like
    public function like($image_id){
        
        // Obtener datos del usuarios
        $user = \Auth::user()->id;

        // Verificar si el like ya existe previante
        $issent_like = Like::where('user_id',$user)->where('image_id',$image_id)->count();

        if($issent_like == 0){
            
            // Instanciar el objeto like
            $like = new Like();
    
            // Asignar valores al objeto like
            $like->user_id = $user;
            $like->image_id = (int)$image_id;
    
            // Guardar
            $like->save();

            return response()->json(['like' => $like]);

        }else{

            return response()->json(['message' => 'Ya existe el like']);

        }

    }

    // Método dislike
    public function dislike($image_id){
        # code...
    }

}