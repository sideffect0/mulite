<?php
require_once("pathconf.php");
require_once(CONFIGDIR."model.php");
require_once(INCLUDEDIR."error.php");

/*
  Conventions to  follow ..
  findMethodName -> function to get array of rows from database
  getMethodName -> function to get Single row
*/

class Model{
 protected $conn;
 protected $db_flag;
 protected $table; 
 public function __construct(){
  $this->conn = mysql_connect(DB_HOST.":".DB_PORT,DB_USER,DB_PSWD);
  $this->table = strtolower(get_called_class());	//assumes the table name same as the model name
  if(!($this->conn)){
     $this->modelError("Error in connecting..");
  }
  $this->db_flag = mysql_select_db(DB_NAME,$this->conn);
  if(!($this->db_flag)){
    $this->modelError("Error on selecting db..");
  }
 }
 public function insert_query($fields){
  return "insert into ".$this->table." (".$fields.")"." values ";
 }
 public function update_query($field,$value){
  return "update ".$this->table." set $field='$value'";
 }
 public function select_query($fields){
  return "select $fields from ".$this->table." ";
 }
 public function modelError($error){
  $called = get_called_class();
  $error = PHP_EOL."Error From Model  : '$called' ".PHP_EOL."Error was : $error".PHP_EOL;
  if(MODEL_DEBUG){
   die($error);
  }
  else{
   error_log($error);
   raiseHTTP500(); 
  }
 }
 public function __destruct(){
  mysql_close($this->conn);
 }
}

?>
