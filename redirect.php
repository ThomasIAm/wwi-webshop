<?php
// functie om naar een andere pagina door te verwijzen
function redirect($url) {
	if (!headers_sent()) {
		// als de headers nog niet zijn verzonden kan een normale header redirect gebruikt worden
		header('Location: '.$url);
		exit;
	} else {
		// als de headers al wel zijn verzonden wordt een stukje js gebruikt om te redirecten
		echo '<script type="text/javascript">';
		echo 'window.location.href="'.$url.'";';
		echo '</script>';
		echo '<noscript>';
		echo '<meta http-equiv="refresh" content="0;url='.$url.'" />';
		echo '</noscript>';
		exit;
	}
}