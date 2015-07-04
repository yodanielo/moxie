<?php

if (!defined('_VALID_CHA')) define( '_VALID_CHA', 1 );

if ( !file_exists($dir.'config.php' ) || filesize( $dir.'config.php' ) < 10 ) {
    echo "debe configurar éste sitio web antes de usarlo";
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
$sql="select codigo, id, titulo, descripcion, url, fotochica from mox_chicas where estado=1 and (titulo like '%".mosgetparam($_GET,"query","")."%' or descripcion like '%".mosgetparam($_GET,"query","")."%')";
$db->setBareQuery($sql);
$res=$db->loadObjectList();
if(count($res)>0){
    $ini=$res[0];
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
            } catch(err) {}
        </script>
    </head>
    <body>
        <div id="pagina">
            <div id="logo">
                <script type="text/javascript">runSWF2("flash/logotipo-moxie.swf", 235, 210,"8,0,29,0", "transparent");</script>
            </div>
            <div id="menu">
                <script type="text/javascript">runSWF2("flash/menu-videos-moxie.swf", 745, 122,"8,0,29,0", "transparent");</script>
            </div>
            <div id="blobuscar">
                <div id="buscol1">
                    <div id="vidcontenedor" <?php if(count($res)==0){ echo 'style="background:url(images/nohayvideos.jpg) no-repeat center;"';} ?>>
                        <?php
                        if(count($res)>0){
                            echo $ini->codigo;
                        }
                        ?>
                    </div>
                    <?php if(count($res)>0){ ?>
                    <div id="panelcompartir">
                        <a id="comtwitter" target="_blank" href="http://www.twitter.com/login"></a>
                        <a id="comtuenti" target="_blank" href="http://www.tuenti.com"></a>
                        <div id="comemail" onclick="sacar_comparte()"></div>
                    </div>
                    <?php }?>
                    <a id="busvolver" href="videos-moxie.php"></a>
                </div>
                <div id="buscol2">
                    <?php
                    for($i=0;$i<count($res);$i++){
                        $r=$res[$i];
                        ?>
                    <div class="encontrado" onclick="carga_video(<?php echo $r->id ?>)">
                            <div class="encfoto">
                                <img src="chicas_moxie/<?php echo $r->fotochica ?>" border="0" />
                            </div>
                            <div class="cntencontrado">
                                <div class="titencontrado">
                                    <?php echo limitar_letras($r->titulo,41); ?>
                                </div>
                                <div class="desencontrado">
                                    <?php echo limitar_letras($r->descripcion,41); ?>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
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
                    <!--<input type="button" class="compsubmit" onclick="compartir()" value="Enviar" />-->
                    <div class="compsubmit" onclick="compartir()"></div>
                    <input type="hidden" name="video" value="<?php if(count($res)>0){echo $ini->url;} ?>" />
                </div>
                <div id="cuayoutube"></div>
            </form>
        </div>
    </body>
</html>