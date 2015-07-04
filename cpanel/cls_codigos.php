<?php
include("cls_MantixBase20.php");

class Registro extends MantixBase
{
    function __construct()
    {
        $this->ini_datos("mox_codigos","id");
    }
    function lista()
    {
        $r = new MantixGrid();
        $r->buscador=array(
            array("label"=>"C&oacute;digo:","campo"=>"valor","id"=>"cod"),
        );
        $r->atributos=array("tabla"=>"mox_codigos","nropag"=>"50","ordenar"=>"id","ver_eliminar"=>"0","ver_buscador"=>"1");
        $r->columnas=array(
            array("titulo"=>"C&oacute;digo","campo"=>"valor"),
            array("titulo"=>"Fecha y hora","campo"=>"inserted"),
            array("titulo"=>"Fue v&aacute;lido","campo"=>"valido")
        );

        return $r->ver();
    }
    function formulario()
    {
        $m_Form = new MantixForm();
        $m_Form->atributos=array("texto_submit"=>"C&oacute;digo v&aacute;alido");
        $m_Form->datos=$this->datos;
        $m_Form->controles=array(
            array("label"=>"C&oacute;digo:","campo"=>"valor","extras"=>"readonly"),
            array("label"=>"Fecha y hora de ingreso:","campo"=>"inserted","extras"=>"readonly"),
            array("label"=>"Fue v&aacute;lido:","campo"=>"valido","extras"=>"readonly"),
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