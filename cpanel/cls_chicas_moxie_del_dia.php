<?php
include("cls_MantixBase20.php");

class Registro extends MantixBase
{
    function __construct()
    {
        $this->ini_datos("mox_chicas","id");
    }

    function lista()
    {
        $r = new MantixGrid();
        $sql="select * from mox_chicas where seccion='Chicas Moxie del d&iacute;a'";
        $r->atributos=array("sql"=>$sql,"nropag"=>"20","ordenar"=>"id","url_form"=>"chicas_moxie_del_dia.php","url"=>"chicas_moxie_del_dia.php");
        $r->columnas=array(
            array("titulo"=>"T&iacute;tulo","campo"=>"titulo"),
            array("titulo"=>"Fecha y hora:","campo"=>"inserted"),
        );

        return $r->ver();
    }

    function formulario()
    {
        $m_Form = new MantixForm();
        $m_Form->atributos=array("texto_submit"=>"Chica Moxie");
        $m_Form->datos=$this->datos;
        $m_Form->controles=array(
            array("label"=>"T&iacute;tulo:","campo"=>"titulo","tipo"=>"text","obligatorio"=>"1"),
            array("label"=>"Descripci&oacute;n:","campo"=>"descripcion","tipo"=>"area"),
            array("label"=>"URL del video:","campo"=>"url","tipo"=>"text","obligatorio"=>"1"),
            array("label"=>"Codigo embebido de YouTube:","campo"=>"codigo","tipo"=>"area","obligatorio"=>"1"),
            array("label"=>"Foto peque&ntilde;a:","campo"=>"fotochica","tipo"=>"archivo","obligatorio"=>"1"),
        );
        return $m_Form->ver();
    }
    function pre_ins(){
        $this->datos->agregar("estado","1");
        $x=preg_replace("/height=\"[0-9]{3}\"/","height=\"200\"",$_POST["codigo"]);
        $x=preg_replace("/width=\"[0-9]{3}\"/","width=\"330\"",$x);
        $this->datos->agregar("codigo",$x);
        $this->datos->agregar("seccion","Chicas Moxie del d&iacute;a");
    }
    function pre_upd(){
        $this->datos->agregar("estado","1");
        $x=preg_replace("/height=\"[0-9]{3}\"/","height=\"200\"",$_POST["codigo"]);
        $x=preg_replace("/width=\"[0-9]{3}\"/","width=\"330\"",$x);
        $this->datos->agregar("codigo",$x);
        $this->datos->agregar("seccion","Chicas Moxie del d&iacute;a");
    }
    //<object width="425" height="344"><param name="movie" value="http://www.youtube.com/v/m2T1NbFlvZ4&hl=es&fs=1&"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/m2T1NbFlvZ4&hl=es&fs=1&" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="425" height="344"></embed></object>
}
?>