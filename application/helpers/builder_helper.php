<?php
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

//ABM


function getTextAreaHtmlEditor($parameters,$ops){
    $html="";
    if(!isset($ops["format"])){$ops["format"]="text";}
    if(!isset($ops["readonly"])){$ops["readonly"]=false;}
    if(!isset($ops["nolabel"])){$ops["nolabel"]=false;}
    if(!isset($ops["empty"])){$ops["empty"]=false;}
    if(!isset($ops["rows"])){$ops["rows"]="10";}
    if(!isset($ops["free"])){$ops["free"]="";}
    if(!isset($parameters["records"]["data"][0])){$parameters["records"]["data"][0]=null;}
    $value=secureField($parameters["records"]["data"][0],$ops["name"]);
    if ($ops["empty"]) {$value="";}
    if(!$ops["nolabel"]){$html.="<label for='".$ops["name"]."'>".lang('p_'.$ops["name"])."</label>";}
    if ($ops["readonly"]) {
        $html.="<div class='border'><pre>".$value."</pre></div>";
    } else {
        $html.="<textarea class='".$ops["class"]."' id='".$ops["name"]."' name='".$ops["name"]."' rows='".$ops["rows"]."' ".$ops["free"].">".$value."</textarea>";
    }
    $html.="<script type='text/javascript'>new nicEditor({iconsPath : './assets/js/_third/nicEditorIcons.gif',fullPanel: true}).panelInstance('".$ops["name"]."');</script>";
    if(isset($ops["col"])){$html="<div class='".$ops["col"]."'>".$html."</div>";}
    return $html;
}

function buildHeaderAbmStd($parameters,$title){
    if(!isset($parameters["readonly"])){$parameters["readonly"]=false;}
    if(!isset($parameters["records"]["data"][0])){$parameters["records"]["data"][0]=null;}
    $new=((int)secureField($parameters["records"]["data"][0],"id")===0);
    $html="<div class='bg-default clearfix'>";
    $html.="<h3 class='title-abm float-left'>";
    if ($parameters["readonly"]) {
       $html.="<span class='badge badge-warning'>".lang('msg_view');
    } else{
       if ($new){ $html.="<span class='badge badge-secondary'>".lang('msg_new');}else{$html.="<span class='badge badge-info'>".lang('msg_edit');}
    }
    $html.="</span> ".$title;
    $html.="</h3>";
    $html.="</div>";
    if ($new and isset($parameters["messageNew"])){$html.=$parameters["messageNew"];}
    if (isset($parameters["messageAlert"])){$html.=$parameters["messageAlert"];}
    return $html;
}
function buildFooterAbmStd($parameters){
    if(!isset($parameters["readonly"])){$parameters["readonly"]=false;}
    if(!isset($parameters["accept-class-name"])){$parameters["accept-class-name"]="";}
    if(!isset($parameters["records"]["data"][0])){$parameters["records"]["data"][0]=null;}
    $record=$parameters["records"]["data"][0];
    $new=((int)secureField($record,"id")===0);    
    $dataSegment=buildDataSegment($parameters);
    if ($new){
       $dataRec=str_replace('|ID|','0',$dataSegment);
    } else {
       $dataRec=str_replace('|ID|',secureField($record,"id"),$dataSegment);
    }
    $html="<hr class='shadow-sm'/>";
    $html.="<div class='container-full card py-2'>";
    $html.=" <div class='row justify-content-left'>";
    if ($parameters["readonly"]) {
        $html.="  <div class='px-4'><button type='button' class='btn-raised btn-abm-cancel btn btn-info'><i class='material-icons'>close</i></span>".lang("b_close")."</button></div>";
    } else {

        if ($parameters["accept-class-name"]!="")
        {
            $html.="  <div class='px-4'><button type='button' class='btn-raised ".$parameters["accept-class-name"]." btn btn-success' ".$dataRec."><i class='material-icons'>done</i></span>".lang("b_accept")."</button></div>";
        }
        else
        {
            $html.="  <div class='px-4'><button type='button' class='btn-raised btn-abm-accept btn btn-success' ".$dataRec."><i class='material-icons'>done</i></span>".lang("b_accept")."</button></div>";
        }

        if ($parameters["records"]["data"][0]["code"]!="llamada-telefonica") {
           $html.="  <div class='px-4'><button type='button' class='btn-raised btn-abm-cancel btn btn-danger'><i class='material-icons'>not_interested</i></span>".lang("b_cancel")."</button></div>";
        }
    }
    $html.=" </div>";
    $html.="</div>";
    return $html;
}

