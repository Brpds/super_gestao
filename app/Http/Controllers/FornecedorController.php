<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FornecedorController extends Controller
{
    public function index(){

        $fornecedores = [
            0 => [
                'nome' => 'Fornecedor 1',
                'status' => 'N',
                'cnpj' => '00.000.00/000-00',
                'ddd' => '11', //sp
                'telefone' => '0000-0000'
            ],
            1 => [
                'nome' => 'Fornecedor 2',
                'status' => 'S',
                'cnpj' => null,
                'ddd' => '85', //fortaleza ceará
                'telefone' => '0000-0000'
            ],
            2 => [
                'nome' => 'Fornecedor 3',
                'status' => 'S',
                'cnpj' => null,
                'ddd' => '32', //juiz de fora MG
                'telefone' => '0000-0000'
            ]
        ];

        //operadores ternários
        //$msg = isset($fornecedores[1]['cnpj']) ? 'Is Set' : 'Is not set';
        //echo $msg
        return view('app.fornecedor.index', compact('fornecedores'));
    }
}
