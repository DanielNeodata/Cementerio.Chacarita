<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Pagador extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }
    public function brow($values){
        try {
            $values["view"]="vw_pagador";
            $values["order"]="NumeroPagador ASC";
            $values["records"]=$this->get($values);

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

                array("field"=>"Nombre","format"=>"text"),
                array("field"=>"NumeroPagador","format"=>"text"),
                array("field"=>"domicilio","format"=>"text"),
                array("field"=>"TipoDocumento","format"=>"text"),
                array("field"=>"NumeroDocumento","format"=>"text"),

            );

            $values["controls"]=array(
                "<label>".lang('p_Nombre')."/Email</label><input type='text' id='browser_Nombre' name='browser_Nombre' class='form-control text'/>",
                "<label>".lang('p_NumeroPagador')."</label><input type='text' id='browser_NumeroPagador' name='browser_NumeroPagador' class='form-control text'/>",
                "<label>".lang('p_NumeroDocumento')."</label><input type='text' id='browser_NumeroDocumento' name='browser_NumeroDocumento' class='form-control text'/>",
            );

            $values["filters"]=array(
                array("name"=>"browser_Nombre", "operator"=>"like","fields"=>array("Nombre","DomicilioEntreCalles")),
                array("name"=>"browser_NumeroPagador", "operator"=>"like","fields"=>array("NumeroPagador")),
                array("name"=>"browser_NumeroDocumento", "operator"=>"like","fields"=>array("NumeroDocumento")),
                array("name"=>"browser_search", "operator"=>"like","fields"=>array("Nombre","NumeroPagador", "NumeroDocumento")), 
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
            
            $values["table"]="vw_pagador";
            $values["view"]="vw_pagador";

            $values["where"]=("id_pagador=".$values["id"]);

            $values["records"]=$this->get($values);

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
                "name"=>"id_paisNacionalidad",  // aca va eñ nombre del campo de la tabla principal
                "class"=>"form-control dbase",
                "empty"=>true,
                "id_actual"=>secureComboPosition($values["records"],"id_paisNacionalidad"), // --> id en la tabla principal
                "id_field"=>"id",  // --> esta es la PK de la tabla del combo y es la que usa para identificar e seleccionado.
                "description_field"=>"descripcion", // --> descripcion de la tabla del combo
                "get"=>array("order"=>"descripcion ASC","pagesize"=>-1),
            );
            // Provincia: id_Provincia y NOMBRE
            $parameters_Domicilio_id_provincia=array(
                "model"=>(MOD_BRITANICO."/Provincia"),
                "table"=>"Provincia",
                "name"=>"Domicilio_id_provincia",  // --> id en la tabla principal
                "class"=>"form-control dbase",
                "empty"=>true,
                "id_actual"=>secureComboPosition($values["records"],"Domicilio_id_provincia"), // --> id en la tabla principal
                "id_field"=>"id_Provincia", // --> esta es la PK de la tabla del combo y es la que usa para identificar e seleccionado.
                "description_field"=>"NOMBRE", // --> descripcion de la tabla del combo
                "get"=>array("order"=>"NOMBRE ASC","pagesize"=>-1),
            );

            $values["controls"]=array(
                "id_TipoDocumento"=>getCombo($parameters_id_TipoDocumento,$this),
                "id_paisNacionalidad"=>getCombo($parameters_id_paisNacionalidad,$this),
                "Domicilio_id_provincia"=>getCombo($parameters_Domicilio_id_provincia,$this),
            );

            $registro = $values["records"]["data"][0];

            // Cliente Relacionado
            //$sqlClienteRelacionado = <<<EOD
            //select * from vw_Rel_Cliente_Pagador_Parcela where id_pagador = ?;
            //EOD; // ? -> '%apellido o numero%'     id -> id_cliente|id_pagador & detalle

            $sqlClienteRelacionado = "select distinct id_cliente,id_pagador,cliente from vw_Rel_Cliente_Pagador_Parcela where id_pagador = ?;";

            $prms=array(
                "id_pagador" => $registro["id_pagador"],  // pagador
            );            
            $rc = $this->execAdHocWithParms($sqlClienteRelacionado, $prms);

            if (!$rc) {
                // si dio false estoy en problemas.....hacer un throw o raise...
                $mierror = $this->db->error();
                throw new Exception($mierror['message'], $mierror['code']);

            } else {
                $clienteRelacionado = $rc->result_array();
                $values["clienteRelacionado"]=$clienteRelacionado;
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
            $values["table"]="vw_pagador";
            $values["view"]="vw_pagador";
            $storedp = "";
            $p = array();
            if (!isset($values["id"])){$values["id"]=0;}
            $id=(int)$values["id"];
            if($id==0){
                // Insert
                if($fields==null) {
                    $storedp = "dbo.coop_Pagador_Insert ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?";
                    $NumeroDocumento = $values["NumeroDocumento"];
                    $NumeroDocumento = str_replace(".", "", $NumeroDocumento); // por si mandaron puntos en el documento.
                    if ($NumeroDocumento=="") {$NumeroDocumento = null;}
                    $p = array(
                        'id_pagador'=>$values["id"],
                        'NumeroPagador'=>$values["NumeroPagador"],
                        'Nombre'=>clean($values["Nombre"]),
                        'DomicilioCalle'=>$values["DomicilioCalle"],
                        'DomicilioNumero'=>$values["DomicilioNumero"],
                        'DomicilioPiso'=>$values["DomicilioPiso"],
                        'DomicilioDepartamento'=>$values["DomicilioDepartamento"],
                        'DomicilioEntreCalles'=>$values["DomicilioEntreCalles"],
                        'Domicilio_id_provincia' => secureEmptyNull($values,"Domicilio_id_provincia"),
                        'Localidad'=>$values["Localidad"],
                        'CodigoPostal'=>$values["CodigoPostal"],
                        'Telefono1'=>$values["Telefono1"],
                        'Telefono2'=>$values["Telefono2"],
                        'id_TipoDocumento' => secureEmptyNull($values,"id_TipoDocumento"),
                        'NumeroDocumento' => $NumeroDocumento,
                        'id_paisNacionalidad' => secureEmptyNull($values,"id_paisNacionalidad"),
                        'InstanciaIntimatoria'=>$values["InstanciaIntimatoria"],
                    );
                    $fields = array(
                        //'offline' => null,
                        //'fum' => $this->now,                        
                        'NumeroPagador'=>$values["NumeroPagador"],
                        'Nombre'=>clean($values["Nombre"]),
                        'DomicilioCalle'=>$values["DomicilioCalle"],
                        'DomicilioNumero'=>$values["DomicilioNumero"],
                        'DomicilioPiso'=>$values["DomicilioPiso"],
                        'DomicilioDepartamento'=>$values["DomicilioDepartamento"],
                        'DomicilioEntreCalles'=>$values["DomicilioEntreCalles"],                        
                        'Domicilio_id_provincia' => secureEmptyNull($values,"Domicilio_id_provincia"),
                        'Localidad'=>$values["Localidad"],
                        'CodigoPostal'=>$values["CodigoPostal"],
                        'Telefono1'=>$values["Telefono1"],
                        'Telefono2'=>$values["Telefono2"],
                        'id_TipoDocumento' => secureEmptyNull($values,"id_TipoDocumento"),
                        'NumeroDocumento' => $values["NumeroDocumento"],
                        'id_paisNacionalidad' => secureEmptyNull($values,"id_paisNacionalidad"),
                        'InstanciaIntimatoria'=>$values["InstanciaIntimatoria"],                        
                    );
                }
                $x= parent::saveExtended($values,$fields, $forcedTable=null, $forcedSp=$storedp, $param=$p, $keyname="id_pagador");

                $values["view"] = "Pagador";
                //$values["where"] = ("id_pagador=" . $x["id"]);
                $values["where"] = ("id_pagador=(select max(id_pagador) from pagador )");//.$x["id"]);
                $values["records"] = $this->get($values);
                $x["customMensaje"] = "Se ha grabado el Pagador con el número " . $values["records"]["data"][0]["NumeroPagador"];
                return $x;
            } else {
                // Update
                if($fields==null) {
                    // CCOO
                    $storedp = "dbo.coop_Pagador_Update ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?";
                    $NumeroDocumento = $values["NumeroDocumento"];
                    $NumeroDocumento = str_replace(".", "", $NumeroDocumento); // por si mandaron puntos en el documento.
                    if ($NumeroDocumento=="") {$NumeroDocumento = null;}
                    $p = array(
                        'id_pagador'=>$values["id"],
                        'NumeroPagador' => $values["NumeroPagador"],
                        'Nombre'=>clean($values["Nombre"]),
                        'DomicilioCalle'=>$values["DomicilioCalle"],
                        'DomicilioNumero'=>$values["DomicilioNumero"],
                        'DomicilioPiso'=>$values["DomicilioPiso"],
                        'DomicilioDepartamento'=>$values["DomicilioDepartamento"],
                        'DomicilioEntreCalles'=>$values["DomicilioEntreCalles"],                        
                        'Domicilio_id_provincia' => secureEmptyNull($values,"Domicilio_id_provincia"),
                        'Localidad'=>$values["Localidad"],
                        'CodigoPostal'=>$values["CodigoPostal"],
                        'Telefono1'=>$values["Telefono1"],
                        'Telefono2'=>$values["Telefono2"],
                        'id_TipoDocumento' => secureEmptyNull($values,"id_TipoDocumento"),
                        'NumeroDocumento' => $NumeroDocumento,
                        'id_paisNacionalidad' => secureEmptyNull($values,"id_paisNacionalidad"),
                        'InstanciaIntimatoria'=>$values["InstanciaIntimatoria"],
                    );

                    $fields = array(
                        //'offline' => null,
                        //'fum' => $this->now,                        
                        'Nombre'=>clean($values["Nombre"]),
                        'NumeroPagador' => $values["NumeroPagador"],
                        'DomicilioCalle'=>$values["DomicilioCalle"],
                        'DomicilioNumero'=>$values["DomicilioNumero"],
                        'DomicilioPiso'=>$values["DomicilioPiso"],
                        'DomicilioDepartamento'=>$values["DomicilioDepartamento"],
                        'DomicilioEntreCalles'=>$values["DomicilioEntreCalles"],                        
                        'Domicilio_id_provincia' => secureEmptyNull($values,"Domicilio_id_provincia"),
                        'Localidad'=>$values["Localidad"],
                        'CodigoPostal'=>$values["CodigoPostal"],
                        'Telefono1'=>$values["Telefono1"],
                        'Telefono2'=>$values["Telefono2"],
                        'id_TipoDocumento' => secureEmptyNull($values,"id_TipoDocumento"),
                        'NumeroDocumento' => $values["NumeroDocumento"],
                        'id_paisNacionalidad' => secureEmptyNull($values,"id_paisNacionalidad"),
                        'InstanciaIntimatoria'=>$values["InstanciaIntimatoria"],                        
                    );
                }

                $x= parent::saveExtended($values,$fields, $forcedTable=null, $forcedSp=$storedp, $param=$p, $keyname="id_pagador");
                return $x;
            }
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }

public function borrar_cliente_pagador($values) {
        try {
	        
            $sqlBorrarClientePagador = "delete from dbo.Rel_Cliente_Pagador where id_pagador = ? and id_cliente = ?;";
            $prms=array(
                "id_pagador" => $values["id_pagador"],
                "id_cliente" => $values["id_cliente"],
            );            
            $rc = $this->execAdHocWithParms($sqlBorrarClientePagador, $prms);

            if (!$rc) {
                // si dio false estoy en problemas.....hacer un throw o raise...
                $mierror = $this->db->error();
                throw new Exception($mierror['message'], $mierror['code']);
            } else {
                //$cliente_pagador = $rc->result_array();
            }
           return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>"Records",
                //"cliente_pagador"=>$cliente_pagador,
                "table"=>$this->table,
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT)
            );
        }
        catch(Exception $e) {
            return logError($e,__METHOD__ );
        }            
    
    }
    public function relacionar_cliente_pagador($values) {
        try {
	        
            $sqlAsociarClientePagador = "dbo.coop_Rel_Cliente_Pagador_Insert ?, ?;";
            $prms=array(
                "id_cliente" => $values["id_cliente"],
                "id_pagador" => $values["id_pagador"],            
            );            
            $rc = $this->execAdHocWithParms($sqlAsociarClientePagador, $prms);

            if (!$rc) {
                // si dio false estoy en problemas.....hacer un throw o raise...
                $mierror = $this->db->error();
                throw new Exception($mierror['message'], $mierror['code']);
            } else {
                $cliente_pagador = $rc->result_array();
            }
           return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>"Records",
                "cliente_pagador"=>$cliente_pagador,
                "table"=>$this->table,
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT)
            );
        }
        catch(Exception $e) {
            return logError($e,__METHOD__ );
        }            
    }

    public function buscar_cliente_pagador($values) {
        try {
	        
            $sqlClientePagador = "SELECT TOP 50   id_cliente as id, " . 
            "                cliente + ', ' + domicilio + ', ' + pagador   as cliente ,  " .
            "                cliente + ', ' + domicilio + ', ' + pagador  as detalle " .
            "FROM dbo.vw_Cliente_Simple_Pagador  " .
            "WHERE numerocliente LIKE ? OR cliente Like ? OR pagador Like ?  " .
            "ORDER BY cliente; ";
            $prms=array(
                //"id_pagador"=> $values["id"], 
                "numCli" => $values["searchKey"].'%',
                "cli" => '%'.$values["searchKey"].'%',
                "pag" => $values["searchKey"].'%',                                
            );            
            $rc = $this->execAdHocWithParms($sqlClientePagador, $prms);
            if (!$rc) {
                // si dio false estoy en problemas.....hacer un throw o raise...
                $mierror = $this->db->error();
                throw new Exception($mierror['message'], $mierror['code']);
            } else {
                $cliente_pagador = $rc->result_array();
            }
           return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>"Records",
                "cliente_pagador"=>$cliente_pagador,
                "table"=>$this->table,
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT)
            );
        }
        catch(Exception $e) {
            return logError($e,__METHOD__ );
        }            
    }
    public function transferirPagador($values) {
        try {
	        
            $sqlTransferirPagador = "dbo.spTransferirPagador ?; ";
            $prms=array("id_pagador"=> $values["id_pagador"]);            
            $rc = $this->execAdHocWithParms($sqlTransferirPagador, $prms);

            if (!$rc) {
                // si dio false estoy en problemas.....hacer un throw o raise...
                $mierror = $this->db->error();
                throw new Exception($mierror['message'], $mierror['code']);
            } else {
                $transferenciaPagador = $rc->result_array();
            }
           return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>"Records",
                "transferenciaPagador"=>$transferenciaPagador,
                "table"=>$this->table,
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT)
            );
        }
        catch(Exception $e) {
            return logError($e,__METHOD__ );
        }            
    }
    public function historicoPagador($values) {
        try {
	        
            $sqlHistoricoPagador = "SELECT id_pagador_historico, id_pagador_parent, Nombre, NumeroPagador, NumeroDocumento, Telefono1, Telefono2, InstanciaIntimatoria, fecha_historico FROM dbo.pagador_historico WHERE id_pagador_parent= ? ORDER BY fecha_historico DESC; ";
            $prms=array("id_pagador" => $values["id_pagador"]);            
            $rc = $this->execAdHocWithParms($sqlHistoricoPagador, $prms);
            if (!$rc) {
                // si dio false estoy en problemas.....hacer un throw o raise...
                $mierror = $this->db->error();
                throw new Exception($mierror['message'], $mierror['code']);
            } else {
                $historicoPagador = $rc->result_array();
            }
           return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>"Records",
                "historicoPagador"=>$historicoPagador,
                "table"=>$this->table,
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT)
            );
        }
        catch(Exception $e) {
            return logError($e,__METHOD__ );
        }            
    }    
}
