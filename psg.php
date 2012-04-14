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
 $icon_folder=$icon_dir."/folder.gif";
 $image_top=$icon_dir."/PSG-HEAD-V1.jpg";
 $image_bot=$icon_dir."/PSG-FOOT-V1.jpg";
 $image_up=$icon_dir."/up.gif";
 $image_home=$icon_dir."/home.gif";
 $thumbnail_prefix="T-";
 $resized_prefix="R-";
 $thumbnail_size=200;
 $photosmal_size=800;
 $remake_images=0;
 $columns = 3;
 $script_url = "psg.php";

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
     echo "<A HREF=\"".$href."\">";
     echo "<img ".$width_height." src=\"".$img."\" /><br>";
     echo $text;
     echo "</A>"; }
  else {
     $href=$ref;
     echo "<A class=first title=".$text." HREF=\"".$href."\">";
     echo "<img ".$width_height." src=\"".$img."\" /><br>";
     echo $text;
     echo "</A>"; 
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
?>

</table>
</center>
</body>
</html>
