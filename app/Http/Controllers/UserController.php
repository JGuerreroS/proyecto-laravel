<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller{


    // Cargar vista de configuración
    public function config()    {
        
        return view('user.config');

    }

    // Recibir datos de configuración
    public function update(Request $request){

        $id = \Auth::user()->id;

        $validate = $this->validate($request, [
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'nick' => ['required', 'string', 'max:255', 'unique:users,nick,'.$id],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$id]
        ]);

        $name = $request->input('name');
        $surname = $request->input('surname');
        $nick = $request->input('nick');
        $email = $request->input('email');

        var_dump($id);
        var_dump($name);
        var_dump($nick);
        die();
    }

}
