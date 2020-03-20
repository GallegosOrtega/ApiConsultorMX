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

///Pruebas
Route::get('Pruebas', 'UsuariosController@Pruebas');

Route::post('AlmacenCargado', 'GeneralesController@AlmacenCargado');
Route::post('DatosDeInicio', 'GeneralesController@DatosDeInicio');
//Route::get('ListaSucursales/{idempresa}', 'EmpresasController@ListaSucursales');

//Empresas
Route::get('ListaEmpresas', 'EmpresasController@ListaEmpresas');
Route::get('DatosEmpresaAD/{idempresa}', 'EmpresasController@DatosEmpresaAD');
Route::post('EliminarEmpresaAD', 'EmpresasController@EliminarEmpresaAD');
Route::post('GuardarEmpresaAD', 'EmpresasController@GuardarEmpresaAD');
Route::get('DatosEmpresa', 'EmpresasController@DatosEmpresa');
Route::post('DatosFacturacion', 'EmpresasController@DatosFacturacion');
Route::post('ActualizaVigencia', 'EmpresasController@ActualizaVigencia');

///Usuarios
Route::post('Login', 'UsuariosController@Login');
Route::get('DatosUsuario/{idusuario}', 'UsuariosController@DatosUsuario');
Route::post('EliminarUsuario', 'UsuariosController@EliminarUsuario');
Route::post('Desvincular', 'UsuariosController@Desvincular');
Route::get('NotificacionesUsuario', 'UsuariosController@NotificacionesUsuario');
route::post('ModificaNotificacion', 'UsuariosController@ModificaNotificacion');
route::post('VinculacionUsuarios', 'UsuariosController@VinculacionUsuarios');

Route::get('ListaUsuariosAdmin/{idempresa}', 'UsuariosController@ListaUsuariosAdmin');
Route::get('ListaUsuarios/{idempresa}', 'UsuariosController@ListaUsuarios');
Route::post('GuardaUsuario', 'UsuariosController@GuardaUsuario');
Route::post('GuardarEmpresa', 'EmpresasController@GuardarEmpresa');

//Verifica Usuario por correo
Route::post('ObtenerUsuarioNuevo', 'UsuariosController@ObtenerUsuarioNuevo');
Route::post('VerificaUsuario', 'UsuariosController@VerificaUsuario');
Route::post('ValidarCorreo', 'UsuariosController@ValidarCorreo');
Route::post('VerificaCelular', 'UsuariosController@VerificaCelular');

//Restablece Contraseña
Route::post('RestablecerContraseña', 'UsuariosController@RestablecerContraseña');

//MODULOS Y PERFILES
Route::get('Modulos', 'UsuariosController@Modulos');
Route::get('DatosModulo/{IDMod}', 'UsuariosController@DatosModulo');
Route::get('Perfiles', 'GeneralesController@PerfilesGen');
Route::get('DatosPerfil', 'UsuariosController@DatosPerfil');
Route::get('ListaPermisos/{IDPer}', 'UsuariosController@ListaPermisos');
Route::post('EliminaPermiso', 'UsuariosController@EliminaPermiso');
Route::post('GuardaPermiso', 'UsuariosController@GuardaPermiso');
Route::post('GuardaPerfil', 'UsuariosController@GuardaPerfil');

Route::get('PerfilUsuario', 'GeneralesController@PerfilUsuario');
Route::get('PermisosUsuario', 'GeneralesController@PermisosUsuario');
Route::post('VinculaEmpresa', 'GeneralesController@VinculaEmpresa');
Route::get('PerfileEmpresa/{idempresa}', 'GeneralesController@PerfilesEmpresa');
Route::post('EliminarPerfilEmpresa', 'GeneralesController@EliminarPerfilEmpresa');
Route::get('PermisosPerfil', 'GeneralesController@PermisosPerfil');
Route::post('updatePermisoUsuario', 'GeneralesController@updatePermisoUsuario');
Route::post('EditarPerfilEmpresa', 'GeneralesController@EditarPerfilEmpresa');
Route::post('updatePermisoPerfil', 'GeneralesController@updatePermisoPerfil');

