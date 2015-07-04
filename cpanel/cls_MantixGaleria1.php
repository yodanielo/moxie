<?
header( 'Expires: Mon, 26 Jul 1997 05:00:00 GMT' );
header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
header( 'Cache-Control: no-store, no-cache, must-revalidate' );
header( 'Cache-Control: post-check=0, pre-check=0', false );
header( 'Pragma: no-cache' );

if($_GET["idc"]!="") {
 include("cls_MantixOaD.php");
 $n=new MantixOaD();
 $n->listaSP("select * from ".$_GET["tabla"]." where idc=".$_GET["idc"]." order by id","","");
 $imgs=array();
 $imgsid=array();
 while($n->no_vacio()) {
  array_push($imgs,$n->valor("imagen"));
  array_push($imgsid,$n->valor("id"));
 }
 echo implode("/-/",$imgs).'///'.implode(",",$imgsid);
}
?>