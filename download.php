<?php
require_once 'vendor/autoload.php';


function dd($string)
{
    var_dump($string);exit;
}

if(!isset($_GET['address'])){
    exit;
}


$fileManager = new FileManager\FileManager();



$fileManager->download(urldecode($_GET['address']));


?>