<?php

namespace App\Http\Controllers;

use App\Http\Requests\QueriesRequest;
use SweetAlert2\Laravel\Swal;
use App\Models\Query;

class QueriesController extends Controller
{
    public function create(){
        return view('pages.public.queries');
    }
    
    public function store(QueriesRequest $request){
        $datos = $request->validated();
        Query::create($datos);
        Swal::success([
            'title' => '!Hecho!',
            'text' => '¡La consulta se ha procesado correctamente!'
        ]);
        return redirect()->back()->with('swal_success', '¡Recibido! Nos pondremos en contacto a la brevedad.');
    }
}
