<?php
/*
=============================================
 Name      : MWS Link Page v1.1
 Author    : Mehmet Hanoğlu ( MaRZoCHi )
 Site      : https://dle.net.tr/
 License   : MIT License
 Date      : 28.09.2018
=============================================
*/

if ( ! defined( 'DATALIFEENGINE' ) ) die("Hacking attempt");

include ENGINE_DIR . "/data/linkpage.conf.php";

if ( $config['allow_alt_url'] ) {
	$link_page_url = $config['http_home_url'] . $lpset['module_link'] . "/" . crc32( $row['id'] ) . "/";
} else {
	$link_page_url = $config['http_home_url'] . "index.php?do=linkpage&nid=" . crc32( $row['id'] );
}
$tpl->set( "{link-page}", $link_page_url );

?>