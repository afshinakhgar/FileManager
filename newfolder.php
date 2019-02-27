<?php session_start();
require_once 'vendor/autoload.php';


function dd($string)
{
    var_dump($string);exit;
}




$fileManager = new FileManager\FileManager();


$new_query_string = http_build_query($_GET);
$url = 'http://'.$_SERVER['HTTP_HOST'].'?'.urldecode($new_query_string);
$fileManager->makeDir($_POST['title']);

$_SESSION['message'] = 'ساخته شد';
header("Location:".$url);
exit;

