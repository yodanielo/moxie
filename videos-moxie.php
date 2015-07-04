<?php

if (!defined('_VALID_CHA')) define( '_VALID_CHA', 1 );

if ( !file_exists($dir.'config.php' ) || filesize( $dir.'config.php' ) < 10 ) {
    echo "debe configurar &eacute;ste sitio web antes de usarlo";
    exit();
}
else
    require_once (dirname(__FILE__).'/config.php');

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

require_once (dirname(__FILE__).'/includes/database.php');
require_once (dirname(__FILE__).'/includes/helpers.php');

$image_path = dirname(__FILE__).'/img';

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
//para sacar los datos del destacado
$sql="select titulo, codigo, CONCAT('"."http://".$_SERVER["SERVER_NAME"]."/edmultimedia/ddesarrollo/moxie/inicio.php?video=',md5(titulo)) as url, url as url2 from mox_chicas where destacado=1 and estado=1";
$db->setQuery($sql);
$res=$db->loadObjectList();
$destacado=null;
if(count($res)>0){
    $destacado=$res[0];
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title><?php echo $config_sitename; ?></title>
        <meta name="Description" content="<?php echo $config_MetaDesc; ?>" />
        <meta name="Keywords" content="<?php echo $config_MetaKeys; ?>" />
        <meta name="author" content="<?php echo $config_author; ?>" />
        <meta name="owner" content="<?php echo $config_sitename; ?>" />
        <meta name="robots" content="index, follow" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" type="text/css" href="css/nav.css" />
        <!--[if IE ]>
	<link rel="stylesheet" type="text/css" href="css/ie.css" />
        <![endif]-->
        <!--[if IE 7]>
	<link rel="stylesheet" type="text/css" href="css/ie7.css" />
        <![endif]-->
        <!--[if IE 6]>
	<link rel="stylesheet" type="text/css" href="css/ie6.css" />
        <![endif]-->
        <script type="text/javascript" src="scripts/jquery-1.3.2.js"></script>
        <script type="text/javascript" src="scripts/jqModal.js"></script>
        <script type="text/javascript" src="scripts/flashobject.js"></script>
        <script type="text/javascript" src="scripts/run.js"></script>
        <script type="text/javascript" src="scripts/flexcroll.js"></script>
        <script type="text/javascript" src="scripts/generales.js"></script>
        <script type="text/javascript">
            var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
            document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
        </script>
        <script type="text/javascript">
            try {
                var pageTracker = _gat._getTracker("UA-9410578-2");
                pageTracker._trackPageview();
            } catch(err) {}</script>
    </head>
    <body>
        <div id="pagina">
            <div id="logo">
                <script type="text/javascript">runSWF2("flash/logotipo-moxie-interior.swf", 235, 210,"8,0,29,0", "transparent");</script>
            </div>
            <div id="menu">
                <script type="text/javascript">runSWF2("flash/menu-videos-moxie.swf", 745, 122,"8,0,29,0", "transparent");</script>
            </div>
            <div id="cuadrovideos">
                <div id="linkparticipa" onclick="cargaparticipa()"></div>
            </div>
            <div id="losnuestros">
                <div id="titlosnuestros"></div>
                <div id="detlosnuestros"></div>
            </div>
            <div id="videoprincipal">
                <div id="vidtitulo">
                    <?php
                    echo limitar_letras($destacado->titulo,20);
                    ?>
                </div>
                <div id="vidcontenedor">
                    <?php
                    echo $destacado->codigo;
                    ?>
                </div>
                <div id="panelcompartir">
                    <a id="comtwitter" target="_blank" href="http://twitter.com/home?status=<?php echo rawurlencode("Echa un vistazo a &eacute;ste video de una Chica Moxie: ").$destacado->url ?>"></a>
                    <a id="comtuenti" target="_blank" href="<?php echo substr_replace($destacado->url2,"http://www.tuenti.com/#m=sharevideo&id=",0,31); ?>"></a>
                    <a id="comfacebook" target="_blank" href="<?php echo "http://www.facebook.com/sharer.php?u=".$destacado->url2; ?>"></a>
                    <div id="comemail" onclick="sacar_comparte()"></div>
                </div>
                <?php
                $db->setQuery("select * from mox_chicas where chicasmoxie=1 and estado=1 order by ordchicasmoxie asc");
                $res=$db->loadObjectList();
                ?>
                <div id="titcarousel"></div>
                <div id="panelcarousel">
                    <div id="flecizq"></div>
                    <div id="contcarousel">
                        <ul id="panelcarousel_c">
                            <?php
                            for($i=0;$i<count($res);$i++) {
                                echo '<li><img alt="'.$res[$i]->titulo.'" onclick="carga_video('.$res[$i]->id.')" src="chicas_moxie/'.$res[$i]->fotochica.'" width="105" height="57" /></li>'."\n               ";
                            }
                            ?>
                        </ul>
                    </div>
                    <div id="flecder"></div>
                </div>
                <div style="height:70px;" id="flparticipa">
                    <script type="text/javascript">runSWF2("http://www.sebuscachicamoxie.com/demo/flash/hl-url-participa.swf", 368, 52,"8,0,29,0", "transparent");</script>
                    <div id="btnparticipa" onclick="cargaparticipa()">&nbsp;</div>
                </div>
            </div>
            <div id="buscador">
                <form name="frmbuscador" id="frmbuscador" method="get" action="busca-el-tuyo.php">
                    <input type="text" name="query" value="" id="query" />
                    <input type="image" id="btnquery" src="images/btn-buscar-moxie.png" />
                </form>
            </div>
            <div id="titchicasmoxie"></div>
            <div id="videosninas" class="fotosninas" >

            </div>
            <div id="legales">
                <a href="contacto.php">Contacto::</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a href="aviso-legal.php">Aviso legal::</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a href="mapa-web.php">Mapa Web::</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&reg;
                <a>Copyright 2009 Moxie Girlz ::</a><br />
                TM & © MGA Entertainment, Inc. All rights reserved.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a target="_blank" href="http://www.moxiegirlz.com">www.moxiegirlz.com</a>
            </div>

        </div>
        <div class="jqmWindow" id="dlgcomparte">
            <form action="#" method="post" name="frmcomparte" id="frmcomparte" >
                <div id="btncerrar" onclick="$('#dlgcomparte').jqmHide()" ></div>
                <div id="comcol1">
                    <input type="text" name="txtdenombre" class="comptexto" id="txtdenombre" />
                    <input type="text" name="txtdemail" class="comptexto" id="txtdemail" />
                    <input type="text" name="txtparnombre" class="comptexto" id="txtparnombre" />
                    <input type="text" name="txtparcorreo" class="comptexto" id="txtparcorreo" />
                    <div class="compsubmit" onclick="compartir()"></div>
                    <input type="hidden" name="video" value="<?php
                    echo $destacado->url;
                    ?>" />
                </div>
                <div id="cuayoutube"></div>
            </form>
        </div>
        <div class="jqmWindow" id="dlgbuscar">
            
        </div>
        <div class="jqmWindow" id="dlgparticipa">
            <div class="btncerrar" onclick="$('#dlgparticipa').jqmHide()" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
            <div id="contenido">
                &iquest;T&Uacute; TAMBI&Eacute;N ERES MOXIE?, &iquest;QUIERES DEMOSTRARLO?, &iquest;TIENES ENTRE 6 Y 13 A&Ntilde;OS? &iexcl;PARTICIPA!
                <br />
                Para enviar tu v&iacute;deo s&oacute;lo tienes que seguir los siguientes pasos:
                <br/>
                1.  Solicita permiso a tus padres para participar.<br />
                2.  Graba un v&iacute;deo en el que aparezcas t&uacute; contestando a las siguientes preguntas (Si tienes dudas sobre c&oacute;mo hacerlo, puedes tomar como ejemplo cualquiera de los v&iacute;deos que aparecen en nuestra web):<br />
                &nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size:20px; font-weight:bold;">&middot;</span>&iquest;C&oacute;mo te llamas?, &iquest;Cu&aacute;ntos a&ntilde;os tienes?<br />
                &nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size:20px; font-weight:bold;">&middot;</span>&iquest;Qu&eacute; es lo que m&aacute;s te gusta hacer? &iquest;Por qu&eacute;?<br />
                &nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size:20px; font-weight:bold;">&middot;</span>&iquest;Qu&eacute; te gustar&iacute;a hacer de mayor? &iquest;Por qu&eacute;?<br />
                &nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size:20px; font-weight:bold;">&middot;</span>&iquest;Qu&eacute; es lo &uacute;ltimo que has compartido con una amiga?<br />
                &nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size:20px; font-weight:bold;">&middot;</span>&iquest;Cu&aacute;l es tu animal favorito? &iquest;Por qu&eacute;?<br />
                &nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size:20px; font-weight:bold;">&middot;</span>&iquest;Qu&eacute; instrumento te gustar&iacute;a tocar? &iquest;Por qu&eacute;?<br />
                &nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size:20px; font-weight:bold;">&middot;</span>&iquest;C&oacute;mo crees que debe ser una chica Moxie?<br />
                3.  M&aacute;ndanos un email con tu video, a la siguiente direcci&oacute;n de correo electr&oacute;nico: video@sebuscachicamoxie.com<br />
                4.  Adem&aacute;s, para participar debes enviarnos por fax, al n&uacute;mero 91 376 46 13, una autorizaci&oacute;n firmada por tus padres, y una copia de su DNI.<br />
                5.  &iexcl;Estate atenta a nuestra web! Porque si todos los datos son correctos, es posible que pronto puedas ver tu v&iacute;deo en www.sebuscachicamoxie.com y puedas compartirlo con tus familiares y amigas.
                <br />
                <br />
                REQUISITOS
                <br />
                <ul>
                    <li>Los v&iacute;deos tendr&aacute;n una duraci&oacute;n m&aacute;xima de 1 minuto, y un tama&ntilde;o nunca superior a 10 MB.</li>
                    <li>No se tomar&aacute;n como v&aacute;lidos los v&iacute;deos en los que aparezca m&aacute;s de una persona.</li>
                    <li>Se admiten los siguientes formatos de video: avi, mov, mpeg, mp4, 3gp, flv, divx,  vob, o wmv.</li>
                </ul>
                <br />
                USUARIOS NOVATOS
                <br />
                VIDEO:
                <br />
                Resoluci&oacute;n recomendada: 1280 x 720 (16 x 9 HD) y 640 x 480 (4:3 SD) La primera de ellas permitir&aacute; ver el video en formato panor&aacute;mico y alta calidad. La segunda est&aacute; considerada est&aacute;ndar y es m&aacute;s frecuente obtener este tipo de resoluciones cuando se usan las habituales video-c&aacute;maras, c&aacute;maras digitales o dispositivos m&oacute;viles que captan video. Para reducir el tama&ntilde;o del video se recomienda utilizar la p&aacute;gina web gratuita Mediaconverter (www.mediaconverter.org). En ella podr&aacute;s optimizar tu video para formato web.
                <br />
                <br />
                USUARIOS EXPERTOS
                <br />
                VIDEO:
                <br />
                Resoluci&oacute;n recomendada: 1280 x 720 (16 x 9 HD) y 640 x 480 (4:3 SD) No hay ninguna especificaci&oacute;n obligatoria de resoluci&oacute;n m&iacute;nima, pero por lo general se recomienda utilizar una resoluci&oacute;n alta (a ser posible, HD). Para contenido de v&iacute;deo m&aacute;s antiguo, se debe utilizar inevitablemente una resoluci&oacute;n inferior. C&oacute;dec: H.264, MPEG-2 o MPEG-4 preferiblemente
                <br />
                <br />
                AUDIO:
                <br />
                Codec: MP3 o AAC preferiblemente Frecuencia de muestreo: 44,1 kHz Canales: 2 (est&eacute;reo)
                <br />
                <a id="linkpdf" target="_blank" href="descargas/Autorizacion_videos.pdf">Descargar PDF e imprimir: Autorizaci&oacute;n para que firmen los padres.</a>
            </div>
        </div>
    </body>
</html>
