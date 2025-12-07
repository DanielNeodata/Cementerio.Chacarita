<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Devuelve un CB Code128 como contenido para un img en base64.
 *
 * Devuelve el base64 para un tag img de una imagen PNG del code128 del numero enviado. Utiliza Zend Barcode
 *
 * @since x.x.x
 * @see new_function_name()
 *
 * @param mixed Mandatorio. Es el numero a codificar en Code128.
 * @return mixed data:image/png;base64,_BASE64_.
 */
function generateCode128asBase64Img($arg_number) {

        // http://shikhapathak6.blogspot.com/2020/02/generate-barcode-using-codeigniter-with.html
        // https://github.com/desta88/Codeigniter-Barcode

        // https://framework.zend.com/manual/1.12/en/zend.barcode.objects.html

        // https://stackoverflow.com/questions/8243205/load-a-library-in-a-helper
        $CI = &get_instance();
        $CI->load->library('zend');
        $CI->zend->load('Zend/Barcode');
        //$this->load->library('zend');
        //$this->zend->load('Zend/Barcode');


        //$barcodeOptions = array('text' => $id, 'width'=> '100', 'height'=> '20');
        //  https://gist.github.com/ezimuel/3129095
        $barcodeOptions = array('text' => $arg_number,
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

        return $dataUri;

}