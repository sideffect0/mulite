<?php
 require_once("pathconf.php");
 class Form {
  protected $_is_valid;
  protected $_request;
  public function __construct($request,$file=""){
   $this->_request = $request;
   if(!empty($file)){
    $this->_file = $file;
   }
  }

  public function is_valid(){
   $fields = get_object_vars($this);
   unset($fields['_is_valid']);
   unset($fields['_request']);
   foreach($fields as $field=>$object){
    $_r = isset($this->_request[$field]);
    $_f = isset($_FILES[$field]);
    $_o = ($_r)||($_f);
    if($_o){
     $result = $object->validate($this->_request[$field],$field);
     $this->_request[$field] = $result["data"];
     if($result["valid"]){
      $this->_is_valid = TRUE;
     }else{
      $this->_is_valid = FALSE;
      break;
     }
    }else{
      $this->_is_valid = FALSE;
      break;
    }
   }
     return $this->_is_valid;
  }

  public function getValue($field){
   return $this->_request[$field];
  }
 }


/*

 //Sample Field Class
 class G{

  // Currently validate method is must one
  public function validate($value){
   if($value=='eng'){
    return TRUE;
   }
   else{
    return FALSE;
   }
  }
 }


//Sample Form Class

 class E extends FormController{
  public $e;

  public function __construct($request){
    parent::__construct($request);
    $this->e = new G();
  }
 }


//How to Use it 3:)

if($_SERVER['REQUEST_METHOD'] == 'GET'){
 $j = new E($_GET);
 if($j->is_valid()){
  echo "Done";
 }
 else{
  echo "No";
 }
}

*/
?>
