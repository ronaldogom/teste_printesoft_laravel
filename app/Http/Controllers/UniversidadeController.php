<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Universidade;

class UniversidadeController extends Controller
{
    public function cadastrar(Request $request)
    {
        if($request->has('nome', 'endereco', 'cidade', 'bairro', 'estado'))
        {
            $newUniversidade = new Universidade();
            $newUniversidade->nome = $request->nome;
            $newUniversidade->endereco = $request->endereco;
            $newUniversidade->cidade = $request->cidade;
            $newUniversidade->bairro = $request->bairro;
            $newUniversidade->estado = $request->estado;

            $newUniversidade->save();

            return response(['sucesso' => true, 'mensagem' => 'Universidade cadastrada com sucesso', 'universidade' => $newUniversidade]);
        }

        return response(['sucesso' => false, 'mensagem' => 'Por favor forneça todos os dados obrigatórios']);
    }

    public function listarTodas(Request $request)
    {
        $universidades = Universidade::all();
        return response(['sucesso' => true, 'mensagem' => 'Universidades listadas com sucesso', 'universidades' => $universidades]);
    }
}
