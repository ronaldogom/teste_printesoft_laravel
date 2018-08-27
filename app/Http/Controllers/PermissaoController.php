<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Permissao;

class PermissaoController extends Controller
{
    public function cadastrar(Request $request)
    {
        if($request->has(['rota', 'descricao']))
        {
            $newPermissao = new Permissao;
            $newPermissao->rota = $request->rota;
            $newPermissao->descricao = $request->descricao;

            $newPermissao->save();

            return response(['mensagem' => 'permissão cadastrada com sucesso', 'nova_permissao' => $newPermissao]);
        }

        return response(['mensagem' => 'forneça uma rota para criar uma nova permissão']);
    }
}
