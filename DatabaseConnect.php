<?php

error_reporting(E_ALL|E_STRICT);
ini_set("display_errors", 1);

class DatabaseConnect{

    public function __init(){
        try{
            $xml = $this->createXmlElement();
            $dbCredentials = $this->dbCredentials($xml);
            $link = $this->dbConnect($dbCredentials);
            return ($dbSelected = $this->dbSelect($dbCredentials, $link));
        }
        catch (Exception $e){
            die($e->getMessage());
            }
        }

    public function createXmlElement(){
        $xml = json_decode(json_encode(simpleXML_load_file('/var/www/magento/app/etc/local.xml','SimpleXMLElement', LIBXML_NOCDATA)),true);
        return $xml;
        //var_dump($xml);
        }

    public function dbCredentials($xml){
        $contents = $xml['global']['resources']['default_setup']['connection'];
        $connectAttributes = array_slice($contents,0,4);
        $host = $connectAttributes['host'];
        $username = $connectAttributes['username'];
        $password = $connectAttributes['password'];
        $dbName = $connectAttributes['dbname'];
        $storeCredentials = array();
        $storeCredentials[] = $host;
        $storeCredentials[] = $username;
        $storeCredentials[] = $password;
        $storeCredentials[] = $dbName;
        return $storeCredentials;
        }

    public function dbConnect($storeCredentials){
        $link = mysql_connect($storeCredentials[0], $storeCredentials[1], $storeCredentials[2]);
        if (!$link){
            //$this->create_log_entry("Error connecting to database: " . mysql_error());
            die("Could not connect : " . mysql_error());
            }
        /* Uncomment below if needed for the test! */
        //else {echo "Link was established.<br/>";}
        //else {$this->create_log_entry("Successfully connected to database");}
        return $link;
        }

    public function dbSelect($storeCredentials, $link){
        $dbSelected = mysql_select_db($storeCredentials[3], $link);
        if(!$dbSelected){
            //$this->create_log_entry("Could not select a database: " . mysql_error());
            die('Can\'t use db : ' . mysql_error());
            }
        /* Uncomment below if needed for the test! */
        //else {echo "<br/>Database $storeCredentials[3] was selected.";}
        //else {$this->create_log_entry("Successfully selected database $storeCredentials[3]");}
        return $dbSelected;
        }
    }
/*
try{
    $connect = new DatabaseConnect();
    $dbConnect = $connect->__init();
    //var_dump($dbConnect);
}
catch (Exception $e){
    echo $e->getMessage() . "\n";
}
*/