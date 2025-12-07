<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Cliente extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }
    public function brow($values){
        try {

            $values["view"] = "vw_Cliente";
            if ($values["where"] == "") {
                $values["top"] = 10;
                $values["where"] = "id_cliente<0";
            }
            //$values["order"]="id_inhumado desc";

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
                array("field"=>"cliente","format"=>"text"),  // Cliente
                array("field"=>"NumeroCliente","format"=>"text"),
                array("field"=>"pagador","format"=>"text"),  // Pagador
                array("field"=>"deuda","format"=>"text"),
                array("field"=>"parcela","format"=>"text"),                
                array("field"=>"domicilio","format"=>"text"),                
                array("field"=>"TipoDocumento","format"=>"text"),                
                array("field"=>"parcela_historica","format"=>"text"),                
                array("field"=>"FechaVtoArrendamiento","format"=>"text"),                
                array("field"=>"FechaVtoConservacion","format"=>"text"),
                array("field" => "ImporteDeuda", "format" => "text")
            );
   
            // Controles para los filtros?
            $values["controls"]=array(
                "<label>".lang('p_NumeroCliente')."</label><input type='text' id='browser_NumeroCliente' name='browser_NumeroCliente' class='form-control number'/>",
                "<label>".lang('p_cliente')."/Email</label><input type='text' id='browser_cliente' name='browser_pagador' class='form-control text'/>",
                "<label>".lang('p_pagador')."</label><input type='text' id='browser_pagador' name='browser_pagador' class='form-control text'/>",
                "<label>Gestión externa</label><select id='browser_gestionExterna' name='browser_gestionExterna' class='form-control'><option selected value=''>[Todos]</option><option value='S'>Solo en gestión</option><option value='N'>Sin gestión</option></select>",
            );

            // Filtros y search. Confirmar si funciona como AND o como OR
            $values["filters"]=array(
                array("name" => "browser_gestionExterna", "operator" => "=", "fields" => array("gestion_externa")),
                array("name" => "browser_NumeroCliente", "operator" => "=", "fields" => array("NumeroCliente")),
                array("name"=>"browser_cliente", "operator"=>"like","fields"=>array("cliente","DomicilioEntreCalles")),
                array("name"=>"browser_pagador", "operator"=>"like","fields"=>array("pagador")),
                array("name"=>"browser_search", "operator"=>"like","fields"=>array("NumeroCliente","cliente","pagador")),                
            );  
   
            return parent::brow($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }

    public function libro($values){
        try {

            $values["view"]="vw_listado_impresion_libro";
            $values["pagesize"]=-1;  // esto es para evitar la paginacion que termina apuntando a table en lugar de view, y es en view donde tengo la vista de libro
            $values["records"]=$this->get($values);

            $values["getters"]=array(
                "search"=>true,
                //"googlesearch"=>true,
                "excel"=>true,
                "pdf"=>true,
            );
   
            $values["buttons"]=array(
                "new"=>false,
                "edit"=>false,
                "delete"=>false,
                "offline"=>false,
            );

            $values["columns"]=array(
                array("field"=>"fecha","format"=>"text"),
                array("field"=>"numerocliente","format"=>"text"),
                array("field"=>"razonsocial","format"=>"text"),
                array("field"=>"parcelas","format"=>"text"),
                array("field"=>"fechaVENCEARR","format"=>"text"),                
                array("field"=>"fechaVENCECON","format"=>"text"),                
            );
   
            // Controles para los filtros?
            $values["controls"]=array(
                "<label>".lang('p_NumeroCliente')."</label><input type='text' id='browser_NumeroCliente' name='browser_NumeroCliente' class='form-control number'/>",
                "<label>".lang('p_cliente')."</label><input type='text' id='browser_cliente' name='browser_pagador' class='form-control text'/>",
                "<label>".lang('p_pagador')."</label><input type='text' id='browser_pagador' name='browser_pagador' class='form-control text'/>",
            );

            // Filtros y search. Confirmar si funciona como AND o como OR
            $values["filters"]=array(
                array("name"=>"browser_NumeroCliente", "operator"=>"=","fields"=>array("NumeroCliente")),
                array("name"=>"browser_cliente", "operator"=>"like","fields"=>array("cliente")),
                array("name"=>"browser_pagador", "operator"=>"like","fields"=>array("pagador")),
                array("name"=>"browser_search", "operator"=>"like","fields"=>array("NumeroCliente","cliente","pagador")),                
            );  
   
            return parent::brow($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }

    public function sincons($values){
        try {

            /*
            SELECT c.* 
            FROM dbo.vw_cliente as c 
            WHERE c.letra_parcela NOT IN ('L','R') 
            AND c.parcela IS NOT null 
            AND c.id_cliente NOT IN (SELECT vcc.id_cliente 
                                    FROM dbo.vw_CuentaCorriente as vcc 
                                    WHERE vcc.clase='C' 
                                    AND vcc.Fecha_Vencimiento>GETDATE() 
                                    AND vcc.id_Parcela=c.id_Parcela)  
            ORDER BY 2,1
            */
            $values["view"]="vw_cliente";
            $w="letra_parcela NOT IN ('L','R')";
            $w=$w . " " . "AND parcela IS NOT null"; 
            $w=$w . " " . "AND id_cliente NOT IN (SELECT vcc.id_cliente"; 
            $w=$w . " " . "FROM dbo.vw_CuentaCorriente as vcc"; 
            $w=$w . " " . "WHERE vcc.clase='C'"; 
            $w=$w . " " . "AND vcc.Fecha_Vencimiento>GETDATE()"; 
            $w=$w . " " . "AND vcc.id_Parcela=vw_cliente.id_Parcela)";

            $values["where"]=$w;                        
            $values["order"]="2, 1";

            //$values["pagesize"]=-1;  // esto es para evitar la paginacion que termina apuntando a table en lugar de view, y es en view donde tengo la vista de libro
            $values["records"]=$this->get($values);

            $values["getters"]=array(
                "search"=>true,
                //"googlesearch"=>true,
                "excel"=>true,
                "pdf"=>true,
            );
   
            $values["buttons"]=array(
                "new"=>false,
                "edit"=>false,
                "delete"=>false,
                "offline"=>false,
            );

            $values["columns"]=array(
                array("field"=>"Cliente","format"=>"text"),
                array("field"=>"NumeroCliente","format"=>"text"),
                array("field"=>"Pagador","format"=>"text"),
                array("field"=>"marca","format"=>"text"),
                array("field"=>"parcela_historica","format"=>"text"),                
                array("field"=>"tipo_parcela","format"=>"text"),   
                array("field"=>"britanico","format"=>"text"), 
                array("field"=>"sin_datos","format"=>"text"), 
                array("field"=>"s_servicio","format"=>"text"),              
            );
   
            // Controles para los filtros?
            $values["controls"]=array(
                "<label>".lang('p_NumeroCliente')."</label><input type='text' id='browser_NumeroCliente' name='browser_NumeroCliente' class='form-control number'/>",
                "<label>".lang('p_cliente')."</label><input type='text' id='browser_cliente' name='browser_pagador' class='form-control text'/>",
                "<label>".lang('p_pagador')."</label><input type='text' id='browser_pagador' name='browser_pagador' class='form-control text'/>",
            );

            // Filtros y search. Confirmar si funciona como AND o como OR
            $values["filters"]=array(
                array("name"=>"browser_NumeroCliente", "operator"=>"=","fields"=>array("NumeroCliente")),
                array("name"=>"browser_cliente", "operator"=>"like","fields"=>array("cliente")),
                array("name"=>"browser_pagador", "operator"=>"like","fields"=>array("pagador")),
                array("name"=>"browser_search", "operator"=>"like","fields"=>array("NumeroCliente","cliente","pagador")),                
            );  
   
            return parent::brow($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }

    public function sinarr($values){
        try {

            /*
            SELECT vw_cliente.* 
            FROM vw_cliente 
            WHERE vw_cliente.parcela IS NOT null 
            AND vw_cliente.id_cliente NOT IN (SELECT vcc.id_cliente 
                                    FROM dbo.vw_CuentaCorriente as vcc 
                                    WHERE vcc.clase='A' 
                                    AND vcc.Fecha_Vencimiento>GETDATE() 
                                    AND vcc.id_Parcela=vw_cliente.id_Parcela)  
            ORDER BY 2,1
            */
            $values["view"]="vw_cliente";

            $where="";
            $where .= "vw_cliente.parcela IS NOT null"; 
            $where .= "AND vw_cliente.id_cliente NOT IN (SELECT vcc.id_cliente "; 
            $where .= "                        FROM dbo.vw_CuentaCorriente as vcc "; 
            $where .= "                        WHERE vcc.clase='A' "; 
            $where .= "                        AND vcc.Fecha_Vencimiento>GETDATE() "; 
            $where .= "                        AND vcc.id_Parcela=vw_cliente.id_Parcela)";
            
            $values["where"]=$where;
            //$values["pagesize"]=-1;  // esto es para evitar la paginacion que termina apuntando a table en lugar de view, y es en view donde tengo la vista de libro
            $values["records"]=$this->get($values);

            $values["getters"]=array(
                "search"=>true,
                //"googlesearch"=>true,
                "excel"=>true,
                "pdf"=>true,
            );
   
            $values["buttons"]=array(
                "new"=>false,
                "edit"=>false,
                "delete"=>false,
                "offline"=>false,
            );

            $values["columns"]=array(
                array("field"=>"Cliente","format"=>"text"),
                array("field"=>"NumeroCliente","format"=>"text"),
                array("field"=>"Pagador","format"=>"text"),
                array("field"=>"marca","format"=>"text"),
                array("field"=>"parcela_historica","format"=>"text"),                
                array("field"=>"tipo_parcela","format"=>"text"),   
                array("field"=>"britanico","format"=>"text"), 
                array("field"=>"sin_datos","format"=>"text"), 
                array("field"=>"s_servicio","format"=>"text"),                
            );
   
            // Controles para los filtros?
            $values["controls"]=array(
                "<label>".lang('p_NumeroCliente')."</label><input type='text' id='browser_NumeroCliente' name='browser_NumeroCliente' class='form-control number'/>",
                "<label>".lang('p_cliente')."</label><input type='text' id='browser_cliente' name='browser_pagador' class='form-control text'/>",
                "<label>".lang('p_pagador')."</label><input type='text' id='browser_pagador' name='browser_pagador' class='form-control text'/>",
            );

            // Filtros y search. Confirmar si funciona como AND o como OR
            $values["filters"]=array(
                array("name"=>"browser_NumeroCliente", "operator"=>"=","fields"=>array("NumeroCliente")),
                array("name"=>"browser_cliente", "operator"=>"like","fields"=>array("cliente")),
                array("name"=>"browser_pagador", "operator"=>"like","fields"=>array("pagador")),
                array("name"=>"browser_search", "operator"=>"like","fields"=>array("NumeroCliente","cliente","pagador")),                
            );  
   
            return parent::brow($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }

    public function cocheria($values){
        try {

            $values["view"]="vw_cocherias_resuelto";
            //$values["order"]="id_inhumado desc";

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
                array("field"=>"Nombre_cocheria","format"=>"text"), 
                array("field"=>"FechaMovimiento","format"=>"text"),
                array("field"=>"numerocliente","format"=>"text"),               
            );
   
            // Controles para los filtros?
            $values["controls"]=array(
                "<label>".lang('p_NumeroCliente')."</label><input type='text' id='browser_NumeroCliente' name='browser_NumeroCliente' class='form-control number'/>",
                "<label>".lang('p_cliente')."</label><input type='text' id='browser_cliente' name='browser_pagador' class='form-control text'/>",
                "<label>".lang('p_pagador')."</label><input type='text' id='browser_pagador' name='browser_pagador' class='form-control text'/>",
            );

            // Filtros y search. Confirmar si funciona como AND o como OR
            $values["filters"]=array(
                array("name"=>"browser_NumeroCliente", "operator"=>"=","fields"=>array("NumeroCliente")),
                array("name"=>"browser_cliente", "operator"=>"like","fields"=>array("cliente")),
                array("name"=>"browser_pagador", "operator"=>"like","fields"=>array("pagador")),
                array("name"=>"browser_search", "operator"=>"like","fields"=>array("NumeroCliente","cliente","pagador")),                
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
            
            $values["table"]="vw_Cliente";
            $values["view"]="vw_Cliente";
            $values["where"]=("id=".$values["id"]);
            $values["records"]=$this->get($values);

            // CategoriaIva: id_CategoriaIva y Nombre
            $parameters_id_CategoriaIva=array(
                "model"=>(MOD_BRITANICO."/CategoriaIva"),
                "table"=>"CategoriaIva",
                "name"=>"id_CategoriaIva", // --> Nombre del combo  
                "class"=>"form-control dbase",
                "empty"=>true,
                "id_actual"=>secureComboPosition($values["records"],"id_CategoriaIva"), //nombre de la columna en la tabla externa
                "id_field"=>"id_CategoriaIva", // nombre de la pk de la tabla del combo
                "description_field"=>"Nombre",
                "get"=>array("order"=>"Nombre ASC","pagesize"=>-1),
            );
            // vw_TipoDocumento: id y Descripcion
            $parameters_id_TipoDocumento=array(
                "model"=>(MOD_BRITANICO."/vw_TipoDocumento"),
                "table"=>"vw_TipoDocumento",
                "name"=>"id_TipoDocumento",  
                "class"=>"form-control dbase",
                //"empty"=>true,
                "empty"=>false,
                "id_actual"=>secureComboPosition($values["records"],"id_TipoDocumento"),
                "id_field"=>"id",
                "description_field"=>"Descripcion",
                "get"=>array("order"=>"Descripcion ASC","pagesize"=>-1),
            );
            // vw_PaisCombo: id y descripcion
            $parameters_id_PaisNacionalidad=array(
                "model"=>(MOD_BRITANICO."/vw_PaisCombo"),
                "table"=>"vw_PaisCombo",
                "name"=>"id_PaisNacionalidad",  // aca va el codigo
                "class"=>"form-control dbase",
                "empty"=>true,
                "id_actual"=>secureComboPosition($values["records"],"id_PaisNacionalidad"),
                "id_field"=>"id",
                "description_field"=>"descripcion",
                "get"=>array("order"=>"descripcion ASC","pagesize"=>-1),
            );
            // Provincia: id_Provincia y NOMBRE
            $parameters_Domicilio_id_provincia=array(
                "model"=>(MOD_BRITANICO."/Provincia"),
                "table"=>"Provincia",
                "name"=>"Domicilio_id_provincia",  // aca va el codigo
                "class"=>"form-control dbase",
                "empty"=>true,
                "id_actual"=>secureComboPosition($values["records"],"Domicilio_id_provincia"),
                "id_field"=>"id_Provincia",
                "description_field"=>"NOMBRE",
                "get"=>array("order"=>"NOMBRE ASC","pagesize"=>-1),
            );
            $parameters_id_tipo_notas=array(
                "model"=>(MOD_BRITANICO."/Tipo_notas"),
                "table"=>"tipo_notas",
                "name"=>"id_tipo_notas",  // aca va el codigo
                "class"=>"form-control dbase",
                "empty"=>true,
                "id_actual"=>secureComboPosition($values["records"],"id_tipo_nota"),
                "id_field"=>"id",
                "description_field"=>"descripcion",
                "get"=>array("order"=>"id DESC","pagesize"=>-1),
            );

            $values["controls"]=array(
                "id_CategoriaIva"=>getCombo($parameters_id_CategoriaIva,$this),
                "id_TipoDocumento"=>getCombo($parameters_id_TipoDocumento,$this),
                "id_PaisNacionalidad"=>getCombo($parameters_id_PaisNacionalidad,$this),
                "Domicilio_id_provincia"=>getCombo($parameters_Domicilio_id_provincia,$this),
                "id_tipo_notas"=>getCombo($parameters_id_tipo_notas,$this),
            );
            
            $registro = $values["records"]["data"][0];

            $sqlNotasAsociadasAlCliente = "select top 5 * from dbo.vw_notas where id_cliente = ? order by fecha_alta desc;"; 
            $prms=array(
                "id_cliente" => $registro["id_cliente"],
            );            
            $rc = $this->execAdHocWithParms($sqlNotasAsociadasAlCliente, $prms);

            if (!$rc) {
                // si dio false estoy en problemas.....hacer un throw o raise...
                $mierror = $this->db->error();
                throw new Exception($mierror['message'], $mierror['code']);

            } else {
                $notasAsociadasAlCliente = $rc->result_array();
                $values["notasAsociadasAlCliente"]=$notasAsociadasAlCliente;
            }

            $sqlRecuperarHistoricoTitularidad = "select * from dbo.cliente_historico where id_cliente_parent= ? order by fecha_historico DESC;"; 
            $prms=array(
                "id_cliente" => $registro["id_cliente"],
            );  
            $rc = $this->execAdHocWithParms($sqlRecuperarHistoricoTitularidad, $prms);

            if (!$rc) {
                // si dio false estoy en problemas.....hacer un throw o raise...
                $mierror = $this->db->error();
                throw new Exception($mierror['message'], $mierror['code']);

            } else {
                $historicoTitularidad = $rc->result_array();
                $values["historicoTitularidad"] = $historicoTitularidad;
            }

            $sqlPermisosAdicionalesModificarMarcaSinDatos = "exec obtenerPermisosAdicionales ?, ?;";
            $prmsPermisosAdicionalesModificarMarcaSinDatos=array(
                "userId" => $values["id_user_active"],
                "perm" => "marca_sin_datos"
            );                     
            $rc = $this->execAdHocWithParms($sqlPermisosAdicionalesModificarMarcaSinDatos, $prmsPermisosAdicionalesModificarMarcaSinDatos);
            if (!$rc) {
                // si dio false estoy en problemas.....hacer un throw o raise...
                $mierror = $this->db->error();
                throw new Exception($mierror['message'], $mierror['code']);

            } else {
                $permisosAdicionalesModificarMarcaSinDatos = $rc->result_array();
            }    
            $permite = $this->trueFalseFromSN($permisosAdicionalesModificarMarcaSinDatos[0]["permitido"]);

            if ($permite) {
                //$data["parameters"]["permiteGenerar"] = "S";  
                $values["permisosAdicionalesModificarMarcaSinDatos"] = "S";  
            } else {
                //$data["parameters"]["permiteGenerar"] = "N";     
                $values["permisosAdicionalesModificarMarcaSinDatos"] = "N";               
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
            $values["table"]="vw_cliente";
            $values["view"]="vw_cliente";
            
            if (!isset($values["id"])){$values["id"]=0;}
            $id=(int)$values["id"];
            $storedp="";
            $param="";
            if($id==0){
                // Por insert
                if($fields==null) {
                    $storedp = "dbo.coop_Cliente_Insert ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?";
                    $p = array(
                        'id_Cliente' => (int)$values["id"],
                        'NumeroCliente' => $values["NumeroCliente"],
                        'RazonSocial' => clean($values["RazonSocial"]),
                        'DomicilioCalle' => $values["DomicilioCalle"],
                        'DomicilioNumero' => $values["DomicilioNumero"],
                        'DomicilioPiso' => $values["DomicilioPiso"],
                        'DomicilioDepartamento' => $values["DomicilioDepartamento"],
                        'DomicilioEntreCalles' => $values["DomicilioEntreCalles"],
                        'Domicilio_id_provincia' => $values["Domicilio_id_provincia"],
                        'Localidad' => $values["Localidad"],
                        'CodigoPostal' => $values["CodigoPostal"],
                        'Telefono1' => $values["Telefono1"],
                        'Telefono2' => $values["Telefono2"],
                        'Cuit' => $values["Cuit"],
                        'id_CategoriaIva' => secureEmptyNull($values,"id_CategoriaIva"),
                        'ID_CuentaContable' => secureEmptyNull($values,"ID_CuentaContable"),
                        'id_TipoDocumento' => secureEmptyNull($values,"id_TipoDocumento"),
                        'NumeroDocumento' => $values["NumeroDocumento"],
                        'id_PaisNacionalidad' => secureEmptyNull($values,"id_PaisNacionalidad"),
                        'RecibeAviso' => $values["RecibeAviso"],
                        'CeroDevengamiento' => $values["CeroDevengamiento"],
                        'RevisadoGerencia' => $values["RevisadoGerencia"],
                        'observaciones' => $values["observaciones"],
                    );
                    if ($p["id_PaisNacionalidad"]==null || $p["id_PaisNacionalidad"]==0) {$p["id_PaisNacionalidad"]=1;}
                    if ( $p["id_CategoriaIva"] == null ) {$p["id_CategoriaIva"] = 3;}
                    if ($p["ID_CuentaContable"] != null ) {$p["ID_CuentaContable"] = null;} 
                    $p["RecibeAviso"] = "S";
                    $p["CeroDevengamiento"] = "N";
                    if ( $p["RevisadoGerencia"] == "") {$p["RevisadoGerencia"] = null;}
                    $p["Cuit"] = null;

                    $fields = array(  
                        'id_Cliente' => $values["id"],
                        'NumeroCliente' => $values["NumeroCliente"],
                        'RazonSocial' => clean($values["RazonSocial"]),
                        'DomicilioCalle' => $values["DomicilioCalle"],
                        'DomicilioNumero' => $values["DomicilioNumero"],
                        'DomicilioPiso' => $values["DomicilioPiso"],
                        'DomicilioDepartamento' => $values["DomicilioDepartamento"],
                        'DomicilioEntreCalles' => $values["DomicilioEntreCalles"],
                        'Domicilio_id_provincia' => $values["Domicilio_id_provincia"],
                        'Localidad' => $values["Localidad"],
                        'CodigoPostal' => $values["CodigoPostal"],
                        'Telefono1' => $values["Telefono1"],
                        'Telefono2' => $values["Telefono2"],
                        'Cuit' => $values["Cuit"],
                        'id_CategoriaIva' => secureEmptyNull($values,"id_CategoriaIva"),
                        'ID_CuentaContable' => secureEmptyNull($values,"ID_CuentaContable"),
                        'id_TipoDocumento' => secureEmptyNull($values,"id_TipoDocumento"),
                        'NumeroDocumento' => $values["NumeroDocumento"],
                        'id_PaisNacionalidad' => secureEmptyNull($values,"id_PaisNacionalidad"),
                        'RecibeAviso' => $values["RecibeAviso"],
                        'CeroDevengamiento' => $values["CeroDevengamiento"],
                        'RevisadoGerencia' => $values["RevisadoGerencia"],
                        'observaciones' => $values["observaciones"],              
                    );
                }
                $x =  parent::saveExtended($values,$fields, $forcedTable=null, $forcedSp=$storedp, $prm=$p, $keyName="id_cliente");
                $values["view"] = "Cliente";
               // $values["where"] = ("id_cliente=" . $x["id"]);
                $values["where"] = ("id_cliente=(select max(id_cliente) from cliente )");//.$x["id"]);
                $values["records"] = $this->get($values);
                $x["customMensaje"] = "Se ha grabado el Cliente con el número " . $values["records"]["data"][0]["NumeroCliente"];
                return $x;
            } else {
                // Por update
                if($fields==null) {
                    // CCOO

                    $storedp = "dbo.coop_Cliente_Update ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?";

                    $p = array(
                        //'id_Cliente' => $values["id_Cliente"],
                        'id_Cliente' => $values["id"],
                        'NumeroCliente' => $values["NumeroCliente"],
                        'RazonSocial' => clean($values["RazonSocial"]),
                        'DomicilioCalle' => $values["DomicilioCalle"],
                        'DomicilioNumero' => $values["DomicilioNumero"],
                        'DomicilioPiso' => $values["DomicilioPiso"],
                        'DomicilioDepartamento' => $values["DomicilioDepartamento"],
                        'DomicilioEntreCalles' => $values["DomicilioEntreCalles"],
                        'Domicilio_id_provincia' => $values["Domicilio_id_provincia"],
                        'Localidad' => $values["Localidad"],
                        'CodigoPostal' => $values["CodigoPostal"],
                        'Telefono1' => $values["Telefono1"],
                        'Telefono2' => $values["Telefono2"],
                        'Cuit' => $values["Cuit"],
                        'id_CategoriaIva' => secureEmptyNull($values,"id_CategoriaIva"),
                        'ID_CuentaContable' => secureEmptyNull($values,"ID_CuentaContable"),
                        'id_TipoDocumento' => secureEmptyNull($values,"id_TipoDocumento"),
                        'NumeroDocumento' => $values["NumeroDocumento"],
                        'id_PaisNacionalidad' => secureEmptyNull($values,"id_PaisNacionalidad"),
                        'RecibeAviso' => $values["RecibeAviso"],
                        'CeroDevengamiento' => $values["CeroDevengamiento"],
                        'RevisadoGerencia' => $values["RevisadoGerencia"],
                        'observaciones' => $values["observaciones"],
                        'gestion_externa' => $values["gestion_externa"],
                    );

                    if ($p["id_PaisNacionalidad"]==null || $p["id_PaisNacionalidad"]==0) {
                        $p["id_PaisNacionalidad"]=1;
                    }
                    if ( $p["id_CategoriaIva"] == null ) {
                        $p["id_CategoriaIva"] = 3;
                    }
                    if ($p["ID_CuentaContable"] != null ) {
                        $p["ID_CuentaContable"] = null;
                    } 
                    $p["RecibeAviso"] = "S";
                    $p["CeroDevengamiento"] = "N";
                    //$p["RevisadoGerencia"] = "N";
                    if ( $p["RevisadoGerencia"] == "") {
                        $p["RevisadoGerencia"] = null;
                        // Los valores posibles son (segun el legacy):
                        // null, viene de los que no tienen permisos cuando hacen el alta.
                        // S o N viene de los que tienen permisos cuando hacen el alta
                        // en el update, los que no tienen permisos la marca no se cambia
                        // en el update, los que tienen permisos pasan a S o N indefectiblemente
                    }
                    $p["Cuit"] = null;
                    
                    $fields = array(
                        'id_Cliente' => $values["id"],
                        'NumeroCliente' => $values["NumeroCliente"],
                        'RazonSocial' => clean($values["RazonSocial"]),
                        'DomicilioCalle' => $values["DomicilioCalle"],
                        'DomicilioNumero' => $values["DomicilioNumero"],
                        'DomicilioPiso' => $values["DomicilioPiso"],
                        'DomicilioDepartamento' => $values["DomicilioDepartamento"],
                        'DomicilioEntreCalles' => $values["DomicilioEntreCalles"],
                        'Domicilio_id_provincia' => $values["Domicilio_id_provincia"],
                        'Localidad' => $values["Localidad"],
                        'CodigoPostal' => $values["CodigoPostal"],
                        'Telefono1' => $values["Telefono1"],
                        'Telefono2' => $values["Telefono2"],
                        'Cuit' => $values["Cuit"],
                        'id_CategoriaIva' => secureEmptyNull($values,"id_CategoriaIva"),
                        'ID_CuentaContable' => secureEmptyNull($values,"ID_CuentaContable"),
                        'id_TipoDocumento' => secureEmptyNull($values,"id_TipoDocumento"),
                        'NumeroDocumento' => $values["NumeroDocumento"],
                        'id_PaisNacionalidad' => secureEmptyNull($values,"id_PaisNacionalidad"),
                        'RecibeAviso' => $values["RecibeAviso"],
                        'CeroDevengamiento' => $values["CeroDevengamiento"],
                        'RevisadoGerencia' => $values["RevisadoGerencia"],
                        'observaciones' => $values["observaciones"],
                        'gestion_externa' => $values["gestion_externa"],
                    );
                }
                $x =  parent::saveExtended($values,$fields, $forcedTable=null, $forcedSp=$storedp, $prm=$p, $keyName="id_cliente");
                return $x;
            }
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }

    public function excel($values){
        try {
            if ($values["where"]!=""){$values["where"]=base64_decode($values["where"]);}
            $values["view"]="vw_Cliente";
            $values["delimiter"]=";";
            $values["pagesize"]=-1;
            //$values["order"]=" NUMERO ASC";
            $values["records"]=$this->get($values);

            $values["columns"]=array(
                array("field"=>"cliente","format"=>"text"),  // Cliente
                array("field"=>"NumeroCliente","format"=>"text"),
                array("field"=>"pagador","format"=>"text"),  // Pagador
                array("field"=>"deuda","format"=>"text"),
                array("field"=>"parcela","format"=>"text"),                
                array("field"=>"domicilio","format"=>"text"),                
                array("field"=>"TipoDocumento","format"=>"text"),                
                array("field"=>"parcela_historica","format"=>"text"),                
                array("field"=>"FechaVtoArrendamiento","format"=>"text"),                
                array("field"=>"FechaVtoConservacion","format"=>"text"),                
                array("field"=>"ImporteDeuda","format"=>"text"),                
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
            $values["view"]="vw_Cliente";
            $values["pagesize"]=-1;
            //$values["order"]=" NUMERO ASC";
            $values["records"]=$this->get($values);
            
            $values["title"]="Clientes";

            $values["columns"]=array(
                array("field"=>"cliente","format"=>"text"),  // Cliente
                array("field"=>"NumeroCliente","format"=>"text"),
                array("field"=>"pagador","format"=>"text"),  // Pagador
                array("field"=>"deuda","format"=>"text"),
                array("field"=>"parcela","format"=>"text"),                
                array("field"=>"domicilio","format"=>"text"),                
                array("field"=>"TipoDocumento","format"=>"text"),                
                array("field"=>"parcela_historica","format"=>"text"),                
                array("field"=>"FechaVtoArrendamiento","format"=>"text"),                
                array("field"=>"FechaVtoConservacion","format"=>"text"),                
                array("field"=>"ImporteDeuda","format"=>"text"),                
            );

            return parent::pdf($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function insertarNotaEnCliente ($values){
        try {

            $sqlInsertarNotaEnCliente = "insert into dbo.notas (nota, fecha_alta, id_tipo_nota, id_cliente, id_usuario) " . 
                                        "values (?, getdate(), ?, ?, ?);";

            $sqlRecuperarNotaCliente = "select * from dbo.vw_notas where id = ?;"; // aca me traigo la ultima nota, la que acabo de agregar.

            $prms=array(
                "nota" => $values["nota"],
                "id_tipo_nota" => $values["id_tipo_nota"],
                "id_cliente" => $values["id_cliente"],
                "id_usuario" => $values["id_user_active"],
                "fecha_alta" => date('Y-m-d H:i:s'),
            );  
    
            $this->db->insert('notas',$prms);
            $last_id = $this->db->insert_id();            

            $rc = $this->execAdHocWithParms($sqlRecuperarNotaCliente, $last_id);

            if (!$rc) {
                // si dio false estoy en problemas.....hacer un throw o raise...
                $mierror = $this->db->error();
                throw new Exception($mierror['message'], $mierror['code']);

            } else {
                $notaAgregada = $rc->result_array();
                $values["notaAgregadaCliente"] = $notaAgregada;
            }
            

            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>"Records",
                "notaAgregadaCliente"=>$values["notaAgregadaCliente"][0],
                "table"=>$this->table,
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT)
            );
        } catch (Exception $e) {
            return logError($e,"Excepcion en metodo:".__METHOD__. " * namespace:" . __NAMESPACE__ . 
                                    " * clase:" . __CLASS__ . " * funcion:". __FUNCTION__ 
                                    . " * directorio:" .  __DIR__ . " * archivo:" . __FILE__ );
        }
    }
    public function getObservacionesCliente ($values){
        try {


            $sqlRecuperarObservacionesCliente = "select c.id_cliente, c.observaciones from dbo.Cliente c where c.id_cliente = ?;";

            $prms=array(
                "id_cliente" => $values["id_cliente"],
            );  
    
            $rc = $this->execAdHocWithParms($sqlRecuperarObservacionesCliente, $values["id_cliente"]);

            if (!$rc) {
                // si dio false estoy en problemas.....hacer un throw o raise...
                $mierror = $this->db->error();
                throw new Exception($mierror['message'], $mierror['code']);

            } else {
                $observacion = $rc->result_array();
                $values["observacionesCliente"] = $observacion;
            }            

            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>"Records",
                "observacionesCliente"=>$values["observacionesCliente"][0],
                "table"=>$this->table,
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT)
            );
        } catch (Exception $e) {
            return logError($e,"Excepcion en metodo:".__METHOD__. " * namespace:" . __NAMESPACE__ . 
                                    " * clase:" . __CLASS__ . " * funcion:". __FUNCTION__ 
                                    . " * directorio:" .  __DIR__ . " * archivo:" . __FILE__ );
        }
    }    
    public function actualizarObservacionesCliente ($values){
        try {

            $sqlActualizarObservacionesCliente = "UPDATE dbo.Cliente SET observaciones=? WHERE id_cliente = ?;";

            $sqlRecuperarObservacionesCliente = "select c.id_cliente, c.observaciones from dbo.Cliente c where c.id_cliente = ?;";

            // esto iba por posicion, como hago update() el primer parametro deberia ser la pk...
            $prms=array(
                "id_cliente" => $values["id_cliente"],
                "observaciones" => $values["observaciones"],                
            );  
    
            // esto iba por posicion
            //$prms=array(
            //    "observaciones" => $values["observaciones"],                
            //    "id_cliente" => $values["id_cliente"],
            //);  

            //$this->db->update('dbo.Cliente',$prms);


            //$rc = $this->execAdHocWithParms($sqlActualizarObservacionesCliente, $prms);
            $rc = $this->db->update("Cliente", array("observaciones"=>$values["observaciones"]), array("id_cliente" => $values["id_cliente"]));

            if (!$rc) {
                // si dio false estoy en problemas.....hacer un throw o raise...
                $mierror = $this->db->error();
                throw new Exception($mierror['message'], $mierror['code']);

            } else {
                $r = $this->db->affected_rows();
            }

            // esto iba por posicion
            $prms=array(
                "id_cliente" => $values["id_cliente"],
            );  
            $rc = $this->execAdHocWithParms($sqlRecuperarObservacionesCliente, $values["id_cliente"]);

            if (!$rc) {
                // si dio false estoy en problemas.....hacer un throw o raise...
                $mierror = $this->db->error();
                throw new Exception($mierror['message'], $mierror['code']);

            } else {
                $actualizacionObservacion = $rc->result_array();
                $values["observacionesCliente"] = $actualizacionObservacion;
            }            

            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>"Records",
                "observacionesCliente"=>$values["observacionesCliente"][0],
                "table"=>$this->table,
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT)
            );
        } catch (Exception $e) {
            return logError($e,"Excepcion en metodo:".__METHOD__. " * namespace:" . __NAMESPACE__ . 
                                    " * clase:" . __CLASS__ . " * funcion:". __FUNCTION__ 
                                    . " * directorio:" .  __DIR__ . " * archivo:" . __FILE__ );
        }
    }    
    public function verHistoricoTitularidad ($values){
        try {

            $sqlRecuperarHistoricoTitularidad = "SELECT * FROM dbo.cliente_historico WHERE id_cliente_parent= ? ORDER BY fecha_historico DESC;"; 
            $prms=array(
                "id_cliente" => $values["id_cliente"],
            );  
            $rc = $this->execAdHocWithParms($sqlRecuperarHistoricoTitularidad, $prms);

            if (!$rc) {
                // si dio false estoy en problemas.....hacer un throw o raise...
                $mierror = $this->db->error();
                throw new Exception($mierror['message'], $mierror['code']);

            } else {
                $historicoTitularidad = $rc->result_array();
                $values["historicoTitularidad"] = $historicoTitularidad;
            }
            
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>"Records",
                "notaAgregadaCliente"=>$values["historicoTitularidad"],
                "table"=>$this->table,
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT)
            );
        } catch (Exception $e) {
            return logError($e,"Excepcion en metodo:".__METHOD__. " * namespace:" . __NAMESPACE__ . 
                                    " * clase:" . __CLASS__ . " * funcion:". __FUNCTION__ 
                                    . " * directorio:" .  __DIR__ . " * archivo:" . __FILE__ );
        }
    }  
    public function transferirTitularidad ($values){
        try {

            $sqlTransferirTitularidad = "spTransferirTitularidad ?;";

            $prms=array(
                "id_cliente" => $values["id_cliente"],
            );  

            $rc = $this->execAdHocWithParms($sqlTransferirTitularidad, $prms);

            if (!$rc) {
                // si dio false estoy en problemas.....hacer un throw o raise...
                $mierror = $this->db->error();
                throw new Exception($mierror['message'], $mierror['code']);

            } else {
                $transferenciaTitularidad = $rc->result_array();
                $values["transferenciaTitularidad"] = $transferenciaTitularidad;
            }
            
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>"Records",
                "transferenciaTitularidad"=>$values["transferenciaTitularidad"],
                "table"=>$this->table,
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT)
            );
        } catch (Exception $e) {
            return logError($e,"Excepcion en metodo:".__METHOD__. " * namespace:" . __NAMESPACE__ . 
                                    " * clase:" . __CLASS__ . " * funcion:". __FUNCTION__ 
                                    . " * directorio:" .  __DIR__ . " * archivo:" . __FILE__ );
        }
    }    
    public function getCuentaCorriente ($values){
        try {
            
            $sqlPermisosAdicionales = "exec obtenerPermisosAdicionales ?, ?;";
            //"abm_modificacion_itemctacte"
            //"abm_borrado_itemctacte"
            $prmsPermisosAdicionales=array(
                "userId" => $values["id_user_active"],
                "perm" => "abm_modificacion_itemctacte"
            );                     
            $rc = $this->execAdHocWithParms($sqlPermisosAdicionales, $prmsPermisosAdicionales);
            if (!$rc) {
                // si dio false estoy en problemas.....hacer un throw o raise...
                $mierror = $this->db->error();
                throw new Exception($mierror['message'], $mierror['code']);

            } else {
                $permisosAdicionalesUpd = $rc->result_array();
            }  

            $prmsPermisosAdicionales=array(
                "userId" => $values["id_user_active"],
                "perm" => "abm_borrado_itemctacte"
            );                     
            $rc = $this->execAdHocWithParms($sqlPermisosAdicionales, $prmsPermisosAdicionales);
            if (!$rc) {
                // si dio false estoy en problemas.....hacer un throw o raise...
                $mierror = $this->db->error();
                throw new Exception($mierror['message'], $mierror['code']);

            } else {
                $permisosAdicionalesDel = $rc->result_array();
            }  

            /*--------------------- Cuenta Corriente Full ------------------------------------------*/

            $sqlCuentaCorriente =   " SELECT (select max(estado) " .
                                            " from vw_desenganche_parcela " . 
                                            " where id_cliente=cc.id_cliente " . 
                                            " and id_Parcela=cc.id_Parcela " . 
                                            " AND id_cliente NOT IN (SELECT id_cliente " . 
                                                                    " FROM dbo.parcela)) as estado_desenganche,  " .
                                            " cc.comprobante,  " .
                                            " cc.id_tipo_comprobante,  " .
                                            " cc.Fecha_Emision,  " .
                                            " cc.Fecha_Vencimiento,  " .
                                            " cc.Importe,  " .
                                            " cc.Saldo,  " .
                                            " cc.codigoListaDePrecios as SEC, " .
                                            " cc.parcelaReducido, " .
                                            " cc.nombrePrecio, " .
                                            " cc.DescripcionMovCC, " .
                                            " cc.Meses, " .
                                            " cc.recibos, " .
                                            " cc.plan_pago, " .
                                            " cc.tipo_plan, " .
                                            " cc.ID_CuentaCorriente " .
                                    " FROM dbo.vw_CuentaCorriente as cc " . 
                                    " WHERE cc.id_tipo_comprobante!=24 AND cc.estado='A' " . 
                                    " AND cc.id_cliente=? " .
                                    " ORDER BY cc.Fecha_Vencimiento, cc.codigoTipoComprobante, cc.LetraCbte, " . 
                                    " cc.Nro_SucMovimiento, cc.Nro_Movimiento, cc.NroLineaItem; ";

            $prms=array(
                "id_cliente" => $values["id_cliente"],
            );  

            $rc = $this->execAdHocWithParms($sqlCuentaCorriente, $prms);

            if (!$rc) {
                // si dio false estoy en problemas.....hacer un throw o raise...
                $mierror = $this->db->error();
                throw new Exception($mierror['message'], $mierror['code']);

            } else {
                $cuentaCorriente = $rc->result_array();
                $values["cuentaCorriente"] = $cuentaCorriente;
            }

            /*------------------------ Cuenta Corriente - Conceptos Impagos ------------------------------*/

            $sqlCuentaCorrienteImpago = " SELECT (select max(estado) " .
                                                " from vw_desenganche_parcela " . 
                                                " where id_cliente=cc.id_cliente " . 
                                                " and id_Parcela=cc.id_Parcela " . 
                                                " AND id_cliente NOT IN (SELECT id_cliente " . 
                                                                        " FROM dbo.parcela)) as estado_desenganche,  " .
                                                " cc.comprobante,  " .
                                                " cc.Fecha_Emision,  " .
                                                " cc.Fecha_Vencimiento,  " .
                                                " cc.Importe,  " .
                                                " cc.Saldo,  " .
                                                " cc.codigoListaDePrecios as SEC, " .
                                                " cc.parcelaReducido, " .
                                                " cc.DescripcionMovCC, " .
                                                " cc.Meses, " .
                                                " cc.recibos, " .
                                                " cc.plan_pago, " .
                                                " cc.tipo_plan, " .
                                                " cc.ID_CuentaCorriente, cc.Nro_Movimiento " .
                                        " FROM dbo.vw_CuentaCorriente as cc " . 
                                        " WHERE cc.id_tipo_comprobante!=24 AND cc.estado='A' " . 
                                        " AND cc.id_cliente = ? " .
                                        " AND cc.saldo <> 0 " .
                                        " ORDER BY cc.Fecha_Vencimiento, cc.codigoTipoComprobante, cc.LetraCbte, " . 
                                        " cc.Nro_SucMovimiento, cc.Nro_Movimiento, cc.NroLineaItem; ";

            $prms=array(
                "id_cliente" => $values["id_cliente"],
            );  

            $rc = $this->execAdHocWithParms($sqlCuentaCorrienteImpago, $prms);

            if (!$rc) {
                // si dio false estoy en problemas.....hacer un throw o raise...
                $mierror = $this->db->error();
                throw new Exception($mierror['message'], $mierror['code']);

            } else {
                $cuentaCorrienteImpago = $rc->result_array();
                $values["cuentaCorrienteImpago"] = $cuentaCorrienteImpago;
            }

            /*----------------------------- Valos total Impago ---------------------------------------*/

            $sqlCuentaCorrienteImpagoValor = " SELECT sum(cc.Saldo) as totalAdeudado " .
                                             " FROM dbo.vw_CuentaCorriente as cc " .
                                             " WHERE id_tipo_comprobante!=24 AND estado='A' " .
                                             " AND id_cliente=? " .
                                             " and cc.Saldo <> 0;";
            

            $prms=array(
                "id_cliente" => $values["id_cliente"],
            );  

            $rc = $this->execAdHocWithParms($sqlCuentaCorrienteImpagoValor, $prms);

            if (!$rc) {
                // si dio false estoy en problemas.....hacer un throw o raise...
                $mierror = $this->db->error();
                throw new Exception($mierror['message'], $mierror['code']);

            } else {
                $cuentaCorrienteImpagoValor = $rc->result_array();
                $values["cuentaCorrienteImpagoValor"] = $cuentaCorrienteImpagoValor;
            }

            /*--------------------------------- Resumen De Deuda ----------------------------------*/

            $sqlResumenDeDeuda =    " SELECT ID_CuentaCorriente, Nro_Movimiento, c.id_cliente, c.id_pagador, c.pagador, " .
                                    " c.domicilio, c.CodigoPostal, c.Localidad, " .  
                                    " c.numerocliente, c.cliente, c.Parcela, c.parcelaReducido, " .
                                    " c.Fecha_Emision, c.Fecha_Vencimiento, " . 
                                    " c.comprobante, c.Importe, c.Saldo " .
                                    " FROM dbo.vw_CuentaCorriente c " .
                                    " WHERE id_cliente= ?  " .
                                    " AND estado='A' " . 
                                    " AND saldo>0 and id_tipo_comprobante!=24 " . 
                                    " ORDER BY fecha_vencimiento DESC; ";

            $prms=array(
                "id_cliente" => $values["id_cliente"],
            );

            log_message("error", "RELATED ".json_encode($sqlResumenDeDeuda,JSON_PRETTY_PRINT));


            $rc = $this->execAdHocWithParms($sqlResumenDeDeuda, $prms);

            if (!$rc) {
                // si dio false estoy en problemas.....hacer un throw o raise...
                $mierror = $this->db->error();
                throw new Exception($mierror['message'], $mierror['code']);

            } else {
                $resumenDeDeuda = $rc->result_array();
                $values["resumenDeDeuda"] = $resumenDeDeuda;
            }

            /*------------------------ Tipos de Comprobantes para recibo -----------------------------*/

            $sqlComprobantesProv =  " SELECT id_tipo_comprobante,DescTipos_Comprobantes,CodigoComprobante " .
                                    " FROM dbo.tipo_comprobante " .
                                    " WHERE BL_CompProv='S' " .
                                    " AND id_tipo_comprobante!=17 " . 
                                    " ORDER BY DescTipos_Comprobantes; ";
                        
            $rc = $this->execAdHoc($sqlComprobantesProv);

            if (!$rc) {
                // si dio false estoy en problemas.....hacer un throw o raise...
                $mierror = $this->db->error();
                throw new Exception($mierror['message'], $mierror['code']);

            } else {
                $tiposDeComprobanteProv = $rc->result_array();
                $values["tiposDeComprobanteProv"] = $tiposDeComprobanteProv;
            }

            /*------------------------ Parcelas del Cliente -----------------------------*/

            $sqlParcelasDelCliente =" SELECT id_Parcela,parcela_formateada FROM dbo.vw_Parcela WHERE id_cliente = ? ORDER BY parcela_formateada, id_Parcela; ";
            
            $params = array(
                "id_cliente" => $values["id_cliente"],
            );

            $rc = $this->execAdHocWithParms($sqlParcelasDelCliente, $params);

            if (!$rc) {
                // si dio false estoy en problemas.....hacer un throw o raise...
                $mierror = $this->db->error();
                throw new Exception($mierror['message'], $mierror['code']);

            } else {
                $parcelasDelCliente = $rc->result_array();
                $values["parcelasDelCliente"] = $parcelasDelCliente;
            }

            /*------------------------ Inhumados de Parcelas del Cliente -----------------------------*/

            $sqlInhumadosEnParcela =  " SELECT id_inhumado, Nombre, NumeroInhumado, NumeroCertificado, id_Parcela_Actual " .
                                      " FROM dbo.vw_inhumados_by_parcela_simple " .
                                      " WHERE id_Parcela_Actual = ? " . 
                                      " ORDER BY nombre; ";
                        
            $params = array(
                "id_parcela" => $values["id_Parcela"],
            );
            $rc = $this->execAdHocWithParms($sqlInhumadosEnParcela, $params);

            if (!$rc) {
                // si dio false estoy en problemas.....hacer un throw o raise...
                $mierror = $this->db->error();
                throw new Exception($mierror['message'], $mierror['code']);

            } else {
                $inhumadosEnParcela = $rc->result_array();
                $values["inhumadosEnParcela"] = $inhumadosEnParcela;
            }
            /*------------------------ Lista de Precios -----------------------------*/

            $sqlListaPrecios =  " SELECT id_ConceptoListaPrecio, codigo+' - '+isnull(operacion, '') as operacion, Codigo, Precio FROM dbo.vw_ListaPrecio WHERE activo='A' ORDER BY 2; ";
                        
            $rc = $this->execAdHoc($sqlListaPrecios);

            if (!$rc) {
               // si dio false estoy en problemas.....hacer un throw o raise...
               $mierror = $this->db->error();
               throw new Exception($mierror['message'], $mierror['code']);

            } else {
               $listaPrecios = $rc->result_array();
               $values["listaPrecios"] = $listaPrecios;
            }
            
            /*  CC Corriente Historica  ( Britanico y BaseNogues, que no existe)*/
            $sqlBuscaCliente = "select c.id_cliente, c.NumeroCliente, c.RazonSocial from dbo.Cliente c where c.id_cliente = ?;";
            $prmsBuscaCliente=array(
                "id_cliente" => $values["id_cliente"],
            );  
            $rc = $this->execAdHocWithParms($sqlBuscaCliente, $prmsBuscaCliente);

            if (!$rc) {
                // si dio false estoy en problemas.....hacer un throw o raise...
                $mierror = $this->db->error();
                throw new Exception($mierror['message'], $mierror['code']);

            } else {
                $cliente = $rc->result_array();
            }

            // Aca tendria que chequear si es Britanico o Nogues
            // Si britanico, la DB es Britanico
            // Si Nogues, la DB deberia ser BaseNogues, pero no existe en el servidor, asi que falla nomas...
            $sqlCCHistorica =   "SELECT ch.FechaProceso, ch.FechaValor, ch.FechaVencimiento, ch.Importe, " . 
                                "ch.ImportePagado, ch.Tipo, ch.Sucursal, ch.Numero, ch.Detalle, " . 
                                "ch.EmpresaSecuencia, ch.NumeroCliente, ch.EmpresaSecuencia, ch.AplNumero, " .
                                "convert(varchar(10), ch.FechaProceso, 103) as FechaProcesoStr,  " .
								"convert(varchar(10), ch.FechaValor, 103) as FechaValorStr,  " .
                                "convert(varchar(10), ch.FechaVencimiento, 103) as FechaVencimientoStr, " .  
                                "cast(ch.Importe as varchar(20)) as ImporteStr,  " .
								"cast(ch.ImportePagado as varchar(20)) as ImportePagadoStr, " .
                                "ch.Tipo + '-' + cast(ch.Sucursal as varchar(10)) + '-' + cast(ch.Numero as varchar(10)) as Comprobante, " .
                                "isnull(ch.Importe, 0) - isnull(ch.ImportePagado, 0) as Saldo, " .
                                "cast(isnull(ch.Importe, 0) - isnull(ch.ImportePagado, 0) as varchar(20)) as SaldoStr " .
                                //"FROM Britanico.dbo.CuentaCorriente ch " .
                                "FROM Britanico_CuentaCorrienteHistorica ch " .
                                "WHERE ch.NumeroCliente=? " .
                                "AND ch.EmpresaSecuencia=0 " . 
                                "AND ch.AplNumero=ch.Numero " . 
                                "OR isnull(ch.aplnumero,'')='' " . 
                                "ORDER BY ch.aplnumero, ch.Numero; ";
            $prmsCCHistorica=array(
                "NumeroCliente" => $cliente[0]["NumeroCliente"],
            );  
            $rc = $this->execAdHocWithParms($sqlCCHistorica, $prmsCCHistorica);

            if (!$rc) {
                // si dio false estoy en problemas.....hacer un throw o raise...
                $mierror = $this->db->error();
                throw new Exception($mierror['message'], $mierror['code']);

            } else {
                $ccHistorica = $rc->result_array();
            }
                                
            /*------------------------------------- Salida ----------------------------------------*/

            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>"Records",
                "cuentaCorriente"=>$values["cuentaCorriente"],
                "cuentaCorrienteImpago"=>$values["cuentaCorrienteImpago"],
                "cuentaCorrienteImpagoValor"=>$values["cuentaCorrienteImpagoValor"],
                "resumenDeDeuda"=>$values["resumenDeDeuda"],
                "tiposDeComprobanteProv"=>$values["tiposDeComprobanteProv"],
                "parcelasDelCliente"=>$values["parcelasDelCliente"],
                "inhumadosEnParcela"=>$values["inhumadosEnParcela"],
                "listaPrecios"=>$values["listaPrecios"],
                "ccHistorica"=>$ccHistorica,
                "permisosAdicionalesUpd" => $permisosAdicionalesUpd,
                "permisosAdicionalesDel" => $permisosAdicionalesDel,
                "table"=>$this->table,
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT)
            );
        } catch (Exception $e) {
            return logError($e,"Excepcion en metodo:".__METHOD__. " * namespace:" . __NAMESPACE__ . 
                                    " * clase:" . __CLASS__ . " * funcion:". __FUNCTION__ 
                                    . " * directorio:" .  __DIR__ . " * archivo:" . __FILE__ );
        }
    }  
    public function getCuentaCorrienteHistorica ($values){
        try {
                   
            /*  CC Corriente Historica  ( Britanico y BaseNogues, que no existe)*/
            $sqlBuscaCliente = "select c.id_cliente, c.NumeroCliente, c.RazonSocial from dbo.Cliente c where c.id_cliente = ?;";
            $prmsBuscaCliente=array(
                "id_cliente" => $values["id_cliente"],
            );  
            $rc = $this->execAdHocWithParms($sqlBuscaCliente, $prmsBuscaCliente);

            if (!$rc) {
                // si dio false estoy en problemas.....hacer un throw o raise...
                $mierror = $this->db->error();
                throw new Exception($mierror['message'], $mierror['code']);

            } else {
                $cliente = $rc->result_array();
            }

            // Aca tendria que chequear si es Britanico o Nogues
            // Si britanico, la DB es Britanico
            // Si Nogues, la DB deberia ser BaseNogues, pero no existe en el servidor, asi que falla nomas...
            $sqlCCHistorica =   "SELECT ch.FechaProceso, ch.FechaValor, ch.FechaVencimiento, ch.Importe, " . 
                                "ch.ImportePagado, ch.Tipo, ch.Sucursal, ch.Numero, ch.Detalle, " . 
                                "ch.EmpresaSecuencia, ch.NumeroCliente, ch.EmpresaSecuencia, ch.AplNumero, " .
                                "convert(varchar(10), ch.FechaProceso, 103) as FechaProcesoStr,  " .
								"convert(varchar(10), ch.FechaValor, 103) as FechaValorStr,  " .
                                "convert(varchar(10), ch.FechaVencimiento, 103) as FechaVencimientoStr, " .  
                                "cast(ch.Importe as varchar(20)) as ImporteStr,  " .
								"cast(ch.ImportePagado as varchar(20)) as ImportePagadoStr, " .
                                "ch.Tipo + '-' + cast(ch.Sucursal as varchar(10)) + '-' + cast(ch.Numero as varchar(10)) as Comprobante, " .
                                "isnull(ch.Importe, 0) - isnull(ch.ImportePagado, 0) as Saldo, " .
                                "cast(isnull(ch.Importe, 0) - isnull(ch.ImportePagado, 0) as varchar(20)) as SaldoStr " .
                                //"FROM Britanico.dbo.CuentaCorriente ch " .
                                "FROM Britanico_CuentaCorrienteHistorica ch " .
                                "WHERE ch.NumeroCliente=? " .
                                "AND ch.EmpresaSecuencia=0 " . 
                                "AND ch.AplNumero=ch.Numero " . 
                                "OR isnull(ch.aplnumero,'')='' " . 
                                "ORDER BY ch.aplnumero, ch.Numero; ";
            $prmsCCHistorica=array(
                "NumeroCliente" => $cliente[0]["NumeroCliente"],
            );  
            $rc = $this->execAdHocWithParms($sqlCCHistorica, $prmsCCHistorica);

            if (!$rc) {
                // si dio false estoy en problemas.....hacer un throw o raise...
                $mierror = $this->db->error();
                throw new Exception($mierror['message'], $mierror['code']);

            } else {
                $ccHistorica = $rc->result_array();
            }
                                
            /*------------------------------------- Salida ----------------------------------------*/

            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>"Records",
                "ccHistorica"=>$ccHistorica,
                "table"=>$this->table,
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT)
            );
        } catch (Exception $e) {
            return logError($e,"Excepcion en metodo:".__METHOD__. " * namespace:" . __NAMESPACE__ . 
                                    " * clase:" . __CLASS__ . " * funcion:". __FUNCTION__ 
                                    . " * directorio:" .  __DIR__ . " * archivo:" . __FILE__ );
        }
    }   
    public function getItemCuentaCorriente ($values){
        try {

            $sqlPermisosAdicionales = "exec obtenerPermisosAdicionales ?, ?;";
            //"abm_modificacion_itemctacte"
            //"abm_borrado_itemctacte"
            $prmsPermisosAdicionales=array(
                "userId" => $values["id_user_active"],
                "perm" => "abm_modificacion_itemctacte"
            );                     
            $rc = $this->execAdHocWithParms($sqlPermisosAdicionales, $prmsPermisosAdicionales);
            if (!$rc) {
                // si dio false estoy en problemas.....hacer un throw o raise...
                $mierror = $this->db->error();
                throw new Exception($mierror['message'], $mierror['code']);

            } else {
                $permisosAdicionalesUpd = $rc->result_array();
            }  

            $prmsPermisosAdicionales=array(
                "userId" => $values["id_user_active"],
                "perm" => "abm_borrado_itemctacte"
            );                     
            $rc = $this->execAdHocWithParms($sqlPermisosAdicionales, $prmsPermisosAdicionales);
            if (!$rc) {
                // si dio false estoy en problemas.....hacer un throw o raise...
                $mierror = $this->db->error();
                throw new Exception($mierror['message'], $mierror['code']);

            } else {
                $permisosAdicionalesDel = $rc->result_array();
            }  

            $sqlItemCC =    " SELECT ID_CuentaCorriente, ID_EmpresaSucursal, id_cliente, id_Parcela, id_tipo_comprobante, " . 
                        "        id_ConceptoListaPrecio, ID_Operacion, ID_Recibo, Nro_Movimiento, NroLineaItem, " . 
                        "        DescripcionMovCC, Fecha_Emision, Fecha_Vencimiento, ID_Forma_de_Pago, Importe, Saldo, " . 
                        "        Fecha_Alta, Estado " .
                        " FROM dbo.CuentaCorriente WHERE id_CuentaCorriente=?;";
            $prmsItemCC=array(
                "ID_CuentaCorriente" => $values["ID_CuentaCorriente"],
            );  
            $sqlConceptos="SELECT id_ConceptoListaPrecio, codigo+' - '+isnull(Operacion, '') as Operacion FROM dbo.vw_ListaPrecio ORDER BY 2;";

            $rc = $this->execAdHocWithParms($sqlItemCC, $prmsItemCC);

            if (!$rc) {
                // si dio false estoy en problemas.....hacer un throw o raise...
                $mierror = $this->db->error();
                throw new Exception($mierror['message'], $mierror['code']);

            } else {
                $itemCC = $rc->result_array();
            }

            $rc = $this->execAdHocWithParms($sqlConceptos, array());

            if (!$rc) {
                // si dio false estoy en problemas.....hacer un throw o raise...
                $mierror = $this->db->error();
                throw new Exception($mierror['message'], $mierror['code']);

            } else {
                $conceptos = $rc->result_array();
            }
            /*------------------------------------- Salida ----------------------------------------*/

            return array(
                        "code"=>"2000",
                        "status"=>"OK",
                        "message"=>"Records",
                        "itemCuentaCorriente"=>$itemCC,
                        "listaPrecios"=>$conceptos,
                        "permisosAdicionalesUpd"=>$permisosAdicionalesUpd,
                        "permisosAdicionalesDel"=>$permisosAdicionalesDel,
                        "table"=>$this->table,
                        "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT)
                    );

        } catch (Exception $e) {
            return logError($e,"Excepcion en metodo:".__METHOD__. " * namespace:" . __NAMESPACE__ . 
                                    " * clase:" . __CLASS__ . " * funcion:". __FUNCTION__ 
                                    . " * directorio:" .  __DIR__ . " * archivo:" . __FILE__ );
        }
    }
    public function getResumenDeDeuda ($values){
        try {

            $sqlTransferirTitularidad = "spTransferirTitularidad ?;";

            $prms=array(
                "id_cliente" => $values["id_cliente"],
            );  

            $rc = $this->execAdHocWithParms($sqlTransferirTitularidad, $prms);

            if (!$rc) {
                // si dio false estoy en problemas.....hacer un throw o raise...
                $mierror = $this->db->error();
                throw new Exception($mierror['message'], $mierror['code']);

            } else {
                $transferenciaTitularidad = $rc->result_array();
                $values["transferenciaTitularidad"] = $transferenciaTitularidad;
            }
            
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>"Records",
                "notaAgregadaCliente"=>$values["transferenciaTitularidad"][0],
                "table"=>$this->table,
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT)
            );
        } catch (Exception $e) {
            return logError($e,"Excepcion en metodo:".__METHOD__. " * namespace:" . __NAMESPACE__ . 
                                    " * clase:" . __CLASS__ . " * funcion:". __FUNCTION__ 
                                    . " * directorio:" .  __DIR__ . " * archivo:" . __FILE__ );
        }
    }    
    public function generarPlanDePagos ($values){
        try {

            $sqlGenerarPlanDePago = "dbo.spGenerar_Plan_Pago ?, ?, ?, ?, ?, ?, ?;";

            /*
            dbo.spGenerar_Plan_Pago @vCuenta_corriente,
                                    @total_original_plan,
                                    @total_plan,
                                    @cuotas_plan,
                                    @importe_cuota_plan,
                                    @diferencia_plan,
                                    @tipo_plan,

            dbo.spGenerar_Plan_Pago ?, ?, ?, ?, ?, ?, ?
            */

            $prms=array(
                "vCuenta_corriente" => $values["vCuenta_corriente"],
                "total_original_plan" => $values["total_original_plan"],
                "total_plan" => $values["total_plan"],
                "cuotas_plan" => $values["cuotas_plan"],
                "importe_cuota_plan" => $values["importe_cuota_plan"],
                "diferencia_plan" => $values["diferencia_plan"],
                "tipo_plan" => $values["tipo_plan"],
            );  

            $rc = $this->execAdHocWithParms($sqlGenerarPlanDePago, $prms);

            if (!$rc) {
                // si dio false estoy en problemas.....hacer un throw o raise...
                $mierror = $this->db->error();
                throw new Exception($mierror['message'], $mierror['code']);

            } else {
                $rcPlanDePago = $rc->result_array();
                $values["rcPlanDePago"] = $rcPlanDePago;
            }
            
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>"Records",
                "rcPlanDePago"=>$values["rcPlanDePago"],
                "table"=>$this->table,
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT)
            );
        } catch (Exception $e) {
            return logError($e,"Excepcion en metodo:".__METHOD__. " * namespace:" . __NAMESPACE__ . 
                                    " * clase:" . __CLASS__ . " * funcion:". __FUNCTION__ 
                                    . " * directorio:" .  __DIR__ . " * archivo:" . __FILE__ );
        }
    }        
    public function borrarComprobanteCC ($values){
        try {
            /*
                dbo.spDelMCC @idCuentaCorriente
                dbo.spDelMCC ?
            */
            $sqlBorrarComprobanteCC = "dbo.spDelMCC ?;";
            $prms=array(
                "idCuentaCorriente" => $values["idCuentaCorriente"],
            );  

            $rc = $this->execAdHocWithParms($sqlBorrarComprobanteCC, $prms);

            if (!$rc) {
                // si dio false estoy en problemas.....hacer un throw o raise...
                $mierror = $this->db->error();
                throw new Exception($mierror['message'], $mierror['code']);
            } else {
                $rcBorradoCC = $rc->result_array();
                $values["borradoCC"] = $rcBorradoCC;
            }

            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>"Records",
                "borradoCC"=>$values["borradoCC"],
                "table"=>$this->table,
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT)
            );
        } catch (Exception $e) {
            return logError($e,"Excepcion en metodo:".__METHOD__. " * namespace:" . __NAMESPACE__ . 
                                    " * clase:" . __CLASS__ . " * funcion:". __FUNCTION__ 
                                    . " * directorio:" .  __DIR__ . " * archivo:" . __FILE__ );
        }
    }
    public function modificarComprobanteCC ($values){
        try {
            /*
            dbo.spUpdMCC    @idCuentaCorriente,
                            @fecha_emision_mcc,
                            @fecha_vencimiento_mcc,
                            @id_ConceptoListaPrecio_mcc,
                            @importe_mcc,
                            @saldo_mcc
            dbo.spUpdMCC ?, ?, ?, ?, ?, ?
            */
            $sqlModificarComprobanteCC = "dbo.spUpdMCC ?, ?, ?, ?, ?, ?;";
            $prms=array(
                "idCuentaCorriente" => $values["idCuentaCorriente"],
                "fecha_emision_mcc" => $values["fecha_emision_mcc"],
                "fecha_vencimiento_mcc" => $values["fecha_vencimiento_mcc"],
                "id_ConceptoListaPrecio_mcc" => $values["id_ConceptoListaPrecio_mcc"],
                "importe_mcc" => $values["importe_mcc"],
                "saldo_mcc" => $values["saldo_mcc"],
            );  

            $rc = $this->execAdHocWithParms($sqlModificarComprobanteCC, $prms);

            if (!$rc) {
                // si dio false estoy en problemas.....hacer un throw o raise...
                $mierror = $this->db->error();
                throw new Exception($mierror['message'], $mierror['code']);

            } else {
                $rcCC = $rc->result_array();
                $values["rcCC"] = $rcCC;
            }
            
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>"Records",
                "modficacionCC"=>$values["rcCC"],
                "table"=>$this->table,
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT)
            );
        } catch (Exception $e) {
            return logError($e,"Excepcion en metodo:".__METHOD__. " * namespace:" . __NAMESPACE__ . 
                                    " * clase:" . __CLASS__ . " * funcion:". __FUNCTION__ 
                                    . " * directorio:" .  __DIR__ . " * archivo:" . __FILE__ );
        }
    }            
    public function crearComprobanteCC ($values){
        // Inserta movimiento cuenta corriente
        try {
            $sqlCrearComprobanteCC = "dbo.coop_CuentaCorriente_Insert_Custom ?, ?, ?, ?, ?, ?, ?;";
            /*
            dbo.coop_CuentaCorriente_Insert_Custom  @ID_EmpresaSucursal int,
                                                    @id_cliente int,
                                                    @id_Parcela int,
                                                    @id_tipo_comprobante int,
                                                    @items VARCHAR(MAX),
                                                    @fecha_alta datetime=null,
                                                    @ajuste varchar(max)='N'

            dbo.coop_CuentaCorriente_Insert_Custom ?, ?, ?, ?, ?, ?, ?

            @items = id_operacion^descripcion^importe^id_inhumado~id_operacion^descripcion^importe^id_inhumado~id_operacion^descripcion^importe^id_inhumado
            173^1000 10 24/3^10^null~12^1020 20 22/3^20^null

            parametros
            fecha_alta  -> ABM

            Dato UI
            Fecha Inicio Periodo -> fecha alta

            Tabla
            Fecha Emision -> Fecha Inicio Periodo
            Fecha Vencimiento -> calculada con inicio periodo + meses de la lista de precio
            */
            $prms=array(
                "ID_EmpresaSucursal" => $values["ID_EmpresaSucursal"],
                "id_cliente" => $values["id_cliente"],
                "id_Parcela" => $values["id_Parcela"],
                "id_tipo_comprobante" => $values["id_tipo_comprobante"],
                "items" => $values["items"],
                "fecha_alta" => $values["fecha_alta"],
                "ajuste" => $values["ajuste"],
            );
            //log_message("error", "RELATED " . json_encode($prms, JSON_PRETTY_PRINT));

            $rc = $this->execAdHocWithParms($sqlCrearComprobanteCC, $prms);

            if (!$rc) {
                // si dio false estoy en problemas.....hacer un throw o raise...
                $mierror = $this->db->error();
                throw new Exception($mierror['message'], $mierror['code']);

            } else {
                $rcCC = $rc->result_array();
                $values["rcCC"] = $rcCC;
            }
            
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>"Records",
                "creacionCC"=>$values["rcCC"],
                "table"=>$this->table,
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT)
            );
        } catch (Exception $e) {
            return logError($e,"Excepcion en metodo:".__METHOD__. " * namespace:" . __NAMESPACE__ . 
                                    " * clase:" . __CLASS__ . " * funcion:". __FUNCTION__ 
                                    . " * directorio:" .  __DIR__ . " * archivo:" . __FILE__ );
        }
    }            

}