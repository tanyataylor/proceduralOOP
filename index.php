<?php

error_reporting(E_ALL|E_STRICT);
ini_set("display_errors",1);

$var = explode("/", $_SERVER['REQUEST_URI']);

$log = explode("?", $var[2]);

function __autoload($class_name){
    $file = getcwd(). "/" . $class_name . ".php";
    if(file_exists($file)){
        include($file);
    }
    else {
        echo $file;
    }
}

$connect = new DatabaseConnect();
$dbConnect = $connect->__init();


if($var[2] == "ProductData"){
    $productData = new ProductData();
    $output = $productData->__init($dbConnect);
    }

elseif($log[0] == "ProductData"){
    $productData = new ProductData();
    $output = $productData->__init($dbConnect);
}

elseif(isset($_POST['sortoption'])){
    $productData = new ProductData();
    $output = $productData->__init($dbConnect);
    }

elseif(isset($_POST['sortorder'])){
    $productData = new ProductData();
    $output = $productData->__init($dbConnect);
}

elseif((isset($_POST['limit'])) AND $_POST['limit'] > 0){
    $productData = new ProductData();
    $output = $productData->__init($dbConnect);
}

elseif(isset($_POST['submit'])){
    $productData = new ProductData();
    $output = $productData->__init($dbConnect);
}

else {
    $log = explode("?", $var[2]);
    if($log[0] == 'Logs'){
        $logs = new Logs();
        $outputLogs = $logs->initialize();
        }
    }


