<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Usuario;
use Universidade;
use Permissao;
use PermissaoUsuarioPivot;
use UniversidadeUsuarioPivot;
use DB;
use Carbon\Carbon;

class UsuarioController extends Controller
{
    public function cadastrarAdmin(Request $request)
    {
        if($request->has(['nome', 'email', 'senha', 'telefone']))
        {
            $usuario = Usuario::where('email', $request->email)->first();

            if($usuario == null)
            {
                try
                {
                    DB::beginTransaction();

                    $carbon = new Carbon();

                    $newUsuario = new Usuario();
                    $newUsuario->nome     = $request->nome;
                    $newUsuario->email    = $request->email;
                    $newUsuario->senha    = md5($request->senha);
                    $newUsuario->telefone = $request->telefone;
                    $newUsuario->token    = md5($request->email.$carbon->timestamp);
                    $newUsuario->save();

                    $permissao = Permissao::where('rota', 'api/admin/')->first();

                    if($permissao != null)
                    {
                        $newPermissaoUsuarioPivot = new PermissaoUsuarioPivot();
                        $newPermissaoUsuarioPivot->permissao_id = $permissao->id;
                        $newPermissaoUsuarioPivot->usuario_id = $newUsuario->id;
                        $newPermissaoUsuarioPivot->save();

                        DB::commit();
                        return response(['sucesso' => true, 'mensagem' => 'Admin cadastrado com suceesso', 'admin' => $newUsuario]);
                    }

                    DB::rollback();
                    return response(['sucesso' => false,'mensagem' => 'A permissão de usuário necessária não existe']);

                }
                catch (\Exception $e)
                {
                    DB::rollback();
                    return response(['sucesso' => false,'mensagem' => 'Um erro interno ocorreu', 'error' => $e->getMessage()]);
                }
            }

            return response(['sucesso' => false,'mensagem' => 'Já existe um usuário com o email informado']);
        }

        return response(['sucesso' => false,'mensagem' => 'Forneça todos os campos obrigatórios']);
    }

    public function cadastrarUsuario(Request $request)
    {
        if($request->has(['nome', 'email', 'senha', 'telefone']))
        {
            $usuario = Usuario::where('email', $request->email)->first();

            if($usuario == null)
            {
                $carbon = new Carbon();

                $newUsuario = new Usuario();
                $newUsuario->nome     = $request->nome;
                $newUsuario->email    = $request->email;
                $newUsuario->senha    = md5($request->senha);
                $newUsuario->telefone = $request->telefone;
                $newUsuario->status   = "pendente";
                $newUsuario->token    = md5($request->email.$carbon->timestamp);

                $newUsuario->save();

                return response(['sucesso' => true, 'mensagem' => 'Usuário cadastrado com suceesso', 'usuario' => $newUsuario]);
            }

            return response(['sucesso' => false,'mensagem' => 'O email informado já está em uso por um usuário.']);
        }

        return response(['sucesso' => false,'mensagem' => 'Forneça todos os campos obrigatórios.']);
    }

    public function atualizarStatus(Request $request)
    {
        if($request->has(['usuario_id', 'novo_status']))
        {
            $usuario = Usuario::find($request->usuario_id);

            if(!is_null($usuario))
            {
                $usuario->update(['status'=> $request->novo_status]);

                return response(['sucesso' => true, 'mensagem' => 'Status atualizado com sucesso.']);
            }

            return response(['sucesso' => false, 'mensagem' => 'O usuário informado não existe.']);
        }

        return response(['sucesso' => false, 'mensagem' => 'Forneça todos os campos obrigatórios.']);
    }

    public function atribuirUniversidade(Request $request)
    {
        if($request->has(['universidade_id', 'usuario_id']))
        {
            $universidade = Universidade::find($request->universidade_id);
            $usuario = Usuario::find($request->usuario_id);

            if(!is_null($universidade))
            {
                if(!is_null($usuario))
                {
                    try {
                        DB::beginTransaction();

                        $newUniversidadeUsuarioPivot = new UniversidadeUsuarioPivot();
                        $newUniversidadeUsuarioPivot->universidade_id = $request->universidade_id;
                        $newUniversidadeUsuarioPivot->usuario_id = $request->usuario_id;
                        $newUniversidadeUsuarioPivot->save();

                        $permissao_pivot = PermissaoUsuarioPivot::where('usuario_id', $usuario->id)->first();

                        //se o usuário ainda não foi aprovado em nenhuma universidade então devemos atribuir sua permissão para o caso inicial
                        //caso contrário não é necessário criar novos pivots para a mesma permissão
                        if(is_null($permissao_pivot))
                        {
                            $usuario->status = "aprovado";
                            $usuario->save();

                            $permissao = Permissao::where('rota', 'api/usuario/')->first();

                            $newPermissaoUsuarioPivot = new PermissaoUsuarioPivot();
                            $newPermissaoUsuarioPivot->permissao_id = $permissao->id;
                            $newPermissaoUsuarioPivot->usuario_id = $usuario->id;
                            $newPermissaoUsuarioPivot->save();
                        }

                        DB::commit();
                        return response(['sucesso' => true, 'mensagem' => 'O usuário '.$usuario->nome.' foi aceito na universidade '.$universidade->nome.'.']);

                    } catch (\Exception $e) {
                        DB::rollback();
                        return response(['sucesso' => false, 'mensagem' => 'Um erro interno ocorreu: '.$e->getMessage()]);
                    }
                }

                return response(['sucesso' => false, 'mensagem' => 'O usuário informado não existe']);
            }

            return response(['sucesso' => false, 'mensagem' => 'A universidade informada não existe']);
        }

        return response(['sucesso' => false, 'mensagem' => 'Forneça todos os campos obrigatórios']);
    }
}
