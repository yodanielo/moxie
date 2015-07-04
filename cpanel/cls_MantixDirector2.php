<?php
include("cls_MantixSession.php");
if($_SESSION["user"]["id"]=="") header("location:index.php");
include("cls_MantixIni.php");
include("cls_MantixTop.php");
include("cls_MantixError20.php");
$w_Error= new MantixError();
switch($_POST["accion"]) {
case 1:  echo $w_Error->ok($obj_adm->insertar(),1); break;
case 2:  echo $w_Error->ok($obj_adm->actualizar()); break;
case 3:	 echo $w_Error->ok($obj_adm->eliminar()); break;
default: echo $w_Error->ok("");
}
echo $obj_adm->formulario();
include("cls_MantixFoot.php");
?>