//Checa BD Disponible
Route::get('BDDisponible', 'EmpresasController@BDDisponible');
Route::post('AsignaBD', 'EmpresasController@AsignaBD');
Route::post('CrearTablasEmpresa', 'EmpresasController@CrearTablasEmpresa');
Route::post('UsuarioEmpresa', 'EmpresasController@UsuarioEmpresa');
Route::post('UsuarioProfile', 'EmpresasController@UsuarioProfile');
Route::post('EliminaAsignaBD', 'EmpresasController@EliminaAsignaBD');
Route::post('EliminarRegistro', 'EmpresasController@EliminarRegistro');
Route::post('EliminarTablas', 'EmpresasController@EliminarTablas');
Route::post('EliminarUsuarioEmpresa', 'EmpresasController@EliminarUsuarioEmpresa');
Route::post('ProfileVinculacion', 'PermisosController@ProfileVinculacion');

//PERMISOS
Route::get('PermisoModulos', 'PermisosController@PermisoModulos');
Route::get('PermisoMenus', 'PermisosController@PermisoMenus');
Route::get('PermisoSubMenus', 'PermisosController@PermisoSubMenus');
Route::get('NombreModulo', 'PermisosController@NombreModulo');
Route::get('NombreMenu', 'PermisosController@NombreMenu');
Route::get('NombreSubMenu', 'PermisosController@NombreSubMenu');
Route::get('Modulos', 'PermisosController@Modulos');
Route::get('Menus', 'PermisosController@Menus');
Route::get('SubMenus', 'PermisosController@SubMenus');
Route::get('MenusPermiso', 'PermisosController@MenusPermiso');
Route::get('SubMenuPermiso', 'PermisosController@SubMenuPermiso');

//UPDATES PERMISOS
Route::post('UpdatePermisoModulo', 'PermisosController@UpdatePermisoModulo');
Route::post('UpdatePermisoMenu', 'PermisosController@UpdatePermisoMenu');
Route::post('UpdatePermisoSubMenu', 'PermisosController@UpdatePermisoSubMenu');

//PARAMETROS GENERALES
Route::post('Parametros', 'EmpresasController@Parametros');

//PERFILES DE LA EMPRESA 23/03/2019
Route::post('GuardaPerfilEmpresa', 'GeneralesController@GuardaPerfilEmpresa');
Route::post('ModulosPerfil', 'GeneralesController@ModulosPerfil');
Route::post('MenuPerfil', 'GeneralesController@MenuPerfil');
Route::post('SubMenuPerfil', 'GeneralesController@SubMenuPerfil');
Route::get('DatosPerfilEmpresa', 'GeneralesController@DatosPerfilEmpresa');
Route::get('PermisosModPerfil', 'GeneralesController@PermisosModPerfil');
Route::get('PermisosMenusPerfil', 'GeneralesController@PermisosMenusPerfil');
Route::get('PermisoSubMenusPerfil', 'GeneralesController@PermisoSubMenusPerfil');


//ARCHIVOS EMPRESA - ALMACEN DIGITAL
Route::post('RubrosGen', 'ConsumoController@RubrosGen');
Route::post('CatSucursales', 'ConsumoController@CatSucursales');
Route::post('AlmCargaArchivos', 'ConsumoController@AlmCargaArchivos');
Route::post('AlmacenConsumo', 'ConsumoController@AlmacenConsumo');
Route::post('AlmacenMarcado', 'ConsumoController@AlmacenMarcado');
Route::post('CambiaRubroDocumento', 'ConsumoController@CambiaRubroDocumento');
Route::post('ExtraerConsecutivo', 'ConsumoController@ExtraerConsecutivo');
Route::post('EliminaDocumentosAPI', 'ConsumoController@EliminaDocumentosAPI');

