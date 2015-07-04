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
$sql="select count(*) from mox_validos where valor='".htmlentities($_POST["codigo"])."'";
$db->setQuery($sql);
if($db->loadResult()!=1) {
    header("location:inicio.php");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>Moxie Holding</title>
        <META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
        <META HTTP-EQUIV="Cache-Control" CONTENT="no-cache">
        <script src="js/flashobject.js"></script>
        <script src="js/run.js" type="text/javascript"></script>

        <style type="text/css">
            <!--
            body {
                margin-left: 0px;
                margin-top: 0px;
                margin-right: 0px;
                margin-bottom: 0px;
            }
            #pag_center {
                position:absolute;
                width:980px;
                left:50%;
                margin-left:-490px;
                float:left;
            }
            #codtxtley{
                position:absolute;
                width:900px;
                left:50%;
                margin-left:-465px;
                margin-top:580px;
                padding:15px;
                font-family: Arial, sans-serif, Tahoma, Verdana;
                font-size:11px;
                text-align:justify;
            }
            -->
        </style>
        <link rel="stylesheet" type="text/css" href="css2/nav.css" />
        <!--[if IE ]>
        <link rel="stylesheet" type="text/css" href="css2/ie.css" />
        <![endif]-->
        <!--[if gt IE 6]>
        <link rel="stylesheet" type="text/css" href="css2/ie7.css" />
        <![endif]-->
        <!--[if gte IE 8]>
        <link rel="stylesheet" type="text/css" href="css2/ie8.css" />
        <![endif]-->
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
        <img src="images/cupon-print-bg-.png" id="imgcenter" border="0" />
        <div id="pag_center">
            <div id="codtxtcodigo">
                <?php echo htmlentities($_POST["codigo"]) ?>
            </div>
            <div id="codtxtdescarga">
                <strong>Descarga e imprime &eacute;ste cup&oacute;n</strong><br />
                Rell&eacute;nalo con tus datos personales, y env&iacute;alo antes del 6 de Enero de 2010, al apdo. de correos 74066 de Madrid, junto con el ticket de compra de una Moxie Girlz, y su c&oacute;digo de barras. Te enviaremos, totalmente gratis, una nueva mu&ntilde;eca Moxie Girlz.</div>

            <div id="codtxtabajo">
                V&aacute;lido para cupones enviados antes del 6 de Enero de 2010, que adjunten<br />
                un ticket de compra de una Moxie Girlz anterior al 30  de Noviembre de 2009
            </div>
            <div id="codimprimir" onclick="window.print()"><img src="images/cupon-print-btn-imprimir.jpg" border="0" /></div>
        </div>
        <div id="codtxtley">
            En cumplimiento de la Ley Org&aacute;nica 15/1999, de 13 de diciembre, de Protecci&oacute;n de Datos de Car&aacute;cter Personal, le informamos que sus datos de car&aacute;cter personal facilitados ser&aacute;n tratados  e incorporados en los ficheros de MGA Entertainment Iberia, S.L. y Zapf Creation (Espa&ntilde;a), S.L. con el fin gestionar la actual promoci&oacute;n e informar de futuras acciones promocionales, as&iacute; como para enviar informaci&oacute;n relativa a los productos de MGA y Zaft Creation. En este sentido, consiente de forma expresa a que sus datos sean tratados por MGA para dar cumplimiento a la finalidad indicada anteriormente. Usted podr&aacute; ejecutar los derechos de acceso, rectificaci&oacute;n y oposici&oacute;n en las oficinas de MGA en Avenida de Burgos, 114-28050 Madrid.<br/><br/>
            Si eres menor de edad, pide a tu padre, madre o tutor que lo rellenen y lo envíen por ti.
        </div>
    </body>
</html>