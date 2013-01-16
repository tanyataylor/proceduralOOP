<?php

error_reporting(E_ALL|E_STRICT);
ini_set("display_errors",1);

//include('Log.php');
class Setters
{
    public function __construct(){
        if(!isset($_SESSION)){
            session_start();
        }
    }
    public function init(){
        $basename = $this->getBaseName();
        $getUrls = $this->getUrls($basename);
        $renderUrls = $this->renderUrls($getUrls);
    }

    public function getBaseName(){
        $sessionArray = array();
        $url = $_SERVER['REQUEST_URI'];
        $path_info = pathinfo($url);
        $base_name = $path_info['basename'];
        return $base_name;
    }

    public function getUrls($basename){

        $expire=time()+60*60*24*30;
        for ($i=5; $i>=1; $i--){
            $_SESSION['view_url'.$i]= $_SESSION['view_url'.($i - 1)];
            $arr[]=$_SESSION['view_url'.$i];
            setcookie("last_urls", serialize($arr) , $expire);
        }
        $_SESSION['view_url'.$i] = $basename;
        //echo ("This is URL - _SESSION: <br />");
        //var_dump($arr);               //----------------------SESSION //var_dump($_SESSION);
        $cookie_urls= $_COOKIE['last_urls'];
        $str = $cookie_urls;
        $str1 = explode( ';', $str);

        $cookie_arr = array();
        foreach($str1 as $single){
            $findme = '"';
            $position = strpos($single,$findme);
            if ($position !== false){
                //echo "The string {$findme} was found at position {$position}";
                $get_str = substr($single,$position);
                $cookie_arr[] = $get_str;
            }
            else { }

        }
        $settersArray = array();
        $settersArray[] = $arr;
        $settersArray[] = $cookie_arr;
        return $settersArray;
    }

    public function renderUrls($settersarray){
        echo "Render SESSION and COOKIE: <br />";
        if (empty($settersarray[0])){
            echo "SESSION is empty, printing COOKIE";
            foreach($settersarray[1] as $key=>$value){
                echo "Site {$key} cookie : {$value} <br/>";
            }
        }
        else {
            echo "Printing SESSION: <br/>";
            foreach($settersarray[0] as $key=>$value){
                echo "Site {$key} session : {$value} <br/>";
            }
        }
    }
}