//PARA EL ADMINISTRADOR GENERAL DE LA PAGINA
Route::get('LoginAdmin', 'AdministradorController@LoginAdmin');
Route::post('numEstadistica', 'AdministradorController@numEstadistica');
Route::get('allempresas', 'AdministradorController@allempresas');

//API FC_PREMIUM
Route::post('enviarModulos', 'FcPremiumController@enviarModulos');
Route::post('versionesModulos', 'FcPremiumController@versionesModulos');
Route::post('datosVersion', 'FcPremiumController@datosVersion');
Route::post('linkArchivo', 'FcPremiumController@linkArchivo');
Route::post('actualizaVersion', 'FcPremiumController@actualizaVersion');
Route::post('altaCliente', 'FcPremiumController@altaCliente');
Route::post('verificarLicencia', 'FcPremiumController@verificarLicencia');
Route::post('validarClave', 'FcPremiumController@validarClave');
Route::post('activa', 'FcPremiumController@activa');

//VALIDACIONES RECEPCION POR LOTES
Route::get('ConsultarLotes', 'GeneralesController@ConsultarLotes');
Route::get('ConsultarDoctos', 'GeneralesController@ConsultarDoctos');
Route::get('ConsultarMovtosLote', 'GeneralesController@ConsultarMovtosLote');
Route::get('ConsultarMovtosDocto', 'GeneralesController@ConsultarMovtosDocto');
//Route::post('RegistrarLote', 'GeneralesController@RegistrarLote');
Route::post('EliminarLote', 'GeneralesController@EliminarLote');
Route::post('EliminarDocto', 'GeneralesController@EliminarDocto');
Route::post('VerificarLote', 'GeneralesController@VerificarLote');
Route::post('ChecarCatalogos', 'GeneralesController@ChecarCatalogos');
Route::post('RegistrarElemento', 'GeneralesController@RegistrarElemento');
Route::get('Paginador', 'GeneralesController@Paginador');
Route::post('VerificarClave', 'GeneralesController@VerificarClave');

//PARA CONSUMO DEL MODULO DE INVENTARIOS
Route::post('LoteCargado', 'GeneralesController@LoteCargado');
Route::post('LoteMarcado', 'ConsumoController@LoteMarcado');
Route::post('LoteConsumo', 'ConsumoController@LoteConsumo');
Route::post('LoteCatalogos', 'ConsumoController@LoteCatalogos');
Route::get('DatosAlmacen', 'ConsumoController@DatosAlmacen');
Route::get('ArchivosAlmacen', 'ConsumoController@ArchivosAlmacen');
Route::post('EliminaArchivoAlmacen', 'ConsumoController@EliminaArchivoAlmacen');
Route::get('DatosFiltroAvanzado', 'ConsumoController@DatosFiltroAvanzado');
Route::get('FiltrarDatos', 'ConsumoController@FiltrarDatos');
Route::post('LinkDescarga', 'ConsumoController@LinkDescarga');
Route::post('EliminaDocumentoAll', 'ConsumoController@EliminaDocumentoAll');
Route::get('ArchivosCorreccionLinks', 'ConsumoController@ArchivosCorreccionLinks');


// PARA EL PROCESO DE COMPRAS
Route::post('ReqCompras', 'ConsumoController@ReqCompras');


//10/09/2019 PARA LEER LA BITACORA
Route::post('archivosBitacora', 'FcPremiumController@archivosBitacora');

//STORAGE
Route::get('DatosStorage', 'ConsumoController@DatosStorage');
Route::get('DatosStorageADM', 'ConsumoController@DatosStorageADM');
Route::post('registraBitacora', 'AdministradorController@registraBitacora');

