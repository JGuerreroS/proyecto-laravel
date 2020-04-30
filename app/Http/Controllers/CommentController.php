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

    }

    public function delete($id){
        
        // Conseguir datos del usuario identificado
        $user = \Auth::user();

        // Conseguir objeto del comentario
        $comment = Comment::find($id);

        // Comprobar si soy el dueño del comentario o publicación
        if($user && ($comment->user_id == $user->id || $comment->image->user_id == $user->id)){

            $comment->delete(); // Se elimina el comentario

            // Redirección
            return redirect()->route('image.detail', ['id' => $comment->image->user_id])->with([
                'message' => 'Comentario eliminado correctamente'
            ]);

        }else{
            // Redirección
            return redirect()->route('image.detail', ['id' => $comment->image->user_id])->with([
                'message' => 'El comentario no se ha eliminado'
            ]);
        }
    }

}