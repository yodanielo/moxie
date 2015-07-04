<?
header( 'Expires: Mon, 26 Jul 1997 05:00:00 GMT' );
header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
header( 'Cache-Control: no-store, no-cache, must-revalidate' );
header( 'Cache-Control: post-check=0, pre-check=0', false );
header( 'Pragma: no-cache' );
 include("cls_MantixOaD.php");
 $n=new MantixOaD();
 $n->ejecutar("delete from ".$_GET["tabla"]." where id=".$_GET["idc"]);
 unset($n); 
?>