//19/09/2019 PARA LA ADMINISTRACION DE EMPRESAS DE LOS AGENTES DESKTOP
Route::post('datosadmin', 'AdministradorController@datosadmin');
Route::post('empresasadmin', 'AdministradorController@empresasadmin');
Route::post('serviciosfc', 'AdministradorController@serviciosfc');
Route::post('servicioscontratados', 'AdministradorController@servicioscontratados');
Route::post('bitacoraservicios', 'AdministradorController@bitacoraservicios');
Route::post('updateBitacora', 'AdministradorController@updateBitacora');
Route::post('listaejercicios', 'AdministradorController@listaejercicios');
Route::post('listaserviciosbit', 'AdministradorController@listaServicios_bit');
Route::post('listaagentesbit', 'AdministradorController@listaAgentes_bit');
Route::post('existebitacora', 'AdministradorController@Existe_bitacora');
Route::post('MarcaBitacora', 'AdministradorController@MarcaBitacora');
Route::post('EntregadoDocumento', 'AdministradorController@EntregadoDocumento');

//ASOCIACION DIGITAL
Route::post('SubMenusFiltro', 'PermisosController@SubMenusFiltro');
Route::post('RubrosUser', 'PermisosController@RubrosUser');
Route::post('addSucursal', 'AdministradorController@addSucursal');
Route::post('addRubros', 'AdministradorController@addRubros');
Route::post('datosRubros', 'AdministradorController@datosRubros');
Route::post('datosSucursal', 'AdministradorController@datosSucursal');
Route::post('datosRubrosSubMenu', 'AdministradorController@datosRubrosSubMenu');
Route::post('documentosdigitales', 'AdministradorController@documentosdigitales');
Route::post('usuarionube', 'AdministradorController@usuarionube');
Route::post('Plantillas', 'AdministradorController@Plantillas');



// AUTORIZACION Y COMPRAS
Route::get('obtenerRequerimiento', 'ComprasController@obtenerRequerimiento');
Route::get('Bitacora', 'ComprasController@Bitacora');
Route::get('ArchivosRequerimientos', 'ComprasController@ArchivosRequerimientos');
Route::post('nuevoRequerimiento', 'ComprasController@nuevoRequerimiento');
Route::post('eliminarRequerimiento', 'ComprasController@eliminarRequerimiento');
Route::post('editarRequerimiento', 'ComprasController@editarRequerimiento');
Route::post('nuevoEstado', 'ComprasController@nuevoEstado');

//APLICAICON MOVIL
Route::post('DatosUsuarios', 'AppController@DatosUsuarios');

Route::get('listaempresas', 'AdministradorController@listaempresas');

//MENU 2020
Route::get('menuWeb', 'MenuController@menuWeb');

//USUARIO 2020
Route::post('inicioUsuario', 'UsuarioController@inicioUsuario');
Route::get('permisosUsuario', 'UsuarioController@permisosUsuario');
Route::post('registrarUsuario', 'UsuarioController@registrarUsuario');
Route::post('verificaCodigo', 'UsuarioController@verificaCodigo');
Route::post('reenviaCodigo', 'UsuarioController@reenviaCodigo');
Route::get('listaUsuariosEmpresa', 'UsuarioController@listaUsuariosEmpresa');
Route::put('modificaPermisoModulo', 'UsuarioController@modificaPermisoModulo');
Route::put('modificaPermisoMenu', 'UsuarioController@modificaPermisoMenu');
Route::put('modificaPermisoSubmenu', 'UsuarioController@modificaPermisoSubmenu');
Route::get('permisosUsuarioGeneral', 'UsuarioController@permisosUsuarioGeneral');
Route::put('desvinculaUsuario', 'UsuarioController@desvinculaUsuario');
Route::delete('eliminaUsuarioEmpresa', 'UsuarioController@eliminaUsuarioEmpresa');
Route::post('editaNotificacion', 'UsuarioController@editaNotificacion');

