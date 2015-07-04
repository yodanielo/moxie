<?php
include("cls_MantixMenu.php");
$menu =new MantixMenu();
$menu->opciones = array(
    array("titulo"=>"Usuarios" ,"url"=>"usuarios.php","id"=>"usuarios1"),
    array("titulo"=>"Videos Moxie" ,"url"=>"videos_moxie.php","id"=>"chicas",
//        "sub"=>array(
//            array("titulo"=>"Destacados" ,"url"=>"destacados.php","id"=>"chicas1"),
//            array("titulo"=>"Los nuestros" ,"url"=>"los_nuestros.php","id"=>"chicas2"),
//            array("titulo"=>"Chicas Moxie del d&iacute;a" ,"url"=>"chicas_moxie_del_dia.php","id"=>"chicas3"),
//            array("titulo"=>"Todas las Chicas Moxie" ,"url"=>"todas_las_chicas_moxie.php","id"=>"chicas4"),
//        ),
    ),
    array("titulo"=>"Comprobaci&oacute;n de c&oacute;digos" ,"url"=>"codigos.php","id"=>"usuarios1"),
);
$img_top="bg-top.png";
$usuario="";
?>
