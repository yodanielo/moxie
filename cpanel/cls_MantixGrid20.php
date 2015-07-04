<?php

class MantixGrid
{
var $datos;
var $atributos;
var $buscador;
var $ver_buscador;
var $js;

function MantixGrid()
{
  $this->buscador=array();
  $this->columnas=array();
  
}
function __destruct()
{
  unset($this->buscador);
  unset($this->columnas);
}

function mostrar_buscador() {
 if($this->atributos["ver_buscador"]=="1") {
	  $r='<br><div align="center"><fieldset class="field_buscador"><legend>Buscador</legend>'; 
	  for($a=0; $a<=count($this->buscador)-1;$a++) {
		$r.='<div id="fila_form"><div id="fila_label">'.$this->buscador[$a]["label"].'</div><div id="fila_input">';
		$tipo="text";
		if($this->buscador[$a]["tipo"]!="") $tipo=$this->buscador[$a]["tipo"];
		switch($tipo) {
		  case "text" : $r.='<input class="bus_input" type="input" name="bus_'.$this->buscador[$a]["id"].'" id="bus_'.$this->buscador[$a]["id"].'" value="'.$_POST["bus_".$this->buscador[$a]["id"]].'" />'; break;
	 	  case "select":
	 		if($this->buscador[$a]["tabla_asoc"]!="") { 
				   $ta=new MantixOaD();
				   $ordenar="";
			   	   if($this->buscador[$a]["ordenar"]!="") $ordenar=" order by ".$this->buscador[$a]["ordenar"];

				   $ta->listaSP("select * from ".$this->buscador[$a]["tabla_asoc"].$ordenar,"","");
				   $ops='<option value=""> - Seleccione - </option>'."\r\n";
				   $campos=explode("+",$this->buscador[$a]["campo_asoc"]);
				   
				   while($ta->no_vacio()) {
					 $opvalue="";
					 for($b=0;$b<count($campos);$b++) {
					   $coma="";
					   if($b<(count($campos)-1)) { $coma=" - "; }
					   $opvalue.=$ta->valor($campos[$b]).$coma;
					 }
					 $ops.='<option value="'.$ta->valor("id").'">'.$opvalue.'</option>'."\r\n";
				   }
				   unset($ta);
			 }
			 else {	$ops=$this->buscador[$a]["opciones"]; }

			if($_POST['bus_'.$this->buscador[$a]["id"]]!="") $ops=str_replace('value="'.$_POST['bus_'.$this->buscador[$a]["id"]].'"','value="'.$_POST['bus_'.$this->buscador[$a]["id"]].'" selected="selected" ',$ops);
			$r.= '<select id="bus_'.$this->buscador[$a]["id"].'" name="bus_'.$this->buscador[$a]["id"].'" class="form_select" >'.$ops.'</select>';
			break;
			case "fecha": 
			$r.= '<input size="10" id="bus_' .$this->buscador[$a]["id"].'" class="form_date" type="text" READONLY name="bus_'.$this->buscador[$a]["id"].'" value="'.$_POST['bus_'.$this->buscador[$a]["id"]].'" title="DD/MM/YYYY" > <input type="button" class="form_submit" value="ver calendario" onclick="scwShow(scwID(\'bus_'.$this->buscador[$a]["id"].'\'),event)">'; 
			break;
		}
		$r.='</div></div><br style="clear:both" />';
	  }
	  if(isset($this->atributos["sin_buscar"])){
	  	$r.='<input type="hidden" name="sinbuscar" value="1">';
	  }
	  $r.='<br style="clear:both" /><div id="fila_form" align="center"><input class="form_submit" type="button" onclick="grid_buscar()" value="Buscar" />&nbsp;<input class="form_submit" type="button" value="Volver al listado" onClick="location.href=\''.$_SERVER['REQUEST_URI'].'\'" /></div></fieldset></div><br><br>';
 }
 return $r;
}

function filtrar_buscador() {
$cad="";
if(!isset($this->atributos["sin_buscar"])){
	for($a=0; $a<count($this->buscador);$a++) {
	  if($_POST['bus_'.$this->buscador[$a]["id"]]!="") {
		if($cad!="") { $cad.=" and "; }
		$tipo="text";
		if($this->buscador[$a]["tipo"]!="") $tipo=$this->buscador[$a]["tipo"];
		switch($tipo) {
		  case "text" : $cad.= $this->buscador[$a]["campo"]." like '".$_POST['bus_'.$this->buscador[$a]["id"]]."%'";break;
		  case "select" : $cad.= $this->buscador[$a]["campo"]."=".htmlentities($_POST['bus_'.$this->buscador[$a]["id"]]); break;
		  }
	   }
	 }
}
return $cad;
}


function ver()
{

if ($_POST["pag"] != "" ) {	$p_actual = $_POST["pag"]; } else { $p_actual = 1;}

$this->datos = new MantixOaD();
$this->datos->tabla=$this->atributos["tabla"];
$this->datos->l_nropag =$this->atributos["nropag"];

$cad=$this->filtrar_buscador();
if($this->atributos["ver_buscador"]=="1") {
	if($this->atributos["sql"]!="" and $cad!="") {
		$pwhere = strpos($cad, "where");
		if($pwhere===false) {  $this->atributos["sql"].=" where ".$cad; }	
		else {  $this->atributos["sql"].=" and ".$cad; }
	}
	if($this->atributos["tabla"]!="" and $cad!="") {
		$pwhere = strpos($filtro2, "and");
		if($pwhere===false) {  $filtro2.= $cad; }	
		else {   $filtro2.=" and ".$cad; }
	}
	//echo $this->atributos["sql"];
}
$ordenar=$this->atributos["ordenar"];
if($_POST["grid_ordenar"]!="") $ordenar=$_POST["grid_ordenar"];

$this->datos->lista($this->atributos["sql"], $filtro2,$ordenar,$p_actual);

$r='<form id="form_grid" name="form_grid" method="post">'."\r\n";
$r.='<input type="hidden" id="accion" name="accion" value="2">'."\r\n";
$r.='<input type="hidden" id="idobj" name="idobj">'."\r\n";
$r.='<input type="hidden" id="pag" name="pag">'."\r\n";
$r.='<input type="hidden" id="grid_ordenar" name="grid_ordenar" value="'.$_POST["grid_ordenar"].'">'."\r\n";
$r.=$this->mostrar_buscador();
if (!$this->datos->vacio() )
{
$r.='<div id="grilla_principal">';
$r.="<table width=\"98%\" align=center border=0 cellpadding=0 cellspacing=2>\r\n";

if ($this->atributos["ver_barra"]=="") {
  $r.='<tr><td align="left" height="20" colspan=20 >';
  if($this->atributos["ver_estado"]=="") {
	$r.='<input type="button" value="Activar" class="grid_bar" onclick="grid_activar_multiple()" />&nbsp;<input type="button" value="Desactivar" class="grid_bar" onclick="grid_desactivar_multiple()" />&nbsp;';
  }
  if($this->atributos["ver_eliminar"]=="") {
     $r.='<input type="button" value="Eliminar" class="grid_bar" onclick="grid_eliminar_multiple()" />&nbsp;'; 
	}
  if($this->atributos["ver_exportar"]=="1") {
   $r.='<input type="button" value="Exportar Filtro" class="grid_bar" onclick="grid_exportar_filtro()" />&nbsp;'; 
  }
  $r.="</td></tr>"."\r\n";
}
$r.="<tr>";

if($this->atributos["ver_chk"]=="") {
	$r.="<td width=\"10\" class=\"grid_header\" ><input type=\"hidden\" name=\"chk_true\" value=\"0\"><input type=\"checkbox\" name=\"multi\" value=\"\" onclick=\"marcatodo();\" /></td>";
}
if ($this->atributos["ver_modificar"]=="") { $r.=" <td width=15 valign=middle class=grid_header align=center>Abrir</td>\r\n"; }
if ($this->atributos["ver_estado"]=="") {  $r.="	<td width=10 valign=middle class=grid_header align=center>Estado</td>\r\n"; }
if ($this->atributos["ver_eliminar"]=="") { $r.="	<td width=17  valign=middle class=grid_header align=center>Eliminar</td>\r\n"; }
if ($this->atributos["ver_nro"]=="") { $r.=" <td width=50 height=19 valign=middle align=center class=grid_header>Nro</td>\r\n ";}

for($a=0;$a<count($this->columnas);$a++)
{
 $css_header=($this->columnas[$a]["css_header"]!="")?$this->columnas[$a]["css_header"]:"grid_header";
 $img_asc=(strpos($_POST["grid_ordenar"], $this->columnas[$a]["campo"]." asc")===false)?"images/asc.gif":"images/asc_color.gif"; 	
 $img_desc=(strpos($_POST["grid_ordenar"], $this->columnas[$a]["campo"]." desc")===false)?"images/desc.gif":"images/desc_color.gif"; 
 
 $r.=' <td width="'.$this->columnas[$a]["ancho"].'" height="30" valign="top" class="'.$css_header.'" align="left"><table border="0" cellspacing="0" width="100%" align="left" ><tr><td align=left><a href="#" onclick="grid_ordenar_asc(\''.$this->columnas[$a]["campo"].'\')"><img border="0" src="'.$img_asc.'" width="10" height="10"></a><a href="#" onclick="grid_ordenar_desc(\''.$this->columnas[$a]["campo"].'\')"><img border="0" src="'.$img_desc.'" width="10" height="10"></a></td></tr><tr><td  height="20" align="center" class="'.$css_header.'">'.$this->columnas[$a]["titulo"]."</td></tr></table></td>\r\n";

}
$r.="</tr>\r\n ";

$fi=0;

$r_col="";
$g_ver="";

while ($this->datos->no_vacio())
{
    $fi++;
	if (($fi % 2)==0 ) { $r.="<tr class=\"grid_color1\"  onmouseover=\"this.className='grid_colorover'\" onmouseout=\"this.className='grid_color1'\"  >\r\n"; }
	else { $r.="<tr class=\"grid_color2\" onmouseover=\"this.className='grid_colorover'\"  onmouseout=\"this.className='grid_color2'\" >\r\n"; }
	if($this->atributos["ver_chk"]=="") {
		$r.='<td align="center"> <input type="checkbox" id="reg'.$fi.'" name="cid[]" value="'.$this->datos->valor("id").'" onclick="esActivo(this.checked);" /> </td>'."\r\n";	
	}
  
	if ($this->atributos["ver_modificar"]=="") {$r.='<td valign=middle align=center><a href="#" onClick="grid_modificar('.$this->datos->valor("id").')"><img border=0 src="images/modif.gif" width="16" height="16"></a></td>'."\r\n"; }
	
	if ($this->datos->valor("estado") ==1 ) { $estado="<img border=0 src=\"images/activo.gif\">"; } else { $estado="<img border=0 src=\"images/inactivo.gif\">";}
	
	if ($this->datos->valor("estado") ==0 ) { $aref='<a href="#" onClick="grid_activar('.$this->datos->valor("id").')"><img border=0 src="images/inactivo.gif"></a>';}  else { $aref='<a href="#" onClick="grid_desactivar('.$this->datos->valor("id").')"><img border=0 src="images/activo.gif"></a>'; }
	
	if ($this->atributos["ver_estado"]=="") { $r.='<td valign="middle" align="center">'.$aref.'</td>'."\r\n"; }
	
	if ($this->atributos["ver_eliminar"]=="") { $r.='<td valign="middle" align="center"><a href="#" onClick="grid_eliminar('.$this->datos->valor("id").')"><img border=0 src="images/eliminar.gif"></a></td>'."\r\n"; }

	if ($this->atributos["ver_nro"]=="") { $r.=" <td height=19 valign=middle align=center class=form_centrar>".$fi."</td>"."\r\n";}
	
    for ($a=0; $a<count($this->columnas);$a++)
    {
	  $css_celda=($this->columnas[$a]["css_celda"]!="")?' class="'.$this->columnas[$a]["css_celda"].'" ':"";
	  $alias=($this->columnas[$a]["alias"]!="")?$this->columnas[$a]["alias"]:$this->columnas[$a]["campo"];
	  
	  $r.=" <td height=19 valign=middle ".(($this->atributos["ver_modificar"]=="")?' onclick="grid_modificar('.$this->datos->valor("id").')" style="cursor:pointer"':"")." ".$css_celda.">&nbsp;".$this->datos->valor($alias)."</td> "."\r\n";
    }
	/*
  	if ($this->contador_esp>0 ) {
		for ($a=1;$a<=$this->contador_esp;$a++)
		{
			 $r.=" <td valign=middle class=grid_sec align=center>";
		    			 if ( $this->cols_esp[$a]->c_eval!="")
			 {
			 	  if (eval(" return ".$this->cols_esp[$a]->c_eval.";") )
				  { 
				    $url_esp= $this->cols_esp[$a]->url."&idboj=".$this->datos->valor("id");
					if(strstr($url_esp,"javascript:")) { $url_esp.="')"; }
					$r.="<a href=\"".$url_esp."\" class=\"grid_esp\" target=".$this->cols_esp[$a]->ventana." class=\"grid_esp\">".$this->cols_esp[$a]->titurl."</a></td>"; 
				  }
				  else 
				  { 
					$r.=$this->cols_esp[$a]->titurl."</td>";  
				   }
			 }
			 else
			 { 
			   $r.="<a href=\"".$this->cols_esp[$a]->url."&idboj=".$this->datos->valor("id")." \" target=".$this->cols_esp[$a]->ventana." class=\"grid_esp\">".$this->cols_esp[$a]->titurl."</a></td>"; 
			 }
		}
	} 
	*/
  $r.="</tr>";
}

if ($this->atributos["ver_barra"]=="") {
  $r.='<tr><td align="left" height="20" colspan=20 >';
  if($this->atributos["ver_estado"]=="") {
	$r.='<input type="button" value="Activar" class="grid_bar" onclick="grid_activar_multiple()" />&nbsp;<input type="button" value="Desactivar" class="grid_bar" onclick="grid_desactivar_multiple()" />&nbsp;';
  }
  if($this->atributos["ver_eliminar"]=="") {
     $r.='<input type="button" value="Eliminar" class="grid_bar" onclick="grid_eliminar_multiple()" />&nbsp;'; 
	}
  if($this->atributos["ver_exportar"]=="1") {
   $r.='<input type="button" value="Exportar Filtro" class="grid_bar" onclick="grid_exportar_filtro()" />&nbsp;'; 
  }
  $r.="</td></tr>"."\r\n";
}
 
 
 $t_pag = $this->datos->pagina;

  $r.="<tr><td height=10 ></td></tr><tr><td colspan=20 class=grid_paginado>Total de Registros: ".$this->datos->l_nroreg." ---- ";
 if ($t_pag>1 ) {
   if ($this->p_actual >1  ) {
	 $r.='<a class="grid_nropag" href="#" onclick="grid_pagina('.($p_actual-1).')"> <<< </a>';  			
   }
	 $r.="P&aacute;gina: ".$p_actual." de ".$t_pag;
    if ($p_actual <$t_pag ) {
	  $r.='<a class="grid_nropag" href="#" onclick="grid_pagina('.($p_actual+1).')"> >>></a>';
    }
	$r.=" &nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp; P&aacute;ginas -> &nbsp;";
	for ($a=1;$a<=$t_pag;$a++)
	{
	  if ($a==$p_actual) {$r.="&nbsp;".$a ;}			
	  else { $r.='&nbsp<a class="grid_nropag" href="#" onclick="grid_pagina('.$a.')">'.$a."</a>"; }
	  if(($a %50)==0) $r.="<br>";
	 }
 }
  $r.="</td></tr>";
  $r.=" </td></tr><input type=\"hidden\" name=\"totalreg\" value=\"".$fi."\" /></table></div>"."\r\n";
  $r.=" </td></tr><input type=\"hidden\" name=\"exportar\" value=\"0\" /></table></div>"."\r\n";
}
else { $r.='<br><br><div align="center" class="grid_paginado">No existen registros para mostrar.</div>';}
$this->datos->cerrar();

$r.="</form>"."\r\n";

return $r.$this->ver_js();
}

function ver_js(){
$url=($this->atributos["url"]!="")?$this->atributos["url"]:$_SERVER['PHP_SELF'];
$url_form=($this->atributos["url_form"]!="")?$this->atributos["url_form"]:$url;

$r= " <script>"."\r\n";
$r.= " function armar_ordenar(cad, campo, dire){"."\r\n";
$r.= " var ind, pos,slista;"."\r\n";
$r.= " var spos=-1;"."\r\n";
$r.= " var lista=cad.split(',');"."\r\n";
$r.= " var valor=campo+dire;"."\r\n";
$r.= " for(ind=0; ind<lista.length; ind++)"."\r\n";
$r.= "    {"."\r\n";
$r.= "     if (lista[ind] == valor)"."\r\n";
$r.= "      break;"."\r\n";
$r.= "     }"."\r\n";
$r.= " pos = (ind < lista.length)? ind : -1;"."\r\n";
$r.= " if(pos!=-1) { lista.splice(pos,1); }"."\r\n";
$r.= " else { "."\r\n";
$r.= " for(ind=0; ind<lista.length; ind++)"."\r\n";
$r.= "   {"."\r\n";
$r.= "     slista=lista[ind];"."\r\n";
$r.= "     ncampo=slista.split(' ');"."\r\n";
$r.= "     if (ncampo[0]==campo) {"."\r\n";
$r.= "      spos=ind;"."\r\n";
$r.= "      break;"."\r\n";
$r.= "     }"."\r\n";
$r.= "   }"."\r\n";
$r.= " if(spos!=-1) { lista.splice(spos,1); }"."\r\n";
$r.= " lista.push(valor); }"."\r\n";
$r.= " if(lista[0]=='') { lista.splice(0,1); }"."\r\n";
$r.= " return lista.join(',');"."\r\n";
$r.= " } "."\r\n";
$r.= " function grid_buscar() { "."\r\n";
$r.= "  	var f=document.form_grid; "."\r\n";
$r.="	    f.action ='".$url."';"."\r\n";
$r.="		f.submit();"."\r\n";
$r.="	} "."\r\n";
$r.= " function grid_ordenar_asc(campo) { "."\r\n";
$r.= "  	var f=document.form_grid; "."\r\n";
$r.="	    f.action ='".$url."';"."\r\n";
$r.="	    f.accion.value='';"."\r\n";
$r.="	    f.grid_ordenar.value=armar_ordenar(f.grid_ordenar.value,campo,' asc');"."\r\n";
$r.="		f.submit();"."\r\n";
$r.="	} "."\r\n";
$r.= " function grid_ordenar_desc(campo) { "."\r\n";
$r.= "  	var f=document.form_grid; "."\r\n";
$r.="	    f.accion.value='';"."\r\n";
$r.="	    f.action ='".$url."';"."\r\n";
$r.="	    f.accion.value='';"."\r\n";
$r.="	    f.grid_ordenar.value=armar_ordenar(f.grid_ordenar.value,campo,' desc');"."\r\n";
$r.="		f.submit();"."\r\n";
$r.="	} "."\r\n";

$r.= " function grid_pagina(nro) { "."\r\n";
$r.= "  	var f=document.form_grid; "."\r\n";
$r.="	    f.action ='".$url."';"."\r\n";
$r.="	    f.pag.value=nro;"."\r\n";
$r.="	    f.accion.value='';"."\r\n";
$r.="		f.submit();"."\r\n";
$r.="	} "."\r\n";
$r.= " function grid_modificar(id) { "."\r\n";
$r.= "  	var f=document.form_grid; "."\r\n";
$r.="	    f.action ='".$url_form."';"."\r\n";
$r.="	    f.accion.value=20;"."\r\n";
$r.="	    f.idobj.value=id;"."\r\n";
$r.="		f.submit();"."\r\n";
$r.="	} "."\r\n";
$r.= " function grid_activar(id) { "."\r\n";
$r.= "  	var f=document.form_grid; "."\r\n";
$r.="	    f.action ='".$url."';"."\r\n";
$r.="	    f.accion.value=9;"."\r\n";
$r.="	    f.idobj.value=id;"."\r\n";
$r.="		f.submit();"."\r\n";
$r.="	} "."\r\n";
$r.= " function grid_desactivar(id) { "."\r\n";
$r.= "  	var f=document.form_grid; "."\r\n";
$r.="	    f.action ='".$url."';"."\r\n";
$r.="	    f.accion.value=10"."\r\n";
$r.="	    f.idobj.value=id;"."\r\n";
$r.="		f.submit();"."\r\n";
$r.="	} "."\r\n";
$r.="	function esActivo(isitchecked){" ."\n";
$r.="		if (isitchecked == true){"."\n";
$r.="			document.form_grid.chk_true.value++;" ."\n";
$r.= "		}"."\n";
$r.= "		else {" ."\n";
$r.= "			document.form_grid.chk_true.value--;" ."\n";
$r.= "		}" ."\n";
$r.= "	}"."\n";
$r.="	function grid_eliminar(id) "."\n";
$r.="	{ "."\n";
$r.= "    var f=document.form_grid; "."\r\n";
$r.="	  resp=window.confirm(\"¿ Desea Continuar con la Eliminación del Registro?\");"."\n";
$r.="	  if (resp)" ."\n";
$r.="	  {"."\n";
$r.="	    f.action ='".$url."';"."\r\n";
$r.="	    f.accion.value=8"."\r\n";
$r.="	    f.idobj.value=id;"."\r\n";
$r.="		f.submit();"."\r\n";
$r.="	   }"."\n";
$r.="	  return resp;"."\n";
$r.="	}"."\n";

$r.= "	function marcatodo() {"."\n";
$r.= "	     fldName = 'reg';" ."\n";
$r.= "		var f = document.form_grid;"."\n";
$r.= "		var c = f.multi.checked;" ."\n";
$r.= "		var n2 = 0;" ;
$r.= "		var n = f.totalreg.value;" ."\n";
$r.= "		for (i=1; i <=n; i++) {" ."\n";
$r.= "			cb = eval( 'f.' + fldName + '' + i );"."\n";
$r.= "			if (cb) {" ."\n";
$r.= "				cb.checked = c;" ."\n";
$r.= "				n2++;"."\n";
$r.= "			}" ."\n";
$r.= "		}" ."\n";
$r.= "		if (c) {"."\n";
$r.= "			f.chk_true.value = n2;"."\n";
$r.= "		} else {" ;
$r.= "			f.chk_true.value = 0;" ."\n";
$r.= "		}";
$r.= "	}" ;
$r.= "	function grid_eliminar_multiple() {" ."\n";
$r.= "	var f=document.form_grid;"."\n";
$r.= "     if(f.chk_true.value==0){"."\n";
$r.= "      alert('Debe seleccionar al menos una registro.');"."\n";
$r.= "      return; "."\n";
$r.= "     }"."\n";
$r.= '	var resp=window.confirm("¿ Desea Continuar con la Eliminación del Registro?");'."\n";
$r.= "	 if (resp)"."\n";
$r.= "	  {" ."\n";
$r.="	    f.action ='".$url."';"."\r\n";
$r.= "		f.accion.value=3;"."\n";
$r.= "		f.submit();";
$r.= "	   }"."\n";
$r.= "	 }"."\n";
$r.= "	function grid_activar_multiple() {" ."\n";
$r.= "    var f=document.form_grid;"."\n";
$r.= "     if(f.chk_true.value==0){"."\n";
$r.= "      alert('Debe seleccionar al menos un registro.');"."\n";
$r.= "      return; "."\n";
$r.= "     }"."\n";
$r.="	    f.action ='".$url."';"."\r\n";
$r.="  		f.accion.value = 4; ";
$r.="  		f.submit(); }"."\n";
$r.= "	function grid_desactivar_multiple() {"."\n";
$r.= "      var f=document.form_grid;"."\n";
$r.= "     if(f.chk_true.value==0){"."\n";
$r.= "      alert('Debe seleccionar al menos un registro.');"."\n";
$r.= "      return; "."\n";
$r.= "     }"."\n";
$r.="	    f.action ='".$url."';"."\r\n";
$r.="  		f.accion.value = 5;";
$r.="  		f.submit(); }"."\n";
$r.="	function grid_exportar_filtro(){"."\n";
$r.= "    var f=document.form_grid;"."\n";
$r.="		f.exportar.value='1';"."\n";
$r.="		f.submit();"."\n";
$r.="	}"."\n";
$r.= " </script>"."\r\n";
return $r;

}

function verifica_sel($id) 
{
  $cad=explode(",",$this->descheck);
  for($a=0;$a<count($cad);$a++){
    if($idboj==$cad[$a]) return " "; 
  }
  return " checked=\"checked\" " ;
}

}
?>