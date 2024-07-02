<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fornecedor extends Model
{
    use HasFactory;
    //permite o uso dos soft deletes
    use SoftDeletes;
    /*
        criação do atributo que define/corrige o padrão camelcase + s, caso
        somente a adição do s não seja suficiente para definir o nome da tabela
    */
    protected $table = 'fornecedores';
    /* criação do fillable, para a passagem de valores a serem inseridos
    na tabela usando o método do model sem instanciar ModelName::create */
    protected $fillable = ['nome', 'email', 'uf', 'site'];
}
