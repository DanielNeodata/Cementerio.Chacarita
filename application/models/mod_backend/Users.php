<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

require APPPATH . '/libraries/CreatorJwt.php';

class Users extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }

    public function brow($values){
        try {
            $this->view="vw_users";
            $values["order"]="username ASC";
            $values["records"]=$this->get($values);
            $values["buttons"]=array(
                "new"=>true,
                "edit"=>true,
                "delete"=>true,
                "offline"=>false,
            );
            $values["columns"]=array(
                //array("field"=>"id","format"=>"number"),
                array("field"=>"image","format"=>"image"),
                array("field"=>"username","format"=>"email"),
                array("field"=>"type_user","format"=>"type"),
                array("field"=>"code","format"=>"type"),
                array("field"=>"","format"=>null),
                array("field"=>"","format"=>null),
            );
            $values["filters"]=array(
                array("name"=>"browser_search", "operator"=>"like","fields"=>array("username","type_user")),
                array("name"=>"browser_id_type_user", "operator"=>"=","fields"=>array("id_type_user")),
            );
            $values["controls"]=array(
                comboTypeUsers($this),
            );
            return parent::brow($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function edit($values){
        try {
            $values["interface"]=(MOD_BACKEND."/users/abm");
            $values["page"]=1;
            $values["where"]=("id=".$values["id"]);
            $values["records"]=$this->get($values);

            $parameters_id_type_user=array(
                "model"=>(MOD_BACKEND."/Type_users"),
                "table"=>"type_users",
                "name"=>"id_type_user",
                "class"=>"form-control dbase",
                "empty"=>true,
                "id_actual"=>secureComboPosition($values["records"],"id_type_user"),
                "id_field"=>"id",
                "description_field"=>"description",
                "get"=>array("order"=>"description ASC","pagesize"=>-1),
            );
            $parameters_id_group=array(
                "model"=>(MOD_BACKEND."/Groups"),
                "table"=>"groups",
                "name"=>"id_group",
                "class"=>"multiselect dbase",
                "actual"=>array("model"=>(MOD_BACKEND."/Rel_users_groups"),"table"=>"rel_users_groups","id_field"=>"id_user","id_value"=>$values["id"]),
                "id_field"=>"id",
                "description_field"=>"description",
                "options"=>array("order"=>"description ASC","pagesize"=>-1),
                "function"=>"get",
            );
            $values["controls"]=array(
                "id_type_user"=>getCombo($parameters_id_type_user,$this),
                "id_group"=>getMultiSelect($parameters_id_group,$this),
            );
            return parent::edit($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function save($values,$fields=null){
        try {
            if (!isset($values["id"])){$values["id"]=0;}
            if (!isset($values["image"])){$values["image"]=null;}
            if (!isset($values["phone"])){$values["phone"]=null;}
            $id=(int)$values["id"];
            if($id==0){
                if($fields==null) {
                    $fields = array(
                        'code' => $values["code"],
                        'description' => $values["description"],
                        'created' => $this->now,
                        'verified' => $this->now,
                        'offline' => null,
                        'fum' => $this->now,
                        'id_type_user' => secureEmptyNull($values,"id_type_user"),
                        'id_master' => null,
                        'username' => $values["username"],
                        'password' => md5($values["password"]),
                        'image' => $values["image"],
                        'phone' => $values["phone"],
                        'token_push' => null,
                        'token_authentication' => null,
                        'token_transaction' => null,
                        'token_authentication_expire' => null,
                        'token_authentication_created' => null,
                        'token_transaction_expire' => null,
                        'token_transaction_created' => null,
                        'uid_firecloud' => null,
                    );
                }
            } else {
                if($fields==null) {
                    $fields = array(
                        'code' => $values["code"],
                        'description' => $values["description"],
                        'fum' => $this->now,
                        'id_type_user' => secureEmptyNull($values,"id_type_user"),
                        'id_master' => null,
                        'username' => $values["username"],
                        'image' => $values["image"],
                        'phone' => $values["phone"],
                        'token_authentication' => null,
                    );
                    if(strlen($values["password"])){$fields+=["password"=>md5($values["password"])];}
                }
            }
            $saved=parent::save($values,$fields);
            if($saved["status"]=="OK"){
               $params_groups=array(
                    "module"=>MOD_BACKEND,
                    "model"=>"Rel_users_groups",
                    "table"=>"rel_users_groups",
                    "key_field"=>"id_user",
                    "key_value"=>$saved["data"]["id"],
                    "rel_field"=>"id_group",
                    "rel_values"=>(isset($values["id_group"]) ? $values["id_group"] :array())
               );
               parent::saveRelations($params_groups);
            }
            return $saved;
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function delete($values){
        try {
            if((int)$values["id"]==0) {
                $user=$this->get(array("where"=>"username='".$values["email"]."'"));
                if(isset($user["data"][0])){
                   $values["id"]=$user["data"][0]["id"];
                }
            }
            $deleted=parent::delete($values);
            if($deleted["status"]=="OK"){
               $params_apps=array(
                    "module"=>MOD_BACKEND,
                    "model"=>"Rel_users_applications",
                    "table"=>"rel_users_applications",
                    "key_field"=>"id_user",
                    "key_value"=>$values["id"],
               );
               $params_groups=array(
                    "module"=>MOD_BACKEND,
                    "model"=>"Rel_users_groups",
                    "table"=>"rel_users_groups",
                    "key_field"=>"id_user",
                    "key_value"=>$values["id"],
               );
               $params_channels=array(
                    "module"=>MOD_BACKEND,
                    "model"=>"Rel_channels_users",
                    "table"=>"rel_channels_users",
                    "key_field"=>"id_user",
                    "key_value"=>$values["id"],
               );
               parent::deleteRelations($params_apps);
               parent::deleteRelations($params_groups);
               parent::deleteRelations($params_channels);
            }
            return $deleted;
        }
        catch(Exception $e) {
            return logError($e,__METHOD__ );
        }
    }

    public function logout($values){
        try {
            $this->load->library("session");
            $this->session->set_userdata(array("logged"=>false));
            $this->psession=$this->session;
            
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>"",
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                "data"=>null,
                );
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function authenticate($values){
        try {
            $this->view="vw_users";
            if(!isset($values["try"])){$values["try"]="LOCAL";}
            if(!isset($values["scoope"])){$values["scoope"]="backend";}
            $values["page"]=1;
            $values["where"]=("username='".$values["username"]."' AND password='".md5($values["password"])."' AND offline IS null");
            $users=parent::get($values);
            $values["id_user_active"]=$users["data"][0]["id"];
            
            return $this->buildTokenAuthentication($users,$values["scoope"]);
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function reAuthenticate($values){
        try {
            if(!isset($values["mode"])){$values["mode"]="backend";}
            $values["page"]=1;
            $values["where"]=("id='".$values["id_user_active"]."' AND token_authentication='".$values["token_authentication"]."' AND offline IS null");
            $users=parent::get($values);
            return $this->buildTokenAuthentication($users,$values["mode"]);
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function generateTokenTransaction($values){
        try {
            /*--------------------------------------------------------------*/
            /* Generate token_authentication, with each user authentication */
            /*--------------------------------------------------------------*/
            $values["len"]=8;
            $auth=$this->userTokenTransaction($values);
            if ($auth["status"]=="ERROR") {throw new Exception($auth["message"],(int)$auth["code"]);}
            /*--------------------------------------------------------------*/
            $data=array(
            "token_transaction"=>$auth["data"]["token_transaction"],
            "token_transaction_created"=>$auth["data"]["token_transaction_created"],
            "token_transaction_expire"=>$auth["data"]["token_transaction_expire"],
            );
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>"TokenTransaction",
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                "data"=>$data,
                );
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }

    public function generateTokenPush($values){
        try {
            $values["id"]=$values["id_user_active"];
            $fields = array('token_push' => $values["token_push"]);
            return parent::save($values,$fields);
        } catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function verifyTokenAuthentication($values){
        try {
            $values["page"]=1;
            //single log in same user!
            $values["where"]=("id='".$values["id_user_active"]."' AND token_authentication='".$values["token_authentication"]."' AND offline IS null");
            // N log in same user
            $values["where"] = ("id='" . $values["id_user_active"] . "' AND offline IS null");
            $users=parent::get($values);
            if ($users["status"]=="OK") {
                if (isset($users["data"][0])) {
                    $token_authentication_expire=$this->encryption->decrypt($users["data"][0]["token_authentication_expire"]);
                    if(strtotime("now") > strtotime($token_authentication_expire)){throw new Exception(lang("error_5400"),5400);}
                    return array(
                        "code"=>"2000",
                        "status"=>"OK",
                        "message"=>"",
                        "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                        "data"=>array("id"=>$users["data"][0]["id"]),
                        );
                } else {
                    throw new Exception(lang("error_5401"),5401);
                }
            } else {
                throw new Exception($users["status"]["message"],(int)$users["status"]["code"]);
            }
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function verifyTokenTransaction($values){
        try {
            $values["page"]=1;
            $values["where"]=("phone='".$values["phone"]."' AND token_transaction='".$values["token_transaction"]."' AND offline IS null");
            $users=parent::get($values);
            if ($users["status"]=="OK") {
                if (isset($users["data"][0])) {
                    $token_transaction_expire=$this->encryption->decrypt($users["data"][0]["token_transaction_expire"]);
                    if(strtotime("now") > strtotime($token_transaction_expire)){throw new Exception(lang("error_5500"),5500);}
                    return array(
                        "code"=>"2000",
                        "status"=>"OK",
                        "message"=>"",
                        "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                        "data"=>array("id"=>$users["data"][0]["id"]),
                        );
                } else {
                    throw new Exception(lang("error_5501"),5501);
                }
            } else {
                throw new Exception($users["status"]["message"],(int)$users["status"]["code"]);
            }
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function createToken($values) {
        try {
            $tokenData['uniqueId'] = '11';
            $tokenData['role'] = 'alamgir';
            $tokenData['timeStamp'] = Date('Y-m-d h:i:s');
            $this->objOfJwt = new CreatorJwt();
            $jwtToken = $this->objOfJwt->GenerateToken($tokenData);
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>"",
                "token"=>$jwtToken,
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
            );
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function readToken($values) {
        try {
            $this->objOfJwt = new CreatorJwt();
            $jwtData = $this->objOfJwt->DecodeToken($values['token']);
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>"",
                "token"=>$jwtData,
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
            );
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }

    private function buildTokenAuthentication($users,$scoope){
        try {
            if ($users["status"]=="OK") {
                if (isset($users["data"][0])) {
                    $image=(FILES_USUARIOS.$users["data"][0]["username"].".jpg");
                    if (!file_exists($image)) {$image=(FILES_USUARIOS."default.jpg");}
                    if (!file_exists($image)) {$image=$users["data"][0]["image"];}
                    $image=fileToBase64($image);
                    $data=array(
                            "id"=>$users["data"][0]["id"],
                            "code"=>$users["data"][0]["code"],
                            "description"=>$users["data"][0]["description"],
                            "username"=>$users["data"][0]["username"],
                            "image"=>$image,
                            "master_account"=>$users["data"][0]["master_account"],
                            "master_image"=>$users["data"][0]["master_image"],
                            "id_type_user"=>$users["data"][0]["id_type_user"],
                    );
                    switch($scoope) {
                       case "site": //Verification Token NOT GENERATED! That's ok!
                          $session=array(
                              "logged"=>true,
                              "id_user"=>$users["data"][0]["id"],
                              "id_type_user"=>$users["data"][0]["id_type_user"],
                              "type_user"=>$users["data"][0]["type_user"],
                              "id_master"=>$users["data"][0]["id_master"],
                              "username"=>$users["data"][0]["username"],
                              "image"=>$image,
                              "master_account"=>$users["data"][0]["master_account"],
                              "master_image"=>$users["data"][0]["master_image"],
                          );
                          $this->load->library("session");
                          $this->session->set_userdata($session);
                          $this->psession=$this->session->userdata;
                          break;
                       default: // Verification Token GENERATED. 
                          /*--------------------------------------------------------------*/
                          /* Generate token_authentication, with each user authentication */
                          /*--------------------------------------------------------------*/
                          $params=array("id"=>(int)$users["data"][0]["id"],"len"=>128);
                          $auth=$this->userTokenAuthentication($params);
                          if ($auth["status"]=="ERROR") {throw new Exception(lang("error_5201"),5201);}
                          /*--------------------------------------------------------------*/
                          $data["token_authentication"]=$auth["data"]["token_authentication"];
                          $data["token_authentication_created"]=$auth["data"]["token_authentication_created"];
                          $data["token_authentication_expire"]=$auth["data"]["token_authentication_expire"];
                          break;
                    }

                    return array(
                        "code"=>"2000",
                        "status"=>"OK",
                        "message"=>"Authenticated",
                        "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                        "data"=>$data,
                        );
                } else {
                    throw new Exception(lang("error_5200"),5200);
                }
            } else {
                throw new Exception($users["status"]["message"],(int)$users["status"]["code"]);
            }
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    private function userTokenAuthentication($values){
        try {
            if (!is_numeric($values["id"]) or !isset($values["id"])){$values["id"]=0;}
            if (!is_numeric($values["len"]) or !isset($values["len"])){$values["len"]=256;}
            $token_authentication = opensslRandom((int)$values["len"]);
            $token_authentication_expire = $this->encryption->encrypt((string)date(FORMAT_DATE,strtotime(TOKEN_AUTHENTICATION_TTL,strtotime($this->now))));
            $token_authentication_created = $this->encryption->encrypt((string)date(FORMAT_DATE,strtotime($this->now)));

            $fields = array(
                'token_authentication' => $token_authentication,
                'token_authentication_expire' => $token_authentication_expire,
                'token_authentication_created' => $token_authentication_created,
                );
            $save=parent::save($values,$fields);
            $fields["token_authentication_expire"]=$this->encryption->decrypt($token_authentication_expire);
            $fields["token_authentication_created"]=$this->encryption->decrypt($token_authentication_created);

            if ($save["status"]=="OK") {
                return array(
                    "code"=>"2000",
                    "status"=>"OK",
                    "message"=>"Authenticated",
                    "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                    "data"=>$fields,
                    );
            } else {
                throw new Exception(lang("error_5201"),5201);
            }
        } catch(Exception $e) {
            return logError($e,__METHOD__ );
        }
    }
    private function userTokenTransaction($values){
        try {
            $values["id"]=$values["id_user_active"];
            $token_transaction = getSecureRandomize(11111111, 99999999);
            $token_transaction_expire = $this->encryption->encrypt((string)date(FORMAT_DATE,strtotime(TOKEN_TRANSACTION_TTL,strtotime($this->now))));
            $token_transaction_created = $this->encryption->encrypt((string)date(FORMAT_DATE,strtotime($this->now)));

            $fields = array(
                'token_transaction' => $token_transaction,
                'token_transaction_expire' => $token_transaction_expire,
                'token_transaction_created' => $token_transaction_created,
                );
            $save=parent::save($values,$fields);
            $fields["token_transaction_expire"]=$this->encryption->decrypt($token_transaction_expire);
            $fields["token_transaction_created"]=$this->encryption->decrypt($token_transaction_created);

            if ($save["status"]=="OK") {
                return array(
                    "code"=>"2000",
                    "status"=>"OK",
                    "message"=>"",
                    "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                    "data"=>$fields,
                    );
            } else {
                throw new Exception(lang("error_5201"),5201);
            }
        }
        catch(Exception $e) {
            return logError($e,__METHOD__ );
        }
    }
    private function generateApiKey($length,$id_user)
        {
            $characters='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0987654321'.time();
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = $length; $i > 0; $i--){$randomString .= $characters[rand(0, $charactersLength - 1)];}
            $token_authentication=md5($randomString);
            $this->save(array("id"=>$id_user),array("token_authentication"=>$token_authentication));
            return $token_authentication;
        }
}
