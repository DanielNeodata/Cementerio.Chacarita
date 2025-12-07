<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/


class inhumado extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }

    // brow, para el listado inicial de la pagina
    public function brow($values){
        try {
            ///$values["title"]="ABCM Fallecidos";
            
            
            $values["view"]="vw_inhumado";
            $values["order"]="NumeroInhumado";
            $values["records"]=$this->get($values);

            //$clave = "id_pk";  // <<<<----------- Intento fallido. Hay que generar columna id en las vistas de la DB
            $clave = "";
            if ($clave<>"") {
                $values["records"][$clave]="id_inhumado";
            }

            if (($values["records"])["status"]=="OK"){
                $registros=parent::setRegistersPK($values["records"], $clave);
                logGeneral($this,json_encode($registros),__METHOD__);
                $values["records"]=$registros;
            }
            logGeneral($this,json_encode($values),__METHOD__);

            $values["getters"]=array(
             "search"=>true,
             //"googlesearch"=>true,
             "excel"=>true,
             "pdf"=>true,
           );

            $values["buttons"]=array(
                "new"=>true,
                "edit"=>true,
                "delete"=>false,
                "offline"=>false,
            );
            $values["columns"]=array(
                //array("field"=>"id_inhumado","format"=>"code"),    // <----
                array("field"=>"historico","format"=>"text"),
                array("field"=>"msg_no_innovar","format"=>"text"),
                array("field"=>"NumeroInhumado","format"=>"text"),
                array("field"=>"Nombre","format"=>"text"),
                array("field"=>"Edad","format"=>"text"),                
                array("field"=>"nacionalidad","format"=>"text"),                
                array("field"=>"estado_civil","format"=>"text"),                
                array("field"=>"CausaDeceso","format"=>"text"),                
                array("field"=>"LugarDeceso","format"=>"text"),                
                array("field"=>"detalle_parcela","format"=>"text"),                
                array("field"=>"Nombre_cocheria","format"=>"text"),                
                array("field"=>"FechaDeceso","format"=>"date"),                
                //array("field"=>"Sector","format"=>"text"),
                //array("field"=>"Manzana","format"=>"text"),
                //array("field"=>"parcela","format"=>"text"),
                //array("field"=>"NumeroCliente","format"=>"text"),
                array("field"=>"NumeroCertificado","format"=>"text"),
                array("field"=>"NumeroCliente","format"=>"text"),
                //array("field"=>"id","format"=>"code"),
            );

            // Controles para los filtros?
            $values["controls"]=array(
                "<label>".lang('p_id_inhumado')."</label><input type='text' id='browser_id_inhumado' name='browser_id_inhummado' class='form-control number'/>",
                "<label>".lang('p_Nombre')."</label><input type='text' id='browser_nombre' name='browser_nombre' class='form-control text'/>",
                "<label>".lang('p_detalle_parcela')."</label><input type='text' id='browser_detalle_parcela' name='browser_detalle_parcela' class='form-control text'/>",
                "<label>".lang('p_NumeroCliente')."</label><input type='text' id='browser_NumeroCliente' name='browser_NumeroCliente' class='form-control number'/>",
                "<label>".lang('p_FechaDeceso')." Desde"."</label><input type='date' id='browser_FechaDeceso' name='browser_FechaDeceso' class='form-control date'/>",
            );

            // Filtros y search. Confirmar si funciona como AND o como OR
            $values["filters"]=array(
                array("name"=>"browser_id_inhumado", "operator"=>"=","fields"=>array("id_inhumado")),
                array("name"=>"browser_nombre", "operator"=>"like","fields"=>array("nombre")),
                array("name"=>"browser_detalle_parcela", "operator"=>"like","fields"=>array("detalle_parcela")),
                array("name"=>"browser_FechaDeceso", "operator"=>">=","fields"=>array("FechaDeceso")),
                array("name"=>"browser_NumeroCliente", "operator"=>"=","fields"=>array("NumeroCliente")),
                array("name"=>"browser_search", "operator"=>"like","fields"=>array("id_inhumado","nombre", "detalle_parcela", "NumeroCliente")),                
            );  
            return parent::brow($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }

    // ABM
    public function edit($values){
        try {
            $location=explode("::",strtolower(__METHOD__));
            $values["interface"]=(MOD_BRITANICO."/".$location[0]."/abm");
            $values["page"]=1;
            
            //$values["table"]="Inhumado";
            $values["table"]="vw_inhumado";
            //$values["view"]="Inhumado";
            $values["view"]="vw_inhumado";

            //$values["id_field"]="id_inhumado";  //<------------- SACAR
            //$values["id"]="id_inhumado";

            $values["where"]=("id_inhumado=".$values["id"]);

            $values["records"]=$this->get($values);

            // vw_estadoCivil: id y NombreEstadoCivil
            $parameters_id_estadoCivil=array(
                //"model"=>(MOD_BACKEND."/Type_users"),
                //"model"=>(MOD_BRITANICO."/EstadoCivil"),
                "model"=>(MOD_BRITANICO."/vw_estadoCivil"),
                //"model"=>"vw_EstadoCivil",
                "table"=>"vw_EstadoCivil",
                //"name"=>"id_estadoCivil",
                "name"=>"id_estadoCivil",  
                "class"=>"form-control dbase",
                "empty"=>true,
                "id_actual"=>secureComboPosition($values["records"],"id_estadoCivil"),
                "id_field"=>"id",
                //"id_field"=>"id_estadoCivil",
                "description_field"=>"NombreEstadoCivil",
                "get"=>array("order"=>"NombreEstadoCivil ASC","pagesize"=>-1),
            );
            // vw_TipoDocumento: id y Descripcion
            $parameters_id_TipoDocumento=array(
                "model"=>(MOD_BRITANICO."/vw_TipoDocumento"),
                "table"=>"vw_TipoDocumento",
                "name"=>"id_TipoDocumento",  
                "class"=>"form-control dbase",
                "empty"=>true,
                "id_actual"=>secureComboPosition($values["records"],"id_TipoDocumento"),
                "id_field"=>"id",
                "description_field"=>"Descripcion",
                "get"=>array("order"=>"Descripcion ASC","pagesize"=>-1),
            );
            // vw_PaisCombo: id y descripcion
            $parameters_id_paisNacionalidad=array(
                "model"=>(MOD_BRITANICO."/vw_PaisCombo"),
                "table"=>"vw_PaisCombo",
                "name"=>"id_paisNacionalidad",  // aca va el codigo
                "class"=>"form-control dbase",
                "empty"=>true,
                "id_actual"=>secureComboPosition($values["records"],"id_paisNacionalidad"),
                "id_field"=>"id",
                "description_field"=>"descripcion",
                "get"=>array("order"=>"descripcion ASC","pagesize"=>-1),
            );
            // vw_parcelaCombo: id_Parcela y NumeroCompacto
            $parameters_id_Parcela_ActualReadOnly=array(
                "model"=>(MOD_BRITANICO."/vw_parcelaCombo"),
                "table"=>"vw_parcelaCombo",
                "name"=>"id_Parcela_Actual_ActualReadOnly",  // aca va eñ nombre del campo de la tabla principal, inhumados por ej. Sirva como contenedor del dato luego de la seleccion.
                "class"=>"form-control dbase",
                "empty"=>true,
                "id_actual"=>secureComboPosition($values["records"],"id_Parcela_Actual"), // --> id de la tabla inhumados
                //"id_field"=>"id_Parcela_Actual", // --> id de la tabla inhumados
                "id_field"=>"id_Parcela", // --> esta es la PK de la tabla del combo y es la que usa para identificar e seleccionado.
                //"description_field"=>"NumeroCompacto",  // --> descripcion de la tabla del combo
                "description_field"=>"parcela_formateadaFull",  // --> descripcion de la tabla del combo
                //"get"=>array("where"=>"isnull(NumeroCompacto ,'')<>'' and Disponible='S'", "order"=>"NumeroCompacto  ASC","pagesize"=>-1),
                "get"=>array("order"=>"parcela_formateadaFull  ASC","pagesize"=>-1),
                "disabled"=>"disabled", // disable select control 
            );
            // La editable
            $parameters_id_Parcela_Actual=array(
                "model"=>(MOD_BRITANICO."/vw_parcelaCombo"),
                "table"=>"vw_parcelaCombo",
                "name"=>"id_Parcela_Actual",  // aca va eñ nombre del campo de la tabla principal, inhumados por ej. Sirva como contenedor del dato luego de la seleccion.
                "class"=>"form-control dbase",
                //"empty"=>true,
                "empty"=>false,
                "id_actual"=>secureComboPosition($values["records"],"id_Parcela_Actual"), // --> id de la tabla inhumados
                //"id_field"=>"id_Parcela_Actual", // --> id de la tabla inhumados
                "id_field"=>"id_Parcela", // --> esta es la PK de la tabla del combo y es la que usa para identificar e seleccionado.
                "description_field"=>"parcela_formateadaFull",  // --> descripcion de la tabla del combo
                //"get"=>array("where"=>"isnull(NumeroCompacto ,'')<>'' and Disponible='S'",
                //"get"=>array("order"=>"NumeroCompacto  ASC","pagesize"=>-1),
                "get"=>array("order"=>"parcela_formateadaFull ASC","pagesize"=>-1),
                //parcela_formateadaFull
            );            
            // vw_TipoServicio: id / id_tipo_servicio y NombreTipoServicio
            $parameters_id_tipo_servicio=array(
                "model"=>(MOD_BRITANICO."/vw_TipoServicio"),
                "table"=>"vw_TipoServicio",
                "name"=>"id_tipo_servicio",  // aca va el codigo
                "class"=>"form-control dbase",
                "empty"=>false,
                "id_actual"=>secureComboPosition($values["records"],"id_tipo_servicio"),
                "id_field"=>"id",
                //"id_field"=>"id_tipo_servicio",
                "description_field"=>"NombreTipoServicio",
                "get"=>array("order"=>"NombreTipoServicio ASC","pagesize"=>-1),
            );
            // vw_Cocheria: id / id_cocheria y Nombre_cocheria
            $parameters_id_cocheria=array(
                "model"=>(MOD_BRITANICO."/vw_Cocheria"),
                "table"=>"vw_Cocheria",
                "name"=>"id_cocheria",  // aca va el codigo
                "class"=>"form-control dbase",
                "empty"=>true,
                "id_actual"=>secureComboPosition($values["records"],"id_cocheria"),
                "id_field"=>"id",
                //"id_field"=>"id_cocheria",
                "description_field"=>"Nombre_cocheria",
                "get"=>array("order"=>"Nombre_cocheria ASC","pagesize"=>-1),
            );

            $values["controls"]=array(
                "id_estadoCivil"=>getCombo($parameters_id_estadoCivil,$this),
                "id_TipoDocumento"=>getCombo($parameters_id_TipoDocumento,$this),
                "id_paisNacionalidad"=>getCombo($parameters_id_paisNacionalidad,$this),
                "id_Parcela_Actual"=>getCombo($parameters_id_Parcela_Actual,$this),
                "id_tipo_servicio"=>getCombo($parameters_id_tipo_servicio,$this),
                "id_cocheria"=>getCombo($parameters_id_cocheria,$this),
                "id_Parcela_ActualReadOnly"=>getCombo($parameters_id_Parcela_ActualReadOnly,$this),

            );
            $sqlPermisosAdicionalesModificarNombreFallecido = "exec obtenerPermisosAdicionales ?, ?;";
            $prmsPermisosAdicionalesModificarNombreFallecido=array(
                "userId" => $values["id_user_active"],
                "perm" => "modificar_nombre_fallecido"
            );                     
            $rc = $this->execAdHocWithParms($sqlPermisosAdicionalesModificarNombreFallecido, $prmsPermisosAdicionalesModificarNombreFallecido);
            if (!$rc) {
                // si dio false estoy en problemas.....hacer un throw o raise...
                $mierror = $this->db->error();
                throw new Exception($mierror['message'], $mierror['code']);

            } else {
                $permisosAdicionalesModificarNombreFallecido = $rc->result_array();
            }    
            $permite = $this->trueFalseFromSN($permisosAdicionalesModificarNombreFallecido[0]["permitido"]);

            if ($permite) {
                $values["permisosAdicionalesModificarNombreFallecido"] = "S";  
            } else {
                $values["permisosAdicionalesModificarNombreFallecido"] = "N";               
            }
            return parent::edit($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    } 

    // Guardar lo que se edita en el ABM (id <> 0) o lo nuevo (id = 0)
    public function save($values,$fields=null){
        try {
            $values["table"]="vw_inhumado";
            $values["view"]="vw_inhumado";
            if (!isset($values["id"])){$values["id"]=0;}
            $id=(int)$values["id"];
            if($id==0){
                // Insert
                if($fields==null) {
                    $storedp = "dbo.coop_Inhumado_Insert_Custom ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?";

                    $NumeroCertificado = null;
                    if (is_numeric($values["NumeroCertificado"])) {
                        $NumeroCertificado = $values["NumeroCertificado"];
                    } else {
                        if ($values["NumeroCertificado"] = " " || $values["NumeroCertificado"] = ""){
                            $NumeroCertificado = null; // null cuando es ""
                        } else {
                            $NumeroCertificado = null; // null cuando es alfanumerico
                        }
                    }
                    $NumeroRegistro = null;
                    if (is_numeric($values["NumeroRegistro"])) {
                        $NumeroRegistro = $values["NumeroRegistro"];
                    } else {
                        if ($values["NumeroRegistro"] = " " || $values["NumeroRegistro"] = "") {
                            $NumeroRegistro = null;    
                        } else {
                            $NumeroRegistro = null;
                        }
                    }
                    $p = array(
                        'id_inhumado'=>null,
                        'Nombre'=>$values["Nombre"],
                        'id_TipoDocumento' => secureEmptyNull($values,"id_TipoDocumento"),
                        'NumeroDocumento' => $values["NumeroDocumento"],
                        'FechaDeceso' => $values["FechaDeceso"],
                        'id_paisNacionalidad' => secureEmptyNull($values,"id_paisNacionalidad"),
                        'Profesion' => $values["Profesion"],
                        'UltimoDomicilio' => $values["UltimoDomicilio"],
                        'Edad' => $values["Edad"],
                        'id_estadoCivil' => secureEmptyNull($values,"id_estadoCivil"),
                        'CausaDeceso' => $values["CausaDeceso"],
                        'LugarDeceso' => $values["LugarDeceso"],
                        'CorteControl' => null,
                        'id_Parcela_Actual' => secureEmptyNull($values,"id_Parcela_Actual"),
                        'NumeroCertificado' => $values["NumeroCertificado"],
                        'NumeroRegistro' => $values["NumeroRegistro"],
                        'id_tipo_servicio' => secureEmptyNull($values,"id_tipo_servicio"),
                        'lata' => $values["lata"],
                        'NumeroInhumado' => $values["NumeroInhumado"],
                        'id_cocheria' => secureEmptyNull($values,"id_cocheria"),                           
                    );

                    $fields = array(
                        //'created' => $this->now,
                        //'verified' => $this->now,
                        //'offline' => null,
                        //'fum' => $this->now,     
                        
                        //'id' => $values["id"],
                        //'id_inhumado' => $values["id_inhumado"],
                        'Nombre' => $values["Nombre"],
                        'id_TipoDocumento' => secureEmptyNull($values,"id_TipoDocumento"),
                        'NumeroDocumento' => $values["NumeroDocumento"],
                        'FechaDeceso' => $values["FechaDeceso"],
                        'id_paisNacionalidad' => secureEmptyNull($values,"id_paisNacionalidad"),
                        'Profesion' => $values["Profesion"],
                        'UltimoDomicilio' => $values["UltimoDomicilio"],
                        'Edad' => $values["Edad"],
                        'id_estadoCivil' => secureEmptyNull($values,"id_estadoCivil"),
                        'CausaDeceso' => $values["CausaDeceso"],
                        'LugarDeceso' => $values["LugarDeceso"],
                        'CorteControl' => null,
                        'id_Parcela_Actual' => secureEmptyNull($values,"id_Parcela_Actual"),
                        'NumeroCertificado' => $values["NumeroCertificado"],
                        'NumeroRegistro' => $values["NumeroRegistro"],
                        'id_tipo_servicio' => secureEmptyNull($values,"id_tipo_servicio"),
                        'NumeroInhumado' => $values["NumeroInhumado"],  // poner un trigger para replicar el id de la tabla aqui
                        'lata' => $values["lata"],
                        'id_cocheria' => secureEmptyNull($values,"id_cocheria"),
                        //'no_innovar' => $values["no_innovar"], // Porque esto???                
                    );


                    $x= parent::saveExtended($values,$fields, $forcedTable=null, $forcedSp=$storedp, $param=$p, $keyname="id_inhumado");
                    if (isset($x["data"]["customData"])){
                        //$a = $x["data"]["customData"][0]["NumeroCliente"];
                        $a = $x["data"]["customData"]["NumeroInhumado"];
                        $x["customMensaje"]="Se ha grabado el Inhumado con el número ".$a;
                    }
                    
                    return $x;                    
                }
            } else {
                // Update
                if($fields==null) {
                    // CCOO
                    $storedp = "dbo.coop_Inhumado_Update ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?";
                    $NumeroCertificado = null;
                    if (is_numeric($values["NumeroCertificado"])) {
                        $NumeroCertificado = $values["NumeroCertificado"];
                    } else {
                        if ($values["NumeroCertificado"] = " " || $values["NumeroCertificado"] = ""){
                            $NumeroCertificado = null; // null cuando es ""
                        } else {
                            $NumeroCertificado = null; // null cuando es alfanumerico
                        }
                    }
                    $NumeroRegistro = null;
                    if (is_numeric($values["NumeroRegistro"])) {
                        $NumeroRegistro = $values["NumeroRegistro"];
                    } else {
                        if ($values["NumeroRegistro"] = " " || $values["NumeroRegistro"] = "") {
                            $NumeroRegistro = null;    
                        } else {
                            $NumeroRegistro = null;
                        }
                    }
                    $p = array(
                        'id_inhumado'=>$values["id"],
                        'Nombre'=>$values["Nombre"],
                        'id_TipoDocumento' => secureEmptyNull($values,"id_TipoDocumento"),
                        'NumeroDocumento' => $values["NumeroDocumento"],
                        'FechaDeceso' => $values["FechaDeceso"],
                        'id_paisNacionalidad' => secureEmptyNull($values,"id_paisNacionalidad"),
                        'Profesion' => $values["Profesion"],
                        'UltimoDomicilio' => $values["UltimoDomicilio"],
                        'Edad' => $values["Edad"],
                        'id_estadoCivil' => secureEmptyNull($values,"id_estadoCivil"),
                        'CausaDeceso' => $values["CausaDeceso"],
                        'LugarDeceso' => $values["LugarDeceso"],
                        'CorteControl' => null,
                        'id_Parcela_Actual' => secureEmptyNull($values,"id_Parcela_Actual"),
                        'NumeroCertificado' => $values["NumeroCertificado"],
                        'NumeroRegistro' => $values["NumeroRegistro"],
                        'id_tipo_servicio' => secureEmptyNull($values,"id_tipo_servicio"),
                        'lata' => $values["lata"],
                        'NumeroInhumado' => $values["NumeroInhumado"],
                        'id_cocheria' => secureEmptyNull($values,"id_cocheria"),                           
                    );

                    $fields = array(
                        //'offline' => null,
                        //'fum' => $this->now,                        

                        //'id' => $values["id"],
                        //'id_inhumado' => $values["id_inhumado"],
                        'Nombre' => $values["Nombre"],
                        'id_TipoDocumento' => secureEmptyNull($values,"id_TipoDocumento"),
                        'NumeroDocumento' => $values["NumeroDocumento"],
                        'FechaDeceso' => $values["FechaDeceso"],
                        'id_paisNacionalidad' => secureEmptyNull($values,"id_paisNacionalidad"),
                        'Profesion' => $values["Profesion"],
                        'UltimoDomicilio' => $values["UltimoDomicilio"],
                        'Edad' => $values["Edad"],
                        'id_estadoCivil' => secureEmptyNull($values,"id_estadoCivil"),
                        'CausaDeceso' => $values["CausaDeceso"],
                        'LugarDeceso' => $values["LugarDeceso"],
                        'CorteControl' => null,
                        'id_Parcela_Actual' => secureEmptyNull($values,"id_Parcela_Actual"),
                        'NumeroCertificado' => $values["NumeroCertificado"],
                        'NumeroRegistro' => $values["NumeroRegistro"],
                        'id_tipo_servicio' => secureEmptyNull($values,"id_tipo_servicio"),
                        'NumeroInhumado' => $values["NumeroInhumado"],
                        'lata' => $values["lata"],
                        'id_cocheria' => secureEmptyNull($values,"id_cocheria"),
                        //'no_innovar' => $values["no_innovar"],     // ??????? por que esto?           

                    );

                }
                return parent::saveExtended($values,$fields, $forcedTable=null, $forcedSp=$storedp, $param=$p, $keyname="id_inhumado");
            }
            //return parent::save($values,$fields);
            //return parent::saveExtended($values,$fields, $forcedTable=null, $forcedSp=$storedp, $param=$p, $keyname="id_inhumado");
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }


    
    public function excel($values){
        try {
            if ($values["where"]!=""){$values["where"]=base64_decode($values["where"]);}
            $values["view"]="CON_Cuentas";
            $values["delimiter"]=";";
            $values["pagesize"]=-1;
            $values["order"]=" NUMERO ASC";
            $values["records"]=$this->get($values);

            $values["columns"]=array(
               array("field"=>"ID","format"=>"code"),
               array("field"=>"NUMERO","format"=>"text"),
               array("field"=>"NOMBRE","format"=>"text"),

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
            $values["view"]="CON_Cuentas";
            $values["pagesize"]=-1;
            $values["order"]=" NUMERO ASC";
            $values["records"]=$this->get($values);
            
            $values["title"]="Cuentas: Altas, Bajas, Consultas y Modificaciones";
            $values["columns"]=array(
                //array("field"=>"ID","format"=>"code"),
                array("field"=>"NUMERO","format"=>"text"),
                array("field"=>"NOMBRE","format"=>"text"),
            );
            return parent::pdf($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    
    public function historicoInhumado_CEM($values) {
        try {
            
            $sqlHistorico = "SELECT * FROM dbo.vw_MovimientoInhumado WHERE id_inhumado=? ORDER BY FechaMovimiento";
            $prms=array(
                "id_inhumado1" => $values["id_inhumado"],
            );            
            $rc = $this->execAdHocWithParms($sqlHistorico, $prms);

            if (!$rc) {
                // si dio false estoy en problemas.....hacer un throw o raise...
            } else {
                $historico = $rc->result_array();
            }

            $sqlPermisosAdicionalesBorrarMovimientoInhumado = "exec obtenerPermisosAdicionales ?, ?;";
            $prmsPermisosAdicionalesBorrarMovimientoInhumado=array(
                "userId" => $values["id_user_active"],
                "perm" => "borrar_movimiento_inhumado"
            );                     
            $rc = $this->execAdHocWithParms($sqlPermisosAdicionalesBorrarMovimientoInhumado, $prmsPermisosAdicionalesBorrarMovimientoInhumado);
            if (!$rc) {
                // si dio false estoy en problemas.....hacer un throw o raise...
                $mierror = $this->db->error();
                throw new Exception($mierror['message'], $mierror['code']);

            } else {
                $permisosAdicionalesBorrarMovimientoInhumado = $rc->result_array();
            }    
            $permite = $this->trueFalseFromSN($permisosAdicionalesBorrarMovimientoInhumado[0]["permitido"]);

            if ($permite) {
                //$data["parameters"]["permiteGenerar"] = "S";  
                $values["permisosAdicionalesBorrarMovimientoInhumado"] = "S";  
            } else {
                //$data["parameters"]["permiteGenerar"] = "N";     
                $values["permisosAdicionalesBorrarMovimientoInhumado"] = "N";               
            }


            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>"Records",
                //"historico"=>json_encode($historico),
                "historico"=>$historico,
                "permisosAdicionalesBorrarMovimientoInhumado" => $values["permisosAdicionalesBorrarMovimientoInhumado"],
                //"emails"=>$emails,
                "table"=>$this->table,
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT)
            );
        }
        catch(Exception $e) {
            return logError($e,__METHOD__ );
        }                   
    }
    public function BorrarMI_CEM($values){
        try {
	        
            $sqlHistorico = "spDelMI ?";
            $prms=array(
                "NumeroMovimiento" => $values["NumeroMovimiento"],
            );            
            $rc = $this->execAdHocWithParms($sqlHistorico, $prms);

            if (!$rc) {
                // si dio false estoy en problemas.....hacer un throw o raise...
            } else {
                $historico = $rc->result_array();
            }

            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>"Records",
                //"historico"=>json_encode($historico),
                "borrado"=>$historico,
                //"emails"=>$emails,
                "table"=>$this->table,
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT)
            );
        }
        catch(Exception $e) {
            return logError($e,__METHOD__ );
        }                   

    }
    public function setMarcaNoInnovar($values){
        try {
	        
            $sqlNoInnovar = "dbo.spSetearMarcaNoInnovarInhumados ?, ?";
            $prms=array(
                "id_inhumado" => $values["id_inhumado"],
                "no_innovar" => $values["no_innovar"],
            );            
            $rc = $this->execAdHocWithParms($sqlNoInnovar, $prms);
            if (!$rc) {
                // si dio false estoy en problemas.....hacer un throw o raise...
            } else {
                $no_innovar = $rc->result_array();
            }

            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>"Records",
                "no_innovar"=>$no_innovar,
                "table"=>$this->table,
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT)
            );
        }
        catch(Exception $e) {
            return logError($e,__METHOD__ );
        }                   

    }


}