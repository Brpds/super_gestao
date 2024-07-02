<?php

use App\Http\Middleware\LogAcessoMiddleware;
use Illuminate\Support\Facades\Route;

/*Route::get('/', function () {
    return 'Olá! Seja bem vindo ao curso!';
});*/

Route::get('/', [\App\Http\Controllers\PrincipalController::class, 'principal'])
    ->name('site.index')->middleware('log.acesso');

Route::get('/sobre-nos', [\App\Http\Controllers\SobreNos::class, 'sobrenos'])->name('site.sobrenos');
Route::get('/contato', [\App\Http\Controllers\Contato::class, 'contato'])->name('site.contato');
Route::post('/contato', [\App\Http\Controllers\Contato::class, 'salvar'])->name('site.contato');

Route::get('/login/{erro?}', [\App\Http\Controllers\LoginController::class, 'index'])->name('site.login');
Route::post('/login', [\App\Http\Controllers\LoginController::class, 'autenticar'])->name('site.login');
//agrupamento de rotas

Route::middleware('autenticacao:ldap, usuario')->prefix('/app')->group(function(){
    Route::get('/clientes', function (){  return 'clientes'; })->name('app.clientes');
    Route::get('/fornecedores', [\App\Http\Controllers\FornecedorController::class, 'index'])->name('app.fornecedores');
    Route::get('/produtos', function (){  return 'produtos'; })->name('app.produtos');
});

//passando parâmetros para o controller
Route::get('/teste/{p1}/{p2}', [\App\Http\Controllers\Teste::class, 'teste'])->name('teste');

Route::fallback(function(){
    echo 'Caminho não existe. <a href="'. route('site.index').'">Clique aqui</a> para ir para a página inicial';
});


/*

>>>INÍCIO ANOTAÇÕES<<<

//redirecionamento de rotas
Route::get('/rota1', function(){
    echo 'rota 1';
})->name('site.rota1');

//método 1: redirect do Route
//Route::redirect('/rota2','/rota1');

//método 2: na função de callback (deverá chamar a rota pelo name)
Route::get('/rota2', function(){
    return redirect()->route('site.rota1');
})->name('site.rota2');

//rotas de fallback (quando uma rota acessada não existe)
Route::fallback(function(){
    echo 'Caminho não existe. <a href="'. route('site.index').'">Clique aqui</a> para ir para a página inicial';
});

//envio de parâmetros nomerota/{param1}/{param2}/{paramN}
Route::get('/contato/{nome}/{categoria}/{assunto}/{mensagem}', function (string $nome, string $categoria, string $assunto, string $mensagem) {
    echo "Nome: $nome, \nCategoria: $categoria, \nAssunto: $assunto, \nMensagem: $mensagem";
});

Route::get('/contato/{nome}/{categoria_id}/', function (
    string $nome = 'Desconhecido',
    int $categoria_id = 1 //informação  
    ){
    echo "Nome: $nome, Categoria: $categoria_id";
    
    expressão regular indicando que ID deve ser um número, e o + indica
    que deve receber pelo menos um parâmetro
    
})->where('categoria_id','[0-9]+')
    ->where('nome','[A-Za-z]+');
    
    o segundo where indica que nome só poderá receber caracteres do
    alfabeto A-Z maiúsculos, a-z minúsculos.

Route::get('/sobre-nos', function () {
    return 'Sobre Nós';
});

Route::get('/contato', function () {
    return 'Contato';
});

>>>FIM ANOTAÇÕES<<<

*/