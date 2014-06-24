<?php
require_once("pathconf.php");
require_once(CONFIGDIR."controller.php");
require_once(INCLUDEDIR."error.php");

//General Controller
class BaseController{
  public function __construct(){
  }
  public function controllerError($error){
    $called = explode("Controller",get_called_class());
    $called = $called[0];
    $error = PHP_EOL."Error From Controller  : '$called'".PHP_EOL."Error was : $error";
    if(CONTROLLER_DEBUG){
     die($error);
    }
    else{
     error_log($error);
     raiseHTTP500(); 
    }
  }
}

//Interact with Model

 class DBController extends BaseController{
  protected $model;

  public function __construct(){
   $model = explode("Controller",get_called_class());
   $model = $model[0];
   $this->model = new $model();
  }
 }
?>
