<?php
 require_once("pathconf.php");
 require_once(CONTROLLERDIR."forms.submitform.php");
 $data = new SubmitForm($_POST);
 if($data->is_valid()){
  $value = $data->getValue("data");
  echo "$value";
 }else{
  echo "given invalid name (max 10 chars)";
 }
?>
