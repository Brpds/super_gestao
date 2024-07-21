<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        //criando a coluna em produtos que recebe a FK de fornecedores
        Schema::table('produtos', function(Blueprint $table){
            #inserindo um registro em fornecedores para evitar erros.
            /*procedimento feito apenas porque a tabela já contém registros
             e a próxima migration definirá por valor padrão para fornecedor
             para 0, resultando em erro pois não há nenhum registro com esse id.
             caso as tabelas estivessem vazias (fresh) ou sejam 
             truncadas, não seria necessário
            */
            $fornecedor_id = DB::table('fornecedores')->insert(
                [
                    'nome' => 'Fornecedor Padrão SG',
                    'site' => 'www.defaultSG.com.br',
                    'uf' => 'PE',
                    'email' => 'contato@defaultSG.com.br'
                ]
            );

            #after posiciona fornecedor_id depois da coluna passada
            $table->unsignedBigInteger('fornecedor_id')->default($fornecedor_id)->after('id');
            $table->foreign('fornecedor_id')->references('id')->on('fornecedores');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produtos', function(Blueprint $table){
            $table->dropForeign('produtos_fornecedor_id_foreign');
            $table->dropColumn('fornecedor_id');
        });
    }
};
