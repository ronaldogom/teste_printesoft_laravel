<?php

namespace App\Http\Middleware;

use Closure;
use Usuario;
use PermissaoUsuarioPivot;
use Permissao;

class PermissoesMiddleware
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $uri = $request->route()->uri();

        // se estamos recebendo email como parâmetro, então estamos testando a permissão do usuário de entrar no sistema
        if($request->has('email'))
            $usuario = Usuario::where('email', $request->email)->first();
        else if($request->has('token'))
            $usuario = Usuario::where('token', $request->token)->first();

        if(!is_null($usuario))
        {
            $permissao_pivot = PermissaoUsuarioPivot::where('usuario_id', $usuario->id)->first();

            if(!is_null($permissao_pivot))
            {
                $permissao = Permissao::find($permissao_pivot->permissao_id);

                if(strpos($uri, $permissao->rota) !== false)
                {
                    return $next($request);
                }
            }

            return response(['sucesso' => false, 'mensagem' => 'Você não possui permissão para acessar o sistema.']);
        }

        return response(['sucesso' => false, 'mensagem' => 'Token inválido.']);
    }
}
