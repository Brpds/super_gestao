<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Fornecedor;
use Illuminate\Support\Facades\DB;

class FornecedorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Atente-se para os atributos fillable das classes instanciadas
        //Seeder via instância
        $fornecedor = new Fornecedor();
        $fornecedor->nome = 'Fornecedor 100';
        $fornecedor->uf = 'PE';
        $fornecedor->email = 'fornecedor100@contato.com.br';
        $fornecedor->site = 'www.fornecedor100.com.br';
        $fornecedor->save();

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
}