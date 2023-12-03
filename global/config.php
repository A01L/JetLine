<?php 
//Block this site in others iframes
header('X-Frame-Options: SAMEORIGIN');

//Off error parse system | DEBUG mode
// error_reporting(E_ERROR | E_PARSE );
define('DEBUG', 1);

//Sessions&Cookie life 
ini_set('session.gc_maxlifetime', 172800);
ini_set('session.cookie_lifetime', 172800);

//Main config for Jetl
$ectr_protcol="http";
$ectr_host="localhost";
$ectr_db="mt";
$ectr_login="root";
$ectr_password="root";
$ectr_charset = "utf8";

//Set db config
if ($ectr_host == "") {
    $ectr_connect = null;
}
else {
    $ectr_connect = mysqli_connect($ectr_host, $ectr_login, $ectr_password, $ectr_db);
    $GLOBALS['db'] = $ectr_connect;
        mysqli_set_charset($ectr_connect, $ectr_charset);
        if (!$ectr_connect) {
            die('Error connect to Data base!');
        }
}

//Project info
$ectr_appname="";
$ectr_author="";
$ectr_site="";
$ectr_email="";
$routes_ectr = [];
$ectr_local_storage = [];
$ectr_ns = $_SERVER['SERVER_NAME'];
$routes_ectr_404 = "view/error_pages/404/404.html";
 ?>
