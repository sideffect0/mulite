<?php

/**

@author : renlinx [ fb.com/renlinx ]

@url : https://github.com/renlinx007/mulite.git

**/
  
  $ncompat_version = version_compare(phpversion(),"5.4")<=0;
  if($ncompat_version){
        die("THIS VERSION IS NOT COMPAT ONE");
  }
  require_once("pathconf.php");
  require_once(CONFIGDIR."urls.php");
  require_once(INCLUDEDIR."error.php");
  $filter1   = explode("?",$_SERVER["REQUEST_URI"]);
  $filter2   = explode("/",$filter1[0]);
  include_once(USERLIBDIR."autoload.php");
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
       $controller_class = $view."Controller";
       $controller_ob = new $controller_class();
       if(isset($_PARAM) && (strlen($_PARAM)!=0)){
	$controller_func = $_PARAM;
        $controller_ob->$controller_func();
       }else{
	$controller_ob->__index();
       }
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

  function import_module($module_name){
   require_once(USERLIBDIR.$module_name.".php");
  }

  function import_lib($lib_name){
    require_once(INCLUDEDIR.$lib_name.".php");
  }

  function import_model($model_name){
   require_once(MODELDIR.$model_name.".php");
  }

  function import_helper($helper_name){
   require_once(HELPDIR.$model_name.".php");
  }

  function render_template($template_name){ // Can Get Passed vars
     $__data_nop = func_get_args();
     $data = (object)$__data_nop[1];
     include_once(TEMPLATEDIR.$template_name.".tpl");
  }
?>
