<?php
class MantixError
{
function ok($res,$opt=0){
if($opt==1) {
echo '<script>window.location="'.$PHP_SELF.'?opt='.$res.'";</script>'; 
}
else {
 if($_GET["opt"]!="" && $res=="") {  
    $f='<div id="main_msg" align=center>'.$_GET["opt"].'</div>';
	echo ($f);
  }
 if($res!="") {
	$f='<div id="main_msg" align=center>'.$res."</div>";
	echo ($f);
 }
}
}
}
?>