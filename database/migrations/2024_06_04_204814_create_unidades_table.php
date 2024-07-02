<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('unidades', function (Blueprint $table) {
            $table->id();
            $table->string('unidade', 5);//cm mm kg
            $table->string('descricao', 30);
            $table->timestamps();
        });

        //relacionamento com a tabela produtos
        Schema::table('produtos', function (Blueprint $table){
            $table->unsignedBigInteger('unidade_id');
            $table->foreign('unidade_id')->references('id')->on('unidades');
        });

        //relacionamento com a tabela produto_detalhes
        Schema::table('produto_detalhes', function (Blueprint $table){
            $table->unsignedBigInteger('unidade_id');
            $table->foreign('unidade_id')->references('id')->on('unidades');
        });

    }

    /**
     * Reverse the migrations.
     * deve ser feito na ordem inversa que foi implementada no método up
     */
    public function down(): void
    {
        //relacionamento com a tabela produto_detalhes
        Schema::table('produto_detalhes', function (Blueprint $table){
            //remover a FK
            //(table_nomeColuna_foreign) - formato de nomenclatura de foreign keys
            $table->dropForeign('produto_detalhes_unidade_id_foreign'); 
            //no caso, a coluna será chamada produto_detalhes_unidade_id_foreign
            //remover a coluna
            $table->dropColumn('unidade_id');
        });

        //relacionamento com a tabela produtos
        Schema::table('produtos', function (Blueprint $table){
            //remover a FK
            //(table_nomeColuna_foreign) - formato de nomenclatura de foreign keys
            $table->dropForeign('produtos_unidade_id_foreign'); 
            //no caso, a coluna será chamada produtos_unidade_id_foreign
            //remover a coluna
            $table->dropColumn('unidade_id');
        });
        
        Schema::dropIfExists('unidades');
    }
};
