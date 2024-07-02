<?php

namespace App\Http\Controllers;

use App\Http\Middleware\LogAcessoMiddleware;
use Illuminate\Http\Request;

class SobreNos extends Controller
{

    /* VERIFICAR O PORQUÊ DESTA IMPLEMENTAÇÃO RETORNAR ERRO.
    UNDEFINED METHOD MIDDLEWARE
    public function __construct()
    {
        $this->middleware(LogAcessoMiddleware::class);
    }*/
    
    public function SobreNos(){
        return view('site.sobre-nos');
    }
}
