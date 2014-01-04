<?php
class FileField{
 protected $max_size;
 public function __construct($max_size){
  if(isset($max_size)){
   $this->max_size = $max_size;
  }
 }

 public function validate($value){
  $result = array();
  $file = $_FILES["file"];
  $result["data"] = $file["tmp_name"];
  $result["valid"] = TRUE;
  if(isset($this->max_size)){
   $maxsize = $this->max_size;
   if($file["size"] > $maxsize){
    $result["valid"] = FALSE;
   }
  }
  if($result["valid"] == TRUE){
   $temp = $file["tmp_name"];
   $dir = MEDIADIR;
   $name = md5(uniqid($file["name"]).rand().microtime());
   move_uploaded_file($temp, "$dir/$name");
   $result["data"] = array(
     "name"=>$file["name"],
     "url"=>$name
   );
  }
  return $result;
 }
}

?>
