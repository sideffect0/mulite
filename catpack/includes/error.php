<?php
  require_once("pathconf.php");
  include_once(CONFIGDIR."error.php");
  function raiseHTTP500(){
    header($_SERVER['SERVER_PROTOCOL'] . ' ' .STATUS_500,true,500);
    include_once(VIEW_500);
    exit;
  }
  function raiseHTTP404(){
   header($_SERVER['SERVER_PROTOCOL'] . ' ' .STATUS_404,true,404);
   include_once(VIEW_404);
   exit;
  }
  function raiseHTTP403(){
   header($_SERVER['SERVER_PROTOCOL'] . ' ' .STATUS_403,true,403);
   include_once(VIEW_403);
   exit;
  }
?>
