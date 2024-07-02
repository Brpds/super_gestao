<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MotivoContato;

class PrincipalController extends Controller
{
    public function Principal(){
        $motivo_contatos = MotivoContato::all();
        #dd($motivo_contatos);
        return view('site.principal', ['motivo_contatos' => $motivo_contatos]);
    }
}
