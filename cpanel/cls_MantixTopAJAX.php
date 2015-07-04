<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<META HTTP-EQUIV="Cache-Control" CONTENT="no-cache">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<META content="MSHTML 6.00.2900.2180" name=GENERATOR>
<link rel="stylesheet" type="text/css" href="css/cpanel.css">
<link rel="stylesheet" type="text/css" href="css/lightbox.css">
<link rel="stylesheet" type="text/css" href="css/dialog.2.0.css">
<script src="scripts/prototype.js"></script>
<script src="scripts/scriptaculous.js"></script>
<script src="scripts/lightbox.js"></script>
<script src="scripts/dialog.2.0.js"></script>
<script src="scripts/scw.js"></script>
<script src="scripts/validaciones.js"></script>
<script src="scripts/menu_ini.js"></script>
</head> 
<body>
<div id="img_top"><img src="images/<?=$img_top?>" border="0" ></div>
<div id="main_usuarioses">
<div id="main_usuario"><?=$_SESSION["user"]["nombre"];?></div>
<div id="main_sesion"><a href="mod_pass.php" class="main_sec">Modificar Contrase&ntilde;a</a>
<a href="logout.php" class="main_sec" >&nbsp;&nbsp;&nbsp;&nbsp;Cerrar Sesi&oacute;n</a> &nbsp;&nbsp;&nbsp;</div>
</div>
<div id="main_menu" align="center"><?=$menu->ver()?></div>
<div id="main_titulo" align="center"><?=($titulo)?></div>