<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AutenticacaoMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $metodo_autenticacao, $perfil): Response
    {

        //verificação dos parâmetros passados à rota

        if($metodo_autenticacao == 'padrao'){
            echo "Verificar usuário e senha no Banco de dados. Pefil exibido: $perfil <br>";
        }

        if($metodo_autenticacao == 'ldap'){
            echo "Verificar usuário e senha no Active Directory. Pefil exibido: $perfil <br>";
        }
        //verifica se o usuário tem acesso à rota:
        if(false){
            return $next($request); //passa a requisição adiante
        }else{
            //caso o usuário não esteja autenticado:
            return Response('Acesso negado, requer autenticação');
        }
    }
}
