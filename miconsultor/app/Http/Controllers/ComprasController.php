<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\requerimiento;
use App\requerimiento_bit as Bitacora;
use App\documento as Documento;


class ComprasController extends Controller
{

    // VALIDAR CONEXION
    public function ValidarConexion($RFCEmpresa, $Usuario, $Password, $TipoDocumento, $Modulo, $Menu, $SubMenu)
    {
        //Se puede omitir la validacion de tipo de usuario, y la de permisos de modulo, menu y submenu
        //mandando como parametro el valor 0 en cada uno.
        $conexion[0]['error'] = 0;
        $idempresa = DB::connection("General")->select("SELECT idempresa,rutaempresa,usuario_storage,password_storage,RFC FROM mc1000 WHERE RFC = '$RFCEmpresa'");
        if (!empty($idempresa)) {
            $Pwd = $Password; //password_hash($Password, PASSWORD_BCRYPT); //md5($Password);
            $conexion[0]['idempresa'] = $idempresa[0]->idempresa;
            $conexion[0]['rfc'] = $idempresa[0]->RFC;
            $conexion[0]['userstorage'] = $idempresa[0]->usuario_storage;
            $conexion[0]['passstorage'] = $idempresa[0]->password_storage;
            $idusuario = DB::connection("General")->select("SELECT idusuario, password FROM mc1001 WHERE correo = '$Usuario'");
            if (!empty($idusuario)) {
                $conexion[0]['idusuario'] = $idusuario[0]->idusuario;
                $ID = $idusuario[0]->idusuario;
                //if(password_verify($request->contra, $hash_BD)) {
                if (password_verify($Pwd, $idusuario[0]->password)) {
                    //if($Pwd == $idusuario[0]->password){
                    if ($Modulo != 0 && $Menu != 0 && $SubMenu != 0) {
                        ConnectDatabase($idempresa[0]->idempresa);
                        $permisos = DB::select("SELECT modulo.tipopermiso AS modulo, menu.tipopermiso AS menu, submenu.tipopermiso AS submenu FROM mc_usermod modulo, mc_usermenu menu, mc_usersubmenu submenu WHERE modulo.idusuario = $ID And menu.idusuario = $ID And submenu.idusuario = $ID And modulo.idmodulo = $Modulo AND menu.idmenu = $Menu AND submenu.idsubmenu = $SubMenu;");

                        if (!empty($permisos)) {
                            $conexion[0]['permisomodulo'] = $permisos[0]->modulo;
                            $conexion[0]['permisomenu'] = $permisos[0]->menu;
                            $conexion[0]['permisosubmenu'] = $permisos[0]->submenu;
                            if ($TipoDocumento != 0) {
                                $tipodocto = DB::connection("General")->select("SELECT tipo FROM mc1011 WHERE clave = $TipoDocumento");
                                if (!empty($tipodocto)) {
                                    $conexion[0]['tipodocumento'] = $tipodocto[0]->tipo;
                                } else {
                                    $conexion[0]['error'] = 5; //Tipo de documento no valido
                                }
                            }

                            //}else{
                            //    $conexion[0]['error'] = 4; //El Usuario no tiene permisos
                            //}                        

                        } else {
                            $conexion[0]['error'] = 4; //El Usuario no tiene permisos
                        }
                    }
                } else {
                    $conexion[0]['error'] = 3; //Contraseña Incorrecta
                }
            } else {
                $conexion[0]['error'] = 2; //Correo Incorrecto          
            }
        } else {
            $conexion[0]['error'] = 1; //RFC no existe
            //$conexion[0]['idusuario'] = 1;
        }
        return $conexion;
    }






