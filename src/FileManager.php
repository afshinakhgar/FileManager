<?php
namespace FileManager;
Class FileManager {



    public $rootDir = '';
    public $currentFolder = '';
    public $basePath = '';

    public $blackListFoldersAndFiles = array(
      '.git',
      '.idea',
      'src',
    );
    public $whiteListFormat = array(
      'txt',
      'png',
      'jpg',
    );


    public function __construct()
    {
        $this->rootDir = $_SERVER['DOCUMENT_ROOT'].'/public';
        $this->basePath = $_SERVER['DOCUMENT_ROOT'].'/public';
        $this->currentFolder = '';

        if(isset($_GET['p'])){


            if(strpos($_GET['p'],'..') !== false){
                $_GET['p'] = '/';
            }

            $this->rootDir = $_SERVER['DOCUMENT_ROOT'].'/public/'.$_GET['p'];
            $this->currentFolder = $_GET['p'];


        }

    }

    public function allFolderList( )
    {
        $all = scandir($this->rootDir);


        return $all;
    }


    public function makeDir($dir )
    {


        $dir = preg_replace('/[^a-z0-9_ ]/i', '', $dir);

        if(!is_dir($this->rootDir.$dir)){
            mkdir($this->rootDir.'/'.trim($dir,'/'));
        }

        return $this->rootDir.trim($dir,'/');

    }




    public function getParent($current)
    {

        $parent = (explode('/',trim($current,'/')));
        unset($parent[count($parent)-1]);


        $parent = '/'.implode('/',$parent);
        return $parent;

    }


    public function countFolder($folder)
    {
        return count(array_filter(glob($folder), "is_dir"));

    }


    public function getDir()
    {
        $path = $this->getParentPath($this->rootDir);
        $objects = is_readable($this->rootDir) ? scandir($this->rootDir) : array();
        $folders = array();
        $files = array();



        if (is_array($objects)) {
            foreach ($objects as $file) {

                if ($file == '.' || $file == '..') {
                    continue;
                }

                $new_path = $this->rootDir . '/' . $file;

                if (is_file($new_path)) {
                    $ext = substr($file, strrpos($file, '.')+1);
                    if(in_array($ext,$this->whiteListFormat)) {
                        $files[] = $file;
                    }

                } elseif (is_dir($new_path) && $file != '.' && $file != '..') {
                    $folders[] = $file;
                }


            }
        }

        return [
            'files'=>$files,
            'folders'=>$folders
        ];

    }



    public function currentFolder()
    {
//        dd($_GET['p']);

//        dd($this->currentFolder);


        return trim($this->currentFolder,'/');
    }


    public function breadCrumb($folder)
    {
//        dd($_GET['p']);

//        dd($this->currentFolder);
        $folderArr = explode('/',$folder);
        $fullPath = '';
        $a = '<a href="http://'.$_SERVER['HTTP_HOST'].'?p=/" >Home</a>  ';
        foreach($folderArr as $row){

            $fullPath .= '/'.$row;

            if($this->currentFolder != trim($fullPath,'/')){
                $a .= '/ <a href="http://'.$_SERVER['HTTP_HOST'].'?p='.$fullPath.'" >'.$row.' </a>  ';

            }else{
                $a .= '/ <span href="http://'.$_SERVER['HTTP_HOST'].'?p='.$fullPath.'" class="active">'.$row.' </span>  ';
            }


        }
        return trim($a,'/');
    }


    /**
     * Get nice filesize
     * @param int $size
     * @return string
     */
    public function get_filesize($size)
    {
        if ($size < 1000) {
            return sprintf('%s B', $size);
        } elseif (($size / 1024) < 1000) {
            return sprintf('%s KiB', round(($size / 1024), 2));
        } elseif (($size / 1024 / 1024) < 1000) {
            return sprintf('%s MiB', round(($size / 1024 / 1024), 2));
        } elseif (($size / 1024 / 1024 / 1024) < 1000) {
            return sprintf('%s GiB', round(($size / 1024 / 1024 / 1024), 2));
        } else {
            return sprintf('%s TiB', round(($size / 1024 / 1024 / 1024 / 1024), 2));
        }
    }





    private function clean_path($path)
    {
        $path = trim($path);
        $path = trim($path, '\\/');
        $path = str_replace(array('../', '..\\'), '', $path);
        if ($path == '..') {
            $path = '';
        }
        return str_replace('\\', '/', $path);
    }

    /**
     * Get parent path
     * @param string $path
     * @return bool|string
     */
    private function getParentPath($path)
    {
        $path = $this->clean_path($path);
        if ($path != '') {
            $array = explode('/', $path);
            if (count($array) > 1) {
                $array = array_slice($array, 0, -1);
                return implode('/', $array);
            }
            return '';
        }
        return false;
    }



    public function download($file)
    {

        $fileArr = explode('/',$file);
        $fileName = end($fileArr);
        header("Content-Type: application/octet-stream");
        header("Content-Transfer-Encoding: Binary");
        header("Content-disposition: attachment; filename={$fileName}");


//        readfile ($file);
        exit;
    }





    public static function baseFolderUri()
    {
        return 'http://'.$_SERVER['HTTP_HOST'].'/public';
    }

    public function getPermission($path)
    {
        return substr(sprintf('%o', fileperms($path)), -4);
    }


    public function DeleteDirectory($dirName) {
        $dir = '/'.trim($this->basePath,'/').'/'.$dirName;
//
        $dir_handle = false;
        if (is_dir($dir)){
            $dir_handle = opendir($dir);
        }



        if (!$dir_handle){
            return false;
        }
        while($file = readdir($dir_handle)) {
            if ($file != "." && $file != "..") {
                if (!is_dir($dir."/".$file)){
                    unlink($dir."/".$file);

                } else{
                     $this->DeleteDirectory($dirName.'/'.$file);
                }
            }
        }

        closedir($dir_handle);
        rmdir($dir);


        return true;
    }

    public function deleteFile($file) {
        unlink($this->rootDir.'/'.$file);

        return true;
    }

}