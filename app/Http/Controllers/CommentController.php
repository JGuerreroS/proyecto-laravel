<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Comment;

class CommentController extends Controller{

    // Autenticar usuario de la sesión
    public function __construct(){
        $this->middleware('auth');
    }

    // 
    public function save(Request $request){

        // Validación del formulario
        $validate = $this->validate($request,[
            'image_id' => 'integer|required',
            'content' => 'string|required'
        ]);

        // Recoger datos del formulario
        $user = \Auth::user()->id;
        $image_id = $request->input('image_id');
        $content = $request->input('content');

        // Asignar valores al objeto
        $comment = new Comment();
        $comment->user_id = $user;
        $comment->image_id = $image_id;
        $comment->content = $content;

        // Guardar en la base de datos
        $comment->save();

        // Redirección
        return redirect()->route('image.detail', ['id' => $image_id])->with([
            'message' => 'Comentario públicado correctamente'
        ]);

        var_dump($content);
        var_dump($image_id);
        die();

    }

}