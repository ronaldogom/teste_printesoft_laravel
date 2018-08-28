<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Usuario;
use Carbon\Carbon;

class LoginController extends Controller
{
    public function autenticar(Request $request)
    {
        if($request->has(['email', 'senha']))
        {
            $usuario = Usuario::where('email', $request->email)->first();

            if($usuario != null)
            {
                $senha = md5($request->senha);

                if($senha == $usuario->senha)
                {
                    $carbon = new Carbon();

                    $usuario->token = md5($request->email.$carbon->timestamp);
                    $usuario->save();

                    $usuario->permitido = true;

                    return response(['sucesso' => true,'mensagem' => 'Usuario autenticado com sucesso.', 'usuario' => $usuario]);
                }

                return response(['sucesso' => false,'mensagem' => 'A senha informada está incorreta.']);
            }

            return response(['sucesso' => false,'mensagem' => 'Nenhum usuário encontrado para o email fornecido.']);
        }

        return response(['sucesso' => false,'mensagem' => 'Forneça todos os campos obrigatórios.']);
    }
}
