<?php
include("cls_MantixBase20.php");

class Registro extends MantixBase
{
    function __construct()
    {
        $this->ini_datos("mox_chicas","id");
    }
    function getsecciones(){
        $r='';
        $r.='<option value="\'Los nuestros\'">Los nuestros</option>';
        $r.='<option value="\'Todas las Chicas Moxie\'">Todas las Chicas Moxie</option>';
        $r.='<option value="\'Chicas Moxie del d&iacute;a\'">Chicas Moxie del d&iacute;a</option>';
        return $r;
    }
    function lista()
    {
        $r = new MantixGrid();
        $r->buscador=array(
            array("label"=>"T&iacute;tulo:","campo"=>"titulo","id"=>"tit"),
            array("label"=>"Secci&oacute;n:","campo"=>"seccion","id"=>"sec","tipo"=>"select","opciones"=>$this->getsecciones()),
        );
        $sql="SELECT m.`id`, m.`codigo`, m.`titulo`, m.`url`, m.`fotochica`, m.`seccion`, if(destacado=1,'destacado','') as destacado, m.`inserted`, m.`updated`, m.`user_inserted`, m.`user_updated`, m.`estado` FROM mox_chicas m";
        $r->atributos=array("sql"=>$sql,"nropag"=>"20","ordenar"=>"id","url_form"=>"destacados_1.php","url"=>"destacados.php","ver_eliminar"=>"0","ver_buscador"=>"1");
        $r->columnas=array(
            array("titulo"=>"T&iacute;tulo","campo"=>"titulo"),
            array("titulo"=>"Destacado","campo"=>"destacado"),
            array("titulo"=>"Fecha y hora:","campo"=>"inserted"),
        );

        return $r->ver();
    }
    function formulario()
    {
        $m_Form = new MantixForm();
        $m_Form->atributos=array("texto_submit"=>"Chica Moxie","mostrar_volver"=>"1","url_volver"=>"destacados.php");
        $m_Form->datos=$this->datos;
        $m_Form->controles=array(
            array("label"=>"T&iacute;tulo:","campo"=>"titulo","tipo"=>"text","extras"=>"readonly"),
            array("label"=>"URL del video:","campo"=>"url","tipo"=>"text","extras"=>"readonly"),
            array("label"=>"Codigo embebido de YouTube:","campo"=>"codigo","tipo"=>"area","extras"=>"readonly"),
            array("label"=>"Foto peque&ntilde;a:","campo"=>"fotochica","extras"=>"readonly"),
            array("label"=>"Destacar video:","campo"=>"destacado","tipo"=>"checkbox"),
        );
        return $m_Form->ver();
    }
    function pre_upd(){
        $this->datos->agregar("estado","1");
        $ndb=new MantixOaD();
        $ndb->ejecutar("update mox_chicas set destacado=0");
        //$this->datos->ejecutar("update mox_chicas set destacado=0");

    }
    //<object width="425" height="344"><param name="movie" value="http://www.youtube.com/v/m2T1NbFlvZ4&hl=es&fs=1&"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/m2T1NbFlvZ4&hl=es&fs=1&" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="425" height="344"></embed></object>
}
?>