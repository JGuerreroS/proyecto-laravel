<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use App\Image;
use App\Like;
use App\Comment;

class ImageController extends Controller{

    // Autenticar usuario de la sesión
    public function __construct(){
        $this->middleware('auth');
    }

    // Vista de añadir nuevas imagenes
    public function create(){
        return view('image.create');
    }

    // Guardar imagen
    public function save(Request $request){

        // Validación
        $validate = $this->validate($request,[
            'description' => 'required',
            'image_path' => 'required|image'
        ]);

        // Recibir los datos
        $image_path = $request->file('image_path');
        $description = $request->input('description');
        
        // Asignar valores al objeto
        $user = \Auth::user();
        $image = new Image();
        $image->user_id = $user->id;
        $image->description = $description;
        
        // Subir imagen
        if($image_path){
            $image_path_name = time().$image_path->getClientOriginalName();
            Storage::disk('images')->put($image_path_name, File::get($image_path));
            $image->image_path = $image_path_name;
        }

        $image->save();
        return redirect()->route('home')->with([
            'message' => 'La foto ha sido cargada correctamente'
        ]);

    }

    // Obtener imagenes publicadas
    public function getImage($filename){
        $file = Storage::disk('images')->get($filename);
        return new Response($file,200);
    }

    // Detalles de la imagen
    public function detail($id){
        $image = Image::find($id);
        return view('image.detail', ['image' => $image]);
    }

    // Eliminar publicación
    public function delete($id){
        $user = \Auth::user();
        $image = Image::find($id);
        $comments = Comment::where('image_id',$id)->get();
        $likes = Like::where('image_id',$id)->get();

        if ($user && $image && $image->user->id == $user->id) {
            // Eliminar comentarios
            if ($comments && count($comments) >= 1) {
                foreach ($comments as $comment) {
                    $comment->delete();
                }
            }
            // Eliminar likes
            if ($likes && count($likes) >= 1) {
                foreach ($likes as $like) {
                    $like->delete();
                }
            }
            // Eliminar imagen
            Storage::disk('images')->delete($image->image_path);
            // Eliminar registro de la imagen
            $image->delete();

            $message = ['message' => 'Imagen eliminada correctamente'];


        } else {
            $message = ['message' => 'La imagen no se ha podido eliminar'];
        }

        return redirect()->route('home')->with($message);
        
    }

    // Actualizar publicación
    public function edit($id){
        
        $user = \Auth::user();
        $image = Image::find($id);

        if ($user && $image && $image->user->id == $user->id) {
            return view('image.edit', ['image' => $image]);
        } else {
            return redirect()->route('home');
        }
        
    }

    public function update(Request $request){

        // Validación
        $validate = $this->validate($request,[
            'description' => 'required',
            'image_path' => 'image'
        ]);

        // Recibir los datos
        $image_id = $request->input('image_id');
        $image_path = $request->file('image_path');
        $description = $request->input('description');

        // Objeto de la base de datos
        $image = Image::find($image_id);
        $image->description = $description;
        
        // Subir imagen
        if ($image_path) {
            Storage::disk('images')->delete($image->image_path); // Eliminar foto anterior
            $image_path_name = time() . $image_path->getClientOriginalName();
            Storage::disk('images')->put($image_path_name, File::get($image_path));
            $image->image_path = $image_path_name;
        }

        // Actualizar registro
        $image->update();
        return redirect()->route('image.detail', ['id' => $image_id])->with([
            'message' => 'La publicación ha sido cargada correctamente'
        ]);

    }

}