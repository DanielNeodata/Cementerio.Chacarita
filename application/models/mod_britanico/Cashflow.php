<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Cashflow extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }

    public function Cashflow($values){
        try {
            log_message("error", "RELATED CASHFLOW " . json_encode($values, JSON_PRETTY_PRINT));

            $location=explode("::",strtolower(__METHOD__));
            $values["interface"]=(MOD_BRITANICO."/".$location[0]."/".$location[1]);
            $data["parameters"] = $values;
            $data["title"] = ucfirst(lang("m_".strtolower($values["function"])));
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

    public function GetCashflow($values){
        try {
           

           if (isset($values["view"])){$this->view=$values["view"];}

           $sql="";


           $sql = "   spCashflow '".$values["DESDE"]."','".$values["HASTA"]."'";

           $estadistica = $this->execAdHocAsArray($sql);

           return array(
               "code"=>"2000",
               "status"=>"OK",
               "message"=>"Records",
               "estadistica"=>$estadistica,
               "table"=>$this->table,
               "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT)
           );
       }
       catch(Exception $e) {
           return logError($e,__METHOD__ );
       }
   }
}
