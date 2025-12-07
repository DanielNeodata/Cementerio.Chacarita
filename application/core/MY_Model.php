<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class MY_Model extends CI_Model {
    public $ready = false;
    public $status = null;
    public $language = DEFAULT_LANGUAGE;
    public $module = "";
    public $model = "";
    public $table = "";
    public $view = "";
    public $now=null;
    public $psession=null;

    public function __construct() {
        parent::__construct();
        date_default_timezone_set(DEFAULT_TIMEZONE);
        $this->now=date(FORMAT_DATE);
    }

    public function trueFalseFromSN($val)
    {
        if ($val=='S' || $val=='s' || $val=='Y' || $val=='y')
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /*Initialize*/
    public function init($model,$table,$lang=null){
        try {
            if ($lang!=null){$this->language=$lang;}
            $this->module=explode("/",$model)[0];
            $this->model=$model;
            $this->table=$table;
            $this->view=$table;
            $this->ready = true;
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>"",
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                );
        }
        catch(Exception $e) {
            return logError($e,__METHOD__ );
        }
    }
    public function createModel($module,$model,$table) {
        try {
            $this->load->model($module."/".$model,$model, true);
            $this->{$model}->status=$this->{$model}->init($module."/".$model,$table,$this->language);
            if ($this->{$model}->status["status"]!="OK"){throw new Exception($this->{$model}->status["message"],(int)$this->{$model}->status["code"]);}
            return $this->{$model};
        }
        catch(Exception $e) {
            return null;
        }
    }

    public function prepareModule(){
        /*Módulos sin prefijo*/
        switch(strtoupper($this->module)){
            case strtoupper(MOD_BRITANICO):
                $this->module="";
                break;
            case "":
                $this->module="";
                break;
            default:
                if (substr($this->module, -1)=="_"){$this->module=rtrim($this->module, "_");}
                $this->module=($this->module."_");
                break;
        }
    }

    /*Public I/O methods*/
    public function get($values){
        try {
            if (isset($values["view"])){$this->view=$values["view"];}
            $data=$this->getRecords($values);
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>"Records",
                "table"=>$this->table,
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                "data"=>$data["records"],
                "totalrecords"=>$data["totalrecords"],
                "totalpages"=>$data["totalpages"],
                "page"=>$data["page"]
            );
        }
        // CCOO
        catch(Throwable $e) {
            // throw $e;
            //throw $e;
            return logError($e,__METHOD__ );
        }
        catch(Exception $e) {
            return logError($e,__METHOD__ );
        }
    }
    public function getByWhere($params){
        try {
            $ACTIVE=$this->createModel($params["module"],$params["model"],$params["model"]);
            $record=$ACTIVE->get(array("fields"=>$params["field"],"where"=>$params["where"]));
            $data=null;
            if(isset($record["data"][0][$params["field"]])){$data=$record["data"][0][$params["field"]];}
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>"Record retrieved",
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                "data"=>$data,
                );
        } catch(Exception $e) {
            return logError($e,__METHOD__ );
        }
    }

    // save original. tiene que estar, sino da errores tipo:
    // ERROR - 2022-02-05 23:20:03 --> Severity: Warning --> Declaration of Con_cuentas::save($values, $fields = NULL) should be compatible with MY_Model::save($values, $fields = NULL, $forcedTable = NULL, $forcedSp = NULL, $prm = NULL) F:\Lu\SvnRepoCCO\neodata\Britanico\trunk\application\models\mod_britanico\Con_cuentas.php 87
    public function save($values,$fields=null) {
        try {

            if(!isset($values["id"]) or $values["id"]==""){$values["id"]=0;}
            $id=(int)$values["id"];
            $message="";
            if($id==0){
                if($fields==null) {
                    $fields = array(
                    // CCOO
                    //'code' => $values["code"],
                    //'description' => $values["description"],
                    'created' => $this->now,
                    'verified' => $this->now,
                    'offline' => null,
                    'fum' => $this->now,
                    );
                }
            } else {
                if($fields==null) {
                    $fields = array(
                    // CCOO
                    //'code' => $values["code"],
                    //'description' => $values["description"],
                    'fum' => $this->now,
                    );
                }
            }
            $id=$this->setRecord($fields,$id);
            $this->saveAttachments($values,$id,null);
            $this->saveMessages($values,$id,null);
            $data=array("id"=>$id);
            
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>$message,
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                "data"=>$data,
                );

        }
        catch(Exception $e) {
            return logError($e,__METHOD__ );
        }
    }

