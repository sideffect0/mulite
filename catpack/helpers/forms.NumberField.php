<?php
class NumberField{
 protected $max;
 protected $min;
 public function __construct($min,$max){
  if((isset($min)) && (isset($max))){
   $this->max = $max;
   $this->min = $min;
  }
 }

 public function validate($value){
  $result = array();
  $result["data"] = $value;
  $result["valid"] = TRUE;
/*
  $valid = filter_var(
     $value,
     FILTER_VALIDATE_INT,
       array(
        'options' => array(
            'min_range' => $this->min, 
            'max_range' => $this->max
         )
       )
  );*/
  $valid = (ctype_digit($value) && ($value>=($this->min)) && ($value<=($this->max))); 
  if($valid === FALSE){
   $result["valid"] = FALSE;
  }
  return $result;
 }
}

?>
