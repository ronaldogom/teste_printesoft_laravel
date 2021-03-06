<?php

use Illuminate\Http\Request;

Route::post('permissoes/criar', 'PermissaoController@cadastrar'); //CRIAR PERMISSÕES //testado

//---------------------------------- ROTAS ADMIN --------------------------------------
Route::prefix('admin')->group(function()
{
    Route::post('cadastrar', 'UsuarioController@cadastrarAdmin');//CADATRAR ADMIN //testado

    Route::group(['middleware'=>['permissoes']], function()
    {
        Route::post('entrar', 'Auth\LoginController@autenticar');//AUTENTICAR USUÁRIO //testado
        Route::post('universidade/cadastrar', 'UniversidadeController@cadastrar'); //CADASTRAR UNIVERSIDADES //testado
        Route::post('universidade/listar', 'UniversidadeController@listarTodas'); //LISTAR UNIVERSIDADES //testado
        Route::post('usuario/status/atualizar', 'UsuarioController@atualizarStatus'); //LISTAR UNIVERSIDADES //testado
        Route::post('usuario/listar', 'UsuarioController@listarTodos'); //LISTAR USUÁRIOS PENDENTES //testado
        Route::post('usuario/universidade/atribuir', 'UsuarioController@atribuirUniversidade'); //ATUALIZAR STATUS DOS USUÁRIOS //testado
    });
});

//---------------------------------- ROTAS USUARIOS --------------------------------------
Route::prefix('usuario')->group(function()
{
    Route::post('cadastrar', 'UsuarioController@cadastrarUsuario');//CADASTRAR USUÁRIO //testado
    Route::post('entrar', 'Auth\LoginController@autenticar')->middleware(['permissoes']);//AUTENTICAR USUÁRIO //testado
});
