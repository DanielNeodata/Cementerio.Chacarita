<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Parcela extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }
    public function brow($values){
        // este metodo no usa el metodo de la vista de brow. Va a la vista  que esta en /mod_britanico/parcela/parcelas.php
        try {

            $sqlPermisosAdicionalesSetearParcelaHistorica = "exec obtenerPermisosAdicionales ?, ?;";
            $prmsPermisosAdicionalesSetearParcelaHistorica=array(
                "userId" => $values["id_user_active"],
                "perm" => "setear_parcela_historica"
            );                     
            $rc = $this->execAdHocWithParms($sqlPermisosAdicionalesSetearParcelaHistorica, $prmsPermisosAdicionalesSetearParcelaHistorica);
            if (!$rc) {
                // si dio false estoy en problemas.....hacer un throw o raise...
                $mierror = $this->db->error();
                throw new Exception($mierror['message'], $mierror['code']);

            } else {
                $permisosAdicionalesSetearParcelaHistorica = $rc->result_array();
            }  
            $sqlPermisosAdicionalesAgregarParcela = "exec obtenerPermisosAdicionales ?, ?;";
            $prmsPermisosAdicionalesAgregarParcela=array(
                "userId" => $values["id_user_active"],
                "perm" => "agregado_parcela"
            );                     
            $rc = $this->execAdHocWithParms($sqlPermisosAdicionalesAgregarParcela, $prmsPermisosAdicionalesAgregarParcela);
            if (!$rc) {
                // si dio false estoy en problemas.....hacer un throw o raise...
                $mierror = $this->db->error();
                throw new Exception($mierror['message'], $mierror['code']);

            } else {
                $permisosAdicionalesAgregarParcela = $rc->result_array();
            }  

            $sqlPermisosAdicionalesDeleteClienteParcela = "exec obtenerPermisosAdicionales ?, ?;";
            $prmsPermisosAdicionalesDeleteClienteParcela=array(
                "userId" => $values["id_user_active"],
                "perm" => "delete_cliente_parcela"
            );                     
            $rc = $this->execAdHocWithParms($sqlPermisosAdicionalesDeleteClienteParcela, $prmsPermisosAdicionalesDeleteClienteParcela);
            if (!$rc) {
                // si dio false estoy en problemas.....hacer un throw o raise...
                $mierror = $this->db->error();
                throw new Exception($mierror['message'], $mierror['code']);

            } else {
                $permisosAdicionalesDeleteClienteParcela = $rc->result_array();
            }             
            
            $adicional_decoded = urldecode($values["data_adicional"]);
            $adicionalArr = json_decode($adicional_decoded, true);
            $where_original = $values["where"];
            $where_seleccion = "";
            switch($adicionalArr["opcion"]) {
                case "TODAS":
                    $values["view"]="vw_parcela";
                    $values["order"]="sector ASC,manzana ASC, parcela ASC, secuencia ASC";
                    break;
                case "PROCESO":
                    $values["view"]="vw_parcela_consulta_proceso";
                    $values["order"]="parcela_formateada ASC";
                    break;
                case "DEVOLUCION":
                    $values["view"]="vw_parcela_consulta";
                    $where_seleccion = "  estado='B5' ";
                    $values["order"]="parcela_formateada ASC";
                    break;
                case "ABANDONO":
                    $values["view"]="vw_parcela_consulta";
                    $where_seleccion = "  estado='A5'  ";
                    $values["order"]="parcela_formateada ASC";
                    break;
                case "VIEJAS":
                    $values["view"]="vw_parcela_simplificada";
                    $where_seleccion = " id_cliente is null ";
                    $values["order"]="parcela_formateada ASC";
                    break;
                case "COMITE";
                    $values["view"]="vw_parcela_consulta";
                    $where_seleccion = " estado='A4' AND id NOT IN (SELECT p.id_parcela from dbo.vw_desenganche_parcela as p where p.estado='A5') ";
                    $values["order"]="parcela_formateada ASC";
                    break;
                case "ANALISIS":
                    break;
                case "HISTORICAS":
                    $values["view"]="vw_parcela";                    
                    $where_seleccion = " parcela_historica!='' ";
                    $values["order"]="sector ASC,manzana ASC, parcela ASC, secuencia ASC";
                    break;
                case "SIN_DETALLE":
                    $values["view"]="vw_parcela";
                    $where_seleccion = " RevisadoGerencia='S' ";
                    $values["order"]="sector ASC,manzana ASC, parcela ASC, secuencia ASC";
                    break;                    
                default:
                    $values["view"]="vw_parcela";
                    $values["order"]="sector ASC,manzana ASC, parcela ASC, secuencia ASC";
                    break;
            }
            if ($where_original<>false && $where_original<>null && $where_original<> "") {
                if ($where_seleccion <> ""){
                    $values["where"] = $values["where"] + " AND " + $where_seleccion; 
                }
            } else {
                if ($where_seleccion <> ""){
                    $values["where"] =  $where_seleccion; 
                }
            }
            $values["pagesize"]=25;
            $values["records"]=$this->get($values);
            $values["getters"]=array(
                "search"=>true,
                "excel"=>true,
                "pdf"=>true,
            );
            $values["buttons"]=array(
                "new"=>false,
                "edit"=>true,
                "delete"=>false,
                "offline"=>false,
            );
            $values["columns"]=array(
                array("field"=>"historicoNew","format"=>"text"),
                array("field"=>"contratosNew","format"=>"text"),
                array("field"=>"tipo_parcela","format"=>"text"),
                array("field"=>"Disponible","format"=>"text"),
                array("field"=>"inhumados","format"=>"text"), 
                array("field"=>"estado_parcela","format"=>"text"), 
                array("field"=>"parcela_formateada","format"=>"text"),
                array("field"=>"cliente","format"=>"text"),
                array("field"=>"pagador","format"=>"text"),
                array("field"=>"estado_desenganche","format"=>"text"), 
                array("field"=>"parcela_historica","format"=>"text"),
                array("field"=>"Sector","format"=>"text"),
                array("field"=>"Manzana","format"=>"text"),
                array("field"=>"Parcela","format"=>"text"),
                array("field"=>"Secuencia","format"=>"text"), 
            );
            $values["controls"]=array(
                "<label>" . lang('p_parcela_formateada') . " DESDE</label><input type='text' id='browser_parcela_formateada_desde' name='browser_parcela_formateada_desde' class='form-control text'/>",
                "<label>" . lang('p_parcela_formateada') . " HASTA</label><input type='text' id='browser_parcela_formateada_hasta' name='browser_parcela_formateada_hasta' class='form-control text'/>",
                "<label>".lang('p_Sector')."</label><input type='text' id='browser_sector' name='browser_sector' class='form-control text'/>",
                "<label>" . lang('p_Manzana') . "</label><input type='text' id='browser_manzana' name='browser_manzana' class='form-control text'/>",
                "<label>Parcela</label><input type='text' id='browser_parcela' name='browser_parcela' class='form-control text'/>",

                "<label>".lang('p_cliente')."</label><input type='text' id='browser_cliente' name='browser_cliente' class='form-control text'/>",
                "<label>".lang('p_Disponible')."</label><input type='text' id='browser_disponible' name='browser_disponible' class='form-control text'/>",                
            );

            $values["filters"]=array(
                array("name"=>"browser_sector", "operator"=>"=","fields"=>array("Sector")),
                array("name"=>"browser_manzana", "operator"=>"=","fields"=>array("Manzana")),
                array("name"=>"browser_parcela", "operator"=>"=","fields"=>array("Parcela")),
                array("name" => "browser_parcela_formateada_desde", "operator" => ">=", "fields" => array("parcela_formateada")),
                array("name" => "browser_parcela_formateada_hasta", "operator" => "<=", "fields" => array("parcela_formateada")),
                array("name"=>"browser_cliente", "operator"=>"like","fields"=>array("cliente")),
                array("name"=>"browser_disponible", "operator"=>"=","fields"=>array("Disponible")),
                //array("name"=>"browser_pagador", "operator"=>"like","fields"=>array("pagador")),                
                //array("name"=>"browser_search", "operator"=>"like","fields"=>array("cliente","pagador", "parcela_formateada")),
                array("name"=>"browser_search", "operator"=>"like","fields"=>array("parcela_formateada", "cliente")),  
            );
            $location=explode("::",strtolower(__METHOD__));
            $values["interface"]=(MOD_BRITANICO."/".$location[0]."/"."parcelas"); // /mod_britanico/parcela/parcelas  esto es para tener control de la pantalla y que a la vez me funcionen los filtros y paginacion del framework out-of-the-box
                                                                                 // aca tengo que armar la pantalla con los distintos filtros asociados al estado de la parcela o el desenganche
            if(!isset($values["interface"])){$values["interface"]=("brow");}  // No deberia pasar por aca
            if(!isset($values["id"])) {
                if(isset($values["id_field"])) {
                    $values["id"]=("brow");
                }
            }

            $permite = $this->trueFalseFromSN($permisosAdicionalesSetearParcelaHistorica[0]["permitido"]);

            if ($permite) {
                //$data["parameters"]["permiteGenerar"] = "S";  
                $values["permisosAdicionalesSetearParcelaHistorica"] = "S";  
            } else {
                //$data["parameters"]["permiteGenerar"] = "N";     
                $values["permisosAdicionalesSetearParcelaHistorica"] = "N";               
            }

            $permite = $this->trueFalseFromSN($permisosAdicionalesAgregarParcela[0]["permitido"]);

            if ($permite) {
                //$data["parameters"]["permiteGenerar"] = "S";  
                $values["permisosAdicionalesAgregarParcela"] = "S";  
            } else {
                //$data["parameters"]["permiteGenerar"] = "N";     
                $values["permisosAdicionalesAgregarParcela"] = "N";               
            }

            $permite = $this->trueFalseFromSN($permisosAdicionalesDeleteClienteParcela[0]["permitido"]);

            if ($permite) {
                //$data["parameters"]["permiteGenerar"] = "S";  
                $values["permisosAdicionalesDeleteClienteParcela"] = "S";  
            } else {
                //$data["parameters"]["permiteGenerar"] = "N";     
                $values["permisosAdicionalesDeleteClienteParcela"] = "N";               
            }

            //$values["permisosAdicionalesAgregarParcela"] = $permisosAdicionalesAgregarParcela;
            //$values["permisosAdicionalesDeleteClienteParcela"]=$permisosAdicionalesDeleteClienteParcela;

            $data["parameters"] = $values;
            $data["title"] = ucfirst(lang("m_".strtolower($values["model"])));


            $html=$this->load->view($values["interface"],$data,true);
            
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>compress($this,$html),
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                "data"=>null,
                "compressed"=>true
            );            
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }        
    }
    public function _parcelas($values){
        try {
            
            $values["view"]="vw_parcela";
            $values["order"]="sector ASC,manzana ASC, parcela ASC, secuencia ASC";
            $values["pagesize"]=50;
            $values["records"]=$this->get($values);
            $values["getters"]=array(
                "search"=>true,
                "excel"=>true,
                "pdf"=>true,
            );
            $values["buttons"]=array(
                "new"=>false,
                "edit"=>true,
                "delete"=>false,
                "offline"=>false,
            );
            $values["columns"]=array(
                array("field"=>"historico","format"=>"text"),
                array("field"=>"contratos","format"=>"text"),
                array("field"=>"tipo_parcela","format"=>"text"),
                array("field"=>"Disponible","format"=>"text"),
                array("field"=>"inhumados","format"=>"text"), 
                array("field"=>"estado_parcela","format"=>"text"), 
                array("field"=>"parcela_formateada","format"=>"text"),
                array("field"=>"cliente","format"=>"text"),
                array("field"=>"pagador","format"=>"text"),
                array("field"=>"estado_desenganche","format"=>"text"), 
                array("field"=>"parcela_historica","format"=>"text"),

                array("field"=>"Sector","format"=>"text"),
                array("field"=>"Manzana","format"=>"text"),
                array("field"=>"Parcela","format"=>"text"),
                array("field"=>"Secuencia","format"=>"text"), 

            );

            $values["controls"]=array(
                "<label>".lang('p_parcela_formateada')."</label><input type='text' id='browser_parcela_formateada' name='browser_parcela_formateada' class='form-control text'/>",
                //"<label>".lang('p_Sector')."</label><input type='text' id='browser_sector' name='browser_sector' class='form-control text'/>",
                //"<label>".lang('p_Manzana')."</label><input type='text' id='browser_manzana' name='browser_manzana' class='form-control text'/>",
                "<label>".lang('p_Disponible')."</label><input type='text' id='browser_disponible' name='browser_disponible' class='form-control text'/>",                
                "<label>".lang('p_cliente')."</label><input type='text' id='browser_cliente' name='browser_cliente' class='form-control text'/>",
                "<label>".lang('p_pagador')."</label><input type='text' id='browser_pagador' name='browser_pagador' class='form-control text'/>",
            );

            $values["filters"]=array(
                //array("name"=>"browser_sector", "operator"=>"=","fields"=>array("Sector")),
                //array("name"=>"browser_manzana", "operator"=>"=","fields"=>array("Manzana")),
                array("name"=>"browser_parcela_formateada", "operator"=>"like","fields"=>array("parcela_formateada")),
                array("name"=>"browser_disponible", "operator"=>"=","fields"=>array("Disponible")),
                array("name"=>"browser_cliente", "operator"=>"like","fields"=>array("cliente")),
                array("name"=>"browser_pagador", "operator"=>"like","fields"=>array("pagador")),                
                array("name"=>"browser_search", "operator"=>"like","fields"=>array("cliente","pagador", "parcela_formateada")),  
            );


            $location=explode("::",strtolower(__METHOD__));
            $values["interface"]=(MOD_BRITANICO."/".$location[0]."/".$location[1]); // /mod_britanico/parcela/parcelas
    
            //if(!isset($values["interface"])){$values["interface"]=("brow");}

            if(!isset($values["id"])) {
                if(isset($values["id_field"])) {
                    $values["id"]=("brow");
                }
            }
            $data["parameters"] = $values;
            $data["title"] = ucfirst(lang("m_".strtolower($values["model"])));


            $html=$this->load->view($values["interface"],$data,true);
            
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>compress($this,$html),
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                "data"=>null,
                "compressed"=>true
            );            

        }

        catch(Exception $e){
            return logError($e,__METHOD__ );
        }        

    }
    public function edit($values){
        try {

            $location=explode("::",strtolower(__METHOD__));
            $values["interface"]=(MOD_BRITANICO."/".$location[0]."/abm"); // CCOO -> por esto el directorio de la vista debe ser en minuscula
            $values["page"]=1;
            $values["table"]="vw_parcela"; // CCOO
            $values["view"]="vw_parcela";
            // CCOO
            $values["where"]=("id_parcela=".$values["id"]);
            $values["records"]=$this->get($values);

            $parameters_id_TipoParcela=array(
                "model"=>(MOD_BRITANICO."/TipoParcela"),
                "table"=>"TipoParcela",
                "name"=>"id_TipoParcela",  // aca va eñ nombre del campo de la tabla principal, inhumados por ej. Sirva como contenedor del dato luego de la seleccion.
                "class"=>"form-control dbase",
                "empty"=>true,
                "id_actual"=>secureComboPosition($values["records"],"id_TipoParcela"), // --> id de la tabla inhumados
                //"id_field"=>"id_Parcela_Actual", // --> id de la tabla inhumados
                "id_field"=>"id_TipoParcela", // --> esta es la PK de la tabla del combo y es la que usa para identificar e seleccionado.
                "description_field"=>"Nombre",  // --> descripcion de la tabla del combo
                //"get"=>array("where"=>"isnull(NumeroCompacto ,'')<>''", "order"=>"NumeroCompacto  ASC","pagesize"=>-1),
                "get"=>array("order"=>"Nombre  ASC","pagesize"=>-1),
            );
            $values["controls"]=array("id_TipoParcela"=>getCombo($parameters_id_TipoParcela,$this));
            $registro = $values["records"]["data"][0];
            // Cliente Relacionado
	        
            $sqlClienteRelacionado = "SELECT * FROM dbo.vw_Rel_Cliente_Pagador_Parcela WHERE id_cliente= ? AND id_parcela= ?";
            $prms=array(
                "id_cliente" => $registro["id_cliente"],
                "id_Parcela" => $registro["id_Parcela"],
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
            $sqlInhumadosParcela = "SELECT * FROM dbo.vw_inhumados_by_parcela_simple WHERE id_Parcela_Actual= ? ORDER BY NumeroInhumado ASC";
            $prms=array("id_Parcela" => $registro["id_Parcela"]);            
            $rc = $this->execAdHocWithParms($sqlInhumadosParcela, $prms);
            if (!$rc) {
                // si dio false estoy en problemas.....hacer un throw o raise...
                $mierror = $this->db->error();
                throw new Exception($mierror['message'], $mierror['code']);
            } else {
                $inhumadosParcela = $rc->result_array();
                $values["inhumadosParcela"]=$inhumadosParcela;
            }
	        
            $sqlContratosArrendamientoParcela = "SELECT * FROM dbo.vw_rel_cuentacorriente_numeros WHERE id_parcela= ? ORDER BY Fecha_Vencimiento DESC";
            $prms=array("id_Parcela" => $registro["id_Parcela"]);            
            $rc = $this->execAdHocWithParms($sqlContratosArrendamientoParcela, $prms);

            if (!$rc) {
                // si dio false estoy en problemas.....hacer un throw o raise...
                $mierror = $this->db->error();
                throw new Exception($mierror['message'], $mierror['code']);
            } else {
                $contratosArrendamientoParcela = $rc->result_array();
                $values["contratosArrendamientoParcela"]=$contratosArrendamientoParcela;
            }
            $sqlPermisosAdicionalesSetearParcelaHistorica = "exec obtenerPermisosAdicionales ?, ?;";
            $prmsPermisosAdicionalesSetearParcelaHistorica=array(
                "userId" => $values["id_user_active"],
                "perm" => "setear_parcela_historica"
            );                     
            $rc = $this->execAdHocWithParms($sqlPermisosAdicionalesSetearParcelaHistorica, $prmsPermisosAdicionalesSetearParcelaHistorica);
            if (!$rc) {
                // si dio false estoy en problemas.....hacer un throw o raise...
                $mierror = $this->db->error();
                throw new Exception($mierror['message'], $mierror['code']);
            } else {
                $permisosAdicionalesSetearParcelaHistorica = $rc->result_array();
            }  
            $sqlPermisosAdicionalesAgregarParcela = "exec obtenerPermisosAdicionales ?, ?;";
            $prmsPermisosAdicionalesAgregarParcela=array(
                "userId" => $values["id_user_active"],
                "perm" => "agregado_parcela"
            );                     
            $rc = $this->execAdHocWithParms($sqlPermisosAdicionalesAgregarParcela, $prmsPermisosAdicionalesAgregarParcela);
            if (!$rc) {
                // si dio false estoy en problemas.....hacer un throw o raise...
                $mierror = $this->db->error();
                throw new Exception($mierror['message'], $mierror['code']);
            } else {
                $permisosAdicionalesAgregarParcela = $rc->result_array();
            }  
            $sqlPermisosAdicionalesDeleteClienteParcela = "exec obtenerPermisosAdicionales ?, ?;";
            $prmsPermisosAdicionalesDeleteClienteParcela=array(
                "userId" => $values["id_user_active"],
                "perm" => "delete_cliente_parcela"
            );                     
            $rc = $this->execAdHocWithParms($sqlPermisosAdicionalesDeleteClienteParcela, $prmsPermisosAdicionalesDeleteClienteParcela);
            if (!$rc) {
                // si dio false estoy en problemas.....hacer un throw o raise...
                $mierror = $this->db->error();
                throw new Exception($mierror['message'], $mierror['code']);
            } else {
                $permisosAdicionalesDeleteClienteParcela = $rc->result_array();
            }             
            $permite = $this->trueFalseFromSN($permisosAdicionalesSetearParcelaHistorica[0]["permitido"]);
            if ($permite) {
                $values["permisosAdicionalesSetearParcelaHistorica"] = "S";  
            } else {
                $values["permisosAdicionalesSetearParcelaHistorica"] = "N";               
            }
            $permite = $this->trueFalseFromSN($permisosAdicionalesAgregarParcela[0]["permitido"]);
            if ($permite) {
                $values["permisosAdicionalesAgregarParcela"] = "S";  
            } else {
                $values["permisosAdicionalesAgregarParcela"] = "N";               
            }
            $permite = $this->trueFalseFromSN($permisosAdicionalesDeleteClienteParcela[0]["permitido"]);
            if ($permite) {
                $values["permisosAdicionalesDeleteClienteParcela"] = "S";  
            } else {
                $values["permisosAdicionalesDeleteClienteParcela"] = "N";               
            }
            return parent::edit($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }

    // CCOO ??
    public function save($values,$fields=null){
        try {
            if (!isset($values["id"])){$values["id"]=0;}
            $id=(int)$values["id"];
            if($id==0){
                // Insert
                if($fields==null) {
                    $storedp = "dbo.coop_Parcela_Insert ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?";
                    $sp2 = "dbo.coop_Rel_Cliente_Pagador_Insert ?, ?";
                    $p = array(
                        'id_Parcela'=>$values["id_Parcela"],            // PK, creada en el alta
                        'ID_EmpresaSucursal'=>$values["ID_EmpresaSucursal"], // se crea en el alta. No editable
                        'Sector' => secureEmptyNull($values,"Sector"),  // Clave compuesta, se crea en el alta. No editable
                        'Manzana' => $values["Manzana"],                // Clave compuesta, se crea en el alta. No editable
                        'Parcela' => $values["Parcela"],                // Clave compuesta, se crea en el alta. No editable
                        'Secuencia' => $values["Secuencia"],            // Clave compuesta, se crea en el alta. No editable
                        'id_EstadoParcela' => $values["id_EstadoParcela"], //  estado 1 Arrendada 2 Libre 3 Fuera de Venta. No editable
                        'id_TipoParcela' => $values["id_TipoParcela"],  // Boveda, Cripta, etc.  Si codigoAnterior == S -> id_TipoParcela = null
                        'id_TamanioParcela' => secureEmptyNull($values,"id_TamanioParcela"), // Tamaño. Esta definido en el Alta. No editable  
                        'CodigoAnterior' => $values["CodigoAnterior"], // No editable
                        'FechaArrendamiento' => $values["FechaArrendamiento"], // No editable
                        'ClienteCategoria' => $values["ClienteCategoria"], // Estado: _N_ormal, _J_ardin, _C_amino
                        'id_cliente' => secureEmptyNull($values,"id_cliente"), // sale del buscador, y si no hay cliente podra pasar Disponible a S
                        'id_pagador' => $values["id_pagador"], // sale del buscador, y si no hay cliente podra pasar Disponible a S
                        'FechaDia' => $values["FechaDia"], // _N_ormal, _J_ardin o _C_amino. No nulo?
                        'MesesContratoArrendamiento' => $values["MesesContratoArrendamiento"], // ??
                        'id_ConceptoListaPrecio_Arrendamiento' => secureEmptyNull($values,"id_ConceptoListaPrecio_Arrendamiento"), // ??
                        'ImporteArrendamiento' => $values["ImporteArrendamiento"], // ??
                        'PeriodoConservacion' => $values["PeriodoConservacion"], // ??
                        'id_ConceptoListaPrecio_Conservacion' => secureEmptyNull($values,"id_ConceptoListaPrecio_Conservacion"), // ??
                        'CertificadoTitularidad' => $values["CertificadoTitularidad"], // ??
                        'NumeroCompacto' => $values["NumeroCompacto"], // ??
                        'Disponible' => null, // No es editable, depende de la existencia de cliente y lo resuelve el SP
                        'fecha_limite_conservacion' => secureEmptyNull($values,"fecha_limite_conservacion"),  // ??
                        'numero_pagina_mapa' => secureEmptyNull($values,"numero_pagina_mapa"), // Numero de Pagina. Si codigoAnterior == S -> nroPagina = null
                        'observaciones' => secureEmptyNull($values,"observaciones"),   // Datos parcela
                    );
                    // Campos de la ventana de ABM
                    $fields = array(
                        'id_Parcela'=>$values["id_Parcela"],
                        'ID_EmpresaSucursal'=>$values["ID_EmpresaSucursal"],
                        'Sector' => secureEmptyNull($values,"Sector"),
                        'Manzana' => $values["Manzana"],
                        'Parcela' => $values["Parcela"],
                        'Secuencia' => secureEmptyNull($values,"Secuencia"),
                        'id_EstadoParcela' => $values["id_EstadoParcela"],
                        'id_TipoParcela' => $values["id_TipoParcela"],
                        'id_TamanioParcela' => $values["id_TamanioParcela"],
                        'CodigoAnterior' => secureEmptyNull($values,"CodigoAnterior"),
                        'FechaArrendamiento' => $values["FechaArrendamiento"],
                        'ClienteCategoria' => $values["ClienteCategoria"],
                        'id_cliente' => $values["id_cliente"],
                        'id_pagador' => secureEmptyNull($values,"id_pagador"),
                        'FechaDia' => $values["FechaDia"],
                        'MesesContratoArrendamiento' => $values["MesesContratoArrendamiento"],
                        'id_ConceptoListaPrecio_Arrendamiento' => secureEmptyNull($values,"id_ConceptoListaPrecio_Arrendamiento"),
                        'CertificadoTitularidad' => $values["CertificadoTitularidad"],
                        'NumeroCompacto' => $values["NumeroCompacto"],
                        'Disponible' => secureEmptyNull($values,"Disponible"),
                        'fecha_limite_conservacion' => secureEmptyNull($values,"fecha_limite_conservacion"),
                        'numero_pagina_mapa' => secureEmptyNull($values,"numero_pagina_mapa"),
                        'observaciones' => secureEmptyNull($values,"observaciones"),                         
                    );
                    
                }
            } else {
                // Update
                if($fields==null) {
                    $sqlParcela = "SELECT * FROM dbo.Parcela WHERE id_Parcela = ?";
                    $prms=array("id_Parcela" => $values["id"]);            
                    $rc = $this->execAdHocWithParms($sqlParcela, $prms);
                    if (!$rc) {
                        // si dio false estoy en problemas.....hacer un throw o raise...
                        $mierror = $this->db->error();
                        throw new Exception($mierror['message'], $mierror['code']);
                    } else {
                        $_parcelaEnEdicion = $rc->result_array();
                    }
                    $storedp = "dbo.coop_Parcela_Update ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?";
                    $sp2 = "dbo.coop_Rel_Cliente_Pagador_Update ?, ?";
                    $id_cliente="";
                    $id_pagador="";
                    $x=[];
                    $y=[];
                    if (isset($values["key"])) {
                        if ($values["key"]!=null){
                            $x=explode("|*|", $values["key"]);
                            $y=explode(".", $x[1]);
                            $id_cliente=$y[0];  // 
                            $id_pagador=$y[1];
                        }
                    }
                    $parcelaEnEdicion = $_parcelaEnEdicion[0];
                    $ID_EmpresaSucursal = $parcelaEnEdicion["ID_EmpresaSucursal"];
                    $Sector = $parcelaEnEdicion["Sector"];
                    $Manzana = $parcelaEnEdicion["Manzana"];
                    $Parcela = $parcelaEnEdicion["Parcela"];
                    $Secuencia = $parcelaEnEdicion["Secuencia"];
                    $id_EstadoParcela = $parcelaEnEdicion["id_EstadoParcela"];
                    $id_TipoParcela = $parcelaEnEdicion["id_TipoParcela"];
                    $id_TamanioParcela = $parcelaEnEdicion["id_TamanioParcela"];
                    $CodigoAnterior = $parcelaEnEdicion["CodigoAnterior"];
                    $FechaArrendamiento = $parcelaEnEdicion["FechaArrendamiento"];
                    $FechaDia = $parcelaEnEdicion["FechaDia"];
                    $MesesContratoArrendamiento = $parcelaEnEdicion["MesesContratoArrendamiento"];
                    $id_ConceptoListaPrecio_Arrendamiento = $parcelaEnEdicion["id_ConceptoListaPrecio_Arrendamiento"];
                    $ImporteArrendamiento = $parcelaEnEdicion["ImporteArrendamiento"];
                    $PeriodoConservacion = $parcelaEnEdicion["PeriodoConservacion"];
                    $id_ConceptoListaPrecio_Conservacion = $parcelaEnEdicion["id_ConceptoListaPrecio_Conservacion"];
                    $CertificadoTitularidad = $parcelaEnEdicion["CertificadoTitularidad"];
                    $NumeroCompacto = $parcelaEnEdicion["NumeroCompacto"];
                    $fecha_limite_conservacion = $parcelaEnEdicion["fecha_limite_conservacion"];
                    $o = secureEmptyNull($values,"Observaciones");
                    $oo = $values["Observaciones"];
                    // parametros del SP
                    $p = array(
                        'id_Parcela'=>$values["id"],            // PK, creada en el alta
                        'ID_EmpresaSucursal'=> $ID_EmpresaSucursal, // se crea en el alta. No editable. No viene en $values
                        'Sector' => $Sector,  // Clave compuesta, se crea en el alta. No editable. No viene en $values
                        'Manzana' => $Manzana,                // Clave compuesta, se crea en el alta. No editable. No viene en $values
                        'Parcela' => $Parcela,                // Clave compuesta, se crea en el alta. No editable. No viene en $values
                        'Secuencia' => $Secuencia,            // Clave compuesta, se crea en el alta. No editable. No viene en $values
                        'id_EstadoParcela' => $id_EstadoParcela, //  estado 1 Arrendada 2 Libre 3 Fuera de Venta. No editable. No viene en $values
                        'id_TipoParcela' => $id_TipoParcela,  // Boveda, Cripta, etc.  Si codigoAnterior == S -> id_TipoParcela = null
                        'id_TamanioParcela' => $id_TamanioParcela, // Tamaño. Esta definido en el Alta. No editable. No viene en $values  
                        'CodigoAnterior' => $values["CodigoAnterior"], // Mapeado con Parcela Historica.
                        'FechaArrendamiento' => $FechaArrendamiento, // No editable. No viene en $values
                        'ClienteCategoria' => $values["ClienteCategoria"], // Estado: _N_ormal, _J_ardin, _C_amino
                        'id_cliente' => $id_cliente, // sale del buscador, y si no hay cliente podra pasar Disponible a S
                        'id_pagador' => $id_pagador, // sale del buscador, y si no hay cliente podra pasar Disponible a S
                        'FechaDia' => $FechaDia, // No editable. No viene en $values
                        'MesesContratoArrendamiento' => $MesesContratoArrendamiento, // No editable. No viene en $values
                        'id_ConceptoListaPrecio_Arrendamiento' => $id_ConceptoListaPrecio_Arrendamiento, // No editable. No viene en $values
                        'ImporteArrendamiento' => $ImporteArrendamiento, // No editable. No viene en $values
                        'PeriodoConservacion' => $PeriodoConservacion, // No editable. No viene en $values
                        'id_ConceptoListaPrecio_Conservacion' => $id_ConceptoListaPrecio_Conservacion, // No editable. No viene en $values
                        'CertificadoTitularidad' => $CertificadoTitularidad, // No editable. No viene en $values
                        'NumeroCompacto' => $NumeroCompacto, // No editable. No viene en $values
                        'Disponible' => null, // No es editable, depende de la existencia de cliente y lo resuelve el SP
                        'fecha_limite_conservacion' => $fecha_limite_conservacion,  // No editable. No viene en $values
                        'numero_pagina_mapa' => secureEmptyNull($values, "numero_pagina_mapa"), // Numero de Pagina. Si codigoAnterior == S -> nroPagina = null
                        'observaciones' => $values["observaciones"],   // Datos parcela
                    );
                    $fields = array(
                        'id_Parcela'=>$values["id_Parcela"],
                        'ID_EmpresaSucursal'=>$values["ID_EmpresaSucursal"],
                        'Sector' => secureEmptyNull($values,"Sector"),
                        'Manzana' => $values["Manzana"],
                        'Parcela' => $values["Parcela"],
                        'Secuencia' => secureEmptyNull($values,"Secuencia"),
                        'id_EstadoParcela' => $values["id_EstadoParcela"],
                        'id_TipoParcela' => $values["id_TipoParcela"],
                        'id_TamanioParcela' => $values["id_TamanioParcela"],
                        'CodigoAnterior' => $values["CodigoAnterior"],
                        'FechaArrendamiento' => $values["FechaArrendamiento"],
                        'ClienteCategoria' => $values["ClienteCategoria"],
                        'id_cliente' => $values["id_cliente"],
                        'id_pagador' => $values["id_pagador"],
                        'FechaDia' => $values["FechaDia"],
                        'MesesContratoArrendamiento' => $values["MesesContratoArrendamiento"],
                        'id_ConceptoListaPrecio_Arrendamiento' => secureEmptyNull($values,"id_ConceptoListaPrecio_Arrendamiento"),
                        'CertificadoTitularidad' => $values["CertificadoTitularidad"],
                        'NumeroCompacto' => $values["NumeroCompacto"],
                        'Disponible' => secureEmptyNull($values,"Disponible"),
                        'fecha_limite_conservacion' => secureEmptyNull($values,"fecha_limite_conservacion"),
                        'numero_pagina_mapa' => secureEmptyNull($values,"numero_pagina_mapa"),
                        'observaciones' => secureEmptyNull($values,"observaciones"),                         
                    );
                }
            }
            $saving = parent::saveExtended($values, $fields, $forcedTable = null, $forcedSp = $storedp, $param = $p, $keyname = "id_Parcela");
            $prms = array("id_cliente" => $p["id_cliente"],"id_pagador" => $p["id_pagador"]);
            return $saving;
        }
        catch (Exception $e){
            return logError($e,"Excepcion en metodo:".__METHOD__. " * namespace:" . __NAMESPACE__ . 
                                    " * clase:" . __CLASS__ . " * funcion:". __FUNCTION__ 
                                    . " * directorio:" .  __DIR__ . " * archivo:" . __FILE__ );
        }
    }
    public function contratosArrendamiento_CEM($values) {
        try {
	        
            $sqlContratos = "SELECT * FROM dbo.vw_rel_cuentacorriente_numeros WHERE id_parcela= ? ORDER BY Fecha_Vencimiento DESC";
            $prms=array("id_Parcela" => $values["id_Parcela"]);            
            $rc = $this->execAdHocWithParms($sqlContratos, $prms);
            if (!$rc) {
                // si dio false estoy en problemas.....hacer un throw o raise...
                $mierror = $this->db->error();
                throw new Exception($mierror['message'], $mierror['code']);
            } else {
                $contratos = $rc->result_array();
            }
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>"Records",
                "contratos"=>$contratos,
                "table"=>$this->table,
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT)
            );
        }
        catch(Exception $e) {
            return logError($e,"Excepcion en metodo:".__METHOD__. " * namespace:" . __NAMESPACE__ . " * clase:" . __CLASS__ . " * funcion:". __FUNCTION__ . " * directorio:" .  __DIR__ . " * archivo:" . __FILE__ );
        }                   
    }
    public function historicoParcela_CEM($values) {
        try {
	        
            $sqlHistoricoActual = "SELECT * " .
             "FROM dbo.vw_MovimientoInhumado " .  
             "WHERE id_Parcela= ? AND detalle NOT LIKE '%CAMBIO%' " . 
             "and id_inhumado NOT IN (SELECT m.id_inhumado " . 
             "                       FROM MovimientoInhumado as m " . 
             "                       WHERE m.id_Parcela= ? AND m.detalle like '%SALIDA%') " . 
             "and FechaMovimiento IN (SELECT MAX(f.FechaMovimiento)  " .
             "                       FROM MovimientoInhumado as f  " .
             "                       WHERE f.id_Parcela= ?   " .
             "                       AND f.id_inhumado = vw_MovimientoInhumado.id_inhumado " .  
             "                       AND (f.detalle like '%ENTRADA%' or f.detalle is null) )  " .
             "order by FechaMovimiento; ";
            $sqlHistoricoCompleto = "SELECT * FROM dbo.vw_MovimientoInhumado WHERE id_parcela= ? ORDER BY FechaMovimiento;";
            $prms=array(
                "id_Parcela1" => $values["id_Parcela"],
                "id_Parcela2" => $values["id_Parcela"],
                "id_Parcela3" => $values["id_Parcela"],            
            );            
            $rc = $this->execAdHocWithParms($sqlHistoricoActual, $prms);

            if (!$rc) {
                // si dio false estoy en problemas.....hacer un throw o raise...
                $mierror = $this->db->error();
                throw new Exception($mierror['message'], $mierror['code']);
            } else {
                $historicoActual = $rc->result_array();
            }
            $prms=array("id_parcela" => $values["id_Parcela"]);            
            $rc = $this->execAdHocWithParms($sqlHistoricoCompleto, $prms);
            if (!$rc) {
                // si dio false estoy en problemas.....hacer un throw o raise...
                $mierror = $this->db->error();
                throw new Exception($mierror['message'], $mierror['code']);
            } else {
                $historicoCompleto = $rc->result_array();
            }
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>"Records",
                "historicoActual"=>$historicoActual,
                "historicoCompleto"=>$historicoCompleto,
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
	        
            $sqlClientePagador = "SELECT TOP 8 cast(id_cliente as varchar(200))+'.'+cast(id_pagador as varchar(200)) as id, ".
            "            pagador as pagador, " .
            "            pagador as detalle " .
            "FROM dbo.vw_Rel_Cliente_Pagador " .
            "WHERE pagador Like ? ORDER BY 3; ";
            $prms=array("searchKey" => $values["searchKey"]);            
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
    public function getEstadoDesenganche($values) {
        try {
	        
            $sql = "SELECT TOP 1 estado FROM dbo.desenganche_parcela WHERE id_parcela=? AND id_cliente=? ORDER BY fecha_cambio DESC; ";
            $prms=array(
                "id_parcela" => $values["id_parcela"],
                "id_cliente" => $values["id_cliente"],
            );               
            $rc = $this->execAdHocWithParms($sql, $prms);
            if (!$rc) {
                // si dio false estoy en problemas.....hacer un throw o raise...
                $mierror = $this->db->error();
                throw new Exception($mierror['message'], $mierror['code']);
            } else {
                $desenganche = $rc->result_array();
            }
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>"Records",
                "desenganche"=>$desenganche,
                "table"=>$this->table,
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT)
            );
        }
        catch(Exception $e) {
            return logError($e,__METHOD__ );
        }            
    }
    public function setEstadoDesenganche($values) {
        try {
	        
            $sqlClientePagador = "INSERT INTO dbo.desenganche_parcela (id_parcela,id_cliente,fecha_cambio,estado) VALUES (?,?,GETDATE(),?); ";
            $prms=array(
                "id_parcela" => $values["id_parcela"],
                "id_cliente" => $values["id_cliente"],
                "estado" => $values["estado"],
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
    public function revertirDesenganche($values) {
        try {
	        
            $sql = "DELETE dbo.desenganche_parcela WHERE id_parcela=? AND id_cliente=?; ";
            $prms=array(
                "id_parcela" => $values["id_parcela"],
                "id_cliente" => $values["id_cliente"],
            );            
            $rc = $this->execAdHocWithParms($sql, $prms);
            if (!$rc) {
                // si dio false estoy en problemas.....hacer un throw o raise...
                $mierror = $this->db->error();
                throw new Exception($mierror['message'], $mierror['code']);
            } else {
                $cliente_pagador = $rc->result_array(); // hay que corregir aca
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
    public function desengancharParcelaCC($values) {
        try {     
	        
            $sql = "spDesengarcharParcela ?, ?, ?; ";
            $prms=array(
                "id_parcela" => $values["id_parcela"],
                "id_cliente" => $values["id_cliente"],
                "estado" => $values["estado"],
            );            
            $rc = $this->execAdHocWithParms($sql, $prms);
            if (!$rc) {
                // si dio false estoy en problemas.....hacer un throw o raise...
                $mierror = $this->db->error();
                throw new Exception($mierror['message'], $mierror['code']);
            } else {
                $desenganche = $rc->result_array();
            }
           return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>"Records",
                "desenganche"=>$desenganche,
                "table"=>$this->table,
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT)
            );
        }
        catch(Exception $e) {
            return logError($e,__METHOD__ );
        }            
    }        
    public function setearDesengancheParcela($values) {
        try {     
            $prmsInsDesenganche=array(
                "id_parcela" => $values["id_parcela"],
                "id_cliente" => $values["id_cliente"],
                "fecha_cambio" => $this->now,
                "estado" => $values["modo"],
            );    
            $prmsDelDesenganche=array(
                "id_parcela" => $values["id_parcela"],
                "id_cliente" => $values["id_cliente"],
            );    
            $sqlDesengancheCC = "dbo.spDesengarcharParcela ?, ?, ?; ";
            $prmsDesengancheCC=array(
                "id_parcela" => $values["id_parcela"],
                "id_cliente" => $values["id_cliente"],
                "estado" => $values["modo"],
            );            
            $accion = '';
            $resultadoI = false;
            $resultadoD = false;
            $resultadoSP = false;
            $last_id = -1;
            $deleted_rows=-1;
            $inserted_rows = -1;
            if ($values["modo"] != 'X') {
                $accion.='I';
                // Insert
                $resultadoI=$this->db->insert('dbo.desenganche_parcela',$prmsInsDesenganche); // aca solo da true si funciono el resultado fue correcto
                $inserted_rows=$this->db->affected_rows();
                $last_id = $this->db->insert_id(); // esto no devuelve nada porque no hay un identity
                //$resultadoI=true;
            } else {
                $accion.='D';
                // delete
                $resultadoD=$this->db->delete('dbo.desenganche_parcela', $prmsDelDesenganche);
                $deleted_rows=$this->db->affected_rows();
                //$resultadoD = true;
            }
            if ($values["modo"] == "A5" || $values["modo"] == "B5") {
                $accion.='P';
                $rc = $this->execAdHocWithParms($sqlDesengancheCC, $prmsDesengancheCC);
                if (!$rc) {
                    // si dio false estoy en problemas.....hacer un throw o raise...
                    $mierror = $this->db->error();
                    throw new Exception($mierror['message'], $mierror['code']);
                } else {
                    // select @idAsiento as id, @err as error, @reg as registros
                    $desengancheCC = $rc->result_array();
                    $resultadoSP=true;
                }
            }
           return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>"Records",
                "accion"=>$accion,
                "resultadoI"=>$resultadoI,
                "insertedRows"=>$inserted_rows,
                "resultadoD"=>$resultadoD,
                "deletedRows"=>$deleted_rows,
                "resultadoCC"=>$resultadoSP,
                "desengancheCC"=>$desengancheCC,
                "table"=>$this->table,
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT)
            );
        }
        catch(Exception $e) {
            return logError($e,__METHOD__ );
        }            
    }     
    
    public function getAnalisisGestion($values) {
        try {     
            // sp de reporte
            $sqlAnalisisGestionUniverso = "dbo.spAnalisisGestionUniverso; ";          
            $sqlAnalisisGestionMorosos = "dbo.spAnalisisGestionMorosos; ";          
            $rc = $this->execAdHocWithParms($sqlAnalisisGestionUniverso, array());
            if (!$rc) {
                // si dio false estoy en problemas.....hacer un throw o raise...
                $mierror = $this->db->error();
                throw new Exception($mierror['message'], $mierror['code']);
            } else {
                $analisisGestionUniverso = $rc->result_array();
                $resultadoSP=true;
            }
            $rc = $this->execAdHocWithParms($sqlAnalisisGestionMorosos, array());
            if (!$rc) {
                // si dio false estoy en problemas.....hacer un throw o raise...
                $mierror = $this->db->error();
                throw new Exception($mierror['message'], $mierror['code']);
            } else {
                $analisisGestionMorosos = $rc->result_array();
                $resultadoSP=true;
            }            
           return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>"Records",
                "analisisGestionUniverso"=>$analisisGestionUniverso,
                "analisisGestionMorosos"=>$analisisGestionMorosos,
                "table"=>$this->table,
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT)
            );
        }
        catch(Exception $e) {
            return logError($e,__METHOD__ );
        }            
    }         
    public function excel($values){
        try {
            $values["delimiter"]=";";
            $values["pagesize"]=-1;
            $adicional_decoded = urldecode($values["data_adicional"]);
            $adicionalArr = json_decode($adicional_decoded, true);
            $where_original = $values["where"];
            $where_seleccion = "";
            switch($adicionalArr["opcion"]) {
                case "TODAS":
                    $values["view"]="vw_parcela";
                    $values["order"]="sector ASC,manzana ASC, parcela ASC, secuencia ASC";
                    break;
                case "PROCESO":
                    $values["view"]="vw_parcela_consulta_proceso";
                    $values["order"]="parcela_formateada ASC";
					break;
                case "DEVOLUCION":
                    $values["view"]="vw_parcela_consulta";
                    $where_seleccion = "  estado='B5' ";
					$values["order"]="parcela_formateada ASC";
                    break;
                case "ABANDONO":
                    $values["view"]="vw_parcela_consulta";
                    $where_seleccion = "  estado='A5'  ";
					$values["order"]="parcela_formateada ASC";
                    break;
                case "VIEJAS":
                    $values["view"]="vw_parcela_simplificada";
                    $where_seleccion = " id_cliente is null ";
					$values["order"]="parcela_formateada ASC";
                    break;
                case "COMITE";
                    $values["view"]="vw_parcela_consulta";
                    $where_seleccion = " estado='A4' AND id NOT IN (SELECT p.id_parcela from dbo.vw_desenganche_parcela as p where p.estado='A5') ";
                    $values["order"]="parcela_formateada ASC";
					break;
                case "ANALISIS":
                    break;
                case "HISTORICAS":
                    $values["view"]="vw_parcela";                    
                    $where_seleccion = " parcela_historica!='' ";
					$values["order"]="sector ASC,manzana ASC, parcela ASC, secuencia ASC";
                    break;
                case "SIN_DETALLE":
                    $values["view"]="vw_parcela";
                    $where_seleccion = " RevisadoGerencia='S' ";
					$values["order"]="sector ASC,manzana ASC, parcela ASC, secuencia ASC";
                    break;                    
                default:
                    $values["view"]="vw_parcela";
                    $values["order"]="sector ASC,manzana ASC, parcela ASC, secuencia ASC";
                    break;
            }
            if ($where_original<>false && $where_original<>null && $where_original<> "") {
                if ($where_seleccion <> ""){
                    $values["where"] = $values["where"] + " AND " + $where_seleccion; 
                }
            } else {
                if ($where_seleccion <> ""){
                    $values["where"] =  $where_seleccion; 
                }
            }
            $values["pagesize"]=-1;
            $values["records"]=$this->get($values);
            $values["columns"]=array(
                array("field"=>"historico","format"=>"text"),
                array("field"=>"contratos","format"=>"text"),
                array("field"=>"tipo_parcela","format"=>"text"),
                array("field"=>"Disponible","format"=>"text"),
                array("field"=>"inhumados","format"=>"text"), 
                array("field"=>"estado_parcela","format"=>"text"), 
                array("field"=>"parcela_formateada","format"=>"text"),
                array("field"=>"cliente","format"=>"text"),
                array("field"=>"pagador","format"=>"text"),
                array("field"=>"estado_desenganche","format"=>"text"), 
                array("field"=>"parcela_historica","format"=>"text"),
                array("field"=>"Sector","format"=>"text"),
                array("field"=>"Manzana","format"=>"text"),
                array("field"=>"Parcela","format"=>"text"),
                array("field"=>"Secuencia","format"=>"text"), 
            );
            return parent::excel($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function pdf($values){
        try {
            $values["pagesize"]=-1;
            $adicional_decoded = urldecode($values["data_adicional"]);
            $adicionalArr = json_decode($adicional_decoded, true);
            $where_original = $values["where"];
            $where_seleccion = "";
            switch($adicionalArr["opcion"]) {
                case "TODAS":
                    $values["view"]="vw_parcela";
                    $values["order"]="sector ASC,manzana ASC, parcela ASC, secuencia ASC";
                    break;
                case "PROCESO":
                    $values["view"]="vw_parcela_consulta_proceso";
                    $values["order"]="parcela_formateada ASC";
                    break;
                case "DEVOLUCION":
                    $values["view"]="vw_parcela_consulta";
                    $where_seleccion = "  estado='B5' ";
                    $values["order"]="parcela_formateada ASC";
                    break;
                case "ABANDONO":
                    $values["view"]="vw_parcela_consulta";
                    $where_seleccion = "  estado='A5'  ";
                    $values["order"]="parcela_formateada ASC";
                    break;
                case "VIEJAS":
                    $values["view"]="vw_parcela_simplificada";
                    $where_seleccion = " id_cliente is null ";
                    $values["order"]="parcela_formateada ASC";
                    break;
                case "COMITE";
                    $values["view"]="vw_parcela_consulta";
                    $where_seleccion = " estado='A4' AND id NOT IN (SELECT p.id_parcela from dbo.vw_desenganche_parcela as p where p.estado='A5') ";
                    $values["order"]="parcela_formateada ASC";
                    break;
                case "ANALISIS":
                    break;
                case "HISTORICAS":
                    $values["view"]="vw_parcela";                    
                    $where_seleccion = " parcela_historica!='' ";
                    $values["order"]="sector ASC,manzana ASC, parcela ASC, secuencia ASC";
                    break;
                case "SIN_DETALLE":
                    $values["view"]="vw_parcela";
                    $where_seleccion = " RevisadoGerencia='S' ";
                    $values["order"]="sector ASC,manzana ASC, parcela ASC, secuencia ASC";
                    break;                    
                default:
                    $values["view"]="vw_parcela";
                    $values["order"]="sector ASC,manzana ASC, parcela ASC, secuencia ASC";
                    break;
            }
            if ($where_original<>false && $where_original<>null && $where_original<> "") {
                if ($where_seleccion <> ""){
                    $values["where"] = $values["where"] + " AND " + $where_seleccion; 
                }
            } else {
                if ($where_seleccion <> ""){
                    $values["where"] =  $where_seleccion; 
                }
            }
            $values["pagesize"]=-1;
            $values["records"]=$this->get($values);
            $values["columns"]=array(
                array("field"=>"tipo_parcela","format"=>"text"),
                array("field"=>"Disponible","format"=>"text"),
                array("field"=>"inhumados","format"=>"text"), 
                array("field"=>"estado_parcela","format"=>"text"), 
                array("field"=>"parcela_formateada","format"=>"text"),
                array("field"=>"cliente","format"=>"text"),
                array("field"=>"pagador","format"=>"text"),
                array("field"=>"estado_desenganche","format"=>"text"), 
                array("field"=>"parcela_historica","format"=>"text"),
                array("field"=>"Sector","format"=>"text"),
                array("field"=>"Manzana","format"=>"text"),
                array("field"=>"Parcela","format"=>"text"),
                array("field"=>"Secuencia","format"=>"text"), 
            );
            return parent::pdf($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }    
}

