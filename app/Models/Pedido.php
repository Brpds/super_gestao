<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    public function produtos(){
        //return $this->belongsToMany('App\Models\Produto', 'pedido_produtos');
        return $this->belongsToMany('App\Models\Item', 'pedido_produtos', 'pedido_id', 'produto_id')->withPivot( 'id', 'created_at', 'updated_at');
        /*
            Quando não trabalhando com nomes padronizados, belongsToMany recebe 4 parâmetros
            1 - modelo de relacionamento NxN em relação ao modelo implementado
            2 - nome da tabela auxiliar que armazena os registros de relacionamento
            3 - nome da FK na tabela mapeada pelo modelo na tabela de relacionamento
            4 - nome da FK na tabela mapeada pelo model utilizado no relacionamento

        */
    }
}
