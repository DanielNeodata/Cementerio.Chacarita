<?php
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
defined('BASEPATH') OR exit('No direct script access allowed');
/*---------------------------------*/

class AvisoDeDeuda extends MY_Controller {
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

    public function getDeudaByPagador(){
        $this->load->view('HOLA');
    }
    /**
     * controller + model del acceso del cliente al aviso de deuda
     *
     * Controller + Model. Acceso publico al aviso de deuda mediate una hash. El acceso es
     * algo asi: http://localhost:4001/avisodeuda/ADQnw4M3wyMDIyfDg
     * 
     * Al ingresar, marca el aviso como accedido
     *
     * @param string $hash. AD+base64 -> el base64 esta confirmado de empresa|IdPagador|anio|mes.
     *                                   B Chacarita - N Nogues / anio y mes como entero.
     * @return array Html del resumen con la deuda, incluye barcode Pago Facil ("html","paginas","caracteres","skips","registros")
     * 
     */
    public function getDeudaByHash($hash){

        // B|31|2022|6     B Chacarita  N Nogues  | Id Pagador | anio | mes   
        // -> AD + base64 ->   ADQnwzM3wyMDIyfDY
        // http://localhost:4001/avisodeuda/ADQnw4M3wyMDIyfDg
        
        $datos=array();

        $htmlFinal="";

        $this->load->helper('aviso_deuda');
        $datos = getDatosFromUrlAvisoDeudaMensual($hash); // hash sin url
        $deuda = generateAvisoResumenAsHtml($datos["idPagador"], $datos["anio"], $datos["mes"], $datos["empresa"],-1);

        $data = array(
            "deuda" => $deuda,
        );

        $rc = array();
        $rc = marcarAvisoMensualComoAccedido($idPagador, $anio, $mes, $empresa);
        
        $this->load->view('AvisoDedeuda/AvisoIndividual.php', $data);
    }
    /**
     * controller + model del acceso del cliente al aviso de deuda
     *
     * Controller + Model. Acceso publico al aviso de deuda mediate una hash. El acceso es
     * algo asi: http://localhost:4001/avisodeuda2/B/83/2022/8
     *
     * Al ingresar, marca el aviso como accedido
     * 
     * @param mixed $empresa. B Chacarita - N Nogues 
     * @param mixed $idPagador.
     * @param mixed $anio. 
     * @param mixed $mes. 
     * @return array Html del resumen con la deuda, incluye barcode Pago Facil ("html","paginas","caracteres","skips","registros")
     * 
     */
    public function getDeudaByKey($empresa, $idPagador, $anio, $mes){
        
        $datos=array();

        $htmlFinal="";

        $this->load->helper('aviso_deuda');
        //$datos = getDatosFromUrlAvisoDeudaMensual($hash); // hash sin url
        $datos = array(
                    "idPagador"=>$idPagador,
                    "anio"=>$anio,
                    "mes"=>$mes,
                    "empresa"=>strtoupper($empresa),
                );
        $deuda = generateAvisoResumenAsHtml($datos["idPagador"], $datos["anio"], $datos["mes"], $datos["empresa"], -1);

        $data = array(
            "deuda" => $deuda,
        );

        $rc = array();
        $rc = marcarAvisoMensualComoAccedido($idPagador, $anio, $mes, $empresa);
        $this->load->view('AvisoDedeuda/AvisoIndividual.php', $data);
    } 
    /**
     * controller para obtener archivo PDF luego de la view
     *
     * controller para obtener archivo PDF luego de la view, invocado desde un http-equiv="refresh" content="5; ....
     * que esta en el HTML que devuelve el controller getDeudaByHash
     *
     * @param string $hash.
     * 
     */
    public function getFile() {
        // https://forum.codeigniter.com/thread-53607.html
        $this->load->helper('download');
        //$img_url = base_url().'images/myimage.jpg';
        $img_url = "pdf/legalizacion.pdf";
        $data = file_get_contents($img_url);
        $name = 'archivoXX.pdf';
        force_download($name, $data); // si hay segundo parametro con el contenido, el primero es el nombre del archivo propuesto que va a tomar en la descarga.
    }


    public function prueba2($id){
        // GET /avisosprueba2
        $a = $this->load->view('AvisoDedeuda/hola', '', true);
        $data = array(
            "id" => $id,
        );
        $this->load->view('AvisoDedeuda/hola2', $data);
    }

    public function prueba($id){
       
        $britanico = $this->load->database('neo_britanico', true);
        $queryB = $britanico->query("select * from dbo.EmpresaSucursal");
        $n = $queryB->num_rows();
        $res = $queryB->result_array();
        foreach ($res as $row)
        {
            echo $row["Razon_Social"];
        }
        $britanico->close();

        $nogues = $this->load->database('neo_nogues', true);
        $queryN = $nogues->query("select * from dbo.EmpresaSucursal");
        $n = $queryN->num_rows();
        $res = $queryN->result_array();
        foreach ($res as $row)
        {
            echo $row["Razon_Social"];
        }
        $nogues->close();

    }


    function barcode($id)
    {
        // Barcode generation function, using Zend library
        // http://shikhapathak6.blogspot.com/2020/02/generate-barcode-using-codeigniter-with.html
        // https://github.com/desta88/Codeigniter-Barcode

        // https://framework.zend.com/manual/1.12/en/zend.barcode.objects.html

        $this->load->library('zend');
        $this->zend->load('Zend/Barcode');
        //$id=$this->input->post('id');
        //$barcodeOptions = array('text' => $id, 'width'=> '100', 'height'=> '20');
        //  https://gist.github.com/ezimuel/3129095
        $barcodeOptions = array('text' => $id,
                                'barHeight' => '50',
                                'drawText' => true,
                                'verticalPosition'   => 'middle',
                                'barHeight' => '50',
                                'factor' => 2,
                                'font' => 5,
                                //'barThickWidth' => '5',
                                //'barThinWidth' => '2',
                                'stretchText' => false,
                                //'fontSize' => '4',
                            );
        $rendererOptions = array('imageType'          =>'png', 
                                'horizontalPosition' => 'center', 
                                'verticalPosition'   => 'top',
                                //'barHeight' => '50',
                                //'drawText' => false,
                                //'barThickWidth' => '5',
                                //'barThinWidth' => '2',
                                //'factor' => 10,
                                //'stretchText' => true,
                                //'fontSize' => '4',
                                //'width'=> '200', 
                                //'height'=> '40',
                            );
            
        //$imageResource=Zend_Barcode::factory('code128', 'image', $barcodeOptions, $rendererOptions)->render();
        //$imageResource_=Zend_Barcode::factory('code128', 'image', $barcodeOptions, $rendererOptions);
        $imageResource=Zend_Barcode::factory('code128', 'image', $barcodeOptions, $rendererOptions)->draw();

        ob_start(); // Let's start output buffering.
            imagepng($imageResource); //This will normally output the image, but because of ob_start(), it won't.
            $contents = ob_get_contents(); //Instead, output above is saved to $contents
        ob_end_clean(); //End the output buffer.
        
        $dataUri = "data:image/png;base64," . base64_encode($contents);

        /*
        View
        <img src="<?php echo base_url(); ?>index.php/suggestion/barcode"  alt="not show" /></div>
        Display barcode as simple image
        $imageResource = Zend_Barcode::factory('code128', 'image', array('text'=>$barcode), array())->draw();
        imagepng($imageResource, 'public_html/img/barcode.png');
        */

        //return $dataUri;
        $data = array(
            "dataUri" => $dataUri,
        );
        $this->load->view('AvisoDedeuda/barcode', $data);
        
    } 

}