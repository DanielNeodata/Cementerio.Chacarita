<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Gestion_cobranza extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }
    public function reporteGestionCobranza($values){
        try {
            $location=explode("::",strtolower(__METHOD__));
            $values["interface"]=(MOD_BRITANICO."/".$location[0]."/".$location[1]); // Gestion_cobranza/reporteGestionCobranza
            $data["parameters"] = $values;

            // Botones
            $sqlGestionCobranzaSegPorMonto = "SELECT * FROM dbo.segmentos_cobranza WHERE id_segmento <= 4 ORDER BY 1 asc;";
            // Este es el que se usa para los botones
            $sqlGestionCobranzaSegPorAntiguedad = "SELECT * FROM dbo.segmentos_cobranza WHERE id_segmento >= 5 ORDER BY 1 asc;";

            // sp de reporte
            $sqlGestionCobranzaTotal = "dbo.spGestionCobranzaTotal ?;";          
            $sqlGestionCobranzaCabecera = "dbo.spGestionCobranzaCabecera ?;";
            $sqlGestionCobranzaDetalle = "";          

            $rc = $this->execAdHocWithParms($sqlGestionCobranzaSegPorAntiguedad, array());

            if (!$rc) {
                // si dio false estoy en problemas.....hacer un throw o raise...
                $mierror = $this->db->error();
                throw new Exception($mierror['message'], $mierror['code']);
            } else {
                $gestionCobranzaSegPorAntiguedad = $rc->result_array();
            }
       
            $data["title"] = "Gestión de Cobranza";
            $data["gestionCobranzaSegPorAntiguedad"]=$gestionCobranzaSegPorAntiguedad;
            $html=$this->load->view($values["interface"],$data,true);

            
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>compress($this,$html),
                "gestionCobranzaSegPorAntiguedad"=>$gestionCobranzaSegPorAntiguedad,
                //"gestionCobranzaTotal"=>$array,
                //"gestionCobranzaCabecera"=>$gestionCobranzaCabecera,
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                "data"=>null,
                "compressed"=>true
            );

        }
        catch(Exception $e) {
            return logError($e,__METHOD__ );
        }   

    }
    public function getReporteGestionCobranza($values){
        try {
            

            // sp de reporte
            $sqlGestionCobranzaTotal = "dbo.spGestionCobranzaTotal ?;";          
            $sqlGestionCobranzaCabecera = "dbo.spGestionCobranzaCabecera ?;";       

            $rc = $this->execAdHocWithParms($sqlGestionCobranzaTotal, array($values["idSegmento"]));

            if (!$rc) {
                // si dio false estoy en problemas.....hacer un throw o raise...
                $mierror = $this->db->error();
                throw new Exception($mierror['message'], $mierror['code']);
            } else {
                $gestionCobranzaTotal = $rc->result_array();
            }
            $rc = $this->execAdHocWithParms($sqlGestionCobranzaCabecera, array($values["idSegmento"]));

            if (!$rc) {
                // si dio false estoy en problemas.....hacer un throw o raise...
                $mierror = $this->db->error();
                throw new Exception($mierror['message'], $mierror['code']);
            } else {
                $gestionCobranzaCabecera = $rc->result_array();
            }

            
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>"Records",
                "gestionCobranzaTotal"=>$gestionCobranzaTotal,                
                "gestionCobranzaCabecera"=>$gestionCobranzaCabecera,
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                "data"=>null,
            );
        }
        catch(Exception $e) {
            return logError($e,__METHOD__ );
        }
    }  
    public function getActividadEnNotas($values){
        try {
            
            if (isset($values["view"])){$this->view=$values["view"];}
            $sqlActividad="SELECT id, nota, fecha_alta, tipo_nota, NumeroCliente, RazonSocial, id_usuario, usuario FROM dbo.vw_notas WHERE cast(fecha_alta as date)=? ORDER BY id_usuario, usuario, tipo_nota, fecha_alta;";
            $prm = array($values["fechaActividad"]);
            $q = $this->db->query($sqlActividad, $prm);
            $actividadEnNotas = array();
            if ($q) {
                $actividadEnNotas=$q->result_array();
            }
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>"Records",
                "actividadEnNotas"=>$actividadEnNotas,
                "table"=>$this->table,
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT)
            );
        }
        catch(Exception $e) {
            return logError($e,__METHOD__ );
        }
    }     
}
