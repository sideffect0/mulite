<?php
class ChoiceField{
 protected $options;
 public function __construct($options){
   $this->options = $options;
 }

 public function validate($value){
  $result = array();
  $result["data"] = strtolower($value);
  $result["valid"] = FALSE;
  if(in_array($result["data"],$this->options)){
   $result["valid"] = TRUE;
  }
  return $result;
 }
}

?>
