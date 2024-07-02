# Laravel


### configuração

#### repositório de packages do composer
    composer config -g repo.packagist composer https://packagist.org

## criando um projeto Laravel 

#### pelo cmd via composer

    composer create-project --prefer-dist laravel/laravel projeto_laravel_via_composer

**--prefer-dist diz respeito à preferência de instalação dos pacotes**
**laravel/laravel indica o proprietário dos pacotes**

#### pelo laravel installer:

    laravel new <nomeProjeto> //cria o projeto com nomeProjeto


# Artisan:

### php artisan list //mostra a lista de comandos do Artisan

### php artisan serve --port=XXXX(port opcional) //usado no lugar de php -S, serve a aplicação na porta indicada em XXXX. se deixada em branco, terá uma porta padrão

### php artisan down //coloca a aplicação em modo de manutenção, deixando-a indisponível para o acesso público e apresentando uma página de manutenção customizável

### php artisan up //restaura a aplicação do modo de manutenção.

## Rotas:

### Rotas são definidas dentro da pasta Routes na aplicação. são de 4 tipos:

### ***web, console, apis, e channels.***

## Rotas web: www.site.com/RouteName

### Formato genérico:

    Route::<httpMethod>($uri, $callbackFunction);


#### exemplo:

    Route::get('/contato', function () {
        return 'Contato';
    });

***get é o método http usado. pode ser também put, post, patch delete***

***'/contato' será a $uri definida, o caminho que aquela rota levará***

***function () {return 'Contato';} é a função de callback que define o retorno da rota quando acessada***

## Controllers:

### são as funções que definem regras para as rotas, podem ser criadas usando o artisan, com o comando e nome em CamelCase:

    php artisan make:controller NomeController

### definido o controller, podemos chamá-lo na rota no seguinte molde

    Route::get('/', [\App\Http\Controllers\NomeController::class, 'NomeClasse']); //NomeController é o nome do arquivo do controller, enquanto NomeClasse é o nome da função que será acessada no controller.


## Views:

### para criar views, na pasta principal do projeto, no diretório resources, há a pasta views. Ela armazenará as views do projeto.

### os arquivos devem ser salvos no modo nomeView.blade.php - blade é o processador de views

### para chamar as views, nos controllers, dentro das actions simplesmente retornamos usando a função view(); o nome/caminho da view separado por .

    class Contato extends Controller
    {
        public function Contato(){
            return view('site.contato'); /*view contato está dentro da pasta site*/
        }
    }

## Parâmetros

### os parâmetros encaminhados pelas rotas são recebidos através do arquivo web.php na pasta de rotas. são passados entre chaves, quando indicados na rota:

    www.site.com.br/{param1}/{paramN} //o endereço acessado

    Route::get('www.site.com.br/{param1}/{paramN}', function ($param1, $paramN) {
        echo "Parâmetro 1 é: $param1. Parâmetro N é: $paramN";
    });

***os parâmetros serão recebidos na função na sequência em que são definidos, independente do nome dado na sua passagem, mas por boas práticas, usar nomes similares***