public function saveRecord($fields,$id,$tableName) {
        try {
            $this->prepareModule();
            $resolvedTableView=$tableName;
            if($id==0) {
                $this->db->insert($resolvedTableView, $fields);
                $id=$this->db->insert_id();
            } else {
                $this->db->where('id',$id);
                $this->db->update($resolvedTableView, $fields);
            }
            return $id;
        }
        catch(Exception $e){
            return $e;
        }
    }

    public function saveRecordCustomKey($fields,$id,$tableName,$keyName) {
        try {
            $this->prepareModule();
            $resolvedTableView=$tableName;
            if($id==0) {
                $this->db->insert($resolvedTableView, $fields);
                $id=$this->db->insert_id();
            } else {
                $this->db->where($keyName,$id);
                $this->db->update($resolvedTableView, $fields);
            }
            return $id;
        }
        catch(Exception $e){
            return $e;
        }
    }

    public function saveExtended($values,$fields=null, $forcedTable=null, $forcedSp=null, $prm=null, $keyName=null) {
        try {
            if ( ($forcedTable == "" || $forcedTable == null ) && ($forcedSp == "" || $forcedSp == null) ) {
                if(!isset($values["id"]) or $values["id"]==""){$values["id"]=0;}
                $id=(int)$values["id"];
                $message="";
                if($id==0){
                    if($fields==null) {
                        $fields = array(
                        // CCOO
                        //'code' => $values["code"],
                        //'description' => $values["description"],
                        'created' => $this->now,
                        'verified' => $this->now,
                        'offline' => null,
                        'fum' => $this->now,
                        );
                    }
                } else {
                    if($fields==null) {
                        $fields = array(
                        // CCOO
                        //'code' => $values["code"],
                        //'description' => $values["description"],
                        'fum' => $this->now,
                        );
                    }
                }
                $id=$this->setRecord($fields,$id);
                $this->saveAttachments($values,$id,null);
                $this->saveMessages($values,$id,null);

                $data=array("id"=>$id);
                
                return array(
                    "code"=>"2000",
                    "status"=>"OK",
                    "message"=>$message,
                    "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                    "data"=>$data,
                    "id"=>$id
                    );

            } else {
                // entro por forcedTable o forcedSp

                if(!isset($values["id"]) or $values["id"]==""){$values["id"]=0;}
                $id=(int)$values["id"];
                $message="";
                if($id==0){
                    if($fields==null) {
                        $fields = array(
                        'created' => $this->now,
                        'verified' => $this->now,
                        'offline' => null,
                        'fum' => $this->now,
                        );
                    }
                } else {
                    if($fields==null) {
                        $fields = array(
                        'fum' => $this->now,
                        );
                    }
                }
                if ( !($forcedSp == "" || $forcedSp == null) && ($forcedTable=="" || $forcedTable==null)) {
                    // Aca ejecutar SP
                    // El SP no debe devolver nada mas que su result set
                    // Debe toner su SET NOCOUNT ON
                    // Ninguno de los otros SP que se llamen adentro deben estar devolviendo result set
                    // Tambien deben tener su SET NOCOUNT ON
                    // El identity que devuelva, o se llama id, o hay que pasar el nombre como parametro del save()
                    //$rc = $this->db->query($forcedSp, $prm)->result_array();
                    
                    $rc = $this->db->query($forcedSp, $prm);

                    $salida = $rc->result_array();
                    $id=0;
                    if (!($keyName==null || $keyName=='')) {
                        if (isset($salida[0][$keyName]) && $salida[0][$keyName]!=null) {
                            $id=$salida[0][$keyName];
                            $data=array("id" => $salida[0][$keyName]);
                        }
                    } else {
                        $id=$salida["id"];
                        $data=array("id" => $salida["id"]);
                    }
                    $data["customData"] = $salida[0];
                    
                    return array(
                        "code"=>"2000",
                        "status"=>"OK",
                        "message"=>$message,
                        "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                        "data"=>$data,
                        "id"=> $data["customData"]["id"]
                        );
                }
            }
        }
        catch(Exception $e) {
            return logError($e,"Excepcion en metodo:".__METHOD__. " * namespace:" . __NAMESPACE__ .
                                    " * clase:" . __CLASS__ . " * funcion:". __FUNCTION__
                                    . " * directorio:" .  __DIR__ . " * archivo:" . __FILE__ );

        }
    }
    public function insertBySelect($values){
        try {
            $this->prepareModule();
            $sql="INSERT INTO ".($this->module.$this->table)." (".$values["fieldList"].") (".$values["selectToInsert"].")";
            $ins = $this->db->query($sql);
            
            // CCOO
            if( !$ins ){
                $mierror = $this->db->error();
                throw new Exception($mierror['message'], $mierror['code']);
            }
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>"",
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                );
        }
        catch(Throwable $e) {
            return logError($e,__METHOD__ );
            throw $e;
        }
        catch(Exception $e){
            // PHP5.5 ?
            return logError($e,__METHOD__ );
            throw $e;
        }

    }

    public function offline($values){
        try {
            $data=array("id"=>$this->setRecord(array('offline' => $this->now,'fum' => $this->now),$values["id"]));
            
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>lang('msg_offline'),
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                "data"=>$data
                );
        }
        catch(Exception $e) {
            return logError($e,__METHOD__ );
        }
    }
    public function online($values){
        try {
            $data=array("id"=>$this->setRecord(array('offline' => null,'fum' => $this->now),$values["id"]));
            
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>lang('msg_online'),
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                "data"=>$data
                );
        }
        catch(Exception $e) {
            return logError($e,__METHOD__ );
        }
    }
    public function delete($values){
        try {
            $FILES_ATTACHED=$this->createModel(MOD_BACKEND,"Files_attached","Files_attached");
            $file=$FILES_ATTACHED->get(array("where"=>"id_rel=".$values["id"]." AND table_rel='".$this->table."'"));
            foreach($file["data"] as $item){
                unlink($item["src"]);
                $FILES_ATTACHED->delete(array("id"=>$item["id"]));
            }
            $del=$this->delRecord(("id=".$values["id"]));
            if($del!==true){throw $del;};
            
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>lang('msg_delete'),
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                "data"=>null
                );
        }
        catch(Exception $e) {
            return logError($e,__METHOD__ );
        }
    }
    public function deleteByWhere($where){
        try {
        //log_message("error", "WHERE ".json_encode($where,JSON_PRETTY_PRINT));
            $del=$this->delRecord($where);
            if($del!==true){throw $del;};
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>"",
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                "data"=>null
                );
        }
        catch(Exception $e) {
            return logError($e,__METHOD__ );
        }
    }
    public function updateByWhere($fields,$where){
        try {
            $this->prepareModule();
            $resolvedTableView=($this->module.$this->table);
            $this->db->where($where);
            $this->db->update($resolvedTableView, $fields);
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>"",
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                "data"=>null
                );
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function info($values){
        try {
            $values["fields"]="max(id) as max_id, count(*) as total";
            $info=$this->get($values);
            if ($info["status"]!="OK"){throw new Exception($info["message"],(int)$info["code"]);};
            if (isset($info["data"][0])) {
                $data=array(
                    "total"=>(int)$info["data"][0]["total"],
                    "max_id"=>(int)$info["data"][0]["max_id"],
                    );
                return array(
                    "code"=>"2000",
                    "status"=>"OK",
                    "message"=>lang('msg_info'),
                    "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                    "data"=>$data,
                    );
            } else {
                throw new Exception(lang("error_5001"),5001);
            }
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function process($values){
        try {
            
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>lang('msg_process'),
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                "data"=>null
                );
        }
        catch(Exception $e) {
            return logError($e,__METHOD__ );
        }
    }
    public function getRecordsAdHoc($sql) {
        try {
            $records=$this->db->query($sql)->result_array();

            // CCOO
            if( !$records ){
                $mierror = $this->db->error();
                // https://codeigniter.com/userguide3/database/db_driver_reference.html?highlight=error#CI_DB_driver::display_error
                throw new Exception($mierror['message'], $mierror['code']);
            }

            if (strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN') {$records=toUtf8($records);}
            return $records;
        }
        //catch(Exception $e){
        //    return null;
        //}
        // CCOO
        catch(Throwable $e) {
            return null;
        }
        catch(Exception $e){
            // PHP5.5 ?
            throw $e;
        }
    }
    public function execAdHoc($sql) {
        try {
            $return = $this->db->query($sql);
            if( !$return ){
                $mierror = $this->db->error();
                log_message("error", "RELATED R0-> mierror " . json_encode($mierror, JSON_PRETTY_PRINT));
                throw new Exception($mierror['message'], $mierror['code']);
            }

            return $return;
        }
        catch(Throwable $e) {
            log_message("error", "RELATED err1-> " . json_encode($e, JSON_PRETTY_PRINT));
            return null;
        }
        catch(Exception $e){
            log_message("error", "RELATED err2-> " . json_encode($e, JSON_PRETTY_PRINT));
            return null;
        }
    }
    public function execAdHocWithParms($sql, $prms) {
        try {
            //return $this->db->query($sql);
            // CCOO
            $return = $this->db->query($sql, $prms);

            if( !$return ){
                $mierror = $this->db->error();
                // https://codeigniter.com/userguide3/database/db_driver_reference.html?highlight=error#CI_DB_driver::display_error
                throw new Exception($mierror['message'], $mierror['code']);
            }

            return $return;
        }
        //catch(Exception $e){
        //    return null;
        //}
        // CCOO
        catch(Throwable $e) {
            //return null;

            logError($e,"Excepcion en metodo:".__METHOD__. " * namespace:" . __NAMESPACE__ .
                                    " * clase:" . __CLASS__ . " * funcion:". __FUNCTION__
                                    . " * directorio:" .  __DIR__ . " * archivo:" . __FILE__ );

            throw $e;
        }
        catch(Exception $e){
            // PHP5.5 ?
            return null;
        }
    }

    public function execAdHocAsArray($sql) {
        try {
            $return = $this->db->query($sql)->result_array();
            //$records=$this->db->query($sql)->result_array();
			//$return=array(
			//    "records"=>$records,
			//    "totalrecords"=>$totalrecords,
			//    "totalpages"=>$totalpages,
			//    "page"=>$values["page"]
			//    );

            // CCOO
            if( !$return ){
                $mierror = $this->db->error();
                // https://codeigniter.com/userguide3/database/db_driver_reference.html?highlight=error#CI_DB_driver::display_error
                throw new Exception($mierror['message'], $mierror['code']);
            }

            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                return $return;
            } else {
                return toUtf8($return);
            }
        }
        //catch(Exception $e){
        //    return null;
        //}
        // CCOO
        catch(Throwable $e) {
            //return null;
            throw $e;
        }
        catch(Exception $e){
            // PHP5.5 ?
            return null;
        }
    }

    /*Public GPI methods*/
    public function form($values){
        try {
            if(!isset($values["interface"])){$values["interface"]=("form");}
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
    public function brow($values){
        try {

            if(!isset($values["interface"])){$values["interface"]=("brow");}

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
    public function browWithoutLoad($values){
        try {

            if(!isset($values["interface"])){$values["interface"]=("brow");}

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
    public function excel($values){
        try {
            if(!isset($values["delimiter"])){$values["delimiter"]=(",");}
            if(!isset($values["interface"])){$values["interface"]=("excel");}
            $data["parameters"] = $values;
            $data["title"] = ucfirst(lang("m_".strtolower($values["model"])));
            $html=$this->load->view($values["interface"],$data,true);
            $ret=array("message"=>$html,"mime"=>"text/csv","mode"=>$values["mode"],"indisk"=>false);
            return $ret;
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function pdf($values){
        try {
            if(!isset($values["interface"])){$values["interface"]=("pdf");}
            $data["parameters"] = $values;
            $data["title"] = ucfirst(lang("m_".strtolower($values["model"])));
            $html=$this->load->view($values["interface"],$data,true);
            $this->load->library("m_pdf");
            $this->m_pdf->pdf->WriteHTML($html, 2);
            ob_end_clean();
            $html=$this->m_pdf->pdf->Output("legalizacion.pdf", "S");
            
            $ret=array("message"=>$html,"mime"=>"application/pdf","mode"=>$values["mode"],"indisk"=>false);
            return $ret;
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function edit($values){
        try {
            if(!isset($values["interface"])){$values["interface"]="abm";}
            if(!isset($values["attached_files"])){$data["attached_files"] = $this->getAttachments($values,null);}else{$data["attached_files"]=$values["attached_files"];}
            if(!isset($values["attached_messages"])){$data["attached_messages"] = $this->getMessages($values,null);}else{$data["attached_messages"]=$values["attached_messages"];}
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

    /*Public 1 a N relation methods*/
    public function saveRelations($values) {
        try {
            $ACTIVE=$this->createModel($values["module"],$values["model"],$values["model"]);
            $ACTIVE->deleteByWhere(array($values["key_field"]=>$values["key_value"]));
            foreach ($values["rel_values"] as $item){
                if ($item!=null and $item!="") {$ACTIVE->save(array("id"=>0),array($values["key_field"]=>$values["key_value"],$values["rel_field"]=>$item));}
            }
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>"",
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                "data"=>null,
                );
            }
            catch(Exception $e) {
                return logError($e,__METHOD__ );
            }
    }
    public function deleteRelations($values) {
        try {
            $ACTIVE=$this->createModel($values["module"],$values["model"],$values["model"]);
            $ACTIVE->deleteByWhere(array($values["key_field"]=>$values["key_value"]));
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>"",
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                "data"=>null,
                );
            }
            catch(Exception $e) {
                return logError($e,__METHOD__ );
            }
    }

    /*Public FILE attachment methods*/
    public function saveAttachments($values,$id,$opts=null){
        try {
                if(!isset($opts["module"])){$opts["module"]=MOD_BACKEND;}
                if(!isset($opts["model"])){$opts["model"]="Files_attached";}
                if(!isset($opts["new"])){$opts["new"]="new-files";}
                if(!isset($opts["del"])){$opts["del"]="del-files";}
                if(!isset($opts["newLinks"])){$opts["newLinks"]="new-links";}
                if(!isset($opts["delLinks"])){$opts["delLinks"]="del-links";}
                if(!isset($opts["id"])){$opts["id"]="id";}
                if(!isset($opts["source"])){$opts["source"]="src";}
                if(!isset($opts["filename"])){$opts["filename"]="filename";}
                if(!isset($opts["priority"])){$opts["priority"]="priority";}
                $this->prepareModule();
                $resolvedTableView=($this->module.$this->table);

                $ACTIVE=$this->createModel($opts["module"],$opts["model"],$opts["model"]);
                //Process new attached files
                $path=(FILE_ATTACHED."/".$this->table);
                if (isset($values[$opts["new"]]) and is_array($values[$opts["new"]])) {
                    foreach ($values[$opts["new"]] as $item){
                        if ($item[$opts["source"]]!=null and $item[$opts["source"]]!="") {
                            $code=opensslRandom(16);
                            $opts["filename"]=removeAccents($opts["filename"]);
                            $fullpath=($path."/".$code."_".$item[$opts["filename"]]);
                            saveBase64ToFile(array("data"=>$item[$opts["source"]],"path"=>$path,"fullPath"=>$fullpath));
                            if(!isset($opts["inner"])) {
                                $fields=array(
                                        "code"=>$code,
                                        "description"=>$item[$opts["filename"]],
                                        "created"=>$this->now,
                                        "verified"=>$this->now,
                                        "fum"=>$this->now,
                                        "src"=>$fullpath,
                                        "filename"=>basename($fullpath),
                                        "id_rel"=>$id,
                                        "table_rel"=>$resolvedTableView
                                );
                            } else {
                                $fields=$opts["inner"];
                                foreach(array_keys($fields) as $key){
                                    switch($key) {
                                        case "code":
                                            $fields[$key]=$code;
                                            break;
                                        case "description":
                                            $fields[$key]=$item[$opts["filename"]];
                                            break;
                                        case "data":
                                            $fields[$key]=$fullpath;
                                            break;
                                        case "mime":
                                            $fields[$key]=getMimeType($fullpath);
                                            break;
                                        case "basename":
                                            $fields[$key]=basename($fullpath);
                                            break;
                                        case "priority":
                                            $fields[$key]=$item[$opts["priority"]];
                                            break;
                                        default:
                                            if($fields[$key]=="="){$fields[$key]=$item[$key];}
                                            break;
                                    }
                                }
                            }
                            $ACTIVE->save(array("id"=>0),$fields);
                        }
                    }
                }
             if (isset($values[$opts["newLinks"]]) and is_array($values[$opts["newLinks"]])) {
                    foreach ($values[$opts["newLinks"]] as $item){
//log_message("error", "RELATED ".json_encode($item,JSON_PRETTY_PRINT));
                        if ($item[$opts["source"]]!=null and $item[$opts["source"]]!="") {
                            $code=opensslRandom(16);
                            $fullpath=$item["link"];

                            if(!isset($opts["inner"])) {
                                $fields=array(
                                        "code"=>$code,
                                        "description"=>$item["src"],
                                        "created"=>$this->now,
                                        "verified"=>$this->now,
                                        "fum"=>$this->now,
                                        "src"=>$fullpath,
                                        "filename"=>$fullpath,
                                        "id_rel"=>$id,
                                        "table_rel"=>$resolvedTableView
                                );
                            } else {
                                $fields=$opts["inner"];
                                foreach(array_keys($fields) as $key){
                                    switch($key) {
                                        case "code":
                                            $fields[$key]=$code;
                                            break;
                                        case "description":
                                            $fields[$key]=$item["src"];
                                            break;
                                        case "data":
                                            $fields[$key]=$fullpath;
                                            break;
                                        case "mime":
                                            $fields[$key]=getMimeType($fullpath);
                                            break;
                                        case "basename":
                                            $fields[$key]=basename($fullpath);
                                            break;
                                        case "priority":
                                            $fields[$key]=$item[$opts["priority"]];
                                            break;
                                        default:
                                            if($fields[$key]=="="){$fields[$key]=$item[$key];}
                                            break;
                                    }
                                }
                            }
                            $ACTIVE->save(array("id"=>0),$fields);
                        }
                    }
                }
                //Process del attached files
                if (isset($values[$opts["del"]]) and is_array($values[$opts["del"]])) {
                    foreach ($values[$opts["del"]] as $item){
                        $file=$ACTIVE->get(array("where"=>"id=".$item[$opts["id"]]));
                        foreach($file["data"] as $item){
                            unlink($item[$opts["source"]]);
                            $ACTIVE->delete(array("id"=>$item[$opts["id"]]));
                        }
                    }
                }
                if (isset($values[$opts["delLinks"]]) and is_array($values[$opts["delLinks"]])) {
                    foreach ($values[$opts["delLinks"]] as $item){
                       $ACTIVE->delete(array("id"=>$item[$opts["id"]]));
                    }
                }
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>"",
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                "data"=>null,
                );
            }
            catch(Exception $e) {
                return logError($e,__METHOD__ );
            }
    }
    public function getAttachments($values,$opts=null){
        try {
                if(!isset($opts["module"])){$opts["module"]=MOD_BACKEND;}
                if(!isset($opts["model"])){$opts["model"]="Files_attached";}
                if(!isset($opts["where"])){
                    $resolvedTableView=($values["module"]."_".$values["table"]);
                    $opts["where"]=("table_rel='".$resolvedTableView."' AND id_rel=".$values["id"]);
                }
                if(!isset($opts["view"])){$opts["view"]=$opts["model"];}
                if(!isset($opts["order"])){$opts["order"]="description ASC";}
                $ACTIVE=$this->createModel($opts["module"],$opts["model"],$opts["model"]);
                $ACTIVE->view=$opts["view"];
                return $ACTIVE->get(array("fields"=>$opts["fields"],"where"=>$opts["where"],"order"=>$opts["order"]));
            }
            catch(Exception $e) {
                return logError($e,__METHOD__ );
            }
    }

    /*Public MESSAGES attachment methods*/
    public function saveMessages($values,$id,$opts=null){
        try {
                if(!isset($opts["module"])){$opts["module"]=MOD_BACKEND;}
                if(!isset($opts["model"])){$opts["model"]="Messages_attached";}
                if(!isset($opts["new"])){$opts["new"]="new-messages";}
                if(!isset($opts["del"])){$opts["del"]="del-messages";}
                if(!isset($opts["id"])){$opts["id"]="id";}
                if(!isset($opts["source"])){$opts["source"]="message";}
                $this->prepareModule();
                $resolvedTableView=($this->module.$this->table);
                $ACTIVE=$this->createModel($opts["module"],$opts["model"],$opts["model"]);
                //Process new attached messages
                if (isset($values[$opts["new"]]) and is_array($values[$opts["new"]])) {
                    foreach ($values[$opts["new"]] as $item){
                        if ($item[$opts["source"]]!=null and $item[$opts["source"]]!="") {
                            $code=opensslRandom(16);
                            $fields=array(
                                    "code"=>$code,
                                    "description"=>$code,
                                    "created"=>$this->now,
                                    "verified"=>$this->now,
                                    "fum"=>$this->now,
                                    "message"=>$item[$opts["source"]],
                                    "id_user"=>$values["id_user_active"],
                                    "id_rel"=>$id,
                                    "table_rel"=>$resolvedTableView
                            );
                            $ret=$ACTIVE->save(array("id"=>0),$fields);
                            if ($ret["status"]!="OK"){throw new Exception($ret["message"],(int)$ret["code"]);}
                            if (isset($ret["data"]["id"])) {
                               $values["id"]=$ret["data"]["id"];
                               logMessagesAttached($this,$values,lang('msg_message_viewed'));
                            }
                        }
                    }
                }
                //Process del attached messages
                if (isset($values[$opts["del"]]) and is_array($values[$opts["del"]])) {
                    foreach ($values[$opts["del"]] as $item){
                        $file=$ACTIVE->get(array("where"=>"id=".$item[$opts["id"]]));
                        foreach($file["data"] as $item){
                            $ACTIVE->delete(array("id"=>$item[$opts["id"]]));
                        }
                    }
                }
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>"",
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                "data"=>null,
                );
            }
            catch(Exception $e) {
                return logError($e,__METHOD__ );
            }
    }
    public function getMessages($values,$opts=null){
        try {
                if(!isset($opts["module"])){$opts["module"]=MOD_BACKEND;}
                if(!isset($opts["model"])){$opts["model"]="Messages_attached";}
                if(!isset($opts["where"])){
                    $this->prepareModule();
                    $resolvedTableView=($this->module.$this->table);
                    $opts["where"]=("table_rel='".$resolvedTableView."' AND id_rel=".$values["id"]);
                }
                if(!isset($opts["view"])){$opts["view"]=$opts["model"];}
                $ACTIVE=$this->createModel($opts["module"],$opts["model"],$opts["model"]);
                $ACTIVE->view=$opts["view"];
                return $ACTIVE->get(array("where"=>$opts["where"],"ORDER BY created DESC"));
            }
            catch(Exception $e) {
                return logError($e,__METHOD__ );
            }
    }

    /*Private I/O methods*/
    private function getRecords($values) {
        try {
            $this->prepareModule();
            $resolvedTableView=($this->module.$this->view);
            $top = -1;
            if (isset($values["top"]) and $values["top"] != "") {$top = $values["top"];}
            if(!isset($values["page"])){$values["page"]=1;}
            if(!isset($values["pagesize"]) or $values["pagesize"]==""){$values["pagesize"]=25;}
            if(!isset($values["fields"]) or $values["fields"]==""){$values["fields"]="*";}
            
            //Total records
            $totalrecords = $top;
            $totalpages = 1;
            if ($top <= 0) {
                $this->db->select("count(*) as total");
                $this->db->from($resolvedTableView);
                if (isset($values["where"]) and $values["where"] != "") {
                    $this->db->where($values["where"]);
                }
                $sql1 = $this->db->get_compiled_select();
                $records1 = $this->db->query($sql1)->result_array();

                // CCOO revisar
                if (!$records1) {
                    $mierror = $this->db->error();
                    throw new Exception($mierror['message'], $mierror['code']);
                }
                $totalrecords = $records1[0]["total"];
                $totalpages = ceil($totalrecords / $values["pagesize"]);
            }
            //Filtered get
            $this->db->select($values["fields"]);
            $this->db->from($resolvedTableView);

            if(isset($values["where"]) and $values["where"]!=""){$this->db->where($values["where"]);}
            if(isset($values["order"]) and $values["order"]!=""){$this->db->order_by($values["order"]);}
            
            if((int)$values["pagesize"]>0){
                $from=(int)(($values["page"] - 1) * $values["pagesize"]);
                $size=(int)$values["pagesize"];
                if ( -1==$top )  {
                    // Caso mas normal
                    $this->db->limit($size,$from);
                }
                if ($top>0) {
                    // hay top
                    $this->db->limit($top);
                    $totalrecords = $totalrecords>$top?$totalrecords:$top;
                    $totalpages=1;
                }                    
                //$this->db->limit($size,$from);
            }
            $sql2=$this->db->get_compiled_select();
            log_message("error", "RELATED " . json_encode($sql2, JSON_PRETTY_PRINT));
            $records2=$this->db->query($sql2)->result_array();

            // CCOO revisar
            //if( !$records2 ){
            if( $records2 === false ) {
                $mierror = $this->db->error();
                throw new Exception($mierror['message'], $mierror['code']);
            }

            $return=array(
                "records"=>$records2,
                "totalrecords"=>$totalrecords,
                "totalpages"=>$totalpages,
                "page"=>$values["page"]
                );
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                return $return;
            } else {
                return toUtf8($return);
            }
        }
        catch(Throwable $e) {
            throw $e; // CCOO revisar
        }
        catch(Exception $e){
            return $e;
        }
    }
    private function setRecord($fields,$id) {
        try {
            $this->prepareModule();
            $resolvedTableView=($this->module.$this->table);
            if($id==0) {
                $resultado = $this->db->insert($resolvedTableView, $fields);
                if( !$resultado ){
                    $mierror = $this->db->error();
                    throw new Exception($mierror['message'], $mierror['code']);
                }
                $id=$this->db->insert_id();
            } else {
                // por update
                $this->db->where('id',$id);
                $u=$this->db->get_compiled_update();
                $resultado =$this->db->update($resolvedTableView, $fields);
                if( !$resultado ){
                    $mierror = $this->db->error();
                    throw new Exception($mierror['message'], $mierror['code']);
                }
            }
            return $id;
        }
        //catch(Exception $e){
        //    return $e;
        //}
        // CCOO
        catch(Throwable $e) {
            throw $e;
        }
        catch(Exception $e){
            throw $e;
        }
        //return $e;
    }
    private function delRecord($where) {
        try {
            $this->prepareModule();
            $resolvedTableView=($this->module.$this->table);
            //$this->db->delete($resolvedTableView, $where);
            $del = $this->db->delete($resolvedTableView, $where);
            // CCOO
            if( !$del ){
                $mierror = $this->db->error();
                // https://codeigniter.com/userguide3/database/db_driver_reference.html?highlight=error#CI_DB_driver::display_error
                throw new Exception($mierror['message'], $mierror['code']);
            }
            return true;
        }
        //catch(Exception $e){
        //    return $e;
        //}
        // CCOO
        catch(Throwable $e) {
            throw $e;
        }
        catch(Exception $e){
            // PHP5.5 ?
            throw $e;
        }
    }

    public function renameArrKey($arr, $oldKey, $newKey){
        if(!isset($arr[$oldKey])) return $arr; // Failsafe
        $keys = array_keys($arr);
        $keys[array_search($oldKey, $keys)] = $newKey;
        $newArr = array_combine($keys, $arr);
        return $newArr;
    }

    public function setRegistersPK ($registers, $whereIsPk = "id_pk") {
        // Permite que el framework siga buscando la columna id, y si el modelo no la tiene entonces la replica
        // en funcion de la PK dada.
        // Util cuando la PK de una tabla no se llama id.

        // Toma $values["records"], y los devuelve completos.

        //$clave=$values["data"]["id_field"];
        if(!isset($registers[$whereIsPk])) {
            return $registers;
        }
        $pk=$registers[$whereIsPk];
        if ($pk == null || $pk === null || $pk == "") {
            return $registers;
        }
        $existe=false;
        $num = count($registers["data"]);
        for($i = 0; $i < $num; $i++) {
            echo $i;
            foreach( $registers["data"][$i] as $key => $value ){
                $existe=false;
                // Si no existe la que necesito (id)
                if (!array_key_exists("id", $registers["data"][$i])) {
                    // Si verdaderamente existe la que supuestamente tengo
                    if ($key == $pk) {
                        $existe=true;

                        $registers["data"][$i]["id"] = $registers["data"][$i][$pk];
                        unset($registers["data"][$i][$pk]);

                        //$values["records"]["data"][$i] = renameArrKey($values["records"]["data"][$i], $pk, "id");
                        //$values["records"]["data"][$i]["id"] = (int)$values["records"]["data"][$i][$pk];
                        break;
                    }
                }
            }
            //if (!$existe) {
            //    $values["records"]["data"][$i]["id"] = $values["records"]["data"][$i][$pk];
            //}
        }
        return $registers;
    }



}
