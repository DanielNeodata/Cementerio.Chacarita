<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class TextosMargenes extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }
    public function brow($values){
        try {
            $values["order"]="Uso ASC, Etiqueta ASC";
            //$values["top"]=3;
            $values["records"]=$this->get($values);

            $values["getters"]=array(
             "search"=>true,
             //"googlesearch"=>false,
             "excel"=>false,
             "pdf"=>false,
           );

            $values["buttons"]=array(
                "new"=>false,
                "edit"=>true,
                "delete"=>false,
                "offline"=>false,
            );
            $values["columns"]=array(
                array("field"=>"Uso","format"=>"text"),
                array("field"=>"Etiqueta","format"=>"text"),
                array("field"=>"PosX","format"=>"number"),
                array("field"=>"PosY","format"=>"number"),
                array("field"=>"Width","format"=>"number"),
                array("field"=>"Height","format"=>"number"),
                array("field"=>"Unidades","format"=>"text"),
                array("field"=>"Contenido","format"=>"text"),
                array("field"=>"TagInicio","format"=>"text"),
                array("field"=>"TagCierre","format"=>"text"),
            );

            // Controles para los filtros
            /*$values["controls"]=array(
                "<label>".lang('p_Codigo')."</label><input type='text' id='browser_Codigo' name='browser_Codigo' class='form-control text'/>",
                "<label>".lang('p_Nombre')."</label><input type='text' id='browser_Nombre' name='browser_nombre' class='form-control text'/>",
                "<label>".lang('p_nro_cuenta_contable')."</label><input type='text' id='browser_nro_cuenta_contable' name='browser_nro_cuenta_contable' class='form-control text'/>",
                "<label>".lang('p_cuenta_contable')."</label><input type='text' id='browser_cuenta_contable' name='browser_cuenta_contable' class='form-control text'/>",                
            );*/
            // Filtros y search. Confirmar si funciona como AND o como OR
            $values["filters"]=array(
                array("name"=>"browser_search", "operator"=>"like","fields"=>array("Uso","Etiqueta","Contenido","TagInicio","TagCierre")),
            );
            return parent::brow($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }

    public function edit($values){
        try {
            $location=explode("::",strtolower(__METHOD__));
            $values["interface"]=(MOD_BRITANICO."/".$location[0]."/abm");
            $values["page"]=1;
            $values["view"]="TextosMargenes";
            $values["where"]=("id=".$values["id"]);
            $values["records"]=$this->get($values);
            return parent::edit($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function save($values,$fields=null){
        try {
            if (!isset($values["id"])){$values["id"]=0;}
            $id=(int)$values["id"];
            if($id==0){
                if($fields==null) {
                    $aa = secureEmptyNull($values, "TagInicio");
                    $fields = array(
                        'Uso' => $values["Uso"],
                        'Etiqueta' => $values["Etiqueta"],
                        'PosX' => $values["PosX"],     //secureEmptyNull($values, "PosX"),       //$values["PosX"],
                        'PosY' => $values["PosY"],     //secureEmptyNull($values, "PosY"),       //$values["PosY"],
                        'Width' => $values["Width"],   //secureEmptyNull($values, "Width"),      //$values["Width"],
                        'Height' => $values["Height"], //secureEmptyNull($values, "Height"),     //$values["Height"],
                        //'Unidades' => secureEmptyNull($values, "Unidades"),      //$values["Unidades"],
                        'Contenido' => secureEmptyNull($values, "Contenido"),    //$values["Contenido"],
                        //'TagInicio' => secureEmptyNull($values, "TagInicio"),    //$values["TagInicio"],
                        //'TagCierre' => secureEmptyNull($values, "TagCierre"),    //$values["TagCierre"],
                    );
                }
            } else {
                if($fields==null) {

                    $pX = $values["PosX"];
                    $pY = $values["PosY"];
                    $w = $values["Width"];
                    $h = $values["Height"];

                    if ($pX == "") {
                        $pX=null;
                    }
                    if ($pY == "") {
                        $pY=null;
                    }
                    if ($w == "") {
                        $w=null;
                    }
                    if ($h == "") {
                        $h=null;
                    }
                    $fields = array(
                        'PosX' => $pX,     //secureEmptyNull($values, "PosX"),       //$values["PosX"],
                        'PosY' => $pY,     //secureEmptyNull($values, "PosY"),       //$values["PosY"],
                        'Width' => $w,   //secureEmptyNull($values, "Width"),      //$values["Width"],
                        'Height' => $h, //secureEmptyNull($values, "Height"),     //$values["Height"],
                        //'Unidades' => secureEmptyNull($values, "Unidades"),      //$values["Unidades"],
                        'Contenido' => $values["Contenido"],//secureEmptyNull($values, "Contenido"),    //$values["Contenido"],
                        //'TagInicio' => secureEmptyNull($values, "TagInicio"),    //$values["TagInicio"],
                        //'TagCierre' => secureEmptyNull($values, "TagCierre"),    //$values["TagCierre"],
                    );
                }
            }
            return parent::save($values,$fields);
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }
}
