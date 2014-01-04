<?php
require_once("pathconf.php");
require_once(CONFIGDIR."helper.php");
class EmailField{
 public function validate($value){
  $value = filter_var($value, FILTER_SANITIZE_EMAIL);
  $filtered = filter_var($value, FILTER_VALIDATE_EMAIL);
  $result = array();
  $result["data"] = $value;
  if($filtered){
   $result["data"] = $filtered;
   $result["valid"] = TRUE;
   //Add More
   //checkdnsrr is not reliable :/ oops ..DoS
   global $EMAIL_BLACKLIST;
   $temp = explode('@',$value);
   $domain = strtolower($temp[1]);
   $blacklisted = $EMAIL_BLACKLIST;
   if(in_array($domain,$blacklisted)){
    $result["valid"] = FALSE;
   }
   return $result;
  }else{
   $result["valid"] = FALSE;
   return $result;
  }
 }
}
?>
