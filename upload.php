<?php session_start();
require_once 'vendor/autoload.php';


function dd($string)
{
    var_dump($string);exit;
}


$fileManager = new FileManager\FileManager();


$new_query_string = http_build_query($_GET);
$url = 'http://'.$_SERVER['HTTP_HOST'].'?'.urldecode($new_query_string);
$url = 'http://'.$_SERVER['HTTP_HOST'].'?p=/'.urldecode($_POST['currentpath']);


if ($_FILES["file"]["size"] > 500000) {
    $_SESSION['message'] = [
        'type' => 'error',
        'msg' => 'حجم فایل خیلی زیاد است ',
    ];
    $uploadOk = 0;

    header("Location:".$url);
    exit;
}

$target_dir = "public/".$_POST['currentpath'];
$target_file = $target_dir . basename($_FILES["file"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
if($imageFileType != "jpg" && $imageFileType != "png"  && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {

    $_SESSION['message'] = [
        'type' => 'error',
        'msg' => 'Sorry, only JPG, JPEG, PNG & GIF  files are allowed. ',
    ];
    $uploadOk = 0;

    header("Location:".$url);
    exit;

}



$uploadfile = $fileManager->rootDir .'/'.$_POST['currentpath'].'/'. basename($_FILES['file']['name']);

$dd = preg_match('/[^a-z0-9_ .]/i', basename($_FILES['file']['name']), $matches);


if(count($matches) > 0){
    $msg =   ' نام فایل فقط حروف انگلیسی و اعداد و  نقطه  و ـ';
    $msg .=   'می تواند باشد';
    $_SESSION['message'] = [
        'type' => 'error',
        'msg' => $msg,
    ];
    $uploadOk = 0;

    header("Location:".$url);
    exit;
}

if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
//    echo "File is valid, and was successfully uploaded.\n";



    $new_query_string = ($_GET['p']);


//    $parent = $fileManager->getParent($new_query_string);


    $_SESSION['message'] = [
        'type' => 'success',
        'msg' => 'فایل ارسال شد',
    ];

    header("Location:".$url);
    exit;
} else {
//    echo "Possible file upload attack!\n";
    $_SESSION['message'] = [
        'type' => 'error',
        'msg' => 'Possible file upload attack',
    ];


    header("Location:".$url);
    exit;

}
