<?php
require_once("pathconf.php");
require_once(CONFIGDIR."helper.php");
class DateField{
 public function validate($value){
  $filtered = explode("-",$value);
  $year = $filtered[0];
  $month = $filtered[1];
  $day = $filtered[2];
  $result = array();
  $result["data"] = $year."/".$month."/".$day;
  if(checkdate($month,$day,$year)){
   $result["valid"] = TRUE;
   return $result;
  }else{
   $result["valid"] = FALSE;
   return $result;
  }
 }
}
?>
