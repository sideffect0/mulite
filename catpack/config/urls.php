<?php
require_once("pathconf.php");
define("HOME_VIEW",VIEWDIR."home.php");
define("ADMIN","admin");
define("ADMIN_URL","/".ADMIN."/");
define("STATIC_URL","/static/");
define("MEDIA_URL","/media/");
$urls = array(
  "home"=>VIEWDIR."home.php",
  "submitname"=>VIEWDIR."submit.php"
);
?>
