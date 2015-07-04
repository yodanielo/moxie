<?php
function clipImage($file, $dest, $width, $height) {
	$imSrc  = imagecreatefromjpeg($file);
	$w      = imagesx($imSrc);
	$h      = imagesy($imSrc);
	if($width/$height>$w/$h) {
		$nh = ($h/$w)*$width;
		$nw = $width;
	} else {
		$nw = ($w/$h)*$height;
		$nh = $height;
	}
	$dx = ($width/2)-($nw/2);
	$dy = ($height/2)-($nh/2);
	$imTrg  = imagecreatetruecolor($width, $height);
	imagecopyresized($imTrg, $imSrc, $dx, $dy, 0, 0, $nw, $nh, $w, $h);
	imagedestroy($imSrc);
	imagejpeg($imTrg, $dest, 95);
	imagedestroy($imTrg);
}
function ajuste_img($prefijo,$dir_thumb,$dir_img,$subir,$width,$height)
{
	$imSrc  = imagecreatefromjpeg($dir_img.$subir );
	$w      = imagesx($imSrc);
	$h      = imagesy($imSrc);
	if($width/$height>$w/$h) {
	$nh = ($h/$w)*$width;
	$nw = $width;
	} else {
	$nw = ($w/$h)*$height;
	$nh = $height;
	}
	$dx = ($width/2)-($nw/2);
	$dy = ($height/2)-($nh/2);
	$imTrg  = imageCreateTrueColor($width, $height);
	imagecopyresized($imTrg, $imSrc, $dx, $dy, 0, 0, $nw, $nh, $w, $h);
	imagedestroy($imSrc);
	imagejpeg($imTrg, $dir_thumb.$prefijo.$subir , 100);
	imagedestroy($imTrg);
	return $prefijo.$subir;
}

function ajuste_imgmax($prefijo,$dir_thumb,$dir_img,$subir,$width,$height)
{
	$imSrc  = imagecreatefromjpeg($dir_img.$subir );
	$w      = imagesx($imSrc);
	$h      = imagesy($imSrc);
	if($w>$width || $h>$height) {
		if($width/$height>$w/$h) {
		$nh = ($h/$w)*$width;
		$nw = $width;
		} else {
		$nw = ($w/$h)*$height;
		$nh = $height;
		}
		$dx = ($width/2)-($nw/2);
		$dy = ($height/2)-($nh/2);
		$imTrg  = imageCreateTrueColor($width, $height);
		imagecopyresized($imTrg, $imSrc, $dx, $dy, 0, 0, $nw, $nh, $w, $h);
		imagedestroy($imSrc);
		imagejpeg($imTrg, $dir_thumb.$prefijo.$subir , 100);
		imagedestroy($imTrg);
		return $prefijo.$subir;
	}
	else {
	}
}
?>