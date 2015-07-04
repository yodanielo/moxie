<?
header( 'Expires: Mon, 26 Jul 1997 05:00:00 GMT' );
header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
header( 'Cache-Control: no-store, no-cache, must-revalidate' );
header( 'Cache-Control: post-check=0, pre-check=0', false );
header( 'Pragma: no-cache' );
 include("cls_MantixOaD.php");
 $n=new MantixOaD();
 if($_GET["pos"]==-1) { $n->listaSP("select * from ".$_GET["tabla"]." where id<".$_GET["id"]." order by id desc limit 1","",""); }
 else { $n->listaSP("select * from ".$_GET["tabla"]." where id>".$_GET["id"]." order by id asc limit 1","",""); }
 if($n->no_vacio()) {
   $posn=$n->valor("imagen");
   $idp=$n->valor("id");
   $n->ejecutar("update ".$_GET["tabla"]." set imagen='".$_GET["imagen"]."' where id=".$idp);
   $n->ejecutar("update ".$_GET["tabla"]." set imagen='".$posn."' where id=".$_GET["id"]);
 }
 unset($n); 
?>