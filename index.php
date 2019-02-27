<?php
    require_once 'vendor/autoload.php';


    function dd($string)
    {
        var_dump($string);exit;
    }


    $fileManager = new FileManager\FileManager();

    $all = $fileManager->getDir();


    $current = $fileManager->currentFolder();



    $folderUri = \FileManager\FileManager::baseFolderUri();





?>

<html>
    <head>
        <title>
            File Manager
        </title>
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css" />
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">



        <style>
            .wrapper{
                width: 60%;
                margin:0px auto;
            }

            body{
                direction: rtl;
            }
            .toolbar{

                border-bottom: 1px solid #ffcd3c;
                padding: 20px 0px;
                margin: 0px;
            }


            .toolbar ul {
                float: right;
                margin: 0px;
                padding: 0px;
            }
            .toolbar ul li{
                display: inline-block;
                list-style-type: none;
                padding-left: 10px;
            }
            
            .up-level{
                padding: 20px 0px;
            }
            .newfolder_form{
                display: none;
            }

.address-show{
    direction: ltr;
}


        </style>
    </head>



    <body>





    <div class="wrapper">

        <?php if(isset($_SESSION['message'])):?>
        <div class="alert <?=$_SESSION['message']['type']?>">
            <?=$_SESSION['message']['msg']?>
        </div>
        <?endif;?>
        <h1>File Manager</h1>
        <hr>

        <div class="toolbar clearfix">
            <ul>
                <li>
                    <a href="#" id="new_folder">
                        <fa class="fa fa-folder-plus">
                        </fa>
                    </a>
                </li>
                <li>
                    <form action="upload.php" id="upload_form" method="post" enctype="multipart/form-data">
                        <input type="file" name="file"  multiple >
                        <input type="hidden" name="currentpath" value="<?=$current?>">

                    </form>
                </li>
            </ul>
            
            
            <div class="newfolder_form">
                <form action="newfolder.php?p=<?=$current?>" method="post">
                    <input type="text" name="title" class="title_new_folder">
                    <input type="hidden" name="currentpath" value="<?=$current?>">
                    <input type="submit" value="فولدر جدید">
                </form>
            </div>


            <div class="row">
                <div class="col-md-12">
                    <?=$fileManager->breadCrumb($current)?>
                    <i class="fa fa-folder-open"></i>

                </div>
            </div>

        </div>
        <div class="row up-level">
            <div class="col-md-12 clearfix">
                <div class="pull-right">
                    <?php if($current):?>
                        <a href="http://<?=$_SERVER['HTTP_HOST']?>?p=<?=$fileManager->getParent($current)?>">
                            <i class="fa fa-level-up-alt"></i>
                        </a>
                    <?php endif;?>
                </div>
            </div>
        </div>
        <table class="table table-striped ">
            <tr>
                <th>Preview</th>
                <th>عنوان</th>
                <th>عملیات</th>

                <th>آدرس</th>
                <th>دانلود</th>
            </tr>


            <?php foreach($all['folders'] as $row):?>

                <tr>

                    <td>



                        <?php
                        $folderCnt = $fileManager->countFolder($fileManager->basePath.'/'.$current.'/'.$row);
                        $folderIcon = 'folder';

                        ?>
                        <a href="http://<?=$_SERVER['HTTP_HOST']?>?p=<?=$current.'/'.$row?>">
                        <i class="fa fa-<?=$folderIcon?>" style="font-size: 75px;">
                        </i>
                        </a>

                    </td>

                    <td></i>
                        <a href="http://<?=$_SERVER['HTTP_HOST']?>?p=<?=$current.'/'.$row?>"><?=$row?></a></td>
                    <td>
                        <a href="delete.php?p=<?=$current.'/'.$row?>&type=folder" class="delete">
                            <i class="fa fa-trash"></i>
                        </a>
<!--                        <a href="#">-->
<!--                            <i class="fa fa-edit"></i>-->
<!--                        </a>-->

                    </td>
                    <td></td>
                    <td>.</td>
                </tr>

            <?php endforeach;?>



            <?php foreach($all['files'] as $row):?>

                <tr>
                    <?php
                    $fileAddress = $folderUri.'/'.$current.'/'.$row;
                    ?>
                    <td>
                        <a href="<?=$fileAddress?>"  target="_blank">

                        <img class="img-thumbnail img-rounded" style="width: 250px;" src="<?=$fileAddress?>" alt="">
                        </a>
                    </td>
                    <td>
                        <a href="<?=$fileAddress?>"  target="_blank">
                            <i class="fa fa-file"></i>
                            <?=$row?>
                        </a>
                    </td>
                    <td>
                        <a href="delete.php?p=<?=$current?>&type=file&filename=<?=$row?>" class="delete">
                            <i class="fa fa-trash"></i>

                        </a>
<!--                        <a href="#" class="title-edit">-->
<!--                            <i class="fa fa-edit"></i>-->
<!--                        </a>-->
                    </td>
                    <td><input type="text" value="<?=$folderUri.'/'.$current.$row?>" class="form-control address-show" ></td>

                    <td><a href="http://<?=$_SERVER['HTTP_HOST']?>/download.php?address=<?=urlencode($fileAddress)?>"><i class="fa fa-download"></i></a></td>


                </tr>

            <?php endforeach;?>
        </table>
    </div>


    <script
        src="https://code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
        crossorigin="anonymous"></script>




    <script>
        $('#new_folder').on('click',function(){
            $('.newfolder_form').show();
            $('.newfolder_form input.title_new_folder').focus();
        });



        $('#upload_form').on('change',function () {
            $(this).submit();
        });


        $('.delete').on('click',function (e) {
           return confirm('Are you Sure?');

        })
    </script>



    </body>
</html>