//BROWSER
function buildHeaderBrowStd($parameters,$title){
    if (!isset($parameters['filters'])){$parameters['filters']=array();}
    if (!isset($parameters['controls'])){$parameters['controls']=array();}
    if (!isset($parameters["getters"]) or !is_array($parameters["getters"])){
        $parameters["getters"]=array(
           "search"=>true,
           "googlesearch"=>true,
           "excel"=>false,
           "pdf"=>false,
        );
    }
    if(!isset($parameters["getters"]["search"])){$parameters["getters"]["search"]=true;}
    if(!isset($parameters["getters"]["googlesearch"])){$parameters["getters"]["googlesearch"]=true;}
    if(!isset($parameters["getters"]["excel"])){$parameters["getters"]["excel"]=false;}
    if(!isset($parameters["getters"]["pdf"])){$parameters["getters"]["pdf"]=false;}
    $dataSegment=buildDataSegment($parameters);
    $dataRec=str_replace('|ID|','0',$dataSegment);
    $html="<div class='bg-default clearfix'>";
    $html.="   <h3 class='title-browser float-left'>".$title."</h3>";
    $html.="   <div class='search-browser input-group mb-3 float-right'>";
    foreach($parameters["controls"] as $control) {
        $html.="<div class='browser_controls' style='padding-right:5px;display:inline;'>".$control."</div>";
    }
    if($parameters["getters"]["googlesearch"]){$html.="    <input id='browser_search' name='browser_search' type='text' class='search-trigger form-control browser_search' placeholder='".lang('p_search')."' aria-label='".lang('p_search')."' />";}
    $html.="    <div class='input-group-append'>";
    if($parameters["getters"]["search"]){$html.="<button class='btn btn-secondary btn-sm btn-browser-search' type='button' ".$dataRec." data-mode='brow' data-page='1' data-filters='".json_encode($parameters['filters'])."'><i class='material-icons' style='font-size:22px;vertical-align:middle;'>search</i>".lang('p_search')."</button>";}
    if($parameters["getters"]["pdf"]){$html.="<button class='btn btn-secondary btn-sm btn-pdf-search' type='button' ".$dataRec." data-mode='pdf' data-page='1' data-filters='".json_encode($parameters['filters'])."'><i class='material-icons' style='font-size:22px;vertical-align:middle;'>picture_as_pdf</i>PDF</button>";}
    if($parameters["getters"]["excel"]){$html.="<button class='btn btn-secondary btn-sm btn-excel-search' type='button' ".$dataRec." data-mode='excel' data-page='1' data-filters='".json_encode($parameters['filters'])."'><i class='material-icons' style='font-size:22px;vertical-align:middle;'>table_rows</i>Excel</button>";}
    $html.="    </div>";
    $html.="   </div>";
    $html.="</div>";
    return $html;
}
function buildFooterBrowStd($parameters){
    $dataSegment=buildDataSegment($parameters);
    $dataRec=str_replace('|ID|','0',$dataSegment);
    $html="<div class='footer-browser d-flex float-right pb-1 mb-2'>";
    $html.=" <nav class='p-2'>";
    $html.="  <ul class='pagination justify-content-end'>";
    $totalpages=(int)$parameters["records"]["totalpages"];
    $page=(int)$parameters["records"]["page"];
    $limit=($page+10);
    if($limit>$totalpages){$limit=$totalpages;}
    if ($totalpages>=1){
        if($page>1){$html.="<li class='page-item'><a ".$dataRec." class='page-link btn-browser-search' href='#' tabindex='-1' data-page='".($page-1)."' data-filters='".json_encode($parameters['filters'])."'>".lang('msg_previous')."</a></li>";}
        for($i=$page;$i<=$limit;$i++){
            $class="";
            if($i==$page){$class="active";}
            $html.="<li class='page-item ".$class."'><a ".$dataRec." data-page='".$i."' class='page-link btn-browser-search' href='#' data-filters='".json_encode($parameters['filters'])."'>".$i."</a></li>";
        }
        if(($limit+1)<=$totalpages){$html.="<li class='page-item'><a ".$dataRec." class='page-link btn-browser-search' href='#' data-page='".($limit+1)."' data-filters='".json_encode($parameters['filters'])."'>".lang('msg_next')."</a></li>";}
    }
    $html.="  </ul>";
    $html.=" </nav>";
    $html.="</div>";
    return $html;
}
function buildBodyHeadBrowStd(&$parameters){
    $html="  <thead class='thead-light'>";
    $html.="   <tr class='header'>";
    $html.=getThCheck($parameters);
    $html.=getThNew($parameters);
    if (!isset($parameters["columns"]) or !is_array($parameters["columns"])){
        $parameters["columns"]=array(
            //array("field"=>"id","format"=>"number"),
            array("field"=>"code","format"=>"code"),
            array("field"=>"description","format"=>"text"),
            array("field"=>"","format"=>null),
            array("field"=>"","format"=>null),
        );
    }
    foreach ($parameters["columns"] as $column) {
       if (isset($column["forcedlabel"])){$column["field"]=$column["forcedlabel"];}
       $html.=getThCol("p_".$column["field"],$column["class"]);
    }
    $html.="   </tr>";
    $html.="  </thead>";
    return $html;
}
function buildBodyBrowStd(&$parameters){
    $html="  <thead class='thead-light'>";
    $html.="   <tr class='header'>";
    $html.=getThNew($parameters);
    if (!isset($parameters["columns"]) or !is_array($parameters["columns"])){
        $parameters["columns"]=array(
            //array("field"=>"id","format"=>"number"),
            array("field"=>"code","format"=>"code"),
            array("field"=>"description","format"=>"text"),
            array("field"=>"","format"=>null),
            array("field"=>"","format"=>null),
        );
    }
    foreach ($parameters["columns"] as $column) {$html.=getThCol("p_".$column["field"],$column["class"]);}
    $html.="   </tr>";
    $html.="  </thead>";
    return $html;
}

