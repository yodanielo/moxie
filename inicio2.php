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
$sql="select titulo, codigo, url from mox_chicas where destacado=1";
$db->setQuery($sql);
$res=$db->loadObjectList();
$destacado=null;
if(count($res)>0) {
    $destacado=$res[0];
}
?>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?php echo $config_sitename; ?></title>
        <meta name="Description" content="<?php echo $config_MetaDesc; ?>" />
        <meta name="Keywords" content="<?php echo $config_MetaKeys; ?>" />
        <meta name="author" content="<?php echo $config_author; ?>" />
        <meta name="owner" content="<?php echo $config_sitename; ?>" />
        <meta name="robots" content="index, follow" />
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
                <script type="text/javascript">runSWF2("flash/menu-moxie.swf", 745, 122,"8,0,29,0", "transparent");</script>
            </div>
            <div id="inicol1">
                <div id="iniqueesmoxie"><script type="text/javascript">runSWF2("flash/hl-que-es-moxie.swf", 235, 132,"8,0,29,0", "transparent");</script></div>
                <div id="hlinsertatucod" style="height:125px">
                    <form target="_blank" action="inserta-tu-codigo.php" onsubmit="return validacodigo()" method="POST" name="frminserta" id="frminserta">
                        <input type="text" name="codigo" id="codigo" border="0" maxlength="8" />
                        <input type="image" src="images/btn-codigo-moxie.jpg" id="codsubmit" />
                    </form>
                </div>
                <div id="hlsiguenosen" style="height:108px">
                    <div id="siguenos_tuenti" onclick="window.location.href='http://twitter.com/chicamoxie';"></div>
                    <div id="siguenos_twitter" onclick="window.location.href='http://www.tuenti.com/#m=Profile&func=index&user_id=66081052'"></div>
                </div>
            </div>
            <div id="iniseparador"></div>
            <div id="videoprincipal1">
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
                    <a id="comtwitter" target="_blank" href="http://twitter.com/home?status=<?php echo rawurlencode("Echa un vistazo a éste video de una Chica Moxie: ").$destacado->url ?>"></a>
                    <a id="comtuenti" target="_blank" href="http://www.tuenti.com"></a>
                    <div id="comemail" onclick="sacar_comparte()"></div>
                </div>
                <?php
                $db->setQuery("select * from mox_chicas where chicasmoxie=1 order by ordchicasmoxie asc");
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
            <div id="inicol2">
                <div style="height:211px; margin-top:-10px; position:relative; z-index:5;"><script type="text/javascript">runSWF2("flash/hl-noticias-descargas.swf", 205, 211,"8,0,29,0", "transparent");</script></div>
                <div style="height:224px; "><script type="text/javascript">runSWF2("flash/hl-eres-una-chica-moxie.swf", 205, 224,"8,0,29,0", "transparent");</script></div>
            </div>
        </div>
        <!--Fin de la pagina-->
        <div class="jqmWindow" id="dlgcomparte">
            <form action="#" method="post" name="frmcomparte" id="frmcomparte" >
                <div id="btncerrar" onclick="$('#dlgcomparte').jqmHide()" ></div>
                <div id="comcol1">
                    <input type="text" name="txtdenombre" class="comptexto" id="txtdenombre" />
                    <input type="text" name="txtdemail" class="comptexto" id="txtdemail" />
                    <input type="text" name="txtparnombre" class="comptexto" id="txtparnombre" />
                    <input type="text" name="txtparcorreo" class="comptexto" id="txtparcorreo" />
                    <!--<input type="button" class="compsubmit" onclick="compartir()" value="Enviar" />-->
                    <div class="compsubmit" onclick="compartir()"></div>
                    <input type="hidden" name="video" value="<?php
                    echo $destacado->url;
                           ?>" />
                </div>
                <div id="cuayoutube"></div>
            </form>
        </div>
        <div class="jqmWindow" id="dlgparticipa">
            <a class="btncerrar" onclick="$('#dlgparticipa').jqmHide()" >&nbsp;</a>
            <div id="contenido">
                M&aacute;ndanos un email con tu video a <a style="color: #e2007a;" href="mailto:video@sebuscachicamoxie.com">video@sebuscachicamoxie.com</a>
                Pero recuerda, para participar no olvides enviarnos un fax al n&uacute;mero 91 333 3333 con una autorizaci&oacute;n de tus padres y una copia de su DNI. As&iacute; entrar&aacute;s en "Se Busca Una Chica Moxie".
                <br /><br />
                Los datos t&eacute;cnicos del video son:
                <br /><br />
                <strong>PARA TODOS:</strong>
                <br /><br />
                Formato de video: se admiten preferiblemente: avi, mov, mpeg, mp4, 3gp, flv, divx, mkv, vob, xvid, mov, rm, qt, wmv...
                Se recomienda que los videos no excedan de 5 MB de peso*
                Nuestro email es: <a style="color: #e2007a;" href="mailto:video@sebuscachicamoxie.com">video@sebuscachicamoxie.com</a>
                <br /><br />
                <strong>USUARIOS NOVATOS</strong>
                <br /><br />
                <strong>VIDEO:</strong><br />
                Resoluci&oacute;n recomendada: 1280 x 720 (16 x 9 HD) y 640 x 480 (4:3 SD)
                La primera de ellas permitir&aacute; ver el video en formato panor&aacute;mico y alta calidad. La segunda est&aacute; considerada est&aacute;ndar y es m&aacute;s frecuente obtener este tipo de resoluciones cuando se usan las habituales video-c&aacute;maras, c&aacute;maras digitales o dispositivos m&oacute;viles que captan video.
                Para reducir el tama&ntilde;o del video se recomienda utilizar la p&aacute;gina web gratuita Mediaconverter (www.mediaconverter.org). En ella podr&aacute;s optimizar tu video para formato web.
                <br /><br />
                <strong>USUARIOS EXPERTOS:</strong>
                <br /><br />
                <strong>VIDEO:</strong> <br />
                Resoluci&oacute;n recomendada: 1280 x 720 (16 x 9 HD) y 640 x 480 (4:3 SD)
                No hay ninguna especificaci&oacute;n obligatoria de resoluci&oacute;n m&iacute;nima, pero por lo general se recomienda utilizar una resoluci&oacute;n alta (a ser posible, HD). Para contenido de v&iacute;deo m&aacute;s antiguo, se debe utilizar inevitablemente una resoluci&oacute;n inferior.
                C&oacute;dec: H.264, MPEG-2 o MPEG-4 preferiblemente
                <br /><br />
                <strong>AUDIO:</strong> <br />
                Codec: MP3 o AAC preferiblemente
                Frecuencia de muestreo: 44,1 kHz
                Canales: 2 (est&eacute;reo)
            </div>

        </div>
        <div class="jqmWindow" id="dlgaviso">

        </div>
    </body>
</html>