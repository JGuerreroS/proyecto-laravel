<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ImageController extends Controller{

    // Autenticar usuario de la sesión
    public function __construct(){

        $this->middleware('auth');

    }

    // Vista de añadir nuevas imagenes
    public function create(){
        return view('image.create');
    }

}