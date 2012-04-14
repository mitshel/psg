<?php
 
 /* PROJECT GLOBAL VARIABLES */
 
 $startdir="/fserv/MY_DOCS/DOCUMS/Foto/_ФОТОАРХИВ";
 $thumbsdir="/home/www/photo/PhotoScanGallery/images/_ФОТОАРХИВ";
 $thumbnail_prefix="T-";
 $resized_prefix="R-";
 $thumbnail_size=200;
 $photosmal_size=800;
 $remake_images=0;

 /* MODULE GLOBAL VARIABLES */

 $startlen=strlen($startdir)+1;

 function thumb_create($path, $newpath, $fn) {

   $thumb_file=$newpath."/".$GLOBALS["thumbnail_prefix"].$fn;
   $resized_file=$newpath."/".$GLOBALS["resized_prefix"].$fn;
   
   if (file_exists($thumb_file)) {
      echo "Thumbnail ".$thumb_file." already Exists\r\n";
      return;
   } 
   else
   { 
      echo "Thumbnail ".$thumb_file." not Exists\r\n";
   }

   $cmd_identify="identify -format \"%[EXIF:Orientation]\" \"".$path."\"";

   echo $cmd_identify;
   $last_line = system($cmd_identify, $retval);
   echo " Result=".$last_line.":".$retval."\n";

   $cmd_rotation="";

   if ($last_line=="6") { $cmd_rotation="-rotate 90"; };
   if ($last_line=="8") { $cmd_rotation="-rotate -90"; };


   $cmd_convert_thumb="convert -thumbnail ".$GLOBALS["thumbnail_size"]."x".$GLOBALS["thumbnail_size"]." ".$cmd_rotation." \"".$path."\" \"".$thumb_file."\"";
   $cmd_convert_pict="convert -resize ".$GLOBALS["photosmal_size"]."x".$GLOBALS["photosmal_size"]." ".$cmd_rotation." \"".$path."\" \"".$resized_file."\"";
  
   echo $cmd_convert_thumb;
   $last_line = system($cmd_convert_thumb, $retval);
   echo " Result=".$last_line.":".$retval."\n";
   
   echo $cmd_convert_pict;
   $last_line = system($cmd_convert_pict, $retval);
   echo " Result=".$last_line.":".$retval."\n\n";
   
   return 1;
 }


 function dir_path($dir) {
 $dh = opendir($dir);
   $path_curent = '';
   $file_dir = '';
   $file_ext = '';
   $file_name = '';
   
   while (($file = readdir($dh)) !== false)
     if ($file != "." and $file != "..")
       {
         $path = $dir."/".$file;
         if (is_dir($path))
           {
	     $short_path = substr($path,$GLOBALS["startlen"]);
	     $mkpath = $GLOBALS["thumbsdir"]."/".$short_path;
	     $cmd = "mkdir -p '".$mkpath."'";

             echo $cmd;
             $last_line = system($cmd, $retval);
	     echo " Result=".$last_line.":".$retval."\n\n";

             dir_path($path);
           }
         else
           {
             if (is_file($path))
               {
	       $path_parts = pathinfo($path);
	       $file_dir = dirname($path);
	       $file_ext = $path_parts['extension'];
	       $file_name = basename($path);
	       $short_path = substr($file_dir,$GLOBALS["startlen"]);
	       $mkpath = $GLOBALS["thumbsdir"]."/".$short_path;
	       if (strcmp(strtoupper($file_ext),"JPG")==0) {
  		    thumb_create($path, $mkpath, $file_name);
	          }

               }
           }
       }
   closedir($dh);
   return $path_curent;
 }

 dir_path($startdir);
 
 ?>
