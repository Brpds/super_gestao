<?php

namespace App\Http\Controllers;

use App\Models\Fornecedor;
use Illuminate\Http\Request;

class FornecedorController extends Controller
{
    public function index(){
        return view('app.fornecedor.index');
    }

    public function listar(Request $request){

        $fornecedores = Fornecedor::with('produtos')->where('nome', 'like', '%'.$request->input('nome').'%')
        ->where('site', 'like', '%'.$request->input('site').'%')
        ->where('uf', 'like', '%'.$request->input('uf').'%')
        ->where('email', 'like', '%'.$request->input('email').'%')
        #aplica paginação. Necessita que a rota seja chamada via get
        ->paginate(4);

        return view('app.fornecedor.listar', ['fornecedores' => $fornecedores, 'request' => $request->all()]);
    }

    public function adicionar(Request $request){

        //inicia a variável de msg para confirmar cadastro posteriormente
        $msg = '';

        if($request->input('_token') != '' && $request->input('id') == ''){
            //validação:

            $regras = [
                'nome' => 'required|min:3|max:40',
                'site' => 'required',
                'uf' => 'required|min:2|max:2',
                'email' => 'email'
            ];

            $feedback = [
                'nome.min' => 'O campo nome deve ter no mínimo 3 caracteres',
                'nome.max' => 'O campo nome deve ter no máximo 40 caracteres',
                'uf.min' => 'O campo UF só poderá ter 2 caracteres',
                'uf.max' => 'O campo UF só poderá ter 2 caracteres',
                'email' => 'Insira um email válido',
                'required' => 'O campo :attribute deve ser preenchido'
            ];

            $request->validate($regras, $feedback);

            //instancia o model fornecedor e chama o método de criação
            $fornecedor = new Fornecedor();
            $fornecedor->create($request->all());

            //redirect

            //mensagem de sucesso caso os dados sejam cadastrados
            $msg = 'Dados enviados com sucesso!';
        }

        //editar
        /*se faz necessário testar se o token está preenchido e se
        o id não está vazio. existindo um ID significa que o item foi recuperado do
        banco e está passível de edição
        */
        if($request->input('_token') != '' && $request->input('id') != ''){
            $fornecedor = Fornecedor::find($request->input('id'));
            $btnTxt = 'Atualizar';

            $update = $fornecedor->update($request->all());
            
            //mensagem custom se o update foi realizado
            if($update){
                $msg = 'Atualizado com sucesso';
            }else{
                $msg = 'Erro ao tentar atualizar o registro';
            }

            return redirect()->route('app.fornecedor.editar', ['msg' => $msg, 'id' => $request->input('id'), 'botao' => $btnTxt]);
        }

        //cadastro
        return view('app.fornecedor.adicionar', ['msg' => $msg]);
    }

    //encontrando e editando o fornecedor no banco
    public function editar($id, $msg = '', $btnTxt = 'Atualizar'){
        $fornecedor = Fornecedor::find($id);
        
        return view('app.fornecedor.adicionar', ['fornecedor' => $fornecedor, 'msg' => $msg, 'btnTxt' => $btnTxt]);
    }

    //excluir
    public function excluir($id){
        Fornecedor::find($id)->delete();
        return redirect()->route('app.fornecedor');
    }
}
