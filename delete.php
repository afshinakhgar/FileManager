<?php
require_once 'vendor/autoload.php';


function dd($string)
{
    var_dump($string);exit;
}

if(!isset($_GET['type'])){
    exit;
}


$fileManager = new FileManager\FileManager();


if($_GET['type'] == 'file' && isset($_GET['filename'])){

    $fileManager->deleteFile(($_GET['filename']));

}else{
    $a = $fileManager->DeleteDirectory(urldecode($_GET['p']));
}


$new_query_string = ($_GET['p']);


$parent = $fileManager->getParent($new_query_string);
$url = 'http://'.$_SERVER['HTTP_HOST'].'?p='.urldecode($parent);


header("Location:".$url);
exit;

?>