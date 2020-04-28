<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Image;

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

}