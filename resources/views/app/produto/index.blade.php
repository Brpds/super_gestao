@extends('app.layouts.basico')

@section('titulo', 'Fornecedor')

@section('conteudo')
    <div class="conteudo-pagina">

        <div class="titulo-pagina-2">
            <p>Listagem de Produtos</p>
        </div>

        <div class="menu">
            <ul>
                <li><a href="{{ route('produto.create') }}">Novo</a></li>
                <li><a href="">Consulta</a></li>
            </ul>
        </div>

        <div class="informacao-pagina">
            <div style="width:90%; margin-left:auto; margin-right:auto">
                <table border="1" width="100%">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Peso</th>
                            <th>Fornecedor</th>
                            <th>Site</th>
                            <th>Descrição</th>
                            <th>Unidade ID</th>
                            <th>Comprimento</th>
                            <th>Largura</th>
                            <th>Altura</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($produtos as $produto)
                        <tr>
                            <td>{{ $produto->nome }}</td>
                            <td>{{ $produto->peso }}</td>
                            <td>{{ $produto->fornecedor->nome }}</td>
                            <td>{{ $produto->fornecedor->site }}</td>
                            <td>{{ $produto->descricao }}</td>
                            <td>{{ $produto->unidade_id }}</td>
                            <td>{{$produto->itemDetalhe->comprimento ?? ''}}</td>
                            <td>{{$produto->itemDetalhe->largura ?? ''}}</td>
                            <td>{{$produto->itemDetalhe->altura ?? ''}}</td>
                            <td><a href="{{ route('produto.show', ['produto' => $produto->id]) }}">Visualizar</a></td>
                            <td>
                                <form id="form_{{$produto->id}}" method="post" action="{{route('produto.destroy', ['produto' => $produto->id])}}">
                                    @csrf
                                    @method('DELETE')
                                    <!-- <button type="submit">Excluir</button> -->
                                    <a href="#" onclick="document.getElementById('form_{{$produto->id}}').submit()">Excluir</a>
                                </form>
                            </td>
                            <td><a href="{{ route('produto.edit', ['produto' => $produto->id])}}">Editar</a></td>
                        </tr>
                        <tr>
                            <td colspan="12">Exibir o id do(s) Pedido(s):
                                <p>Pedidos:</p>
                                @foreach($produto->pedidos as $pedido)
                                    <a href="{{route('pedido-produto.create', ['pedido' => $pedido->id])}}">
                                        Pedido: {{ $pedido->id }},
                                    </a>
                                @endforeach
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <!-- display dos links de paginação-->
                    {{ $produtos->appends($request)->links() }}
                <div>
                <p>
                    Exibindo
                        {{ $produtos->count() }}
                    produtos de
                        {{ $produtos->total()}}
                    ({{ $produtos->firstItem()}}
                        a
                        {{ $produtos->lastItem()}})
                </p>
                </div>
            </div>
        </div>
    </div>
@endsection