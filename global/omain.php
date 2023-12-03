<?php
/* 
	-Easy Controll (ECTR)-
	Author: Just Adil
	Group: Just a Code Space
	GitHub: https://GitHub.com/A01L
	Version: ECTR 0.5v Beta
 */


//Set all config for global
require_once "config.php";
require_once 'Mobile_Detect.php';
include('qrlib.php');
//For function QrGen
$qr = new QR_BarCode();
session_start();

                        // data base control
	function tracker($trac){
		$detect = new Mobile_Detect;

		if($detect->isTablet() ) { $device = 'Tablet'; }
		elseif($detect->isMobile() && !$detect->isTablet() ) { $device = 'Phone'; }
		else{ $device = "PC";}

		if($detect->isiOS() ) { $os = 'IOS'; }
		elseif($detect->isAndroidOS() ) { $os = 'AOS'; }
		elseif($detect->isBlackBerryO() ) { $os = 'BB'; }
		elseif($detect->iswebOS() ) { $os = 'WEOS'; }
		else{ $os = 'Windows OS';}


		if($detect->isiPhone() ) { $phone = 'iPhone'; }
		elseif($detect->isSamsung() ) { $phone = 'Samsung'; }
		elseif($detect->isBlackBerry() ) { $phone = 'BB'; }
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


		$ip = $_SERVER['REMOTE_ADDR']; 
			$query = @unserialize(file_get_contents('http://ip-api.com/php/'.$ip.'?lang=ru'));
			if($query && $query['status'] == 'success') {
				$country = $query['country'];
				$city = $query['city'];
				} else {
				$country = "null";
				$city = "null";
			}
		$today = date("Y-m-d H:i"); 
		
		$data = array(
			'id' => 'null',
			'date' => $today,
			'trac' => $trac,
			'country' => $country,
			'region' => $city,
			'device' => $device,
			'model_device' => $phone,
			'browser' => $browser,
			'os' => $os,
		);
		add_data_db('data', $data);
	}
	/*
		MySQL Rows count
	*/
	function count_rows($table, $index, $index2){
		$sql = "SELECT * FROM `$table` WHERE `$index` = '$index2'";
		$conn = $GLOBALS['ectr_connect'];  
		if($result = $conn->query($sql)){
			$rowsCount = $result->num_rows; // ID - constant
			
			return $rowsCount;
			$result->free();
		}
	}
	function count_rows_two($table, $index, $index2, $index3, $index4){
		$sql = "SELECT * FROM `$table` WHERE `$index` = '$index2' AND `$index3` = '$index4'";
		$conn = $GLOBALS['ectr_connect'];  
		if($result = $conn->query($sql)){
			$rowsCount = $result->num_rows; // ID - constant
			
			return $rowsCount;
			$result->free();
		}
	}
	
	/*
		@param string $table => table name fo add
		@param array $ma => array data on type table
	*/
	function add_data_db($table, $ma){
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
		mysqli_query($GLOBALS['ectr_connect'], $full);
		return $full;
	}

	function update_data($table, $data, $id){
		$query = "UPDATE `".$table."` SET ";
		//`password` = '7f14dfdsdvds1', `hash` = 'Esdvsdv' WHERE `id` = 1
		foreach(array_keys($data) as $key){
			$query = $query."`".$key."` = '".$data["$key"]."', ";
		}
		$query = mb_eregi_replace("(.*)[^.]{2}$", '\\1', $query);
		$query = $query." WHERE `id` = ".$id;
		mysqli_query($GLOBALS['ectr_connect'], $query);
		return $query;
	}
	/*
		@param string $db => Connect to DataBase
		@param string $table => Table in DateBase
		@param string $data => Data for getting
		@param string $index => Name column in table for Equile
		@param string $index2 => Equile value in table column value (IDENTY)
	*/
    function get_data_db($db, $table, $data, $index, $index2){
        $querry = mysqli_query($db, "SELECT * FROM `$table` WHERE `$index` = '$index2'");
        $datas = mysqli_fetch_assoc($querry);
        return $datas["$data"];
    }

	/*
		@param string $db => Connect to DataBase
		@param string $table => Table in DateBase
		@param string $index => Name column in table for Equile
		@param string $index2 => Equile value in table column value (IDENTY)
	*/
    function del_data_db($db, $table, $index, $index2){
    	mysqli_query($db, "DELETE FROM `$table` WHERE `$index` = $index2");
    }

	/*
		Only controll main config DataBase!
		@param string $table => Table in DateBase
		@param string $data => Data for getting
		@param string $index => Name column in table for Equile
		@param string $index2 => Equile value in table column value (IDENTY)
	*/
    function get_data($table, $data, $index, $index2){
    	global $ectr_connect;
        $querry = mysqli_query($ectr_connect, "SELECT * FROM `$table` WHERE `$index` = '$index2'");
        $datas = mysqli_fetch_assoc($querry);
        return $datas["$data"];
    }


	function get_data_two($table, $data, $index, $index2, $index3, $index4){
    	global $ectr_connect;
        $querry = mysqli_query($ectr_connect, "SELECT * FROM `$table` WHERE `$index` = '$index2' AND `$index3` = '$index4'");
        $datas = mysqli_fetch_assoc($querry);
        return $datas["$data"];
    }

	/*
		Only controll main config DataBase!
		@param string $table => Table in DateBase
		@param string $index => Name column in table for Equile
		@param string $index2 => Equile value in table column value (IDENTY)
	*/
    function del_data($table, $index, $index2){
    	global $ectr_connect;
    	mysqli_query($ectr_connect, "DELETE FROM `$table` WHERE `$index` = $index2");
    }

    					// data control
	
	/*
		@param string $name_form => name of input in form 
		@param string $text => patch for set [Storage Controll] 
	*/
	function storage_control($name_form, $path_storage){

	// Generate identy name of time
	date_default_timezone_set('UTC');

	// For catalog 
	$dir = date("Ym");
	
	// For file
	$gen1 = date("dHis"); 
	$gen2 = rand_n(3);
	$fname = "$gen1$gen2";

	// Check catalog and save file
	if (is_dir ($dir)) {
		get_file("$path_storage/$dir/", $name_form, $fname);
	} 
	else {
		mkdir("$path_storage/$dir", 0777, true);
		get_file("$path_storage/$dir/", $name_form, $fname);
	}

	// Array info for call-back
	$ex = format($_FILES["$name_form"]['name']);
	$call_back = array(
		"name" => "$fname.$ex",
		"path" => "$dir/"
	);
	return $call_back;
	}

	/*
		@param integer $start => count simbols for cutting start string
		@param string $text => Text
		@param integer $end => count simbols for cutting end string
	*/
    function cut_text($start, $text, $end){
    $t1 = mb_eregi_replace("(.*)[^.]{".$end."}$", '\\1', $text);
    $t2 = mb_eregi_replace("^.{".$start."}(.*)$", '\\1', $t1);
    $text = $t2;
    return $text;
	}

	/*
		@param string $type => type send data GET[g] or POST[p]
	*/
	function form($type){
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

	/*
		@param string $file => fullname (file) 
	*/
	function format($file){
		 $temp= explode('.',$file);
		 $extension = end($temp);
		 return $extension;
	}

	/*
		@param string $path => path for save file
		@param string $name => (form->input->attr->name) name input in forms  
		@param string $newn => rename file before save
	*/
	function get_file($path, $name, $newn = "null"){
		if (!@copy($_FILES["$name"]['tmp_name'], $path.$_FILES["$name"]['name'])){
			return 1;
			}
		else {
			$fn = $_FILES["$name"]['name'];
			$type = format($fn);
			if ($newn != "null") {
				rename("$path$fn", "$path$newn.$type");
				return "$newn.$type";
			}
			else{
				$fnn=str_replace( " " , "_" , $_FILES["$name"]['name']);
				rename("$path$fn", "$path$fnn");
				return "$fnn";
			}
		}
	}

	/*
		@param string $dir => path to dir for scanning 
		@param integer $i => array index 
	*/
	function ls($dir, $i){
		$files = scandir($dir);
		$array = count($files);
		if ($i >= $array) {
			return "Out chain data!";
		}
		else{
			return $files[$i];
		}
	}	

	/*
		@param string $link => link for sending GET data
		@param array $data => array data ['name' = 'value', ...]
	*/
	function ssg_data($link, $data){
			$ch = curl_init("$link?" . http_build_query($data));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_HEADER, false);
			$return = curl_exec($ch);
			curl_close($ch);
			return $return;
		
	}
	
	/*
		@param string $link => link for sending POST data
		@param array $data => array data ['name' = 'value', ...]
	*/
	function ssp_data($link, $data){
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

	/*
		@param integer $length => length simbols for generate [Default value = 6]
	*/
	function rand_s($length = 6)
	{
		$arr = array(
			'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 
			'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
		);
	 
		$res = '';
		for ($i = 0; $i < $length; $i++) {
			$res .= $arr[random_int(0, count($arr) - 1)];
		}
		return $res;
	}

	/*
		@param integer $length => length number for generate [Default value = 6]
	*/
	function rand_n($length = 6)
	{
		$arr = array(
			'1', '2', '3', '4', '5', '6', '7', '8', '9', '0'
		);
	 
		$res = '';
		for ($i = 0; $i < $length; $i++) {
			$res .= $arr[random_int(0, count($arr) - 1)];
		}
		return $res;
	}

	/*
		@param integer $length => length hybrid simbols and number for generate [Default value = 6]
	*/
	function rand_sn($length = 6)
	{
		$arr = array(
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

	// Module function for zip controll system 
	function addFileRecursion($zip, $dir, $start = '')
	{
		if (empty($start)) {
			$start = $dir;
		}
		
		if ($objs = glob($dir . '/*')) {
			foreach($objs as $obj) { 
				if (is_dir($obj)) {
					addFileRecursion($zip, $obj, $start);
				} else {
					$zip->addFile($obj, str_replace(dirname($start) . '/', '', $obj));
				}
			}
		}
	}

	/*
		@param string $name => new zip file Name
		@param string $file => path to file for add zip
	*/
	function file_to_zip($name, $file){
		$zip = new ZipArchive();
		$zip->open("$name.zip", ZipArchive::CREATE);
		$zip->addFile("$file", "$file");
		$zip->close();
	}

	/*
		@param string $name => new zip file Name
		@param string $dir => path to dir for add zip
	*/
	function dir_to_zip($name, $dir){
		$zip = new ZipArchive();
		$zip->open("$name.zip", ZipArchive::CREATE|ZipArchive::OVERWRITE);
		addFileRecursion($zip, "$dir");
		$zip->close();
	}

	/*
		@param string $file => zip file for unzip 
		@param string $path => path to extract files in zip
	*/
	function un_zip($file, $path){
		$zip = new ZipArchive();
		$zip->open("$file");
		$zip->extractTo("$path");
		$zip->close();
	}



						// system control
	
	/*
		@param string $url => URL for redirecting 
		@param integer $sleep => sleep timer
	*/
	function route($link, $path){
		global $routes_ectr;
		$routes_ectr["$link"] = "$path";
	}

	function startRoute(){
		global $routes_ectr;
		global $routes_ectr_404;
		// �������� ������� URL
		$url = $_SERVER['REQUEST_URI'];
		$url = explode('?', $url);
		$url = $url[0];
		// ���������, ���� �� ������� ��� ������� URL
		if (array_key_exists($url, $routes_ectr)) {
			// ���� ����, �������� ��������������� ����������
			$handler = $routes_ectr[$url];
			include "view/".$handler;
		} else {
			// ���� ������� �� ������, ������� �������� ������ 404
			include $routes_ectr_404;
		}
	}


	/*
		@param string $url => URL for redirecting 
		@param integer $sleep => sleep timer
	*/
	function redirect($url, $sleep = 0){
		header('Refresh: '.$sleep.'; url='.$url);
		exit();
	}

	/*
		Getting full locate to runned script file
	*/
	function locate_full(){
		$actual_link = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		return $actual_link;
	}

	/*
		@param string $path => (subdir) dir before hostname
	*/
	function locate($path = ""){
		if ($path) {
			$link="/$path";
		}
		else {
			$link=null;
		}
			$actual_link = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$link";		
		
		return $actual_link;
	}

						// Session control

	function session_control_signin($table, $login, $password, $rule){
		// call connect config
		global $ectr_connect;

		// query for checking in table to valid data [login, password]
		$check_user = mysqli_query($ectr_connect, "SELECT * FROM `$table` WHERE `login` = '$login' AND `password` = '$password'");
  
			// query require find result
			if (mysqli_num_rows($check_user) > 0) {

				$user = mysqli_fetch_assoc($check_user);

				// create session
				creat_sess($rule, $user['id'], rand_sn(9));

			   $result = 1;

			} 
        
			// query require not found result
			else {
				$result = 0;
			}

			// reurn result [ YES=1 | NOT=0]
			return $result;
	
	}
/*
		@param string $name => session name
		@param integer $id => set ID to session 
		@param string $key => set KEY to session
*/
	function creat_sess($name, $id, $key){
		$_SESSION["$name"] = [
            "id" => $id,
            "key" => $key,
        ];
	}

/*
		@param string $name => session name for closing
*/
	function close_sess($name){
		unset($_SESSION["$name"]);
	}

/*
		@param string $name => valid session name
		@param string $locate => link for redirect if session name no valid
*/
	function route_not_sess($name, $locate){
		if (!$_SESSION["$name"]) {
    		header('Location: '.$locate);
		}
	}

/*
		@param string $name => session name
		@param string $locate => link for redirect
*/
	function route_sess($name, $locate){
		if ($_SESSION["$name"]) {
    		header('Location: '.$locate);
		}
	}

/*
		@param string $name => session name for checking status [0=not, 1=yes]
*/
	function check_sess($name){
		if (!$_SESSION["$name"]) {
    		return 0;
		}
		else{
			return 1;
		}
	}

						// Api controll

/*
		@param string $data => data for encode to QR image
		@param string $path => path for saving result QR image
*/
	function gen_qr($data, $path){
		global $qr;
        $qr->info($data);
        $qr->qrCode(500, $path);
	}

/*
		@param string $number => phone number for redirect 
		@param string $msg => message for sending
*/
	function wa_api($number, $msg = " "){
		$data=urlencode($msg);
		$link="https://api.whatsapp.com/send?phone=$number&text=$data";
		return $link;
	}

/*
		@param string $currency_code => official country currency code 
		@param string $format => format for getting (n.000) [Default = 0]
*/		
	function currency($currency_code, $format = 0) {

		$date = date('d/m/Y');
		$cache_time_out = 14400; 
		
		$file_currency_cache = './currency.xml'; 

		if(!is_file($file_currency_cache) || filemtime($file_currency_cache) < (time() - $cache_time_out)) {

			$ch = curl_init();

			curl_setopt($ch, CURLOPT_URL, 'https://www.cbr.ru/scripts/XML_daily.asp?date_req='.$date);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
			curl_setopt($ch, CURLOPT_HEADER, 0);

			$out = curl_exec($ch);

			curl_close($ch);

			file_put_contents($file_currency_cache, $out);

		}

		$content_currency = simplexml_load_file($file_currency_cache);

		return number_format(str_replace(',', '.', $content_currency->xpath('Valute[CharCode="'.$currency_code.'"]')[0]->Value), $format);

	}

									//Three JS controll on php

/*
		@param string $display => class name block for render
		@param string $path => path to model .gltf
		@param string $paf => if sript run in other dir, use it param for locate path to ECTR lib
		@param string $bg => background color for render scene 
		@param string<int $py => place level [Default = -1]
*/
function add_gltf($display, $path, $zoom=60, $paf="", $bg="#ffffff", $py="-1"){
	echo'
	
<script type="importmap">
        {
            "imports": {
                "three": "./'.$paf.'ectr/build/three.module.js",
                "OrbitControls": "./'.$paf.'ectr/jsm/controls/OrbitControls.js",
                "GLTFLoader": "./'.$paf.'ectr/jsm/loaders/GLTFLoader.js",
                "RectAreaLightHelper": "./'.$paf.'ectr/jsm/helpers/RectAreaLightHelper.js",
                "RectAreaLightUniformsLib": "./'.$paf.'ectr/jsm/lights/RectAreaLightUniformsLib.js"
            }
        }
    </script>

    <script type="module">

        import * as THREE from "three";
        import { OrbitControls } from "OrbitControls";
        import { GLTFLoader } from "GLTFLoader";
        import { RectAreaLightHelper } from "RectAreaLightHelper"
        import { RectAreaLightUniformsLib } from "RectAreaLightUniformsLib";

        function init() {
            let container = document.querySelector(".'.$display.'");

            const scene = new THREE.Scene()
            scene.background = new THREE.Color("'.$bg.'");

            const camera = new THREE.PerspectiveCamera('.$zoom.', window.innerWidth / window.innerHeight, 0.1, 3000);
            camera.position.set(1, 0.5, 1)
            

            const renderer = new THREE.WebGLRenderer({antialias: true})
            renderer.setSize(window.innerWidth, window.innerHeight)
            container.appendChild(renderer.domElement)

            let plain;
            {
                plain = new THREE.Mesh(
                    new THREE.PlaneGeometry(1000, 1000),
                    new THREE.MeshBasicMaterial({color: "'.$bg.'"})
                )
                plain.reciveShadow = true;
                plain.position.set(0, '.$py.', 0)
                plain.rotateX(-Math.PI / 2);
                scene.add(plain)
            }

            {
                const loader = new GLTFLoader();
                loader.load("./'.$path.'", gltf => {
                scene.add(gltf.scene);
                }, 
                    function (error) {
                        console.log("Error: " + error)
                    }
                )
            }
            
            {
                const light = new THREE.DirectionalLight(0xffffff, 1)
                light.position.set(-2, 0, 10)
                light.lookAt(1, -1, 0)
                scene.add(light)

                // Helper
                // const helper = new THREE.DirectionalLightHelper(light, 5)
                // scene.add(helper)
            }

            {
                const light = new THREE.DirectionalLight(0xffffff, 1)
                light.position.set(2, 0, 5)
                light.lookAt(0, 1, 0)
                scene.add(light)

                // Helper
                // const helper = new THREE.DirectionalLightHelper(light, 5)
                // scene.add(helper)
            }

            RectAreaLightUniformsLib.init();
            {
                const rectLight = new THREE.RectAreaLight(0xffffff, 1, 100, 100);
                rectLight.position.set(-10,0,0)
                rectLight.rotation.y = Math.PI + Math.PI/4;
                scene.add(rectLight)
            }

            {
                const rectLight = new THREE.RectAreaLight(0xffffff, 1, 100, 100);
                rectLight.position.set(10,0,0)
                rectLight.rotation.y = Math.PI - Math.PI/4;
                scene.add(rectLight)
            }
            

            const controls = new OrbitControls(camera, renderer.domElement);
            controls.autoRotate = true;
            controls.autoRotateSpeed = 5;
            controls.enableDamping = true;

            window.addEventListener("resize", onWindowResize, false)
            
            function onWindowResize() {
                camera.aspect = window.innerWidth / window.innerHeight;
                camera.updateProjectionMatrix();

                renderer.setSize(window.innerWidth, window.innerHeight)
            }

            function animate() {
                requestAnimationFrame(animate)
                controls.update();
                renderer.render(scene, camera)
            }
            animate()
        }
        init()
    </script>';
}

/*
		@param string $display => class name block for render
		@param string $path => path to model .vox
		@param integer $zoom => camera zoom
		@param string $paf => if sript run in other dir, use it param for locate path to ECTR lib
		@param string $bg => background color for render scene 
*/
function add_vox($display, $path, $zoom=50, $paf="", $bg="#ffffff"){
    echo '<script async src="'.$paf.'ectr/es-module-shims.js"></script>

		<script type="importmap">
			{
				"imports": {
					"three": "./'.$paf.'ectr/build/three.module.js",
					"three/addons/": "./'.$paf.'ectr/jsm/"
				}
			}
		</script>

		<script type="module">

			import * as THREE from "three";

			import { OrbitControls } from "three/addons/controls/OrbitControls.js";
			import { VOXLoader, VOXMesh } from "three/addons/loaders/VOXLoader.js";

			let camera, controls, scene, renderer;

			init();

			function init() {

               

				camera = new THREE.PerspectiveCamera( '.$zoom.', window.innerWidth / window.innerHeight, 0.01, 10 );
				camera.position.set( 0.175, 0.075, 0.175 );

				scene = new THREE.Scene();
                scene.background = new THREE.Color("'.$bg.'");
				scene.add( camera );

				// light

				const hemiLight = new THREE.HemisphereLight( 0xcccccc, 0x444444, 1 );
				scene.add( hemiLight );

				const dirLight = new THREE.DirectionalLight( 0xffffff, 0.75 );
				dirLight.position.set( 1.5, 3, 2.5 );
				scene.add( dirLight );

				const dirLight2 = new THREE.DirectionalLight( 0xffffff, 0.5 );
				dirLight2.position.set( - 1.5, - 3, - 2.5 );
				scene.add( dirLight2 );

				const loader = new VOXLoader();
				loader.load( "'.$path.'", function ( chunks ) {

					for ( let i = 0; i < chunks.length; i ++ ) {

						const chunk = chunks[ i ];

						// displayPalette( chunk.palette );

						const mesh = new VOXMesh( chunk );
						mesh.scale.setScalar( 0.0015 );
						scene.add( mesh );

					}

				} );

				// renderer

				let container = document.querySelector(".'.$display.'");

				renderer = new THREE.WebGLRenderer();
				renderer.setPixelRatio( window.devicePixelRatio );
				renderer.setSize( window.innerWidth, window.innerHeight );
				container.appendChild( renderer.domElement );

				// controls

				controls = new OrbitControls( camera, renderer.domElement );
				controls.minDistance = .1;
				controls.maxDistance = 0.5;

				//

				window.addEventListener( "resize", onWindowResize );

			}

			function onWindowResize() {

				camera.aspect = window.innerWidth / window.innerHeight;
				camera.updateProjectionMatrix();

				renderer.setSize( window.innerWidth, window.innerHeight );

			}

			function animate() {

				requestAnimationFrame( animate );

				controls.update();

				renderer.render( scene, camera );

			}
			animate();


		</script>';
}

/*
		@param array $links => 4 links hash in YouTube
*/
function add_ytc($container ,$links){
	$check = count($links);
	if ($check != 4) {
	   echo 'Need 4 links hash!';
    }
    else {
		echo '
			<div id="blocker_ytc"></div><script async src="ectr/es-module-shims.js"></script>

			<script type="importmap">
				{
					"imports": {
						"three": "./ectr/build/three.module.js",
						"three/addons/": "./ectr/jsm/"
					}
				}
			</script>

			<script type="module">

				import * as THREE from "three";

				import { TrackballControls } from "three/addons/controls/TrackballControls.js";
				import { CSS3DRenderer, CSS3DObject } from "three/addons/renderers/CSS3DRenderer.js";

				let camera, scene, renderer;
				let controls;

				function Element( id, x, y, z, ry ) {

					const div = document.createElement( "div" );
					div.style.width = "480px";
					div.style.height = "360px";
					div.style.backgroundColor = "black";

					const iframe = document.createElement( "iframe" );
					iframe.style.width = "480px";
					iframe.style.height = "360px";
					iframe.style.border = "0px";
					iframe.src = [ "https://www.youtube.com/embed/", id, "?rel=0" ].join( "" );
					div.appendChild( iframe );

					const object = new CSS3DObject( div );
					object.position.set( x, y, z );
					object.rotation.y = ry;

					return object;

				}

				init();
				animate();

				function init() {

					const container = document.getElementById( "'.$container.'" );

					camera = new THREE.PerspectiveCamera( 50, window.innerWidth / window.innerHeight, 1, 5000 );
					camera.position.set( 500, 350, 750 );

					scene = new THREE.Scene();

					renderer = new CSS3DRenderer();
					renderer.setSize( window.innerWidth, window.innerHeight );
					container.appendChild( renderer.domElement );

					const group = new THREE.Group();
					group.add( new Element( "'.$links[0].'", 0, 0, 240, 0 ) );
					group.add( new Element( "'.$links[1].'", 240, 0, 0, Math.PI / 2 ) );
					group.add( new Element( "'.$links[2].'", 0, 0, - 240, Math.PI ) );
					group.add( new Element( "'.$links[3].'", - 240, 0, 0, - Math.PI / 2 ) );
					scene.add( group );

					controls = new TrackballControls( camera, renderer.domElement );
					controls.rotateSpeed = 4;

					window.addEventListener( "resize", onWindowResize );

					// Block iframe events when dragging camera

					const blocker = document.getElementById( "blocker_ytc" );
					blocker.style.display = "none";

					controls.addEventListener( "start", function () {

						blocker.style.display = "";

					} );
					controls.addEventListener( "end", function () {

						blocker.style.display = "none";

					} );

				}

				function onWindowResize() {

					camera.aspect = window.innerWidth / window.innerHeight;
					camera.updateProjectionMatrix();
					renderer.setSize( window.innerWidth, window.innerHeight );

				}

				function animate() {

					requestAnimationFrame( animate );
					controls.update();
					renderer.render( scene, camera );

				}

			</script>';
		}
}

?>
