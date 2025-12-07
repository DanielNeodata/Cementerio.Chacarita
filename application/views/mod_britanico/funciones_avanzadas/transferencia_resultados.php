<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="container-full marco p-1">
    <div class="row mx-0 shadow rounded" style="background-color:whitesmoke;">
        <div class="col-12 pt-1 m-0">
            <h1 class="m-0 p-0" style="font-weight:bold;color:rgb(0, 71, 186);"><?php echo $title;?></h1>
        </div>
    </div>
    <div class="row m-2 p-2">
		
    </div>
    <hr/>
    <div class="row">
        <div class="col-12" style="padding-top:15px;">
            <a href="#" class="btnAction btnAccept btn btn-success btn-raised pull-right"><?php echo lang('b_accept');?></a>
            <a href="#" class="btnAction btnCancel btn btn-danger btn-raised pull-right"><?php echo lang('b_cancel');?></a>
        </div>
    </div>
</div>
<script>
    $.getScript('./application/views/mod_disidentes/funciones_avanzadas/transferencia_resultados.js', function() {

    }
});
</script>
