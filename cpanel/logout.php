<?
include_once("cls_MantixOaD.php");
include_once("cls_MantixSession.php");
session_unset();
session_destroy();
$_SESSION = array();
header("location:index.php");
?>