//EMPRESA 2020
Route::get('listaEmpresasUsuario', 'EmpresaController@listaEmpresasUsuario');
Route::post('validaEmpresa', 'EmpresaController@validaEmpresa');
Route::get('enviaCorreoEmpresa', 'EmpresaController@enviaCorreoEmpresa');
Route::post('registraEmpresa', 'EmpresaController@registraEmpresa');
Route::get('datosEmpresa', 'EmpresaController@datosEmpresa');


//ACTUALIZA PERFILES GENERALES
Route::put('actualizaPerfilesGeneral', 'ActualizarBaseDatosController@actualizaPerfilesGeneral'); // 14/02/2020
Route::put('actualizaPerfilesEmpresa', 'ActualizarBaseDatosController@actualizaPerfilesEmpresa'); // 14/02/2020
Route::put('actualizaPermisosUsuario', 'ActualizarBaseDatosController@actualizaPermisosUsuario'); // 14/02/2020
Route::put('creaTablasRequerimientos', 'ActualizarBaseDatosController@creaTablasRequerimientos'); // 14/02/2020

//AUTORIZACION Y GASTOS 2020
Route::get('cargaConceptos', 'AutorizacionyGastosController@cargaConceptos');
Route::get('cargaEstatus', 'AutorizacionyGastosController@cargaEstatus');
Route::post('nuevoRequerimiento', 'AutorizacionyGastosController@nuevoRequerimiento');
Route::get('listaRequerimientos', 'AutorizacionyGastosController@listaRequerimientos');
Route::get('datosRequerimiento', 'AutorizacionyGastosController@datosRequerimiento');
Route::put('agregaEstatus', 'AutorizacionyGastosController@agregaEstatus');
Route::delete('eliminaEstatus', 'AutorizacionyGastosController@eliminaEstatus');
Route::get('permisosAutorizaciones', 'AutorizacionyGastosController@permisosAutorizaciones');
Route::put('guardaPermisoAutorizacion', 'AutorizacionyGastosController@guardaPermisoAutorizacion');
Route::delete('eliminaPermisoAutorizacion', 'AutorizacionyGastosController@eliminaPermisoAutorizacion');
Route::post('editarRequerimiento', 'AutorizacionyGastosController@editarRequerimiento');
Route::delete('eliminaDocumento', 'AutorizacionyGastosController@eliminaDocumento');
Route::delete('eliminaRequerimiento', 'AutorizacionyGastosController@eliminaRequerimiento');
Route::post('creaGasto', 'AutorizacionyGastosController@creaGasto');
Route::get('getTotalImporte', 'AutorizacionyGastosController@getTotalImporte');
Route::get('traerLimiteGastosUsuario', 'AutorizacionyGastosController@traerLimiteGastosUsuario');
Route::post('guardaLimiteGastos', 'AutorizacionyGastosController@guardaLimiteGastos');


//ALMACEN DIGITAL OPERACIONES 2020
Route::get('listaAlmacenDigital', 'AlmacenDigitalOperacionesController@listaAlmacenDigital');
Route::get('archivosAlmacenDigital', 'AlmacenDigitalOperacionesController@archivosAlmacenDigital');
Route::post('cargaArchivosAlmacenDigital', 'AlmacenDigitalOperacionesController@cargaArchivosAlmacenDigital');
Route::post('eliminaArchivosDigital', 'AlmacenDigitalOperacionesController@eliminaArchivosDigital');

//API PARA LOS EXPEDIENTES DE LOS MODULOS
Route::post('ClipMarcado', 'ConsumoController@ClipMarcado');

//NOTIFICACIONES CRM
Route::get('notificacionesCRM', 'NotificacionesController@notificacionesCRM');
Route::get('usuariosNotificacion', 'NotificacionesController@usuariosNotificacion');
Route::post('reenviarNotificacion', 'NotificacionesController@reenviarNotificacion');
Route::delete('eliminaNotificacion', 'NotificacionesController@eliminaNotificacion');

//PERFILES
Route::get('listaPerfiles', 'PerfilesController@listaPerfiles');