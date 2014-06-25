<?php
require_once("pathconf.php");
define("HOME_VIEW",VIEWDIR."home.php");
define("ADMIN","admin");
define("ADMIN_URL","/".ADMIN."/");
define("STATIC_URL","/static/");
define("MEDIA_URL","/media/");
/*
   VIEW NAME => "view php file path"
*/
$urls = array(
  "home"=>CONTROLLERDIR."home.php",
  "test"=>CONTROLLERDIR."test_controller.php"
);
?>