    // GET REQUERIMIENTO NO HISTORIAL
    function obtenerRequerimiento(Request $request)
    {
        $autenticacion = $this->ValidarConexion($request->rfcempresa, $request->usuario, $request->pwd, 0, 4, $request->idmenu, $request->idsubmenu);
        $array["error"] = $autenticacion[0]["error"];
        if ($autenticacion[0]['error'] == 0) {
            ConnectDatabase($autenticacion[0]["idempresa"]);
            $idmenu = $request->idmenu;
            $idsubmenu = $request->idsubmenu;
            $iduser = $autenticacion[0]["idusuario"];
            $idconcepto = $request->idconcepto;

        //    ruquerimento  "select * from mc_reque  id_usuario= $iduser"

        //    conpeto  select idconcepto from mc_concepts = idusuario

        //     for 
        //         select  * from mc_req idconcepto = id concepto = filtro

            $req = DB::select("SELECT * FROM mc_requerimientos WHERE id_usuario = $iduser");
            // return $req;
            
            $concepto = DB::select("SELECT * FROM mc_conceptos WHERE id_usuario = $iduser");
            // return $concepto;
            $permisos = DB::select("SELECT * FROM mc_usuarios_concepto WHERE id_usuario= $iduser ");
            // return $permisos;

            if(!empty($permisos)){
                for ($i=0; $i < count($permisos); $i++) { 
                    // Poner varible antes
                    $req = DB::select("SELECT * FROM mc_requerimientos");
                    return $req;

                }
            }

            // if (!empty($permisos)) {
            //     $contador = 0;
            //     for ($i = 0; $i < count($req); $i++) {
            //         for ($j = 0; $j < count($permisos); $j++) {
            //             if ($req[$i]->id_concepto == $permisos[$j]->id_concepto) {
            //                 $array["requerimientos"][$contador] = $req[$i];
            //                 $contador = $contador + 1;
            //                 break;
            //             }
            //         }
            //     }
            //     return json_encode($array, JSON_UNESCAPED_UNICODE);
            // }

            
            if (!empty($req)) {
                for ($i = 0; $i < count($req); $i++) {
                    $idconcepto = $req[$i]->id_concepto;
                    $concepto = DB::select("SELECT * FROM mc_conceptos WHERE id = $idconcepto");
                    $req[$i]->concepto = $concepto[0]->nombre_concepto;
                    $idestado = $req[$i]->estado_documento;
                    $estado_documentos = DB::connection("General")->select("SELECT nombre_estado FROM mc1015 WHERE id = $idestado");
                    $req[$i]->estado = $estado_documentos[0]->nombre_estado;
                }
                $array["req"] = $req;
            }
            return json_encode($array, JSON_UNESCAPED_UNICODE);
        }
    }





    // TRAE EL HISTORIAL DE REQUERIMIENTOS
    function Bitacora(Request $request){
        $autenticacion = $this->ValidarConexion($request->rfcempresa, $request->usuario, $request->pwd, 0, 4, $request->idmenu, $request->idsubmenu);
        $array["error"] = $autenticacion[0]["error"];
        if ($autenticacion[0]['error'] == 0) {
            ConnectDatabase($autenticacion[0]["idempresa"]);
            $iduser = $autenticacion[0]["idusuario"];
            // $permisos = DB::select("SELECT * FROM mc_usuarios_concepto WHERE id_usuario= $iduser ");
            // $concepto = DB::select("SELECT * FROM mc_conceptos");
            $req = DB::select("SELECT * FROM mc_requerimientos");
            $bit = DB::select("SELECT * FROM mc_requerimientos_bit");
            $documentos = DB::select("SELECT * FROM mc_requerimientos_doc");
            for ($r = 0; $r < count($req); $r++) {
                for ($i = 0; $i < count($bit); $i++) {
                    $array["documentos"] = $documentos;
                    $idestado = $bit[$i]->status;
                    $estado_documentos = DB::connection("General")->select("SELECT nombre_estado FROM mc1015 WHERE id = $idestado");
                    $bit[$i]->estado = $estado_documentos[0]->nombre_estado;
                }
            }
            $array["bitacora"] = $bit;
        }
        return json_encode($array, JSON_UNESCAPED_UNICODE);
    }


    // ELIMINAR REQ
    public function eliminarRequerimiento(Request $request)
    {
        $autenticacion = $this->ValidarConexion($request->rfcempresa, $request->usuario, $request->pwd, 0, 4, $request->idmenu, $request->idsubmenu);
        $array["error"] = $autenticacion[0]["error"];
        if ($autenticacion[0]['error'] == 0) {
            $idmenu = $request->idmenu;
            $idsubmenu = $request->idsubmenu;
            $idreq = $request->idreq;
            ConnectDatabase($autenticacion[0]["idempresa"]);
            DB::table('mc_requerimientos')->where("id_req", $idreq)->delete();
            DB::table('mc_requerimientos_bit')->where("id_req", $idreq)->delete();
            DB::table('mc_requerimientos_doc')->where("id_req", $idreq)->delete();
            return response($idreq, 200);
        }
        return json_encode($array, JSON_UNESCAPED_UNICODE);
    }




    // NUEVO ESTADO
    public function nuevoEstado(Request $request)
    {
        $autenticacion = $this->ValidarConexion($request->rfcempresa, $request->usuario, $request->pwd, 0, 4, $request->idmenu, $request->idsubmenu);
        $array["error"] = $autenticacion[0]["error"];
        if ($autenticacion[0]['error'] == 0) {            
            ConnectDatabase($autenticacion[0]["idempresa"]);



        }
        return json_encode($array, JSON_UNESCAPED_UNICODE);
    }



