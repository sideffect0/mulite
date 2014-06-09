<?php

/**

@author : renlinx [ fb.com/renlinx ]

@url : https://github.com/renlinx007/mulite.git

**/
  
  $ncompat_version = version_compare(phpversion(),"5.4")<=0;
  if($ncompat_version){
        echo "THIS VERSION IS NOT COMPAT ONE";
  }
  require_once("pathconf.php");
  require_once(CONFIGDIR."urls.php");
  require_once(INCLUDEDIR."error.php");
  $filter1   = explode("?",$_SERVER["REQUEST_URI"]);
  $filter2   = explode("/",$filter1[0]);
  if($filter2[1]){
   $view = $filter2[1];
   $_PARAM = $filter2[2];
   if($view == "index.php"){
     $view = $filter2[2];
     if(isset($urls[$view])){
       require($urls[$view]);
     }
     else{
      require(HOME_VIEW);
     }
   }
   else{
     if(isset($urls[$view])){
       require($urls[$view]);
     }
     else{
       header('HTTP/1.0 404 Not Found');
       raiseHTTP404();
     }
   }
  }
  else{
   require(HOME_VIEW);
  }

?>
