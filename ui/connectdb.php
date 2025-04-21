<?php

try{

    $pdo = new PDO('mysql:host=localhost;port=3306;dbname=pos_barcode_db','root','');


}catch(PDOException $e  ){

echo $e->getMessage();


}





//echo'connection success';




?>