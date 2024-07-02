<h3>Fornecedor View</h3>


{{--
 isset testa se o array $fornecedores está declarado dentro do compact(ou outro método)
 no controller. estando declarado, o bloco de código dentro do isset será 
 executado, não estando declarado, o código não será executado. Em caso de
 índices inexistentes nos arrays pesquisados, é possível colocar um isset
 dentro de outro isset para fazer a verificação
--}}
@isset($fornecedores)

    @forelse($fornecedores as $indice => $fornecedor)
        Fornecedor: {{ $fornecedor['nome']}}
        <br>
        Status - Ativo? {{$fornecedor['status']}}
        <br>
        CNPJ: {{ $fornecedor['cnpj'] ?? 'Valor Padrão'}}
        <br>
        Telefone: {{ $fornecedor['ddd'] ?? ''}} {{$fornecedor['telefone'] ?? ''}}
        <hr>

        @empty
            Não existem fornecedores cadastrados!!!
    @endforelse

@endisset

{{-- eu sou um comentário blade --}}


{{-- incluindo blocos de php puro --}}

@php

    //sou um bloco de php puro

@endphp

{{--


>>>Modelo WHILE<<<

 @php $i = 0 @endphp
    @while(isset($fornecedores[$i]))

        Fornecedor: {{ $fornecedores[$i]['nome']}}
        <br>
        Status - Ativo? {{$fornecedores[$i]['status']}}
        <br>
        CNPJ: {{ $fornecedores[$i]['cnpj'] ?? 'Valor Padrão'}}
        <br>
        Telefone: {{ $fornecedores[$i]['ddd'] ?? ''}} {{$fornecedores[$i]['telefone'] ?? ''}}
        <hr>
        @php $i++ @endphp
    @endwhile

>>>Modelo FOR<<<
@for($i = 0; $i < 10; $i++)

    {{$i}} <br>

@endfor

>>>Modelo de SwitchCase<<<
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

>>>Modeo de IfElse<<<
@if(count($fornecedores) > 0 && count($fornecedores) < 10)
    <h3>Existem Fornecedores cadastrados</h3>
@elseif(count($fornecedores) >= 10)
    <h3>Existem muitos fornecedores cadastrados</h3>
@else
    <h3>Não existem fornecedores cadastrados</h3>
@endif 


>>>Modeo de Unless<<<
@unless( 3 < 2)
    Menor que dois
@endunless


>>>Modelo de isset<<<
@isset($fornecedores[0]['cnpj'])
        CNPJ: {{$fornecedores[0]['cnpj']}}
        @empty($fornecedores[0]['cnpj'])
            - Vazio
        @endempty
    @endisset

--}}