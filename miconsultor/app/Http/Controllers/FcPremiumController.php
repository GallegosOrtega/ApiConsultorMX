<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class FcPremiumController extends Controller
{
    function enviarModulos(Request $request){
        $rfcempresa = $request->rfc;
        $nombrecliente = $request->razon;
        
       
        $now = date('Y-m-d');
        $empresa = DB::connection("General")->select("SELECT idcliente FROM fcclientes WHERE rfc='$rfcempresa'");    
        if (empty($empresa)){
           
            $idcliente = DB::connection("General")->table('fcclientes')->insertGetId(
                ['nombre_cliente' => $nombrecliente,'rfc' => $rfcempresa,
                'fecha' => $now,'status' =>"1" ]);  
        }else{
            $idcliente = $empresa[0]->idcliente;
        }
 
        $modulos = DB::connection("General")->select("SELECT  m.idmodulo,m.nombre_modulo,MAX(v.nombre_version) as mVer FROM fcmodulos m 
        INNER JOIN fcmodversion v ON m.idmodulo = v.idmodulo GROUP BY m.idmodulo");
        foreach($modulos as $t){
            $modulos2 = DB::connection("General")->select("SELECT idcliente FROM fcmodclientes WHERE idcliente='$idcliente' AND idmodulo='$t->idmodulo'");
            if (empty($modulos2)){
                $idU = DB::connection("General")->table('fcmodclientes')->insertGetId(
                    ['idcliente' => $idcliente,'idmodulo' => $t->idmodulo,
                    'idversion' => "0", 'permiso' => "0" ]);
            }
        }
        
        $datos = array(
            "modulo" => $modulos,
        ); 
        return json_encode($datos, JSON_UNESCAPED_UNICODE);

    }

    function versionesModulos(Request $request){

        $idmodulo = $request->idmodulo;

        $versiones = DB::connection("General")->select("SELECT idversion,nombre_version FROM fcmodversion 
                                        WHERE idmodulo='$idmodulo' and status=1");    
        $datos = array(
            "version" => $versiones,
        ); 
        return json_encode($datos, JSON_UNESCAPED_UNICODE);
       
    }

    function datosVersion(Request $request){
        $idversion = $request->idversion;
        $version = DB::connection("General")->select("SELECT m.nombre_archivo AS nomfin,m.nombre_carpeta,
                                                    v.nombre_version,v.nombre_archivo AS nomdes,
                                                    v.nombre_ficha,v.script_gen,v.spreedsheets,v.arch_version
                                                    FROM fcmodulos m INNER JOIN fcmodversion v 
                                                    ON m.idmodulo=v.idmodulo WHERE v.idversion='$idversion'");    
        $datos = array(
            "version" => $version,
        ); 
        return json_encode($datos, JSON_UNESCAPED_UNICODE);
    } 

    function linkArchivo(Request $request){
            $linkurl = $request->link;
            $carpeta = $request->archivo;
            $usernube = $request->user;
            $passnube = $request->pass;
            $ch = curl_init();


            curl_setopt($ch, CURLOPT_URL, $linkurl);
            curl_setopt($ch, CURLOPT_VERBOSE, 1);
        
            curl_setopt($ch, CURLOPT_USERPWD, $usernube.":".$passnube);
            curl_setopt($ch, CURLOPT_POSTFIELDS, "path=".$carpeta."&shareType=3");

            curl_setopt($ch, CURLOPT_HTTPHEADER, array('OCS-APIRequest:true'));
            curl_setopt($ch, CURLOPT_HEADER, true);

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);

            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");

            $httpResponse = curl_exec($ch);

            $httpResponse = explode("\n\r\n", $httpResponse);

            $body = $httpResponse[1];
        
            $Respuesta= simplexml_load_string($body);
            
            $url = ((string)$Respuesta[0]->data->url);
            curl_close($ch);
            return $url;
    }

    function actualizaVersion(Request $request){
        $rfcempresa = $request->rfc;
        $idmodulo = $request->idmodulo;
        $fecha = $request->fecha;
        $version = $request->version;
        $permiso = $request->permiso;

        $empresa = DB::connection("General")->select("SELECT idcliente FROM fcclientes WHERE rfc='$rfcempresa'");    
        $idcliente = $empresa[0]->idcliente;

        $empresa = DB::connection("General")->select("SELECT idversion FROM fcmodversion 
                                        WHERE idmodulo='$idmodulo' AND nombre_version='$version'");    
        if (!empty($empresa)){
        $idversion = $empresa[0]->idversion;

        DB::connection("General")->table('fcmodclientes')->where("idcliente", $idcliente)->
            where("idmodulo", $idmodulo)->
            update(['fecha_actualiza' => $fecha, 'idversion' => $idversion, 'permiso' => $permiso]);
        }    
        return "1";
    }

    function altaCliente(Request $request){
        $rfcempresa = $request->rfc;
        $nomcliente = $request->razon;
        $idcliente= 0;
        $now = date('Y-m-d');
        $empresa = DB::connection("General")->select("SELECT idcliente FROM fcclientes WHERE rfc='$rfcempresa'");    
        if (empty($empresa)){
            if($rfcempresa != "" && $nomcliente != ""){
                $idcliente = DB::connection("General")->table('fcclientes')->insertGetId(
                    ['nombre_cliente' => $nomcliente,'rfc' => $rfcempresa,
                    'fecha' => $now,'status' =>"1" ]); 
            }
        }else{
            $idcliente = $empresa[0]->idcliente;
        }
        return $idcliente;
    }

    function verificarLicencia(Request $request){
        $rfcempresa = $request->rfc;
        $nomcliente = $request->razon;
        $equipo= $request->equipo;
        $clave =$request->clave;
        $idcliente= 0;
        $datoex= "FcPremium2019";
        $now = date('Y-m-d');
        $Serial="";
        $retur = "Licencia No Valida, , ";

        $empresa = DB::connection("General")->select("SELECT idcliente FROM fcclientes WHERE rfc='$rfcempresa'");    
        if (empty($empresa)){
            if($rfcempresa != "" && $nomcliente != ""){
                $idcliente = DB::connection("General")->table('fcclientes')->insertGetId(
                    ['nombre_cliente' => $nomcliente,'rfc' => $rfcempresa,
                    'fecha' => $now,'status' =>"1" ]); 
            }
        }else{
            $idcliente = $empresa[0]->idcliente;
        }

        if ($idcliente != 0){
            $licencia = DB::connection("General")->select("SELECT idlicencia,clave_activacion,fechafin FROM fclicencias 
                                                            WHERE numero_serie='$clave' AND idcliente=0 AND status=0");
            $Serial= bcrypt($clave.$equipo.$rfcempresa.$datoex);
            if (!empty($licencia)){
                DB::connection("General")->table('fclicencias')->where("idlicencia", $licencia[0]->idlicencia)->
                    update(['idcliente' => $idcliente, 'clave_activacion' => $Serial, 'equipo' => $equipo, 'status' => "1"]);

                $retur = "Licencia Valida,".$Serial.",".$licencia[0]->fechafin;
            }
        }

        return $retur;
    }

    function validarClave(Request $request){
        $rfcempresa = $request->rfc;
        $equipo= $request->equipo;
        $clave = $request->clave;
       

        $empresa = DB::connection("General")->select("SELECT idcliente FROM fcclientes WHERE rfc='$rfcempresa' AND status=1");    
        if (!empty($empresa)){
            $idcliente = $empresa[0]->idcliente;
            $licencia = DB::connection("General")->select("SELECT clave_activacion FROM fclicencias WHERE
                                             idcliente='$idcliente' AND equipo='$equipo' AND status=1"); 
            if (!empty($licencia)){
                if ($licencia[0]->clave_activacion == $clave ){
                    $res = 1;    
                }
            }
        }

        return $res;
    }

    function activa(Request $request){
        $rfcempresa = $request->rfc;
        $equipo= $request->equipo;
        $retur = "Licencia No Valida, , ";

        $empresa = DB::connection("General")->select("SELECT idcliente FROM fcclientes WHERE rfc='$rfcempresa' AND status=1");    
        if (!empty($empresa)){
            $idcliente = $empresa[0]->idcliente;
            $licencia = DB::connection("General")->select("SELECT clave_activacion,fechafin FROM fclicencias WHERE
                                             idcliente='$idcliente' AND equipo='$equipo' AND status=1"); 
            $retur = "Licencia Valida,".$licencia[0]->clave_activacion.",".$licencia[0]->fechafin;
        }
        return $retur;
    }

    function archivosBitacora(Request $request){
        $rfcempresa = $request->rfc;
        $idsubmenu = $request->idsubmenu;
        $tipo = $request->tipodocumento;
        $status = $request->status;

        $empresa = DB::connection("General")->select("SELECT idempresa FROM mc1000 WHERE rfc='$rfcempresa' AND status=1");
        if (!empty($empresa)){
            $idempresa = $empresa[0]->idempresa;
            ConnectDatabase($idempresa);

            $archivos = DB::select("SELECT fecha,archivo,nombrearchivo,idusuarioentrega,periodo,ejercicio,tipodocumento FROM mc_bitcontabilidad WHERE
                                 idsubmenu = $idsubmenu And status = $status order by fecha desc");
            $datos = $archivos;
            return $datos;
        } 
        return "false";
    }

    function registraBitacora(Request $request){
        $rfcempresa = $request->Rfc;
        $registros = $request->Regbitacora;
        $num_registros = count($request->Regbitacora);

        $now = date('Y-m-d');
        $fechamod = date('Y-m-d H:i:s');
        $reg = "false";
        $empresa = DB::connection("General")->select("SELECT idempresa FROM mc1000 WHERE rfc='$rfcempresa' AND status=1");
        if (!empty($empresa)){
            $idempresa = $empresa[0]->idempresa;
            ConnectDatabase($idempresa);
            for ($i=0; $i < $num_registros; $i++) {
                $periodo= $registros[$i]['Periodo'];
                $ejercicio = $registros[$i]['Ejercicio']; 
                $archivo = $registros[$i]['Archivo'];
                $nomarchi = $registros[$i]['Nombrearchivo'];
                $idusersub = $request->Idusuariosubida;   
                $status=  $request->Status;
                $iduserentrega = $request->Idusuarioentrega;
                $result = DB::select("SELECT id FROM mc_bitcontabilidad WHERE idsubmenu = $request->Idsubmenu
                                                    AND tipodocumento= '$request->Tipodocumento'
                                                    AND periodo= $periodo
                                                    AND ejercicio=$ejercicio");
                if(empty($result)){
                    $idU = DB::table('mc_bitcontabilidad')->insertGetId(
                        ['idsubmenu' => $request->Idsubmenu,'tipodocumento' => $request->Tipodocumento,
                        'periodo' => $periodo, 'ejercicio' => $ejercicio,
                        'fecha' => $now, 'fechamodificacion' => $fechamod,
                        'archivo' => $archivo, 'nombrearchivoG' => $nomarchi,
                        'status' => $status,'idusuarioE' => $iduserentrega,
                        'idusuarioG' => $idusersub]);
                }else{
                    DB::table('mc_bitcontabilidad')->where("idsubmenu", $request->Idsubmenu)->
                        where("tipodocumento", $request->Tipodocumento)->
                        where("periodo", $periodo)->where('ejercicio', $ejercicio)->
                        update(['fechamodificacion' => $fechamod]);
                } 
            }
            $reg = "true";
        } 
        return $reg;
    }

    

}
