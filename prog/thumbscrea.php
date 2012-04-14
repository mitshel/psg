<?php header('Content-type: text/html; charset=utf-8')?>

<?php
 
 $startdir="/fserv/MY_DOCS/DOCUMS/Foto/_ФОТОАРХИВ";
 $startlen=strlen($startdir)+1;
 $thumbsdir="/home/www/photo/PhotoScanGallery/images";
 
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
             $path_curent .= "<b>".$mkpath."</b><br>";
	     $cmd = "mkdir -p '".$mkpath."'";
             $last_line = system($cmd, $retval);
		
 	     
             $path_curent .= dir_path($path);
           }
         else
           {
             if (is_file($path))
               {
	       $path_parts = pathinfo($path);
	       $file_dir = dirname($path);
	       $file_ext = $path_parts['extension'];
	       $file_name = basename($path);
/*               $path_curent .= $file_name." (".$file_dir.",".strtoupper($file_ext).")<br>";
*/
	       if (strcmp(strtoupper($file_ext),"JPG")==0) {
	            $path_curent .= $file_name." (".$file_dir.")<br>"; 
	          }
		  else
		  {
		    $path_curent .= '';
                  }

               }
           }
       }
   closedir($dh);
   return $path_curent;
 }

 echo "<b>".$startdir."</b><br>";
 echo dir_path($startdir);
 
 ?>
