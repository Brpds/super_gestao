<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Teste extends Controller
{
    //recebendo os parâmetros passados pela rota
    public function teste(int $p1,int $p2){
        //echo "A soma de $p1 + $p2 é: " .($p1 + $p2);

        //passando parâmetros do controller para a view:
        //return view('site.teste', ['p1' => $p1, 'p2' => $p2]);//array associativo

        //return view('site.teste', compact('p1', 'p2'));//compact
        
        return view('site.teste')->with('p1', $p1)->with('p2', $p2);
    }
}