//BROWSER PARTIAL DATA
function getThCheck($parameters){
    if(!secureButtonDisplay($parameters,null,"check")){return "";}
    $dataSegment=buildDataSegment($parameters);
    $dataRec=str_replace('|ID|','0',$dataSegment);
    $html="<th>";
    $html.="<input type='checkbox' value='0' class='form-control btn-record-check ' ".$dataRec."/>";
    $html.="</th>";
    return $html;
}
function getThNew($parameters){
    if(!isset($parameters["custom_class_new"])){$parameters["custom_class_new"]="btn-record-edit";}
    if(!secureButtonDisplay($parameters,null,"new")){return "<th></th>";}
    $dataSegment=buildDataSegment($parameters);
    $dataRec=str_replace('|ID|','0',$dataSegment);
    $html="<th>";
    $html.="<button type='button' class='".$parameters["custom_class_new"]." btn btn-raised btn-primary btn-sm'".$dataRec."><i class='material-icons'>note_add</i></button>";
    $html.="</th>";
    return $html;
}
function getThCol($keyLang,$class){
    $html="<th class='".$class."'></th>";
    if($keyLang!="p_" and $keyLang!="" ){$html="<th class='".$class."'>".lang($keyLang)."</th>";}
    return $html;
}
function getTdCheck($parameters,$record,$td){
    if(!isset($notd)){$notd=false;}
    if(!secureButtonDisplay($parameters,$record,"check") and $td){
        $alternate="";
        if (isset($parameters["buttons"]["check"]["alternate"])){$alternate=$parameters["buttons"]["check"]["alternate"];}
        return "";
    }
    $dataSegment=buildDataSegment($parameters);
    $dataRec=str_replace('|ID|',secureField($record,"id"),$dataSegment);
    $html="";
    if ($td){$html.="<td style='min-width:50px;'>";}
    $html.="<input type='checkbox' value='".secureField($record,"id")."' class='form-control btn-record-check' ".$dataRec."/>";
    if ($td){$html.="</td>";}
    return $html;
}
function getTdEdit($parameters,$record,$td){
    if(!isset($notd)){$notd=false;}
    if(!secureButtonDisplay($parameters,$record,"edit") and $td){
        $alternate="";
        if (isset($parameters["buttons"]["edit"]["alternate"])){$alternate=$parameters["buttons"]["edit"]["alternate"];}
        return "<td>".$alternate."</td>";
    }
    $dataSegment=buildDataSegment($parameters);
    $dataRec=str_replace('|ID|',secureField($record,"id"),$dataSegment);
    $html="";
    if ($td){$html.="<td style='width:50px;'>";}
    $html.="<button type='button' class='btn btn-raised btn-record-edit btn-info btn-sm' ".$dataRec."><i class='material-icons'>edit</i></button>";
    if ($td){$html.="</td>";}
    return $html;
}
function getTdCol($parameters,$record,$column){
    if(!isset($column["html"])){$column["html"]="";}
    if(!isset($column["whenready"])){$column["whenready"]="";}
    if ($column["field"]==""){
       $dataSegment=buildDataSegment($parameters);
       $dataRec=str_replace('|ID|',secureField($record,"id"),$dataSegment);
       $html="";
       switch ($column["format"]) {
           case "button":
              $html.="<td><button type='button' class='".$column["class"]."'".$dataRec."><i class='material-icons'>".$column["icon"]."</i></button></td>";
              break;
       }
    } else {
       switch($column["format"]) {
          case "conditional#block":
             $column["html"]=str_replace('|ID|',secureField($record,"id"),$column["html"]);
             $other=false;
             if(secureField($record,"hidden")!=1){$other=true;}
             if (secureField($record,$column["field"])==1){$column["html"]=$column["whenready"];}
             if($other){$value=$column["html"];}else{$value="";}
             break;
          case "html#block":
             $column["html"]=str_replace('|ID|',secureField($record,"id"),$column["html"]);
             $column["html"]=str_replace('|DESCRIPTION|',secureField($record,$column["field"]),$column["html"]);
             $value=$column["html"];
             break;
          default:
             $value=secureField($record,$column["field"]);
             break;
       }
       $html=" <td class='".$column["class"]."'>".formatHtmlValue($value,$column["format"])."</td>";
    }
    return $html;
}
function getTdDelete($parameters,$record,$td){
    if(!secureButtonDisplay($parameters,$record,"delete") and $td){return "<td></td>";}
    $dataSegment=buildDataSegment($parameters);
    $dataRec=str_replace('|ID|',secureField($record,"id"),$dataSegment);
    $html="";
    if ($td){$html.="<td align='right' style='width:50px;'>";}
    $html.="<button type='button' class='btn btn-raised btn-record-remove btn-danger btn-sm' ".$dataRec."><i class='material-icons'>delete_forever</i></button>";
    if ($td){$html.="</td>";}
    return $html;
}
function getTdOffline($parameters,$record,$td){
    if(!secureButtonDisplay($parameters,$record,"offline") and $td){return "<td></td>";}
    $dataSegment=buildDataSegment($parameters);
    $offline=secureField($record,"offline");
    $dataRec=str_replace('|ID|',secureField($record,"id"),$dataSegment);
    $html="";
    if ($td){$html.="<td align='right' style='width:50px;'>";}
    if ($offline!="") {
        $html.="<span class='badge badge-warning'>".lang('msg_offline')." ".date(FORMAT_DATE_DMYHMS, strtotime($offline))."</span>";
        $html.="<a href='#' class='badge btn-record-online badge-info' ".$dataRec."><i class='material-icons'>settings_backup_restore</i></a>";
    } else {
        $html.="<button type='button' class='btn btn-raised btn-record-offline btn-warning btn-sm' ".$dataRec."><i class='material-icons'>remove_circle_outline</i></button>";
    }
    if ($td){$html.="</td>";}
    return $html;
}
function getNoData(){
   return "<div class='alert alert-warning'><strong>".lang("msg_nodata")."</strong></div>";
}
function getUnInitialized(){
   return "<div class='alert alert-danger'><strong>".lang("msg_uninitialized")."</strong></div>";
}

//RECORD IDENTIFIER DATA-
function buildDataSegment($parameters) {
    $html="data-id='|ID|'";
    $html.="data-module='".$parameters["module"]."'";
    $html.="data-model='".$parameters["model"]."'";
    $html.="data-table='".$parameters["table"]."'";
    //$html.="data-xx='yy'";
    return $html;
}

