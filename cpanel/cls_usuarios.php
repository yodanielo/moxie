<?php
include("cls_MantixBase20.php");

class Usuarios extends MantixBase
{
var $id;
var $nombres;
var $apellidos;
var $usuario;
var $password;

function __construct()
{
 $this->ini_datos("mox_usuarios","usuario");
}

function get_fila2($idu)
{
 $this->datos->get_fila($idu);
 $this->id=$this->datos->valor("id");
 $this->nombres=$this->datos->valor("nombres");
 $this->apellidos=$this->datos->valor("apellidos");
 $this->usuario=$this->datos->valor("usuario");
 $this->password=$this->datos->valor("password");
}

function full_nombre() {
 return $this->datos->valor("nombres")."&nbsp;".$this->datos->valor("apellidos");
}

function pre_ins() {
 $this->datos->agregar("password",md5($_POST["password"]));
}

function pre_upd() {
 $this->datos->agregar("password",md5($_POST["password"]));
}

function ingresar()
{
	$this->datos->listaSP("select * from mox_usuarios where usuario='".str_replace("'","",trim($_POST["txt_user"]))."' and password='".md5(trim($_POST["txt_pass"]))."'","","");
	if ($this->datos->no_vacio())  { 
		  include_once("cls_MantixSession.php");
		  $_SESSION["user"]=array("id"=>$this->datos->valor("id"),"nombre"=>$this->datos->valor("nombres")." ".$this->datos->valor("apellidos"),"usuario"=>$this->datos->valor("usuario"),"password"=>$this->datos->valor("password"));
		  return 0; 
	 }
	 else  { return 11;	 }
}


function modificar_password()
{
if($_SESSION["user"]["password"]==trim(md5($_POST["password"]))) {
	$this->datos->ejecutar("update mox_usuarios set password='".md5(trim($_POST["nueva"]))."' where id=".$_SESSION["user"]["id"]);
    return "Contrase�a cambiada correctamente."; 
}
else { return "Verifique la Contrase�a actual."; }
}

function lista()
{
$r = new MantixGrid();
$r->atributos=array("tabla"=>"mox_usuarios","nropag"=>"20","ordenar"=>"usuario");
$r->columnas=array(
 					array("titulo"=>"Usuario","campo"=>"usuario","ancho"=>"25%"),
 					array("titulo"=>"Nombres","campo"=>"nombres","ancho"=>"25%"),
 					array("titulo"=>"Apellidos","campo"=>"apellidos","ancho"=>"45%")
				  );
				
return $r->ver();
}

function formulario()
{

$m_Form = new MantixForm();
$m_Form->atributos=array("texto_submit"=>"Usuario");
$m_Form->datos=$this->datos;
$m_Form->controles=array(
array(
		"label"=>"Usuario:",
		"campo"=>"usuario",
		"obligatorio"=>"1",
		"max_car"=>"30"
		),
  array(
		"label"=>"Contrase&ntilde;a:",
		"campo"=>"password",
		"obligatorio"=>"1",
		"tipo"=>"password",
		"valor"=>"NULL",
		"max_car"=>"30"
		),
  array(
		"label"=>"Reingresar Contrase&ntilde;a:",
		"obligatorio"=>"1",
		"campo"=>"rpass",
		"max_car"=>"30",			
		"valor"=>"NULL",
		"tipo"=>"password"
		),
  array(
		"label"=>"Nombres:",
		"campo"=>"nombres",
		"obligatorio"=>"1"
		),
  array(
		"label"=>"Apellidos:",
		"campo"=>"apellidos",
		"obligatorio"=>"1",
		),
  array("label"=>"Estado del registro:","campo"=>"estado","tipo"=>"select","opciones"=>$this->cbo_activo())
	);

$res = $m_Form->ver();
return  $res;
}

function formulario_modpass()
{
$m_Form = new MantixForm();
$m_Form->atributos=array("texto_submit"=>"Contrase�a");
$m_Form->datos=$this->datos;
$m_Form->controles=array(
array(
		"label"=>"Contrase�a  Actual:",
		"campo"=>"password",
		"obligatorio"=>"1",
		"tipo"=>"password",		
		"valor"=>"NULL",		
		"max_car"=>"30"
		),
  array(
		"label"=>"Contrase�a Nueva:",
		"campo"=>"nueva",
		"valor"=>"NULL",
		"obligatorio"=>"1",
		"tipo"=>"password",
		"max_car"=>"30"
		),
  array(
		"label"=>"Reingresar Contrase�a:",
		"obligatorio"=>"1",
		"campo"=>"rpass",
		"max_car"=>"30",			
		"valor"=>"NULL",
		"tipo"=>"password"
		));

$res = str_replace("f.submit();","if ( f.rpass.value!=f.nueva.value  ) { alert ('La contrase�a nueva no coincide con el reingreso.'); f.nueva.focus(); return; } f.submit();", $m_Form->ver());
return  $res;
}
}
?>