<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class UserController extends Controller{


    // Cargar vista de configuración
    public function config()    {
        
        return view('user.config');

    }

    // Recibir datos de configuración
    public function update(Request $request){

        // Conseguir usuario de la sesión
        $user = \Auth::user();

        $id = $user->id;

        // Validar formulario de configuración
        $validate = $this->validate($request, [
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'nick' => ['required', 'string', 'max:255', 'unique:users,nick,'.$id],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$id]
        ]);

        // Obtener los datos del formulario
        $name = $request->input('name');
        $surname = $request->input('surname');
        $nick = $request->input('nick');
        $email = $request->input('email');

        // Asignar nuevos valores al objeto del usuario
        $user->name = $name;
        $user->surname = $surname;
        $user->nick = $nick;
        $user->email = $email;

        // Subir la imagen
        $imagen_path = $request->file('image_path');

        if($imagen_path){

            // Colocar nombre unico a la imagen
            $imagen_path_name = time().$imagen_path->getClientOriginalName();

            // Guardar imagen en la carpeta storage/app/users
            Storage::disk('users')->put($imagen_path_name,File::get($imagen_path));

            // Asignar nombre de la imagen al objeto
            $user->image = $imagen_path_name;

        }

        // Ejecutar consulta y cambios en la base de datos
        $user->update();

        return redirect()->route('config')->with(['message'=>'Usuario actualizado correctamente']);

    }

    public function getImage($filename){
        
        $file = Storage::disk('users')->get($filename);
        return new Response($file,200);
    }


}