//HTML CONTROLS
function getFile($parameters,$ops,$list=null){
    if(!isset($ops["allow_delete"])){$ops["allow_delete"]=true;}
    if(!isset($ops["allow_read"])){$ops["allow_read"]=true;}
    if(!isset($parameters["readonly"])){$parameters["readonly"]=false;}
    $defaultIcon="./assets/img/image-upload.png";
    $label=lang('p_'.$ops["name"]);
    if($ops["forcelabel"]!=""){$label=$ops["forcelabel"];}
    $rootExternalLink="";
    $rootDirectLink="";
    $key=("btn-".$ops["relation"]."-files-".$ops["name"]);
    $html="<label for='".$ops["name"]."'>".$label."</label>";
    $html.="<div class='upload pt-3 position-relative'>";
    $html.=" <div class='row'>";
    if(!$parameters["readonly"]){
        $html.="  <div class='col-2'>";
        $html.="   <a href='#' class='btn btn-light btn-lg btn-upload' data-click='.".$key."'>";
        $html.="    <img class='img-".$ops["name"]."' src='".$defaultIcon."' style='width:52px;'/>";
        $html.="   </a>";
        $html.="   <input ";
        $html.="    data-module='".$ops["module"]."' ";
        $html.="    data-input='#".$ops["name"]."' ";
        $html.="    data-target='.ls-".$ops["name"]."' ";
        $html.="    data-click='.".$key."' ";
        $html.="    class='".$key." d-none' type='file' accept='".$ops["accept"]."'/>";
        $html.="   <a href='#' class='btn btn-secondary btn-md btn-raised btn-external-link' data-target='.ls-".$ops["name"]."' data-click='.".$key."'>".lang('b_external')."</a>";
        $html.="  </div>";
    }
    $html.="  <div class='col-10'>";
    $html.="     <ul class='list-group ls-".$ops["name"]."'>";
    if(is_array($list)) {
        foreach ($list as $record){
            $id=$record["id"];
			$html .= "<li class='list-group-item li-".$id."'>";
            $urlExternal=$parameters["baseserver"].$rootExternalLink."/".base64_encode($record["data"]);
            $url=$parameters["baseserver"].$rootDirectLink."/".base64_encode($record["data"]);
            $html .= "<div class='badge badge-dark text-truncate' style='max-width:100%;'>";
            if(!$parameters["readonly"]){
               $html .= lang('p_priority').": <input class='folder-item-priority-update' data-id='".$id."' id='priority' name='priority' type='number' step='10' min='0' value='".$record["priority"]."' style='width:50px;'/>";
            }
            $html .= "</div>";
            if ($record["mime"]=="application/octet-stream") {
               $html .= "<a target='_blank' href='".$urlExternal."' style='width:40px;' class='btn btn-sm btn-raised btn-default'><i class='material-icons'>link</i></a>";
            } else {
               $html .= "<a target='_blank' href='".$url."' style='width:40px;' class='btn btn-sm btn-raised btn-default'><i class='material-icons'>attach_file</i></a>";
            }
            if($ops["allow_read"]) {
                if(isset($record["viewed"])) {
			        if ((int)$record["viewed"] == 0) {
                        $color="magenta";
                        $status="ready";
			            $menu="<div class='btn-group p-0 m-0 btn-menu-".$record["id"]."'>";
			            $menu.=" <a href='#' class='btn btn-sm btn-default' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'><i class='material-icons'>more_vert</i> ".lang('msg_notreaded')."</a>";
			            $menu.="   <div class='dropdown-menu'>";
			            $menu.="      <button data-id='".$record["id"]."' data-status='".$status."' class='".$color." ready-".$record["id"]." btn-status-folder-item dropdown-item' type='button'>".lang("p_".$status)."</button>";
			            $menu.="   </div>";
			            $menu.="</div>";
                        $html.=$menu;
                        $html.="<div class='badge badge-danger mr-3 badge-readed'>".lang('msg_notreaded')."</div>";
                    } else {
                        $html.="<div class='badge badge-success mr-3'>".lang('msg_readed')."</div>";
                    }
                }
            }
			$html .= " <div data-id='".$id."' class='img-".$id." badge badge-secondary text-truncate' style='max-width:100%;' title='".$record["description"]."'>".$record["description"]."</div> ";
            if ($record["mime"]=="application/octet-stream") { 
    			$html .= "<div class='badge badge-primary text-truncate' style='max-width:100%;' title='".lang('b_external')."'>".lang('b_external')."</div> ";
                if(!$parameters["readonly"]) {
                    $html .= "<a href='#' data-id='".$id."' class='btn btn-sm btn-danger float-right btn-link-delete'><i class='material-icons'>delete_forever</i></a>";
                    $html .= "<pre>".$urlExternal."</pre>";
                }
            } else {
    			$html .= "<div class='badge badge-primary text-truncate' style='max-width:100%;' title='".$record["type_folder_item"]."'>".$record["type_folder_item"]."</div> ";
    			$html .= " <div class='badge badge-info text-truncate' style='max-width:100%;' title='".$record["keywords"]."'>".$record["keywords"]."</div> ";
                if(!$parameters["readonly"]) {
                    if($ops["allow_delete"]) {$html .= "<a href='#' data-id='".$id."' class='btn btn-sm btn-danger float-right btn-folders-delete'><i class='material-icons'>delete_forever</i></a>";}
                    $html .= "<pre>".$url."</pre>";
                }
            }
			$html .= "</li>";
        }
    } 
    $html.="     </ul>";
    $html.="  </div>";

    $html.=" </div>";
    $html.="</div>";
    if(isset($ops["col"])){$html="<div class='".$ops["col"]."'>".$html."</div>";}
    return $html;
}
function getImage($parameters,$ops,$list=null){
    $defaultIcon="./assets/img/image-upload.png";
    $html=getInput($parameters,$ops);
    $image="";
    if(isset($parameters["records"]["data"][0])){$image=secureField($parameters["records"]["data"][0],$ops["name"]);}
    if($image==""){$image=$defaultIcon;}

    if(!isset($ops["size"])){$ops["size"]="103";}
    if(!isset($ops["multi"])){$ops["multi"]=false;}
    if(!isset($ops["type"])){$ops["type"]="base64";}
    if(!isset($ops["format"])){$ops["format"]="jpeg";}
    if(!isset($ops["quality"])){$ops["quality"]=0.5;}
    if(!isset($ops["crop"])){$ops["crop"]="square";}

    $html.="<div class='upload pt-3 position-relative'>";

    $html.=" <div class='row'>";
    $html.="  <div class='col-12'>";
    $html.="   <a href='#' class='btn btn-light btn-lg btn-upload' data-click='.btn-pick-files-".$ops["name"]."'>";
    $html.="    <img class='img-".$ops["name"]."' src='".$image."' style='width:".$ops["size"]."px;'/>";
    $html.="   </a>";
    $html.="   <input ";
    $html.=" data-input='#".$ops["name"]."' ";
    if (!$ops["multi"]) {
       $html.=" data-target='.img-".$ops["name"]."' ";
    } else {
       $html.=" data-target='.ls-".$ops["name"]."' ";
    }
    $html.=" data-click='.btn-pick-files-".$ops["name"]."' ";
    $html.=" data-type='".$ops["type"]."' ";
    $html.=" data-format='".$ops["format"]."' ";
    $html.=" data-quality='".$ops["quality"]."' ";
    $html.=" data-crop='".$ops["crop"]."' ";
    $html.=" data-multi='".$ops["multi"]."' ";
    $html.=" class='btn-pick-files-".$ops["name"]." d-none' type='file' accept='image/*'/>";
    $html.="   <div class='row'>";
    $html.="    <div class='col-12'>";
    if (!$ops["multi"]) {
        $html.="      <button type='button' class='mr-auto control-image btn btn-info btn-lg btn-upload btn-sm' data-click='.btn-pick-files-".$ops["name"]."'><i class='material-icons'>add</i></button>";
        $html.="      <button type='button' class='ml-5 control-image btn btn-warning btn-lg btn-upload-reset btn-sm' data-input='#".$ops["name"]."' data-default='".$defaultIcon."' data-target='.img-".$ops["name"]."'><i class='material-icons'>clear</i></button>";
    } else {
        $html.="   <ul class='list-group ls-".$ops["name"]."'>";
        if(is_array($list)) {
            foreach ($list as $record){
                $id=$record["id"];
				$html.="<li class='list-group-item li-".$id."'>";
				$html.="<img data-id='".$id."' src='".$record["src"]."' style='width:40px;' class='img-".$id."' data-filename='".$record["description"]."' /> ";
				$html.="<div class='badge badge-primary text-truncate' style='display: inline-block;max-width:100%;' title='".$record["description"]."'>".$record["description"]."</div> ";
				$html.="<a href='#' data-id='".$id."' class='btn btn-sm btn-danger float-right btn-upload-delete'><i class='material-icons'>delete</i></a>";
				$html.="<pre>".$record["src"]."</pre>";
				$html.="</li>";
            }
        } 
        $html.="</ul>";
    }
    $html.="    </div>";
    $html.="   </div>";
    $html.="  </div>";
    $html.=" </div>";
    $html.="</div>";
    if(isset($ops["col"])){$html="<div class='".$ops["col"]."'>".$html."</div>";}
    return $html;
}
function getInputMicro($parameters,$ops){
    $checked="";
    $html="";
    $checPaeameters="";
    if(!isset($ops["custom"])){$ops["custom"]="";}
    if(!isset($ops["forcelabel"])){$ops["forcelabel"]="";}
    if(!isset($ops["format"])){$ops["format"]="text";}
    if(!isset($ops["readonly"])){$ops["readonly"]=false;}
    if(!isset($ops["nolabel"])){$ops["nolabel"]=false;}
    if(!isset($ops["empty"])){$ops["empty"]=false;}
    if(!isset($ops["checkboxtype"])){$ops["checkboxtype"]="01";}//01 para 01, SN para Si o No
    if(!isset($parameters["records"]["data"][0])){$parameters["records"]["data"][0]=null;}
    $value=secureField($parameters["records"]["data"][0],$ops["name"]);
    if ($ops["empty"]) {$value="";}
    $label=lang('p_'.$ops["name"]);
    if($ops["forcelabel"]!=""){$label=$ops["forcelabel"];}
    
    $html="<table style='width:100%;'>";
    $html.="<tr>";
    if(!$ops["nolabel"]){$html.="<td>".$label."</td>";}
    if ($ops["readonly"]) {
       $html.="<td>".formatHtmlValue($value,$ops["format"])."</td>";
    } else {
        $styleWidth="";
        switch ($ops["type"]) {
            case "checkbox":
                if ($ops["checkboxtype"]=="01")
                {
                    $checPaeameters=" checkboxtype=01 ";
                    if($value==1) {$checked='checked';}else{$checked='';}
                    $styleWidth="style='width:25px;'";
                    break;
                }
                else if ($ops["checkboxtype"]=="SN")
                {
                    $checPaeameters=" checkboxtype=SN ";
                    if($value=="S") {$checked='checked';}else{$checked='';}
                    $styleWidth="style='width:25px;'";
                    break;
                }
                else
                {
                    if($value==1) {$checked='checked';}else{$checked='';}
                    $styleWidth="style='width:25px;'";
                    break;
                }

                break;
            case "date":
                $styleWidth="style='width:50%;'";
                if ($value!=""){$value=date(FORMAT_DATE_DB, strtotime($value));}
                break;
        }
       $html.="<td ".$styleWidth."><input ".$ops["custom"]." data-type='".$ops["type"]."' ".$checked." autocomplete='nope' ".$checPaeameters." value='".$value."' class='".$ops["class"]."' type='".$ops["type"]."' name='".$ops["name"]."' id='".$ops["name"]."' data-clear-btn='false' placeholder='".lang('p_'.$ops["name"])."' /></td>";
    }
    $html.="</tr>";
    $html.="<tr><td colspan='2'><div class='invalid-feedback invalid-".$ops["name"]." d-none'/></td></tr>";
    $html.="</table>";
    if(isset($ops["col"])){$html="<div class='".$ops["col"]."'>".$html."</div>";}
    return $html;
}

