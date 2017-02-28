<?php
/**
 * Created by PhpStorm.
 * User: zeng
 * Date: 2017/2/15
 * Time: 21:50
 */
//header("Content-Type:text/html;   charset=gb2312");
//循环删除目录和文件函数

function delDirAndFile( $dirName )
{
    if ( $handle = opendir( "$dirName" ) )
    {
        while ( false !== ( $item = readdir( $handle ) ) )
        {
            if ( $item != "." && $item != ".." )
            {
                if ( is_dir( "$dirName\\$item" ) )
                {
                    delDirAndFile( "$dirName\\$item" );
                }
                else
                {
                    if( unlink( "$dirName\\$item" ) )
                        echo "成功删除文件： ".utf8($dirName.'\\'.$item)."<br>";
                }
            }
        }

    }
    closedir( $handle );
     if( rmdir( $dirName ) )
      echo '成功删除目录： '.utf8($dirName).'<br>';
}
//目录遍历
function traverse($path = '.')
{
    $current_dir = opendir($path);    //opendir()返回一个目录句柄,失败返回false
    while(($file = readdir($current_dir)) !== false) {    //readdir()返回打开目录句柄中的一个条目
        $sub_dir = $path . DIRECTORY_SEPARATOR . $file;    //构建子目录路径
        if($file == '.' || $file == '..') {
            continue;
        } else if(is_dir($sub_dir)) {    //如果是目录,进行递归
            echo 'Directory ' . $file . ':<br>';
            traverse($sub_dir);
        } else {    //如果是文件,直接输出
            echo 'File in Directory ' . $path . ': ' . $file . '<br>';
        }
    }
}

//返回当前文件夹下的所有目录，不递归遍历
function dir_lever1($path = '.')
{
    $dirs=array();
    $current_dir = opendir($path);    //opendir()返回一个目录句柄,失败返回false
    while(($file = readdir($current_dir)) !== false) {    //readdir()返回打开目录句柄中的一个条目
        $sub_dir = $path . DIRECTORY_SEPARATOR . $file;    //构建子目录路径
        if($file == '.' || $file == '..') {
            continue;
        } else if(is_dir($sub_dir)) {    //如果是目录,进行递归
             array_push($dirs,$sub_dir);

        }
    }
    return $dirs;
}
//返回当前文件夹下的第二级目录，不递归遍历
function dir_lever2($path = '.')
{
     $dirs=dir_lever1($path);
    //var_dump($dirs);
    $lever2_dirs=array();
    foreach($dirs as $dir)
    {
        $cur_dirs=dir_lever1($dir);
        $lever2_dirs=array_merge($lever2_dirs,$cur_dirs);
        //var_dump($cur_dirs);
    }
    return $lever2_dirs;
}

function gbk($src)
{
    return mb_convert_encoding($src, 'gbk', 'utf-8');
}
function utf8($src)
{
    return mb_convert_encoding($src, 'utf-8', 'gbk');
}
function copy_dir($src,$dst)
{

    $dir = opendir($src);
    @mkdir($dst);
    while(false !== ( $file = readdir($dir)) )
    {
        if (( $file != '.' ) && ( $file != '..' )) {
            if ( is_dir($src . '\\' . $file) ) {
                copy_dir($src . '\\' . $file,$dst . '\\' . $file);
                continue;
            }
            else
            {
                //echo utf8($src . '\\' . $file).'<br>';
                copy($src . '\\' . $file,$dst . '\\' . $file);
            }
        }
    }
    closedir($dir);
}

$source=$_GET['source'];
$destin=$_GET['destin'];
if(!$source)
{
    die("请设置源目录source");
}
echo  '源目录是'.$source;
if(!$destin)
{
    die("请设置目标目录destin") ;
}
echo '<br>目标目录是'.$destin.'<br>';

//$source='E:\PHP\abc';
//$destin='E:\PHP\copy';

//copy_dir($source,$destin);
$destin=gbk($destin);
$source=gbk($source);
//traverse($source);

$dirs=dir_lever2($source);
//var_dump($dirs);
$find_dirs=array();
foreach($dirs as $dir)
{
    $str = mb_convert_encoding('视频',  'gbk', 'utf-8');//转中文码，因为输出格式为gbk,定义的字符串默认为utf8
    //echo  $str;
    if(mb_strpos($dir,$str)!==false)
    {
        array_push($find_dirs,$dir);
        //echo  $dir."<br>";
    }
    //echo  utf8($dir)."<br>";

}
//目录拷贝
for($i=1;$i<=count($find_dirs);$i++  )
{
    $dir=$find_dirs[$i-1];
    $fileName=$destin.'\\'.$i.'  '.basename($dir);
    //$fileName = mb_convert_encoding($fileName, 'utf-8', 'gbk');
    echo  '正在拷贝目录'.utf8($fileName).'...<br>' ;
    copy_dir($dir,$fileName);
    echo '开始删除旧目录<br>';
     delDirAndFile($dir);
}
//var_dump(dir_lever2($source));