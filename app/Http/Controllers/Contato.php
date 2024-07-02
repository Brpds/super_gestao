<?php

namespace App\Http\Controllers;

use App\Models\MotivoContato;
use Illuminate\Http\Request;
use App\Models\SiteContato;

class Contato extends Controller
{
    public function Contato(Request $request){

        $contato = new SiteContato();
        /*echo '<pre>';
        print_r($request->all());
        echo '</pre>';
        echo $request->input('nome');
        echo '<br>';
        echo $request->input('email');*/

        /*$contato = new SiteContato();
        $contato->nome = $request->input('nome');
        $contato->telefone = $request->input('telefone');
        $contato->email = $request->input('email');
        $contato->motivo_contato = $request->input('motivo_contato');
        $contato->mensagem = $request->input('mensagem');
        
        print_r($contato->getAttributes());
        $contato->save();*/
        // $contato->fill($request->all());

        // print_r($contato->getAttributes());
        //$contato->create($request->all());
        //$contato->save();
        $motivo_contatos = MotivoContato::all();
        return view('site.contato', ['titulo' => 'Contato(Teste)', 'motivo_contatos' => $motivo_contatos ]);
    }

    public function Salvar(Request $request){
        //realizar a validação dos dados do $request
        $regras = [
            'nome' => 'required|min:3|max:40',
            'email' => 'email',
            'telefone' => 'required',
            'motivo_contatos_id' => 'required',
            'mensagem' => 'required|max:2000'
        ];

        $feedback = [
            'nome.min' => 'O Nome precisa ter pelo menos 3 caracteres',
            'nome.max' => 'O Nome tem um limite de 40 caracteres',
            'email.email' => 'Insira um email válido.',
            'motivo_contatos_id.required' => 'Selecione um motivo',
            'mensagem.max' => 'A mensagem tem um máximo de 2000 caracteres',

            'required' => 'O campo :attribute é obrigatório'
        ];

        $request->validate($regras,$feedback);
        SiteContato::create($request->all());

        return redirect()->route('site.index');
    }
}
