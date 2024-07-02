<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function index(Request $request){

        $erro = '';
        
        if($request->get('erro') == 1){
            $erro = 'Usuário ou senha não correspondem';
        }

        return view('site.login', ['titulo' => 'Login', 'erro' => $erro]);
    }

    public function autenticar(Request $request){
        
        //regras de validação:
        $regras = [
            'usuario' => 'email',
            'senha' => 'required'
        ];

        //as mensagens de feedback de validação:

        $feedback = [
            'usuario.email' => 'O campo usuário(email) é obrigatório',
            'senha.required' => 'O campo senha é obrigatório'
        ];

        //valida os campos login e senha
        $request->validate($regras, $feedback);

        //caputrando os dados para consulta no banco
        $email = $request->get('usuario');
        $senha = $request->get('senha');

        #echo "$email, $senha, <br>";

        //inicia o model User
        $user = new User();

        //atribui os valores do formulário a uma variável para comparação no banco
        $usuario = $user->where('email', $email)
                    ->where('password', $senha)
                    ->get()
                    ->first();

        //testa se o usuário corresponde a um registro no banco
        if(isset($usuario->name)){
            
            session_start();

            $_SESSION['nome'] = $usuario->name;
            $_SESSION['email'] = $usuario->email;

            return redirect()->route('app.clientes');
        }else{
            //redireciona a rota e captura o erro após a tentativa de validação
            return redirect()->route('site.login', ['erro' => 1]);
        }
    }
}