function getInput($parameters,$ops){
    $checked="";
    $html="";
    $checPaeameters="";
    if(!isset($ops["custom"])){$ops["custom"]="";}
    if(!isset($ops["forcelabel"])){$ops["forcelabel"]="";}
    if(!isset($ops["format"])){$ops["format"]="text";}
    if(!isset($ops["readonly"])){$ops["readonly"]=false;}
    if(!isset($ops["nolabel"])){$ops["nolabel"]=false;}
    if(!isset($ops["empty"])){$ops["empty"]=false;}
    if(!isset($ops["visible"])){$ops["visible"]=true;}
    if(!isset($ops["checkboxtype"])){$ops["checkboxtype"]="01";}//01 para 01, SN para Si o No
    if(!isset($parameters["records"]["data"][0])){$parameters["records"]["data"][0]=null;}
    $value=secureField($parameters["records"]["data"][0],$ops["name"]);
    $visi="";

    if ($ops["visible"]==false){$visi=" style='display:none;'";}

    if ($ops["empty"]) {$value="";}
    $label=lang('p_'.$ops["name"]);
    if($ops["forcelabel"]!=""){$label=$ops["forcelabel"];}
    if(!$ops["nolabel"]){
        if(strpos($ops["class"], 'validate') !== false){
            $html.="<label for='".$ops["name"]."'>* ".$label."</label>";
        } else{
            $html.="<label for='".$ops["name"]."'>".$label."</label>";
        }
        
    }
    if ($ops["readonly"]) {
       $html.=formatHtmlValue($value,$ops["format"]);
    } else {
        switch ($ops["type"]) {
            case "checkbox":
                if ($ops["checkboxtype"]=="01")
                {
                    if($value==1) {$checked='checked';}else{$checked='';}
                    break;
                }
                else if ($ops["checkboxtype"]=="SN")
                {
                    $checPaeameters=" checkboxtype=SN ";
                    if($value=="S") {$checked='checked';}else{$checked='';}
                    break;
                }
                else
                {
                    if($value==1) {$checked='checked';}else{$checked='';}
                    break;
                }
            case "date":
                if ($value!=""){$value=date(FORMAT_DATE_DB, strtotime($value));}
                break;
        }
       $html.="<input ".$ops["visi"]." ".$ops["custom"]." data-type='".$ops["type"]."' ".$checked." autocomplete='nope' ".$checPaeameters." value='".$value."' class='".$ops["class"]."' type='".$ops["type"]."' name='".$ops["name"]."' id='".$ops["name"]."' data-clear-btn='false' placeholder='".lang('p_'.$ops["name"])."'";
       
       if(isset($ops["min"])){$html.=" min='".$ops["min"]."'";}
       if(isset($ops["step"])){$html.=" step='".$ops["step"]."'";}
       if(isset($ops["pattern"])){$html.=" pattern='".$ops["pattern"]."'";}
       if(isset($ops["inputmode"])){$html.=" inputmode='".$ops["inputmode"]."'";}
       if(isset($ops["oninput"])){$html.=" oninput='".$ops["oninput"]."'";}
       if(isset($ops["onkeypress"])){$html.=" onkeypress='".$ops["onkeypress"]."'";}

       $html.=" />";
    }
    $html.="<div class='invalid-feedback invalid-".$ops["name"]." d-none'/>";
    if(isset($ops["col"])){$html="<div class='".$ops["col"]."'>".$html."</div>";}
    return $html;
}
function getTextArea($parameters,$ops){
    $html="";
    if(!isset($ops["format"])){$ops["format"]="text";}
    if(!isset($ops["readonly"])){$ops["readonly"]=false;}
    if(!isset($ops["nolabel"])){$ops["nolabel"]=false;}
    if(!isset($ops["empty"])){$ops["empty"]=false;}
    if(!isset($ops["rows"])){$ops["rows"]="10";}
    if(!isset($parameters["records"]["data"][0])){$parameters["records"]["data"][0]=null;}
    $value=secureField($parameters["records"]["data"][0],$ops["name"]);
    if ($ops["empty"]) {$value="";}
    if(!$ops["nolabel"]){$html.="<label for='".$ops["name"]."'>".lang('p_'.$ops["name"])."</label>";}
    if ($ops["readonly"]) {
        $html.="<div class='border'><pre>".$value."</pre></div>";
    } else {
        $html.="<textarea class='".$ops["class"]."' id='".$ops["name"]."' name='".$ops["name"]."' rows='".$ops["rows"]."' style='width:100%;'>".$value."</textarea>";
    }
    if(isset($ops["col"])){$html="<div class='".$ops["col"]."'>".$html."</div>";}
    return $html;
}
function getHtmlResolved($parameters,$type,$field,$ops=null) {
    //log_message("error", "getHtmlResolved starting");
    $html="";
    $label=lang('p_'.$field);
    if(!isset($ops["nolabel"])){$ops["nolabel"]=false;}
    if($ops["forcelabel"]!=""){$label=$ops["forcelabel"];}
    if(!$ops["nolabel"]){$html.="<label for='".$field."'>".$label."</label>";}
    $html.=$parameters[$type][$field];
    //log_message("error", "ARRAY parameters ".json_encode($parameters,JSON_PRETTY_PRINT));
    //log_message("error", "getHtmlResolved get from parqameters: ".$parameters[$type][$field]);
    if(isset($ops["col"])){$html="<div class='".$ops["col"]."'>".$html."</div>";}
    //log_message("error", "getHtmlResolved html: ".$html);
    //log_message("error", "getHtmlResolved ending");
    return $html;
}
function getCombo($parameters,$obj){
    //log_message("error", "getCombo starting");
    $parts=explode("/",$parameters["model"]);
    if(!isset($parameters["mode"])){$parameters["mode"]="NORMAL";}
    $ACTIVE=$obj->createModel($parts[0],$parts[1],$parts[1]);
    $records=$ACTIVE->get($parameters["get"]);
    $html="";
    switch($parameters["mode"]) {
        case "NORMAL":
            $disabled="";
            if(isset($parameters["disabled"])){
                $disabled = "disabled='".$parameters["disabled"]."'"; // disabled="disabled">
            }
            $html.="<select  data-live-search='true' data-type='select' id='".$parameters["name"]."' name='".$parameters["name"]."' class='".$parameters["name"]." ".$parameters["class"]."' ".$disabled." >";  //"'>";
            break;
        case "MULTISELECT":
            $html.="<select data-live-search='true' data-actions-box='true' id='".$parameters["name"]."' name='".$parameters["name"]."' class='selectpicker ".$parameters["name"]." ".$parameters["class"]."' show-tick multiple data-width='100%' data-size='10' data-live-search='true' style='color:black;'>";
            break;
    }
    try {
        if((int)$parameters["empty"]) {$html.="<option value='' selected>".lang('p_select_combo')."</option>";}
        foreach($records["data"] as $record){
            $selected="";
            $id=secureField($record,$parameters["id_field"]);
            if(($id==$parameters["id_actual"])){$selected="selected";}
            $html.="<option ".$selected." value='".$id."'>".$record[$parameters["description_field"]]."</option>";
        };
    } catch(Exception $e){}
    $html.="</select>";
    $html.="<div class='invalid-feedback invalid-".$parameters["name"]." d-none'/>";

    //log_message("error", "getCombo html: ".$html);
    //log_message("error", "getCombo ending");
    return $html;
}
function getMultiSelect($parameters,$obj){
    $html="";
    try {
        if(!isset($parameters["icon_field"])){$parameters["icon_field"]=null;}
        if(!isset($parameters["options"])){$parameters["options"]=null;}
        if(!isset($parameters["children"])){$parameters["children"]=null;}
        $parts=explode("/",$parameters["actual"]["model"]);
        $RELATED=$obj->createModel($parts[0],$parts[1],$parts[1]);
        $related=$RELATED->get(array("pagesize"=>-1,"where"=>($parameters["actual"]["id_field"]."=".$parameters["actual"]["id_value"])));
        $parts=explode("/",$parameters["model"]);
        $ACTIVE=$obj->createModel($parts[0],$parts[1],$parts[1]);
        $records=$ACTIVE->{$parameters["function"]}($parameters["options"]);
        $html.="<br/><select data-actions-box='true' id='".$parameters["name"]."' name='".$parameters["name"]."' class='selectpicker ".$parameters["name"]." ".$parameters["class"]."' show-tick multiple data-width='100%' data-size='10' data-live-search='true' style='color:black;'>";
        foreach($records["data"] as $record){
            $selected="";
            $id=secureField($record,$parameters["id_field"]);
            foreach($related["data"] as $item){if($item[$parameters["name"]]==$id){$selected="selected";break;}}
            $data_content="";
            if ($parameters["icon_field"]!=null){$data_content="data-content='<i class=\"material-icons\">".$record[$parameters["icon_field"]]."</i> ".$record[$parameters["description_field"]]."'";}
            $html.="<option ".$data_content." ".$selected." value='".$id."' style='color:navy;'>".$record[$parameters["description_field"]]."</option>";
            if($parameters["children"]!=null) {
                if (isset($record[$parameters["children"]])) {
                    foreach($record[$parameters["children"]] as $child){
                        $selected="";
                        $id=secureField($child,$parameters["id_field"]);
                        foreach($related["data"] as $subitem){if($subitem[$parameters["name"]]==$id){$selected="selected";break;}}
                        $data_content="";
                        if ($parameters["icon_field"]!=null){$data_content="data-content='<i class=\"material-icons\">".$child[$parameters["icon_field"]]."</i> ".$child[$parameters["description_field"]]."'";}
                        $html.="<option ".$data_content." ".$selected." value='".$id."' style='margin-left:10px;color:grey;'>".$child[$parameters["description_field"]]."</option>";
                    }
                }
            }
        };
        $html.="</select>";
    } catch(Exception $e){

    }
    return $html;
}
function getProgressBar($parameters,$ops){
    $hide="";
    if(!isset($ops["dyncolor"])){$ops["dyncolor"]=false;}
    if(!isset($ops["min"])){$ops["min"]="0";}
    if(!isset($ops["max"])){$ops["max"]="100";}
    if(!isset($ops["value"])){$ops["value"]="0";}
    if(!isset($ops["class"])){$ops["class"]="progress-bar-striped progress-bar-animated";}
    if ($ops["dyncolor"]) {
       if ((int)$ops["value"]<10) { 
          $ops["class"].=" bg-secondary";
       } elseif ((int)$ops["value"]<25) {
          $ops["class"].=" bg-danger";
       } elseif ((int)$ops["value"]<50) {
          $ops["class"].=" bg-info";
       } elseif ((int)$ops["value"]<80) {
          $ops["class"].=" bg-primary";
       } elseif ((int)$ops["value"]<100) {
          $ops["class"].=" bg-success";
       } elseif ((int)$ops["value"]==100) {
          $ops["class"].=" bg-light";
          $hide="d-none";
       }
    }
    $html="";
    $html.="<div class='progress ".$hide."' style='height:2px;'>";
    $html.="   <div class='progress-bar ".$ops["class"]."' role='progressbar' aria-valuenow='".$ops["value"]."' aria-valuemin='".$ops["min"]."' aria-valuemax='".$ops["max"]."' style='width: ".$ops["value"]."%'></div>";
    $html.="</div>";
    if(isset($ops["col"])){$html="<div class='".$ops["col"]."'>".$html."</div>";}
    return $html;
}
function getButtonRibbon($parameters,$ops){
  if(!isset($parameters["class"])){$parameters["class"]="btn-default";}
  $html="<div class='btn-toolbar' role='toolbar'>";  
  $html.="<div class='btn-group mr-2' role='group'>";
  //$html.=$parameters["name"];
  foreach($ops as $op){
      if(!isset($op["mode"])){$op["mode"]="";}
      if(!isset($op["class"])){$op["class"]="";}
      if(!isset($op["link"])){$op["link"]="#";}
      if(!isset($op["datax"])){$op["datax"]="";}
      if(!isset($op["style"])){$op["style"]="";}
      //if ($parameters["name"]!=$op["name"]) {
        $html.="<button type='button' class='btn btn-primary btn-raised ".$op["class"]."' ".$op["datax"]." style='".$op["style"]."'>".$op["name"]."</button>";
      //}
  }
  $html.="</div>";
  $html.="</div>";
  return $html;
}
function getDropdown($parameters,$ops){
  if(!isset($parameters["class"])){$parameters["class"]="btn-default";}
  $html="<div class='btn-group'>";
  $html.="<button type='button' class='btn ".$parameters["class"]." dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>";
  $html.=$parameters["name"];
  $html.="</button>";
  $html.="<div class='dropdown-menu'>";
  foreach($ops as $op){
      if(!isset($op["mode"])){$op["mode"]="";}
      if(!isset($op["class"])){$op["class"]="";}
      if(!isset($op["link"])){$op["link"]="#";}
      if(!isset($op["datax"])){$op["datax"]="";}
      if(!isset($op["style"])){$op["style"]="";}
      switch($op["mode"]) {
         case "divider":
            $html.="<div class='dropdown-divider'></div>";
            break;
         default:
            $html.="<a ".$op["datax"]." class='dropdown-item ".$op["class"]."' href='".$op["link"]."' style='".$op["style"]."'>".$op["name"]."</a>";
            break;
      }
  }
  $html.="</div>";
  $html.="</div>";
  return $html;
}
function getMessagesList($parameters,$ops,$list=null){
    $body=getTextArea($parameters,array("col"=>"col-md-12","name"=>"message","class"=>"form-control text validate-message trumbo"));
    $body.="<div class='modal-footer font-weight-light'>";
    $body.="<button type='button' class='btn btn-primary btn-success-message'>".lang('b_accept')."</button>";
    $body.="</div>";
    $body=base64_encode($body);

    $html="<a href='#' class='btn btn-block btn-raised btn-primary btn-message-external' data-list='.ls-".$ops["name"]."' data-body='".$body."' data-title='".lang('b_new_message')."'>".lang('b_new_message')."</a>";
    $html.="<ul class='ls-".$ops["name"]." list-inline'>";
    foreach ($list as $message){
        $html.="<li style='width:100%;' class='list-group-item li-" .$message["id"]. "'>";
        $html.="<table class='table-condensed table-striped' style='width:100%;'>";
        $html.=" <tr><td>".$message["message"]. "</td></tr>";
        $html.=" <tr>";
        $html.="  <td align='right' style='font-size:9px;' class='td-".$message["id"]."'>";
        $html.=$message["created"]. ": <i>" .$message["username"]."</i>";
        if ((int)$message["viewed"]==0){
            $html.=" <a href='#' class='btn btn-xs btn-info btn-message-read btn-raised sp-" .$message["id"]. "' data-id='".$message["id"]."'>".lang('b_mark')."</a>";
        } else {
            $html.=" <span class='badge badge-success' style='font-size:12px;'>".lang('b_checked')."</span>";
        }
        $html.="  </td>";
        $html.=" </tr>";
        $html.="</table>";
        $html.="</li>";
    }
    $html.="</ul>";
    if(isset($ops["col"])){$html="<div class='".$ops["col"]."'>".$html."</div>";}
    return $html;
}

