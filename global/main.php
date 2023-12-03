<?php
/* 
	-Jet Line (v7)-
	Author: Just Adil
	GitHub: https://GitHub.com/A01L
	Version: Jetline 0.7v
 */

//Set all config for gl-obal
require_once "config.php";
require_once 'modules/MobileDetect/Mobile_Detect.php';
require_once "modules/QRLib/qrlib.php";

#--------------------------------------------DEFAULT FUNCTIONS-------------------------------------------

//Get and saver file
function get_file($path, $name, $newn = "null")
	{
		if (!@copy($_FILES["$name"]['tmp_name'], "containers/".$path.$_FILES["$name"]['name'])){
			return 1;
			}
		else {
			$fn = $_FILES["$name"]['name'];
			$type = format($fn);
			if ($newn != "null") {
				rename("containers/$path$fn", "containers/$path$newn.$type");
				return "$newn.$type";
			}
			else{
				$fnn=str_replace( " " , "_" , $_FILES["$name"]['name']);
				rename("containers/$path$fn", "containers/$path$fnn");
				return "$fnn";
			}
		}
	}


//Get foramt from file
function format($file)
	{
		 $temp= explode('.',$file);
		 $extension = end($temp);
		 return $extension;
	}

# ---------------------------------------------CLASS--------------------------------------------------#

//Other tools
class GEN {

	//Qr Generate example: qr('https://jcodes.space', 'my/adil.png')
	public static function qr($data, $path){
		$qr = new QR_BarCode();
        $qr->info($data);
        $qr->qrCode(500, $_SERVER["DOCUMENT_ROOT"]."/containers".$path);
	}

	//Generate random symbols
	public static function str($length = 6, $mod = '*'){	
		if($mod == 'A'){
			$arr = array(
				'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 
				'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
			);
		}
		elseif($mod == 'a'){
			$arr = array(
				'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 
				'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z',
			);
		}
		elseif($mod == '*'){
			$arr = array(
				'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 
				'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
				'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 
				'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z',
			);
		}
		else{ return 'ERROR MOD'; }
	 
		$res = '';
		for ($i = 0; $i < $length; $i++) {
			$res .= $arr[random_int(0, count($arr) - 1)];
		}
		return $res;
	}
	
	//Generate random numbers
	public static function int($length = 6){
		$arr = array(
			'1', '2', '3', '4', '5', '6', '7', '8', '9', '0'
		);
	 
		$res = '';
		for ($i = 0; $i < $length; $i++) {
			$res .= $arr[random_int(0, count($arr) - 1)];
		}
		return $res;
	}

	//Generate random (numbers and symbols)
	public static function mix($length = 6){
		$arr = array(
			'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 
			'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z',
			'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 
			'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 
			'1', '2', '3', '4', '5', '6', '7', '8', '9', '0'
		);
	 
		$res = '';
		for ($i = 0; $i < $length; $i++) {
			$res .= $arr[random_int(0, count($arr) - 1)];
		}
		return $res;
	}

	//Generate link for whatsapp
	public static function wame($number, $msg = " "){
		$data=urlencode($msg);
		$link="https://api.whatsapp.com/send?phone=$number&text=$data";
		return $link;
	}

}

// data base control
class DBC {

	//Count rows
	public static function count($table, $key){
		$ma = $key;
		$query = "SELECT * FROM `".$table."` WHERE ";
		$value = "VALUES (";
		foreach(array_keys($ma) as $key){
			$query = $query."`".$key."` = '".$ma["$key"]."' AND ";
		}
		$query = mb_eregi_replace("(.*)[^.]{4}$", '\\1', $query);
		$sql = $query;
		$conn = $GLOBALS['db'];

		if($result = $conn->query($sql)){
			$rowsCount = $result->num_rows; // ID - constant
			return $rowsCount;
			//return $rowsCount;
			$result->free();
		}

	}

	//Add data to table
	public static function insert($table, $values){
		$ma = $values;
		$query = "INSERT INTO `".$table."`(";
		$value = "VALUES (";
		foreach(array_keys($ma) as $key){
			$query = $query."`".$key."`, ";
		}
		$query = mb_eregi_replace("(.*)[^.]{2}$", '\\1', $query);
		$query = $query.")";
		foreach(array_keys($ma) as $key){
			$value = $value."'".$ma["$key"]."', ";
		}
		$value = mb_eregi_replace("(.*)[^.]{2}$", '\\1', $value);
		$value = $value.")";
		$full = $query." ".$value;
		mysqli_query($GLOBALS['db'], $full);
		return $full;
	}

