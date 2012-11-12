<?php header('Content-type: text/html; charset=utf-8')?>

<?php

 /* PROJECT GLOBAL VARIABLES */
 $archive_name="_ФОТОАРХИВ";
 $startdir="/fserv/MY_DOCS/DOCUMS/Foto/_ФОТОАРХИВ";
 $thumbsbasedir="/home/www/photo/PhotoScanGallery/images";
 $thumbsdir=$thumbsbasedir."/_ФОТОАРХИВ";
 $thumbsreldir="/photo/PhotoScanGallery/images/_ФОТОАРХИВ";
 $basedir="/home/www";
 $url_home="/photo/PhotoScanGallery";
 $icon_dir="/photo/PhotoScanGallery/images/ICONS";
 $icon_folder=$icon_dir."/plain_folder.png";
 $lock_folder=$icon_dir."/folder_locked.png";
 $psg_folder=$icon_dir."/psg_folder.png";
 $image_top=$icon_dir."/PSG-HEAD-V1.jpg";
 $image_bot=$icon_dir."/PSG-FOOT-V1.jpg";
 $image_up=$icon_dir."/up.gif";
 $image_dwld=$icon_dir."/download.png";
 $image_home=$icon_dir."/home.gif";
 $thumbnail_prefix="T-";
 $resized_prefix="R-";
 $thumbnail_size=200;
 $photosmal_size=800;
 $remake_images=0;
 $columns = 3;
 $script_url = "psg.php";
 $ini_name=".psg";

 /* MODULE GLOBAL VARIABLES */

 $col_count = 0;
 if (isset($_GET['url']))
 {
   $url=$_GET['url'];
 }
 else
 {
   $url=$thumbsdir;  
 };

 $startlen=strlen($url)+1;
 $baselen=strlen($basedir);
 $zagolovok = substr($url,strlen($thumbsbasedir)+1);
 if ($url==$thumbsdir)
  {$url_prev=$url_home;}
 else
  {$url_prev=$script_url."?url=".dirname($url);};
?>

<html>
<head>
<link rel="stylesheet" type="text/css" href="fancybox/jquery.fancybox-1.3.4.css">
<script type="text/javascript" src="fancybox/jquery-1.4.3.min.js"></script>
<script type="text/javascript" src="fancybox/jquery.easing-1.3.pack.js"></script>
<script type="text/javascript" src="fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<script type="text/javascript">
$(document).ready(function() {
$("a.first").fancybox({
	'overlayShow'	: false,
	'transitionIn'	: 'elastic',
	'transitionOut'	: 'elastic'
	});
});
</script>

<style type="text/css">
img { border:0; } 
</style>

<title>PhotoScanGallery Photo Album</title>
</head>
<body>
<center>
<table bgcolor=#E1E0E5 border=0 cellspacing=0>
<?php
  echo "<tr>";
  echo "<td colspan=".$columns."><center><img src=\"".$image_top."\"/></center></td>";
  echo "</tr><tr>";
  echo "<td colspan=".$columns." bgcolor=#49454E><a href=\"".$url_home."\"><img src=\"".$image_home."\"/></a><a href=\"".$url_prev."\"><img src=\"".$image_up."\"/></a></td>";
  echo "</tr><tr>";
  echo "<td colspan=".$columns." bgcolor=#49454E><center><font color=#F1F293>".$zagolovok."</font></center></td>";
  echo "</tr>";
?>

<?php

if (!defined('_BR_'))
   define('_BR_',chr(13).chr(10));
class TIniFileEx {
    public $filename;
    public $arr=array();
    function __construct($file = false){
        if ($file)
            $this->loadFromFile($file);
    }
    function initArray(){
        $this->arr = parse_ini_file($this->filename, true);
    }
    function loadFromFile($file){
        $result = true;
        $this->filename = $file;
        if (file_exists($file) && is_readable($file)){
            $this->initArray();
        }
        else
            $result = false;
        return $result;
    }
    function read($section, $key, $def = ''){
        if (isset($this->arr[$section][$key])){
            return $this->arr[$section][$key];
        } else
            return $def;
    }
    function write($section, $key, $value){
        if (is_bool($value))
            $value = $value ? 1 : 0;
        $this->arr[$section][$key] = $value;
    }
    function eraseSection($section){
        if (isset($this->arr[$section]))
            unset($this->arr[$section]);
    }
    function deleteKey($section, $key){
        if (isset($this->arr[$section][$key]))
            unset($this->arr[$section][$key]);
    }
    function readSections(&$array){
        $array = array_keys($this->arr);
        return $array;
    }
    function readKeys($section, &$array){
        if (isset($this->arr[$section])){
            $array = array_keys($this->arr[$section]);
            return $array;
        }
        return array();
    }
    function updateFile(){
        $result = '';
        foreach ($this->arr as $sname=>$section){
            $result .= '[' . $sname . ']' . _BR_;
            foreach ($section as $key=>$value){
                $result .= $key .'='.$value . _BR_;
            }
            $result .= _BR_;
        }
            file_put_contents($this->filename, $result);
            return true;
    }
    function __destruct(){
        $this->updateFile();
    }
}

