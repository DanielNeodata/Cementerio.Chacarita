<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Sac_funcavanzadas_asientos_desbalance extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }
    public function delete($values){
		try {
		    try
		    {
                $recno1 = $this->execAdHoc("sp_deleteAsiento ".$values["id"]);
		    }
		    catch(Exception $ee){
		        return logError($ee,__METHOD__ );
		        throw $del;
		    }
		    return array(
		        "code"=>"2000",
		        "status"=>"OK",
		        "message"=>lang('msg_delete'),
		        "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
		        "data"=>null
		        );
		}
		catch(Exception $e){
		    return logError($e,__METHOD__ );
		}
    }

    public function brow($values){
        try {
            $values["view"]="vw_ConEncabezados";
            $values["order"]="ID DESC";
            $values["where"]=" BALANCE<>0";
            $values["records"]=$this->get($values);
            $values["getters"]=array(
             "search"=>true,
             "googlesearch"=>true,
             "excel"=>true,
             "pdf"=>true,
           );

            $values["buttons"]=array(
                "new"=>false,
                "edit"=>false,
                "delete"=>true,
                "offline"=>false,
            );
            $values["columns"]=array(
                //array("field"=>"ID","format"=>"code"),
                //array("field"=>"ssst","format"=>"code"),
                array("field"=>"NUMERO_ENCABEZADO","format"=>"text"),
                array("field"=>"FECHA","format"=>"date"),
                array("field"=>"RENGLONES","format"=>"text"),
                array("field"=>"Balance","format"=>"number"),
                array("field"=>"COMENTARIO","format"=>"text"),

                array("field"=>"","format"=>null),
                array("field"=>"","format"=>null),
            );

            $values["controls"]=array(
                "<label>".lang('p_NUMERO_ENCABEZADO')."</label><input type='text' id='browser_numero_encabezado' name='browser_numero_encabezado' class='form-control text'/>",
                "<label>".lang('p_FECHA')."</label><input type='date' id='browser_fecha' name='browser_fecha' class='form-control date'/>",

            );

            $values["filters"]=array(
                array("name"=>"browser_fecha", "operator"=>"=","fields"=>array("FECHA")),
                array("name"=>"browser_numero_encabezado", "operator"=>"like","fields"=>array("NUMERO_ENCABEZADO")),
                array("name"=>"browser_search", "operator"=>"like","fields"=>array("NUMERO_ENCABEZADO","COMENTARIO","FECHA")),

            );
            return parent::brow($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }

    public function excel($values){
        try {
            if ($values["where"]!=""){$values["where"]=base64_decode($values["where"]);}
            $values["view"]="vw_SacRecibos";
            $values["delimiter"]=";";
            $values["pagesize"]=-1;
            $values["records"]=$this->get($values);
            $values["where"]=" BALANCE<>0";
            $values["columns"]=array(
                array("field"=>"ID","format"=>"code"),
                array("field"=>"NUMERO_ENCABEZADO","format"=>"text"),
                array("field"=>"FECHA","format"=>"date"),
                array("field"=>"COMENTARIO","format"=>"text"),
            );
            return parent::excel($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function pdf($values){
        try {
            if ($values["where"]!=""){$values["where"]=base64_decode($values["where"]);}
            $values["view"]="vw_SacRecibos";
            $values["pagesize"]=-1;
            $values["order"]="1 ASC";
            $values["records"]=$this->get($values);
            $values["where"]=" BALANCE<>0";
            $values["title"]="Asientos: Altas, Bajas, Consultas y Modificaciones";
            $values["columns"]=array(
                array("field"=>"ID","format"=>"code"),
               array("field"=>"NUMERO_ENCABEZADO","format"=>"text"),
                array("field"=>"FECHA","format"=>"date"),
                array("field"=>"COMENTARIO","format"=>"text"),
            );
            return parent::pdf($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function edit($values)
    {
        try {
            $location = explode("::", strtolower(__METHOD__));
            $values["interface"] = (MOD_BRITANICO . "/Sac_funcavanzadas_asientos/abm");
            $values["page"] = 1;
            $permisos = $this->execAdHocAsArray("exec obtenerPermisosAdicionales " . $values["id_user_active"] . ", 'abm_asientos_postedit'");
            //if (!$this->trueFalseFromSN($permisos[0]["permitido"]))
            //{
            //   $values["readonly"]=true;
            //}
            //else
            //{
            $values["accept-class-name"] = "btn-abm-accept-confirm";
            //}
            $values["view"] = "vw_ConEncabezados";
            $values["where"] = ("id=" . $values["id"]);
            $values["records"] = $this->get($values);
            return parent::edit($values);
        } catch (Exception $e) {
            return logError($e, __METHOD__);
        }
    }
    public function save($values, $fields = null)
    {
        $values["view"] = "CON_Encabezados";
        $values["table"] = "CON_Encabezados";
        try {
            if (!isset($values["id"])) {
                $values["id"] = 0;
            }
            $id = (int) $values["id"];
            $counter = $values["OPER-COUNTER"];
            if ($id == 0) {
                $recno = $this->execAdHocAsArray("exec sp_getNextNumber 'CE%'");
                $asiento = $recno[0]["RECNO"];
                $asiento = "CE" . $asiento;
                if ($fields == null) {
                    $fields = array(
                        'FECHA' => $values["FECHA"],
                        'RENGLONES' => $values["OPER-COUNTER"],
                        'COMENTARIO' => $values["COMENTARIO"],
                        'XTRUDEADO' => "M",
                        'NUMERO' => $asiento,
                    );
                }
                $id = $this->saveRecord($fields, $id, "CON_Encabezados");
                $asientoId = $id;
            } else {
                $asiento = $values["NUMERO_ENCABEZADO"];
                $asientoId = $id;
                $recno1 = $this->execAdHoc("exec sp_deleteAsientoRenglon " . $values["id"]);
                if ($fields == null) {
                    $fields = array(
                        'FECHA' => $values["FECHA"],
                        'RENGLONES' => $values["OPER-COUNTER"],
                        'COMENTARIO' => $values["COMENTARIO"],
                        'XTRUDEADO' => "M",
                        'NUMERO' => $asiento,
                    );
                }
                $id = $this->saveRecord($fields, $id, "CON_Encabezados");
            }
            for ($i = 1; $i <= $counter; $i++) {
                $cuenta = $values["detail-cuenta-" . $i];
                $tipcom = $values["detail-tipcom-" . $i];
                $numcom = $values["detail-numcom-" . $i];
                $imp = $values["detail-importe-" . $i];
                $come = $values["detail-comentario-" . $i];
                $fields1 = null;
                if ($fields1 == null) {
                    $fields1 = array(
                        'FECHA' => $values["FECHA"],
                        'idEncabezado' => $asientoId,
                        'ASIENTO' => $asiento,
                        'RENGLON' => $i,
                        'CUENTA' => $cuenta,
                        'TIPCOM' => $tipcom,
                        'NUMCOM' => $numcom,
                        'IMPORTE' => $imp,
                        'COMENTARIO' => $come,
                    );
                }
                $id2 = 0;
                $id2 = $this->saveRecord($fields1, $id2, "CON_Renglones");
            }
            $data = array("id" => $id);
            return array(
                "code" => "2000",
                "status" => "OK",
                "message" => $message,
                "recibo" => $recibo,
                "function" => ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ : ENVIRONMENT),
                "data" => $data,
            );

            //return parent::save($values,$fields);
        } catch (Exception $e) {
            return logError($e, __METHOD__);
        }


    }


}
