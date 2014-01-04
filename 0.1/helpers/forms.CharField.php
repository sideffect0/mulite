<?php
class CharField{
 protected $max_length;
 public function __construct($max_length){
  if(isset($max_length)){
   $this->max_length = $max_length;
  }
 }

 public function validate($value){
  $result = array();
  $result["data"] = $value;
  if(isset($this->max_length)){
   $maxlength = $this->max_length;
   if(strlen($value)<=$maxlength){
    $result["valid"] = TRUE;
   }else{
    $result["valid"] = FALSE;
   }
  }else{
   $result["valid"] = TRUE;
  }
   return $result;
 }
}

?>
