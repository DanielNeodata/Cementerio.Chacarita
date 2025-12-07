<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/
?>
<div class="d-flex" id="wrapper">
    <div class="bg-light shadow sidebar-wrapper" id="sidebar-wrapper">
        <div class="m-0" id="data-menu-close" style="width:100%;">
            <span class="btn-toggle-menu btn btn-menu-close btn-sm float-left pb-0 mb-0"><i class="material-icons">arrow_back_ios</i></span>
            <span class="mx-0 px-1 waiter wait-ajax"></span>
        </div>
        <div id="accordion" role="tablist" class="pt-0 mt-0 list-group side-menu">
            <?php   
            $html="";
            foreach ($menu as $item){
                $id=$item["id"];
                $ops=array("value"=>$item["running"],"dyncolor"=>true);
                $running=getProgressBar(null,$ops);
                $html.="<div class='p-0 m-0' role='tab' id='heading-".$id."' style='position:relative;'>";
                $html.="   <a class='list-group-item bg-secondary' data-toggle='collapse' href='#menu-".$id."' aria-expanded='true' aria-controls='menu-".$id."' style='color:whitesmoke;'>";
                $html.="      <table style='width:100%;'>";
                $html.="         <tr>";
                $html.="            <td valign='middle' style='width:30px;'><i class='material-icons'>".$item["icon"]."</i></td>";
                $html.="            <td valign='middle'>".ucfirst(lang($item["code"]))."</td>";
                $html.="         </tr>";
                $html.="         <tr>";
                $html.="            <td colspan='2' align='right' valign='middle'>".$running."</td>";
                $html.="         </tr>";
                $html.="      </table>";
                $html.="   </a>";
                if ($item["show_brief"]==1 && $item["brief"]!="") {$html.=getHelpButton($item,array("title"=>"code","body"=>"brief"));}
                $html.="</div>";
                $html.="<div id='menu-".$id."' class='collapse' role='tabpanel' aria-labelledby='heading-".$id."' data-parent='#accordion'>";
                foreach ($item["submenu"] as $subitem){
                    $ops=array("value"=>$subitem["running"],"dyncolor"=>true);
                    $running=getProgressBar(null,$ops);
                    $html.="  <div style='position:relative;'>";
                    $html.="    <a href='#' class='list-group-item bg-light btn-menu-click btn-".$subitem["code"]."' data-alert='".$subitem["alert_build"]."' data-module='".$subitem["data_module"]."' data-model='".$subitem["data_model"]."' data-table='".$subitem["data_table"]."' data-action='".$subitem["data_action"]."'>";
                    $html.="      <table style='width:100%;'>";
                    $html.="         <tr>";
                    $html.="            <td valign='middle' style='width:30px;'><i class='material-icons'>".$subitem["icon"]."</i></td>";
                    $html.="            <td valign='middle' class='label-menu'>".ucfirst(lang($subitem["code"]))."</td>";
                    $html.="         </tr>";
                    $html.="         <tr>";
                    if($subitem["alert_build"]==1) {
                        $html.="<td colspan='2'><span class='badge badge-danger'><i class='material-icons' style='font-size:14px;'>build</i> ".lang('msg_not_use')."</span></td>";
                    }else{
                        $html.="<td colspan='2' valign='middle'>".$running."</td>";
                    }
                    $html.="         </tr>";
                    $html.="      </table>";
                    $html.="    </a>";
                    if ($subitem["show_brief"]==1 && $subitem["brief"]!="") {$html.=getHelpButton($subitem,array("title"=>"code","body"=>"brief"));}
                    $html.="  </div>";
                }
                $html.="</div>";
            }
            echo $html;
            ?>
        </div>
    </div>

    <div id="page-content-wrapper">
        <div class="d-flex">
            <div class="col-4 m-0 p-0" style="min-height:40px;">
                <div class="info-heading d-none">
                    <span class="btn-toggle-menu btn btn-menu-open btn-sm pb-0 mb-0"><i class="material-icons">menu</i></span>
                    <h5 class="p-1 top-heading d-inline"><?php echo $title;?></h5> 
                    <span class="mx-0 px-1 waiter wait-ajax"></span>
                </div>
            </div>
            <div class="col-8 ml-auto m-auto p-auto">
                <div class="float-right status-ajax-calls d-none p-0 m-0">
                    <span class="badge badge-success d-sm-inline font-weight-lighter mx-0 px-2 text-break cl-empresa-sucursal">XXX</span>
                    <a class="btn btn-xs btn-dark text-break mx-0 p-2 raw-messages_alert_NO d-none" title="<?php echo lang('msg_notreaded');?>"></a>
                    <img class="rounded-circle shadow img-user" src="./assets/img/user.jpg" style="height:40px;"/>
                    <span class="text-break font-weight-lighter badge badge-primary mx-0 px-2 raw-username_active d-none d-sm-inline"></span>
                    <a href="#" class="btn btn-raised btn-danger btn-sm btn-logout">Logout</a>
                    <?php 
                        if  (ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') {
                            echo "<br/>";
                            echo "<div class='float-right p-0 m-0'>";
                            echo "   <span class='text-monospace text-break font-weight-lighter badge badge-light mx-0 px-1 elapsed-time d-none d-sm-inline' style='font-size:8px;'></span>";
                            echo "   <span class='text-monospace text-break font-weight-lighter badge badge-info mx-0 px-1 execution-mode d-sm-inline' style='font-size:8px;'>".strtoupper(ENVIRONMENT)."</span>";
                            echo "   <span class='text-monospace text-break font-weight-lighter badge badge-success mx-0 px-1 status-last-call d-none d-sm-inline' style='font-size:8px;'></span>";
                            echo "   <span class='text-monospace text-break font-weight-lighter badge badge-danger mx-0 px-1 status-message d-none' style='font-size:8px;'></span>";
                            echo "</div>";
                        }
                    ?>
                </div>
            </div>
        </div>
        <div class="container-fluid dyn-area browser"></div>
        <div class="container-fluid dyn-area abm d-none"></div>
        <div class="alert-frame" style="position:fixed;bottom:0;"></div>
    </div>
</div>

<script>
   $(".btn-m_home").click();
</script>
