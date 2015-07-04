<?php
include("cls_MantixBase20.php");

class Registro extends MantixBase
{
    function __construct()
    {
//        if(isset($_POST["bus_seccion"])){
            //$_POST["accion"];
//        }
        $this->ini_datos("mox_chicas","id");
        //ésto es para poner
        $sql="select id, estado, ordlosnuestros, ordchicasmoxie, ordchicasmoxiedeldia, losnuestros, chicasmoxie, chicasmoxiedeldia from mox_chicas";
        $db2 = new MantixOaD();
        $res=$db2->ejecutar($sql);
        while($r=mysql_fetch_array($res)){
            $t=array();
            if($r["losnuestros"]=="1"){
                $t[]="Oficiales Moxie";
            }
            if($r["chicasmoxie"]=="1"){
                $t[]="Destacados";
            }
            if($r["chicasmoxiedeldia"]=="1"){
                $t[]="Chicas Moxie";
            }
            $sql="update mox_chicas set tipo='".implode($t,", ")."' where id=".$r["id"];
            $db2->ejecutar($sql);
        }
    }
    function getsecciones(){
        $r='';
//        $r.='<option value="2">Todas las Chicas Moxie</option>';
//        $r.='<option value="1">Los nuestros</option>';
//        $r.='<option value="3">Chicas Moxie del d&iacute;a</option>';
        //nxbjkas
        $r.='<option value="3">Oficiales Moxie</option>';
        $r.='<option value="0">Chicas Moxie</option>';
        $r.='<option value="1">Destacados</option>';
        $r.='<option value="2">Todos los Videos Moxie</option>';
        return $r;
    }
    function lista()
    {
        $r = new MantixGrid();
        $r->buscador=array(
            array("label"=>"Secci&oacute;n:","id"=>"seccion","tipo"=>"select","opciones"=>$this->getsecciones(),"consalto"=>"a"),
        );
        //si seleccioné un parametro
        if(!isset($_POST["bus_seccion"])){
            $_POST["bus_seccion"]="2";
        }
        switch($_POST["bus_seccion"]){
            case "0":
                $sql="select *,if(destacado=1,'destacado','') as destac2 from mox_chicas where chicasmoxiedeldia=1";
                $r->columnas=array(
                    array("titulo"=>"T&iacute;tulo","campo"=>"titulo"),
                    array("titulo"=>"Fecha y hora:","campo"=>"activado"),
                    array("titulo"=>"Tipo:","campo"=>"tipo"),
                    array("titulo"=>"Orden","campo"=>"ordchicasmoxiedeldia"),
                    array("titulo"=>"Destacado:","campo"=>"destac2"),
                );
                $r->atributos=array("sql"=>$sql,"nropag"=>"50","ordenar"=>"ordchicasmoxiedeldia","url_form"=>"videos_moxie.php","url"=>"videos_moxie.php","ver_buscador"=>"1","sin_buscar"=>"1" );
                break;
            case "1":
                $sql="select *,if(destacado=1,'destacado','') as destac2 from mox_chicas where chicasmoxie=1";
                $r->columnas=array(
                    array("titulo"=>"T&iacute;tulo","campo"=>"titulo"),
                    array("titulo"=>"Fecha y hora:","campo"=>"activado"),
                    array("titulo"=>"Tipo:","campo"=>"tipo"),
                    array("titulo"=>"Orden","campo"=>"ordchicasmoxie"),
                    array("titulo"=>"Destacado:","campo"=>"destac2"),
                );
                $r->atributos=array("sql"=>$sql,"nropag"=>"50","ordenar"=>"ordchicasmoxie","url_form"=>"videos_moxie.php","url"=>"videos_moxie.php","ver_buscador"=>"1","sin_buscar"=>"1" );
                break;
            case "3":
                $sql="select *,if(destacado=1,'destacado','') as destac2 from mox_chicas where losnuestros=1";
                $r->columnas=array(
                    array("titulo"=>"T&iacute;tulo","campo"=>"titulo"),
                    array("titulo"=>"Fecha y hora:","campo"=>"activado"),
                    array("titulo"=>"Tipo:","campo"=>"tipo"),
                    array("titulo"=>"Orden","campo"=>"ordlosnuestros"),
                    array("titulo"=>"Destacado:","campo"=>"destac2"),
                );
                $r->atributos=array("sql"=>$sql,"nropag"=>"50","ordenar"=>"ordlosnuestros","url_form"=>"videos_moxie.php","url"=>"videos_moxie.php","ver_buscador"=>"1","sin_buscar"=>"1" );
                break;
            case "2":
                $sql="select *,if(destacado=1,'destacado','') as destac2 from mox_chicas";
                $r->columnas=array(
                    array("titulo"=>"T&iacute;tulo","campo"=>"titulo"),
                    array("titulo"=>"Fecha y hora:","campo"=>"activado"),
                    array("titulo"=>"Tipo:","campo"=>"tipo"),
                    array("titulo"=>"Destacado:","campo"=>"destac2"),
                );
                $r->atributos=array("sql"=>$sql,"nropag"=>"50","ordenar"=>"inserted desc","url_form"=>"videos_moxie.php","url"=>"videos_moxie.php","ver_buscador"=>"1","sin_buscar"=>"1" );
                break;
        }
        //aqui poner la columna correspondiente
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
            array("label"=>"URL del video:","campo"=>"url","tipo"=>"text","obligatorio"=>"1","max_car"=>"42"),
            array("label"=>"Codigo embebido de YouTube:","campo"=>"codigo","tipo"=>"area","obligatorio"=>"1"),
            array("label"=>"Foto peque&ntilde;a:","campo"=>"fotochica","tipo"=>"archivo","obligatorio"=>"1"),
            array("label"=>"Destacar en la portada:","campo"=>"destacado","tipo"=>"checkbox"),
            array("label"=>"Oficiales Moxie:","campo"=>"losnuestros","tipo"=>"checkbox"),
            array("label"=>"Orden en Oficiales Moxie:","campo"=>"ordlosnuestros"),
            array("label"=>"Destacados:","campo"=>"chicasmoxie","tipo"=>"checkbox"),
            array("label"=>"Orden en Destacados:","campo"=>"ordchicasmoxie"),
            array("label"=>"Chicas Moxie:","campo"=>"chicasmoxiedeldia","tipo"=>"checkbox"),
            array("label"=>"Orden en Chicas Moxie:","campo"=>"ordchicasmoxiedeldia"),
            array("label"=>"Estado del registro:","campo"=>"estado","tipo"=>"checkbox"),
        );
        
