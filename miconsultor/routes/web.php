<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('Pruebas', 'UsuariosController@Pruebas');

Route::get('ListaSucursales/{idempresa}', 'EmpresasController@ListaSucursales');

Route::post('Login', 'UsuariosController@Login');
Route::post('EliminarUsuario', 'UsuariosController@EliminarUsuario');

Route::post('GuardaUsuario', 'UsuariosController@GuardaUsuario');


