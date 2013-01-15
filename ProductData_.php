<?php
error_reporting(E_ALL|E_STRICT);
ini_set("display_errors", 1);

require_once("DatabaseConnect.php");

class ProductData
{
    public function __construct(){

        $connect = new DatabaseConnect();
        $dbConnect = $connect->__init();

        include('Setters.php');
        $setters = new Setters();
        $output = $setters->init();

        if(!isset($_SESSION)){
            session_start();
        }
    }
    public function __init($dbConnect){

        $header = $this->displayHeader();

        $productData = $this->getProductData($dbConnect, $sortoption = 'null', $sortorder = 'null');
        //echo session_id();
        return ($sortData = $this->dataSortForm($productData));
    }

    public function displayHeader(){
        include("displayHeader.phtml");
    }

    public function getProductData($dbConnect,$sortoption = 'null', $sortorder = 'null'){

        $sql = "SELECT catalog_product_entity.sku, catalog_product_entity_varchar.value, core_website.name
FROM catalog_product_entity
JOIN catalog_product_entity_varchar
ON catalog_product_entity.entity_id = catalog_product_entity_varchar.entity_id
JOIN catalog_product_website
ON catalog_product_website.product_id = catalog_product_entity.entity_id
JOIN core_website
ON catalog_product_website.website_id = core_website.website_id
WHERE catalog_product_entity_varchar.attribute_id = 96";

        if(isset($_GET['sku'])){
            $sql .= " AND catalog_product_entity.sku = '" .$_GET['sku']. "' ";
        }
        else {
            if(isset($_POST['sortoption'])){
                $sortoption = $_POST["sortoption"];
            }
            else $sortoption = "sku";

            if(isset($_POST['sortorder'])){
                $sortorder = $_POST["sortorder"];
            }
            else $sortorder = "asc";

            $sql .= " ORDER BY {$sortoption}" . " {$sortorder} ";

            if((isset($_POST['limit'])) AND $_POST['limit'] > 0){
                $limit = $_POST['limit'];
                $sql .= " limit 0, {$limit}";
            } else{
                $sql .= " limit 0, 30";
            }
        }

        $result = mysql_query($sql);
        if(!$result){
            die("Invalid query : " . mysql_error());
        }
        /* Uncomment if needed for the test! */
        //else {echo "<br/>Success<br/>";}
        else { }

        while ($row = mysql_fetch_assoc($result)){
            echo $string = "<tr><td>" . '<a href="ProductData_.php?sku=' .$row['sku'] . '">' . $row['sku'] . '</a>'.
                "</td><td>"  .$row['value'] .
                "</td><td>" . $row['name'] .
                "</td></tr>";
        }
        return $string;
    }

    public function dataSortForm($string){
        include("productdata.phtml");

    }

}

try{
    $productData = new ProductData();
    $output = $productData->__init($dbConnect);

}
catch (Exception $e){
    die($e->getMessage() . "\n");
}
