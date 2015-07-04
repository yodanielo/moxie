<?php 
function esp_char($cadena){
    $traducciones=array(
        "Á"=>"Aacute;",
        "É"=>"Eacute;",
        "Í"=>"Iacute;",
        "Ó"=>"Oacute;",
        "Ú"=>"Uacute;",
        "á"=>"aacute;",
        "é"=>"eacute;",
        "í"=>"iacute;",
        "ó"=>"oacute;",
        "ú"=>"uacute;",
        "Ñ"=>"&Ntilde;",
        "ñ"=>"&ntilde;",
        "¡"=>"&iexcl;",
        "¿"=>"&iquest;",
    );
    strtr($cadena,$traducciones);
}
function contar_palabras($str,$conetiquetas=0){
	if(conetiquetas==1)
		$str=strip_tags($str);
	$reemplazar=array(",",".","-","+","(",")","{","}","_",";",":","  ");
	foreach ($reemplazar as $rr){
		$str=str_replace($rr,"",$str);
	}
	return sizeof(explode(" ", $str));
}

function limitar_palabras($str, $limit = 100, $end_char = '&#8230;')
{

	if (trim($str) == '')
	{
		return $str;
	}

	preg_match('/^\s*+(?:\S++\s*+){1,'.(int) $limit.'}/', $str, $matches);
		
	if (strlen($str) == strlen($matches[0]))
	{
		$end_char = '';
	}
	
	return rtrim($matches[0]).$end_char;
}

function limitar_letras($str, $n = 500, $end_char = '&#8230;')
{
	if (strlen($str) < $n)
	{
		return $str;
	}
	
	$str = preg_replace("/\s+/", ' ', str_replace(array("\r\n", "\r", "\n"), ' ', $str));

	if (strlen($str) <= $n)
	{
		return $str;
	}

	$out = "";
	foreach (explode(' ', trim($str)) as $val)
	{
		$out .= $val.' ';
		
		if (strlen($out) >= $n)
		{
			$out = trim($out);
			return (strlen($out) == strlen($str)) ? $out : $out.$end_char;
		}		
	}
}
?>
