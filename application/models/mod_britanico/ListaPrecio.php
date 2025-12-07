<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class ListaPrecio extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }
    public function brow($values){
        try {

            // Botones de busqueda y exportacion
            $values["getters"]=array(
                "search"=>true,
                //"googlesearch"=>true,
                "excel"=>true,
                "pdf"=>true,
            );


            $sqlPermisosAdicionales = "exec obtenerPermisosAdicionales ?, ?;";
            $prmsPermisosAdicionales=array(
                "userId" => $values["id_user_active"],
                "perm" => "abm_listaprecio"
            );                     
            $rc = $this->execAdHocWithParms($sqlPermisosAdicionales, $prmsPermisosAdicionales);
            if (!$rc) {
                // si dio false estoy en problemas.....hacer un throw o raise...
                $mierror = $this->db->error();
                throw new Exception($mierror['message'], $mierror['code']);
            } else {
                $permisosAdicionales = $rc->result_array();
            }  
            // Botones de ABM
            $values["buttons"]=array(
                "new"=>$this->trueFalseFromSN($permisosAdicionales[0]["permitido"]),
                "edit"=>$this->trueFalseFromSN($permisosAdicionales[0]["permitido"]),
                "delete"=>false,
                "offline"=>false,
            );
            if (!($this->trueFalseFromSN($permisosAdicionales[0]["permitido"]))){$values["readonly"]=true;}
            // Columnas para el brow.
            $values["columns"]=array(
                array("field"=>"Codigo","format"=>"text"),
                array("field"=>"Nombre","format"=>"text"),
                array("field"=>"Precio","format"=>"text"),
                array("field"=>"Meses","format"=>"text"),
                array("field"=>"nro_cuenta_contable","format"=>"text"),                
                array("field"=>"cuenta_contable","format"=>"text"),                
                array("field"=>"Operacion","format"=>"text"),                
                //array("field"=>"id","format"=>"code"),
            );

            // Controles para los filtros
            $values["controls"]=array(
                "<label>".lang('p_Codigo')."</label><input type='text' id='browser_Codigo' name='browser_Codigo' class='form-control text'/>",
                "<label>".lang('p_Nombre')."</label><input type='text' id='browser_Nombre' name='browser_nombre' class='form-control text'/>",
                "<label>".lang('p_nro_cuenta_contable')."</label><input type='text' id='browser_nro_cuenta_contable' name='browser_nro_cuenta_contable' class='form-control text'/>",
                "<label>".lang('p_cuenta_contable')."</label><input type='text' id='browser_cuenta_contable' name='browser_cuenta_contable' class='form-control text'/>",                
            );

            // Filtros y search. Confirmar si funciona como AND o como OR
            $values["filters"]=array(
                array("name"=>"browser_Codigo", "operator"=>"like","fields"=>array("Codigo")), // Filtro
                array("name"=>"browser_Nombre", "operator"=>"like","fields"=>array("Nombre")), // Filtro
                array("name"=>"browser_nro_cuenta_contable", "operator"=>"like","fields"=>array("nro_cuenta_contable")), // Filtro
                array("name"=>"browser_cuenta_contable", "operator"=>"like","fields"=>array("cuenta_contable")), // Filtro     
                array("name"=>"browser_search", "operator"=>"like","fields"=>array("Codigo", "Nombre", "cuenta_contable")), // Search                
            );
            $values["view"]="vw_ListaPrecio";
            $values["order"]="Codigo asc";
            $values["records"]=$this->get($values);
             return parent::brow($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
   
    public function excel($values){
        try {
            if ($values["where"]!=""){$values["where"]=base64_decode($values["where"]);}
            $values["delimiter"]=";";
            $values["pagesize"]=-1;
            $values["records"]=$this->get($values);
            $values["columns"]=array(
                array("field"=>"ID","format"=>"code"),
                array("field"=>"Codigo","format"=>"text"),
                array("field"=>"Nombre","format"=>"text"),
                array("field"=>"Precio","format"=>"text"),
                array("field"=>"Meses","format"=>"text"),
                array("field"=>"nro_cuenta_contable","format"=>"text"),                
                array("field"=>"cuenta_contable","format"=>"text"),                
                array("field"=>"Operacion","format"=>"text"),    
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
            $values["pagesize"]=-1;
            $values["order"]="1 ASC";
            $values["records"]=$this->get($values);
            $values["title"]="Lista de precios";
            $values["columns"]=array(
                array("field"=>"ID","format"=>"code"),
                array("field"=>"Codigo","format"=>"text"),
                array("field"=>"Nombre","format"=>"text"),
                array("field"=>"Precio","format"=>"text"),
                array("field"=>"Meses","format"=>"text"),
                array("field"=>"nro_cuenta_contable","format"=>"text"),                
                array("field"=>"cuenta_contable","format"=>"text"),                
                array("field"=>"Operacion","format"=>"text"),    
            );
            return parent::pdf($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
   // ABM -> Edit
   public function edit($values){
        try {
            $location=explode("::",strtolower(__METHOD__));
            $values["interface"]=(MOD_BRITANICO."/".$location[0]."/abm");
            $values["page"]=1;
            $values["table"]="vw_ListaPrecio";
            $values["view"]="vw_ListaPrecio";
            $values["where"]=("id=".$values["id"]);
            $values["records"]=$this->get($values);
            $parameters_ID_CuentaContable=array(
                "model"=>(MOD_BRITANICO."/Con_cuentas"),
                "table"=>"CON_Cuentas",
                "name"=>"ID_CuentaContable",  // aca va eñ nombre del campo de la tabla principal, inhumados por ej. Sirva como contenedor del dato luego de la seleccion.
                "class"=>"form-control dbase",
                "empty"=>true,
                "id_actual"=>secureComboPosition($values["records"],"ID_CuentaContable"), // --> id de la tabla inhumados
                "id_field"=>"ID", // --> esta es la PK de la tabla del combo y es la que usa para identificar e seleccionado.
                "description_field"=>"NOMBRE",  // --> descripcion de la tabla del combo
                "get"=>array("where"=>"isnull(NOMBRE ,'')<>'' and isnull(NUMERO ,'')<>''", "order"=>"NOMBRE  ASC","pagesize"=>-1),
            );
            $parameters_id_TipoParcela=array(
                "model"=>(MOD_BRITANICO."/TipoParcela"),
                "table"=>"TipoParcela",
                "name"=>"id_TipoParcela",  // aca va eñ nombre del campo de la tabla principal, inhumados por ej. Sirva como contenedor del dato luego de la seleccion.
                "class"=>"form-control dbase",
                "empty"=>true,
                "id_actual"=>secureComboPosition($values["records"],"id_TipoParcela"), // --> id de la tabla inhumados
                "id_field"=>"id_TipoParcela", // --> esta es la PK de la tabla del combo y es la que usa para identificar e seleccionado.
                "description_field"=>"Nombre",  // --> descripcion de la tabla del combo
                "get"=>array("order"=>"Nombre  ASC","pagesize"=>-1),
            );
            $parameters_id_TamanioParcela=array(
                "model"=>(MOD_BRITANICO."/TamanioParcela"),
                "table"=>"TamanioParcela",
                "name"=>"id_TamanioParcela",  // aca va eñ nombre del campo de la tabla principal, inhumados por ej. Sirva como contenedor del dato luego de la seleccion.
                "class"=>"form-control dbase",
                "empty"=>true,
                "id_actual"=>secureComboPosition($values["records"],"id_TamanioParcela"), // --> id de la tabla inhumados
                "id_field"=>"id_TamanioParcela", // --> esta es la PK de la tabla del combo y es la que usa para identificar e seleccionado.
                "description_field"=>"Nombre",  // --> descripcion de la tabla del combo
                "get"=>array("order"=>"Nombre  ASC","pagesize"=>-1),
            );
            $parameters_ID_Operacion=array(
                "model"=>(MOD_BRITANICO."/SAC_Operaciones"),
                "table"=>"SAC_Operaciones",
                "name"=>"ID_Operacion",  // aca va el nombre del campo de la tabla principal. Sirva como contenedor del dato luego de la seleccion.
                "class"=>"form-control dbase",
                "empty"=>true,
                "id_actual"=>secureComboPosition($values["records"],"ID_Operacion"), // --> id de la tabla principal
                "id_field"=>"ID_Operacion", // --> esta es la PK de la tabla del combo.
                "description_field"=>"DENOMINACION",  // --> descripcion de la tabla del combo
                "get"=>array("order"=>"DENOMINACION  ASC","pagesize"=>-1),
            );

            $values["controls"]=array(
                "ID_CuentaContable"=>getCombo($parameters_ID_CuentaContable,$this),
                "id_TipoParcela"=>getCombo($parameters_id_TipoParcela,$this),
                "id_TamanioParcela"=>getCombo($parameters_id_TamanioParcela,$this),
                "ID_Operacion"=>getCombo($parameters_ID_Operacion,$this),
            );

            $sqlPermisosAdicionales = "exec obtenerPermisosAdicionales ?, ?;";
            $prmsPermisosAdicionales=array(
                "userId" => $values["id_user_active"],
                "perm" => "abm_listaprecio"
            );                     
            $rc = $this->execAdHocWithParms($sqlPermisosAdicionales, $prmsPermisosAdicionales);
            if (!$rc) {
                // si dio false estoy en problemas.....hacer un throw o raise...
                $mierror = $this->db->error();
                throw new Exception($mierror['message'], $mierror['code']);

            } else {
                $permisosAdicionales = $rc->result_array();
            }  

            if (!$this->trueFalseFromSN($permisosAdicionales[0]["permitido"]))
            {
                $values["readonly"]=true;
                $values["accept-class-name"]="btn-abm-accept-confirm";
            }

            return parent::edit($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    } 

    // Guardar lo que se edita en el ABM (id <> 0) o lo nuevo (id = 0)
    //public function save($values,$fields=null) {
    public function save($values,$fields=null) {
        try {
            $values["table"]="vw_ListaPrecio";
            $values["view"]="vw_ListaPrecio";
            if (!isset($values["id"])){$values["id"]=0;}
            $id=(int)$values["id"];
            $storedp = "";
            // Insert
            if($id==0){
                if($fields==null) {
                    $storedp = "dbo.coop_ListaPrecio_Insert ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?";
                        $p = array(
                            null,  // esta es la PK, pero estoy yendo por insert, asi que tiene sentido                             
                            'Codigo' => $values["Codigo"],
                            'Nombre' => $values["Nombre"],
                            'Precio' => $values["Precio"],
                            'ID_CuentaContable' => secureEmptyNull($values,"ID_CuentaContable"),
                            'ID_Operacion' => secureEmptyNull($values,"ID_Operacion"),
                            'Clase' => $values["Clase"],
                            'Meses' => $values["Meses"],
                            'id_TipoParcela' => secureEmptyNull($values,"id_TipoParcela"),
                            'id_TamanioParcela' => secureEmptyNull($values,"id_TamanioParcela"),
                            null, // tipo servicio
                            'activo' => 'A',
                            'orden' => $values["orden"],             
                        );
                    $fields = array(
                        'Codigo' => $values["Codigo"],
                        'Nombre' => $values["Nombre"],
                        'Precio' => $values["Precio"],
                        'Meses' => $values["Meses"],
                        'Clase' => $values["Clase"],
                        'activo' => 'A',
                        'ID_CuentaContable' => secureEmptyNull($values,"ID_CuentaContable"),
                        'id_TipoParcela' => secureEmptyNull($values,"id_TipoParcela"),
                        'id_TamanioParcela' => secureEmptyNull($values,"id_TamanioParcela"),
                        'ID_Operacion' => secureEmptyNull($values,"ID_Operacion"),
                        'orden' => $values["orden"],             
                    );
                }
            } else {
            // Update
                if($fields==null) {
                    $storedp = "dbo.coop_ListaPrecio_Update ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?";
                    $p = array(
                        'id_ConceptoListaPrecio' => $id,
                        'Codigo' => $values["Codigo"],                         
                        'Nombre' => $values["Nombre"],
                        'Precio' => $values["Precio"],
                        'ID_CuentaContable' => secureEmptyNull($values,"ID_CuentaContable"),
                        'ID_Operacion' => secureEmptyNull($values,"ID_Operacion"),
                        'Clase' => $values["Clase"],
                        'Meses' => $values["Meses"],
                        'id_TipoParcela' => secureEmptyNull($values,"id_TipoParcela"),
                        'id_TamanioParcela' => secureEmptyNull($values,"id_TamanioParcela"),
                        null, // tipo servicio
                        'activo' => 'A',
                        'orden' => $values["orden"],             
                    );

                    $fields = array(
                        'Codigo' => $values["Codigo"],
                        'Nombre' => $values["Nombre"],
                        'Precio' => $values["Precio"],
                        'Meses' => $values["Meses"],
                        'Clase' => $values["Clase"],
                        'activo' => 'A',
                        'ID_CuentaContable' => secureEmptyNull($values,"ID_CuentaContable"),
                        'id_TipoParcela' => secureEmptyNull($values,"id_TipoParcela"),
                        'id_TamanioParcela' => secureEmptyNull($values,"id_TamanioParcela"),
                        'ID_Operacion' => secureEmptyNull($values,"ID_Operacion"),
                        'orden' => $values["orden"],
                    );
                }
            }
            return parent::saveExtended($values,$fields, $forcedTable=null, $forcedSp=$storedp, $prm=$p, $keyName="id_ConceptoListaPrecio");
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }

}
