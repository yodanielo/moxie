<?php 
header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=natreem_reporte.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin t&iacute;tulo</title>
</head>
<body>
<?php 
include("cls_MantixOaD.php");
include("rsa.class.php");
$db=new MantixOaD();
//desencriptando consulta
$RSA = new RSA(); 
$keys = $RSA->generate_keys ('9990454949', '9990450271', 1); 
$sql=$RSA->decrypt ($_GET["token"], $keys[2], $keys[0]); 
//ejecutando consulta
$r=$db->ejecutar($sql);
//iniciando listado
$tabla="";
$tabla.='<table cellpadding="0" cellspacing="0" border="1">';
//listando cabeceras
$i=0;
$tabla.="<tr>";
while ($i < mysql_num_fields($r)){
	$meta = mysql_fetch_field($r, $i);
	if(!$meta){
		$tabla.="<th>&nbsp;</th>";
	}
	else{
		$tabla.="<th>".$meta->name."</th>";
	}
	$i++;
}
$tabla.="</tr>";
//listando filas
while($row=mysql_fetch_array($r)){
	$tabla.='<tr>';
	$inscrito=true;
	foreach($row as $campo){
		if($inscrito){
			$tabla.='<td>';
			$tabla.=htmlentities($campo);
			$tabla.='</td>';
		}
		$inscrito=!$inscrito;
	}
	$tabla.='</tr>';
}
$tabla.='</table>';
echo $tabla;
?>
</body>
</html>