    // UPDATE
    public function editarRequerimiento(Request $request)
    {
        $autenticacion = $this->ValidarConexion($request->rfcempresa, $request->usuario, $request->pwd, 0, 4, $request->idmenu, $request->idsubmenu);
        $array["error"] = $autenticacion[0]["error"];
        if ($autenticacion[0]['error'] == 0) {
        }
    }




    // POST REQUERIMIENTOS
    function nuevoRequerimiento(Request $request)
    {
        $autenticacion = $this->ValidarConexion($request->rfcempresa, $request->usuario, $request->pwd, 0, 4, $request->idmenu, $request->idsubmenu);
        $array["error"] = $autenticacion[0]["error"];
        if ($autenticacion[0]['error'] == 0) {

            
            $archivos = $request->file();
            $descripcion = $request->descripcion;
            $folio = $request->folio;
            $concepto = $request->concepto;
            $serie = $request->serie;
            $fecha = date("Ymd");
            $importe = $request->importe;
            $idempresa = $request->idempresa;
            $rfc = $request->rfc;
            $idsucursal = $request->idsucursal;
            $idusuario = $autenticacion[0]['idusuario'];
            $status = 1;

            
            // ConnectDatabase($autenticacion[0]["idempresa"]);
            newConexion($rfc);

            // $requerimientos = requerimiento::get();
            $requerimiento = new requerimiento();
            $requerimiento->fecha = $fecha;
            $requerimiento->id_usuario = $idusuario;
            $requerimiento->descripcion = $descripcion;
            $requerimiento->importe_estimado = $importe;
            $requerimiento->estado_documento = $status;
            $requerimiento->id_concepto = $concepto;
            $requerimiento->serie = $serie;
            $requerimiento->folio = $folio;
            $requerimiento->id_sucursal = $idsucursal;
            $requerimiento->save();


            $bitacora = new Bitacora();
            $bitacora->id_usuario = $requerimiento->id_usuario;
            $bitacora->id_req = $requerimiento->id_req;
            $bitacora->fecha = $fecha;
            $bitacora->descripcion = $descripcion;
            $bitacora->status = $requerimiento->estado_documento;
            $bitacora->save();


            $documento = new Documento();
            $documento->documento = 'document.' . $request->documento->extension();
            $documento->id_req = $requerimiento->id_req;
            $documento->tipo_doc = 1;
            $documento->download = '<ESTA PENDIENTE>';

            // foreach ($archivos as $key) {

                // if (strlen($countreg) == 1) {
                //     $consecutivo = "000" . $countreg;
                // } elseif (strlen($countreg) == 2) {
                //     $consecutivo = "00" . $countreg;
                // } elseif (strlen($countreg) == 3) {
                //     $consecutivo = "0" . $countreg;
                // } else {
                //     $consecutivo = $countreg;
                // }

            $documento->save();
        }
    }


    function SubirArchivosCloud($archivo_name, $ruta_temp, $rfcempresa, $servidor, $usuario, $password, $menu, $submenu, $fechadocto, $consecutivo)
    {

        $mod = substr(strtoupper($submenu), 0, 3);
        $directorio = $rfcempresa . '/Entrada/' . $menu . '/' . $submenu;
        $string = explode("-", $fechadocto);
        $codfec = substr($string[0], 2) . $string[1];
        $codarchivo = $rfcempresa . "_" . $codfec . "_" . $mod . "_";

        $ch = curl_init();
        $file = $archivo_name;
        $filename = $codarchivo . $consecutivo;
        $source = $ruta_temp; //Obtenemos un nombre temporal del archivo        
        $type = explode(".", $file);
        $target_path = $directorio . '/' . $filename . "." . $type[count($type) - 1];

        $gestor = fopen($source, "r");
        $contenido = fread($gestor, filesize($source));

        curl_setopt_array(
            $ch,
            array(
                CURLOPT_URL => 'https://' . $servidor . '/remote.php/dav/files/' . $usuario . '/CRM/' . $target_path,
                CURLOPT_VERBOSE => 1,
                CURLOPT_USERPWD => $usuario . ':' . $password,
                CURLOPT_POSTFIELDS => $contenido,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_BINARYTRANSFER => true,
                CURLOPT_CUSTOMREQUEST => 'PUT',
            )
        );
        $resp = curl_exec($ch);
        $error_no = curl_errno($ch);
        fclose($gestor);
        curl_close($ch);
        $array["archivo"]["target"] = $target_path;
        $array["archivo"]["codigo"] = $filename;
        $array["archivo"]["error"] = $error_no;

        return $array;
    }
}
