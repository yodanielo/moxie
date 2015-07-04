<?php
include("cls_usuarios.php");
$obj_adm=new Usuarios;
$titulo="Modificar Contraseña";

include("cls_MantixSession.php");
if($_SESSION["user"]["id"]=="") header("location:index.php");
include("cls_MantixIni.php");
include("cls_MantixTop.php");
include("cls_MantixError20.php");
$w_Error= new MantixError();
switch($_POST["accion"]) {
case 1:  echo $w_Error->ok($obj_adm->modificar_password()); break;
}
echo $obj_adm->formulario_modpass();
include("cls_MantixFoot.php");
?>