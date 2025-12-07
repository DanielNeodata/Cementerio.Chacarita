<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Estadistica_cobro extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }
    public function reporteEstadisticaCobro($values){
        try {
            $location=explode("::",strtolower(__METHOD__));
            $values["interface"]=(MOD_BRITANICO."/".$location[0]."/".$location[1]); // Estadistica_cobro/reporteEstadisticaCobro
            $data["parameters"] = $values;

   
            $data["title"] = "Estadística de Cobro";
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
        catch(Exception $e) {
            return logError($e,__METHOD__ );
        }   

    }   
    public function getReporteEstadisticaCobroPorAvisos($values){
        try {
            

            if (isset($values["view"])){$this->view=$values["view"];}
            $sqlPorAvisos="SELECT dbo.Get_Cobrado(?, ?) as cobrado";
            $fecha = $values["anio"]."-".$values["mes"]."-01";
            $prmA = array(-1, $fecha);

            $fecha = $values["anio"]."-".$values["mes"]."-01";
            $prmB = array(0, $fecha);

            $fecha = $values["anio"]."-".$values["mes"]."-01";
            $prmC = array(1, $fecha);

            $fecha = $values["anio"]."-".$values["mes"]."-01";
            $prmD = array(2, $fecha);

            $fecha = $values["anio"]."-".$values["mes"]."-01";
            $prmE = array(3, $fecha);

            $fecha = $values["anio"]."-".$values["mes"]."-01";
            $prmF = array(4, $fecha);
            
            $sqlCuenta = "select count(ID_CuentaCorriente) as cantidad ".
                         "   from dbo.CuentaCorriente " .
                         "   where Fecha_alta=? ";
            $prmCuenta = array($fecha);

            $q = $this->db->query($sqlPorAvisos, $prmA);
            $porAvisosA = array();
            if ($q) {$porAvisosA=$q->result_array();}
            $q = $this->db->query($sqlPorAvisos, $prmB);
            $porAvisosB = array();
            if ($q) {$porAvisosB=$q->result_array();}
            
            $q = $this->db->query($sqlPorAvisos, $prmC);
            $porAvisosC = array();
            if ($q) {$porAvisosC=$q->result_array();}

            $q = $this->db->query($sqlPorAvisos, $prmD);
            $porAvisosD = array();
            if ($q) {$porAvisosD=$q->result_array();}
            
            $q = $this->db->query($sqlPorAvisos, $prmE);
            $porAvisosE = array();
            if ($q) {$porAvisosE=$q->result_array();}

            $q = $this->db->query($sqlPorAvisos, $prmF);
            $porAvisosF = array();
            if ($q) {$porAvisosF=$q->result_array();}

            $q = $this->db->query($sqlCuenta, $prmCuenta);
            $cantidad = array();
            if ($q) {$cantidad=$q->result_array();}
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>"Records",
                "porAvisosA"=>$porAvisosA,
                "porAvisosB"=>$porAvisosB,
                "porAvisosC"=>$porAvisosC,
                "porAvisosD"=>$porAvisosD,
                "porAvisosE"=>$porAvisosE,
                "porAvisosF"=>$porAvisosF,
                "cantidad"=>$cantidad,
                "table"=>$this->table,
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT)
            );
        }
        catch(Exception $e) {
            return logError($e,__METHOD__ );
        }
    }       
    public function getReporteEstadisticaCobroDatosAgrupados($values){
        try {
            if (isset($values["view"])){$this->view=$values["view"];}
            $sqlDatosAgrupados="select x.importe, x.Codigo, x.Nombre, x.numero, _LISTA2_ as listaCodigos
                                from (SELECT sum(ISNULL(rrcc.Importe_Cancelado,0)) as importe,lp.Codigo, lp.Nombre, ct.numero 
                                    FROM dbo.RelReciboCuentaCorriente as rrcc,
                                            dbo.Recibo as r, 
                                            dbo.CuentaCorriente as cc, 
                                            dbo.ListaPrecio as lp, 
                                            dbo.Con_Cuentas as ct 
                                    WHERE rrcc.ID_Recibo = r.ID_Recibo 
                                    AND cc.ID_CuentaCorriente = rrcc.ID_CuentaCorriente 
                                    AND cc.id_ConceptoListaPrecio = lp.id_ConceptoListaPrecio 
                                    AND YEAR(r.Fecha_Emision)=? AND MONTH(r.Fecha_Emision)=? 
                                    AND lp.Codigo IN _LISTA_                                    
                                    GROUP BY lp.Codigo, lp.Nombre, ct.numero) as x 
                                ORDER BY x.Codigo, x.Nombre;";

            $prm = array($values["anio"], $values["mes"]);
            $sqlDatosAgrupados = str_replace("_LISTA2_", "'" . $values["listaCodigos"] . "'", $sqlDatosAgrupados);
            $sqlDatosAgrupados = str_replace("_LISTA_", "(" . $values["listaCodigos"] . ")", $sqlDatosAgrupados);
            $q = $this->db->query($sqlDatosAgrupados, $prm);
            $datosAgrupados = array();
            if ($q) {$datosAgrupados=$q->result_array();}
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>"Records",
                "datosAgrupados"=>$datosAgrupados,
                "table"=>$this->table,
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT)
            );
        }
        catch(Exception $e) {
            return logError($e,__METHOD__ );
        }
    }         
}
