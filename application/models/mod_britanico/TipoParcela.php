<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class TipoParcela extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }
    public function brow($values){
        try {
            $values["table"]="TipoParcela";
            $values["view"]="TipoParcela";
            $values["records"]=$this->get($values);
            return parent::brow($values);
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
            $values["records"]=$this->get($values);
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
                if($fields==null) {
                    $fields = array(
                        'Sector' => $values["Sector"],
                        'Manzana' => $values["Manzana"],
                        'Parcela' => $values["Parcela"],
                        'Disponible' => $values["Disponible"],
                        //'created' => $this->now,
                        //'verified' => $this->now,
                        //'offline' => null,
                        //'fum' => $this->now,                        
                    );
                }
            } else {
                if($fields==null) {
                    // CCOO
                    $fields = array(
                        'Sector' => $values["Sector"],
                        'Manzana' => $values["Manzana"],
                        'Parcela' => $values["Parcela"],
                        'Disponible' => $values["Disponible"],
                        //'offline' => null,
                        //'fum' => $this->now,                        
                        
                        //'ESTADO_OCUPACION' => $values["estado_ocupacion"],

                        //'id_forma_pago' => secureEmptyNull($values,"id_forma_pago"),
                        //'numero_tarjeta' => $values["numero_tarjeta"],

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
