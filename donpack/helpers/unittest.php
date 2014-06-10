function time_start(){
   $mtime = microtime(); 
   $mtime = explode(" ",$mtime);
   $mtime = $mtime[1] + $mtime[0];
   $GLOBALS['starttime']  = $mtime;
}

function time_end(){
 	$mtime = microtime(); 
 	$mtime = explode(" ",$mtime); 
 	$mtime = $mtime[1] + $mtime[0]; 
 	$endtime = $mtime; 
 	$totaltime = ($endtime - $starttime); 
        echo "taken".$totaltime." seconds";
} 