function user_access($dir) {
  $curdir=$dir;
  $ret=0;

  do {
        $inifile=$curdir."/".$GLOBALS["ini_name"];
        $ini=new TIniFileEx($inifile);
        $users = $ini->read('auth','users','-');
        if ($users==='-') $curdir=dirname($curdir);
        $ret=$ret+1;
  } while (($curdir!==$GLOBALS["thumbsbasedir"])&&($users==='-'));

  if ($users==='-') return 0;
  $users_list=strtoupper($users);
  $pos=strpos($users_list,'ALL');
  if ($pos===false) {
     $pos=strpos($users_list,strtoupper($_SERVER['REMOTE_USER']));
     if ($pos===false) return 0;
  }

  return $ret;
}

function user_access2($dir) {

  return 1;

  $curdir=$dir;
  $ret=0;
  do {
        $inifile=$curdir."/".$GLOBALS["ini_name"];
        $ini=parse_ini_file($inifile,true);
        if ($ini==false) $curdir=dirname($curdir);
        $ret=$ret+1;
  } while (($curdir!=$GLOBALS["thumbsbasedir"])&&($ini===false));

  if ($ini===false) return 0;
  $users_list=strtoupper($ini['auth']['users']);
  $pos=strpos($users_list,'ALL');
  if ($pos===false) {
     $pos=strpos($users_list,strtoupper($_SERVER['REMOTE_USER']));
     if ($pos===false) return 0;
  }

  return $ret;
}

function cell_out($img, $text, $ref, $isimg) {
  $GLOBALS["col_count"]=$GLOBALS["col_count"]+1;
  if ($GLOBALS["col_count"]==1) {
     echo "<tr>";
  };

  $width_height="";
  if ($isimg==0) { $width_height="width=".$GLOBALS["thumbnail_size"]." height=".$GLOBALS["thumbnail_size"]; };
  $td_width_height="width=".$GLOBALS["thumbnail_size"]." height=".$GLOBALS["thumbnail_size"];
  echo "<td ".$td_width_height."><center>";

  if ($isimg==0) {
     $href=$GLOBALS["script_url"]."?url=".$ref;

     $ua=user_access($ref);

     if ($ua==0) {
        echo "<img src=\"".$GLOBALS["lock_folder"]."\" /><br>";
        echo $text;
     } else 
     if ($ua==1) {
        echo "<A HREF=\"".$href."\">";
     	echo "<img src=\"".$GLOBALS["psg_folder"]."\" /><br>";
        echo $text;
        echo "</A>";
     }
     else
     {
        echo "<A HREF=\"".$href."\">";
        echo "<img src=\"".$GLOBALS["icon_folder"]."\" /><br>";
        echo $text;
        echo "</A>";
     };
}
  else {
     $href=$ref;
     echo "<A class=first rel=gr title=".$text." HREF=\"".$href."\">";
     echo "<img ".$width_height." src=\"".$img."\" /><br>";
     echo "</A>"; 
     echo "<table><tr><td>";
     echo "<A class=first rel=tx title=".$text." HREF=\"".$href."\">";
     echo $text;
     echo "</A>";   
     echo "</td><td>";
     echo "<A HREF=\"download.php?filename=".$href."\">";
     echo "<img width=16 height=16 src=\"".$GLOBALS["image_dwld"]."\" />";
     echo "</A>";
     echo "</td></tr></table>";
  }
  
  echo "<br><br></center></td>";
  if ($GLOBALS["col_count"]==$GLOBALS["columns"]) {
     echo "</tr>\n";
     $GLOBALS["col_count"]=0;
  };
}

function dir_path($dir) {
//   $dh = opendir($dir);
   $path_curent = '';
   $file_dir = '';
   $file_ext = '';
   $file_name = '';

   $arr=scandir($dir,1);
   $count=count($arr);

//   while (($file = readdir($dh)) !== false) 
     for ($c=0;$c<$count;$c++)
     {
     $file=$arr[$c];
     if ($file != "." and $file != "..")
       {
         $abspath = $dir."/".$file;
         if (is_dir($abspath))
           {
             $short_path = substr($abspath,$GLOBALS["startlen"]);
             $mkpath = $GLOBALS["thumbsreldir"]."/".$short_path;
	     cell_out($GLOBALS["icon_folder"], $short_path, $abspath,0);
           }
         else
           {
             if (is_file($abspath))
               {
               $path_parts = pathinfo($abspath);
               $file_dir = dirname($abspath);
               $file_ext = $path_parts['extension'];
               $file_name = basename($abspath);
               $short_path = substr($abspath,$GLOBALS["startlen"]);
               $img_path = substr($abspath,$GLOBALS["baselen"]);
	       $prefix = substr($file_name,0,2);
               $mkpath = $GLOBALS["thumbsdir"]."/".$short_path;
               if ((strcmp(strtoupper($file_ext),"JPG")==0)  and (strtoupper($prefix)==strtoupper($GLOBALS["thumbnail_prefix"])))
                  {
                    cell_out($img_path, substr($file_name,2), dirname($img_path)."/".$GLOBALS["resized_prefix"].substr($file_name,2),1);
                  }

               }
           }
       }
   }
//   closedir($dh);
   return 0;
}

dir_path($url);

?>

<?php
  echo "<tr>";
  echo "<td colspan=".$columns."><center><img src=\"".$image_bot."\"/></center></td>";
  echo "</tr>";
//  echo "<tr>";
//  echo "<td colspan=".$columns."><center>".$_SERVER['REMOTE_USER']."</center></td>";
//  echo "</tr>";
?>

</table>
</center>
</body>
</html>