### parâmetros podem ser definidos como opcionais, simplesmente adicionando uma interrogação ao fim do nome do parâmetro.

    Route::get('www.site.com.br/{param1?}/{paramN?})

***? ao fim do nome do parâmetro indica que ele é opcional***

***como regra, para evitar erros de parametrização e para que o Laravel não se perca durante o processo de recebimento de parâmetros, deve-se passar parâmetros opcionais de modo sequencial da direita para esquerda:***

### válido:

    Route::get('www.site.com.br/{param1?}/{param2?}/{param3?}/{paramN?})

### inválido:

    Route::get('www.site.com.br/{param1?}/{param2}/{param3}/{paramN?})

### para parâmetros padrão, basta atribuir valores padronizados quando passados na função (callback ou não):

    Route::get('www.site.com.br/{param1?}/{paramN?}, function(
        param1 = 'Sou o parâmetro 1',
        paramN = 'Sou o parâmetro N'
    ){})

## tratamento de parâmetros com expressões regulares:

### os parâmetros podem ser tratados com o uso de expressões regulares, usando a função ->where(param1,param2) logo ao final do get, recebendo 2 parâmetros: o nome do parâmetro e o tipo de entrada (numérico, string, etc), podendo receber um símbolo +, indicando que o parâmetro deve ser de pelo menos 1 caractere. Ex:

    Route::get('/contato/{nome}/{categoria_id}/', function (
        string $nome = 'Desconhecido',
        int $categoria_id = 1 //informação  
        ){})->where('categoria_id','[0-9]+')
***acima, a expressão regular indica que ID deve ser um número, e o + indica que deve receber pelo menos um parâmetro***
    ->where('nome','[A-Za-z]+');
***condições where() podem ser declaradas múltiplas vezes, esta indicando que o parâmetro nome deve ser uma letra de A-Z maiúsculo, ou a-z minúsculo, e o + indicando que deve ser passado pelo menos um caractere. Caso o parâmetro seja opcional, o valor padrão indicado 1 será aplicado***

## php artisan route:list - lista todas as rotas configuradas no projeto


## Agrupamento de rotas:

### rotas podem ser agrupadas sob um determinado prefixo, fazendo com que não seja necessário colocar múltiplas vezes o mesmo prefixo dentro do path da rota:

    Route::prefix('/NomeRota')->group(function(){});

***Route::prefix() recebe uma string com o nome que agrupará a rota, seguido da chamada de ->group() que recebe uma função de callback contendo as rotas agrupadas***

### Ex:

    Route::prefix('/app')->group(function(){
        Route::get('/login', function (){  return 'login'; });
        Route::get('/clientes', function (){  return 'clientes'; });
        Route::get('/fornecedores', function (){  return 'fornecedores'; });
        Route::get('/produtos', function (){  return 'produtos'; });
    });

## Nomes de rotas (nicknames):

### é possível nomear rotas, fazendo com que seu acesso se dê por meio da chamada dos nicknames das rotas ao invés de chamá-las via pathName.

***declaramos no fim da função Route::get() a terminação ->name('') que recebe como parâmetro a string que indica o nome da rota***

    Route::get('/produtos', function (){  return 'produtos'; })->name('site.produtos');

***nesse caso, sempre que quisermos acessar a rota, chamamos dentro de chaves duplas route('nomeRota'), em substituição ao caminho***

    <a href="{{ route('site.produtos')}}">Produtos</a>

***dessa forma, mesmo que o caminho mude na função Route, o name dado ainda será capaz de acessar o path definido pela função Route***

## redirecionamento de rotas:

### o redirecionamento de rotas pode ser feito de duas formas:

### na primeira, podemos fazer o redirecionamento através da função redirect($param1, $param2) do Route, passando dois parâmetros:

    Route::redirect('/rota2','/rota1'); 
***quando acessada a rota1, redirecionará para a rota2***

### na segunda forma, colocamos o redirecionamento diretamente na função de callback, chamando o método redirect()->route('nomeRota'); passando o nome da rota, no lugar do path:

    Route::get('/rota2', function(){
        return redirect()->route('site.rota1');
    })->name('site.rota2'); 
***ao acessar rota2, será redirecionado para a rota nomeada site.rota1***

## Rotas de fallback/contingência

### para rotas de fallback quando rotas inexistentes forem acessadas, basta chamar a função Route::fallback(function(){}); passando por parâmetro a função de callback que retornará o destino da rota não encontrada

    Route::fallback(function(){
        echo 'Caminho não existe. <a href="'. route('site.index').'">Clique aqui</a> para ir para a página inicial';
    }); 
***qualquer rota inexistente acessada retornará a mensagem indicada (pode ser também uma view ou controller)***

## passagem de parâmetros das rotas para os controllers:

### para passar parâmetros das rotas para os controllers, declaramos no objeto route o parâmetro a ser passado e no controller, dentro da função(action), o nome de variáveis que receberão os parâmetros, sempre na ordem em que são passados:

    Route::get('/teste/{p1}/{p2}', [\App\Http\Controllers\Teste::class, 'teste'])->name('teste'); 
***os parâmetros p1 e p2 serão passados para o controller teste***

    class Teste extends Controller
    {
        public function teste(int $p1,int $p2){
            echo "A soma de $p1 + $p2 é: " .($p1 + $p2);
        }
    } 
***a classe teste receberá os parâmetros p1 e p2 passados, atribuindo-os nas variáveis $p1 e p2, respectivamente (o nome de variável não precisa ser idêntico ao do parâmetro)***

## passagem de parâmetros do controller para a visualização:

### pode ser feito de 3 formas: array associativo, compact(), ->with()

### Array Associativo: são passados como segundo parâmetro na função view, com os nomes dos parâmetros associados às variáveis correspondentes

    public function teste(int $p1,int $p2){
        return view('site.teste', ['p1' => $p1, 'p2' => $p2]);
    }
***as variáveis $p1 e $p2 terão seus nomes associados 'p1' e 'p2', respectivamente, como declarados, sendo chamadas na view como {{ $p1 }} e {{ $p2 }}***

### compact('param1', 'paramN'): compact cria um array associativo, sendo declarado como segundo parâmetro dentro da função view e declarando-se apenas como string o nome associativo do parâmetro passado, igual ao da variável. É O MAIS COMUM DE SER USADO

    public function teste(int $p1,int $p2){
        return view('site.teste', compact('p1', 'p2')); 
    }
***as variáveis aqui também serão chamadas na view como {{ $p1 }} e {{ $p2 }}***

### ->with(): neste caso, não é passado nenhum parâmetro para a função view, mas chama-se ao final dela com a atribuição de string/valor, sendo necessário 1 chamada de with para cada variável

    public function teste(int $p1,int $p2){
        return view('site.teste')->with('p1', $p1)->with('p2', $p2);
        
    }
***as variáveis também serão chamadas na view como {{ $p1 }} e {{ $p2 }}***

## Comentários em Blade

### para inserir comentários nas views do blade, basta utilizarmos {{-- --}}. qualquer coisa colocada dentro dos hífens será descartada completamente, não sendo sequer visualizada pelo inspect dos navegadores.

### para blocos de PHP puro, basta usar @php //código aqui @endphp para inserir códigos de php diretamente

### as chaves duplas no blade {{}} tem a mesma função das tags de impressão do PHP


## blocos de código com @if()@else @endif

### nas views do blade, é possível criar blocos de ifelse diretamente com o @if(condição) @elseif(condição2) @else, finalizando com @endif

    @if(count($fornecedores) > 0 && count($fornecedores) < 10)
        <h3>Existem Fornecedores cadastrados</h3>
    @elseif(count($fornecedores) >= 10)
        <h3>Existem muitos fornecedores cadastrados</h3>
    @else
        <h3>Não existem fornecedores cadastrados</h3>
    @endif

***no exemplo, um array de fornecedores foi declarado no controller e passado via compact para a view. O blade não consegue imprimir arrays diretamente {{ $nomeArray }}, porém, podemos fazer em duas formas:***

***usando @dd($nomeArray), que imprimirá o array em um formato específico do blade***

    @dd($fornecedores)

***acessando o índice do array diretamente {{$nomeArray[i]}}***

    {{ $fornecedores[N]}}

## @unless(): a função do blade que verifica uma condição contrária/negativa (a menos que) para dar o resultado, sendo o equivalente do !/not.

    @unless( 'x' == 'x') ***caso x NÃO seja == x, o output será impresso***
        //output aqui
    @endunless

## @isset() @endisset - verifica se uma variável está declarada dentro do controller E retornada para a view (no compact, ->with() ou diretamente como array associativo). Se a variável estiver declarada, o código dentro do @isset/@endisset será executado. Não estando declarada, o código será descartado. ex: 

    @isset($fornecedores)

        Fornecedor: {{ $fornecedores[0]['nome']}}
        <br>
        Status - Ativo? {{$fornecedores[0]['status']}}
        <br>
        @isset($fornecedores[0]['cnpj'])***isset 
            CNPJ: {{$fornecedores[0]['cnpj']}}
        @endisset

    @endisset

## @empty() @endempty - verifica se uma variável está vazia, retornando true caso ela esteja. Enquanto o isset verifica se a variável está declarada ou não, empty verifica - retornando true - se a variável foi declarada com algum dos seguintes valores:

    - ''
    - 0
    - 0.0
    - '0'
    - null
    - false
    - array[] //declarado vazio
    - $var //somente declarada, sem atribuição

## operadores ternários:

### é possível definir operações com ternários em substituição aos if/else em consultas mais simples: 

    $msg = isset($fornecedores[index]['cnpj']) ? 'Is Set' : 'Is not set';
    echo $msg; 
***caso true, a mensagem retornada é Is Set, caso false, a mensagem retornada é Is not set***

###  Operadores ternários podem ser encadeados dentro dos retornos:

    if(True) ? 'Retorno 1' : (condicao2 ? 'Retorno 1 cond 2' : 'Retorno 2 cond 2);

## operador condicional de valor default do Blade:

### o blade dispõe de um operador de valor condicinal padrão, o ??. Ele testa para a existência da variável a ser impressa. Caso a variável exista, à exceção do null, o valor do operador será true - diferente do empty, 0, '', 0.0, '0' serão impressos/retornados true.

    CNPJ: {{ $fornecedores[0]['cnpj'] ?? 'Valor Padrão'}} 
***se cnpj em fornecedores existir e não for nulo, seu valor será retornado. caso não exista ou seja nulo retornará 'Valor Padrão'***

## switch case:

### no blade, também pode-se utilizar o switch case, no seguinte formato:

    @switch($fornecedores[1]['ddd'])

            @case('11')
                São Paulo - SP
                @break 
            @case('32')
                Juiz de Fora - MG
                @break
            @case('85')
                Fortaleza - CE
                @break
            @default
                Estado não identificado.
    @endswitch
***os testes devem terminar em @break, sendo também necessário o default***

## FOR

### o blade também pode receber o for

    @for($i = 0; $i < 10; $i++)

        {{$i}} <br>

    @endfor

## WHILE

### while também está disponível no blade. a diferença é que como não há um incrementador de variável dentro do while, precisamos declarar a variável externamente usando o @php

    @php $i = 0 @endphp ***declaração da variável a ser usada no while***
       @while(isset($fornecedores[$i]))
           Fornecedor: {{ $fornecedores[$i]['nome']}}
           <br>
           Status - Ativo? {{$fornecedores[$i]['status']}}
           <br>
           CNPJ: {{ $fornecedores[$i]['cnpj'] ?? 'Valor Padrão'}}
           <br>
           Telefone: {{ $fornecedores[$i]['ddd'] ?? ''}} {{$fornecedores[$i]    ['telefone'] ?? ''}}
           <hr>
           @php $i++ @endphp ***o incremento de variável é feito aqui***
       @endwhile

## FOREACH:

### idêntico ao foreach do PHP

    @foreach($fornecedores as $indice => $fornecedor)
            Fornecedor: {{ $fornecedor['nome']}}
            <br>
            Status - Ativo? {{$fornecedor['status']}}
            <br>
            CNPJ: {{ $fornecedor['cnpj'] ?? 'Valor Padrão'}}
            <br>
            Telefone: {{ $fornecedor['ddd'] ?? ''}} {{$fornecedor['telefone'] ?? ''}}
            <hr>
    @endforeach

## FORELSE

### o forelse é específico do blade e funciona de modo parecido com o foreach. a diferença é que é possível desviar o fluxo caso a variável esteja vazia.

    @forelse($fornecedores as $indice => $fornecedor)
            Fornecedor: {{ $fornecedor['nome']}}
            <br>
            Status - Ativo? {{$fornecedor['status']}}
            <br>
            CNPJ: {{ $fornecedor['cnpj'] ?? 'Valor Padrão'}}
            <br>
            Telefone: {{ $fornecedor['ddd'] ?? ''}} {{$fornecedor['telefone'] ?? ''}}
            <hr>
            ***caso a variável esteja vazia, o empty será retornado***
            @empty 
                Não existem fornecedores cadastrados!!!
    @endforelse

## Assets:

### para assets, o blade dispõe de um meio de adicioná-los diretamente aos arquivos phtml. usando a tag {{asset('pathName')}}, onde o blade buscará dentro da pasta public o arquivo no caminho passado:

    <img src="{{asset('img/youtube.png')}}"> 
    <link rel="stylesheet" href="{{ asset('/css/estilo_basico.css') }}">

***asset buscará na pasta img dentro de public o arquivo com nome youtube.png, e também pode ser usado para incluir folhas de estilo css***

## Templpates:

### o blade também é capaz de receber templates html, usando @extends('nomeTemplate'), @section('nomeSection) @endsection. 

### Primeiro passo: definimos uma página blade básica dentro das views (opcional, criar uma pasta para conter os layouts).

    templatePage.blade.php

### Segundo Passo: definido o layout padrão e dentro de sua marcação HTML colocamos o @yield('NomeSection'), que será a posição em que o conteúdo será exibido:

    <body>

        @yield('nomeSection')

    </body>

***todo o conteúdo será exibido dentro das tags body***

### Posteriormente, na página blade que receberá a template, definimos no começo @extends(caminhoNomeTemplate), fazendo com que o blade reconheça que aquela página herdará o template indicado:

    @extends('site.layouts.templatePage')

***extends buscará a página templatePage dentro das pastas site>layouts***

***extends buscará diretamente na pasta views, em toda sua extensão pela página indicada, sendo que seu caminho deve ser passado separado por .***

### Daí indicamos o nome da seção dentro das tags @section(nomeSection) @endsection:

    @extends('site.layouts.basico')
***extends procurará por basico.blade.php dentro das pastas site>layouts***

    @section('conteudo')

        HTML here!!!

    @endsection


### Section pode receber variáveis passadas pelos controllers, diretamente.

    @section('titulo', 'Sou o Título')
***podemos passar como string diretamente no section, sem o @endsection***

    @section('titulo', $titulo)
***o blade entende que $titulo é uma variável php, declarada nessa forma***

### as variáveis são passadas nos controllers normalmente, via compact, array associativo ou with.

    return view('site.contato', ['titulo' => 'Contato(Teste)']);

### e logo após, são impressas no template no respectivo local indicado:

    <title>Super Gestão - @yield('titulo')</title>

## Include:

### o blade também pode incluir "parciais" através do include, que permite incluir trechos específicos em locais específicos da página - semelhante aos components front-end do react/angular, facilitando a reusabilidade de código

    @include('site.layouts._partials.topo')

***include também buscará dentro da pasta views, semelhante ao @yield. no exepmlo, buscará dentro de views o arquivo topo nas pastas site>layouts>_partials***

## Forms:

### no envio de formulários, no laravel enviamos via route:

    <form action="{{route('nomeRoute')}}" method="get">

### para envio de formulários via post, precisamos seguir alguns passos, sendo o primeiro deles criar a rota que receberá o formulário usando o verbo post:

***de***
    Route::get('/contato', [\App\Http\Controllers\Contato::class, 'contato'])->name('site.contato');

***para***
    Route::post('/contato', [\App\Http\Controllers\Contato::class, 'contato'])->name('site.contato');

***as rotas podem utilizar o mesmo name e controller, visto que o blade discrimina o tipo de verbo do método do formulário***

### o próximo passo é colocar logo abaixo da tag do formulário, o token @csrf, responsável por garantir a integridade e segurança dos dados passados no formulário

    <form action="{{route('site.contato')}}" method="post">
    @csrf //cross site request forgery


## @Components @endcomponent:

### o blade também aceita components, semelhante ao include. A diferença basicamente está na passagem de parâmetros. podem ser passados diretamente entre as tags:

    @component('site.layouts._components.form_contato')
    @endcomponent

***component buscará diretamente dentro da pasta views, em toda sua extensão. no exemplo, ele procurará por form_contato dentro das pastas views>layouts._components***

### é possível passar parâmetros das views para os componentes, de duas formas diferentes:

***entre as tags @component() @endcomponent, indicando no componente onde ela será impressa através do {{ $slot }}:***

    @component('site.layouts._components.form_contato')
        <p>A equipe analisará sua mensagem e retornará o contato</p>
        <p>O tempo médio de resposta é de 48h úteis.</p>
    @endcomponent

***no componente, definimos onde o html será impresso posicionando a tag {{ $slot }}***

    {{ $slot }} //slot ficará logo acima do componente
    <form action="{{route('site.contato')}}" method="post">
     //code here
    </form>

***ou podemos passar como segundo parâmetro na view, através de um array associativo na chamada da função @component():***

    @component('site.layouts._components.form_contato', ['classe' => "borda-preta"])
    @endcomponent

***e no component, indicamos onde ele será impresso através do posicionamento do índice do array***

    <input name="nome" type="text" placeholder="Nome" class="{{ $classe }}">

***indicando que no componente, quando carregado, a classe recebida será borda-preta, no exemplo***

### as views podem passar parâmetros diferentes para seus componentes, sendo que cada uma pode implementar seus parâmetros de forma dinâmica. Ainda é necessário sempre declarar o parâmetro quando um mesmo component for carregado pela view.

## Models:

### models são recursos do laravel que permitem o uso de orientação a objetos para a reusabilidade de código. Para criar um model, usamos o comando:

    php artisan make:model NomeModel -m

***o nome do model deverá sempre ser no singular***
***-m significa migration, que é um requisito para o model sempre que aquele model precisar armazenar dados dentro do DB***

## Migrations:

### as migrations dizem respeito à configuração do banco de dados, "sem o uso do sql", deixando que o framework se encarregue de utilizar essa linguagem.

### dentro da pasta migrations, após criada a migration (feita automaticamente junto com o model), devemos consultar a documentação do Laravel para identificar os tipos de dados que serão incluídos nas tabelas.

    $table->id();
    $table->timestamps();
    $table->string('nome', 50);
    $table->string('telefone', 20);
    $table->string('email', 80);
    $table->integer('motivo_contato', 1);
    $table->text('mensagem');
    //$table é padrão da migration

***$table->tipoDoDado('nomeDaColuna', qtdeCaracteres), onde quantidade de caracteres indica a quantidade de caracteres do tipo de dado serão inseridos na coluna***

***as migrations já vem por padrão com as colunas id e timestamps.***

## Os databases:

### as conexões de database estão disponíveis dentro de database.php, na pasta de database. Escolhido o database a ser utilizado, deve-se ir no arquivo .env na pasta principal do projeto, e configurar DB_CONNECTION=nomeDatabase, sendo que nomeDatabase recebe um dos nomes de database disponíveis.

    DB_CONNECTION=sqlite 
***aqui a conexão será feita com o sqlite***

### posteriormente, dentro do arquivo database.php, no array associativo correspondente ao database selecionado, configuramos a url de conexão:

    'sqlite' => [
                ...,
                'database' => env('DB_DATABASE', database_path('database.sqlite')),
                ...
    ]

***o primeiro parâmetro se refere ao nome da linha no arquivo .env que indica qual database será conectado***

***a segunda linha indica o path do database dentro da pasta database. no caso, o arquivo database.sqlite, caso não exista, deverá ser criado***

### criadas as migrations, executamos no console:

    php artisan migrate

## Conexão do banco de dados(workbench/general config):

### para conectar a um banco de dados, precisa-se primeiro verificar no array associativo em database.php o tipo de database. Após identificar o tipo de banco, configuramos em .env as conexões:

    DB_CONNECTION=myqsl //tipo do banco a conectar
    DB_HOST=127.0.0.1 //endereço do banco
    DB_PORT=3306 //porta
    DB_DATABASE=sg //nome do database - no caso, super gestão
    DB_USERNAME=root //usuário
    DB_PASSWORD=root //senha

***essas configurações por padrão estão comentadas em .env, com apenas DB_CONNECTION indicando database.sqlite como padrão, que caso não esteja na pasta de database, deverá ser criado***

### o banco usa uma instância do PDO. Para verificar se a extensão do PDO está carregada, usamos o comando:

    php -r "var_dump(extension_loaded('pdo_mysql'));"

***deverá retornar true. Se retornar false, ir no php.ini e habilidar a extensão PDO no ambiente***

### é possível fazer migrations de maneira manual (sem usar o -m na criação do model)

    php artisan make:migration create_NOMETABELA_table

***essa nomenclatura segue o padrão de nomes do próprio laravel***

## alterando tables:

### para alterar tables via migration, por convenção de desenvolvimento incremental, criamos uma nova migration com nome referente à alteração que será feita naquela migration:

    php artisan make:migration alter_fornecedores_novas_colunas
***esta migration indica que alterará a tabela fornecedores, adicionando novas colunas***

### posteriormente, no arquivo .php da migration, em up(){}, adicionamos as novas colunas, usando o método table do objeto Schema:

    public function up(): void
        {
            Schema::table('fornecedores', function (Blueprint $table) {
                $table->string('uf', 2);
                $table->string('email', 150);
            });
        }

## Migrations: up(), down(), rollbacks.

### nas migrations há as funções Up and down, sendo que up é responsável pela criação/alteração progressiva do conteúdo, enquanto down é responsável pela destruição/regresão do conteúdo - capaz de desfazer o que a função up da migration fez. Ex:

    public function up(): void
        {
            Schema::table('fornecedores', function (Blueprint $table) {
                $table->string('uf', 2);
                $table->string('email', 150);
            });
        }
***dentro da migration de add column, a função UP é responsável por adicionar as novas colunas***

    public function down(): void
        {
            Schema::table('fornecedores', function (Blueprint $table) {
                //remover colunas
                //$table->dropColumn('uf');
                //$table->dropColumn('email);
                >>> ou <<<
                $table->dropColumn(['uf', 'email']);
            });
        }
***down recebe a função que desfaz a adição das colunas***

***o drop column de $table pode receber 1 parâmetro por vez ou um array com os nomes das colunas a serem dropped***

## Aplicando migrations no contexto de down()/rollback

### para aplicar as funções definidas em down, usamos o comando:

    php artisan migrate:rollback

***rollback, por padrão, desfará 1 migration por vez (step), da última, em direção à primeira***

### para aplicar rollback para vários passos de migration, usamos a terminação --step=Número, definindo a quantidade de passos(steps) que desejamos voltar:

    php artisan migrate:rollback --step=3

***--step=3 indica que serão desfeitas as 3 últimas migrações. o número relativo à quantidade de migrações está na coluna batch da tabela migrations, indicando qual foi a ordem de execução da criação das migrations***

## Valores default e nullable nas migrations:

### as migrations podem receber valores nullable ou default, adicionando ao final das declarações de colunas:

    $table->integer('peso')->nullable();
    $table->float('preco_venda', 8, 2)->default(0.01);


***peso pode receber um valor null/não receber valor, enquanto preco_venda recebe por padrão 0.01 caso nenhum valor seja definido***

## Adicionando foreign keys 1 para 1:

### para adicionar foreign keys, algumas considerações devem ser feitas:

    $table->unsignedBigInteger('produto_id');

***primeiro, deverá ser verificado na tabela de origem o TIPO da variável que será usada, para que na tabela que receberá a FK possa armazená-la corretamente. No exemplo, a coluna ID na tabela de referência produto é do tipo unsignedBigInteger - unsigned significa somente positivos***

***depois, aplicamos a convenção do laravel, usando nomeTabelaRef_nomeColunaRef para nome da coluna da FK. no exemplo, a tabela de referência é a tabela produto e a coluna é a coluna id***

### aplicar as constraints:

    $table->foreign('produto_id')->references('id')->on('produtos');$table->unique('produto_id');

### explicando:

->foreign('produto_id') //indica que esta coluna é a FK
->references('id') //indica que esta é a coluna de referência
->on('produtos') //indica que esta é a tabela de referência

    $table->unique('produto_id'); //indica que o relacionamento é 1 para 1, dado que os dados da coluna devem ser únicos$

## relacionamento FK 1 para muitos:

### semelhante ao 1 para 1, apenas não criamos a constraint $table->unique

## relacionamento muitos para muitos:

### em um relacionamento muitos para muitos, é necessária uma tabela auxiliar que recebe chaves estrangeiras de múltiplas tabelas, sendo necessário apenas a devida criação das constraints

    $table->foreign('filial_id')->references('id')->on('filiais');
    $table->foreign('produto_id')->references('id')->on('produtos');

## posicionando colunas com AFTER

### é possível posicionar colunas usando as migrations. por padrão, as colunas são sempre colocadas à direita. caso deseje-se posicionar a coluna em um outro local, basta usar a função ->after(nomeColuna), passando por parâmetro a coluna que ficará à esquerda da nova adicionada.

    $table->string('site', 150)->after('nome'); //indica que a coluna site ficará posicionada imediatamente à direita da coluna nome

## RESET, REFRESH, FRESH, STATUS

### os métodos acima do php artisan migrate são responsáveis pelas seguintes situações:

php artisan migrate:status ***mostra o status de todas as migrations criadas (se foram migradas ou não)***

php artisan migrate:reset ***reseta todas as migrations usando os métodos down, da mais nova à mais antiga***

php artisan migrate:refresh ***reexecuta todas as migrations, primeiro executando os métodos down, depois, automaticamente, executando os métodos up. usado normalmente quando da implementação em produção ou para novos testes***

php artisan migrate:fresh ***semelhante ao refresh, a diferença é que ele dropa todos os objetos das migrations em vez dos métodos down, e depois executa todos os métodos up***


## Tinker - Eloquent ORM - serve para manipular os models e insere registros no banco

## Inserindo registros no banco:

### com Tinker, primeiro precisamos inicializá-lo:

    php artisan tinker

### depois, declaramos uma nova variável como uma instância do model (nome da tabela no banco de dados onde será inserido o registro), usando seu namespace //o eloquent ORM identifica automaticamente o nome da tabela a partir do nome do model e adiciona um s no final

    $contato = new \App\Models\SiteContato();

***contato será uma instância de SiteContato, inserindo todos os registros na tabela com o mesmo nome do model, adicionado de s no final, na tabela site_contatos ***

***eloquent pega o nome no CamelCase e modifica-o, colocando um _ antes das maiúsculas do meio da palavra, adicionando um s no final:***

    UmDoisTresQuatro -> um_dois_tres_quatros


### podemos então seguir adicionando os campos de forma incremental, como em um array associativo:

    $contato->nome = 'NomeAqui';

    $contato->telefone = '00000000';

    $contato->email = 'email@email.com'; $

***cada instrução retornará ="DadoInserido", onde dado inserido é o valor passado na declaração***

### terminada a inserção, utilizamos o método ->save(); do model - os models herdam(extends) de Model, logo, possuem todos os métodos:

    $contato->save();  

### para novos registros, segue-se com as novas declarações:

    $contato2 = new \App\Models\ModelName();

### podemos ver os itens dentro do array da nova variável usando o comando:

    print_r($contato->getAttributes())

***imprimirá os pares chave:valor do array que estamos adicionando no model***

## Ajuste de nome no Tinker:

### caso o eloquent não consiga detectar a table automaticamente via camelcasing + s no nome do model, podemos definir o nome da tabela dentro do model através do atributo protected $table do model:

    class Cor extends Model
    {
        protected $table = 'cores';
    }

***neste caso, 'cors' não seria o plural correto da palavra cor, logo, quando a tabela criada no plural chamada cores recebesse uma tentativa de adição, retornaria um erro. Adicionando o atributo protected $table = 'cores', podemos colocar o nome correto da tabela onde o registro será inserido***

## Adicionando registros com Create e Fillable:

### é possível adicionar dados por meio de arrays sem criar uma nova instância do model. para isso, precisa-se de dois passos:

### dentro do model, deve-se declarar os atributos do atributo protected $fillable, que indica quais campos podem ser preenchidos(filled!). São passados via array que indica através das chaves o nome das colunas:

    protected $fillable = ['nome', 'telefone', 'endereco']
***aqui indica-se que os campos que podem ser preenchidos na criação do registro são nome, telefone e endereco, que possuem nomes idênticos às colunas na tabela***

### posteriormente, chamamos o método estático create do model(tabela) que receberá o registro e passamos o array associativo com os dados a serem inseridos:

    \App\Models\DadosCliente::create(['nome'=>'fulano', 'telefone'=>'xxxx-xxxx', 'endereco'=>'Rua A, num 00, Centro'])

***a tabela será populada com os dados indicados no array associativo***

## Recuperando registros do banco com o Eloquent e métodos estáticos:

## Todos os Registros:

### para selecionar todos os registros da tabela, usamos duas formas:

    $fornecedores = \App\Models\Fornecedor::all();

***usando o método estático ::all(), herdado por Fornecedor do Model, atribuímos à variável fornecedores o retorno do método, que trará todos os registros da tabela***

    use \App\Models\Fornecedor
***então***
    $fornecedores = Fornecedor::all(); $

***é o segundo modo de recuperação dos registros. seleciona o model Fornecedor, permitindo o acesso direto e atribuição por meio de Fornecedor***

## selecionando registros do banco via find() do Eloquent ORM

### da mesma forma que o método estático all(), find() é utilizado a partir de instância do model e chamada direta. find recebe por parâmetro a primary key do registro a ser retornado, podendo também receber um array de primary keys. Retorna um objeto caso seja passado um único parâmetro, ou uma collection caso sejam passados 2 ou mais parâmetros. Retornará vazio na collection para os parâmetros que não existem.

    $fornecedores = Fornecedor::find(3) ou 
    $fornecedores = Fornecedor::find([1, 4, 7])
***dentro da tabela fornecedores, find buscará as primary keys passadas por parâmetro***

## recuperando registros com where()->get() do Eloquent ORM

### para recuperar registros usando o where() do Eloquent, ao fazermos a instância do model, chamamos o método estático ::where(param1, param2, param3), passando 3 parâmetros, onde param1 é o nome da coluna, param2 é o operador de comparação ( maior > ; menor <; maior igual >=; menor igual <=; diferente <>; igual ==; like: termina com -> %termoPesquisa; começa com -> termoPesquisa%; contém -> %termoPesquisa%)

    use \App\Models\NomeModel

    $nomeVariavel = NomeModel::where('id','>','1')->get(); //$ atribuindo a uma variável, buscará o registro com o id maior que 1

***caso o ->get() não esteja definido, o Eloquent retornará apenas o builder, não os resultados. Logo, ->get() precisa ser chamado para visualizarmos os registros retornados***

***para operações de igualdade, podemos omitir o sinal:***

    $contatos = SiteContato::where('id','1')->get();   

## whereIn() e whereNotIn() do Eloquent ORM:

### whereIn e whereNotIn são métodos estáticos do Eloquent que recebem 2 parâmetros: nome_da_coluna e valor(ou array de valores).

    $nomeVariavel = NomeModel::whereIn('nome_coluna','valor')->get(); 
        //ou
    $nomeVariavel = NomeModel::whereIn('nome_coluna',[valores])->get(); 

***exemplo***
    $nomeVariavel = NomeModel::whereIn('cor',['azul', 'amarelo'])->get();

***neste caso, retornaria todas os registros que tenha as cores passadas***

### whereNotIn trabalha de maneira inversa, semelhante a !

    $nomeVariavel = NomeModel::whereNotIn('cor',['azul', 'amarelo']);
    $nomeVariavel->get();
***retornará todos os registros na tabela do modelo em que cor NÃO seja azul ou amarelo***

## whereBetween e whereNotBetween

### whereBetween retorna os registros da tabela dentro do intervalo especificado no array

    $nomeVariavel = NomeModel::whereBetween('id',[2, 20])->get();
***retornará todos os registros entre de 2 até 20***

### enquanto whereNotBetween retornará todos os registros fora do intervalo passado: 

    $nomeVariavel = NomeModel::whereNotBetween('id',[2, 20])->get();
***retornará todos os registros que estiverem fora do intervalo de 2 até 20***

## combinando queries com múltiplos where:

## AND
### para combinar queries, basta adicionar a próxima condição em forma de ->where(); na query anterior, ex:

    $contatos = SiteContato::where('nome', '<>', 'Fernando')->whereIn('motivo_contato',[1,2])->whereBetween('created_at', ['2024-06-05 00:00:00', '2024-06-10 23:59:59'])->get();

***este tipo de query combina as queries com AND***

## OR

### para combinar queries adicionando condições OR, basta adicionar or antes dos comandos de query:

    $contatos = SiteContato::where('nome', '<>', 'Fernando')->orWhereIn('motivo_contato',[1,2])->orWhereBetween('created_at', ['2024-06-05 00:00:00', '2024-06-10 23:59:59'])->get();

## whereNull; e whereNotNull();:

### método estático do eloquent que recebe por parâmetro o nome da coluna, retornando através do ->get(); os registros que tiverem os valores nulo ou não nulo naquela coluna na tabela:

    $contatos = SiteContato::whereNull('nome')->get();
    //ou
    $contatos = SiteContato::whereNotNull('nome')->get();
***também aceitam orWhereNull() e orWhereNotNull()***

## registros a partir de datas:

### para pesquisar registros do tipo data, a coluna obrigatoriamente deve ser do tipo dateTime, para então usarmos os métodos a seguir

### whereDate('nomeColuna', '2024-01-01')->get(); retorna registros com base na data completa

    $contatos = SiteContato::whereDate('created_at', '2024-01-01')->get();

### whereYear('nomeColuna', '2024')->get(); retorna registros com base no ano passado

    $contatos = SiteContato::whereYear('created_at', '2024')->get();

### whereMonth('nomeColuna', '01')->get(); retorna registros com base no mês passado

    $contatos = SiteContato::whereDay('created_at', '1')->get();

### whereDay('nomeColuna', '01')->get(); retorna registros com base no dia passado

    $contatos = SiteContato::whereDay('created_at', '1')->get();

### whereTime('nomeColuna', <OpComparação> ,'23:59:59')->get(); retorna registros com base na hora passada, podendo receber um operador de comparação do Tinker.

    $contatos = SiteContato::whereTime('created_at', '10:00:00')->get();
    //ou
    $contatos = SiteContato::whereTime('created_at', '<>', '10:00:00')->get();
    //ou
    $contatos = SiteContato::whereTime('created_at','<=' '10:00:00')->get();

## whereColumn(param1, param2)

### whereColumn é um método de comparação de colunas. retornará os registros caso os registros dentro das colunas passadas por parâmetro atendam às condições dos operadores de comparação:

    $contatos = SiteContato::whereColumn('created_at', 'updated_at')->get();
    //ou
    $contatos = SiteContato::whereColumn('created_at','<>', 'updated_at')->get();

## precedência de operações lógicas no eloquent:

### para aplicar precedência de operações no Eloquent, é necessário definir as queries dentro de funções callback, isolando-as dentro das cláusulas da query principal:
    $contatos = SiteContato::where(function($query){ $query->where('nome', 'Brunno')->orWhere('nome', 'Rui'); })->where(function($query){ $query->whereIn('motivo_contato', [1, 2])->orWhereBetween('id', [4, 6]);})->get();

### dessa forma, a query fará o equivalente SQL a:

    where(condição1 ou condição2) and (condição3 ou condição4)

## ordenando registros:

### para ordenar registros no eloquent, usamos o método estático orderBy(param1, param2), onde param1 indica a coluna de ordenação, e param2 indica se descendente ou ascendente, podendo ser omitido em caso de asc, por ser o padrão. Pode ser combinado com outras queries e outros orderBy:

    SiteContato::whereBetween('id', [2,6])->orderBy('motivo_contato')->orderBy('nome', 'asc')->get();
***neste caso, retornará os registros que estejam entre (e inclusive) 2 e 6, ordenando por motivoContato e posteriormente ordenando por nome***

## Collections:

### esta seção trata sobre os métodos que podem ser usados dentro das collections. ao criar a variável, temos acesso aos métodos. a exemplo dos métodos abaixo:

    $contatos = SiteContato::where('id','>', 5) //query exemplo
    $contatos->first(); //retorna o primeiro registro
    $contatos->last(); //retorna o último registro
    $contatos->reverse(); //retorna os resultados de modo reverso

### as collections também dispõem dos métodos toArray() e toJson(), que convertem o retorno dos registros para o respectivo tipo:

    $contatos = SiteContato::where('id', '<', 3)->get()->toArray();
    $contatos = SiteContato::where('id', '<', 3)->get()->toJson();

## pluck(); - depenar!

### o método pluck(param1, param2); retorna apenas os registros do campo passado como param1:

    $contatos = SiteContato::all()->pluck('nome');

***retornará em forma de collection somente os valores nome. poderá receber outros métodos relacionados às collections, como toJson, toArray, etc***

### caso o segundo parâmetro seja passado, o pluck criará um array associativo usando o valor da coluna passada como chave:

    $contatos = SiteContato::all()->pluck('telefone', 'nome');
    //ex de retorno: "Joao" => "9999-8888"

## Atualizando registros:

### a atualização de registros é feita através do método ->save() do objeto. primeiro, acessa-se o atributo do item e atribui-se um valor ao mesmo, posteriormente, chamando o método diretamente da variável declarada:

    $usuario->nome = 'Nome do Usuário'; //define o novo nome do usuário
    $usuario->save(); //salva o(s) novo(s) atributo(s) e marca na coluna updated at a timestamp da atualização

## fill();

### o método estático fill() do eloquent recebe por parâmetro um array associativo para preencher múltiplas colunas, em vez de coluna a coluna com o método direto de acesso anterior.

    $fornecedores2->fill(['nome'=>'Fornecedor 789', 'site'=>'www.site3.com.br', 'email'=>'contato@fornecedor.com.br']);
    $fornecedores2->save();
***fill() só preencherá efetivamente os campos declarados no model dentro do array protected $fillable = ['nome', 'email', 'uf', 'site']***
***deve-se salvar o que foi alterado através do método ->save();***

## atualizando com where e update:

### para atualizar os registros usando o método ->update([array associativo]), seleciona-se o registro com o método correspondente, para então passar um array associativo que recebe por parâmetros o par chave:valor com chave sendo o nome da coluna a ser atualizada e valor contendo o registro atualizado

    Fornecedor::whereIn('id',[1,2])->update(['nome' => 'John Doe', 'telefone' => '1111-2222']);
***update não precisa chamar o método ->save(); para persistir as alterações no banco***

## apagando registros com delete(); destroy();

### a exclusão de registros pode ser feita de duas formas: chamando o método delete->() ao final de uma query, ou com o destroy(param) passando por parâmetro o id do item a ser excluído:

    use \App\Models\NomeModel
    $nomeItem = NomeModel::find(7)->delete(); //encontra e exclui item de id 7
    //ou
    $nomeItem = NomeModel::where('id', 1)->delete(); //apagará diretamente o item passado na query, podendo também ser usado $nomeItem->delete();
    //ou
    NomeModel::destroy(id); //destruirá o item com o id passado

## softDelete();

### o softDelete é uma opção do Eloquent responsável por manter registros para fins de historicidade. é uma coluna de nome deleted_at adicionada à tabela que por padrão tem seu valor como null. toda vez que um registro é deletado via $nomeVariavel->delete();, a coluna é preenchida com a timestamp do delete e o registro não será mais retornado nas queries do eloquent.

***primeiro deve-se adicionar o softDeletes dentro do model da tabela***
    use Illuminate\Database\Eloquent\SoftDeletes;
    class NomeModel extends Model{
        ...
        use SoftDeletes;
        ...
    }

***posteriormente, adiciona-se na migração a coluna softDelete, que criará na tabela a coluna deleted_at***

    public function up(): void
    {
        Schema::table('nomeTable', function (Blueprint $table) {
            /*coluna deletedAt, responsável por armazenar os dados
            de modo que são excluídos dos retornos, mas mantidos
            para contexto histórico*/
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //reverte a criação da coluna softDelete
        Schema::table('fornecedores', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }

***feita a migração, no tinker, acessamos o model e executamos a query de exclusão***

    use \App\Models\NomeModel
    $modelVariavel = NomeModel::find(3) /*seleciona o registro de id 3; podem ser feitas queries normalmente aqui, com where, etc...*/
    $modelVariavel->delete();

***no contexto, o valor da coluna deleted_at, por padrão null, será alterado para a timestamp do momento da query, ocultando o registro das queries posteriores, mas sem de fato apagá-lo da tabela***

### para apagar um registro definitivamente da tabela, usa-se o ->forceDelete();

    NomeModel::where('id', N)->forceDelete(); //deleta o registro de id N

## recuperando os registros do softDelete();

### para recuperar os registros alterados(excluídos) pelo softDelete, podemos usar dois métodos estáticos do model:

    //withTrashed() - todos os registros, softDeleted e não deletados
    NomeModel::withTrashed()->get();

    //onlyTrashed - somente os registros softDeleted
    NomeModel::onlyTrashed()->get();
***pode-se realizar operações com os registros recuperados***

## restaurando os registros com restore();

### para restaurar um registro, associamos o retorno do model a uma variável:
    $variavel = NomeModel::withTrashed()->get();
### posteriormente, dentro do array de retorno, acessamos o id do objeto armazenado e chamamos a função restore:
    $variavel[id]->restore();
***aqui considera-se que o id é o índice do objeto a ser restaurad no array de retorno***

## Seeders:

### seeders são os responsáveis por popular o banco de dados com registros. São criados através do comando:

    php artisan make:seeder NomeSeeder
***por boas práticas, o nome do seeder faz referência ao model a que ele se relaciona. Ex: um seeder de usuários se chamará UsuarioSeeder, enquanto um de produtos seria ProdutoSeeder, etc...***

### criado o seeder, há 3 formas de popular o banco de dados através do arquivo resultante:

    {
        //Atentar-se para os atributos fillable dentro do model das classes instanciadas - requisito apenas para o Create, mas serve como referência para os campos a serem preenchidos
        //Seeder via instância
        $fornecedor = new Fornecedor();
        $fornecedor->nome = 'Fornecedor 100';
        $fornecedor->uf = 'PE';
        $fornecedor->email = 'fornecedor100@contato.com.br';
        $fornecedor->site = 'www.fornecedor100.com.br';
        $fornecedor->save();
        //sempre que for criado via instância, o método save() deverá ser chamado

        //seeder via método estático create
        Fornecedor::create(
            [
                'nome' => 'Fornecedor 200',
                'site' => 'www.200fornecedor.com.br',
                'uf' => 'PB',
                'email' => 'fornecedor200@mail.com.br'
            ]);

        //via DB insert diretamente
        DB::table('fornecedores')->insert([
            'nome' => 'Fornecedor 300',
            'site' => 'www.300fornecedor.com.br',
            'uf' => 'SP',
            'email' => 'contato@fornecedor300.com.br'
        ]);
    }

***os campos passados são aqueles com os mesmos nomes de colunas contidos dentro de protected $fillable []***

***a forma DB::table('nomeTabela')->insert não preenche os timestamps created_at e updated_at, está aqui a título de conhecimento.***

### definido o método que inserirá os dados, dentro do arquivo DatabaseSeeder deve-se chamar a seguinte função:

    $this->call(NomeSeeder::class);

### chamando dentro de call o nome do seeder relativo ao model, pode-se então dar o comando de seed ao banco:

    php artisan db:seed

### quando um novo seeder é criado, se um anterior já estiver definido dentro de DatabaseSeeder e tiver populado a tabela com registros, chamar php artisan db:seed adicionará novamente os dados do seeder anterior. Duas abordagens são possíveis aqui:

### nomear o seeder, especificando qual será executado:
    php artisan db:seed --class=NomeSeeder
***executa especificamente o seeder indicado em --class=***

### ou comentar o seeder anterior dentro do arquivo DatabaseSeeder

## Factories:

### factories são funções de classes que populam o banco de dados com registros para testes, através da dependência Faker. Para utilizar factories, criamos uma factory com o nome do model de referência:

    //modelo:
    php artisan make:factory NomeModelFactory --model=NomeModel

    //exemplo:
    php artisan make:factory SiteContatoFactory --model=SiteContato
***no caso, por convenção, definimos o nome terminado em factory com base no model***

### criada a factory, dentro da função definition() da factory, declaramos o array associativo com as colunas a serem preenchidas no banco:

    public function definition(): array
    {
        return [
            'nome' => fake()->name(),
            'telefone' => fake()->phoneNumber(),
            'email' => fake()->email(),
            'motivo_contato' => fake()->numberBetween(1, 3),
            'mensagem' => fake()->text()
        ];
    }
***neste caso, fake dispõe de tipos de dados (consultar documentação) condizentes com os tipos de colunas a serem preenchidos***

### declarado o array dentro da função do factory, dentro do seeder, chamamos a factory dentro da função run:

    //molde
    public function run(): void{
        NomeFactory::factory()->count()->create();
    }

    //exemplo
    public function run(): void{
        SiteContato::factory()->count(100)->create();
    }
***dentro de count, passamos a quantidade de registros a serem criados***

### com a factory chamada dentro da run(){} do seeder, fazemos o seeding através do artisan:

    //molde
    php artisan db:seed --class=NomeSeeder
    //exemplo
    php artisan db:seed --class=SiteContatoSeeder
***especifica-se a classe SiteContatoSeeder para que apenas ela seja executada, evitando readição de novos registros caso haja outros Seeds criados dentro do seeder que já foram executados***

## Formulários:

## Request:

### é o request que possibilita capturar tudo que é enviado via formulários para o backend. Nesse cenário, quando se deseja acessar os itens enviados, basta declarar como parâmetro no controller a variável $request do tipo Request:

    class Contato extends Controller{
    public function Contato(Request $request){
        //code here
        return view('nomeView');
        }
    }
***no exemplo, a view contato possui um formulário de contato que terá seus dados armazenados dentro da variável $request***

### quando os dados são enviados, é possível acessá-los da seguinte forma:

    class Contato extends Controller{
        public function Contato(Request $request){

            print_r($request->all());
            $request->input('nomeCampoInput')
        }}

***o método all retorna em forma de array todos os dados enviados pelo formulário, incluindo o _token @csrf definido no formulário para sua validação***

***o método input('nomeCampo') de $request retorna o campo passado de acordo com seu name="" na view: $request->input('nome'); retornará o campo <input ... name="nome" ...>, $request->input('email'); retornará o campo <input ... name="email" ...>, e assim sucessivamente***

## Salavando os dados do formulário no banco de dados:

### para salvar os dados no banco de dados, dentro do controller, instanciamos o model para atribuir as variáveis capturadas na request no array associativo que será salvo no banco. Isso pode ser feito de três maneiras:

    use App\Models\SiteContato;

    class Contato extends Controller{
        public function Contato(Request $request){

    #1 - selecionando os índices associativos e atribuindo ao objeto contato, via $request->input(param);

    $contato = new SiteContato();
        $contato->nome = $request->input('nome');
        $contato->telefone = $request->input('telefone');
        $contato->email = $request->input('email');
        $contato->motivo_contato = $request->input('motivo_contato');
        $contato->mensagem = $request->input('mensagem');

        $contato->save(); //salva os dados no banco
    
    #2 - usando o método ->fill do model, passando por parâmetro o array retorno de $request->all();. Os índices a serem preenchidos devem estar declarados dentro do protected $fillable = [], dentro do model

    $contato = new SiteContato();
        $contato->fill($request->all());
        $contato->save(); //é necessário chamar o save para salvar.
    
    #3 - usando o método create() passando por parâmetro o array retorno de $request->all();

    $contato = new SiteContato();
        $contato->create($request->all());
    //aqui não é necessário chamar o método ->save();
    //também é necessário declarar $fillable dentro do model

        }
    }

## Validação de campos:

### para validação simples de campos obrigatórios, dentro do controller que captura a variável Request $request pode chamar um método nativo ->validate([]), recebendo por parâmetro um array associativo com os campos e o requisito de validação:

    $request->validate([
            'nome' => 'required',
            'email' => 'required',
            'telefone' => 'required',
            'motivo_contato' => 'required',
            'mensagem' => 'required'
        ]);
        SiteContato::create($request->all());
***neste caso, o requisito de validação é que todos os campos são required. caso a validação não passe, será armazenada na variável global $errors, que é um array disponível a toda aplicação. Caso a validação passe, o método create do model - ModelName::create($request->all()); armazenará o registro no banco de dados***

### a variável $errors pode receber um print_r($errors) para avaliação dos erros.

## outras validações:

### para fazer múltiplas validações para um campo basta dentro do array associativo passar a nova validação no valor associado ao campo, separando por |:

    $request->validate([
            'nome' => 'required|min:3|max:40'
        ])
***para outros tipos de validação, ver documentação***

## repopulação de formulários com valores preenchidos - não perdendo o que já foi digitado pelo usuário:

### para persistir no formulário valores que já foram digitados, basta adicionar a função old(nomeCampo) no atributo value, passando por parâmetro o name do campo Input:

    <input name="nome" value="{{old('nome')}}" type="text" placeholder="Nome">
    <input name="telefone" value="{{old('telefone')}}" type="text" placeholder="Telefone">

    <textarea name="mensagem" class="{{ $classe }}">{{ old('mensagem') !='' ? old('mensagem') : "Preencha aqui a sua mensagem" }}</textarea>
***no exemplo do textArea, é passado um teste ternário. Caso o campo mensagem não esteja vazio, o valor é mantido - a mensagem que foi digitada pelo usuário, caso esteja vazio, a mensagem padrão é aplicada***

## Repopulando - mantendo - selects:

### para validar selects há duas opções: hard coded e dinâmica:

### hard coded:

    <select name="motivo_contato" class="{{ $classe }}">
        <option value="1" {{old('motivo_contato' == 1 ? 'selected' : '')}}>Dúvida</option>
        <option value="2" {{old('motivo_contato' == 2 ? 'selected' : '')}}>Elogio</option>
        <option value="3" {{old('motivo_contato' == 3 ? 'selected' : '')}}>Reclamação</option>
***em cada option é comparado o valor que foi selecionado. caso a condição seja atendida, SELECTED é aplicado ao campo, fazendo com que seja persistido. Caso não tenha sido persistido, o valor é vazio***

### dinâmico:

### usado com mais regularidade, pois permite que os selects sejam retornados a partir do banco e na quantidade em que existirem:

### primeiro é declarado um array associativo no controller que conterá nos pares chave/valor os values e opções do selected e passado para a view dentro do return. Ex:

    $motivo_contatos = [ 
            '1' => 'Dúvida', 
            '2' => 'Elogio',
            '3' => 'Reclamação'
        ];
    
    return view(...['motivo_contatos' => $motivo_contatos ]);

### depois, o array é recebido pelo form (caso o form seja um componente, o array de selects deve ser passado para o component como parâmetro do @component()). Ex:

    //passado para o @component()
    @component(...['motivo_contatos' => $motivo_contatos]);

### por fim, o array é percorrido pelo @foreach para impressão dos valores:

    @foreach ($motivo_contatos as $key => $motivo_contato){
            <option value="{{$key}}" {{old('motivo_contato') == $key ? 'selected' : ''}}>{{$motivo_contato}}</option>
    }@endforeach
***a key será comparada com o value, enquanto o ternário testará se a option e a key são iguais para adicionar SELECTED ou deixar vazio***

## Queries "avulsas":

### é possível fazer queries avulsas nas migrations usando o DB::statement($query):

    DB::statement('update site_contatos set motivo_contato = motivo_contatos_id');

## Validação de emails

### para validar emails, basta que no controller que valida os inputs seja declarado para o input email o valor email:

    $request->validate([
            ...,
            'email' => 'email',
            ...
    ])

## redirecionamento alternativo de rotas no return:

### quando um controller executar um método e for desejável que ele redirecione para uma nova rota, chamamos no return o método:

    return redirect()->route('nomeRota);

## Validação de campos unique:

### para validar campos como unique pelo laravel, basta adicionar na função $request->validate(); nos valores a verificar, o valor unique:param, passando por parâmetro o nome da tabela que será consultada

    $request->validate([
            ...,
            'nome' => 'unique:fornecedores',
            ...
    ]);
***neste caso exemplo, a comparação e validação será feita na coluna nome da tabela fornecedores***

## Apresentação de errors:

### é possível verificar os erros específicos de cada campo input. Para verificar se há algum erro:
    
    $errors->any(); 
***retorna true caso exista pelo menos um erro capturado possibilitando assim expressões booleanas.*** 

### Para retornar retornar todos os erros capturados usando o método:
    $errors->all(). 

### Para impressão na página, pode-se utilizar $errors->has(Param) passando por parâmetro o nome do campo, retornando true ou false a depender da existência do erro no campo parâmetro.
    
    $errors->has('nome');
***retornará true se o campo input nome apresentar um erro de qualquer tipo***

### Por fim, $errors->first(param) retorna a string com a mensagem de erro correspondente:

    {{ $errors->has('telefone') ? $errors->first('telefone') : ''}}
***caso um erro no input telefone seja capturado, a mensagem com o tipo de erro capturado será retornada pelo first()***

## erros customizados:

### é possível customizar os erros de validação. Isso é feito no controller passando como segundo parâmetro na validação um array associativo contendo o nome do campo + tipo de erro como chave e no valor a mensagem a ser exibida, no molde ['nomeInput.tipoErro' => 'Mensagem Padronizada']. Ex:

    $request->validate(
        [
            'nome' => 'required|min:3|max:40',
            'email' => 'email',
            'telefone' => 'required',
            'motivo_contatos_id' => 'required',
            'mensagem' => 'required|max:2000'
        ],
        [
            'nome.min' => 'O Nome precisa ter pelo menos 3 caracteres',
            'nome.max' => 'O Nome tem um limite de 40 caracteres',
            'email.email' => 'Insira um email válido.',
            'motivo_contatos_id.required' => 'Selecione um motivo',
            'mensagem.max' => 'A mensagem tem um máximo de 2000 caracteres',

            'required' => 'O campo :attribute é obrigatório'
        ]
    );
***'nome.min' representa a mensagem exibida caso o erro de validação seja no mínimo de caracteres do nome, enquanto 'nome.max' verifica e apresenta a mensagem definida caso o máximo de caracteres não seja respeitado***
***'required' representa uma mensagem padrão para todos os erros de required, :attribute usará o nome dado ao campo para deixar a mensagem dinâmica. mensagens customizadas no padrão nomeCampo.required terão prioridade sobre a mensagem global de required***

## MiddleWares:

### Middlewares é um tipo de codificação que age como intermediário entre aplicações cliente e servidor. Inicialmente foi concebido para fazer a compatibilização de sistemas legados com sistemas mais atualizados. Para criar um middleware:

    php artisan make:middleware -NomeClasseMiddleware
***os middlewares seguem convenção de NomeClasse finalizado pela palavra Middleware. a exemplo: LogAcessoMiddleware***

### para chamar o middleware, chamamos sua classe na rota:

    Route::middleware(LogAcessoMiddleware::class)
    ->get('/', [\App\Http\Controllers\PrincipalController::class, 'principal'])
    ->name('site.index');
***no exemplo, o middleware LogAcessoMiddleware será chamado antes do acesso de rota. Há ainda a possibilidade de o middleware devolver a response no próprio middleware, sem precisar passar a request para o servidor.***

## capturando IP e rotas no middleware log de acesso:

### quando se fizer necessário capturar dados tipo IP e rota de acesso, é possível fazê-lo através do middleware, chamando-o via classe na rota, como no exemplo anterior. Dessa forma, dentro do middleware, temos acesso ao objeto request, podendo capturar dados a partir dele:


    $ip = $request->server->get('REMOTE_ADDR');
***o objeto $request contém todos os dados da requisição feita pelo cliente***
***dentro do objeto request, temos acesso dentro de server à chave REMOTE_ADDR, que contém os dados do endereço remoto que acessou a rota***
    $rota = $request->getRequestUri();
***por ser um atributo de primeiro nível - leia-se não estar dentro de um array no objeto - podemos acessar a requestUri diretamente da request pelo método get, sem precisar passar parâmetros, conseguindo assim acessar a rota requisitada pelo cliente***

## Implementação de middlewares para todas as rotas:

### para implementar middlewares para todas as rotas, no arquivo app.php em bootstrap, declara-se dentro do método withMiddleware os nomes dos middlewares que serão necessários para toda a aplicação:

    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append:[NomeMiddleware::class]);
    })
***anteriormente, os middlewares eram declarados dentro do Kernel.php, mas esta necessidade foi eliminada no Laravel 11***

## apelidando(aliases) middlewares

### para apelidar middlewares, basta declará-los dentro do arquivo app.php, na função withMiddleware:

    $middleware->alias([
            'apelido.middleware' => NomeMiddleware::class
        ]);
***apelido.middleware é a convenção do laravel de nomes, enquanto NomeMiddleware aponta para o middleware que receberá o apelido***

## Encadeamento de middlewares:

### é possível encadear middlewares, fazendo com que múltiplas execuções de middleware aconteçam antes que uma rota seja acessada. Para isso, na passagem do parâmetro da chamada do middleware na rota, adiciona-se em ordem de execução os nomes dos middlewares separados por vírgula:

    Route::middleware('log.acesso', 'autenticacao', 'middlewareName3'...)...
***os middlewares exemplo acima chamam seus respectivos middlewares através do alias definido anteriormente***

### posteriormente, dentro de cada middleware deve ser declarado o return $next($request), indicando que a aplicação deve passar para o próximo middleware da sequência:

    public function handle(Request $request, Closure $next): Response
    {
        ...    
            return $next($request);
        ...
    }
***é possível aplicar testes dentro da função handle, indicando se a execução da aplicação deve parar (usando um retorno qualquer definido pelo programador) ou seguir adiante para o próximo middleware usando o $next($request);***

## middlewares em grupos de rotas:

### para adicionar middlewares em grupos de rotas, basta chamar o middleware na chamada do objeto que indica o prefix da rota:

    Route::middleware(nomeMiddleware)->prefix('/app')->group(function(){
        Route::get(...); //rota 1
        Route::get(...); //rota 2
        Route::get(...); //rota 3
    });

## passando parâmetros para os middlewares:

### para passar parâmetros para os middlewares, basta declará-los na chamada do middleware na rota, separando por : após o nome do middleware

    Route::middleware('nomeMiddleware:param1, param2, paramN')->etc
***podem ser passados quantos parâmetros forem necessários***

### depois deve-se passar por parâmetro e declarar as variáveis que capturarão os parâmetros dentro da função handle:

    public function handle(Request $request, Closure $next, $nomeVarParam1, $nomeVarParam2){...code here...}
***após captura, as variáveis podem ser usadas para testes dentro da função handle***