//CUSTOM BUTTONS
function getHelpButton($item,$ops){
   $body=$item[$ops["body"]];
   if ($body!=""){$body=base64_encode($item[$ops["body"]]);}
   $html="<i data-title='".ucfirst(lang($item[$ops["title"]]))."' data-body='".$body."' class='btn-brief material-icons bg-light' style='font-size:20px;position:absolute;top:50%;right:1px;z-index:99999;cursor:help;'>help_outline</i>";
   return $html;
}

//SECURE READ DATA AND POSITIONING
function secureField($record,$field){
    if (!isset($record[$field])) {
        $field=strtoupper($field);
        if (!isset($record[$field])) {
           $record[$field]="";
        }
    }
    return $record[$field];
}
function secureEmptyNull($values,$key){
    if (!isset($values[$key])) {$values[$key]=null;}
    if($values[$key]=="" OR $values[$key]==-1 OR $values[$key]==0){$values[$key]=null;}
    return $values[$key];
}
function secureFloatNull($values,$key){
    if (!isset($values[$key])) {$values[$key]=null;}
    $values[$key]=str_replace(",",".",$values[$key]);
    $pattern = '/^[-+]?(((\\\\d+)\\\\.?(\\\\d+)?)|\\\\.\\\\d+)([eE]?[+-]?\\\\d+)?$/';
    if (preg_match($pattern, trim($values[$key]))){$values[$key]=null;} 
    if($values[$key]==""){$values[$key]=null;}
    return $values[$key];
}
function secureComboPosition($records,$field){
    try {
        if ($records["status"]=="OK"){
            if (isset($records["data"][0][$field])) {
               return $records["data"][0][$field];
            } else {
                throw new Exception("");
            }
        } else {
            throw new Exception("");
        }
    } catch(Exception $rex) {
       return null;
    }
}
function secureButtonDisplay($parameters,$record,$action){
    try {
        if (!isset($parameters["buttons"]["check"])){$parameters["buttons"]["check"]=false;}
        if (!isset($parameters["buttons"][$action])){return true;}
        if (is_array($parameters["buttons"][$action])) {
            foreach($parameters["buttons"][$action]["conditions"] as $condition) {if(!compareRecordValue($record,$condition)){return false;}}
            return true;
        } else {
            return $parameters["buttons"][$action];
        }
    } catch(Exception $e){
        return true;
    }
}
function compareRecordValue($record,$condition){
    try {
        $rValue=$record[$condition["field"]];
        switch($condition["operator"]) {
            case "=":
            case "==":
            case "===":
                return ($rValue==$condition["value"]);
            case "!=":
                return ($rValue!=$condition["value"]);
            case ">=":
                return ($rValue>=$condition["value"]);
            case "<=":
                return ($rValue<=$condition["value"]);
            default:
                return true;
        }
    } catch(Exception $e){
        return true;
    }
}

