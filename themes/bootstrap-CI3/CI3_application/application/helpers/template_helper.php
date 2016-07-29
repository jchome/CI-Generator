<?php
/**
 * Template de page avec les inclusion de JS et CSS
 */

if (!function_exists('htmlHeader')) {
	function htmlHeader($title){
		$htmlCode = metaTags() . titleTag($title) . siteCSS() . customCSS() . '

<script type="text/javascript">
function base_url(){return "'. base_url() .'";};
</script>
';
		return $htmlCode;
	}
}

if (!function_exists('bodyFooter')) {
	function bodyFooter(){
		$htmlCode = jquery();
		return $htmlCode;
	}
}

if (!function_exists('jquery')) {
	function jquery(){
		$htmlCode = '
<!-- jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
				
<!-- Latest compiled and minified JavaScript -->
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

<script src="'.base_url().'www/js/bootstrap-typeahead.js"></script>
<!-- GoogleMaps API -- >
<script src="https://maps.googleapis.com/maps/api/js?libraries=drawing,places"></script-->
				';
		return $htmlCode;
	}
}


if (!function_exists('metaTags')) {
	function metaTags(){
		$htmlCode = '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">';
		return $htmlCode;
	}
}

if (!function_exists('titleTag')) {
	function titleTag($title){
		$htmlCode = '<title>'. $title .'</title>';
		return $htmlCode;
	}
}


if (!function_exists('siteCSS')) {
	function siteCSS(){
		$htmlCode = '<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">

<!-- FileUpload plugin -->
<link href="http://hayageek.github.io/jQuery-Upload-File/4.0.8/uploadfile.css" rel="stylesheet">

<!-- Optional theme -->
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
';
		return $htmlCode;
	}
}


if (!function_exists('customCSS')) {
	function customCSS(){
		$htmlCode = '<!-- Custom CSS -->
<link rel="stylesheet" href="'.base_url().'www/css/custom.css" />
<link rel="stylesheet" href="'.base_url().'www/css/main.css" />';
		return $htmlCode;
	}
}



?>