        return $m_Form->ver()  ;
    }
    function pre_ins(){
        $this->datos->agregar("estado","1");
        $this->datos->agregar("activado",date('Y-m-d H:i:s'));
        $x=preg_replace("/height=\"[0-9]{3}\"/","height=\"200\"",$_POST["codigo"]);
        $x=preg_replace("/width=\"[0-9]{3}\"/","width=\"330\"",$x);
        $x=str_replace("transparent","opaque",$x,$veces);
        if(strripos($x,'wmode')===false){
            $x=str_replace('<param name="movie"','<param name="wmode" value="opaque"></param><param name="movie"',$x);
            $x=str_replace('></embed',' wmode="opaque" ></embed',$x);
        }
        $this->datos->agregar("codigo",$x);
        $t=array();
        if($_POST["losnuestros"]==1){
            $t[]="Oficiales Moxie";
        }
        if($_POST["chicasmoxie"]==1){
            $t[]="Destacados";
        }
        if($_POST["chicasmoxiedeldia"]==1){
            $t[]="Chicas Moxie";
        }
        $this->datos->agregar("tipo",implode($t,", "));
        if($_POST["destacado"]=="1"){
            $this->datos->ejecutar("update mox_chicas set destacado=0");
        }
        if($_POST["losnuestros"]=="1"){
            $_POST["ordlosnuestros"]=$this->insertar_orden("ordlosnuestros",$_POST["ordlosnuestros"]);
            //$this->datos->agregar("losnuestros",1);
        }
        if($_POST["chicasmoxie"]=="1"){
            $_POST["ordchicasmoxie"]=$this->insertar_orden("ordchicasmoxie",$_POST["ordchicasmoxie"]);
            //$this->datos->agregar("chicasmoxie",1);
        }
        if($_POST["chicasmoxiedeldia"]=="1"){
            $_POST["ordchicasmoxiedeldia"]=$this->insertar_orden("ordchicasmoxiedeldia",$_POST["ordchicasmoxiedeldia"]);
            //$this->datos->agregar("chicasmoxiedeldia",1);
        }
    }
    function pre_upd(){
        //$this->datos->agregar("estado","1");
        if($_POST["estado"]=="1" && $_POST["estado_ant"]!=$_POST["estado"]){
            $this->datos->agregar("activado",date('Y-m-d H:i:s'));
        }
        $x=preg_replace("/height=\"[0-9]{3}\"/","height=\"210\"",$_POST["codigo"]);
        $x=preg_replace("/width=\"[0-9]{3}\"/","width=\"330\"",$x);
            $x=str_replace("transparent","opaque",$x,$veces);
            if(strripos($x,'wmode')===false){
                $x=str_replace('<param name="movie"','<param name="wmode" value="opaque"></param><param name="movie"',$x);
                $x=str_replace('></embed',' wmode="opaque" ></embed',$x);
            }
        $this->datos->agregar("codigo",$x);
        $t=array();
        if($_POST["losnuestros"]==1){
            $t[]="Oficiales Moxie";
        }
        if($_POST["chicasmoxie"]==1){
            $t[]="Destacados";
        }
        if($_POST["chicasmoxiedeldia"]==1){
            $t[]="Chicas Moxie";
        }
        $this->datos->agregar("tipo",implode($t,", "));
        if($_POST["destacado"]=="1"){
            $this->datos->ejecutar("update mox_chicas set destacado=0");
        }
        if($_POST["losnuestros"]=="1"){
            $_POST["ordlosnuestros"]=$this->actual_orden("ordlosnuestros",$_POST["ordlosnuestros"]);
        }
        else{
            $this->del_orden("ordlosnuestros",$_POST["ordlosnuestros"]);
        }
        if($_POST["chicasmoxie"]=="1"){
            $_POST["ordchicasmoxie"]=$this->actual_orden("ordchicasmoxie",$_POST["ordchicasmoxie"]);
        }
        else{
            $this->del_orden("ordchicasmoxie",$_POST["ordchicasmoxie"]);
        }
        if($_POST["chicasmoxiedeldia"]=="1"){
            $_POST["ordchicasmoxiedeldia"]=$this->actual_orden("ordchicasmoxiedeldia",$_POST["ordchicasmoxiedeldia"]);
        }
        else{
            $this->del_orden("ordchicasmoxiedeldia",$_POST["ordchicasmoxiedeldia"]);
        }
    }
    function insertar_orden($campo,$orden){
        //if((int)$orden<=0 || trim($orden)==""){
            $orden=1;
        //}
        $d2=new MantixOaD();
        $estado=substr($campo,3);
        $r=1;
        $sql="select max(".$campo.") as conteo from mox_chicas where ".$campo."<".$orden." and ".$estado."=1";
        $r=$d2->get_simple($sql);
        $orden=(int)$r+1;
        //if((int)$orden<=0 || trim($orden)==""){
            $orden=1;
        //}
        $this->datos->agregar($campo,$orden);
        
        $d2->ejecutar("update mox_chicas set ".$campo."=".$campo."+1 where ".$campo.">=".$orden." and ".$estado."=1");
        $this->datos->agregar($campo,$orden);
    }
    function actual_orden($campo,$orden){
        if($_POST[$campo]!=$_POST[$campo."_ant"] || (int)$orden<=0){
            if((int)$orden<=0){
                $orden=1;
            }
            $d2=new MantixOaD();
            $estado=substr($campo,3);
            if($_POST[$campo."_ant"]>=1){
                $sql="update mox_chicas set ".$campo."=".$campo."-1 where ".$campo.">=".$_POST[$campo."_ant"]." and ".$estado."=1";
            }
            
            $d2->ejecutar($sql);
            $sql="select max(".$campo.") as conteo from mox_chicas where ".$campo."<".$orden." and ".$estado."=1";
            $r=$d2->get_simple($sql);
            $orden=(int)$r+1;
            if((int)$orden<=0){
                $orden=1;
            }
            //$this->datos->agregar($campo,$orden);
            $d2->ejecutar("update mox_chicas set ".$campo."=".$campo."+1 where ".$campo.">=".$orden." and ".$estado."=1");
            $this->datos->agregar($campo,$orden);
        }
    }
    function del_orden($campo,$orden){
            $d2=new MantixOaD();
            $estado=substr($campo,3);
            //cojo el estado antes de actualizar
            $sql="select ".$estado." from mox_chicas where id=".$_POST["idobj"];
            $estado_ant=$d2->get_simple($sql);
            if($estado_ant=="1"){
                
                $sql="update mox_chicas set ".$campo."=".$campo."-1 where ".$campo.">=".$orden." and ".$campo.">0";
                $d2->ejecutar($sql);
            }
    }
    function pre_del(){
        $id=$_POST["idobj"];
        $sql="select estado, ordlosnuestros, ordchicasmoxie, ordchicasmoxiedeldia, losnuestros, chicasmoxie, chicasmoxiedeldia from mox_chicas where id=".$id;
        $db2 = new MantixOaD();
        $res=$db2->ejecutar($sql);
        $r=mysql_fetch_array($res);
        if($r["losnuestros"]=="1"){
            $sql="update mox_chicas set ordlosnuestros=ordlosnuestros-1 where ordlosnuestros>".(int)$r["ordlosnuestros"]." and ordlosnuestros>=1";
            $db2->ejecutar($sql);
        }
        if($r["chicasmoxie"]=="1"){
            $sql="update mox_chicas set ordchicasmoxie=ordchicasmoxie-1 where ordchicasmoxie>".(int)$r["ordchicasmoxie"]." and ordchicasmoxie>=1";
            $db2->ejecutar($sql);
        }
        if($r["chicasmoxiedeldia"]=="1"){
            $sql="update mox_chicas set ordchicasmoxiedeldia=ordchicasmoxiedeldia-1 where ordchicasmoxiedeldia>".(int)$r["ordchicasmoxiedeldia"]." and ordchicasmoxiedeldia>=1";
            $db2->ejecutar($sql);
        }
    }
    //<object width="425" height="344"><param name="movie" value="http://www.youtube.com/v/m2T1NbFlvZ4&hl=es&fs=1&"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/m2T1NbFlvZ4&hl=es&fs=1&" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="425" height="344"></embed></object>
    //update mox_chicas set chicasmoxiedeldia=chicasmoxiedeldia+3
}
?>