	//Update data in table
	public static function update($table, $data, $id){
		$query = "UPDATE `".$table."` SET ";
		foreach(array_keys($data) as $key){
			$query = $query."`".$key."` = '".$data["$key"]."', ";
		}
		$query = mb_eregi_replace("(.*)[^.]{2}$", '\\1', $query);
		$query = $query." WHERE `id` = ".$id;
		mysqli_query($GLOBALS['db'], $query);
		return $query;
	}

	//Get data from table
	public static function select($table, $index, $index2, $data='a'){
			
		if($data != 'a'){
			$db = $GLOBALS['db'];
			$querry = mysqli_query($db, "SELECT * FROM `$table` WHERE `$index` = '$index2'");
			$datas = mysqli_fetch_assoc($querry);
			return $datas["$data"];
		}
		else{
			$db = $GLOBALS['db'];
			$table = $this->table;
			$querry = mysqli_query($db, "SELECT * FROM `$table` WHERE `$index` = '$index2'");
			$datas = mysqli_fetch_assoc($querry);
			return $datas;
		}
    }

	//Delete data
	public static function delete($table, $index, $index2){
		$db = $GLOBALS['db'];
    	mysqli_query($db, "DELETE FROM `$table` WHERE `$index` = $index2");
    }

}

//Text control
class STR {

	//cuting text
	public static function cut($start, $textf, $end){
    $t1 = mb_eregi_replace("(.*)[^.]{".$end."}$", '\\1', $textf);
    $t2 = mb_eregi_replace("^.{".$start."}(.*)$", '\\1', $t1);
    $textf = $t2;
    return $textf;
	}

	//get format (value after dote)
	public static function format($file){
		 $temp= explode('.',$file);
		 $extension = end($temp);
		 return $extension;
	}

}

//Data control
class DTC{

	//Storage control
	public static function storage($path_storage, $name_form){

		date_default_timezone_set('UTC');
		$dir = date("Ym");
		$gen1 = date("dHis"); 
		$gen2 = GEN::int(3);
		$fname = "$gen1$gen2";

		if (is_dir("$path_storage/$dir")) {
			get_file("$path_storage/$dir/", $name_form, $fname);
		} 
		else {
			mkdir("containers/$path_storage/$dir", 0777, true);
			get_file("$path_storage/$dir/", $name_form, $fname);
		}

		$ex = format($_FILES["$name_form"]['name']);
		$call_back = array(
			"name" => "$fname.$ex",
			"path" => "$dir/",
			"full" => "$dir/$fname.$ex"
		);
		return $call_back;
	}

	//Getting file 
	public static function save($path, $name, $newn = "null"){

		if (!@copy($_FILES["$name"]['tmp_name'], "containers/".$path.$_FILES["$name"]['name'])){
			return 'error';
			}
		else {
			$fn = $_FILES["$name"]['name'];
			$type = format($fn);
			if ($newn != "null") {
				rename("containers/$path$fn", "containers/$path$newn.$type");
				return "$newn.$type";
			}
			else{
				$fnn=str_replace( " " , "_" , $_FILES["$name"]['name']);
				rename("containers/$path$fn", "containers/$path$fnn");
				return "$fnn";
			}
		}
	}

	//Send [GET] data to link
	public static function oget($link, $data){
			$ch = curl_init("$link?" . http_build_query($data));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_HEADER, false);
			$return = curl_exec($ch);
			curl_close($ch);
			return $return;
		
	}

	//Send [POST] data to link
	public static function opost($link, $data){
			$ch = curl_init("$link");
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data, '', '&'));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_HEADER, false);
			$re = curl_exec($ch);
			curl_close($ch);	
			return $re;
		
	}

	//Get all data in global param GET, POST
	public static function dump($type){
		if ($type == "post" OR $type == "p") {
			$a = array();
			if (isset($_POST)){
			    foreach ($_POST as $key=>$value){
			        $a[$key]=$value;
			    }
			}
			print_r($a);
		}
		elseif ($type == "get" OR $type == "g") {
			$a = array();
			if (isset($_GET)){
			    foreach ($_GET as $key=>$value){
			        $a[$key]=$value;
			    }
			}
			print_r($a);
		}
		else{
			echo "Error type!";
		}
	}

}

//Router controller
class Router {
	//Routing for getting content (adding new url)
	public static function set($link, $path){
		global $routes_ectr;
		$routes_ectr["$link"] = "$path";
	}

	//Router activator for all set's
	public static function on(){
		global $routes_ectr;
		global $routes_ectr_404;

		$url = $_SERVER['REQUEST_URI'];
		$url = explode('?', $url);
		$url = $url[0];
		if (array_key_exists($url, $routes_ectr)) {
			$handler = $routes_ectr[$url];
			include "containers/".$handler;
		} else {
			include $routes_ectr_404;
		}
	}

	//Absolute path creator 
	public static function path($path){
		return $_SERVER["DOCUMENT_ROOT"].$path;
	}

