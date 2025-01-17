@extends('app.layouts.basico')

@section('titulo', 'Fornecedor')

@section('conteudo')
    <div class="conteudo-pagina">

        <div class="titulo-pagina-2">
            <p>Detalhes do Produto - Editar</p>
        </div>

        <div class="menu">
            <ul>
                <li><a href="#">Voltar</a></li>
            </ul>
        </div>

        <div class="informacao-pagina">
        <h4>Produto: </h4>
        {{ $produto_detalhe->toJson() }}
        <div>Nome: {{ $produto_detalhe->item->nome ?? '' }}</div>
        <br>
        <div>Descrição: {{ $produto_detalhe->item->descricao ?? '' }}</div>
            <div style="width:30%; margin-left:auto; margin-right:auto">
                @component('app.produto_detalhe._components.form_create_edit', ['produto_detalhe' => $produto_detalhe, 'unidades' => $unidades, 'btnTxt' => $btnTxt])
                @endcomponent
            </div>
        </div>
    </div>
@endsection