<?php
require_once ('../config.php');
$protects = array('_REQUEST', '_GET', '_POST', '_COOKIE', '_FILES', '_SERVER', '_ENV', 'GLOBALS', '_SESSION');
foreach ($protects as $protect) {
    if ( in_array($protect , array_keys($_REQUEST)) ||
        in_array($protect , array_keys($_GET)) ||
        in_array($protect , array_keys($_POST)) ||
        in_array($protect , array_keys($_COOKIE)) ||
        in_array($protect , array_keys($_FILES))) {
        die("Invalid Request.");
    }
}

/**
 * used to leave the input element without trim it
 */
define( "_MOS_NOTRIM", 0x0001 );
/**
 * used to leave the input element with all HTML tags
 */
define( "_MOS_ALLOWHTML", 0x0002 );
/**
 * used to leave the input element without convert it to numeric
 */
define( "_MOS_ALLOWRAW", 0x0004 );
/**
 * used to leave the input element without slashes
 */
define( "_MOS_NOMAGIC", 0x0008 );

function mosgetparam( &$arr, $name, $def=null, $mask=0 ) {
    if (isset( $arr[$name] )) {
        if (is_array($arr[$name])) foreach ($arr[$name] as $key=>$element) $result[$key] = mosGetParam ($arr[$name], $key, $def, $mask);
        else {
            $result = $arr[$name];
            if (!($mask&_MOS_NOTRIM)) $result = trim($result);
            if (!is_numeric( $result)) {
                if (!($mask&_MOS_ALLOWHTML)) $result = strip_tags($result);
                if (!($mask&_MOS_ALLOWRAW)) {
                    if (is_numeric($def)) $result = intval($result);
                }
            }
            if (!get_magic_quotes_gpc()) {
                $result = addslashes( $result );
            }
        }
        return $result;
    } else {
        return $def;
    }
}

require_once ('../includes/database.php');
require_once ('../includes/helpers.php');

$image_path = '../img';

$allowedOptions = array ('login','buscar'.'registrate');

