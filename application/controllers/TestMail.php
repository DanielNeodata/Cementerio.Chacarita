<?php
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
defined('BASEPATH') OR exit('No direct script access allowed');
/*---------------------------------*/

class TestMail extends MY_Controller {
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
	{
        $this->status=$this->init();
        $data["title"] = TITLE_GENERAL;
        $data["title_page"] = TITLE_PAGE;
        $data["status"] = $this->status;
        $data["language"] = $this->language;
        $data["header"] = $this->load->view('common/_header',$data, true);
        $data["footer"] = $this->load->view('common/_footer',$data, true);
        try {
            if (!$this->ready){throw new Exception(lang("error_5002"),5002);}
            $this->load->view('login',$data);
        }
        catch (Exception $e){
            $data["code"]=$e->getCode();
            $data["message"]=$e->getMessage();
            $data["title"] = $e->getCode();
            $this->load->view('common/_error',$data);
        }
	}

    public function test($prm){
        $this->load->library('email');
        $titulo = "";
        $cuerpo = "";
        $destinatario = "";
        $config = array();
        switch($prm){
            case "1":
                // opcion sin autenticar. funciona, pero no manda el mail afuera de SF
                $config = array(
                    'protocol' => 'smtp',
                    //'smtp_host' => 'smtp.sagradafamilia.com.ar',
                    'smtp_host' => 'mail.dreamHost.com',
                    'smtp_user' => 'soporte@soltec.net.ar',
                    'smtp_user' => '!#Eneritec.135...!',
                    'smtp_port' => 587,
                    'smtp_crypto' => 'ssl', //can be 'ssl' or 'tls' for example
                    //'smtp_crypto' => 'tls', //can be 'ssl' or 'tls' for example
                    'mailtype' => 'html', //plaintext 'text' mails or 'html'
                    //'charset' => 'iso-8859-1',
                    'wordwrap' => TRUE
                );
                $this->email->initialize($config);
                $this->email->set_newline("\r\n");
        
                //$this->email->from('lmoltrasio@sagradafamilia.com.ar', 'Luciano');
                $this->email->from('c@soltec.net.ar', 'CCO');
                $this->email->to(array(
                                    'cconnolly@sagradafamilia.com.ar',
                                    )
                                );

                break;             
            case "5":
                // Test Britanico
                $config = array(
                    'protocol' => 'smtp',
                    'smtp_host' => 'localhost',
                    'smtp_user' => '',
                    'smtp_pass' => '',
                    'smtp_port' => 25,
                    //'smtp_crypto' => 'ssl', //can be 'ssl' or 'tls' for example
                    'mailtype' => 'html', //plaintext 'text' mails or 'html'
                    //'charset' => 'iso-8859-1',
                    'wordwrap' => TRUE
                );
                $this->email->initialize($config);
                $this->email->set_newline("\r\n");
        
                $this->email->from('cementeriobritanico@ah000325.ferozo.com', 'CCO');
                $this->email->to(array(
                                    'soporte@soltec.net.ar',
                                    )
                                );
                break;                                  
            default:
                $config = array(
                    'protocol' => 'smtp',
                    'smtp_host' => 'smtp.sagradafamilia.com.ar',
                    'smtp_user' => 'cconnolly',
                    //'smtp_port' => 25,
                    'smtp_port' => 465,
                    'smtp_crypto' => 'ssl', //can be 'ssl' or 'tls' for example
                    'mailtype' => 'html', //plaintext 'text' mails or 'html'
                    //'charset' => 'iso-8859-1',
                    'wordwrap' => TRUE
                );
                $this->email->initialize($config);
                $this->email->set_newline("\r\n");
        
                $this->email->from('cconnolly@sagradafamilia.com.ar', 'Christian');
                $this->email->to(array('lmoltrasio@sagradafamilia.com.ar'));
                break;    
        }
        $this->email->subject('Esta es una prueba del envio de mail por SMTP');
        $this->email->message('Mail de prueba');
        $r = $this->email->send(FALSE);  // autoclear
        $d = $this->email->print_debugger();
        $this->email->clear();
        $html = "probando";
        $data = array(
            "resultado" => $r,
            "mensaje" => $d,
        );
        $this->load->view('TestMail/prueba.php', $data);
    }
    public function test2($prm){
        $this->load->library('email');
        $CI = &get_instance();
        $sqlConfiguracionMail = " select top 1 m.ID, m.Descripcion, m.Direccion, m.Puerto, m.UsaSSL, m.usuario, m.clave, " .
        " m.cuentaDefault, m.nombreDefault, m.protocolo " . 
        " from dbo.mailers m " .
        " order by m.ID desc";
        $prmConfiguracionMail = array( "ID" => 1,);
        $rc = $CI->db->query($sqlConfiguracionMail, $prmConfiguracionMail);
        if (!$rc) {
            // si dio false estoy en problemas.....hacer un throw o raise...
            $mierror = $this->db->error();
            throw new Exception($mierror['message'], $mierror['code']);
        } else {
            $configMail = $rc->result_array();
        }
        $config = array(
            'protocol' => $configMail[0]["protocolo"], // smtp
            'smtp_host' => $configMail[0]["Direccion"], // mail server
            'smtp_port' => $configMail[0]["Puerto"], //25
            'smtp_auth' => null, // Whether to use SMTP authentication, boolean TRUE/FALSE. If this option is omited or if it is NULL, then SMTP authentication is used when both $config['smtp_user'] and $config['smtp_pass'] are non-empty strings.
            'smtp_user' => $configMail[0]["usuario"], // ""
            'smtp_pass' => $configMail[0]["clave"], // ""
            //'smtp_crypto' => 'ssl', //can be 'ssl' or 'tls' for example
            'mailtype' => 'html', //plaintext 'text' mails or 'html'
            //'charset' => 'iso-8859-1',
            'wordwrap' => TRUE
        );
        $crypto = "";
        if ($configMail[0]["UsaSSL"]=="N") {
            $crypto = "";
        } else {
            $crypto = "ssl";
        }
        $config["smtp_crypto"]= $crypto;
        
        $this->email->initialize($config);
        $this->email->set_newline("\r\n");
        $titulo = "Prueba";
        $cuerpo = "Esta es una prueba";
        $destinatario = "soporte@soltec.net.ar";
        $this->email->from('ris@sagradafamilia.com.ar', 'Ris');
        $this->email->to(array($destinatario));
        $this->email->subject('Esta es una prueba del envio de mail por SMTP');
        $html = "Probando el <b>envio</b> de mail";
        $this->email->message($html);
        $r = $this->email->send(FALSE);  // autoclear
        $d = $this->email->print_debugger();
        $this->email->clear();
        $data = array(
            "resultado" => $r,
            "mensaje" => $d,
        );
        $this->load->view('TestMail/prueba.php', $data);
    }
    public function test3(){
        $r = "test3";
        $d = "Esta es una prueba";
        $data = array(
            "resultado" => $r,
            "mensaje" => $d,
        );
        $this->load->view('TestMail/prueba.php', $data);
    }
}