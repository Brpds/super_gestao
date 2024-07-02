<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\LogAcesso;

class LogAcessoMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        //manipular o request:
        $ip = $request->server->get('REMOTE_ADDR');
        $rota = $request->getRequestUri();
        LogAcesso::create(['log' => "IP $ip requisitou a rota $rota"]);

        //return $next($request);
        #return response('Chegamos no middleware e finalizamos aqui mesmo');

        //capturando o conteúdo de $next($request) para exibição
        $resposta = $next($request);
        //alterando um status code e customizando o texto de resposta
        $resposta->setStatusCode(201, 'Status Customizado');

        return $resposta;
    }
}
