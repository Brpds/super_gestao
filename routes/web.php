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
    Route::get('/home', [\App\Http\Controllers\HomeController::class, 'index'])->name('app.home');
    Route::get('/sair', [\App\Http\Controllers\LoginController::class, 'sair'])->name('app.sair');
    
    Route::get('/fornecedor', [\App\Http\Controllers\FornecedorController::class, 'index'])->name('app.fornecedor');
    Route::post('/fornecedor/listar', [\App\Http\Controllers\FornecedorController::class, 'listar'])->name('app.fornecedor.listar');
    Route::get('/fornecedor/listar', [\App\Http\Controllers\FornecedorController::class, 'listar'])->name('app.fornecedor.listar');
    Route::get('/fornecedor/adicionar', [\App\Http\Controllers\FornecedorController::class, 'adicionar'])->name('app.fornecedor.adicionar');
    Route::post('/fornecedor/adicionar', [\App\Http\Controllers\FornecedorController::class, 'adicionar'])->name('app.fornecedor.adicionar');
    Route::get('/fornecedor/editar/{id}/{msg?}', [\App\Http\Controllers\FornecedorController::class, 'editar'])->name('app.fornecedor.editar');
    Route::get('/fornecedor/excluir/{id}', [\App\Http\Controllers\FornecedorController::class, 'excluir'])->name('app.fornecedor.excluir');
    
    //produtos
    #Route::get('/produto', [\App\Http\Controllers\ProdutoController::class, 'index'])->name('app.produto');
    Route::resource('/produto', \App\Http\Controllers\ProdutoController::class);

    //produtos detalhes
    Route::resource('/produto-detalhe', \App\Http\Controllers\ProdutoDetalheController::class);

    Route::resource('/cliente', \App\Http\Controllers\CLienteController::class);
    Route::resource('/pedido', \App\Http\Controllers\PedidoController::class);
    #Route::resource('/pedido-produto', \App\Http\Controllers\PedidoProdutoController::class);
    Route::get('pedido-produto/create/{pedido}', [\App\Http\Controllers\PedidoProdutoController::class, 'create'])->name('pedido-produto.create');
    Route::post('pedido-produto/store/{pedido}', [\App\Http\Controllers\PedidoProdutoController::class, 'store'])->name('pedido-produto.store');
    #Route::delete('pedido-produto.delete/{pedido}/{produto}', [\App\Http\Controllers\PedidoProdutoController::class, 'destroy'])->name('pedido-produto.destroy');
    Route::delete('pedido-produto.delete/{pedidoProduto}/{pedido_id}', [\App\Http\Controllers\PedidoProdutoController::class, 'destroy'])->name('pedido-produto.destroy');
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