//HTML FORMAT VALUES
function formatHtmlValue($value,$format,$ops=null){
    switch($format) {
        case "image":
            $value=("<img class='rounded-circle shadow' src='".$value."' style='width:42px;'/>");
            break;
        case "integrity":
            if($value==""){$value="<span class='badge badge-danger'>".lang('msg_error_integrity')."</span>";}
            if($value==0 or $value==1){$value="<span class='badge badge-warning'>".lang('msg_alert_integrity')."</span>";}
            if($value==2){$value="<span class='badge badge-success'>".lang('msg_success_integrity')."</span>";}
            break;
        case "money":
            $value=("<var style='display:block;text-align:right;'>$ ".$value."</var>");
            break;
        case "number":
            $value=("<var style='display:block;text-align:right;'>".$value."</var>");
            break;
        case "date":
            if ($value!=""){$value=date(FORMAT_DATE_DMY, strtotime($value));}else{$value="";}
            $value=("<pre class='bd-highlight' style='display:block;'>".$value."</pre>");
            break;
        case "datetime":
            if ($value!=""){$value=date(FORMAT_DATE_DMYHMS, strtotime($value));}else{$value="";}
            $value=("<pre class='bd-highlight' style='display:block;'>".$value."</pre>");
            break;
        case "code":
            $value=("<kbd style='display:block;'>".$value."</kbd>");
            break;
        case "email-action":
            if (strpos($value,"@")!==false) {
               $value=getEmailArrayFromString($value);
               $value=("<a href='#' class='btn btn-raised btn-sm btn-info btn-reply-email p-0 m-0' data-email='".$value[0]."' style='display:block;'><i class='material-icons'>email</i> ".$value[0]."</a>");
            } else{
               $value=("<span class='badge badge-info' style='display:block;'>".$value."</span>");
            }
            break;
        case "email":
            $class="badge badge-secondary";
            $value=str_replace(">","",$value);
            $value=("<pre class='".$class."' style='display:block;'>".$value."</pre>");
            break;
        case "danger":
            $class="badge badge-danger";
            if($value==""){$value=lang('msg_empty');$class="badge badge-light";}
            $value=("<span class='".$class."' style='display:block;'>".$value."</span>");
            break;
        case "warning":
            $class="badge badge-warning";
            if($value==""){$value=lang('msg_empty');$class="badge badge-light";}
            $value=("<span class='".$class."' style='display:block;'>".$value."</span>");
            break;
        case "status":
            $class="badge badge-primary";
            if($value==""){$value=lang('msg_empty');$class="badge badge-light";}
            $value=("<span class='".$class."' style='display:block;'>".$value."</span>");
            break;
        case "type":
            $class="badge badge-info";
            if($value==""){$value=lang('msg_empty');$class="badge badge-light";}
            $value=("<span class='".$class."' style='display:block;'>".$value."</span>");
            break;
        case "text":
            $value=("<p class='text-monospace text-break' style='display:block;'>".$value."</p>");
            break;
        case "shorten":
            $value="<div class='comment more'>".$value."</div>";
            break;
        case "fixed":
            $value="<table style='table-layout:fixed;width:100%;'><tr><td style='word-wrap:break-word;'>".$value."</td></tr></table>";
            break;
        case "json":
            $value=("<pre class='text-break' style='display:block;'>".json_encode(json_decode($value),JSON_PRETTY_PRINT)."</pre>");
            break;
        case "sign":
            $value=("<pre class='text-break' style='display:block;'><small>".$value."</small></pre>");
            break;
        case "verify":
            $class="badge badge-success";
            if($value==""){$value=lang('msg_empty');$class="badge badge-warning";}
            $value=("<span class='".$class."' style='display:block;'>".$value."</span>");
            break;
        case "check":
            if($value==0){$value="<div><i class='material-icons'>thumb_down_alt</i></div>";}else{$value="<div><i class='material-icons'>thumb_up_alt</i></div>";}
            break;
        case "private":
            $msg=lang('msg_group_assigned');
            if($value==1){$msg=lang('msg_groups_assigned');}
            if($value==0){$value="<div><i class='material-icons'>lock</i> ".lang('msg_only_creator')."</div>";}else{$value="<div><i class='material-icons'>people</i> ".$value." ".$msg."</div>";}
            break;
        case "message":
            $value="<div><i class='material-icons'>email</i> ".$value."</div>";
            break;
        case "auditoria_telefonica":
            $file=("auditoria_telefonica/".$value);
            $value="<audio controls><source src='".$file."' type='audio/mpeg'></audio>";
            break;
        case "auditoria_io":
            $class="badge badge-info";
            switch($value){
               case "SALIENTE":
                  $class="badge badge-primary";
                  break;
            }
            $value=("<span class='".$class."' style='display:block;'>".$value."</span>");
            break;
        default:
            break;
    }
    if(isset($ops["col"])){$value="<div class='".$ops["col"]."'>".$value."</div>";}
    return $value;
}