ini_set('session.use_trans_sid', 0);
ini_set('session.use_cookies', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.gc_maxlifetime', 172800);
session_cache_limiter('private,must-revalidate');
session_start();
header("Cache-control: private");

if ($_POST["logout"]) {
    unset($_SESSION[$config_sessionname]);
    header('Location: http://'.$_SERVER["HTTP_HOST"]);
};

$db=new database($config_host,$config_user,$config_password,$config_db,$config_dbprefix);
?>

<?php
//Ã©ste es el que corre el ajax
$funcionesprimarias=array("home");
if(in_array(mosgetparam($_POST,"content","home"),$funcionesprimarias)) {
    $funcion=mosgetparam($_POST,"content","home").mosgetparam($_POST,"orden","1");
    if(function_exists($funcion)) {
        $funcion();
    }
}
?>
<?php
//desde aqui todas las funciones AJAX
//cargar los cuadros
function home1() {
    global $db;
    $eof="\n";
    //calcular numero de paginas
    $cuadros=mosgetparam($_POST,"cuadros","4");
    if($cuadros==4) {
        $sql="select id, fotochica, titulo from mox_chicas where losnuestros=1 and estado=1";
        $limpags=13;
    }
    else {
        $sql="select id, fotochica, titulo from mox_chicas where chicasmoxiedeldia=1 and estado=1";
        $limpags=58;
    }
    $pag=mosgetparam($_POST,"pag","1");
    $superpag=ceil($pag/$limpags);
    $aquien=mosgetparam($_POST,"aquien","chicas_moxie");
    $desde=$pag*$cuadros-$cuadros;
    $limit=$cuadros;
    $db->setQuery($sql);
    $pags=ceil(count($db->loadObjectList())/$cuadros);
    $superpags=ceil($pags/$limpags);
    $pagini=ceil($pag/$limpags);
    $pagini=$superpag*$limpags-$limpags+1;
    //calcular fotos a mostrar
    if($cuadros==4) {
        $sql="select id, fotochica, titulo from mox_chicas where losnuestros=1 and estado=1 order by ordlosnuestros limit ".$desde.",".$limit;
    }
    else {
        $cuadros=8;
        $sql="select id, fotochica, titulo from mox_chicas where chicasmoxiedeldia=1 and estado=1 order by ordchicasmoxiedeldia limit ".$desde.",".$limit;
    }
    $db->setQuery($sql);
    if(count($db->loadObjectList())>0) {
        echo '<div class="contfoto">'.$eof;
        foreach($db->loadObjectList() as $r) {
            echo '  <div class="tumbfoto"><img src="chicas_moxie/'.$r->fotochica.'" onclick="carga_video('.$r->id.')" alt="'.$r->titulo.'" border="0" width="103" height="55" /></div>'.$eof;
        }
        if($pags>1) {
            echo '<div class="navfoto" id="navfoto'.$aquien.'">';
            if($pag==1)
                echo '<div class="vermas" onclick="'.$aquien.'(2,'.$cuadros.')"></div>';
            else {
                if($superpag-1>=1)
                    echo '<a class="numpagfoto" href="javascript:'.$aquien.'super('.($superpag-1).')">&lt;&lt;</a>'.$eof;
                else
                    echo '<a class="numpagfotobajo" href="javascript:'.$aquien.'super(0)">&lt;&lt;</a>'.$eof;

                if($pagini+$limpags-1>$pags)
                    $pagfin=$pags;
                else
                    $pagfin=$pagini-1+$limpags;
                if($pagfin/100>=1) {
                    $cc=" d3";
                }
                for($p=$pagini;$p<=$pagfin-1;$p++) {
                    echo '<a class="numpagfoto'.($pag==$p?'_selected':'').$cc.'" href="javascript:'.$aquien.'('.$p.','.$cuadros.')">'.str_pad($p,2,"0",STR_PAD_LEFT).'</a>'.$eof;
                }
                echo '<a class="numpagfoto'.($pag==$pagfin?'_selected':'').$cc.'" href="javascript:'.$aquien.'('.$pagfin.','.$cuadros.')">'.str_pad($pagfin,2,"0",STR_PAD_LEFT).'</a>'.$eof;
                if($superpag+1<=$superpags) {
                    echo '<a class="numpagfoto" href="javascript:'.$aquien.'super('.($superpag+1).')">&gt;&gt;</a>'.$eof;
                }
                else
                    echo '<a class="numpagfotobajo" href="javascript:'.$aquien.'super(0)">&gt;&gt;</a>'.$eof;
            }
            echo '</div>'.$eof;
        }
        echo '</div>'.$eof;
    }
    else {
        echo '<div class="novideos">No hay videos en &eacute;sta secci&oacute;n.</div>';
    }
}
/**
 * hace la paginacion
 */
function home10() {
    global $db;
    $superpag=mosgetparam($_POST,"superpag","1");
    $opc=mosgetparam($_POST,"opc","los_nuestros");
    //calcular el num de paginas
    if($opc=="los_nuestros") {
        $sql="select id, fotochica, titulo from mox_chicas where chicasmoxiedeldia=1 and estado=1";
        $cuadros=8;
        $limpags=58;
    }
    else {
        $sql="select id, fotochica, titulo from mox_chicas where losnuestros=1 and estado=1";
        $cuadros=4;
        $limpags=13;
    }
    $aquien=$opc;
    $pagini=($superpag-1)*$limpags+1;
    $db->setQuery($sql);
    $pags=ceil(count($db->loadObjectList())/$cuadros);
    $superpags=ceil($pags/$limpags);
    if($pagini+$limpags-1>$pags)
        $pagfin=$pags;
    else
        $pagfin=$pagini-1+$limpags;
    if($superpag-1>=1)
        echo '<a class="numpagfoto" href="javascript:'.$aquien.'super('.($superpag-1).')">&lt;&lt;</a>&nbsp;'.$eof;
    else
        echo '<a class="numpagfotobajo" href="javascript:'.$aquien.'super(0)">&lt;&lt;</a>&nbsp;'.$eof;
    if($pagini+$limpags-1>$pags)
        $pagfin=$pags;
    else
        $pagfin=$pagini-1+$limpags;
    $eof="\n";
    if($pagfin/100>=1) {
        $cc=" d3";
    }
    for($p=$pagini;$p<=$pagfin-1;$p++) {
    //aqui habia una evaluacion para determinar cual era la pagina actual
        echo '<a class="numpagfoto'.$cc.'" href="javascript:'.$aquien.'('.$p.','.$cuadros.')">'.str_pad($p,2,"0",STR_PAD_LEFT).'</a>'.$eof;
    }
    echo '<a class="numpagfoto'.$cc.'" href="javascript:'.$aquien.'('.$pagfin.','.$cuadros.')">'.str_pad($pagfin,2,"0",STR_PAD_LEFT).'</a>'.$eof;

    if($superpag+1<=$superpags) {
        echo '<a class="numpagfoto" href="javascript:'.$aquien.'super('.($superpag+1).')">&gt;&gt;</a>'.$eof;
    }
    else
        echo '<a class="numpagfotobajo" href="javascript:'.$aquien.'super(0)">&gt;&gt;</a>'.$eof;
}
//cargar el video
function home2() {
    global $db;
    $db->setQuery("select codigo from mox_chicas where id=".mosgetparam($_POST,"id","1"));
    if($db->loadResult()) {
        $x=$db->loadResult();
        $x=str_replace("transparent","opaque",$x,$veces);
        if(strripos($x,'wmode')===false) {
            $x=str_replace('<param name="movie"','<param name="wmode" value="opaque"></param><param name="movie"',$x);
            $x=str_replace('></embed',' wmode="opaque" ></embed',$x);
        }
        echo $x;
    }
}
//cargar el titulo del video
function home4() {
    global $db;
    $db->setQuery("select titulo from mox_chicas where id=".mosgetparam($_POST,"id","1"));
    if($db->loadResult()) {
        echo $db->loadResult();
    }
}
//cargar la url del video
function home6() {
    global $db;
    $db->setQuery("select CONCAT('http://".$_SERVER["SERVER_NAME"]."/index.php?video=',md5(titulo)) as url from mox_chicas where id=".mosgetparam($_POST,"id","1"));
    if($db->loadResult()) {
        echo $db->loadResult();
    }
}
//cargar la url del video pa tuiter
function home8() {
    global $db;
    $db->setQuery("select url from mox_chicas where id=".mosgetparam($_POST,"id","1"));
    if($db->loadResult()) {
        echo substr_replace($db->loadResult(),"http://www.tuenti.com/#m=sharevideo&id=",0,31);
    }
}
//cargar la url del video pa facebook
function home9() {
    global $db;
    $db->setQuery("select url from mox_chicas where id=".mosgetparam($_POST,"id","1"));
    if($db->loadResult()) {
        echo "http://www.facebook.com/sharer.php?u=".$db->loadResult();
    }
}
//evaluar los codigos
function home5() {
    global $db;
    $db->setQuery("select count(*) from mox_validos where valor='".$_POST["id"]."'");
    if($db->loadResult()==1) {
        echo "bueno";
        $db->setQuery("insert into mox_codigos(valor, valido, inserted, estado)
            values('".$_POST["id"]."','SÃ­',now(),1)");
        $db->query();
    }
    else {
        echo "malo";
        $db->setQuery("insert into mox_codigos(valor, valido, inserted, estado)
            values('".$_POST["id"]."','No',now(),1)");
        $db->query();
    }
}
//enviar el email de compartir
function home3() {
    $a_email="video@sebuscachicamoxie.com";
    $denom =mosgetparam($_POST,"denom","indefinido");
    $demail=mosgetparam($_POST,"demail","indefinido");
    $panom =mosgetparam($_POST,"panom","indefinido");
    $pamail=mosgetparam($_POST,"pamail","indefinido");
    $video=mosgetparam($_POST,"video","indefinido");
    $asunto="Moxie Videos";
    $eol="\r\n";
    $now = mktime().".".md5(rand(1000,9999));
    $headers = "From:".$a_email.$eol."To:".$pamail.$eol;
    $headers .= 'Return-Path: '.$a_email.'<'.$a_email.'>'.$eol;
    $headers .= "Message-ID: <".$now." TheSystem@".$_SERVER['SERVER_NAME'].">".$eol;
    $headers .= "X-Mailer: PHP v".phpversion().$eol;
    $headers .= "Content-Type: text/html; charset=iso-8859-1".$eol;
    $mensaje="";
    $mensaje.="<table style=\"font-family:Arial, Tahoma; font-size:16px; font-weight:bold;\">";
    $mensaje.='    <tr><td align="left"><img src="http://www.sebuscachicamoxie.com/images/logotipo_moxie-sitio.jpg" /></td></tr>';
    $mensaje.='    <tr><td style="color:#EA5589">
                        <br /><br />Hola '.$panom.',<br /><br /></td></tr>
                        <tr><td>
                        '.$denom.'('.$demail.') desea compartir contigo un v&iacute;deo de la<br />
                        p&aacute;gina web <a style="color:#EA5589; text-decoration:none;" href="http://www.sebuscachicamoxie.com">www.sebuscachicamoxie.com.</a>&nbsp;En ella podr&aacute;s conocer a cientos de<br />
                        Chicas Moxie y podr&aacute;s demostrar que t&uacute; tambi&eacute;n lo eres.<br /><br />
                        Para ver el v&iacute;deo, pincha en el siguente enlace<br />
                        <a style="color:#542989; text-decoration:none;" href="'.htmlentities($video).'">'.$video.',</a>&nbsp;<br />o selecciona, copia y pega en la barra de direcciones de tu navegador <br />
                        de internet.<br /><br />
                        Te esperamos en nuestra web.<br /><br />
                        </td></tr>
                        <tr><td style="color:#EA5589">Moxie Girlz<br />Be True * Be You</td></tr>';
    $mensaje.="</table>";
    $resultado=mail($pamail, $asunto, $mensaje, $headers);
    echo "video".$video."W".$resultado;
}

//enviar el mail de certificado
function home7() {
    $a_email="video@sebuscachicamoxie.com";
    $denom =mosgetparam($_POST,"denom","indefinido");
    $demail=mosgetparam($_POST,"demail","indefinido");
    $panom =mosgetparam($_POST,"panom","indefinido");
    $pamail=mosgetparam($_POST,"pamail","indefinido");
    $direccion=mosgetparam($_POST,"direccion","indefinido");
    $asunto="Aqui tenemos una chica Moxie";
    $eol="\r\n";
    $now = mktime().".".md5(rand(1000,9999));
    $headers = "From:".$a_email.$eol."To:".$pamail.$eol;
    $headers .= 'Return-Path: '.$a_email.'<'.$a_email.'>'.$eol;
    $headers .= "Message-ID: <".$now." TheSystem@".$_SERVER['SERVER_NAME'].">".$eol;
    $headers .= "X-Mailer: PHP v".phpversion().$eol;
    $headers .= "Content-Type: text/html; charset=iso-8859-1".$eol;
    $mensaje="";
    $mensaje.="<table style=\"font-family:Arial, Tahoma; font-size:15px; font-weight:bold;\">";
    $mensaje.='    <tr><td align="left"><img style="color:#EA5589; text-decoration:none;" src="http://www.sebuscachicamoxie.com/images/logotipo_moxie-sitio.jpg" /></td></tr>';
    $mensaje.='    <tr><td style="color:#EA5589">
                        <br />Hola '.$panom.'<br /><br /></td></tr>
                        <tr><td>
                        '.$denom.'('.$demail.') desea compartir contigo su certificado de Chica Moxie, de la p&aacute;gina web <a style="color:#EA5589; text-decoration:none;" href="http://www.sebuscachicamoxie.com">www.sebuscachicamoxie.com</a>. En ella podr&aacute;s conocer a cientos de Chicas Moxie y podr&aacute;s demostrar que t&uacute; tambi&eacute;n lo eres.<br /><br />
                        Para ver el certificado que te envian, pincha en el siguente enlace<br/><a style="color:#542989; text-decoration:none;" href="http://www.sebuscachicamoxie.com/eres-una-chica-moxie.php?certificado='.$direccion.'">'.$direccion.'</a>, o selecciona, copia y pega en la barra de direcciones de tu navegador de internet.<br /><br />
                        Te esperamos en nuestra web.<br /><br />
                        </td></tr>
                        <tr><td style="color:#EA5589">Moxie Girlz<br />Be True * Be You</td></tr>';
    $mensaje.="</table>";
    $resultado=mail($pamail, $asunto, $mensaje, $headers);
    echo "video".$video."W".$resultado;
}
?>
