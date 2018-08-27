<?php

use Illuminate\Http\Request;

//---------------------------------- ROTAS ADMIN --------------------------------------
Route::prefix('admin')->group(function()
{
    Route::post('cadastrar', 'UsuarioController@cadastrarAdmin');//CADATRAR ADMIN //testado

    Route::group(['middleware'=>['permissoes']], function()
    {
        Route::post('entrar', 'Auth\LoginController@autenticar');//AUTENTICAR USUÁRIO //testado
        Route::post('permissoes/criar', 'PermissaoController@cadastrar'); //CRIAR PERMISSÕES //testado
        Route::post('universidade/cadastrar', 'UniversidadeController@cadastrar'); //CADASTRAR UNIVERSIDADES //testado
        Route::post('usuario/status/atualizar', 'UsuarioController@atualizarStatus'); //ATUALIZAR STATUS DOS USUÁRIOS //testado
        Route::post('usuario/universidade/atribuir', 'UsuarioController@atribuirUniversidade'); //ATUALIZAR STATUS DOS USUÁRIOS //testado
    });
});

//---------------------------------- ROTAS USUARIOS --------------------------------------
Route::prefix('usuario')->group(function()
{
    Route::post('cadastrar', 'UsuarioController@cadastrarUsuario');//CADASTRAR USUÁRIO //testado
    Route::post('entrar', 'Auth\LoginController@autenticar')->middleware(['permissoes']);//AUTENTICAR USUÁRIO //testado
});
