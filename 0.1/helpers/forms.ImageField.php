<?php
require_once("pathconf.php");
require_once(CONFIGDIR."helper.php");
require_once(HELPDIR."watermark.php");
class ImageField{
// protected $image_type; // required image type
 protected $image_size; // maximum image size (not resolution) in bytes
// protected $to_convert; // conver to image type or not
 public function __construct($image_size){
  if(isset($image_size)){
   $this->image_size = $image_size;
  }
 }

 public function is_image($path)
 {
    $ob = getimagesize($path);
    $image_type = $ob[2];

    if(in_array($image_type , array(IMAGETYPE_GIF , IMAGETYPE_JPEG ,IMAGETYPE_PNG , IMAGETYPE_BMP)))
    {
        return TRUE;
    }
    return FALSE;
 }
 public function validate($value,$field){
  $_file = $_FILES[$field];
  $_file_size = $_file['size'];
  $result["valid"] = TRUE;
  $result["data"]  = $_file['tmp_name'];
  if ((($_file['type'] == GIF) || ($_file['type'] == JPEG) || ($_file['type'] == PNG))){
   $image = $this->is_image($result["data"]);
   if($image){
    $name = md5(microtime()).rand();
    if(isset($this->image_size)){
     if($_file_size>($this->image_size)){
      $result["valid"] = FALSE;
     }else{
      $result["valid"] = TRUE;
      move_uploaded_file($result["data"],MEDIADIR.$name);
      $im = new imagick(MEDIADIR.$name);
      $im->setCompressionQuality(100); 
      $im->setImageFormat("jpeg");
      $im->writeImage(MEDIADIR.$name.".jpg");
      unlink(MEDIADIR.$name);
      $im->clear(); 
      $im->destroy(); 
      $result["data"] = $name.".jpg";
     }
    }else{
      $result["valid"] = TRUE;
      move_uploaded_file($result["data"],MEDIADIR.$name);
      $im = new imagick(MEDIADIR.$name);
      $im->setImageFormat("jpeg");
      $im->writeImage(MEDIADIR.$name.".jpg");
      unlink(MEDIADIR.$name);
      $im->clear(); 
      $im->destroy();
      watermark(MEDIADIR.$name.".jpg");
      $result["data"] = $name.".jpg";
    }
   }else{
    $result["valid"] = FALSE;
   }
  }else{
   $result["valid"] = FALSE;
  }
  return $result;
 }
}

?>
