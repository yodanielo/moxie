<?php

class MantixForm
{
var $controles;
var $atributos;
var $datos;
var $js;
var $jsctl;
var $jsmul;
var $divmul;

function __construct()
{
  $this->controles=array();
}

function __destruct()
{
  unset($this->controles);
}

function ver() { 
 $total=count($this->controles);
 $btn_nuevo="";
 $pre_sub= "Crear "; 
 $idobj="";
 switch($_POST["accion"]) {
  case 3:
  case 8: $accion=1; break;
  case 2:
  case 20: $accion=2; break;
  default: $accion=1;
 }
 if ($_POST["accion"]==20 || $_POST["accion"]==2) { 
	   $idobj=$_POST["idobj"];
	   $this->datos->get_fila($idobj); 
	   $pre_sub= "Actualizar "; 
	   $btn_nuevo='<input type="button" value="Nuevo" class="form_submit" onclick="window.location=\''.basename($_SERVER['REQUEST_URI']).'\'" />';
}
elseif($this->atributos["tipo_form"]=="1") {
	   $idobj=$this->atributos["id"];
	   $this->datos->get_fila($idobj); 
	   $pre_sub= "Actualizar "; 
	   $btn_nuevo='';
	   $accion=2;
}


 $cadf="\r\n".'<br style="clear:both"><form id="formulario" name="formulario" method="post" action="'.$_SERVER['PHP_SELF'].'">'."\r\n";
 
 $cadf.='<input id="idobj" name="idobj" type="hidden" value="'.$idobj.'">'."\r\n";
 $cadf.='<input id="accion" name="accion" type="hidden" value="'.$accion.'">'."\r\n";
 $cadf.='<div  align="center"><fieldset id="form_ctls">'."\r\n";
 $tit_form=$this->atributos["texto_submit"];
 if($this->atributos["titulo"]!="") $tit_form=$this->atributos["titulo"];
 $cadf.='<legend>'.$tit_form.'</legend>'."\r\n";
 $capa_err="";
 for($a=0;$a<$total;$a++) {
   $css_label="form_label";
   $css_campo="form_campo";
   $cadf.='<div id="fila_'.$this->controles[$a]["campo"].'" class="form_fila">';   
   if($this->controles[$a]["css_label"]!="") $css_label=$this->controles[$a]["css_label"];
   $cadf.='<div class="'.$css_label.'">'.$this->controles[$a]["label"].'</div>';

   if($this->controles[$a]["css_campo"]!="") $css_campo=$this->controles[$a]["css_campo"];
   $cadf.='<div class="form_ctl">'.$this->control($this->controles[$a]).'</div>';
   $cadf.='</div><br style="clear:both" />'."\r\n";	
   $capa_err.='<div id="err_'.$this->controles[$a]["campo"].'" class="fila_errores"></div>';
 }
 $capa_err='<div id="capa_errores" style="display:none"><div id="tit_capa_errores">Alerta!</div>'.$capa_err.$this->divmul.'</div>';
 if($this->atributos["mostrar_submit"]!="0"){
 	 $cadf.='<div align="center" class="form_celda">(*) Los campos son obligatorios<br style="clear:both" />';
 }
 $cadf.='</div><br style="clear:both" />'."\r\n";
 
 switch($this->atributos["mostrar_submit"]) {
    case "1": if($_POST) { $cadf.='<input name="Submit" type="button" class="form_submit" value="Actualizar '.$this->atributos["texto_submit"].'"  onClick="validar()">&nbsp;'; } break;
	case "0": $cadf.="";break;
   	default:               $cadf.='<input name="Submit" type="button" class="form_submit" value="'.$pre_sub.$this->atributos["texto_submit"].'"  onClick="validar()">&nbsp;';
 }
 if(isset($_POST["quiensoy"])){
 	$cadf.='<input name="opcenviar" type="hidden" value="0">
			<input name="Enviar" type="button" class="form_submit" value="Enviar" onClick="validar1()">&nbsp';
 }
 $cadf.="\r\n";
 if($this->atributos["mostrar_volver"]=="1")  $cadf.='<input type="button" class="form_submit" value="Volver al Listado"  onClick="window.location=\''.$this->atributos["url_volver"].'\'">'."\r\n";
 
 $cadf.='</fieldset></div>'."\r\n".'</form>'."\r\n".'<br style="clear:both" />'."\r\n";
 return $cadf.$capa_err.$this->ver_js();
}

function get_opciones($tabla,$campo,$ordenars) {
   $ta=new MantixOaD();
   $ordenar="";
   if($ordenars!="") $ordenar=" order by ".$ordenars;
   $ta->listaSP("select * from ".$tabla.$ordenar,"","");
   $ops='<option value=""> - Seleccione - </option>';
   $campos=explode("+",$campo);

   while($ta->no_vacio()) {
	 $opvalue="";
	 for($a=0;$a<count($campos);$a++) {
	   $coma="";
	   if($a<(count($campos)-1)) { $coma=" - "; }
	   $opvalue.=$ta->valor($campos[$a]).$coma;
	 }
	 $ops.='<option value="'.$ta->valor("id").'">'.$opvalue.'</option>';
   }
   unset($ta);

return $ops;
}

function get_opciones_id($tabla,$campo,$ordenars,$ids) {
   $ta=new MantixOaD();
   $ordenar="";
   if($ordenars!="") $ordenar=" order by ".$ordenars;
   if($ids!="") {
   $ta->listaSP("select * from ".$tabla.$ordenar,"","");
   $campos=explode("+",$campo);

   while($ta->no_vacio()) {
	 $opvalue="";
	 for($a=0;$a<count($campos);$a++) {
	   $coma="";
	   if($a<(count($campos)-1)) { $coma=" - "; }
	   $opvalue.=$ta->valor($campos[$a]).$coma;
	 }
	 $ops.='<option value="'.$ta->valor("id").'">'.$opvalue.'</option>';
   }
   unset($ta);
   }
  return $ops;
}

function control($ctl)
{
	$cad=""; $ast="";
	if($ctl["obligatorio"]=="1") { $ast="&nbsp;*"; }
	if(!isset($ctl["equcar"])) { $ctl["equcar"]=""; }
	$valor=$ctl["valor"];
	if($valor!="NULL" && $valor=="" && $_POST["accion"]!=1) $valor=$this->datos->valor($ctl["campo"]);
	if($valor=="NULL") $valor="";
	
	$css_campo="form_input";
	if($ctl["css_campo"]!="") $css_campo=$ctl["css_campo"];

	$tipo="text";
	if($ctl["tipo"]!="") { $tipo=$ctl["tipo"]; }

	$max_car=150;
	if($ctl["max_car"]!="") $max_car=$ctl["max_car"];
	
	switch ($tipo)
	{
  	    case "text": $cad='<input id="'.$ctl["campo"].'" name="'.$ctl["campo"].'" type="'.$tipo.'" class="'.$css_campo.'" maxlength="' .$max_car.'" '.$ctl["extras"].' value="'.$valor.'"><input type="hidden"  id="'.$ctl["campo"].'_ant" name="'.$ctl["campo"].'_ant" value="'.$valor.'">'.$ast; break;
		
		case "password": $cad='<input id="'.$ctl["campo"].'" name="'.$ctl["campo"].'" type="'.$tipo.'" class="'.$css_campo.'" maxlength="'.$max_car.'" '.$ctl["extras"].' value="'.$valor.'"><input type="hidden" id="'.$ctl["campo"].'_ant"  name="'.$ctl["campo"].'_ant" value="'.$valor."\">".$ast; break; 
		
		case "area": $cad='<textarea id="'.$ctl["campo"].'" name="'.$ctl["campo"].'" class="form_area"  '.$ctl["extras"].' >'.$valor.'</textarea>'.$ast; break; 

  		case "checkbox": $cchk="";$ant_v=0;
  						 if ($valor!="")
  						 {	if ($valor==1) {$cchk=" checked "; $ant_v=1;}	 }
  					    $cad= '<input name="'.$ctl["campo"].'" type="checkbox" class="form_chk" '.$ctl["extras"].$cchk.'" value="1">'; break; 
		case "fecha": $cad= '<input size="10" id="' .$ctl["campo"].'" class="form_date" type="text" READONLY name="'.$ctl["campo"].'" title="DD/MM/YYYY"  value="'.$valor.'"> <input type="button" class="form_submit" value="ver calendario" onclick="scwShow(scwID(\''.$ctl["campo"].'\'),event)">'.$ast.'<input type="hidden" name="'.$ctl["campo"].'_ant value="'.$valor.'">'; break;						
		case "fecha1": $cad= '<input size="10" id="' .$ctl["campo"].'" class="form_date" type="text" READONLY name="'.$ctl["campo"].'" title="DD/MM/YYYY"  value="'.$valor.'"> <input type="button" class="form_submit" value="ver calendario" onclick="displayCalendarFor(\''.$ctl["campo"].'\');">'.$ast.'<input type="hidden" name="'.$ctl["campo"].'_ant value="'.$valor.'">'; break;
  	    case "fck": 
		  $cad='<div><input type=hidden id="'.$ctl["campo"].'" name="'.$ctl["campo"].'" value="'. $valor.'" style="display:none" /><iframe id="'.$ctl["campo"].'___Frame" src="fckeditor/editor/fckeditor.html?InstanceName='.$ctl["campo"].'" width="550" height="400" frameborder="0" scrolling="no"></iframe></div><br>';break;
  	    case "fck1": 
		  $cad='<div><input type=hidden id="'.$ctl["campo"].'" name="'.$ctl["campo"].'" value="'. $valor.'" style="display:none" /><iframe id="'.$ctl["campo"].'___Frame" src="fckeditor/editor/fckeditor.html?InstanceName='.$ctl["campo"].'" width="533" height="400" frameborder="0" scrolling="no"></iframe></div><br>';break;
		case "tinymce":
			$cad='
				<script type="text/javascript" src="./tiny_mce/tiny_mce.js"></script>
				<script type="text/javascript">
					// O2k7 skin
					tinyMCE.init({
					// General options
					mode : "exact",
					elements : "'.$ctl["campo"].'",
					theme : "advanced",
					skin : "o2k7",
					plugins : "safari,style,layer,advhr,advimage,advlink,iespell,media,contextmenu,paste,directionality,visualchars,template,inlinepopups",

					// Theme options
					theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,formatselect,fontselect,fontsizeselect",
					theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,undo,redo",
					theme_advanced_buttons3 : "forecolor,backcolor",
					theme_advanced_toolbar_location : "top",
					theme_advanced_toolbar_align : "left",
					theme_advanced_statusbar_location : "bottom",
					theme_advanced_resizing : true,
			
					// Example content CSS (should be your site CSS)
					content_css : "css/content.css",
					
					// Drop lists for link/image/media/template dialogs
					template_external_list_url : "lists/template_list.js",
					external_link_list_url : "lists/link_list.js",
					external_image_list_url : "lists/image_list.js",
					media_external_list_url : "lists/media_list.js",

					// Replace values for the template plugin
					template_replace_values : {
						username : "Some User",
						staffid : "991234"
					}
					
				});
				</script>
			';
			$cad .= '<textarea id="'.$ctl["campo"].'" name="'.$ctl["campo"].'" class="form_area"  '.$ctl["extras"].' >'.$valor.'</textarea>'.$ast; break; 
  		case "galeria": 
							 $cad='<div class="galeria_btn" onmouseover="mover_galeria(\''.$ctl["campo"].'_cont\',1)" onmouseout="detener_galeria(\''.$ctl["campo"].'_cont\')"><img src="images/fizq.png" border="0" /></div><div id="'.$ctl["campo"].'_central" class="galeria_central"><div id="'.$ctl["campo"].'" class="galeria"><div id="'.$ctl["campo"].'_cont" style="float:left; width:0px;height:100px;margin-left:0px"></div></div></div><div class="galeria_btn" onmouseover="mover_galeria(\''.$ctl["campo"].'_cont\',-1)"  onmouseout="detener_galeria(\''.$ctl["campo"].'_cont\')"><img src="images/fder.png" border="0" /></div><br><input class="form_upload" type="button" value="Agregar Imagen"  onClick="window.open(\'files_up_'.$ctl["campo"].'.php\',\'window\', \'height=170,width=500,resizable=1\');return false;"  />';
				 $cad.='<div id="'.$ctl["campo"].'_temp" style="display:none"></div><input type="hidden" name="'.$ctl["campo"].'_contstop" id="'.$ctl["campo"].'_contstop" value="0" /><input type="hidden" name="'.$ctl["campo"].'_conta" id="'.$ctl["campo"].'_conta" value="0" /><input type="hidden" name="himg_'.$ctl["campo"].'_cont" id="himg_'.$ctl["campo"].'_cont" value="" /><input type="hidden" name="hid_'.$ctl["campo"].'_cont" id="hid_'.$ctl["campo"].'_cont" value="" /><br><br>';

				 if($ctl["movible"]=="1") { $cad.='<script src="scripts/mx_galeria2.js"></script>'; }
				 else { $cad.='<script src="scripts/mx_galeria1.js"></script>'; }
                 $cad.='<script>cargar_galeria(\''.$ctl["campo"].'\',\''.$ctl["tabla_asoc"].'\',\''.$_POST["idobj"].'\');</script>';
				  break;
			 
 	    case "archivo": 
			$cad='<input name="'.$ctl["campo"].'" value="'.$valor.'" type="text" READONLY class="'.$css_campo." ".$ctl["extras"].'" >&nbsp;&nbsp;<input class="form_upload" type="button" onClick="window.open(\'files_up_'.$ctl["campo"].'.php\',\'window\', \'height=170,width=500,resizable=1\');return false;" value="Subir Archivo..." />&nbsp;&nbsp<input type="button"  value="Borrar" onclick="document.formulario.'.$ctl["campo"].'.value=\'\';" class="form_up_borrar" width="100"><input type="hidden" name="'.$ctl["campo"].'_ant" value="'.$valor.'">'.$ast;
		 $this->jsctl.= " function poner_".$ctl["campo"]."(foto){ document.formulario.".$ctl["campo"].".value = foto; } ";
		 break;
		 
	 case "archivo_img":  $cad='<input id="'.$ctl["campo"].'" name="'.$ctl["campo"].'" type="'.$tipo.'" class="'.$css_campo.'" maxlength="' .$max_car.'" '.$ctl["extras"].' value="'.$valor.'"><input type="hidden"  id="'.$ctl["campo"].'_ant" name="'.$ctl["campo"].'_ant" value="'.$valor.'">'.$ast; 
	 	 if($valor!="") { $cad.='<br><br><div id="'.$ctl["campo"].'_img"><img src="../images/babystar/'.$valor.'" border="0" /></div>'; } 
		 {	 $cad.='<br><br><div id="'.$ctl["campo"].'_img"></div>';	 }
		 $this->jsctl.= " function poner_".$ctl["campo"]."(foto){ document.formulario.".$ctl["campo"].".value = foto; } ";
		 break;
	 case "select":
	 		if($ctl["tabla_asoc"]!="") {
			   $ops=$this->get_opciones_id($ctl["tabla_asoc"],$ctl["campo_asoc"],$ctl["ordenar"],$valor);
			 }
			 else {	$ops=$ctl["opciones"]; }

			if($valor!="") $ops=str_replace('value="'.$valor.'"','value="'.$valor.'" selected="selected" ',$ops);
			
			$cad= '<select id="'.$ctl["campo"].'" name="'.$ctl["campo"].'" class="form_select" '.$ctl["extras"].'>'.$ops.'</select><input type=hidden name="'.$ctl["campo"].'_ant" value="'.$valor.'">'.$ast;
			break;
			
			
	case "multiple": 
					 $co=$ctl["controles"];
					 $nomcon=$ctl["campo"];
 					 $tabcon=$ctl["tabla_asoc"];
  					 $campocon=$ctl["campo_asoc"];
				 	 $valor=1;$nx=-1; $sigue=true; $cargarjs="";$jsc="";
							
					 $n=new MantixOaD();
					 $valor=$_POST["idobj"];
					 if($valor=="") $valor=-1;
					 
					 $n->listaSP("select * from ".$tabcon." where ".$campocon."=".$valor." order by id","","");
					 $cad.='<div id="form_'.$nomcon.'">';		
					 while($sigue) {	
					    if( $valor==-1 || $n->nro_registros()==0) $sigue=false;
						if(!$n->no_vacio() && $n->nro_registros()>0 && $valor!=-1) break;
						$nx++;
						$cad.='<div id="fila_m'.$nomcon.'_'.$nx.'" class="fila_mulcon">';		 
						
					 for($x=0;$x<count($co);$x++) {
					   	$css="form_input";
						if($co[$x]["css"]!="") $css=$co[$x]["css"];
				  	    $tipoc="text";
				    	if($co[$x]["tipo"]!="") { $tipoc=$co[$x]["tipo"]; }
						$cad.='<div id="fila_'.$nomcon."_".$co[$x]["campo"].'_'.$nx.'" class="form_fila">'; 
						$labelc=(($co[$x]["label"]!="")?'<label class="form_celda">'.$co[$x]["label"].'</label>':'');
						$cad.=$labelc;
						$valorc=($sigue)?$n->valor($co[$x]["campo"]):"";
						$valorid=($sigue)?$n->valor("id"):"";
						$this->divmul.='<div id="err_'.$nomcon."_".$co[$x]["campo"].'_'.$nx.'" class="fila_errores"></div>';
						if($ctl["obligatorio"]=="1")   $js_esp.=$this->generar_js($co[$x]["label"]."(".$ctl["label"].")",$nomcon."_".$co[$x]["campo"].'_'.$nx,$co[$x]["valida"]);
						  
					  switch($tipoc) { 
 			    	    
						case "text": $cad.='<input id="'.$nomcon."_".$co[$x]["campo"].'_'.$nx.'" name="'.$nomcon."_".$co[$x]["campo"].'[]" type="text" class="'.$css.'" maxlength="' .$max_carc.'" value="'.$valorc.'">&nbsp;'; 
									 if($cargarjs=="") $jsc.='<div id="fila_'.$nomcon."_".$co[$x]["campo"].'_'.$nx.'" class="form_fila">'.$labelc.'<input id="'.$nomcon."_".$co[$x]["campo"].'_\'+mul_'.$nomcon.'+\'" name="'.$nomcon."_".$co[$x]["campo"].'[]" type="text" class="'.$css.'" maxlength="' .$max_carc.'">&nbsp;</div>'; 
									 break;
						case "fecha": $cad.='<input size="10" id="'.$nomcon."_".$co[$x]["campo"].'_'.$nx.'" class="form_date" type="text" READONLY name="'.$nomcon."_".$co[$x]["campo"].'[]" title="DD/MM/YYYY"  value="'.$valorc.'"> <input type="button" class="form_submit" value="ver calendario" onclick="scwShow(scwID(\''.$nomcon."_".$co[$x]["campo"].'_'.$nx.'\'),event);">&nbsp;'; 
							 if($cargarjs=="") $jsc.= '<div id="fila_'.$nomcon."_".$co[$x]["campo"].'_'.$nx.'" class="form_fila">'.$labelc.'<input size="10" id="'.$nomcon."_".$co[$x]["campo"].'_\'+mul_'.$nomcon.'+\'" class="form_date" type="text" READONLY name="'.$nomcon."_".$co[$x]["campo"].'[]" title="DD/MM/YYYY" > <input type="button" class="form_submit" value="ver calendario" onclick="scwShow(scwID(\\\''.$nomcon."_".$co[$x]["campo"].'_\'+mul_'.$nomcon.'+\'\\\'),event);">&nbsp;</div>'; 
							 break;
							 
				 	  case "archivo": $cad.='<input id="'.$nomcon."_".$co[$x]["campo"].'_'.$nx.'" name="'.$nomcon."_".$co[$x]["campo"].'[]" value="'.$valorc.'" type="text" READONLY class="form_uptxt"  >&nbsp;&nbsp;<input class="form_upload" type="button" onClick="window.open(\'files_up_'.$co[$x]["campo"].'.php?var='.$nomcon."_".$co[$x]["campo"].'_'.$nx.'\',\'window\', \'height=170,width=500,resizable=1\');return false;" value="Subir Archivo..." />&nbsp;';
					  		if($cargarjs=="") $jsc.='<div id="fila_'.$nomcon."_".$co[$x]["campo"].'_'.$nx.'" class="form_fila"><input id="'.$nomcon."_".$co[$x]["campo"].'_\'+mul_'.$nomcon.' +\'" name="'.$nomcon."_".$co[$x]["campo"].'[]" value="" type="text" READONLY class="form_uptxt"  >&nbsp;&nbsp;<input class="form_upload" type="button" onClick="window.open(\\\'files_up_'.$co[$x]["campo"].'.php?var='.$nomcon."_".$co[$x]["campo"].'_\'+mul_'.$nomcon.' +\'\\\',\\\'window\\\', \\\'height=170,width=500,resizable=1\\\');return false;" value="Subir Archivo..." />&nbsp;</div>'; 
					  
					 	 break;					
					    case "select":
	 					     if($co[$x]["tabla_asoc"]!="") { 
								 $ops=$this->get_opciones($co[$x]["tabla_asoc"],$co[$x]["campo_asoc"],$co[$x]["ordenar"]);
							 }
							 else {	$ops=$co[$x]["opciones"]; }
				
							if($valorc!="") $ops=str_replace('value="'.$valorc.'"','value="'.$valorc.'" selected="selected" ',$ops);
						
							$cad.= '<select id="'.$nomcon."_".$co[$x]["campo"].'_'.$nx.'" name="'.$nomcon."_".$co[$x]["campo"].'[]" class="form_select" >'.$ops.'</select>';
							if($cargarjs=="") $jsc.= '<div id="fila_'.$nomcon."_".$co[$x]["campo"].'_'.$nx.'" class="form_fila">'.$labelc.'<select id="'.$nomcon."_".$co[$x]["campo"].'_\'+mul_'.$nomcon.'+\'" name="'.$nomcon."_".$co[$x]["campo"].'[]" class="form_select" >'.$ops.'</select></div>';
 
							break;
					   }
					    	$cad.="</div>";
					  }
					    $cad.='<input type="hidden" id="'.$nomcon.'_id_'.$nx.'" name="'.$nomcon.'_id[]" value="'.$valorid.'" />';
					  	if($nx>0 || $ctl["obligatorio"]!="1")  { $cad.='<div style="float:left;margin-bottom:4px; height:20px; width:62px"><input type="button" class="form_multiple" value="Eliminar" onclick="eliminar_mul_'.$nomcon.'('.$nx.')" /></div>'; }
						else  { $cad.='<div style="float:left;margin-bottom:4px; height:20px">&nbsp;</div>'; }
						$cad.='</div>';
						$cargarjs=$jsc;
						
					  }
					  $cad.='</div>';
					  unset($n);
					  $this->jsmul.=" function agregar_mul_".$nomcon."() {	var ni=document.getElementById('form_".$nomcon."'); var newdiv = document.createElement('div'); mul_".$nomcon."++; newdiv.setAttribute('id','fila_m".$nomcon."_'+ mul_".$nomcon."); newdiv.setAttribute('class','fila_mulcon'); newdiv.innerHTML = '".$cargarjs."<div style=\"float:left; height:20px; width:62px\"><input type=\"hidden\" id=\"".$nomcon."_id_'+mul_".$nomcon."+'\" name=\"".$nomcon."_id[]\" value=\"\" /><input type=\"button\"  class=\"form_multiple\" value=\"Eliminar\" onclick=\"eliminar_mul_".$nomcon."('+mul_".$nomcon."+')\" /></div><br style=\"clear:both;line-height:0px;\" />';  ni.appendChild(newdiv); } var mul_".$nomcon."=".$nx."; var mul_del_".$nomcon."=new Array();";
					  
					 $this->jsmul.=" function eliminar_mul_".$nomcon."(ind) {  if(confirm('� Est� seguro que desea ELIMINAR el registro ?')) { if(document.getElementById('".$nomcon."_id_'+ind).value!='') {  mul_del_".$nomcon.".push(document.getElementById('".$nomcon."_id_'+ind).value); document.getElementById('mul_del_".$nomcon."').value= mul_del_".$nomcon.".join(','); } d=document.getElementById('form_".$nomcon."'); d.removeChild( document.getElementById('fila_m".$nomcon."_'+ind)).removeChild(); } }";
					 
					 $tb="Registro";
					 if($ctl["texto"]!="") $tb=$ctl["texto"];
					 $cad.='<br clear="all"><input type="button" class="form_submit" value="Agregar '.$tb.'..." onclick="agregar_mul_'.$nomcon.'()" /><input type="hidden" id="mul_del_'.$nomcon.'" name="mul_del_'.$nomcon.'" value="" /><br><br>';
					 

					 break;
	}


if ($ctl["obligatorio"]=="1")
{
 $this->js.=$this->generar_js($ctl["label"],$ctl["campo"],$ctl["validacion"]);
}
if ($ctl["equcar"]!="")
{
 $this->js.=$this->generar_js($ctl["label"],$ctl["campo"],6, $ctl["equcar"]);
}	
$this->js.=$js_esp;
return $cad;
}


function generar_js($label1,$campo,$valida,$comparado=""){
 $label=str_replace(":","",$label1);
 $jscad.="if ( document.getElementById('".$campo."')) { if ( document.getElementById('".$campo."').value =='' ) {" ."\n";
 $jscad.="document.getElementById('err_".$campo."').innerHTML=\"<li>Debe introducir un valor en el campo: <strong>".$label."</strong></li>\";"."\n";
 $jscad.="document.getElementById('fila_".$campo."').className='form_fila_err';"."\n";
 $jscad.="res_valida=false; "."\n";
 $jscad.="} "."\n";
 $jscad.="else { document.getElementById('err_".$campo."').innerHTML=\"\";"."\n";
switch ($valida)
{
  case 1:  $jscad.="if (!isAlphabetic( f.".$campo.".value) ) { document.getElementById('err_".$campo."').innerHTML=\"<li>El valor del campo <strong>" .$label."</strong> debe ser �nicamente letras</li>\";document.getElementById('fila_".$campo."').className='form_fila_err'; return	}"."\n";break;
  case 2:  $jscad.="if (!isNumber( f.".$campo.".value) ) { document.getElementById('err_".$campo."').innerHTML=\"<li>El valor del campo: <strong>" .$label."</strong> debe ser num�rico.</li>\"; document.getElementById('fila_".$campo."').className='form_fila_err';res_valida=false;	}" ."\n";break;
  case 3:  $jscad.="if (!isAlphanumeric( f.".$campo.".value) ) { document.getElementById('err_".$campo."').innerHTML=\"<li>El valor del campo <strong>" .$label."</strong> debe ser letras y n�meros</li>\"); document.getElementById('fila_".$campo."').className='form_fila_err'; res_valida=false;	}"."\n";break;
  case 4:  $jscad.="if (f.".$campo.".value!='')  { rexp=/(^\d{1,2}):(\d{1,2})/;  hr = rexp.exec(f.".$campo.".value);   if (hr!=null){	if ((hr[1]>24) || (hr[2]>=60)) {  document.getElementById('err_".$campo."').innerHTML=\"<li>Hora Inv�lida en campo: <strong>" .$label."</strong></li>\"; document.getElementById('fila_".$campo."').className='form_fila_err';	res_valida=false;} }  else { document.getElementById('err_".$campo."').innerHTML=\"<li>Formato de Hora Inv�lido:  <strong>".$label." <strong></li>\";document.getElementById('fila_".$campo."').className='form_fila_err';res_valida=false; } }"; break;
  case 5:  $jscad.="if (!isEmail( f.".$campo.".value) ) { document.getElementById('err_".$campo."').innerHTML=\"<li>Formato inv�lido de e-mail en:  <strong>".$label." <strong></li>\";document.getElementById('fila_".$campo."').className='form_fila_err'; res_valida=false;	}"."\n";break;
  case 6:  $jscad.="if (f.".$campo.".value.length!=".$comparado."){document.getElementById('err_".$campo."').innerHTML=\"<li>El valor del campo <strong>" .$label."</strong> debe tener exactamente ".$comparado." caracteres</li>\";document.getElementById('fila_".$campo."').className='form_fila_err'; res_valida=false; }"."\n";break;
 }
 $jscad.="} } "."\n";
 return $jscad;
}
function ver_js()
{
$r.="<SCRIPT language=javascript>	" ."\n";
$r.="function validar() { window.scrollTo(0,0);" ."\n";
$r.="var f = document.formulario; var res_valida=true;" ."\n";
$r.=$this->js ;
$r.="if(res_valida)  { f.submit(); } else {  Dialogs.alert($('capa_errores').innerHTML);  }"."\n" ;
$r.="}" ."\n";
$r.=$this->jsctl."\n";
$r.=$this->jsmul;
$r.= " function poner_multiple(nom,archivo){ document.getElementById(nom).value = archivo; } ";

$r.="function validar1() { window.scrollTo(0,0);" ."\n";
$r.="var f = document.formulario; var res_valida=true;" ."\n";
$r.=$this->js ;
$r.="if(res_valida)  { f.opcenviar.value='1';f.submit(); } else {  Dialogs.alert($('capa_errores').innerHTML);  }"."\n" ;
$r.="}" ."\n";
$r.=$this->jsctl."\n";
$r.=$this->jsmul;
$r.= " function poner_multiple(nom,archivo){ document.getElementById(nom).value = archivo; } ";


$r.="	</script>" ."\n";

return  $r;
}

}
?>