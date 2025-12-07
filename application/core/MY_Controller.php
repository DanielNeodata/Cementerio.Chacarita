<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class MY_Controller extends CI_Controller {
    public $legacy = false;

    public $ready = false;
    public $status = null;
    public $language = DEFAULT_LANGUAGE;
    public $parameters = array();
    public $psession=null;
    public $profile=null;

    public function __construct() {
        set_time_limit(0);
        parent::__construct();
    }

    public function init($lang=null){
        try {
            if ($lang!=null){$this->language=$lang;}
            $this->ready = true;
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>"",
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                );
        }
        catch(Exception $e) {
            $this->ready = false;
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
    public function neocommand($forced=false){
        try {
            if ($this->legacy) {throw new Exception(lang("error_legacy"),-1);}
            if(!$forced){
                $raw=$this->rawInput();
                if ($raw!=null)  {throw new Exception($raw);}
                $this->status=$this->init();
            }
            if (!isset($_POST["force"])) {$_POST["force"]="";}
            if (!isset($_POST["function"])) {throw new Exception(lang("error_5100"),5100);}
            if (!isset($_POST["module"])) {throw new Exception(lang("error_5101"),5101);}
            if (!isset($_POST["table"])) {throw new Exception(lang("error_5102"),5102);}
            if (!isset($_POST["model"])) {throw new Exception(lang("error_5103"),5103);}
            if (!isset($_POST["id_sucursal"])) {throw new Exception(lang("error_5116"),5116);}
            if (!isset($_POST["sucursal"])) {throw new Exception(lang("error_5117"),5117);}
            $DB_SELECTED=$this->load->database($_POST["sucursal"],true);
            $this->db=$DB_SELECTED;

            if (!isset($_POST["exit"])) {$_POST["exit"]="output";}
            $baseserver="http://";
            if ((int)$_SERVER["SERVER_PORT_SECURE"]!=0){$baseserver="https://";}
            $baseserver.=$_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"]."/";
            $_POST["baseserver"]=$baseserver;
            $_POST["check_token"]=true;
            switch($_POST["model"]){
                case "forcedLogGeneral";
                    logGeneral($this,$_POST,$_POST["model"],$_POST["trace"]);
                    return null;
                case "xxxx": //Exception model NOT uses authentication
                   $_POST["check_token"]=false;
                   break;
                default:
                   if (isset($_POST["mode"])) {
                       if ($_POST["mode"]==bin2hex(getEncryptionKey())) {$_POST["check_token"]=false;}
                   }
                   break;
            }
            if ($_POST["force"]=="force") {$_POST["check_token"]=false;}
            /*-----------------------------------*/
            $this->parameters=$_POST;
            /*-----------------------------------*/
            if ($_POST["check_token"]) {
                if (!isset($_POST["id_user_active"]) or $_POST["id_user_active"]=="") {throw new Exception(lang("error_5107"),5107);}
                if (!isset($_POST["token_authentication"]) or $_POST["token_authentication"]=="") {throw new Exception(lang("error_5109"),5109);}
                $USERS=$this->createModel(MOD_BACKEND,"Users","Users");
                $verify=$USERS->verifyTokenAuthentication($this->parameters);
                if($verify["status"]!="OK"){throw new Exception($verify["message"],(int)$verify["code"]);}
            }
            $return=$this->InnerResolver();
            if ($return["status"] == "ERROR") {throw new Exception("No se pudo resolver la consulta solicitada", 401);}
            $this->{$_POST["exit"]}($return);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ), 'HTTP/1.0 401 UNAUTHORIZED');
        }
    }

    public function rawInput(){
        try {
            $str=trim(file_get_contents("php://input"));
            parse_str($str, $parsed);
            $str=json_encode($parsed);
            if (!$_POST) {$_POST = json_decode($str,true);}
            return null;
        }
        catch(Exception $e) {
            return $e;
        }
    }
    public function InnerResolver(){
        try {
            
            // Original
            //$ACTIVE=$this->createModel($this->parameters["module"],$this->parameters["model"],$this->parameters["model"]);
            // CCOO
            $ACTIVE=$this->createModel($this->parameters["module"],$this->parameters["model"],$this->parameters["table"]);
            
            return $ACTIVE->{$this->parameters["function"]}($this->parameters);
        }
        catch(Exception $e){
            return logError($e,__METHOD__." model: ".$this->parameters["model"], 'HTTP/1.0 401 UNAUTHORIZED');
        }
    }
    public function output($return,$rVal='HTTP/1.0 200 OK'){
        //'HTTP/1.0 401 UNAUTHORIZED'
        $this->output
            ->set_header($rVal)
            ->set_header('Access-Control-Allow-Origin: *')
            ->set_header('Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method')
            ->set_header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE')
            ->set_content_type('application/json','utf-8')
            ->set_output(json_encode($return));
    }
    public function download($return){
        if(!isset($return["indisk"])){$return["indisk"]=true;}
        $this->load->helper('download');
        $this->load->helper('file');
        if($return["indisk"]){
           $return["filename"]=basename($return["message"]);
           $return["mime"]=getMimeType($return["message"]);
           $return["message"]=file_get_contents($return["message"]);
        } else {
           if(!isset($return["filename"])) {$return["filename"]=uniqid('cem_',true).".".explode("/",$return["mime"])[1];}
        }

        switch($return["mode"]) {
           case "html":
              $this->output
                  ->set_status_header(200)
                  ->set_content_type('text/html','utf-8')
                  ->set_output($return["message"])
                  ->_display();
                  break;
           case "view":
//log_message("error", "DOWNLOAD ".json_encode($return,JSON_PRETTY_PRINT));
              $this->output
                  ->set_status_header(200)
                  ->set_header('Content-Disposition: attachment; filename='.$return["filename"])
                  ->set_content_type($return["mime"])
                  ->set_output($return["message"])
                  ->_display();
                  break;
           case "download":
              force_download($return["filename"], $return["message"]);
              break;
        }
    }
}
