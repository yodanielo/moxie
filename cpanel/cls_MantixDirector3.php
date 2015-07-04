<?php
include("cls_MantixSession.php");
if($_SESSION["user"]["id"]=="") header("location:index.php");
include("cls_MantixIni.php");
include("cls_MantixTop.php");
include("cls_MantixError20.php");
$w_Error= new MantixError();
switch($_POST["accion"]) {
case 3:	 echo $w_Error->ok($obj_adm->eliminar()); break;
case 4:	 echo $w_Error->ok($obj_adm->activar()); break;
case 5:	 echo $w_Error->ok($obj_adm->desactivar()); break;
case 8:	 echo $w_Error->ok($obj_adm->eliminar_simple()); break;
case 9:	 echo $w_Error->ok($obj_adm->activar_simple()); break;
case 10: echo $w_Error->ok($obj_adm->desactivar_simple()); break;
case 20: 
default: 
}
echo $obj_adm->lista();	
include("cls_MantixFoot.php");
?>