	//Redirecting 
	public static function redirect($url, $sleep = 0){
		header('Refresh: '.$sleep.'; url='.$url);
		exit();
	}

	//Get host (may add path)
	public static function host($path = ""){
		if ($path) {
			$link="$path";
		}
		else {
			$link=null;
		}
			$actual_link = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$link";		
		
		return $actual_link;
	}

	//Get full real url
	public static function url(){
		$actual_link = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		return $actual_link;
	}

	//Get info about useragent
	public static function agent(){
		$detect = new Mobile_Detect;

		if($detect->isTablet() ) { $device = 'Tablet'; }
		elseif($detect->isMobile() && !$detect->isTablet() ) { $device = 'Phone'; }
		else{ $device = "PC";}

		if($detect->isiOS() ) { $os = 'IOS'; }
		elseif($detect->isAndroidOS() ) { $os = 'AOS'; }
		elseif($detect->isBlackBerryO() ) { $os = 'BB'; }
		elseif($detect->iswebOS() ) { $os = 'WOS'; }
		else{ $os = 'Windows OS';}


		if($detect->isiPhone() ) { $phone = 'iPhone'; }
		elseif($detect->isSamsung() ) { $phone = 'Samsung'; }
		elseif($detect->isBlackBerry() ) { $phone = 'BlackBery'; }
		elseif($detect->isSony() ) { $phone = 'Sony'; }
		else{ $phone = 'null';}

		if($detect->isChrome() ) { $browser = 'Chrome'; }
		if($detect->isOpera() ) { $browser = 'Opera'; }
		if($detect->isSafari() ) { $browser = 'Safari'; }
		if($detect->isEdge() ) { $browser = 'Edge'; }
		if($detect->isIE() ) { $browser = 'IE'; }
		if($detect->isFirefox() ) { $browser = 'Firefox'; }
		if (strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') !== FALSE) {
			$browser = 'Chrome';
		}
		if (strpos($_SERVER['HTTP_USER_AGENT'], 'OPR') !== FALSE) {
			$browser = 'Opera';
		}
		if (strpos($_SERVER['HTTP_USER_AGENT'], 'YaBrowser') !== FALSE) {
			$browser = 'Yandex';
		}
		else {
			$browser = 'null';
		}

		$today = date("Y-m-d H:i"); 
		$ip = $_SERVER['REMOTE_ADDR']; 
		$query = @unserialize(file_get_contents('http://ip-api.com/php/'.$ip.'?lang=ru'));

		if($query && $query['status'] == 'success') {
			$country = $query['country'];
			$city = $query['city'];
			} 
		else {
			$country = "null";
			$city = "null";
		}

		$data = array(
			'time' => $today,
			'country' => $country,
			'region' => $city,
			'device' => $device,
			'phone' => $phone,
			'browser' => $browser,
			'os' => $os,
		);

		return $data;

	}
}

//Session controller
class SESS {

	//Create new Session
	public static function set($name, $array){
		$_SESSION["$name"] = $array;
	}

	//Close Session
	public static function close($name){
		unset($_SESSION["$name"]);
	}

	//Route if not session
	public static function not($name, $locate){
		if (!$_SESSION["$name"]) {
    		header('Location: '.$locate);
		}
	}

	//Route if have Session
	public static function yes($name, $locate){
		if ($_SESSION["$name"]) {
    		header('Location: '.$locate);
		}
	}

	//Check session (have = 1 / not = 0)
	public static function check($name){
		if (!$_SESSION["$name"]) { return 0; }
		else { return 1; }
	}

}

class ERRC{
	public function __construct(){
		if(DEBUG){error_reporting(-1);}
		else{error_reporting(0);}
		set_error_handler([$this, 'errorHandler']);
		ob_start();
		register_shutdown_function([$this, 'fatalErrorHandler']);
	}

	public function errorHandler($errno, $errstr, $errfile, $errline){
		$this->displayErr($errno, $errstr, $errfile, $errline);
		return true;
	}

	public function fatalErrorHandler(){
		$error = error_get_last();
		if( !empty($error) && $error['type'] & (E_ERROR | E_PARSE | E_COMPILE_ERROR | E_CORE_ERROR)){
			ob_end_clean();
			$this->displayErr($error['type'], $error['message'], $error['file'], $error['line']);
		}
		else{
			ob_end_flush();
		}
	}

	protected function displayErr($errno, $errstr, $errfile, $errline, $response=500){
		http_response_code($response);
		if(DEBUG){require_once 'modules/DEBUG/Dview.php';}
		else{require_once 'modules/DEBUG/Pview.php';}
		die;
	}
}

#-----------------------------------------------AUTO RUN----------------------------------------------
session_start();
new ERRC;
?>