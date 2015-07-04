<?php
class Columna
{
  var $nombre;
  var $valor;
  var $tipo;
  var $marcado;
  
  function formato()
  {  
      switch ($this->tipo)
      {
       case "auto_increment" : return ""; break;
       case "string" : return "'".$this->valor."'";  break;
       case "date" : 
	     if($this->valor!="") {
		     $partes=explode("/",$this->valor);
		     return "'".$partes[2]."-".$partes[1]."-".$partes[0]."'"; break;
		 }
		 else { return 'null'; break;}
       case "datetime" : 
		  return "'".$this->valor."'";  break;

 	   case "time" : return "'".$this->valor."'"; break;
 	   case "blob" : return "'".$this->valor."'"; break;
	   case "longtext" : return "'".$this->valor."'"; break;
	   case "real":
       case "int" : if($this->valor=='') { return 0; }
       			    else {
       			    	if ($this->valor=='on') { return 1; }
       			    	else {return $this->valor; }
       			    } break;
      }
      return $this->valor;
   }	
   function Columna($nombre,$tipo,$valor)
   {
   	$this->nombre=$nombre;
   	$this->tipo=$tipo;
   	$this->valor=$valor;
	$this->marcado=0;
   }
}

class MantixOaD
{
var $tabla;
var $criterio;
var $valor;
var $cols;
var $total;
var $indice;
var $rs;
var $db;
var $sql;
var $pagina;
var $l_nropag;
var $l_nroreg;
var $s_con;
var $error;
var $c_error;
var $con;

function MantixOaD()
{
	$this->total=0;
	$this->indice=1;
	$this->c_error=0;
	$this->cols = array();
	//$this->con = mysql_connect("localhost","root","") or die(mysql_error());
	//$this->db =  mysql_select_db("nexus", $this->con);
	$this->con = mysql_connect("mysql3.gdom.net","0prg9","0dDsrrll9") or die(mysql_error());
	$this->db =  mysql_select_db("dbddesarrollo", $this->con);

}

function ejecutar($sql)
{
	return mysql_query($sql,$this->con);
}

function filas_afectadas(){
 return mysql_affected_rows();
}

function lastid(){
 return mysql_insert_id ($this->con);
}

function comando($sql)
{
   $r = $this->ejecutar($sql);
   if (!$r)
   {
	  	 $this->error = mysql_error($r);
	  	 $this->c_error= 5;
   }
   return $r;
}

function reiniciar()
{
   $this->total=0;
}

function valor($nombre)
{
  	$v = 0;
 	for ($a=1;$a<=$this->total;$a++)
	{
		if ($this->cols[$a]->nombre == $nombre) { 
		  switch($this->cols[$a]->tipo) 
		   {
		     case "date": 
			    if($this->cols[$a]->valor!="" && $this->cols[$a]->valor!="0000-00-00" ) { $v = date("d/m/Y",strtotime($this->cols[$a]->valor)); } else { $v="" ; } break;
			 case "datetime":  
  			 if($this->cols[$a]->valor!="") { $v = date("d/m/Y H:i:s",strtotime($this->cols[$a]->valor)); } else { $v=""; } 
			
				break;			 
			   default:  $v = htmlspecialchars($this->cols[$a]->valor); break;
		  }
		}
	}
	return $v;
}

function tipo($nombre)
{

	for ($a=1;$a<=$this->total;$a++)
	{ if ($this->cols[$a]->nombre == $nombre) { return $this->cols[$a]->tipo; }	}
}

function nom_col($ide)
{
  	$v = 0;
 	for ($a=1;$a<=$this->total;$a++)
	{
		if ($a == $ide) { return $this->cols[$a]->nombre;	}
	}
	return "";
}
function vfecha($nombre)
{
  	$v = 0;
 	for ($a=1;$a<=$this->total;$a++)
	{
		if ($this->cols[$a]->nombre == $nombre) {    $v = $this->cols[$a]->valor;	  }
	}
	return $v;
}


function agregar( $nombre, $valor )
{
	for ($a=1;$a<=$this->total;$a++)
	{
		 if ($this->cols[$a]->nombre == $nombre) { 
		   $this->cols[$a]->valor =$valor;
		   $this->cols[$a]->marcado=1; 
	     }
	}
}

function marcar($nombre)
{
	for ($a=1;$a<=$this->total;$a++)
	{
		 if ($this->cols[$a]->nombre == $nombre) { 
		   $this->cols[$a]->marcado=1; 
	     }
	}
}
function desmarcar($nombre)
{
	for ($a=1;$a<=$this->total;$a++)
	{
		 if ($this->cols[$a]->nombre == $nombre) { 
		   $this->cols[$a]->marcado=0; 
	     }
	}
}

function agregar_todo()
{
	for ($a=1;$a<=$this->total;$a++)
	{
     	$this->cols[$a]->valor = $_POST[$this->cols[$a]->nombre];
		$this->cols[$a]->marcado = 1;
	}
}

function encuentra()
{
	$csql="select id from ".$this->tabla." where ".$this->criterio."='".$this->valor."'";
 	$r = $this->ejecutar($csql);
	if ($r){ $e = (mysql_num_rows($r)>0); }
	else
	{
		$this->error = mysql_error($r);
	  	$this->c_error= 18;
		return false;
	}
	return $e;
}

function get_simple($sql)
{
	$r = $this->ejecutar($sql);
	if ($r)
	{
		if (mysql_num_rows($r)>0) 
		{ 
				$col=mysql_fetch_array($r);
				return $col[0];
		}
	}
	else
	{
			$this->error = mysql_error();
			$this->c_error= 60;
			return "";
	}	
}

function busca_libre_id($str_criterio)
{
	$r = $this->ejecutar("select id from ".$this->tabla." where ".$str_criterio);
	if ($r)
	{
		if (mysql_num_rows($r)>0) 
		{ 
				$col=mysql_fetch_array($r);
				return $col["id"];
		}
	}
	else
	{
			$this->error = mysql_error();
			$this->c_error= 7;
			return 0;
	}	
}

function buscar($tabla, $campo,$cid)
{
	$r = $this->ejecutar("select * from ".$tabla." where ".$campo."=".$cid);
	if ($r)	{	return (mysql_num_rows($r)>0);	}
	else {	return false; }	
}

function get_fila_libre($str_sql)
{
	$this->rs = $this->ejecutar($str_sql);  
	if (!$this->rs)
	{
	   	$this->error = mysql_error();
	  	$this->c_error= 10;
	  	return 0;
	}
}
function get_fila($idv)
{
		if ($this->sql=="") { $this->rs = $this->ejecutar("select * from " .$this->tabla." where id=".$idv); }
		else { $this->rs = $this->ejecutar($this->sql);  }
 		if ($this->rs)
		{
			if (mysql_num_rows($this->rs)>0)
			{
			$this->total = mysql_numfields($this->rs);
			$fila=mysql_fetch_array($this->rs);
			for ($a=1;$a<=$this->total;$a++) { $this->cols[$a] = new Columna(mysql_field_name($this->rs,$a-1),mysql_field_type($this->rs,$a-1),$fila[$a-1]);  }
			}
		}
		else
		{
			$this->error = mysql_error();
			$this->c_error= 10;
			return 0;
		}
}

function get_tipos()
{
	$r = $this->ejecutar("select * from ".$this->tabla." limit 1");
	$this->total = mysql_numfields($r);
	for ($a=1;$a<=$this->total;$a++)
	{
		$this->cols[$a] = new Columna(mysql_field_name($r,$a-1),mysql_field_type($r,$a-1),"");
	}
}
 
function forma_insert()
{
    $cad=" (";
    for ($a=2;$a<=$this->total;$a++)
    {
       if($this->cols[$a]->marcado==1)
	   {
	     if ( $a>2 && strlen($cad)>2) { $cad.=",";}
         $cad.=$this->cols[$a]->nombre; 
		}
     }
    $cad_columnas = $cad.") " ;
    $cad=" (";
    
    for ($a=2;$a<=$this->total;$a++)
    {
	   if($this->cols[$a]->marcado==1)
	    {
         if ($a >2 && strlen($cad)>2) { $cad.=",";}
         $cad.=$this->cols[$a]->formato();
		}
     }
    $cad_valores= $cad.") ";
	
 return "insert into ".$this->tabla.$cad_columnas." values ".$cad_valores;
}

function forma_update($cid)
{
	$cad=" ";
	for ($a=2;$a<=$this->total;$a++)
    {  
      if($this->cols[$a]->marcado==1)
	   {
	    if ($a>2 && strlen($cad)>2) { $cad.=","; }
        $cad.= $this->cols[$a]->nombre."=".$this->cols[$a]->formato(); 
	   }
	   
    }
	
    return "update ".$this->tabla." set ".$cad." where id=".$cid;
}

function insertar ()
{
	if ($this->encuentra()) { $this->c_error=18; return -1;}
	else 
	{
	 	$csql=$this->forma_insert();
		$r = $this->ejecutar($csql);
		if (!$r)
		{
			$this->error = mysql_error($r);
			$this->c_error= 16;
			return -1;
	    }
	}
	return $r;
}

function insertar_sin_val ()
{
   	$r = $this->ejecutar($this->forma_insert());
	if (!$r)
	{
	    $this->error = mysql_error($r);
	  	$this->c_error= 17;
	  	return -1;
	 }
   return $r;
}

function actualizar($cid)
{
  	$r = $this->ejecutar($this->forma_update($cid));
	if (!$r)
	{
	    $this->error = mysql_error();
		$this->c_error= 17;
	  	return -1;
	  }
	 return $r;
}
	   
function eliminar_simple($cid)
{
   	$r = $this->ejecutar("delete from " .$this->tabla." where id = ".$cid);
	if (!$r)
	{
	    $this->error = mysql_error($r);
	  	$this->c_error= 40;
	  	return -1;
	}
	return $r;
}
function eliminar_key($tabla,$campo,$cid)
{
   	$r = $this->ejecutar("delete from " .$tabla." where ".$campo." = ".$cid);
	if (!$r)
	{
	    $this->error = mysql_error($r);
	  	$this->c_error= 40;
	  	return -1;
	}
	return $r;
}
function activar_simple($cid)
{
	$r = $this->ejecutar("update ".$this->tabla." set estado=1 where id = " .$cid);
	if (!$r)
	{
	    $this->error = mysql_error($r);
	  	$this->c_error= 19;
	  	return -1;
    }
        $p=$this->ejecutar("update ".$this->tabla." set activado=now() where id=".$cid);

	return $r;
}
	   
function desactivar_simple($cid)
{
	$r = $this->ejecutar("update ".$this->tabla." set estado=0 where id = " .$cid);
	if (!$r)
	{
	    $this->error = mysql_error($r);
	  	$this->c_error= 19;
	  	return -1;
    }
	 return $r;
}
function eliminar()
{

   	$r = $this->ejecutar("delete from " .$this->tabla."  where id IN (" .implode(",",$_POST["cid"]).")");
	if (!$r)
	{
	    $this->error = mysql_error($r);
	  	$this->c_error= 40;
	  	return -1;
	}
	return $r;
}

function activar()
{
    $r = $this->ejecutar("update ".$this->tabla." set estado=1 where id IN (" .implode(",",$_POST["cid"]).")");
	if (!$r)
	{
	    $this->error = mysql_error($r);
	  	$this->c_error= 19;
	  	return -1;
    }
    $p=$this->ejecutar("update ".$this->tabla." set activado=now() where id IN (" .implode(",",$_POST["cid"]).")");
	return $r;
}
	   
function desactivar()
{
    $r = $this->ejecutar("update ".$this->tabla." set estado=0  where id IN (" .implode(",",$_POST["cid"]).")");
	if (!$r)
	{
	    $this->error = mysql_error($r);
	  	$this->c_error= 19;
	  	return -1;
    }
	 return $r;
}

function contar($str_sql)
{
	$r = $this->ejecutar($str_sql);
	if ($r)
	{
		$fila = mysql_fetch_array($r);
		$co = $fila[0];
     }
	else
	  {
	    $this->error = mysql_error($r);
	  	$this->c_error= 19;
	  	return -1;
	  }
	 return $co;
}

function lista($str_sql,$str_filtro, $str_orden,$p_actual )
{

    $c_filtro="";
	$c_sql=$str_sql;
	$c_orden=$str_orden;
	if ($c_sql=="") { $c_sql =" select * from ".$this->tabla; }
	if ($c_orden!="") {$c_orden =" order by ".$c_orden; }
    if (strlen($str_filtro)>2) { $c_filtro=" where ".$str_filtro;}
	
	$c_sql.=$c_filtro.$c_orden;
	$cant= $this->ejecutar($c_sql);
	$t_reg= mysql_num_rows($cant); 
	$this->l_nroreg=$t_reg;
	$lsob=0;
	$this->pagina= floor($t_reg/$this->l_nropag);
	
	if ($this->pagina>0)
	  { 
	  	$sob = $t_reg % $this->l_nropag; 
	  	if ($sob>0) { $lsob=1; } else { $lsob=0;} 
	  }
	$this->pagina+=$lsob;
	$c_sql=$c_sql." limit ".($p_actual-1)*$this->l_nropag.",".$this->l_nropag;
	$this->rs = $this->ejecutar($c_sql);
	
	if (mysql_num_rows($this->rs)>0)
		{
		return 1;
		}
	else
	{ return 0; }
}

function listaSP($sql,$filtro,$orden )
{
    $c_filtro="";
	$c_sql=$sql;
	$c_orden=$orden;
	if ($c_sql=="") { $c_sql =" select * from ".$this->tabla;}
	if ($c_orden!="") { $c_orden =" order by ".$c_orden;}
    if (strlen($filtro)>2) { $c_filtro=" where ".$filtro;}
	
	$c_sql.=$c_filtro.$c_orden;
	$this->rs = $this->ejecutar($c_sql);
	$t_reg= mysql_num_rows($this->rs);
	$this->l_nroreg=$t_reg;

	if ($t_reg>0)
		{
		return 1;
		}
	else
	{ return 0; }
	
}
function nro_registros()
{ return mysql_num_rows($this->rs);
}
function siguiente()
{
  	
}
function vacio()
{
	return (mysql_num_rows($this->rs)==0);
}
function no_vacio()
{
   if ($fila=mysql_fetch_array($this->rs))
   {
	   $this->total = mysql_numfields($this->rs);
		for ($a=1;$a<=$this->total;$a++)
		{ $this->cols[$a] = new Columna(mysql_field_name($this->rs,$a-1),mysql_field_type($this->rs,$a-1),$fila[$a-1]);}
   }
   return $fila;
}

function cerrar()
{
  mysql_close($this->con);
}
}
?>